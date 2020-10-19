<?php
//PMS Settings

if ( ! function_exists('pms_save_setting') ) {
	function pms_save_setting(){
		$btn_action		=	sanitize_text_field($_POST['btn_action']);
		if(isset($btn_action) && !wp_verify_nonce($_POST['security_nonce'], basename(__FILE__))){  
			global $wpdb;			
			/*
			**Add new encryption password key detail in database
			*/		
			if($btn_action == 'Save'){				
				$pwd= sanitize_text_field($_POST['setting_key']);
				if(isset($pwd)	&&	!empty($pwd)){
					if (empty($pwd)){
						$requ['ecode'] = "special character";
						echo json_encode($requ);
						die;
					}else{
						update_option('pms_encrypt_key',$pwd);						
						//execute query
						if(get_option('pms_encrypt_key',true) == true){
							$requ['requ'] = $pwd;
						}
						else{			
							$requ['requ'] = "error";	
						}
					}
				}
				else{			
					$requ['requ'] = "error";	
				}
				echo json_encode($requ);
				die;
			}
			/*
			**Add dark heme color detail in database
			*/		
			elseif($btn_action == 'dark_button'){

				delete_option('pms_dark_button');

				$dark_btn = sanitize_text_field($_POST['darkbtn']);
				if(isset($dark_btn)	&&	!empty($dark_btn) && !wp_verify_nonce($_POST['security_nonce'], basename(__FILE__))){					
					update_option('pms_dark_button',$dark_btn);	
				}
				if(get_option('pms_dark_button',true) == true){
					$btnclr['dark_btn'] = $dark_btn;	
				}
				else{			
					$btnclr['dark_btn'] = "Error";	
				}
				echo json_encode($btnclr);
				die;
			}

			/*
			**Add light theme color detail in database
			*/
			elseif($btn_action == 'light_button'){

				delete_option('pms_dark_button');

				$light_button = sanitize_text_field($_POST['light_button']);
				if(isset($light_button)	&&	!empty($light_button) && !wp_verify_nonce($_POST['security_nonce'], basename(__FILE__))){

					update_option('pms_dark_button',$light_button);						

				}
				if(get_option('pms_dark_button',true) == true){
					$light['light_btn'] = $light_button;	
				}
				else{			
					$light['light_btn'] = "Error";	
				}
				echo json_encode($light);
				die;
			}
		}
	}
	add_action('wp_ajax_pms_save_setting', 'pms_save_setting');
}

add_action('wp_ajax_pms_send_email_help','pms_send_email_help');
function pms_send_email_help(){
	$btn_action		=	sanitize_text_field($_POST['btn_action']);
	if(isset($btn_action)){  
		if($btn_action == 'send_email' && !wp_verify_nonce($_POST['security_nonce'], basename(__FILE__))){
			$subject = sanitize_text_field(str_replace('-',' ',$_POST['form_type']));
			$subject = ucfirst($subject);
			date_default_timezone_set('Asia/Kolkata');
			$date = date('d-M-Y H:i');
			$subjects = 'Password Management -'.$subject.' - '.$date;
			$email_from = sanitize_email($_POST['fdbk_email']);
			$headers[] = 'Content-type: text/html; charset=utf-8';
			$headers[] = 'From:' . $email_from;
			$body = nl2br($_POST['fdbk_msg']);
			$sent = wp_mail( "coder426@gmail.com", $subjects, $body, $headers );
			if($sent)			{
				echo json_encode("Success");				
			}
		}
	}
	die;
}

?>