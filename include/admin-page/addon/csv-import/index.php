<?php


    if (isset($_POST["pwdms_import_btn"])) {
        @error_reporting( E_ERROR );
        @set_time_limit( 0 );
        @ini_set( 'max_input_time', 3600 * 3 );
        @ini_set( 'max_execution_time', 3600 * 3 );
    
        if(isset($_POST['pwdms_import_btn'])) { 
            $encry_key = get_option('pms_encrypt_key');
            if(!empty($encry_key)){
                if ($_FILES["pwdms_csvim_upload_file"]["size"] > 0) {
        
                    //get the csv file 
                    $fileName = $_FILES["pwdms_csvim_upload_file"]["tmp_name"];
                    $file = fopen($fileName, "r");
                  
                    $data	 = fgetcsv( $file, 100000, ",", "'" ); 
                    $line	 = 0;
                    do {
                        $name	         = $data[ 0 ];
                        $email	         = $data[ 1 ];
                        $password	     = $data[ 2 ];
                        $url	         = $data[ 3 ];
                        $category		 = $data[ 4 ];
                        $note		     = $data[ 5 ];
                       
						if ( ($line !== 0 )	&&	($line < 201 )	) {
                            pwdms_custom_create_passwords_from_csv( $name, $email, $password,$url, $category ,$note);
                        }
                        $line++;
					
                    } while ( $data = fgetcsv( $file, 100000, ",", "'" )					
						
						
						
					);
                }
            }
        }    
    } 
function pwdms_custom_create_passwords_from_csv( $name, $email, $password,$url, $category ,$note){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $cat_name = strtolower(sanitize_text_field(trim($category)));
    $cat_name = str_replace('"', '', $cat_name);	
    $get_all_cate = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$prefix}pms_category WHERE category LIKE '$cat_name'"));
    if($get_all_cate == 0){
        // use if for special character
            
        $table_name = $wpdb->prefix . "pms_category"; 
        $result	=	$wpdb->insert(
        $table_name,                                                               
        array('category' => $cat_name,) , 
        array('%s') 
        );       
    }
    $category = str_replace('"', '', $category);
    $get_cate_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$prefix}pms_category WHERE category LIKE '$category'"));
    $user_name  = sanitize_text_field($name);
    $user_email = sanitize_email($email);
    $pass_cate  = absint($get_cate_id);
    if (class_exists('Encryption')) {
        $Encryption = new Encryption();
    } else { 
        echo "Failed";
        die;
    }
    $qry  = get_option('pms_encrypt_key');
	$stng_key = esc_html($qry);
    $encryppwd 	 = $Encryption->encrypt($password, $stng_key);
    $encryppwd = sanitize_text_field($encryppwd);
	$user_name = sanitize_text_field(str_replace('"', '', $user_name));
    $user_note  = sanitize_text_field(str_replace('"', '', $note));
    $url = esc_url($url);
    $table_name = $prefix . "pms_passwords"; 
    $final_rslt	=	$wpdb->insert(
    $table_name, 
    array('user_name' 		=> $user_name,
            'user_email' 	=> $user_email,
            'user_password' => $encryppwd,
            'category_id' 	=> $pass_cate,
            'note'			=> $user_note,
            'url'           => $url,
    ) , 
    array('%s','%s','%s','%d','%s','%s') 
    );
}

?>