<?php
/*
**Enter password data in datatable
*/	
if ( ! function_exists('get_new_pass') ) {
	function get_new_pass(){  	
		global $wpdb, $posts;
		$prefix = $wpdb->prefix;
		if(!wp_verify_nonce($_POST['security_nonce'], basename(__FILE__))){
			/**
			**options table fetch record
			*/
			$category_name = strtolower(sanitize_text_field($_POST['cat_name']));
			if(isset($category_name) && !empty($category_name)){
				$txt =  "text-white";
				$bg == 'bg-dark';
				$get_cate	=	'';
				$get_cate = $wpdb->get_var("SELECT id FROM {$prefix}pms_category WHERE category LIKE '%$category_name%'");
				$query = "SELECT * FROM {$prefix}pms_passwords WHERE category_id =".$get_cate;
				$pass_rec = $wpdb->get_results($wpdb->prepare($query));	
				$array = json_decode(json_encode($pass_rec), True);
				$pass_c	=	count($pass_rec);			
			}else{			
				$tblclr  = get_option('pms_dark_button'); 
				$str_arr = explode ("_", $tblclr);
				$bg = $str_arr[0];
				$txt = $str_arr[1];
				if($bg == 'bg-dark'){
					$txt =  "text-white";
				}else{
					$txt =  "text-Secondary";
				}
				$query = "SELECT * FROM {$prefix}pms_passwords";
				$pass_rec = $wpdb->get_results($wpdb->prepare($query));		
				$pass_c	=	count($pass_rec);

				$searchVal	=	sanitize_text_field($_POST["search"]["value"]);

				if(isset($searchVal))
				{
					if( !empty($_POST["search"]["value"]) ){
						$query .= ' WHERE user_name LIKE "%'.ucfirst($searchVal).'%" ';

					}else{
						$query .= ' WHERE 1 ';

					}
				}				
			}//end else

			$startLimit		=	absint($_POST['start']);
			$lengthLimit	=	absint($_POST['length']);


			if(	isset($lengthLimit)	&&	($lengthLimit != -1)	&&	isset($startLimit)	)
			{
				$query .= ' LIMIT ' . $startLimit . ', ' . $lengthLimit;
			} else {
				$query .= ' LIMIT ' . 0 . ', ' . 10;
			}
			$qrs_pass = $wpdb->get_results($wpdb->prepare($query));

			$data = array();
			$array = json_decode(json_encode($qrs_pass), True);
			$rowCount	=	absint($_POST['start']);
			foreach($array as $row){
				$rowCount++;
				$id = absint($row['pass_id']);
				$cId = absint($row['category_id']);

				/* Fetch Category from category*/
				$query  = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$prefix}pms_category where id = $cId"));
				$category = $query->category;
				$category = ucfirst(esc_html($category));
				$sub_array = array();
				$sub_array[] = $rowCount;//$row['pass_id']
				$sub_array[] = ucfirst(esc_html($row['user_name']));
				$sub_array[] = esc_html($row['user_email']);
				$sub_array[] = '<input id="user_pwd'.$id.'" name="user_pwd" type="password" value="'.$row['user_password'].'" class="pass_inp border-0 '.$txt.'" readonly="readonly" style="box-shadow: none;background: none;">';
				$sub_array[] = '<a href="'.esc_url($row['url']).'" target="_blank">'.esc_url($row['url']).'</a>';
				$sub_array[] =$category;
				$sub_array[] = '<div class="act_box"><a href="javascript:void(0);" data-id="'.$id.'" id="s_'.$id.'" class="decrypt" onclick="getpwd(this)" title="View password"><i class="fa fa-eye text-success"></i></a>&nbsp;<a href="#" name="update" id="'.$id.'" class="update" title="Edit password"><span class="dashicons dashicons-edit text-warning"></span></a>&nbsp;<a href="#" name="dlt" class="dlt" id="'.$id.'" title="Delete password"><span class="dashicons dashicons-trash text-danger"></span></a>&nbsp;<a href="#" name="note_preview" id="'.$id.'" class="note_preview" title="Preview your note"><span class="dashicons dashicons-clipboard"></span></a>&nbsp;<a href="javascript:void(0);" data-clipboard-action="copy" data-clipboard-target="#user_pwd'.$id.'" id="'.$id.'" class="copy_clipboard" title="Password copy to clipboard"><span class="dashicons dashicons-admin-page"></span></a>
						</div>';
				$data[] = $sub_array;	
			}// end foreach
			$output = array(
				"recordsTotal"  	=>  $pass_c,
				"recordsFiltered" 	=> 	$pass_c,
				"data"				=>	$data
			);	
			echo json_encode($output);
			die;
		}
	}	
}

