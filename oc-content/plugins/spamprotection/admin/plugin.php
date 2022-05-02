<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
} if (!osc_is_admin_user_logged_in()) {
    die;
}

$sp = new spam_prot;
$path = osc_plugin_path('spamprotection/export');
$url = osc_base_url().'oc-content/plugins/spamprotection';
$js_path = osc_plugin_path('spamprotection/assets/js');
$data = $sp->_get(false, true);

if (isset($data['sp_mailtemplates'])) {
    $mail = unserialize($data['sp_mailtemplates']);    
}


if (Params::getParam('subtab')) {
    $subtab = Params::getParam('subtab');        
} if (Params::getParam('create_path') == 'true') {
    $path = osc_plugin_path('spamprotection/export/');
    if (!mkdir($path, 0755) && !is_dir($path)) {
        $create_error = '<div id="flash">'.$sp->_showPopup(
            '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error.</strong>", "spamprotection").'</h1>', 
            '<div style="font-size: 18px;">'.__("Can't create folder, please create manually and grant write access.", "spamprotection").'</div>',
            '',
            1500,
            false,
            false,
            'style="width: 400px;"'
        ).'</div>';
    }        
} elseif (Params::getParam('chmod_path') == 'true') {
    $path = osc_plugin_path('spamprotection/export/');
    $chmod = Params::getParam('chmod');

    if (is_numeric($chmod)) {
        if (!chmod($path, $chmod)) {
            $chmod_error = '<div id="flash">'.$sp->_showPopup(
                '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error.</strong>", "spamprotection").'</h1>', 
                '<div style="font-size: 18px;">'.__("Can't grant write access to path, please change permissions manually.", "spamprotection").'</div>',
                '',
                1500,
                false,
                false,
                'style="width: 400px;"'
            ).'</div>';
        }
    } else {    
        $chmod_error = '<div id="flash">'.$sp->_showPopup(
            '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error.</strong>", "spamprotection").'</h1>', 
            '<div style="font-size: 18px;">'.__("Please enter which chmod settings should be applied for export path", "spamprotection").'</div>',
            '',
            1500,
            false,
            false,
            'style="width: 400px;"'
        ).'</div>';    
    }
} elseif (Params::getParam('export')) {
    $subtab = 'export';
    $export = spam_prot::newInstance()->_export(Params::getParam('export'));
} elseif (Params::getParam('import')) {
    $subtab = 'import';
    $import = spam_prot::newInstance()->_import(Params::getParam('import'), 'server');
} elseif (Params::getParam('upload_exportfile')) {
    $subtab = 'import';
    $ext = pathinfo($_FILES['sp_import']['name'], PATHINFO_EXTENSION);                
    if (!in_array($ext, array('xml'))) {
        $import = '<div id="flash">'.$sp->_showPopup(
                '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error.</strong>", "spamprotection").'</h1>', 
                '<div style="font-size: 18px;">'.__("Only xml files generated through this plugin are allowed", "spamprotection").'</div>',
                '',
                1500,
                false,
                false,
                'style="width: 400px;"'
            ).'</div>'; 
    } else {
        $import = spam_prot::newInstance()->_import($_FILES['sp_import']['tmp_name'], 'upload');
    }    
} elseif (Params::getParam('delete')) {
    $delete = Params::getParam('delete');
    if (!empty($delete)) {
        if (!unlink($path.'/'.$delete)) {
            $delete_error = '<div id="flash">'.$sp->_showPopup(
                '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error.</strong>", "spamprotection").'</h1>', 
                '<div style="font-size: 18px;">'.sprintf(__("Can't delete %s. Maybe missing write permission?", "spamprotection"), $delete).'</div>',
                '',
                1500,
                false,
                false,
                'style="width: 400px;"'
            ).'</div>';    
        }
    } else {    
        $delete_error = '<div id="flash">'.$sp->_showPopup(
            '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error.</strong>", "spamprotection").'</h1>', 
            '<div style="font-size: 18px;">'.__("There is no filename given to delete!", "spamprotection").'</div>',
            '',
            1500,
            false,
            false,
            'style="width: 400px;"'
        ).'</div>';
    }
}

