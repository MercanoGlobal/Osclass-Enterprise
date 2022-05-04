<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
}

function sprot_admin_page_header($message = false) {
    $info = osc_plugin_get_info("spamprotection/index.php");

    echo '
    <h1 style="display: inline-block;font-size: 20px;line-height: 50px; margin-top: -10px;">'.($message ? $message : '<i class="sp_header_icon" style="margin: 0;"></i>'.sprintf(__('Anti Spam & Protection System', 'spamprotection'). ' v'.$info['version'])).'</h1>
    ';    
}

function sprot_install() {
    if (version_compare(phpversion(), '5.5', '<')) {
        trigger_error("I'm sorry, but you need at least PHP 5.5 to make use of this Plugin", E_USER_ERROR);
    }
    spam_prot::newInstance()->_install();
}

function sprot_uninstall() {
    spam_prot::newInstance()->_uninstall();
}

function sprot_style_admin() {
    $params = Params::getParamsAsArray();

    osc_enqueue_style('spam_protection-upgrade_css', osc_plugin_url('spamprotection/assets/css/admin_general.css').'admin_general.css?'.time());
    osc_register_script('spam_protection-upgrade_js', osc_plugin_url('spamprotection/assets/js/admin_general.js') . 'admin_general.js?'.time(), array('jquery'));
    osc_enqueue_script('spam_protection-upgrade_js');

    if (isset($params['file'])) {
        $plugin = explode("/", $params['file']);
        if (!isset($plugin[2])) {
            $plugin[2] = null;
        }
        $file   = explode(".", $plugin[2]);
        if ($plugin[0] == 'spamprotection') {

            osc_enqueue_style('spam_protection-styles_admin', osc_plugin_url('spamprotection/assets/css/admin_plugin.css').'admin_plugin.css?'.time());

            osc_register_script('spam_protection-admin', osc_plugin_url('spamprotection/assets/js/admin_plugin.js') . 'admin_plugin.js?'.time(), array('jquery'));
            osc_enqueue_script('spam_protection-admin');

            osc_add_hook('admin_page_header','sprot_admin_page_header');
            osc_remove_hook('admin_page_header', 'customPageHeader');    
        }
    }
}

function sprot_style_admin_footer() {
    $menu    = spam_prot::newInstance()->_get('sp_activate_menu');
    $order   = spam_prot::newInstance()->_get('sp_menu_after');
    $pulse   = spam_prot::newInstance()->_get('sp_activate_pulsemenu');
    $topicon = spam_prot::newInstance()->_get('sp_activate_topicon');
    $toppos  = spam_prot::newInstance()->_get('sp_topicon_position');
    
    $items    = spam_prot::newInstance()->_countRows('t_item', array(array('key' => 'b_spam', 'value' => '1'), array('key' => 'b_active', 'value' => '0')));
    $comments = spam_prot::newInstance()->_countRows('t_comment', array('key' => 'b_spam', 'value' => '1'));
    $contacts = spam_prot::newInstance()->_countRows('t_sp_contacts');
    $bans     = spam_prot::newInstance()->_countRows('t_sp_ban_log');

    $installed = spam_prot::newInstance()->_get('sp_first_install');
    if ($installed == '1') {
        spam_prot::newInstance()->_firstInstalled();
    }

    if ($topicon == '1') {
        echo '
        <script>
        $(document).ready(function(){
            '.($toppos == 'left' ? 
            '$("#osc_toolbar_spamprotection").insertBefore("#osc_toolbar_home");' : 
            '$("#osc_toolbar_spamprotection").insertBefore("#osc_toolbar_logout").css({"float" : "right", "margin-right": "33px"});'
            ).'                            

            $("div#osc_toolbar_spamprotection > a").hover(function(){
                $("#osc_toolbar_spamprotection > a > i").addClass("hover");
            },function(){
                $("#osc_toolbar_spamprotection > a > i").removeClass("hover");
            });

            '.($items > 0 || $comments > 0 || $contacts > 0 || $bans > 0 ? '$("#osc_toolbar_spamprotection > a > i").addClass("highlight");' : '').'
            '.($pulse == '1' ? '$("#osc_toolbar_spamprotection > a > i").addClass("pulse");' : '').'
        });
        </script>
        ';
    }

    if ($menu == '1' && $order != 'anywhere') {
        echo '
        <script>
        $(document).ready(function(){
            $("ul.oscmenu li#menu_spamprotection").insertAfter("#sidebar ul.oscmenu > li#'.$order.'").css("display", "");                            
            $("ul#hidden-menus li#menu_spamprotection").remove();                            
            resetLayout();

            $("#sidebar ul.oscmenu > li#menu_spamprotection").hover(function(){
                $(this).addClass("hover");
            },function(){
                $(this).removeClass("hover");
            });

            '.($items > 0 || $comments > 0 || $contacts > 0 || $bans > 0 ? '$("ul.oscmenu li#menu_spamprotection a").addClass("highlight");' : '').'
            '.($pulse == '1' ? '$("ul.oscmenu li#menu_spamprotection a").addClass("pulse");' : '').'
        });
        </script>
        ';
    }
}

function sprot_configuration() {
    osc_admin_render_plugin('spamprotection/admin/main.php');
}

function sprot_init() {
    spam_prot::newInstance()->_admin_menu_draw();
    $upgrade = spam_prot::newInstance()->_get('sp_update_check');

    if ($upgrade == '1') {   
        $check = spam_prot::newInstance()->_upgradeCheck();             
        if (!$check) {        
            spam_prot::newInstance()->_upgradeDatabaseInfo($check);
        }
    }
}

