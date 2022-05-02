
<div class="row">
    <div class="col-md-6">
        <div class="d-flex w-100 pt-2 mb-3">
            <a class="btn btn-primary btn-sm" style="height:inherit;" href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/dashboard.php'); ?>"><?php _e('Return to dashboard', 'gdpr_osclass'); ?></a>
        </div>
        <form class="card" action="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/portability.php'); ?>" method="POST">
            <input name="paction" value="submit" type="hidden"/>
            <div class="card-body">
                <h2 class="mr-3"><?php _e('Right to data portability', 'gdpr_osclass'); ?></h2>
                <div class="col-md-12">
                    <div class="form-group pt-3">  
                        <label class="d-block custom-switch mb-3">
                            <input id="portability_enabled" type="checkbox" name="portability_enabled" value="1" <?php echo (osc_get_preference('portability_enabled', 'gdpr_osclass')=="1") ? 'checked' : ''; ?> class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?php _e('Check this to automatically add "Download" section under <em>user menu</em>.', 'gdpr_osclass'); ?></span>
                        </label>
                    </div>
                </div>

                <ul>
                    <li>
                        <label class="selectgroup-item">
                        <input type="radio" name="portability_download" value="1" class="selectgroup-input" <?php echo (osc_get_preference('portability_download', 'gdpr_osclass')=="1") ? 'checked' : ''; ?> >
                        <span class="selectgroup-button">Download file, users by clicking "download" all data will be generated on the fly.</span>
                        </label>
                    </li>

                    <li>
                        <label class="selectgroup-item">
                        <input type="radio" name="portability_download" value="0" class="selectgroup-input" <?php echo (osc_get_preference('portability_download', 'gdpr_osclass')=="0") ? 'checked' : ''; ?> >
                        <span class="selectgroup-button">Request data via email, users by clicking "download" will send and email to the admin email address requesting all user data. Admin will need to manually generate and download the data.</span>
                        </label> 
                    </li>
                </ul>

                <div class="col-md-12 mt-3 alert alert-primary align-self-center">
                    <?php _e('Users will be able to download all data collected by Osclass. Bear in mind that some plugins and themes may store information that won\'t be downloaded.', 'gdpr_osclas'); ?>
                </div>  

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><?php _e('Save', 'gdpr_osclass'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-6"> 
        <form class="card" action="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/portability.php'); ?>" method="POST">
            <input name="paction" value="download" type="hidden"/>
            <div class="card-body">
                <h2 class="mr-3"><?php _e('Download user data manually', 'gdpr_osclass'); ?></h2>
                <div class="col-md-12">
                    <div class="form-group pt-3">  
                        <input placeholder="user id goes here" name="user_id" value="" type="text"/>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><?php _e('Download', 'gdpr_osclass'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>