function getpwd(th) {
	var temp = th.id.split('_');
	var pid = temp[1];
	var act = jQuery(th).attr('class');
	var security_nonce = MyAjax.ajax_public_nonce;
	var crypwd = jQuery('#user_pwd' + pid).val();
	if (act == 'decrypt') {
		var key = crypwd;
		var btn_action = act;
	} else {
		var did = jQuery('#' + th.id).data('id');
		var key = crypwd;
		var btn_action = act;
	}
	jQuery.ajax({
		url: MyAjax.ajaxurl,
		method: "POST",
		data: { user_pwd: key, saction: btn_action, did: did, security_nonce:security_nonce, module: 'password', action: "decrypt_pass" },
		success: function (rest) {
			var input = jQuery('#user_pwd' + pid);
			if (input.attr("type") == "password") {
				jQuery('#' + th.id).find('i').removeClass("fa-eye").addClass('fa-eye-slash');
				input.attr("value", rest);
				input.attr("type", "text");
				jQuery('#' + th.id).attr("class", "encrypt"); // decrypt
			} else {
				jQuery('#' + th.id).find('i').removeClass("fa-eye-slash").addClass('fa-eye');
				input.attr("type", "password");
				input.attr("value", rest);
				jQuery('#' + th.id).attr("class", "decrypt");
			}
		}
	});
}

