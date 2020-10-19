<div role="tabpanel" class="tab-pane pwdms_setting_tab_cntnt" id="pwdms_info_tab">
	<div class="tabpane_inner"> 
		<h2 class="qck_lnk"><?php echo esc_html__(__('Support', 'pwdms')) ?><span class="tgl-indctr" aria-hidden="true"></span></h2>   
		<div class="ref_lnk">
			<form id="pwdms_sprt_form" method="post"> 
				<ul class="pwdms_fdtype">
					<li>
						<input type="radio" class="pwdms_fdtypes" id="pwdms_fdtype_1" name="pwdms-review" value="review" />
						<a id="pwdms_fdtype_lnk1" href="https://wordpress.org/support/plugin/passwords-manager/reviews/" target="_blank">
							<i></i>
							<span>I would like to review this plugin</span>
						</a>
					</li>
					<li>
						<input type="radio" class="pwdms_fdtypes" id="pwdms_fdtype_4" name="pwdms-review" value="more-info" />
						<a id="pwdms_fdtype_lnk4" href="https://hirewebxperts.com/wpPluginDocs/pwdms/" target="_blank">
							<i></i>
							<span>How to use this plugin</span>
						</a>
					</li>
					<li>
						<input type="radio" class="pwdms_fdtypes" id="pwdms_fdtype_2" name="pwdms-suggest" value="suggestions" />
						<label for="pwdms_fdtype_2">
							<i></i>
							<span>I have ideas to improve this plugin</span>
						</label>
					</li>
					<li>
						<input type="radio" class="pwdms_fdtypes" id="pwdms_fdtype_3" name="pwdms-help" value="help-needed" />
						<label for="pwdms_fdtype_3">
							<i></i>
							<span>I need help with this plugin</span>
						</label>
					</li>
				</ul>
				<div class="pwdms_fdback_form">
					<div class="pwdms_field">
						<input placeholder="Enter your email address.." type="email" id="pwdms-feedback-email" class="pwdms-feedback-email" name="pwdms-feedback-email"/>
					</div>
					<div class="pwdms_field">                             
						<textarea rows="4" id="pwdms-feedback-message" class="pwdms-feedback-message" placeholder="Leave plugin developers any feedback here.."></textarea>                     
					</div>
					<div class="pwdms_field pwdms_fdb_terms_s">
						<input type="checkbox" class="pwdms_fdb_terms" id="pwdms_fdb_terms" name="pwdms_fdb_terms"/>
						<label for="pwdms_fdb_terms">I agree that by clicking the send button below my email address and comments will be send to a <a href="https://www.hirewebxperts.com">hirewebxperts.com</a></label>
					</div>
					<div class="pwdms_field">
						<div class="pwdms_sbmt_buttons">
							<button class="btn btn-warning text-white" type="submit" id="pwdms-feedback-submit">
								<i class="fa fa-send"></i> <?php echo _e('Send','pwdms');?>	
								<img src="<?php echo PWDMS_IMG.'sms-loading.gif'?>" height="15px" id="sms_loading" style="display:none">			
							</button>
							<input type="hidden" id="form_type" name="form_type">
							<a class="pwdms_fd_cancel btn" id="pwdms_fd_cancel" href="#"><?php echo _e('Cancel','pwdms');?></a>
						</div>
					</div>
				</div> 
			</form>
		</div>
	</div><!-- end tab panel inner-->
</div><!-- end tab panel-->