function sprot_admin_menu_init() {
    $sidebar = spam_prot::newInstance()->_get('sp_activate_menu');
    AdminMenu::newInstance()->add_menu_tools(__('Anti Spam & Protection', 'spamprotection'), osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=settings'), 'sprot_admin_settings', 'administrator');

    if ($sidebar == '1') {
        osc_add_admin_menu_page( __('Anti Spam & Protection', 'spamprotection'), osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=settings'), 'spamprotection', 'administrator' );
        osc_add_admin_submenu_divider('spamprotection', __('Pages', 'spamprotection'), 'spamprotection_divider', 'administrator');
        osc_add_admin_submenu_page('spamprotection', __('Dashboard', 'spamprotection'), osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=settings'), 'spamprotection_dashboard', 'administrator');
        osc_add_admin_submenu_page('spamprotection', __('Settings', 'spamprotection'), osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_config'), 'spamprotection_settings', 'administrator');
        osc_add_admin_submenu_page('spamprotection', __('Help', 'spamprotection'), osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_help'), 'spamprotection_help', 'administrator');
    }
}

function sprot_admin_menu() {
    echo '<h3><a href="#">'.__('AntiSpam & SysProt', 'spamprotection').'</a></h3>
    <ul>
        <li><a href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=settings') . '">&raquo; '.__('Settings', 'spamprotection').'</a></li>
        <li><a href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=help') . '">&raquo; '.__('Help', 'spamprotection').'</a></li>
    </ul>';
}

function sp_compare_items($options, $item) {
    $return = $options;
    if ($item['b_spam'] == '1' && $item['b_active'] == '0') {        
        $return[] = '<a href="'.osc_admin_render_plugin_url('spamprotection/admin/check.php&itemid='.$item['pk_i_id']).'">'.__('Check Spam', 'spamprotection').'</a>';
    }
    return $return;
}

function sp_check_admin_login() {

    $data = Params::getParamsAsArray();

    if (isset($data['token'])) {
        $data_token = $data['token'];    
    }
    if (isset($data['user'])) {
        $data_user = $data['user'];    
    }
    if (isset($data['password'])) {
        $data_pass = $data['password'];    
    }


    $admin = Admin::newInstance()->findByUsername($data_user);
    if ($admin && is_array($admin)) { $admin_name = $admin['s_username']; $admin_email = $admin['s_email']; }

    $ip = spam_prot::newInstance()->_IpUserLogin();
    spam_prot::newInstance()->_increaseAdminLogin(($admin ? $admin_name : $data_user));

    $max_logins = spam_prot::newInstance()->_get('sp_admin_login_count');
    $login_trys = spam_prot::newInstance()->_countLogin(($admin ? $admin_name : $data_user), 'admin', $ip);

    if (spam_prot::newInstance()->_checkAdminBan($ip) || !empty($data_token)) {
        ob_get_clean();
        osc_add_flash_error_message(__('<strong>Attention!</strong> Your access was restricted due to too many false login attempts. Please contact the webmaster.', 'spamprotection'), 'admin');    
        header('Location: '.osc_admin_base_url(true)."?page=login");
        exit;
    } elseif ($login_trys < $max_logins && spam_prot::newInstance()->_checkAdminLogin($admin, $data_pass)) {
        spam_prot::newInstance()->_resetAdminLogin($data_user);    
    } elseif (!$admin) {
        if ($login_trys >= $max_logins) {
            spam_prot::newInstance()->_handleAdminLogin($data_user, $ip);
        } if (spam_prot::newInstance()->_get('sp_admin_login_inform') == '1') {
            ob_get_clean();
            osc_add_flash_error_message(__('<strong>Attention!</strong> Your access was restricted due to too many false login attempts. Please contact the webmaster.', 'spamprotection'), 'admin');
        }        
        header('Location: '.osc_admin_base_url(true)."?page=login");
        exit;
    } elseif (!spam_prot::newInstance()->_checkAdminLogin($admin, $data_pass)) {
        if ($login_trys >= $max_logins) {
            spam_prot::newInstance()->_handleAdminLogin($admin_name, $ip);
            if ($login_trys == $max_logins) {
                spam_prot::newInstance()->_informUser($admin_name, 'admin');    
            } if (spam_prot::newInstance()->_get('sp_admin_login_inform') == '1') {
                ob_get_clean();
                osc_add_flash_error_message(__('<strong>Attention!</strong> Your access was restricted due to too many false login attempts. Please contact the webmaster.', 'spamprotection'), 'admin');
            }
        } elseif (empty($login_trys) || $login_trys < $max_logins) {                        
            if (spam_prot::newInstance()->_get('sp_admin_login_inform') == '1') {
                ob_get_clean();
                osc_add_flash_error_message(sprintf(__('<strong>Warning!</strong> Only %d login attempts remaining', 'spamprotection'), ($max_logins-$login_trys)), 'admin');    
            }
        }
        header('Location: '.osc_admin_base_url(true)."?page=login");
        exit;    
    }
}

function sp_admin_login() {
    echo '
    <style>
    .sp_form_field {
        z-index: 999;
        position: absolute;
        height: 0 !important;
        width: 0 !important;
        border: none;
        background: none;
        margin: 0;
        top: -9999;
        left: -9999;
        clear: both;
        font-size: 0px;
        line-height: 0px;
    }
    </style>
    <script>
    jQuery(function($){
        $(document).ready(function(){
            $("#token").addClass("sp_form_field");
        });
    });
    </script>
    <input id="token" type="text" name="token" value="" class="form-control" autocomplete="off">
';
}

function sp_unban_cron_admin() {
    spam_prot::newInstance()->_unbanAdmin();
}

function sp_cron_globallog() {    
    $lifetime = spam_prot::newInstance()->_get('sp_globallog_lifetime');
    spam_prot::newInstance()->_clearGlobalLog('cron', $lifetime);    
}
?>