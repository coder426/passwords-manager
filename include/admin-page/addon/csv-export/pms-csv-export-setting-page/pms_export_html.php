<div class="pwdms_csv_tab_cntnt">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active">
            <h2 class="pwdms_imex_top_hd">CSV Export</h2>
            <div class="px-4 py-2">
                <form id="pwdms_form_csv_export" method="post" action="">
                    <table class="form-table" id="pwdms_csv_export_table">
                        <tbody>
                            <tr>
                                <th>Document Title</th>
                                <td><input type="text" name="pwdms_document_title" id="pwdms_document_title" value="Password Info"></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>
                                <?php global $wpdb;                                                                   
                                    $query  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pms_category"); 
                                    $value = json_decode(json_encode($query), True);
                                    if(count($value) == 0){?>
                                        <p>There are no category to export</p>
                                    <?php }else{?>
                                        <select name="pwdms_csv_category" id="pwdms_csv_category" style="display: block;">
                                    <?php 
                                  
                                    if(count($value) > 1){                                      
                                       for($i=0; $i<count($value); $i++){
                                        $ids .= $value[$i]['id'].',';
                                       }
                                       $all_id = rtrim($ids,',');
                                       ?>
                                        <option value="<?php echo $all_id;?>">All</option>
                                    <?php } 
                                            foreach ($value as $row) {
                                                $cate_name = ucfirst(esc_html($row['category']));
                                                $cateory_id = absint($row['id']);?>
                                                <option value="<?php echo $cateory_id; ?>"><?php echo $cate_name;?></option>
                                        <?php }?>
                                    </select>
                                    <?php }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Show Columns</th>                                              
                                <td><fieldset>
                                        <?php
                                        $csv_fields = apply_filters('pwdms_csv_admin_fields', array(
                                            'col_pwdms_name' => __('Name', 'pwdms'),
                                            'col_pwdms_email' => __('Email', 'pwdms'),
                                            'col_pwdms_password' => __('Password', 'pwdms'),
                                            'col_pwdms_url' => __('Url', 'pwdms'), 
                                            'col_pwdms_category' => __('Category', 'pwdms'),
                                            'col_pwdms_desc' => __('Note', 'pwdms'), 
                                        ));
                                        $csv_checked_by_default =  apply_filters('pwdms_csv_checked_fields', array(
                                            'col_pwdms_name',
                                            'col_pwdms_email',                                                            
                                        ));                                       

                                        foreach ($csv_fields as $key => $val) {
                                            if (in_array($key, $csv_checked_by_default)) {
                                                $checked = 'checked="checked"';
                                            } else {
                                                $checked = '';
                                            }
                                            ?>
                                            <label for="<?php echo esc_attr($key); ?>" class="pwdms_checkboxes_label">
                                                <input type="checkbox" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" <?php echo esc_attr($checked); ?>>
                                                <?php echo esc_attr($val); ?>
                                            </label>
                                            <?php
                                        }
                                        ?>
                                        <?php do_action('pwdms_csv_admin_columns'); ?>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th>Select All</th>
                                <td>
                                    <label for="pwdms_select_all_csv" class="pwdms_checkboxes_label">
                                        <input type="checkbox" id="pwdms_select_all_csv" name="pwdms_select_all_csv">                                           
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="csv_export_progressbar"><div class="progress-label"></div></div>
                    <p class="submit">
                        <input type="submit" name="pwdms_export_btn" id="pwdms_export_btn" class="button button-primary" value="Export Data">
                    </p>
                </form> 
            </div>
        </div>
    </div>
</div>