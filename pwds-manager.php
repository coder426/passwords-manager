<?php
/**
 * Plugin Name:		  Passwords Manager
 * Plugin URI:		  https://www.hirewebxperts.com/pms
 * Description:		  Passwords Manager let you store all your passwords at one place.
 * Version: 		  1.4
 * Author: 			  Coder426
 *Text Domain: pwdms
 * Author URI:		  https://www.hirewebxperts.com
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * License: GPL2
*/

/*
**define plugin paths
*/
define('PWDMS_PLUGIN_URL',plugin_dir_url( __FILE__ ));
define('PWDMS_PLUGIN_DIR',dirname( __FILE__ ));
define('PWDMS_JS',PWDMS_PLUGIN_URL. 'assets/js/');
define('PWDMS_CSS',PWDMS_PLUGIN_URL. 'assets/css/');
define('PWDMS_IMG',PWDMS_PLUGIN_URL. 'assets/images/');
define('PWDMS_INC',PWDMS_PLUGIN_DIR. '/include/');

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // exit if accessed directly    
}

	/*
	**Create Datatable for plugin  activation
	*/	
	if ( ! function_exists('pms_db_install') ){
		function pms_db_install() {
			global $wpdb;
			
			/*
			**create pms_category datatable
			*/
			$table_name = $wpdb->prefix . 'pms_category';
			$sql = "CREATE TABLE $table_name (
				id int(11) NOT NULL AUTO_INCREMENT,
				category varchar(55) DEFAULT '' NOT NULL,
				PRIMARY KEY  (id)
			)ENGINE=InnoDB DEFAULT CHARSET=latin1";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			$result	=	$wpdb->insert(
				$table_name, 
				array('category' =>'Uncategorized',) , 
				array('%s') 
			);

			/*
			**create pms_passwords datatable
			*/
			$table_name = $wpdb->prefix . 'pms_passwords';
			$sql1 = "CREATE TABLE $table_name (
				pass_id int(11) NOT NULL AUTO_INCREMENT,
				user_name varchar(200) NOT NULL,
				user_email varchar(200) NOT NULL,
		  		user_password longtext NOT NULL,
		  		category_id int(11) NOT NULL,
				note text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
				url longtext NOT NULL,
				PRIMARY KEY  (pass_id)
			)ENGINE=InnoDB DEFAULT CHARSET=latin1";
			dbDelta( $sql1 );
		}
		 register_activation_hook( __FILE__, 'pms_db_install' );
	}

	/*
	**Drop datatable
	*/		
	if ( ! function_exists('delete_plugin_database_tables') ){
		function delete_plugin_database_tables(){
		        global $wpdb;
				$prefix = $wpdb->prefix;
				$tbl_name = $wpdb->prefix . "options"; 
				$query  = "SELECT * FROM {$prefix}options where option_name LIKE 'pms_encrypt_key'";
				$dlt_q 	= $wpdb->get_row($query);
				$keyId 	= $dlt_q->option_id;
				$rslt	= $wpdb->delete( $tbl_name, array( 'option_id' => $keyId ) );
		        $tableArray = array(   
		          $wpdb->prefix . "pms_passwords",
		          $wpdb->prefix . "pms_category",
		       );

		      foreach ($tableArray as $tablename) {
		         $wpdb->query("DROP TABLE IF EXISTS $tablename");
		      }
		    }

		register_uninstall_hook(__FILE__, 'delete_plugin_database_tables');	
	}
		
	/*
	**After Plugin Activation redirect
	*/
	if( !function_exists( 'pms_after_activation_redirect' ) ){
	  function pms_after_activation_redirect( $plugin ) {
      	if( $plugin == plugin_basename( __FILE__ ) ) {
          exit( wp_redirect( admin_url( 'admin.php?page=pms_settings' ) ) );
      	}
	  }
	  add_action( 'activated_plugin', 'pms_after_activation_redirect' );
	}
	
	/*
	 **plugin update process
	 */
// 	add_action('plugin_loaded', 'pwdms_upgrade_process_complete');// will working only this plugin activated.
// 	function pwdms_upgrade_process_complete()
// 	{
// 		global $wpdb;
// 		$prefix = $wpdb->prefix;
// 		$table_name = $wpdb->prefix . 'pms_passwords';		
// 		$wpdb->query("ALTER TABLE `$table_name` ADD `note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `category_id`");
// 		$wpdb->query("ALTER TABLE `$table_name` ADD `url` LONGTEXT NOT NULL AFTER `note`");
						
