<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

function addHelp() {
    echo '<p>' . __('Enter your custom HTML/CSS code to modify the design of your theme/plugins. The code entered here will not be affected by theme or Osclass updates. The code will be inserted into footer between &lt;style&gt;&lt;/style&gt; tags. Note that extensive modifications will affect the performance of your website, so use this feature sparingly.') . '</p>';
}

osc_add_hook('help_box','addHelp');

function customPageHeader(){ 
?>
    <h1><?php _e('Appearance'); ?>
        <a href="#" class="btn ico ico-32 ico-help float-right"></a>
    </h1>
<?php
}

osc_add_hook('admin_page_header','customPageHeader');

function customPageTitle($string) {
  return sprintf(__('Customization &raquo; %s'), $string);
}

osc_add_filter('admin_title', 'customPageTitle');

osc_current_admin_theme_path('parts/header.php'); 
?>

<div id="customization-setting">
    <!-- settings form -->
    <div id="customization-settings" class="form-horizontal">
        <h2 class="render-title"><?php _e('Theme/Plugins Customization'); ?></h2>
        <ul id="error_list"></ul>
        <form name="settings_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="appearance" />
            <input type="hidden" name="action" value="customization_update" />
            <fieldset>
                <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-label"><?php _e('Your CSS'); ?></div>
                        <div class="form-controls">
                            <textarea type="text" class="" name="customCss" style="width:900px;height:360px;"><?php echo osc_get_preference('custom_css'); ?></textarea><br>
                            <span class="help-box"><?php _e('Do not enter &lt;style&gt;&lt;/style&gt; tags. For more info, you may check the following CSS guide:'); ?> <a target="_blank" ref="noopener norefer nofollow" href="https://www.w3schools.com/css/">https://www.w3schools.com/css/</a></span>
                        </div>
                    </div>
                </div>

                <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-label"><?php _e('Your HTML/JS'); ?></div>
                        <div class="form-controls">
                            <textarea type="text" class="" name="customHtml" style="width:900px;height:360px;"><?php echo osc_get_preference('custom_html'); ?></textarea><br>
                            <span class="help-box"><?php _e('You may enter any HTML or JavaScript code. The code will be added into the website footer. Do not add PHP code.'); ?></span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="clear"></div>

            <div class="form-actions">
                <input type="submit" id="save_changes" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-submit" />
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>