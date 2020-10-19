<?php
global $wpdb;
$prefix = $wpdb->prefix;

//query for get table theme settings  
$query_theme  = get_option('pms_dark_button'); 
$str_arr = explode ("_", $query_theme);
$bg = $str_arr[0];
$txt = $str_arr[1];

//query for get setting key
$key_qry  = get_option('pms_encrypt_key');     
if(isset($key_qry)){
	$stng_key = esc_html($key_qry);
}

?>
<div class="container"> 
	<!--Start Dashboard Content-->
	<div class="row crbox mt-12">
		<div class="col-lg-12 col-xl-12 col-sm-12 col-xs-12">
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="row mb-5">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<h3>All Passwords</h3>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 p-0">
							<input type="radio" name="btn-Secondary" id="light_button" class="style1 btn btn-Secondary rounded-circle" 
								   <?php if(($bg == 'bg-Secondary') && ($txt == 'text-Secondary')) echo "checked='checked'";  ?>>
							<input type="hidden" name="light_buttn" id="light_buttn" value="bg-Secondary_text-Secondary">

							<input type="radio" name="btn-dark" id="dark_button" class="style2 btn btn-dark rounded-circle"
								   <?php if(($bg == 'bg-dark') && ($txt == 'text-white')) echo "checked='checked'";  ?>>
							<input type="hidden" name="dark_buttn" id="dark_buttn" value="bg-dark_text-white">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
							<?php          			
							if(empty($stng_key)){?>
							<button type="button" class="btn btn-secondary btn-xs" data-toggle="tooltip" data-placement="bottom" title="This button is disabled.  To enable this feature please add encryption key in settings page."><i class="fa fa-plus-circle fa-2x"></i></button>
							<?php }else{?>
							<button type="button" name="add" id="add_user_pass" data-toggle="modal" data-target="#pwdsModal" class="btn btn-primary btn-xs"><i class="fa fa-plus-circle fa-2x"></i></button>	
							<?php } ?> 
						</div>
					</div>
					<div class="clear:both"></div>
					<!-- <div id="spinner" style="display:none"><img src="<?php echo PWDMS_IMG.'loading.gif'?>"></div> -->
					<div class="row">
						<div class="col-sm-12 table-responsive <?php if(($bg != '') && ($txt != '')){echo $bg.' '.$txt;}else{echo 'bg-Secondary text-Secondary';}?>" id="table-responsive">
							<table id="pwds_data" class="table table-borderless table-striped mt-4">
								<thead class="bg-primary text-white">
									<tr>
										<th>No.</th>
										<th>Name</th>
										<th>Email</th>
										<th>Password</th>
										<th>Url</th>
										<th>Category</th>
										<th>Action</th>                      
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End container-fluid-->
</div>
<div id="wcbnl_overlay">
	<div class="cv-spinner">
		<img src="<?php echo PWDMS_IMG.'loading.svg'?>">
	</div>
</div>

<!-- add password modal start  -->
<div class="modal fade"  id="pwdsModal">
	<div class="modal-dialog">
		<form method="post" id="pwds_form">
			<div class="modal-content animated fadeInUp">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="input-10">Name<span class="export_error">*</span></label>
							<input type="text" name="user_name" id="user_name" class="form-control" required />
							<h6 id="user_err" class="text-danger" style="display: none">Please fill this field</h6>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="input-12">Email ID<span class="export_error">*</span></label>
							<input type="email" name="user_email" id="user_email" class="form-control" required />
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="input-12">Category<span class="export_error">*</span></label>
							<select class="form-control" name="pass_category" id="pass_category" class="form-control" id="sel1" >
								<option value="">Please Select</option>
								<?php
									$query_cate  = $wpdb->get_results("SELECT * FROM {$prefix}pms_category");            				 
									$value= json_decode(json_encode($query_cate), True);
									foreach ($value as $row) {
										$cate_name = ucfirst(esc_html($row['category']));
										$cateory_id = absint($row['id']);?>
								<option value="<?php echo $cateory_id; ?>"><?php echo $cate_name;?></option>
								<?php }?>
							</select>	
							<h6 id="slct_wrng" class="text-danger" style="display: none">Select category once</h6>						  
						</div>
					</div>						  
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="input-12">Password<span class="export_error">*</span></label>
							<input type="password" name="user_password" id="user_password" class="form-control" required />
							<span toggle="#user_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="input-12">Url</label>
							<input type="url" id="user_url" name="user_url" rows="4" cols="50" class="form-control"/>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="input-12">Note</label>
							<textarea id="user_note" name="user_note" rows="4" cols="50" class="form-control" placeholder="Write note here....."></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="pwds_id" id="pwds_id" />
					<input type="hidden" name="setting_key_enc" id="setting_key_enc" value="<?php echo $stng_key;?>"/>
					<input type="hidden" name="module" id="module" value="password" />
					<input type="hidden" name="btn_action" id="btn_action" value="Add"/>
					<input type="submit" name="saction" id="saction" class="btn btn-info" value="Add" />
				</div>
			</div>
		</form>  
	</div>
</div>
<!-- add pasword modal end  -->
<!-- add password modal start  -->
<div class="modal fade"  id="pwdsnoteModal">
	<div class="modal-dialog">
		<form method="post">
			<div class="modal-content animated fadeInUp">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">         
					<div class="form-row">
						<div class="form-group col-md-12">
							<textarea id="user_note_view" rows="10" cols="60" readonly style="width:100%"></textarea>
						</div>
					</div>
					<div class="modal-footer">  
						<button onclick=pwdms_save_note(user_note_view.value,'Pass-Note.txt') class="btn btn-info">Download</button>        
					</div>
				</div>
			</div>
		</form>  
	</div>
</div>
<!-- add pasword modal end  -->