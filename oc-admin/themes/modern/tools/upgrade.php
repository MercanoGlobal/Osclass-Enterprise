<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
 * Copyright 2022 Osclass Enterprise
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

    $perms = osc_save_permissions();
    $ok    = osc_change_permissions();

    function render_offset(){
        return 'row-offset';
    }

    function addHelp() {
        echo '<p>' . __("Check to see if you're using the latest version of Osclass Enterprise.") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader() { ?>
        <h1><?php _e('Tools'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Upgrade &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="backup-setting">
    <!-- settings form -->
                    <div id="backup-settings">
                        <h2 class="render-title"><?php _e('Upgrade'); ?></h2>
                        <form>
                            <fieldset>
                            <div class="form-horizontal">
                            <div class="form-row">
                                <div class="tools upgrade">
                                <p class="text">
                                   <?php _e('To check for new releases, please visit the <a href="https://github.com/MercanoG/Osclass-Enterprise/releases" target="_blank">official release channel</a> and follow the <a href="https://docs.enterprise-classifieds.com/updating_osclass.html" target="_blank">upgrade instructions</a>.'); ?>
                                </p>
                                    <div id="steps_div">
                                        <div id="steps">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
    <!-- /settings form -->
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>