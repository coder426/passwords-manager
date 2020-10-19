
jQuery(document).ready(function () {
	/*
	**open add category popup
	*/
	jQuery('#add_button').click(function () {
		jQuery('#category_form')[0].reset();
		jQuery('.modal-title').html("<i class='fa fa-plus'></i>Add Category");
		jQuery('#saction').val('Add');
		jQuery('#btn_action').val('Add');
		jQuery('#cat_error').css("display", 'none');
		jQuery('#category_name').removeClass("border-danger");
	});
	/*
	**category datatable value store
	*/
	var security_nonce = MyAjax.ajax_public_nonce;
	var categorydataTable = jQuery('#category_data').DataTable({
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
				"module": 'categories',
				"action": 'get_new_cats',
				'security_nonce':security_nonce
			}
		},
		"columnDefs": [
			{
				"targets": [0, 1, 2, 3],
				"orderable": false,
			},
		],
		"pageLength": 10
	});
	/*
	**category submit 
	*/
	jQuery(document).on('submit', '#category_form', function (event) {
		event.preventDefault();

		jQuery('#wcbnl_overlay').show();
		jQuery('#wcbnl_overlay').css("z-index", "99999");
		jQuery('#categoryModal').css("z-index", "0");
		jQuery('#saction').attr('disabled', false);
		var cat_name = jQuery('#category_name').val();
		var category_id = jQuery('#category_id').val();
		var btn_action = jQuery('#btn_action').val();
		var security_nonce = MyAjax.ajax_public_nonce;
		 
	
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			method: "POST",
			data: { category: cat_name, category_id: category_id, btn_action: btn_action,security_nonce:security_nonce, action: 'post_new_cats' }, //form_data,
			dataType: "json",
			success: function (data) {
				var x = data.resp;
				var y = data.ecode;
				var z = data.al_exist;
				if (x === 'Success') {
					jQuery('#wcbnl_overlay').fadeOut(1000);
					jQuery('#categoryModal').modal('hide');
					jQuery('#wcbnl_overlay').css("z-index", "1");
					jQuery('#categoryModal').css("z-index", "1050");
					jQuery('#category_form')[0].reset();
					jQuery('#alert_action').fadeIn().html('<div class="alert alert-success">' + data + '</div>');
					jQuery('#saction').attr('disabled', false);
					jQuery('#cat_error').css("display", 'none');
					jQuery('#category_name').removeClass("border-danger");

					categorydataTable.ajax.reload();

				} else if (y === 'special character') {
					jQuery('#cat_error').css("display", 'block');
					jQuery('#category_name').addClass("border-danger");
					jQuery('#cat_error').html("Don't use special characters or blank values!");
					jQuery('#saction').attr('disabled', false);
					jQuery('#wcbnl_overlay').fadeOut(1000);
					jQuery('#wcbnl_overlay').css("z-index", "1");
					jQuery('#categoryModal').css("z-index", "1050");

				} else if (z === 'Exists') {
					jQuery('#category_form')[0].reset();
					jQuery('#categoryModal').modal('hide');
					jQuery('#wcbnl_overlay').fadeOut(1000);
					jQuery('#wcbnl_overlay').css("z-index", "1");
					jQuery('#categoryModal').css("z-index", "1050");

				} else {
					jQuery('#cat_error').css("display", 'block');
					jQuery('#category_name').addClass("border-danger");
					jQuery('#cat_error').html("Category Already Exists ");
					jQuery('#saction').attr('disabled', false);
					jQuery('#wcbnl_overlay').fadeOut(1000);
					jQuery('#wcbnl_overlay').css("z-index", "1");
					jQuery('#categoryModal').css("z-index", "1050");

				}
			},
			error: function () {
				alert('failure');
				jQuery('#saction').attr('disabled', false);
				jQuery('#wcbnl_overlay').fadeOut(1000);
			}
		});
	});
	/*
	**category update
	*/
	jQuery(document).on('click', '.upcate', function () {
		jQuery('#cat_error').css("display", 'none');
		jQuery('#category_name').removeClass("border-danger");
		var category_id = jQuery(this).attr("id");
		var btn_action = 'fetch_single';
		var security_nonce = MyAjax.ajax_public_nonce;
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			method: "POST",
			data: { category_id: category_id, btn_action: btn_action, security_nonce:security_nonce, module: 'categories', action: 'edit_cats' },
			dataType: "json",
			success: function (data) {
				jQuery('#categoryModal').modal('show');
				jQuery('#category_name').val(data.category_name);
				jQuery('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Category");
				jQuery('#category_id').val(category_id);
				jQuery('#saction').val('Edit');
				jQuery('#btn_action').val("Edit");
				
			},
		});
	});

	/*
	**category delete
	*/
	jQuery(document).on('click', '.delete', function () {
		var btn_action = 'Delete';
		var wrng_id = jQuery(this).attr('id');
		var security_nonce = MyAjax.ajax_public_nonce;		
		Swal.fire({			
			text: "Deleting this category will also delete all passwords belonging to this category. Are you sure you want to do this?",
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
					data: { category_id: wrng_id, btn_action: btn_action,security_nonce:security_nonce, module: 'categories', action: 'post_new_cats' },
					success: function (data) {
						jQuery('#alert_action').fadeIn().html('<div class="alert alert-info">' + data + '</div>');
						categorydataTable.ajax.reload();
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
			  })
			}
		  });
	});
});