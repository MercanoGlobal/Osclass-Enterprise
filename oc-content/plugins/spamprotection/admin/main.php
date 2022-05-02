<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
}

$sp = new spam_prot;
$data = $sp->_get();
$settings = false; $help = false;

if (Params::getParam('globallog') == 'clear') {
    $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());
    $sp->_clearGlobalLog($admin['s_name'], false);
}

if (Params::getParam('spam') == 'activate') {
    $sp->_spamAction('activate', Params::getParam('item'));
    osc_redirect_to(osc_admin_base_url(true).'?page=items');    
} elseif (Params::getParam('spam') == 'block') {
    $sp->_spamAction('block', Params::getParam('user'));
    osc_redirect_to(osc_admin_base_url(true).'?page=items');
} elseif (Params::getParam('htaccess') == 'save') {
    osc_set_preference('htaccess_warning', '1', 'plugin_spamprotection', 'BOOLEAN');
    return true;
}

if (Params::getParam('tab') == 'sp_contact') {
    $contact = true;    
} elseif (Params::getParam('tab') == 'sp_comments') {
    $comments = true;    
} elseif (Params::getParam('tab') == 'sp_security') {
    $security = true;    
}  elseif (Params::getParam('tab') == 'sp_tools') {
    $tools = true;    
} elseif (Params::getParam('tab') == 'sp_help') {
    $help = true;    
} elseif (Params::getParam('tab') == 'sp_config') {
    $config = true;    
} elseif (Params::getParam('tab') == 'sp_log') {
    $log = true;    
} else {
    $settings = true;    
}

if (Params::getParam('settings') == 'save') {
    $params = Params::getParamsAsArray('', false);
    print_r($params);
    if ($sp->_saveSettings($params)) {        
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

elseif (Params::getParam("createFileNow") == '1') {
    $file = file_get_contents(osc_plugin_path('spamprotection/assets/forbidden.php'));
    if (!empty($file)) {
        $write_file = osc_base_path().'forbidden.php';
        if (is_writable(osc_base_path())) {
            if (!file_put_contents($write_file, $file)) {
                echo '
                <div id="flash">'.spam_prot::newInstance()->_showPopup(
                    '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error</strong>", "spamprotection").'</h1>', 
                    '<div style="font-size: 18px;">'.__("Could not create file", "spamprotection").'</div>',
                    '', 1500, false, false, 'style="margin-top: 50px; width: 400px;"').'</div>';
            } else {
                echo '
                <div id="flash">'.spam_prot::newInstance()->_showPopup(
                    '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Success</strong>", "spamprotection").'</h1>', 
                    '<div style="font-size: 18px;">'.__("File created", "spamprotection").'</div>',
                    '', 1500, false, false, 'style="margin-top: 50px; width: 400px;"').'</div>';
            }
        } else {
            echo '
            <div id="flash">'.spam_prot::newInstance()->_showPopup(
                '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error</strong>", "spamprotection").'</h1>', 
                '<div style="font-size: 18px;">'.__("Path not writable", "spamprotection").'</div>',
                '', 1500, false, false, 'style="margin-top: 50px; width: 400px;"').'</div>';
        }
    } else {
        echo '
        <div id="flash">'.spam_prot::newInstance()->_showPopup(
            '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Error</strong>", "spamprotection").'</h1>', 
            '<div style="font-size: 18px;">'.__("Source file is empty. You have to create your own standard file", "spamprotection").'</div>',
            '', 1500, false, false, 'style="margin-top: 50px; width: 400px;"').'</div>';
    } 
    echo '
    <script>
        $(document).ready(function(){ 
            $("#IpBanFlash").fadeIn();
        });
    </script>
    ';
}

?>
<div id="spamprot" class="<?php if (isset($data['sp_theme'])) { echo $data['sp_theme']; } ?>">
    <div class="container">
        <ul class="tabs">
            <li class="tab-link<?php if (isset($settings) && $settings) { echo ' current'; } ?>" data-tab="sp_settings"><a><?php _e('Ad Settings', 'spamprotection'); ?></a></li>
            <li class="tab-link<?php if (isset($comments) && $comments) { echo ' current'; } ?>" data-tab="sp_comments"><a><?php _e('Comment Settings', 'spamprotection'); ?></a></li>
            <li class="tab-link<?php if (isset($contact) && $contact) { echo ' current'; } ?>" data-tab="sp_contact"><a><?php _e('Contact Settings', 'spamprotection'); ?></a></li>
            <li class="tab-link<?php if (isset($security) && $security) { echo ' current'; } ?>" data-tab="sp_security"><a><?php _e('Account Settings', 'spamprotection'); ?></a></li>
            <li class="tab-link<?php if (isset($tools) && $tools) { echo ' current'; } ?>" data-tab="sp_tools"><a><?php _e('Tools', 'spamprotection'); ?></a></li>
            <li class="tab-link<?php if (isset($help) && $help) { echo ' current'; } ?>" data-tab="sp_help"><a><?php _e('Help', 'spamprotection'); ?></a></li>
            <li class="tab-link float-right<?php if (isset($config) && $config) { echo ' current'; } ?>" data-tab="sp_config"><a class="sp-icon tools"style="padding: 0;background-color: transparent;border: none;margin: 8px 10px 9px;"></a></li>
        </ul>

        <form id="sp_save_settings" action="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php'); ?>" method="POST">
            <input type="hidden" name="page" value="plugins" />
            <input type="hidden" name="tab" id="sp_tab" value="<?php echo Params::getParam('tab'); ?>" />
            <input type="hidden" name="action" value="renderplugin" />
            <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>main.php" />
            <input type="hidden" name="settings" value="save" />

            <div id="sp_settings" class="tab-content<?php if (isset($settings) && $settings) { echo ' current'; } ?>">
                <?php include_once(osc_plugin_path('spamprotection/admin/settings.php')); ?>    
            </div>

            <div id="sp_comments" class="tab-content<?php if (isset($comments) && $comments) { echo ' current'; } ?>">
                <?php include_once(osc_plugin_path('spamprotection/admin/comments.php')); ?>    
            </div>

            <div id="sp_contact" class="tab-content<?php if (isset($contact) && $contact) { echo ' current'; } ?>">
                <?php include_once(osc_plugin_path('spamprotection/admin/contact.php')); ?>    
            </div>

            <div id="sp_security" class="tab-content<?php if (isset($security) && $security) { echo ' current'; } ?>">
                <?php include_once(osc_plugin_path('spamprotection/admin/security.php')); ?>    
            </div>

            <div id="sp_tools" class="tab-content<?php if (isset($tools) && $tools) { echo ' current'; } ?>">
                <?php include_once(osc_plugin_path('spamprotection/admin/tools.php')); ?>    
            </div>

            <div id="sp_help" class="tab-content<?php if (isset($help) && $help) { echo ' current'; } ?>">
                <?php include_once(osc_plugin_path('spamprotection/admin/help.php')); ?>
            </div>

        </form>

        <div id="sp_config" class="tab-content<?php if (isset($config) && $config) { echo ' current'; } ?>">
            <?php include_once(osc_plugin_path('spamprotection/admin/plugin.php')); ?>
        </div>        
    </div>   
</div>