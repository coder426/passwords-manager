jQuery(document).ready(function () {
	'use strict';
	var security_nonce = MyAjax.ajax_public_nonce;
	jQuery(document).on('submit', '#setting_form', function (event) {
		event.preventDefault();
		jQuery('#saction').attr('disabled', 'disabled');
		var setting_key = jQuery('#setting_key').val();
		var btn_action = jQuery('#btn_action').val();
		
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			method: "POST",
			data: { setting_key: setting_key, btn_action: btn_action,security_nonce:security_nonce, module: 'settings', action: 'pms_save_setting' }, //form_data,
			dataType: "json",
			success: function (data) {
				var spl_char = data.ecode;
				if (spl_char === 'special character') {
					jQuery('#error_show').css('display', 'block');
					jQuery('#saction').attr('disabled', false);
				}
				else {
					jQuery(".stng_error").hover(function () {
						jQuery('.wrngTooltip').css('visibility', 'visible');
					}, function () {
						jQuery('.wrngTooltip').css('visibility', 'hidden');
					});
					//jQuery('#msg_show').html('Do not try to change it again else all your older passwords will stop working.');
					jQuery('#saction').css('display', 'none');
					jQuery('#setting_key').addClass('stng_error');
					jQuery('#key_warning').css('display', 'none');
					jQuery('#setting_key').val(data.requ);
					jQuery('#setting_key').prop('readonly', true);
					jQuery('#generate').css('display', 'none');
					jQuery('#saction').addClass('btn-secondary');
					jQuery('#saction').removeClass('btn-info');
					jQuery('#empty_64').css('display', 'none');
					jQuery('#empty_31').css('display', 'block');
				}
			},
			error: function () {
				alert('failure');
			}
		});
		jQuery(document).on('click', '#generate', function (event) {
			event.preventDefault();
			jQuery('#error_show').css('display', 'none');
		});
	});



	jQuery(document).on('click', '#dark_button', function (event) {
		jQuery('#wcbnl_overlay').show();
		var darkbtn = jQuery('#dark_buttn').val();
		var btn_action = 'dark_button';
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			method: "POST",
			data: { darkbtn: darkbtn, btn_action: btn_action,security_nonce:security_nonce, module: 'settings', action: 'pms_save_setting' }, //form_data,
			dataType: "json",
			success: function (data) {
				var drkbtn = data.dark_btn;
				var temp = drkbtn.split('_');
				var bg = temp[0];
				var txt = temp[1];
				jQuery('#table-responsive').removeClass('bg-Secondary text-Secondary');
				jQuery('#table-responsive').addClass(bg);
				jQuery('#table-responsive').addClass(txt);
				jQuery('#pwds_data .pass_inp').removeClass('text-Secondary');
				jQuery('#pwds_data .pass_inp').addClass(txt);
				jQuery('#wcbnl_overlay').fadeOut(1000);
			},
		});
	});

	jQuery(document).on('click', '#light_button', function (event) {
		jQuery('#wcbnl_overlay').show();
		var light_button = jQuery('#light_buttn').val();
		var btn_action = 'light_button';
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			method: "POST",
			data: { light_button: light_button, btn_action: btn_action,security_nonce:security_nonce, module: 'settings', action: 'pms_save_setting' }, //form_data,
			dataType: "json",
			success: function (data) {
				var light_btn = data.light_btn;
				var temp = light_btn.split('_');
				var bg = temp[0];
				var txt = temp[1];
				jQuery('#table-responsive').removeClass('bg-dark text-white');
				jQuery('#table-responsive').addClass(bg);
				jQuery('#table-responsive').addClass(txt);
				jQuery('.pass_inp').removeClass('text-white');
				jQuery('.pass_inp').addClass(txt);
				jQuery('#wcbnl_overlay').fadeOut(1000);

			},
		});
	});
	jQuery('input[class^="style"]').click(function () {
		var jQuerythis = jQuery(this);
		if (jQuerythis.is(".style1")) {
			if (jQuery(this).prop("checked") === true) {
				jQuery(".style2").prop({ checked: false });

			}
			else if (jQuery(this).prop("checked") === false) {
				jQuery(".style1").prop({ checked: true });
			}
		}
		else if (jQuerythis.is(".style2")) {
			if (jQuery(this).prop("checked") === true) {
				jQuery(".style1").prop({ checked: false });

			}
			else if (jQuery(this).prop("checked") === false) {
				jQuery(".style1").prop({ checked: true });
			}
		}
	});

	// feedback form js

	// Reset feedback form
	jQuery('.pwdms_fdtype').attr('checked', false);

	// Review
	jQuery('.pwdms_fdtypes').click(function (e) {

		var radio = jQuery(this).val();
		jQuery('#form_type').val(radio);
		if (radio == 'suggestions') {
			// Hide other options
			jQuery('#pwdms_fdtype_1, #pwdms_fdtype_3, #pwdms_fdtype_4').closest('li').hide();

			// change placeholder message
			jQuery('.pwdms_fdback_form').find('.pwdms-feedback-message').attr('placeholder', 'Leave plugin developers any feedback here...');

			// Show feedback form
			jQuery('.pwdms_fdback_form').fadeIn();

		}
		else if (radio == 'help-needed') {
			// Hide other options
			jQuery('#pwdms_fdtype_1, #pwdms_fdtype_2, #pwdms_fdtype_4').closest('li').hide();

			// change placeholder message
			jQuery('.pwdms_fdback_form').find('.pwdms-feedback-message').attr('placeholder', 'Leave plugin developers any feedback here...');

			// Show feedback form
			jQuery('.pwdms_fdback_form').fadeIn();
		}
		else if (radio == 'review') {
			var rev_url = jQuery('#pwdms_fdtype_lnk1').attr("href");
			//window.location.href = rev_url;
			window.open(rev_url, '_blank');
		}
		else if (radio == 'more-info') {
			var rev_url = jQuery('#pwdms_fdtype_lnk4').attr("href");
			//window.location.href = rev_url;
			window.open(rev_url, '_blank');
		}
	});
	/*
	**Start support form js
	**/
	//feedback term conditions checked or not
	jQuery('#pwdms_fdb_terms').click(function () {
		if (jQuery(this).prop("checked") == true) {
			jQuery('.export_error').remove();
		}
	});
	jQuery("#pwdms_sprt_form #pwdms-feedback-email,#pwdms_sprt_form #pwdms-feedback-message").keyup(function () {
		if (jQuery(this).val().length === 0) {
			jQuery(this).css('border-left', '3px solid red');
			return false;
		} else {
			jQuery(this).css('border-left', '3px solid green');
			return true;

		}
	});
	jQuery(document).on('submit', '#pwdms_sprt_form', function () {
		//event.preventDefault();
		var fdbk_email = jQuery.trim(jQuery('#pwdms-feedback-email').val());
		var fdbk_msg = jQuery.trim(jQuery('#pwdms-feedback-message').val());
		if (jQuery('#pwdms_fdb_terms').is(':checked') == true && fdbk_email.length > 1 && fdbk_msg.length > 1) {
			jQuery('.export_error').remove();
			jQuery('#sms_loading').show();
			jQuery('#pwdms-feedback-email,#pwdms-feedback-message').css('border-left', '3px solid green');
			var form_type = jQuery('#form_type').val();
			var fdbk_trm = jQuery('#pwdms_fdb_terms').val();
			jQuery.ajax({
				url: MyAjax.ajaxurl,
				method: "POST",
				data: { form_type: form_type, fdbk_email: fdbk_email, fdbk_msg: fdbk_msg, fdbk_trm: fdbk_trm,security_nonce:security_nonce, btn_action: 'send_email', action: 'pms_send_email_help' }, //form_data,
				success: function (data) {
					if (!data) {
						jQuery('#sms_loading').hide();
						jQuery('.email_response_fail').remove();
						jQuery('.pwdms_sbmt_buttons').after('<div class="mt-2"><p class="email_response_fail">Failed to send an e-mail. Please contact us directly on hirewebxperts.com.</p></div>');
					} else {
						jQuery('#sms_loading').hide();
						jQuery('.email_response_pass').remove();
						jQuery('.email_response_fail').remove();
						jQuery('#pwdms_sprt_form')[0].reset();
						jQuery('#pwdms-feedback-message,#pwdms-feedback-email').css('border-left', '3px solid red');
						jQuery('.pwdms_sbmt_buttons').after('<div class="mt-2"><p class="email_response_pass">Email sent successfully.</p></div>');

					}
				},
			});
			return false;

		} else {
			jQuery('.export_error').remove();
			if (fdbk_email.length == 0) {
				jQuery('#pwdms-feedback-email').css('border-left', '3px solid red');
			} if (fdbk_msg.length == 0) {
				jQuery('#pwdms-feedback-message').css('border-left', '3px solid red');
			}
			jQuery('.pwdms_fdb_terms_s').after('<div class="export_error"><p>Please fill all field properly.</p></div>');
			return false;

		}

	});

	// Cancel feedback form
	jQuery('#pwdms_fd_cancel').click(function (e) {
		e.preventDefault();
		jQuery('.pwdms_fdback_form').fadeOut(function () {
			jQuery('.pwdms_fdtypes').attr('checked', false).closest('li').show();
		});
		jQuery('.email_response_fail').remove();
		jQuery('.email_response_pass').remove();
		jQuery("#pwdms_sprt_form")[0].reset();
	});
	//end support form

	jQuery('#pwdms_import_btn').click(function (e) {	
		var upfl_val = jQuery("#pwdms_csvim_upload_file").val();
		if (upfl_val == "") {
			e.preventDefault();
			jQuery('.export_error').remove();
			jQuery("#pwdms_import_btn").after('<div class="export_error"><p>No File Upload</p></div>');
		} else {
			jQuery('.export_error').remove();
			var encry_key = jQuery("#setting_key_hdn").val();
			if (encry_key == "") {
				e.preventDefault();
				jQuery('.export_error').remove();
				jQuery(this).after('<div class="export_error"><p>Please add encryption key in setting page.</p></div>');
			} else {
				jQuery('.export_error').remove();
			}
		}
	});
});




