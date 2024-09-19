<?php
/*
Plugin Name: Osclass GDPR
Plugin URI: https://github.com/MercanoGlobal/Osclass-Enterprise
Description: GDPR compliance, add agree box to forms, remove account and download user information
Version: 1.2.2
Author: Osclass
Author URI: https://github.com/MercanoGlobal/Osclass-Enterprise
Short Name: gdpr_osclass
Plugin update URI: gdpr_osclass
*/

/*
*   This software may not be resold, redistributed or otherwise conveyed to a third party
*/

/* DEFINES */
define('OSCLASS_GDPR_PATH', dirname(__FILE__) . '/' );

// require_once OSCLASS_GDPR_PATH . 'model/ModelOsclassGdpr.php';

require_once OSCLASS_GDPR_PATH . 'ModelGdpr.php';
require_once OSCLASS_GDPR_PATH . 'class/CustomZip.php'; 
require_once OSCLASS_GDPR_PATH . 'class/OsclassGdpr.php';
require_once OSCLASS_GDPR_PATH . 'class/OsclassGdprAdmin.php';
require_once OSCLASS_GDPR_PATH . 'class/OsclassGdprAssets.php'; 

function gdpr_osclass_call_after_install() {
    osc_set_preference('gdpr_enabled', '0', 'gdpr_osclass');
    osc_set_preference('remove_account_enabled', "0", 'gdpr_osclass');
    osc_set_preference('portability_enabled', "0", 'gdpr_osclass');

    osc_set_preference('portability_download', "0", 'gdpr_osclass');
    osc_set_preference('portability_email_request', "0", 'gdpr_osclass');

    osc_set_preference('terms_is_page', '1', 'gdpr_osclass');
    osc_set_preference('privacy_is_page', '1', 'gdpr_osclass');

    osc_set_preference('agree_text', 'I have read and accept the {TERMS} and the {PRIVACY}', 'gdpr_osclass');
    osc_set_preference('agree_text_default', 'I have read and accept the {TERMS} and the {PRIVACY}', 'gdpr_osclass');

    osc_set_preference('error_agree_text', 'You must accept our "Terms and Conditions" and "Privacy Policy"', 'gdpr_osclass');
    osc_set_preference('error_agree_text_default', 'You must accept our "Terms and Conditions" and "Privacy Policy"', 'gdpr_osclass');
}

function gdpr_osclass_call_after_uninstall() {
    Preference::newInstance()->delete(array('s_section' => 'gdpr_osclass'));
}

osc_register_plugin(osc_plugin_path(__FILE__), 'gdpr_osclass_call_after_install');
osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'gdpr_osclass_call_after_uninstall');
