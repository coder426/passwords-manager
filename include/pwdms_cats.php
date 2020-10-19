<?php
global $wpdb;
$prefix = $wpdb->prefix;

$query_theme  = get_option('pms_dark_button'); 
$str_arr = explode ("_", $query_theme);
$bg = $str_arr[0];
$txt = $str_arr[1];
?>
<div class="container"> 
	<!--Start Dashboard Content-->
	<div class="row crbox mt-12">
		<div class="col-lg-12 col-xl-12 col-sm-12 col-xs-12">
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="row mb-5">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 p-0">
							<h3>All Categories</h3>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 p-0">
							<input type="radio" name="btn-Secondary" id="light_button" class="style1 btn btn-Secondary rounded-circle" 
								   <?php if(($bg == 'bg-Secondary') && ($txt == 'text-Secondary')) echo "checked='checked'";  ?>>
							<input type="hidden" name="light_buttn" id="light_buttn" value="bg-Secondary_text-Secondary">
							<input type="radio" name="btn-dark" id="dark_button" class="style2 btn btn-dark rounded-circle"
								   <?php if(($bg == 'bg-dark') && ($txt == 'text-white')) echo "checked='checked'";  ?>>
							<input type="hidden" name="dark_buttn" id="dark_buttn" value="bg-dark_text-white">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-0" align="right">
							<button type="button" name="add" id="add_button" data-toggle="modal" data-target="#categoryModal" class="btn btn-primary btn-xs"><i class="fa fa-plus-circle fa-2x"></i></button>
						</div>
					</div>				
					<div class="clear:both"></div>
					<!-- <div id="spinner" style="display:none"><img src="<?php echo PWDMS_IMG.'loading.gif'?>" height="100px"></div> -->
					<div class="row">
						<div class="col-sm-12 table-responsive <?php if(($bg != '') && ($txt != '')){echo $bg.' '.$txt;}else{echo 'bg-Secondary text-Secondary';}?>" id="table-responsive">
							<table id="category_data" class="table table-borderless table-striped mt-4">
								<thead class="bg-primary text-white" id="thead">
									<tr>
										<th>No.</th>
										<th>Category Name</th>
										<th>No. of Passwords</th>
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
<!-- add category modal start  -->
<div class="modal fade"  id="categoryModal">
	<div class="modal-dialog">
		<form method="post" id="category_form">
			<div class="modal-content animated fadeInUp">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-plus"></i> Category</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="input-10">Name<span class="export_error">*</span></label>
							<input type="text" name="category_name" id="category_name" class="form-control" required />
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<div class="text-danger">
								<span id="cat_error"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="category_id" id="category_id"/>
					<input type="hidden" name="module" id="module" value="categories" />
					<input type="hidden" name="btn_action" id="btn_action" value="Add"/>
					<input type="submit" name="saction" id="saction" class="btn btn-primary" value="Add" />
				</div>
			</div>
		</form>  
	</div>
</div>
<!-- end category modal end  -->