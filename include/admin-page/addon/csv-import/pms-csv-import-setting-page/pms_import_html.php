<div class="pwdms_csv_tab_cntnt">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active">
            <h2 class="pwdms_imex_top_hd">CSV Import</h2>
            <div class="p-4 pb-2">
                <form class="form-horizontal" action="" method="post" name="pwdms_form_csv_import" enctype="multipart/form-data" id="pwdms_form_csv_import">
                    <div class="input-row">
                        <?php $encry_key = get_option('pms_encrypt_key'); ?>
                        <input type="file" name="pwdms_csvim_upload_file" id="pwdms_csvim_upload_file" accept=".csv">
                        <input type="submit" name="pwdms_import_btn" id="pwdms_import_btn" class="button button-primary" value="Import Data">
                        <input type="hidden" name="setting_key_hdn" id="setting_key_hdn" value="<?php echo $encry_key ?>">
                    </div>
                    <div id="labelError"></div>
                    <div id="csv_export_progressbar"><div class="progress-label"></div></div>
                </form>
            </div>
        </div>
    </div>
</div>