if (Params::getParam('plugin_settings') == 'save') {
    $params = Params::getParamsAsArray('', false);
    if ($sp->_saveSettings($params, 'plugin')) {
        ob_get_clean();
        $message = $sp->_showPopup(
            '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Success</strong>", "spamprotection").'</h1>', 
            '<div style="font-size: 18px;">'.__("Settings saved.", "spamprotection").'</div>',
            '',
            1500,
            false,
            false,
            'style="width: 400px;"'
            );

        osc_add_flash_ok_message($message, 'admin');
        osc_admin_render_plugin( osc_plugin_folder(__FILE__) . 'main.php&tab='.$params['tab']);    
    } else {
        ob_get_clean();
        $message = $sp->_showPopup(
            '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error.</strong>", "spamprotection").'</h1>', 
            '<div style="font-size: 18px;">'.__("Your settings can not be saved.", "spamprotection").'</div>',
            '',
            false,
            true,
            false,
            'style="width: 400px;"'
            );

        osc_add_flash_ok_message($message, 'admin');
        osc_admin_render_plugin( osc_plugin_folder(__FILE__) . 'main.php&tab='.$params['tab']);    
    }
}

if (Params::getParam('save_mailtemplates') == 'true') {
    $params = Params::getParamsAsArray();
    $subtab = Params::getParam('subtab');

    $save = array(
        'sp_mailuser_user' => $params['sp_mailuser_user'],
        'sp_titleuser_user' => $params['sp_titleuser_user'],
        'sp_mailuser_admin' => $params['sp_mailuser_admin'],
        'sp_titleuser_admin' => $params['sp_titleuser_admin'],
        'sp_mailadmin_user' => $params['sp_mailadmin_user'],
        'sp_titleadmin_user' => $params['sp_titleadmin_user'],
        'sp_mailadmin_admin' => $params['sp_mailadmin_admin'],
        'sp_titleadmin_admin' => $params['sp_titleadmin_admin']
    );

    if ($sp->_saveSettings($params, 'mails')) {
        ob_get_clean();
        $message = $sp->_showPopup(
            '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Success</strong>", "spamprotection").'</h1>', 
            '<div style="font-size: 18px;">'.__("Settings saved.", "spamprotection").'</div>',
            '',
            1500,
            false,
            false,
            'style="width: 400px;"'
            );

        osc_add_flash_ok_message($message, 'admin');
        osc_admin_render_plugin( osc_plugin_folder(__FILE__) . 'main.php&tab='.$params['tab']);    
    } else {
        ob_get_clean();
        $message = $sp->_showPopup(
            '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error.</strong>", "spamprotection").'</h1>', 
            '<div style="font-size: 18px;">'.__("Your settings can not be saved.", "spamprotection").'</div>',
            '',
            false,
            true,
            false,
            'style="width: 400px;"'
            );

        osc_add_flash_ok_message($message, 'admin');
        osc_admin_render_plugin( osc_plugin_folder(__FILE__) . 'main.php&tab='.$params['tab']);    
    }

}
$target = Params::getParam('target');
$target2 = Params::getParam('target2');
$testmail = Params::getParam('testmail');

if (isset($target) && isset($target2) && isset($testmail)) {
    if ($sp->_testMail($target, $target2, $testmail)) {
        $return = true;
    } else {
        $return = false;
    }   
}

