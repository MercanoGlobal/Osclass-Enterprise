
<div class="row">
    <div class="col-md-6">
        <div class="d-flex w-100 pt-2 mb-3">
            <a class="btn btn-primary btn-sm" style="height:inherit;" href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/dashboard.php'); ?>"><?php _e('Return to dashboard', 'gdpr_osclass'); ?></a>
        </div>
        <form class="card" action="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/erasure.php'); ?>" method="POST">
            <input name="paction" value="submit" type="hidden"/>
            <div class="card-body">
                <h2 class="mr-3"><?php _e('Right to erasure', 'gdpr_osclass'); ?></h2>
                <div class="col-md-12">
                    <div class="form-group pt-3">  
                        <label class="d-block custom-switch mb-3">
                            <input id="remove_account_enabled" type="checkbox" name="remove_account_enabled" value="1" <?php echo (osc_get_preference('remove_account_enabled', 'gdpr_osclass')=="1") ? 'checked' : ''; ?> class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?php _e('Check this to automatically add "Remove account" section under <em>user menu</em>.', 'gdpr_osclass'); ?></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-12 mt-3 alert alert-primary align-self-center">
                    <?php _e('Be sure that your theme does not include already a delete user option. Otherwise will be included twice.', 'gdpr_osclas'); ?>
                </div>  

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><?php _e('Save', 'gdpr_osclass'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>