jQuery(document).ready(function () {

	var clipboard = new ClipboardJS('.copy_clipboard');
    var security_nonce = MyAjax.ajax_public_nonce;
	//add password from reset
	jQuery('#add_user_pass').click(function () {
		jQuery('#pwds_form')[0].reset();
		jQuery('#user_name').css('border', '1px solid #7e8993');
		jQuery('#user_err').css('display', 'none');
		jQuery('.modal-title').html("<i class='fa fa-plus'></i>Add Password");
		jQuery('#saction').val('Add');
		jQuery('#btn_action').val('Add');
		jQuery('#saction').attr('disabled', false);
		jQuery('#pass_category').removeClass('cat_wrng');
		jQuery('#slct_wrng').css('display', 'none');
	});
	jQuery('.close').click(function () {
		jQuery('#wcbnl_overlay').fadeOut(1000);

	});

	var userdataTable = jQuery('#pwds_data').DataTable({
		"processing": true,
		"language": {
			processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw text-white"></i>',
			"lengthMenu": "Show _MENU_"
		},
		"serverSide": true,
		"ajax": {
			url: MyAjax.ajaxurl,
			type: "POST",

			data: {
				"module": 'password',
				"action": 'get_new_pass',
				'security_nonce':security_nonce
			}
		},
		"columnDefs": [
			{
				"targets": [1, 2],
				"orderable": false,
			},
			{width    : "250px", targets: [6]},
		],

		"pageLength": 10,
	});

	jQuery(document).on('submit', '#pwds_form', function (event) {
		event.preventDefault();
		let encryption = new Encryption();//include encrypt js class
			
		jQuery('#saction').attr('disabled', false);
		var user_name = jQuery('#user_name').val();
		var user_email = jQuery('#user_email').val();
		var user_pass = jQuery('#user_password').val();
		var pass_cat = jQuery('#pass_category').val();
		var user_note = jQuery('#user_note').val();
		var user_url = jQuery('#user_url').val();
		var pass_id = jQuery('#pwds_id').val();
		var enc_key = jQuery('#setting_key_enc').val();
		var btn_action = jQuery('#btn_action').val();
		if (pass_cat === '') {
			jQuery('#pass_category').addClass('cat_wrng');
			jQuery('#slct_wrng').css('display', 'block');			
		} else {
			
			var enc_pass = encryption.encrypt(user_pass, enc_key);//encrypt password by js
			jQuery.ajax({
				url: MyAjax.ajaxurl,
				method: "POST",
				data: { ency: enc_pass, pass_id: pass_id, user_name: user_name, user_email: user_email, user_url: user_url, pass_cat: pass_cat, user_note: user_note, btn_action: btn_action, security_nonce:security_nonce, action: 'post_new_pass' }, //form_data,
				dataType: "json",
				success: function (data) {
					var blank = data.blnkspc;
					if (blank === "blank") {
						jQuery('#user_name').css('border', '1px solid red');
						jQuery('#user_err').css('display', 'block');
					} else if (data) {
						jQuery('#pwdsModal').modal('hide');
						jQuery('#wcbnl_overlay').show();					
						
						jQuery('#wcbnl_overlay').css("z-index", "1");
						jQuery('#pwdsModal').css("z-index", "1050");
						jQuery('#pwds_form')[0].reset();
						jQuery('#alert_action').fadeIn().html('<div class="alert alert-success">' + data + '</div>');
						jQuery('#saction').attr('disabled', false);

						jQuery('#pass_category').removeClass('cat_wrng');
						jQuery('#slct_wrng').css('display', 'none');
						jQuery('#wcbnl_overlay').fadeOut(1000);
						userdataTable.ajax.reload();
					} else {
						alert('There is some problem. Data is not added.');
					}
				},
				error: function () {
					alert("You do not have any change. Please change first");
					jQuery('#wcbnl_overlay').fadeOut(1000);
					jQuery('#saction').attr('disabled', false);
				}
			});
		}
	});

	jQuery(document).on('click', '.update', function () {
		jQuery('#wcbnl_overlay').show();
		jQuery('#user_name').css('border', '1px solid #ddd');
		jQuery('#user_err').css('display', 'none');
		jQuery('#saction').attr('disabled', false);
		var pass_id = jQuery(this).attr("id");
		var btn_action = 'fetch_single';	
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			method: "POST",
			data: { pass_id: pass_id, btn_action: btn_action,  security_nonce:security_nonce, module: 'password', action: 'edit_pass' },
			dataType: "json",
			success: function (data) {
				jQuery('#pwdsModal').modal('show');
				jQuery('#user_name').val(data.user_name);
				jQuery('#user_email').val(data.user_email);
				jQuery('#pass_category').val(data.user_category);
				jQuery('#user_password').val(data.user_password);
				jQuery('#user_note').val(data.user_note);
				jQuery('#user_url').val(data.user_url);
				jQuery('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Password");
				jQuery('#pwds_id').val(pass_id);
				jQuery('#saction').val('Edit');
				jQuery('#btn_action').val("Edit");
				jQuery('#user_password').attr('required', false);
				jQuery('#wcbnl_overlay').fadeOut(1000);
			},
		});
	});

	jQuery(document).on('click', '.note_preview', function () {
		jQuery('#wcbnl_overlay').css('display','block');
		jQuery('.modal-title').html('<i class="fa fa-sticky-note-o" aria-hidden="true"></i> Note');
		var pass_id = jQuery(this).attr("id");
		var btn_action = 'fetch_single';	
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			method: "POST",
			data: { pass_id: pass_id, btn_action: btn_action, security_nonce:security_nonce, action: 'edit_pass' },
			dataType: "json",
			success: function (data) {
				jQuery('#user_note_view').val(data.user_note);
				jQuery('#pwdsnoteModal').modal('show');
				jQuery('#wcbnl_overlay').fadeOut(1000);
			},
		});
	});



	jQuery(document).on('click', '.dlt', function () {
		//jQuery('#wcbnl_overlay').show();
		var pass_id = jQuery(this).attr('id');
		var btn_action = 'Delete';
		Swal.fire({			
			title: "Are you sure you want to do this?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!',

			preConfirm: (login) => {
				jQuery.ajax({
					url: MyAjax.ajaxurl,
					method: "POST",
					dataType: "json",
					data: { pass_id: pass_id, btn_action: btn_action, security_nonce:security_nonce, module: 'password', action: 'post_new_pass' },
					success: function (data) {
						jQuery('#alert_action').fadeIn().html('<div class="alert alert-info">' + data + '</div>');						
						userdataTable.ajax.reload();
					},
					error: function () {
						alert('failure');
					}
				});
			},
		  }).then((result) => {
			if (result.value) {
			  Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Deleted',
				text:'Your file has been deleted.',
				showConfirmButton: false,
				timer: 2000,
				
			  });
			}
		  });
	});

	//before add see password	
	jQuery(".toggle-password").click(function () {
		jQuery(this).toggleClass("fa-eye fa-eye-slash");
		var input = jQuery(jQuery(this).attr("toggle"));
		if (input.attr("type") === "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});
	// 	jQuery('[data-toggle="tooltip"]').tooltip();

});
//download password note
function pwdms_save_note(textToWrite, fileNameToSaveAs) {
	var textFileAsBlob = new Blob([textToWrite], { type: 'text/plain' });
	var downloadLink = document.createElement("a");
	downloadLink.download = fileNameToSaveAs;
	downloadLink.innerHTML = "Download File";
	if (window.webkitURL != null) {
		// Chrome allows the link to be clicked
		// without actually adding it to the DOM.
		downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
	}
	else {
		// Firefox requires the link to be added to the DOM
		// before it can be clicked.
		downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
		downloadLink.onclick = destroyClickedElement;
		downloadLink.style.display = "none";
		document.body.appendChild(downloadLink);
	}

	downloadLink.click();
}