$import_files = array_diff(scandir($path), array('..', '.', 'index.php'));
?>
<div class="settings">

    <ul class="subtabs sp_tabs">
        <li class="subtab-link <?php echo (!isset($subtab) || $subtab == 'settings' ? 'current' : ''); ?>" data-tab="sp_config_settings"><a><?php _e('Settings', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (isset($subtab) && $subtab == 'mailtemplates' ? 'current' : ''); ?>" data-tab="sp_config_mailtemplates"><a><?php _e('Mail Templates', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (isset($subtab) && $subtab == 'export' ? 'current' : ''); ?>" data-tab="sp_config_export"><a><?php _e('Export', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (isset($subtab) && $subtab == 'import' ? 'current' : ''); ?>" data-tab="sp_config_import"><a><?php _e('Import', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (isset($subtab) && $subtab == 'log' ? 'current' : ''); ?>" data-tab="sp_config_log"><a><?php _e('Global Log', 'spamprotection'); ?></a></li>
    </ul>

    <div id="sp_config_options" class="sp_config_options">

        <div id="sp_config_settings" class="subtab-content <?php echo (!isset($subtab) || $subtab == 'settings' ? 'current' : ''); ?>">
            <form action="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php'); ?>" method="post">
                <input type="hidden" name="page" value="plugins" />
                <input type="hidden" name="tab" id="sp_tab" value="sp_config" />
                <input type="hidden" name="subtab" id="sp_subtab" value="settings" />
                <input type="hidden" name="action" value="renderplugin" />
                <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>main.php" />
                <input type="hidden" name="plugin_settings" value="save" />    

                <button type="submit" class="btn btn-info" style="float: right;margin-top: 15px;margin-bottom: -15px;"><?php _e('Save', 'spamprotection'); ?></button>
                <div style="clear:both;"></div>

                <fieldset>
                    <legend><?php _e("Plugin appearance", "spamprotection"); ?></legend>
                    <div class="row form-group" style="height: 50px;">
                        <label>
                            <?php _e('Theme', 'spamprotection'); ?>
                        </label><br />
                        <select id="sp_theme" name="sp_theme">
                            <option value="black"<?php if (empty($data['sp_theme']) || $data['sp_theme'] == 'black') { echo ' selected="selected"'; } ?>><?php _e('Dark', 'spamprotection'); ?></option>
                            <option value="white"<?php if (!empty($data['sp_theme']) && $data['sp_theme'] == 'white') { echo ' selected="selected"'; } ?>><?php _e('White', 'spamprotection'); ?></option>
                        </select>
                    </div>
                </fieldset>

                <fieldset>
                    <legend><?php _e("Menu appearance", "spamprotection"); ?></legend>
                    <div class="row form-group" style="height: 50px;">
                        <div class="halfrow" style="width: 50%; padding: 0;">
                            <label>
                                <input type="checkbox" name="sp_activate_menu" value="1"<?php if (!empty($data['sp_activate_menu'])) { echo ' checked="checked"'; } ?> />
                                <?php _e('Show icon in sidebar', 'spamprotection'); ?>
                            </label><br />
                            <small><?php _e('This option allows to show an icon for this plugin in your admin sidebar.', 'spamprotection'); ?></small>
                        </div>
                        <div id="sp_menu_appearance_cont" class="halfrow" style="width: 50%; padding: 0;<?php if (empty($data['sp_activate_menu']) || $data['sp_activate_menu'] != '1') { echo ' display: none;'; } ?>">
                            <label>
                                <?php _e('Show icon after', 'spamprotection'); ?>
                            </label><br />
                            <select id="sp_menu_after" name="sp_menu_after">
                                <option value="menu_dash"<?php if (empty($data['sp_menu_after']) || $data['sp_menu_after'] == 'menu_dash') { echo ' selected="selected"'; } ?>><?php _e('Dashboard', 'spamprotection'); ?></option>
                                <option value="menu_items"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'menu_items') { echo ' selected="selected"'; } ?>><?php _e('Items', 'spamprotection'); ?></option>
                                <option value="menu_market"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'menu_market') { echo ' selected="selected"'; } ?>><?php _e('Market', 'spamprotection'); ?></option>
                                <option value="menu_appearance"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'menu_appearance') { echo ' selected="selected"'; } ?>><?php _e('Appearance', 'spamprotection'); ?></option>
                                <option value="menu_plugins"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'menu_plugins') { echo ' selected="selected"'; } ?>><?php _e('Plugins', 'spamprotection'); ?></option>
                                <option value="menu_stats"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'menu_stats') { echo ' selected="selected"'; } ?>><?php _e('Statistics', 'spamprotection'); ?></option>
                                <option value="menu_settings"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'menu_settings') { echo ' selected="selected"'; } ?>><?php _e('Settings', 'spamprotection'); ?></option>
                                <option value="menu_pages"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'menu_pages') { echo ' selected="selected"'; } ?>><?php _e('Pages', 'spamprotection'); ?></option>
                                <option value="menu_users"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'menu_users') { echo ' selected="selected"'; } ?>><?php _e('Users', 'spamprotection'); ?></option>
                                <option value="anywhere"<?php if (!empty($data['sp_menu_after']) && $data['sp_menu_after'] == 'anywhere') { echo ' selected="selected"'; } ?>><?php _e('Anywhere', 'spamprotection'); ?></option>
                            </select>
                        </div>

                        <div style="clear:both;"></div>
                    </div>

                    <div class="row form-group" style="height: 50px;">
                        <div class="halfrow" style="width: 50%; padding: 0;">
                            <label>
                                <input type="checkbox" name="sp_activate_topicon" value="1"<?php if (!empty($data['sp_activate_topicon'])) { echo ' checked="checked"'; } ?> />
                                <?php _e('Show icon in topbar', 'spamprotection'); ?>
                            </label><br />
                            <small><?php _e('This option allows to show an icon for this plugin in your admin topbar.', 'spamprotection'); ?></small>
                        </div>
                        <div id="sp_topicon_appearance_cont" class="halfrow" style="width: 50%; padding: 0;<?php if (empty($data['sp_activate_topicon']) || $data['sp_activate_topicon'] != '1') { echo ' display: none;'; } ?>">
                            <label>
                                <?php _e('Show icon', 'spamprotection'); ?>
                            </label><br />
                            <select id="sp_topicon_position" name="sp_topicon_position">
                                <option value="left"<?php if (empty($data['sp_topicon_position']) || $data['sp_topicon_position'] == 'left') { echo ' selected="selected"'; } ?>><?php _e('Left', 'spamprotection'); ?></option>
                                <option value="right"<?php if (!empty($data['sp_topicon_position']) && $data['sp_topicon_position'] == 'right') { echo ' selected="selected"'; } ?>><?php _e('Right', 'spamprotection'); ?></option>
                            </select>
                        </div>

                        <div style="clear:both;"><br /></div>
                    </div>

                    <div id="sp_activate_pulsemenu_cont" class="row form-group" style="height: 50px;<?php if (!isset($data['sp_activate_menu']) && !isset($data['sp_activate_topicon'])) { echo ' display: none;'; } ?>">    
                        <div class="halfrow" style="width: 50%; padding: 0;">
                            <label>
                                <input type="checkbox" name="sp_activate_pulsemenu" value="1"<?php if (!empty($data['sp_activate_pulsemenu'])) { echo ' checked="checked"'; } ?> />
                                <?php _e('Pulse icon on activity', 'spamprotection'); ?>
                            </label><br />
                            <small><?php _e('This option lets pulse the menu icon if some spam was found.', 'spamprotection'); ?></small>
                        </div>

                        <div style="clear:both;"></div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend><?php _e("Show buttons", "spamprotection"); ?></legend>
                    <div class="halfrow" style="width: 50%; padding: 10px 0;">
                        <label>
                            <input type="checkbox" name="sp_activate_topbar" value="1"<?php if (!empty($data['sp_activate_topbar'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Show buttons in top menu', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('This option activates buttons in your dashboard top bar, everytime if some spam or ban was found.', 'spamprotection'); ?></small>
                    </div>
                    <div class="halfrow" style="width: 50%; padding: 10px 0;">
                        <label>
                            <?php _e('Show Buttons as', 'spamprotection'); ?>
                        </label><br />
                        <select id="sp_topbar_type" name="sp_topbar_type">
                            <option value="text"<?php if (empty($data['sp_topbar_type']) || $data['sp_topbar_type'] == 'text') { echo ' selected="selected"'; } ?>><?php _e('Text', 'spamprotection'); ?></option>
                            <option value="icon"<?php if (!empty($data['sp_topbar_type']) && $data['sp_topbar_type'] == 'icon') { echo ' selected="selected"'; } ?>><?php _e('Icon', 'spamprotection'); ?></option>
                        </select>
                    </div>
                </fieldset>

                <fieldset>
                    <legend><?php _e("Update check", "spamprotection"); ?></legend>
                    <div class="row form-group">
                        <label>
                            <input type="checkbox" name="sp_update_check" value="1"<?php if (!empty($data['sp_update_check'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Check database after update', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('This option checks the database for required changes after each manually or automatically update.', 'spamprotection'); ?></small>
                    </div>
                </fieldset>

                <fieldset id="globallog">
                    <legend><?php _e("Global log settings", "spamprotection"); ?></legend>
                    <div class="row form-group">
                        <div class="halfrow" style="padding: 10px 0;">
                            <label>
                                <input type="checkbox" name="sp_globallog_activate" value="1"<?php if (!empty($data['sp_globallog_activate'])) { echo ' checked="checked"'; } ?> />
                                <?php _e('Activate the global log', 'spamprotection'); ?>
                            </label><br />
                            <small><?php _e('If this option is activated, all activities of this plugin will be logged in the global log.', 'spamprotection'); ?></small>
                        </div>
                        <div class="halfrow" style="padding: 10px 0;">
                            <label style="line-height: 28px;">
                                <?php _e('Standard log limit', 'spamprotection'); ?>
                                <input list="sp_limit_globallog" type="text" class="form-control" name="sp_globallog_limit" style="width: 50px;" value="<?php echo (isset($data['sp_globallog_limit']) && !empty($data['sp_globallog_limit']) ? $data['sp_globallog_limit'] : ''); ?>" /> <span><?php _e('Logs', 'spamprotection'); ?></span>
                                <datalist id="sp_limit_globallog">
                                    <option value="25"><?php _e("Show 25 log entries", "spamprotection"); ?></option>
                                    <option value="50"><?php _e("Show 50 log entries", "spamprotection"); ?></option>
                                    <option value="100"><?php _e("Show 100 log entries", "spamprotection"); ?></option>
                                </datalist>
                            </label><br />
                            <small><?php _e('This would be the standard, how many logs are shown at once. You can change this on the global log page also.', 'spamprotection'); ?></small>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="halfrow" style="padding: 10px 0;">
                            <label style="line-height: 28px;">
                                <?php _e('Automatically delete logs after', 'spamprotection'); ?>
                            </label><br />
                            <select name="sp_globallog_lifetime" >
                                <option value="0"<?php if (empty($data['sp_globallog_lifetime']) || $data['sp_globallog_lifetime'] == '0') { echo ' selected="selected"'; } ?>><?php _e("Disabled", "spamprotection"); ?></option>
                                <option value="1 day"<?php if (!empty($data['sp_globallog_lifetime']) && $data['sp_globallog_lifetime'] == '1 day') { echo ' selected="selected"'; } ?>><?php _e("1 day", "spamprotection"); ?></option>
                                <option value="1 week"<?php if (!empty($data['sp_globallog_lifetime']) && $data['sp_globallog_lifetime'] == '1 week') { echo ' selected="selected"'; } ?>><?php _e("1 week", "spamprotection"); ?></option>
                                <option value="1 month"<?php if (!empty($data['sp_globallog_lifetime']) && $data['sp_globallog_lifetime'] == '1 month') { echo ' selected="selected"'; } ?>><?php _e("1 month", "spamprotection"); ?></option>
                                <option value="6 month"<?php if (!empty($data['sp_globallog_lifetime']) && $data['sp_globallog_lifetime'] == '6 month') { echo ' selected="selected"'; } ?>><?php _e("6 month", "spamprotection"); ?></option>
                            </select>
                        </div>
                        <div class="halfrow" style="padding: 10px 0;">
                            <label>&nbsp;</label><br />
                            <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/').'main.php&tab=sp_config&sub=settings&globallog=clear'; ?>" class="btn btn-red"><?php _e('Clear now', 'spamprotection'); ?></a>
                        </div>
                    </div>
                </fieldset>    

                <button type="submit" class="btn btn-info" style="float: right;margin-top: -5px;"><?php _e('Save', 'spamprotection'); ?></button>
                <div style="clear:both;"></div>

            </form>
        </div>

        <div id="sp_config_mailtemplates" class="subtab-content <?php echo (isset($subtab) && $subtab == 'mailtemplates' ? 'current' : ''); ?>">

            <h3><?php _e("Here you can edit the mail templates, which will be used to inform the user about false login attempts", "spamprotection"); ?></h3>
            <p><?php echo sprintf(__("If you don't want to generate your own templates, the standard mail templates will be used. The mails for admins will always be sent to: %s", "spamprotection"), osc_contact_email()); ?></p>

            <br /><hr /><br />

            <fieldset style="border: 1px solid #bbb; padding: 15px; margin: 15px 0; font-size: 14px;">
                <legend><?php _e("For adding important information in your mail, you can use following placeholders:", "spamprotection"); ?></legend>
                <small>
                    <table>
                        <tr><td><strong>{PAGE_NAME}</strong></td><td><?php echo sprintf(__("This is the name of your page (%s)", "spamprotection"), osc_page_title()); ?></td></tr>
                        <tr><td><strong>{MAIL_USER}</strong></td><td><?php _e("This is the name of the user who will receive the mail", "spamprotection"); ?></td></tr>
                        <tr><td><strong>{MAIL_USED}</strong></td><td><?php _e("This is the used mail address for the login attempts", "spamprotection"); ?></td></tr>
                        <tr><td><strong>{MAIL_DATE}</strong></td><td><?php _e("This is the date for the last false login attempt", "spamprotection"); ?></td></tr>
                        <tr><td><strong>{MAIL_IP}</strong></td><td><?php _e("This is the ip which was used for the false login attempt", "spamprotection"); ?></td></tr>
                        <tr><td><strong>{UNBAN_LINK}</strong></td><td><?php _e("This link allows the user to automatically unban his account", "spamprotection"); ?></td></tr>
                        <tr><td><strong>{PASSWORD_LINK}</strong></td><td><?php _e("This link redirect the user to the password recovery page", "spamprotection"); ?></td></tr>
                        <tr><td><strong>{BAN_LIST}</strong></td><td><?php _e("This link redirect the admin to the ban list", "spamprotection"); ?></td></tr>
                    </table>
                </small>
            </fieldset>

            <br /><hr /><br />

            <form action="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php'); ?>" method="post">
                <input type="hidden" name="page" value="plugins" />
                <input type="hidden" name="tab" id="sp_tab" value="sp_config" />
                <input type="hidden" name="subtab" id="sp_subtab" value="mailtemplates" />
                <input type="hidden" name="action" value="renderplugin" />
                <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>main.php" />
                <input type="hidden" name="save_mailtemplates" value="true" />    

                <button type="submit" class="btn btn-info" style="float: right;margin-top: -5px;"><?php _e('Save', 'spamprotection'); ?></button>
                <div style="clear:both;"></div>

                <div id="sp_mail_user_login">                    
                    <fieldset style="border: 1px solid #bbb; padding: 15px; margin: 15px 0;">
                        <legend><?php _e("Configure mail templates for false user logins", "spamprotection"); ?></legend>

                        <div style="float: left; width: calc(50% - 37.5px); padding: 15px;">
                            <h3><strong><?php _e("Send to user", "spamprotection"); ?></strong></h3>

                            <label for="sp_titleuser_user"><?php _e("Subject", "spamprotection"); ?></label>
                            <input name="sp_titleuser_user" style="margin-bottom: 10px;padding: 5px;width: 100%;border-radius: 3px;" placeholder="Too many false logins on <?php echo osc_page_title(); ?>" value="<?php echo (isset($mail['sp_titleuser_user']) ? $mail['sp_titleuser_user'] : ''); ?>" />

                            <label for="sp_mailuser_user"><?php _e("Mail", "spamprotection"); ?></label>
                            <textarea name="sp_mailuser_user" style="width: 100%; height: 150px; border-radius: 3px;"><?php echo (isset($mail['sp_mailuser_user']) ? $mail['sp_mailuser_user'] : ''); ?></textarea>
                            <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&subtab=mailtemplates&target=user&target2=user&testmail='.osc_logged_admin_email()); ?>" class="btn btn-green" style="margin-top: 10px; margin-right: -15px; float: right; padding: 9px 25px 8px;"><?php echo sprintf(__("Send test mail to: %s", "spamprotection"), osc_logged_admin_email()); ?></a>                    
                        </div>

                        <div style="float: left; width: calc(50% - 37.5px); padding: 15px;">
                            <h3><strong><?php _e("Send to admin", "spamprotection"); ?></strong></h3>

                            <label for="sp_titleuser_admin"><?php _e("Subject", "spamprotection"); ?></label>
                            <input name="sp_titleuser_admin" style="margin-bottom: 10px;padding: 5px;width: 100%;border-radius: 3px;" placeholder="Too many false logins on <?php echo osc_page_title(); ?>" value="<?php echo (isset($mail['sp_titleuser_admin']) ? $mail['sp_titleuser_admin'] : ''); ?>" />

                            <label for="sp_mailuser_admin"><?php _e("Mail", "spamprotection"); ?></label>
                            <textarea name="sp_mailuser_admin" style="width: 100%; height: 150px; border-radius: 3px;"><?php echo (isset($mail['sp_mailuser_admin']) ? $mail['sp_mailuser_admin'] : ''); ?></textarea>
                            <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&subtab=mailtemplates&target=user&target2=admin&testmail='.osc_logged_admin_email()); ?>" class="btn btn-green" style="margin-top: 10px; margin-right: -15px; float: right; padding: 9px 25px 8px;"><?php echo sprintf(__("Send test mail to: %s", "spamprotection"), osc_logged_admin_email()); ?></a>                    
                        </div>                        
                    </fieldset>
                </div>

                <div id="sp_mail_admin_login">                    
                    <fieldset style="border: 1px solid #bbb; padding: 15px; margin: 15px 0;">
                        <legend><?php _e("Configure mail templates for false admin logins", "spamprotection"); ?></legend>

                        <div style="float: left; width: calc(50% - 37.5px); padding: 15px;">
                            <h3><strong><?php _e("Send to admin", "spamprotection"); ?></strong></h3>

                            <label for="sp_titleadmin_user"><?php _e("Subject", "spamprotection"); ?></label>
                            <input name="sp_titleadmin_user" style="margin-bottom: 10px;padding: 5px;width: 100%;border-radius: 3px;" placeholder="Too many false logins on <?php echo osc_page_title(); ?>" value="<?php echo (isset($mail['sp_titleadmin_user']) ? $mail['sp_titleadmin_user'] : ''); ?>" />

                            <label for="sp_mailadmin_user"><?php _e("Mail", "spamprotection"); ?></label>
                            <textarea name="sp_mailadmin_user" style="width: 100%; height: 150px; border-radius: 3px;"><?php echo (isset($mail['sp_mailadmin_user']) ? $mail['sp_mailadmin_user'] : ''); ?></textarea>
                            <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&subtab=mailtemplates&target=admin&target2=user&testmail='.osc_logged_admin_email()); ?>" class="btn btn-green" style="margin-top: 10px; margin-right: -15px; float: right; padding: 9px 25px 8px;"><?php echo sprintf(__("Send test mail to: %s", "spamprotection"), osc_logged_admin_email()); ?></a>                    
                        </div>

                        <div style="float: left; width: calc(50% - 37.5px); padding: 15px;">
                            <h3><strong><?php _e("Send to main admin", "spamprotection"); ?></strong></h3>

                            <label for="sp_titleadmin_admin"><?php _e("Subject", "spamprotection"); ?></label>
                            <input name="sp_titleadmin_admin" style="margin-bottom: 10px;padding: 5px;width: 100%;border-radius: 3px;" placeholder="Too many false logins on <?php echo osc_page_title(); ?>" value="<?php echo (isset($mail['sp_titleadmin_admin']) ? $mail['sp_titleadmin_admin'] : ''); ?>" />
                            
                            <label for="sp_mailadmin_admin"><?php _e("Mail", "spamprotection"); ?></label>
                            <textarea name="sp_mailadmin_admin" style="width: 100%; height: 150px; border-radius: 3px;"><?php echo (isset($mail['sp_mailadmin_admin']) ? $mail['sp_mailadmin_admin'] : ''); ?></textarea>
                            <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&subtab=mailtemplates&target=admin&target2=admin&testmail='.osc_logged_admin_email()); ?>" class="btn btn-green" style="margin-top: 10px; margin-right: -15px; float: right; padding: 9px 25px 8px;"><?php echo sprintf(__("Send test mail to: %s", "spamprotection"), osc_logged_admin_email()); ?></a>                    
                        </div>                        
                    </fieldset>                                    
                </div>    

                <button type="submit" class="btn btn-info" style="float: right;margin-top: -5px;"><?php _e('Save', 'spamprotection'); ?></button>
                <div style="clear:both;"></div>

            </form>

        </div>

        <div id="sp_config_export" class="subtab-content <?php echo (isset($subtab) && $subtab == 'export' ? 'current' : ''); ?>">
            <h2><?php _e("Export settings and data", "spamprotection"); ?></h2>
            <fieldset id="#sp_export_file" style="border: 1px solid #bbb; padding: 15px; margin: 15px 0;">
            <?php if (!is_dir($path)) { ?>
                <legend><?php _e("Error", "spamprotection"); ?></legend>
                <div class="sp_export_path_error">
                    <p>
                        <i class="sp-icon attention margin-right float-left"></i>
                        <?php echo sprintf(__("<strong>Following path does not exist, please create and grant write access to:</strong><br /><em>%s</em>", "spamprotection"), $path); ?>
                    </p>
                    <p>
                        <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&&create_path=true&tab=sp_config&subtab=export'); ?>"><button class="btn btn-green"><?php _e("Create Folder", "spamprotection"); ?></button></a>    
                    </p>
                    <?php if (isset($create_error)) { echo '<div id="flash">'.$create_error.'</div>'; } ?>
            <?php } elseif (!is_writable($path)) { ?>
                <legend><?php _e("Error", "spamprotection"); ?></legend>
                <div class="sp_export_path_error">
                    <p>
                        <i class="sp-icon attention margin-right float-left"></i>
                        <?php echo sprintf(__("<strong>Following path is not writable, please ensure that you grant write access to:</strong><br /><em>%s</em>", "spamprotection"), $path); ?>
                    </p>
                    <p>
                        <form action="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php'); ?>" method="post">
                            <input type="hidden" name="page" value="plugins" />
                            <input type="hidden" name="tab" id="sp_tab" value="sp_config" />
                            <input type="hidden" name="subtab" id="sp_subtab" value="export" />
                            <input type="hidden" name="action" value="renderplugin" />
                            <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>main.php" />
                            <input type="hidden" name="chmod_path" value="true" />
                            <div class="form-group" style="margin: 25px 0 0 46px;;">
                                <label><small><?php _e("Here you can define which chmod should be set and try to fix this problem. Otherwise you have to change the permissions manually"); ?></small></label><br />
                                <input type="text" name="chmod" style="margin-right: 10px; width: 75px; height: 24px;" value="<?php echo Params::getParam('chmod'); ?>" placeholder="0755">
                                <button class="btn btn-green"><?php _e("Chmod Folder", "spamprotection"); ?></button>
                            </div>
                        </form>
                    </p>
                    <?php if (isset($chmod_error)) { echo '<div id="flash">'.$chmod_error.'</div>'; } ?>
                </div>
                <?php } else { ?>
                <legend><?php _e("Select for export", "spamprotection"); ?></legend>                
                <div class="halfrow" style="width: 50%; padding: 0;">
                    <p>
                        <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&export=settings'); ?>">
                            <button class="btn btn-green"><?php _e("Export plugin settings", "spamprotection"); ?></button>
                        </a>
                        <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&export=database'); ?>">
                            <button class="btn btn-blue"><?php _e("Export database", "spamprotection"); ?></button>
                        </a>
                    </p>
                    <?php if (isset($export)) { echo $export; } ?>
                </div>                
                <div class="halfrow" style="width: 50%; padding: 0;">
                    <?php if (!empty($import_files)) { ?>
                    <table style="width: 100%;">
                        <thead style="padding-bottom: 5px; margin-bottom: 5px;">
                            <tr>
                                <td style="border-bottom: 1px solid #aaa;"><h3 style="padding: 0px; margin: 0px;"><?php _e("Created exports", "spamprotection"); ?></h3></td>
                                <td style="width: 60px; text-align: center; border-bottom: 1px solid #aaa;"><?php _e("Size", "spamprotection"); ?></td>
                                <td style="width: 75px; text-align: center; border-bottom: 1px solid #aaa;"><?php _e("Date", "spamprotection"); ?></td>
                                <td style="width: 70px; border-bottom: 1px solid #aaa;"></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($import_files as $v) { ?>
                            <tr>
                                <td><strong><em><a href="<?php echo $url.'/export/'.$v; ?>"><?php echo $v; ?></a></em></strong></td>
                                <td style="width: 60px; text-align: center;"><?php echo number_format(filesize($path.'/'.$v)/1000,2).'kb'; ?></td>
                                <td style="width: 75px; text-align: center;"><?php echo date("d.m.Y", filectime($path.'/'.$v)); ?></td>
                                <td style="width: 70px; text-align: right;">
                                    <a class="sp-icon delete small float-right" href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&subtab=export&delete='.$v); ?>" title="<?php _e("Delete", "spamprotection"); ?>">

                                    </a>
                                    <a class="sp-icon download small float-right" href="<?php echo $url.'/export/'.$v; ?>" title="<?php _e("Download", "spamprotection"); ?>" download="<?php echo str_replace(".xml", "", $v).'_'.date("d.m.Y\_H.i.s", time()).'.xml'; ?>">

                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                </div>
                <div style="clear: both;"></div>
                <?php if (isset($delete_error)) { echo $delete_error; } ?>
            <?php } ?>
            </fieldset>
        </div>

        <div id="sp_config_import" class="subtab-content <?php echo (isset($subtab) && $subtab == 'import' ? 'current' : ''); ?>">
            <h2><?php _e("Import settings and data", "spamprotection"); ?></h2>                

            <fieldset id="#sp_import_file" style="border: 1px solid #bbb; padding: 15px; margin: 15px 0;">
            <legend><?php _e("Files on Server", "spamprotection"); ?></legend>
            <?php if (!empty($import_files)) { ?>
                    <p><?php echo sprintf(__("This files can be used for quick import.", "spamprotection"), $path); ?></p>                
                <?php if (file_exists($path.'/settings.xml')) { ?>
                    <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&import=settings'); ?>">
                        <button class="btn btn-green"><?php _e("Import plugin settings", "spamprotection"); ?></button>
                    </a>
                <?php } if (file_exists($path.'/database.xml')) { ?>
                    <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config&import=database'); ?>">
                        <button class="btn btn-blue"><?php _e("Import database", "spamprotection"); ?></button>
                    </a>
                <?php } ?>
                <?php } else { ?>                    
                <p><?php echo sprintf(__("There is no file to import. If you want to import your previous saved files, copy them to:<br /><em>%s</em>", "spamprotection"), $path); ?></p>    
                <?php } ?>
            </fieldset>

            <fieldset id="#sp_import_drop" style="border: 1px solid #bbb; padding: 15px; margin: 15px 0;">
                <legend><?php _e("Upload file for import", "spamprotection"); ?></legend>
                <form action="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="page" value="plugins" />
                    <input type="hidden" name="tab" id="sp_tab" value="sp_config" />
                    <input type="hidden" name="subtab" id="sp_subtab" value="import" />
                    <input type="hidden" name="action" value="renderplugin" />
                    <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>main.php" />
                    <input type="hidden" name="upload_exportfile" value="true" />

                    <h3 id="file-upload-info"><?php _e("Only xml files generated through this plugin allowed", "spamprotection"); ?></h3>
                    <div class="file-upload-container">                        
                        <div class="file-upload-override-button file-button">
                            <?php _e("Choose file for import", "spamprotection"); ?>
                            <input type="file" name="sp_import" class="file-upload-button" id="file-upload-button" />
                        </div>
                        <div id="file-upload-button2"></div>
                        <div style="clear: both;"></div>
                    </div>
                </form>
                <script>
                $("#file-upload-button").change(function () {
                    var fileName = $(this).val().replace('C:\\fakepath\\', '');
                    $("#file-upload-info").html('<?php _e("File ready to import: ", "spamprotection"); ?>'+fileName);
                    $("#file-upload-button2").html('<button type="submit" class="btn btn-green"><?php _e("Upload & Import", "spamprotection"); ?></button>');
                });
                </script>        
            </fieldset>
            <?php if (!empty($import)) { echo $import; } ?>
        </div>

        <div id="sp_config_log" class="subtab-content <?php echo (isset($subtab) && $subtab == 'log' ? 'current' : ''); ?>">
            <?php include_once(osc_plugin_path('spamprotection/functions/log.php')); ?>
        </div>
    </div>
</div>
