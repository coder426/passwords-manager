<?php
$page = $_GET['page'];
$tab = (isset($_GET['tab'])) ? $_GET['tab'] : '';
if (empty($tab)) {
	$tab = 'export';
}
$menus = array();
$menus['export'] = __('CSV Export', 'pwdms'); 
$menus = apply_filters('pwdms_ex_imp_new_menus', $menus);

?>
<div class="container-fluid clear"> 
	<!--Start Dashboard Content-->
	<div class="row">
		<div class="col-md-12">
			<div class="mt-3" id="pwdms_csv_wrap">
				<div class="pwdms_csv_sidebar"> 
					<ul class="nav pwdms_csv_nav"><?php
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
						}
						?>
					</ul>
				</div>
				<?php do_action('pdwms_menu_export_csv_html');?>
			</div>
		</div>
	</div>
	<!-- End container-fluid-->
</div>