//add new password
if ( ! function_exists('post_new_pass') ) {	
	function post_new_pass(){
		$btn_action		=	sanitize_text_field($_POST['btn_action']);
		if(isset($btn_action) && !wp_verify_nonce($_POST['security_nonce'], basename(__FILE__)) ){  
			global $wpdb;
			$prefix = $wpdb->prefix;
			/*
			**Add new category detail in database
			*/		
			if($btn_action == 'Add'){				
				$user_name  = sanitize_text_field($_POST['user_name']);
				$user_email = sanitize_email($_POST['user_email']);
				$pass_cate  = absint($_POST['pass_cat']);
				$encry_pass = sanitize_text_field($_POST['ency']);
				$user_note  = $_POST['user_note'];
				$user_url 	= esc_url_raw($_POST['user_url']);

				if(($user_name == '') || ($user_email == '') || ($pass_cate == '')){
					$resp['blnkspc'] = "blank";
					echo json_encode($resp);
					die;
				}else{
					if((isset($user_name) && !empty($user_name)) || (isset($user_email) && !empty($user_email)) || (isset($pass_cate) && !empty($pass_cate)) || (isset($encry_pass) && !empty($encry_pass))){

						$table_name = $prefix . "pms_passwords"; 
						$final_rslt	=	$wpdb->insert(
							$table_name, 
							array('user_name' 		=> $user_name,
								  'user_email' 		=> $user_email,
								  'user_password' 	=> $encry_pass,
								  'category_id' 	=> $pass_cate,
								  'note'			=> $user_note,
								  'url' 			=> $user_url,
								 ) , 
							array('%s','%s','%s','%d','%s') 
						);
						//execute query
						if($final_rslt){
							echo json_encode($final_rslt);
						}else{ 
							echo "Failed";
						}die;
					}
				}
			}
			/*
			**Edit password detail in database
			*/					
			elseif($btn_action == 'Edit'){
				$pwd = sanitize_text_field($_POST['ency']);
				$user_name  = sanitize_text_field($_POST['user_name']);
				$user_email = sanitize_email($_POST['user_email']);
				$pass_cate  = sanitize_text_field($_POST['pass_cat']);
				$user_note 	= $_POST['user_note'];
				$user_url 	= esc_url($_POST['user_url']);				
				$pass_id 	= absint($_POST['pass_id']);				
				if(($user_name == '') || ($user_email == '') || ($pass_cate == '')){
					$resp['blnkspc'] = "blank";
					echo json_encode($resp);
					die;
				}else{
					if((isset($user_name) && !empty($user_name)) || (isset($user_email) && !empty($user_email)) || (isset($pass_cate) && !empty($pass_cate))){
						if($pwd != ''){
							$table_name = $prefix . "pms_passwords"; 
							$final_rslt		= $wpdb->update( 
								$table_name, 
								array('user_name' 		=> $user_name,
									  'user_email' 		=> $user_email,
									  'user_password'	=> $pwd,
									  'category_id' 	=> $pass_cate,
									  'note'			=> $user_note,
									  'url'				=> $user_url,
									 ) ,
								array( 'pass_id' => $pass_id )
							);
						}else{
							$table_name = $prefix . "pms_passwords"; 
							$final_rslt		= $wpdb->update(
								$table_name, 
								array('user_name'  => $user_name,
									  'user_email' => $user_email,
									  'category_id'=> $pass_cate,
									  'note'		=> $user_note,
									  'url'			=> $user_url,
									 ) ,
								array( 'pass_id' => $pass_id )
							);
						}
						if($final_rslt){
							echo json_encode($final_rslt);	
						}else{			
							echo "error";	
						}
					}
				}
			}
			/*
			**Delete password detail in batabase
			*/		
			elseif($btn_action == 'Delete'){
				$pass_id 	= absint($_POST['pass_id']);
				$table_name = $prefix . "pms_passwords"; 
				if((isset($pass_id)) && !empty($pass_id)){
					$final_rslt		= $wpdb->delete( $table_name, array( 'pass_id' => $pass_id ) );
				}
				//execute query
				if($final_rslt){
					echo json_encode($final_rslt);
					die;
				}else{ 
					echo "error";
				}
			}
		}
	}
}