// 	}// pwdms_upgrade_process_complete

	//Plugin improve notices
	add_action('admin_notices','pwdms_admin_notices');
	function pwdms_admin_notices(){
		?>
		<div class="notice notice-success is-dismissible">
			<p>Would you like to review or improve password manager <a href="<?php admin_url();?>./admin.php?page=pms_export_import&tab=pwdms_support">Please Review</a></p>
		</div>
		<?php
	}

	/**
	 * Setting link to pluign
	 */	
	add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'pms_add_plugin_page_settings_link');
	function pms_add_plugin_page_settings_link( $links ) {
		$links[] = '<a href="' .admin_url( 'admin.php?page=pms_settings' ) .'">' . __('Settings') . '</a>';
		return $links;
	}


	/*
	*Live demo plugin row meta
	*/
	add_filter('plugin_row_meta' , 'pms_live_demo_meta_links', 10, 2);
	if ( ! function_exists('pms_live_demo_meta_links') ) {
		function pms_live_demo_meta_links($meta_fields, $file) {
			if ( plugin_basename(__FILE__) == $file ) {
			$plugin_url = "https://www.youtube.com/watch?v=D26QpkK-YVo";
			$meta_fields[] = "<a href='" . esc_url($plugin_url) ."' target='_blank' title='" . esc_html__('Live demo', 'pwdms') . "'>
					<i class='fa fa-desktop' aria-hidden='true'>"
				. "&nbsp;<span>Live Demo</span>". "</i></a>";      
			
			}
			return $meta_fields;
		}
	}

	/**
	**Include css js files
	*/

	if(isset($_REQUEST['page'])){
		//     ||  ($_REQUEST['page'] == 'pms_export')
		if(!function_exists('pwdms_add_admin_scripts')  && ($_REQUEST['page'] == 'pms_menu') || ($_REQUEST['page'] == 'pms_cate_menu') || ($_REQUEST['page'] == 'pms_settings') || ($_REQUEST['page'] == 'pms_export_import')){
			function pwdms_add_admin_scripts() {
				wp_register_style( 'fontawsome', PWDMS_CSS . 'font-awesome.min.css' );
				wp_enqueue_style( 'fontawsome');
				wp_register_style( 'pms-bsmincss',PWDMS_CSS . 'bootstrap.min.css' );
				wp_enqueue_style( 'pms-bsmincss');
				wp_enqueue_script( 'jquery-ui-progressbar' );
				wp_enqueue_style( 'pms-admin', PWDMS_CSS . 'pms-admin.css');	
				// added output js 
				$admin_script = array(
					'pms-inkpass'=>'/assets/js/pms-recs.js',
					'pms-encry'=>'/assets/js/encryption.js',
					'pms-settings'=>'/assets/js/pms-settings.js',
					'pms-crypt'=>'/assets/js/crypto.js',
					'pms-clipboard'=>'/assets/js/clipboard.min.js',
					'pms-my-script'=>'/assets/js/jquery.dataTables.min.js',
					'pms-popper'=>'/assets/js/popper.min.js',
					'pms-my-script2'=>'/assets/js/dataTables.bootstrap4.min.js',
					'pms-bsminjs'=>'/assets/js/bootstrap.min.js',
					'pms-inkthemes'=>'/assets/js/pms-cats.js',
					'pms-sweealert'=>'/assets/js/sweealert.js',
					'pms-csv_export'=>'/include/admin-page/addon/csv-export/js/pms_csv_export.js',
				);
				foreach($admin_script as $script_key => $script_value){
					wp_enqueue_script($script_key, plugins_url( $script_value , __FILE__ ) , array( 'jquery' ));				
				}
				// including ajax script in the plugin Myajax.ajaxurl
				$admin_url = strtok( admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ), '?' );
				wp_localize_script( 'pms-inkthemes', 'MyAjax', array( 
					'ajaxurl' => $admin_url,
					'no_export_data' => __('There are no exporting data in your selection fields','pwdms'),
					'ajax_public_nonce' => wp_create_nonce( 'ajax_public_nonce' ),
				));	
				
			}
			add_action( 'admin_enqueue_scripts', 'pwdms_add_admin_scripts' );
		}
	}else{
		function pwdms_front_add_admin_scripts() {
			    wp_register_style( 'fontawsome', PWDMS_CSS . 'font-awesome.min.css' );
				wp_enqueue_style( 'fontawsome');
				wp_enqueue_style( 'pms-front', PWDMS_CSS . 'pms-front.css');
			    // added output js 
				$out_script = array(
					'pms-front'=>'/assets/js/pms-front.js',
					'pms-encry'=>'/assets/js/encryption.js',
					'pms-settings'=>'/assets/js/pms-settings.js',
					'pms-crypt'=>'/assets/js/crypto.js',
					'pms-clipboard'=>'/assets/js/clipboard.min.js',
					'pms-my-script'=>'/assets/js/jquery.dataTables.min.js',
					'pms-my-script2'=>'/assets/js/dataTables.bootstrap4.min.js',
					
					'pms-inkthemes'=>'/assets/js/pms-cats.js',

				);
				foreach($out_script as $script_key => $script_value){
					wp_enqueue_script($script_key, plugins_url( $script_value , __FILE__ ) , array( 'jquery' ));
				}

				$admin_url = strtok( admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ), '?' );
				wp_localize_script( 'pms-inkthemes', 'MyAjax', array( 
					'ajaxurl' => $admin_url,
					'no_export_data' => __('There are no exporting data in your selection fields','pwdms'),
					'ajax_public_nonce' => wp_create_nonce( 'ajax_public_nonce' ),
				));	
				
		}
		
		add_action( 'wp_enqueue_scripts', 'pwdms_front_add_admin_scripts' );
		
		function pwdms_add_admin_shortcode_scripts() {
			wp_enqueue_style( 'pms-admin-short', PWDMS_CSS . 'pms-admin-shortcode.css');
		}
		add_action( 'admin_enqueue_scripts', 'pwdms_add_admin_shortcode_scripts' );
	
	}
	/**
	**Create PMS menu
	*/		
	add_action('admin_menu', 'pms_cat_menu');
	if ( ! function_exists('pms_cat_menu') ){
		function pms_cat_menu(){
			add_menu_page('PWD management System', 'PWDMS', 'manage_options', 'pms_menu', 'pms_pass_output','',99);
			add_submenu_page('pms_menu', 'All Passwords', 'Passwords', 'manage_options', 'pms_menu', 'pms_pass_output');
			add_submenu_page('pms_menu', 'All Categories', 'Categories', 'manage_options', 'pms_cate_menu', 'pms_cat_output');
			add_submenu_page('pms_menu', 'Settings', 'Settings', 'manage_options', 'pms_settings', 'pms_settings_output');
			add_submenu_page('pms_menu', 'Import / Export', 'Import/Export', 'manage_options', 'pms_export_import', 'pms_export_output');
		}
	}
	/**
	**Create password sub-menu
	*/		
	if ( ! function_exists('pms_pass_output') ){
		function pms_pass_output(){
			echo "<p></p>";
				include(PWDMS_INC .'pwdms_recs.php');
		}
	}
	/*
	**Create category sub-menu
	*/	
	if ( ! function_exists('pms_cat_output') ){
		function pms_cat_output(){
			echo "<p></p>";
				include(PWDMS_INC .'pwdms_cats.php');
		}
	}
	/*
	**Create settings sub-menu
	*/	
	if ( ! function_exists('pms_settings_output') ){
		function pms_settings_output(){
			echo "<p></p>";
				include(PWDMS_INC .'pms_settings.php');
		}
	}	
	/*
	**Create Export/Import sub-menu
	*/	
	if ( ! function_exists('pms_export_output') ){
		function pms_export_output(){

			include(PWDMS_INC .'pms_export_tabs.php');
		}
	}	
	add_action('pdwms_menu_export_csv_html','pdwms_menu_export_csv_html_show_page');
	function pdwms_menu_export_csv_html_show_page(){
		if(isset($_GET['tab']) && $_GET['tab'] == 'import'){
			include (PWDMS_INC . 'admin-page/addon/csv-import/pms-csv-import-setting-page/pms_import_html.php');
		}
		elseif(isset($_GET['tab']) && $_GET['tab'] == 'pwdms_support'){
			include (PWDMS_INC . 'pms_support.php');
			
		}else{
			include (PWDMS_INC . 'admin-page/addon/csv-export/pms-csv-export-setting-page/pms_export_html.php');			
		}
		
	}

	add_filter('pwdms_ex_imp_new_menus','pwdms_import_menu');
	function pwdms_import_menu($new_tab){
		$new_tab['import'] = __( 'CSV Import', 'pwdms' );
		$new_tab['pwdms_support'] = __('Support', 'pwdms');
		return $new_tab;
	}
	include(PWDMS_INC .'pms-front-shortcode.php');//end is_admin
	/*
	**include encryption file
	*/
	include(PWDMS_INC .'encryption.php');	
	/*
	**include category action file
	*/	
	include(PWDMS_INC .'pms-cat-action.php');
	/*
	**include pass action file
	*/	
	include(PWDMS_INC .'pms-recs-action.php');
	/*
	**include Setting action file
	*/
	include(PWDMS_INC .'pms-setting-action.php');
	/*
	**include csv-export
	*/
	include(PWDMS_INC .'admin-page/addon/csv-export/index.php');
	/*
	**include csv-import
	*/
	include(PWDMS_INC .'admin-page/addon/csv-import/index.php');
	/*
	**include Setting action file
	*/
	

	
?>