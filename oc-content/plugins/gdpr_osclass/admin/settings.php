<?php 

$localeManager = OSCLocale::newInstance();
$aLocales = $localeManager->listAllEnabled();

osc_add_hook('admin_footer', function(){ ?>
    <script>
    document.addEventListener("DOMContentLoaded", function(){

      $('#myTab a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
      });
      $('#myTab-flash a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
      });

    });
    </script>
<?php }); ?>

<div class="row">
    <div class="col-md-6">
        <div class="d-flex w-100 pt-2 mb-3">
            <a class="btn btn-primary btn-sm" style="height:inherit;" href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/dashboard.php'); ?>"><?php _e('Return to dashboard', 'gdpr_osclass'); ?></a>
        </div>
        <div class="d-flex w-100 pt-2 mb-3">
            <div class="col-md-12 mt-3 alert alert-primary align-self-center">
                <p>Some forms requires a bit of customisation in order to add “I agree” checkbox.</p>
                <p class="pt-3 bt-3">Alert form, instructions for manually adding "I agree" checkbox.<a href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/help/alerts.php'); ?>" class="btn btn-primary float-right"><b>Create Alert form</b></a></p>
                <p class="pt-3 bt-3">Comments form, instructions for manually adding "I agree" checkbox.<a href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/help/comments.php'); ?>" class="btn btn-primary float-right"><b>Create Comment form</b></a></p>
                <p class="pt-3 bt-3">Item post/Item edit form, instructions for manually adding "I agree" checkbox.<a href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/help/item-post.php'); ?>" class="btn btn-primary float-right"><b>Item post/Item edit form</b></a></p>
            </div>
        </div>
        <form class="card" action="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/settings.php'); ?>" method="POST">
            <input name="paction" value="submit" type="hidden"/>
            <div class="card-body">
                <h2 class="mr-3"><?php _e('Enable GDPR', 'gdpr_osclass'); ?></h2>
                <div class="col-md-12">
                    <div class="row"> 

                        <div class="col-md-12 mb-3">
                            <div class="form-group pt-3">  
                                <label class="d-block custom-switch mb-3">
                                    <input id="gdpr_enabled" type="checkbox" name="gdpr_enabled" value="1" <?php echo (osc_get_preference('gdpr_enabled', 'gdpr_osclass')=="1") ? 'checked' : ''; ?> class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description"><?php _e('Check this to turn on GDPR related features (adding a checkbox to your form).', 'osclass_seo'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group pt-3">

                                <div class="d-flex align-items-center">
                                    <h3 class="font-weight-bold mr-3"><?php _e('Terms and Conditions page', 'gdpr_osclass'); ?></h3>
                                    <div class="alert alert-<?php echo (osc_get_preference('terms_page', 'gdpr_osclass')!="") ? 'success' : 'danger'; ?> d-inline ml-auto m-0">
                                        <?php if(osc_get_preference('terms_page', 'gdpr_osclass')!="") { ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" style="color: white;fill: currentColor;margin-right:15px;" width="16" height="16" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
                                        <?php _e('Done', 'gdpr_osclass'); ?>
                                        <?php } else { ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" style="color: white;fill: currentColor;margin-right:15px;" width="16" height="16" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/></svg>
                                        <?php _e('Required', 'gdpr_osclass'); ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <label class="d-block custom-switch mb-3">
                                    <input id="terms_link" type="checkbox" name="terms_is_page" value="1" <?php echo (osc_get_preference('terms_is_page', 'gdpr_osclass')=="1") ? 'checked' : ''; ?> class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description"><?php _e('Check this to use static page instead of a plain url', 'osclass_seo'); ?></span>
                                </label>

                                <div class="box-terms-static" style="<?php echo (osc_get_preference('terms_is_page', 'gdpr_osclass')=="1") ? '': 'display:none;'; ?>">
                                    <input type="hidden" name="terms_page" value="<?php echo osc_get_preference('terms_page', 'gdpr_osclass'); ?>"/>
                                    <div class="terms-select"></div>
                                </div>
                                <div class="box-terms-link" style="<?php echo (osc_get_preference('terms_is_page', 'gdpr_osclass')!="1") ? '': 'display:none;'; ?>">
                                    <input name="terms_link" value="<?php echo (osc_get_preference('terms_is_page', 'gdpr_osclass')!="1") ? osc_get_preference('terms_page', 'gdpr_osclass') : ''; ?>" type="text" placeholder="https://example.com/terms-and-conditions"/>
                                </div>
                                <div class="col-md-12 mt-3 alert alert-primary align-self-center">
                                    <?php _e('Select your <em>Terms and Conditions</em> you can choose a <em>Static page</em> or <em>Custom link</em> if your host your pages outside.', 'gdpr_osclass'); ?>
                                </div>
                                <hr class="mb-3">
                            </div> 

                            <div class="form-group pt-3"> 
                                <div class="d-flex align-items-center">
                                    <h3 class="font-weight-bold mr-3"><?php _e('Privacy Policy page', 'gdpr_osclass'); ?></h3>
                                    <div class="alert alert-<?php echo (osc_get_preference('privacy_page', 'gdpr_osclass')!="") ? 'success' : 'danger'; ?> d-inline ml-auto m-0">
                                        <?php if(osc_get_preference('privacy_page', 'gdpr_osclass')!="") { ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" style="color: white;fill: currentColor;margin-right:15px;" width="16" height="16" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
                                        <?php _e('Done', 'gdpr_osclass'); ?>
                                        <?php } else { ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" style="color: white;fill: currentColor;margin-right:15px;" width="16" height="16" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/></svg>
                                        <?php _e('Required', 'gdpr_osclass'); ?>
                                        <?php } ?>
                                    </div>
                                </div> 

                                <label class="d-block custom-switch mb-3">
                                    <input id="privacy_link" type="checkbox" name="privacy_is_page" value="1" <?php echo (osc_get_preference('privacy_is_page', 'gdpr_osclass')=="1") ? 'checked' : ''; ?> class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description"><?php _e('Check this to use static page instead of a plain url', 'osclass_seo'); ?></span>
                                </label>

                                <div class="box-privacy-static" style="<?php echo (osc_get_preference('privacy_is_page', 'gdpr_osclass')=="1") ? '': 'display:none;'; ?>">
                                    <input type="hidden" name="privacy_page" value="<?php echo osc_get_preference('privacy_page', 'gdpr_osclass'); ?>"/>
                                    <div class="privacy-select"></div>
                                </div>
                                <div class="box-privacy-link" style="<?php echo (osc_get_preference('privacy_is_page', 'gdpr_osclass')!="1") ? '': 'display:none;'; ?>">
                                    <input name="privacy_link" value="<?php echo (osc_get_preference('privacy_is_page', 'gdpr_osclass')!="1") ? osc_get_preference('privacy_page', 'gdpr_osclass') : ''; ?>" type="text" placeholder="https://example.com/privacy-policy"/>
                                </div> 
                                <div class="col-md-12 mt-3 alert alert-primary align-self-center">
                                    <?php _e('Select your <em>Privacy Policy</em> page you can choose a <em>Static page</em> or <em>Custom link</em> if your host your pages outside.', 'gdpr_osclass'); ?>
                                </div>
                                <hr class="mb-3">                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12"> 
                    <h3 class="font-weight-bold mr-3"><?php _e('Update "I agree to terms" text', 'gdpr_osclass'); ?></h3>

                    <div class="mb-3">
                        <ul id="myTab" class="nav nav-tabs" role="tablist"> 
                            <?php foreach($aLocales as $key => $locale) { 
                            $active = (Params::getParam('locale')=="" && $key===0 || Params::getParam('locale')==$locale['pk_c_code']) ? "active" : "";  ?>  
                            <li class="nav-item"><a class="nav-link <?php echo $active; ?>" href="#tab<?php echo $locale['pk_c_code']; ?>" data-toggle="tab"><?php echo $locale['s_name']; ?></a></li>
                            <?php } ?>
                        </ul>

                        <div class="tab-content">
                        <?php foreach($aLocales as $key => $locale) { 
                            $active = (Params::getParam('locale')=="" && $key===0 || Params::getParam('locale')==$locale['pk_c_code']) ? "active" : "";  ?>  
                            <div class="pt-3 tab-pane <?php echo $active; ?>" id="tab<?php echo $locale['pk_c_code']; ?>">
                                <div class="form-group">
                                    <textarea class="form-control" name="agree_text[<?php echo $locale['pk_c_code']; ?>]"><?php echo osc_get_preference('agree_text_' . $locale['pk_c_code'], 'gdpr_osclass'); ?></textarea>
                                    <p class="text-muted mb-0 p-2"><small><b><?php _e('Example text:', 'gdpr_osclass'); ?></b>&nbsp;<?php echo osc_get_preference('agree_text_default', 'gdpr_osclass'); ?></small></p>
                                </div> 
                            </div><!-- /.tab-pane -->
                        <?php } ?>
                        </div> 
                    </div>
                    <div class="mb-3 alert alert-primary align-self-center">
                        <p><?php _e('Change "I agree to terms" text, deafult text is ', 'gdpr_osclass'); ?><?php echo osc_get_preference('agree_text_default', 'gdpr_osclass'); ?></p>
                        <?php _e('Use keywords <code>{TERMS}</code> and <code>{PRIVACY}</code> and will be automatically replaced by your page links.', 'gdpr_osclass'); ?></br>
                    </div>
                    <hr class="mb-3">                               
                </div>
                <div class="col-md-12"> 
                    <h3 class="font-weight-bold mr-3"><?php _e('Flashmessage text', 'gdpr_osclass'); ?></h3>

                    <div class="mb-3">
                        <ul id="myTab-flash" class="nav nav-tabs" role="tablist"> 
                            <?php foreach($aLocales as $key => $locale) { 
                            $active = (Params::getParam('locale')=="" && $key===0 || Params::getParam('locale')==$locale['pk_c_code']) ? "active" : "";  ?>  
                            <li class="nav-item"><a class="nav-link <?php echo $active; ?>" href="#tab-flash-<?php echo $locale['pk_c_code']; ?>" data-toggle="tab"><?php echo $locale['s_name']; ?></a></li>
                            <?php } ?>
                        </ul>

                        <div class="tab-content">
                        <?php foreach($aLocales as $key => $locale) { 
                            $active = (Params::getParam('locale')=="" && $key===0 || Params::getParam('locale')==$locale['pk_c_code']) ? "active" : "";  ?>  
                            <div class="pt-3 tab-pane <?php echo $active; ?>" id="tab-flash-<?php echo $locale['pk_c_code']; ?>">
                                <div class="form-group">
                                    <textarea class="form-control" name="error_agree_text[<?php echo $locale['pk_c_code']; ?>]"><?php echo osc_get_preference('error_agree_text_' . $locale['pk_c_code'], 'gdpr_osclass'); ?></textarea>
                                    <p class="text-muted mb-0 p-2"><small><b><?php _e('Example text:', 'gdpr_osclass'); ?></b>&nbsp;<?php echo osc_get_preference('error_agree_text_default', 'gdpr_osclass'); ?></small></p>
                                </div> 
                            </div><!-- /.tab-pane -->
                        <?php } ?>
                        </div> 
                    </div> 
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><?php _e('Save', 'gdpr_osclass'); ?></button>
                </div>
            </div>
        </form> 
    </div>
</div>

<script>

var pages = [];
<?php osc_count_static_pages(); while(osc_has_static_pages()) { ?>
pages.push('<option value="<?php echo osc_static_page_slug(); ?>"><?php echo osc_static_page_title(); ?></option>');
<?php } ?>

$( document ).ready(function() {
    setTimeout(function() {
        var html = '<select class="form-control">';
        html += '<option value="" selected><?php _e('Select page', 'gdpr_osclass'); ?></option>';
        for (var i = 0; i < pages.length; i++) {
            html += pages[i];
        }
        html += '</select>';
        
        $('.terms-select').append(html);
        $('.privacy-select').append(html);

        $('.terms-select select').prop('name', 'select_terms_page');
        $('.privacy-select select').prop('name', 'select_privacy_page');

        if($('.terms_page').val()!="") {
            $('.terms-select select').val( $('input[name="terms_page"]').val() );
        }
        if($('.privacy_page').val()!="") {
            $('.privacy-select select').val( $('input[name="privacy_page"]').val() );
        }
    }, 200);

    $('#terms_link').change(function() {
        if(this.checked) { 
            $('.box-terms-static').show();
            $('.box-terms-link').hide();
        } else {
            $('.box-terms-static').hide();
            $('.box-terms-link').show();
        }
    });

    $('#privacy_link').change(function() {
        if(this.checked) { 
            $('.box-privacy-static').show();
            $('.box-privacy-link').hide();
        } else {
            $('.box-privacy-static').hide();
            $('.box-privacy-link').show();
        }
    });
});

</script>