/*
**Fetch category detail in database
*/	
if ( ! function_exists('edit_pass') ) {
	function edit_pass(){
		global $wpdb, $posts;
		$prefix = $wpdb->prefix;
		$key_qry  = get_option('pms_encrypt_key');     
		$stng_key = esc_html($key_qry);
		if (class_exists('Encryption')) {
			$Encryption = new Encryption();
		} else { 
			echo "Failed";
			die;
		}
		$pass_id = absint($_POST['pass_id']);
		if((isset($pass_id)) && !empty($pass_id) && !wp_verify_nonce($_POST['security_nonce'], basename(__FILE__)) ){
			$query  = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$prefix}pms_passwords where pass_id = $pass_id"));			
			$value= json_decode(json_encode($query), True);

			if(count($value)>0){
				foreach ($value as $row) {
					$output['user_name']  	  	= esc_html($row['user_name']);
					$output['user_email'] 		= esc_html($row['user_email']);
					$output['user_password']  	= $Encryption->decrypt(esc_html($row['user_password']),$stng_key);
					$output['user_category'] 	= absint($row['category_id']);
					$output['user_note'] 	    = $row['note'];
					$output['user_url'] 	    = $row['url'];
				}
			}
		}else{ 
			echo "error";
		}
		echo json_encode($output);
		die;
	}	
}


/**
**decrypt key
*/
if ( ! function_exists('decrypt_pass') ) {
	function decrypt_pass(){
		global $wpdb;
		$prefix = $wpdb->prefix;
		$key_qry  = get_option('pms_encrypt_key');      
		$stng_key = esc_html($key_qry);

		if (class_exists('Encryption')) {
			$Encryption = new Encryption();
		} else { 
			echo "Failed";
			die;
		}
		$saction  = sanitize_text_field($_POST['saction']);
		$enc_pass = sanitize_text_field($_POST['user_pwd']);

		if(isset($_POST)  && !wp_verify_nonce($_POST['security_nonce'], basename(__FILE__))){
			if(	isset($saction)	&&	($saction	==	'decrypt')){
				$dcryppwd 	 = $Encryption->decrypt($enc_pass, $stng_key);				
				echo $dcryppwd;
				die;
			}
			else if(isset($saction)	&&	($saction	==	'encrypt')){
				$id = absint($_POST['did']);
				$query  = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$prefix}pms_passwords where pass_id = $id"));		
				$ecryppwd = esc_html($query[0]->user_password);
				echo $ecryppwd;
				die;
			}
		}
	}
}
/*
**Add actions category detail
*/	
add_action('wp_ajax_get_new_pass', 'get_new_pass');	
add_action( 'wp_ajax_nopriv_get_new_pass', 'get_new_pass' );
add_action('wp_ajax_post_new_pass', 'post_new_pass');
add_action('wp_ajax_edit_pass', 'edit_pass');
add_action('wp_ajax_decrypt_pass', 'decrypt_pass');
add_action( 'wp_ajax_nopriv_decrypt_pass', 'decrypt_pass' );

?>