<?php if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
}
/*
 * Copyright 2021 Osclass
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
    echo '<p>' . __('Shows system configuration information.') . '</p>';
}
osc_add_hook('help_box', 'addHelp');

function customPageHeader() {
    ?>
    <h1>
        <?php _e('Tools'); ?>
        <a href="#" class="btn ico ico-32 ico-help float-right"></a>
    </h1>
    <?php
}
osc_add_hook('admin_page_header', 'customPageHeader');

function customPageTitle($string) {
    return sprintf(__('Configuration info &raquo; %s'), $string);
}
osc_add_filter('admin_title', 'customPageTitle');

$conn = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!mysqli_connect_errno()) {
    $mysql_version = $conn->server_info;
}
$conn->close();

// Fix for phpinfo CSS messing with page.
ob_start();
phpinfo();
$phpinfo = ob_get_contents();
ob_end_clean();
$phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);

osc_current_admin_theme_path('parts/header.php'); ?>
    <style>
        #phpinfo {}
        #phpinfo pre {margin: 0; font-family: monospace;}
        #phpinfo a:link {color: #009; text-decoration: none; background-color: #fff;}
        #phpinfo a:hover {text-decoration: underline;}
        #phpinfo table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
        #phpinfo .center {text-align: center;}
        #phpinfo .center table {margin: 1em auto; text-align: left;}
        #phpinfo .center th {text-align: center !important;}
        #phpinfo td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
        #phpinfo h1 {font-size: 150%;}
        #phpinfo h2 {font-size: 125%;}
        #phpinfo .p {text-align: left;}
        #phpinfo .e {background-color: #ccf; width: 300px; font-weight: bold;}
        #phpinfo .h {background-color: #99c; font-weight: bold;}
        #phpinfo .v {background-color: #ddd; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
        #phpinfo .v i {color: #999;}
        #phpinfo img {float: right; border: 0;}
        #phpinfo hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
    </style>

    <h2 class="render-title"><?php _e('At a glance'); ?></h2>
        <div class="grid-system">
            <?php
                $plugins_all = count(Plugins::listAll());
                $plugins_active = count(Plugins::listEnabled());
                $plugins_disabled = count(Plugins::listInstalled()) - $plugins_active; 
                $plugins_notinstalled = $plugins_all - $plugins_active - $plugins_disabled;

                $themes_all = count(WebThemes::newInstance()->getListThemes());
            ?>
            <div class="grid-row">
                <div class="row-wrapper">
                    <div class="widget-box cinfo">
                        <div class="widget-box-title"><h3><i class="fa fa-info-circle"></i> <?php _e('Themes & Plugins information'); ?></h3></div>
                        <div class="widget-box-content">
                            <p><strong><?php _e('All plugins'); ?>:</strong> <span><?php echo $plugins_all; ?></span></p>
                            <p><strong><?php _e('Enabled plugins'); ?>:</strong> <span><?php echo $plugins_active; ?></span></p>
                            <p><strong><?php _e('Disabled plugins'); ?>:</strong> <span><?php echo $plugins_disabled; ?></span></p>
                            <p><strong><?php _e('Not installed plugins'); ?>:</strong> <span><?php echo $plugins_notinstalled; ?></span></p>
                            <p><strong><?php _e('All themes'); ?>:</strong> <span><?php echo $themes_all; ?></span></p>
                            <p><strong><?php _e('Inactive themes'); ?>:</strong> <span><?php echo $themes_all - 1; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-row">
                <div class="row-wrapper">
                    <div class="widget-box cinfo">
                        <div class="widget-box-title"><h3><i class="fa fa-info-circle"></i> <?php _e('Server information'); ?></h3></div>
                        <div class="widget-box-content">
                            <p><strong><?php _e('PHP version'); ?>:</strong> <span><?php echo phpversion(); ?></span></p>
                            <p><strong><?php _e('Memory limit'); ?>:</strong> <span><?php echo ini_get('memory_limit'); ?></span></p>
                            <p><strong><?php _e('Max execution time'); ?>:</strong> <span><?php echo ini_get('max_execution_time'); ?>s</span></p>
                            <p><strong><?php _e('Upload max file size'); ?>:</strong> <span><?php echo ini_get('upload_max_filesize'); ?></span></p>
                            <p><strong><?php _e('Max input vars'); ?>:</strong> <span><?php echo ini_get("max_input_vars"); ?></span></p>
                            <p><strong><?php _e('Allow URL Fopen'); ?>:</strong> <span><?php echo (ini_get('allow_url_fopen') ? __('Enabled') : __('Disabled')); ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-row">
                <div class="row-wrapper">
                    <div class="widget-box cinfo">
                        <div class="widget-box-title"><h3><i class="fa fa-info-circle"></i> <?php _e('Database information'); ?></h3></div>
                        <div class="widget-box-content">
                            <p><strong><?php _e('MySQL version'); ?>:</strong> <span><?php echo $mysql_version; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-row">
                <div class="row-wrapper">
                    <div class="widget-box cinfo">
                        <div class="widget-box-title"><h3><i class="fa fa-info-circle"></i> <?php _e('Your information'); ?></h3></div>
                        <div class="widget-box-content">
                            <p><strong><?php _e('Your IP'); ?>:</strong> <span><?php echo $_SERVER['REMOTE_ADDR']; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="clear"></div>

    <div id="backup-setting">
        <div id="backup-settings">
            <div id="phpinfo"><?php echo $phpinfo; ?></div>
        </div>
    </div>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>