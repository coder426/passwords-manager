<?php
global $wpdb;
$prefix = $wpdb->prefix;

$page = $_GET['page'];
$tab = (isset($_GET['tab'])) ? $_GET['tab'] : '';
if (empty($tab)) {
	$tab = 'pwdms_general';
}
$menus = array();
$menus['pwdms_general'] = __('Setting', 'pwdms');
$menus['pwdms_shortcodes'] = __('Shortcode', 'pwdms');
$menus = apply_filters('pwdms_setting_new_menus', $menus);

?>
<script>
	/**
	*Genrate Encryption key
	*/
	function createRandomString( length ) {
		var str = "";
		for ( ; str.length < length; str += Math.random().toString( 36 ).substr( 2 ) );
		return str.substr( 0, length );
	}

	document.addEventListener( "DOMContentLoaded", function() {
		var button = document.querySelector( "#generate" ),
			output = document.querySelector( "#setting_key" );
		if(button){
			button.addEventListener( "click", function() {
				var str = createRandomString( 25 );
				output.value = '';
				output.value = str;
			}, false)
		}

	});

</script>
<div class="container-fluid clear"> 
	<!--Start Dashboard Content-->
	<div class="row">
		<div class="col-md-12">
			<div class="mt-3">
				<div class="pwdms_setting_sidebar"> 
					<ul class="nav pwdms_csv_nav">
						<?php
						foreach ($menus as $key => $menu) {
							$tab_url = add_query_arg(array(                   
								'page' => $page,
								'tab' => $key,
							), admin_url('admin.php'));
						?>
						<li>
							<a class="<?php if ($tab == $key) echo 'active'; ?>" href="<?php echo $tab_url; ?>"><?php echo $menu; ?></a>
						</li>
						<?php
						}//end foreach?>
					</ul>
				</div><!-- end side menu -->
				<?php if($tab == 'pwdms_general'){ ?>
				<div class="pwdms_setting_tab_cntnt">
					<h2 class="qck_lnk">Password Encryption Key:</h2>
					<div class="ref_lnk">
						<form method="post" id="setting_form">
							<div class="form-row">
								<div class="form-group col-md-12 mb-0">
									<label for="input-10">Password Encryption Key<span class="export_error">*</span></label>
								</div>
								<div class="form-group col-md-6 rlt mb-0">
									<?php
	$query  = get_option('pms_encrypt_key');
	if(isset($query)){
		$skey = esc_html($query);
	}
	if(!empty($skey)){?>
									<input type="password" name="setting_key" id="setting_key" data-toggle="tooltip" data-placement="bottom" title="Do not try to change it again else all your older passwords will stop working." class="form-control" value="<?php echo $skey;?>" readonly/>
									<span toggle="#setting_key" class="fa fa-fw fa-eye field-icon toggle-password" ></span> </div>
								<div class="empty_31"></div>
								<div>
									<input type="hidden" name="user_id" id="user_id" />
									<input type="hidden" name="module" id="module" value="settings" /> 
								</div>
								<?php }else{?>
								<input type="password" name="setting_key" id="setting_key" class="form-control stng_error" required />
								<span toggle="#setting_key" class="fa fa-fw fa-eye field-icon toggle-password"></span> <span class="wrngTooltip">Do not try to change it again else all your older passwords will stop working.</span>
								<h6 class="text-danger" id="msg_show"></h6>
								<h6 class="text-danger" id="error_show" style="display: none">Please enter encryption key first</h6>
							</div>
							<div class="form-group col-md-6 mb-3"> <a class="btn btn-primary" id="generate" style="margin: auto; color: white; padding: 7px 35px;">Generate Key</a> </div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="hidden" name="user_id" id="user_id" />
								<input type="hidden" name="module" id="module" value="settings" />
								<input type="hidden" name="btn_action" id="btn_action" value="Save"/>
								<input type="submit" name="saction" id="saction" class="btn btn-info" value="Save" />
							</div>
							<div id="empty_31" style="display:none;"></div>
							<?php }//else ?>
						</form>
						<?php
	$query  = get_option('pms_encrypt_key');        		
	if(isset($query)){
		$skey = esc_html($query);
	}
	if(!empty($skey)){?>
						<div class="row" style="display: none" id="key_warning">
							<div class="alert-dismissible fade show mt-3 pb-0">
								<p class="text-danger ml-1 mb-1"><strong>Note: </strong> You can enter encryption key only once. So, make sure to use secure key.</p>
							</div>
						</div>
						<?php }else{?>
						<div class="row" id="key_warning">
							<div class="col-md-12">
								<div class="alert-dismissible fade show mt-3 pb-0">
									<p class="text-danger ml-1 mb-1"><strong>Note: </strong> You can enter encryption key only once. So, make sure to use secure key.</p>
								</div>
							</div>
						</div>
						<?php }?>
					</div><!--end side setting-->
					<?php }//end if($tab) 
				elseif($tab == 'pwdms_shortcodes'){?>
					<div class="pwdms_setting_tab_cntnt"><!-- start general setting-->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane" style="display:block">
								<div class="tabpane_inner">  
									<h2 class="qck_lnk"><?php echo esc_html__(__('Shortcode', 'pwdms')) ?><span class="tgl-indctr" aria-hidden="true"></span></h2>
									<div class="ref_lnk">
										<p>Anywhere you can show password table using this shortcode</p>
										<p class="pwdms_stcode">[pms_pass cat_name="wordpress"]<p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	<!-- End container-fluid-->
</div>

