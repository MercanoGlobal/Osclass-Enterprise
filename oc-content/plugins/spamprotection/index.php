<?php
/*
Plugin Name: Anti Spam & Protection System
Plugin URI: https://github.com/MercanoG/Osclass-Enterprise
Description: Anti Spam & Protection System for Osclass. Secures your ads, comments and contact mails against spam. Protects your login/registration processes, plus many other features.
Version: 2.0.0
Author: Liath
Author URI: https://web.archive.org/web/20200709204718/http://amfearliath.tk/osclass-spam-protection
Short Name: spamprotection
Plugin update URI: spam-protection

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
Version 2, December 2004

Copyright (C) 2004 Sam Hocevar
14 rue de Plaisance, 75014 Paris, France
Everyone is permitted to copy and distribute verbatim or modified
copies of this license document, and changing it is allowed as long
as the name is changed.

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

0. You just DO WHAT THE FUCK YOU WANT TO.

Changelog

1.0.0 - first published version

1.0.1 - removed non existing class

1.1.0 - Configuration page enhanced for better visuality

1.2.0 - System can now check the MX Record from user e-mail address

1.2.1 - Fix of Stopword search

1.2.2 - PHP Warnings removed, Stopwords and blocked Emails are sorted when settings are saved 

1.3.0 - Changed deprecated functions eregi and split. Improved the stopword mechanism

1.3.1 - Adding extra warning, before editing htaccess file. Little changes to some url's for saving the settings.

1.3.2 - Added optional search for duplicates in descriptions. Search algorythm improved. Configuration page changed.

1.3.3 - Global var changed, to prevent error messages

1.3.4 - Stopwords now shown, if ad was blocked for this reason, Search for duplicates improved, translations corrected

1.4.0 - New selectable method added in search for duplicates, item comment protection added, translations corrected. Help section redesigned.

1.4.1 - Wrong button in check ads page removed

1.5.0 - Security settings for login and form protection added

1.5.1 - Removed Email ban for form protection

1.5.2 - Added User ban to check ads page, fix problem with clicking id on check ads page, added time range for search in duplicates, 
        added cron to automatically unban user after defined time 

1.5.3 - Added Ban overview, some code cleanings, correcting translations 

1.6.0 - Redesigned configuration area, admin login protection, import/export for settings and database, plugin settings, update check 
        and registration check been added. Changed plugin name to Anti Spam & Protection System. 

1.6.1 - Added Mail template manager, now mails sended to admin after user/admin has banned

1.6.2 - Added check on StopForumSpam for registrations

1.6.3 - Fixed bugs for import, change category for ads, sending emails, and paths for configuration files and some display errors. 
        Added Subject to Mail templates, Bad/Trusted user list, Topbar Icon, Themechanger, internal ban if found on StopForumSpam

1.6.4 - fixed some smaller issues, duplicates not longer marked false 

1.6.5 - Added IP Ban Table, corrected some sql statements

1.6.6 - Moved some rows from Table t_user to own table, added fix to copy data and delete not needed rows, changed some stylesheets

1.6.7 - Added Database cleaner to automatically delete unwanted ads, comments, user.

1.6.8 - Global Log added. Fixed small issues.

1.6.9 - Settings for global logs added. Fixed small issues.

1.7.0 - Added an option to block messages after an ad is posted and added an option to inform the user, that the ad has to be moderated.

1.7.1 - Fixed some Bugs (https://forums.osclass.org/plugins/(plugin)-spam-protection/msg153469/#msg153469)

1.7.2 - Fixed Bug with Global Log clearance. Added option to block TOR Network user

1.7.3 - Added file system monitor, fixed file creation for ip ban redirecting

1.7.4 - Fixed some Bugs: https://forums.osclass.org/plugins/(plugin)-spam-protection/msg155084/#msg155084

2.0.0   - Corrected some issues (by Tango) - https://github.com/MercanoG/Osclass-Enterprise

*/

require('classes/class.spamprotection.php');
require('functions/index.php');

$sp = new spam_prot;

osc_register_plugin(osc_plugin_path(__FILE__), 'sprot_install');

if (OC_ADMIN) {
    osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'sprot_uninstall');
    osc_add_hook(osc_plugin_path(__FILE__) . '_configure', 'sprot_configuration');

    osc_add_hook('admin_header', 'sprot_style_admin');
    osc_add_hook('admin_footer', 'sprot_style_admin_footer');
    osc_add_hook('init_admin', 'sprot_init');

    if (osc_version() >= 300) {
        osc_add_hook('admin_menu_init', 'sprot_admin_menu_init');
    } else {
        osc_add_hook('admin_menu', 'sprot_admin_menu');
    }
}

if ((!osc_is_admin_user_logged_in() && !OC_ADMIN) && spam_prot::newInstance()->_get('sp_ipban_activate') == '1' && Params::getParam('page') != 'sp_activate_account') {

    osc_add_hook('actions_manage_users', array($sp, '_userRowIpBan'));

    $ip =  spam_prot::newInstance()->_IpUserLogin();
    $ips = spam_prot::newInstance()->_listIpBanTable();
    $file = osc_base_url().'forbidden.php';

    if (is_array($ips)) {
        if (array_key_exists($ip, $ips)) {
            $mode = spam_prot::newInstance()->_get('sp_ipban_redirect');
            $url = spam_prot::newInstance()->_get('sp_ipban_redirectURL');

            if ($mode == '2' && filter_var($url, FILTER_VALIDATE_URL)) {
                $file = $url;
            } elseif ($mode == '404') {
                spam_prot::newInstance()->_addGlobalLog('Banned IP. Redirected to: 404', $ip, 'IP Ban');
                header("HTTP/1.0 404 Not Found");
                exit;
            } elseif ($mode == '500') {
                spam_prot::newInstance()->_addGlobalLog('Banned IP. Redirected to: 500', $ip, 'IP Ban');
                echo non_existing_function();
            } elseif (!file_exists(osc_base_path().'forbidden.php?')) {
                $file = 'https://google.com';
            }
            spam_prot::newInstance()->_addGlobalLog('Banned IP. Redirected to: '.$file, $ip, 'IP Ban');
            osc_redirect_to($file);    
        }
    }    
}

osc_add_hook('header', 'sprot_style');

if (spam_prot::newInstance()->_get('sp_activate') == '1') {

    $trusted = spam_prot::newInstance()->_isBadOrTrusted(osc_logged_user_id(), 'ads', 'trusted');
    $bad = spam_prot::newInstance()->_isBadOrTrusted(osc_logged_user_id(), 'ads', 'bad');

    if ($bad) {
        osc_add_hook('post_item', 'sp_block_baduser_ads');
        osc_add_hook('before_item_edit', 'sp_block_baduser_ads');

    } elseif (!$trusted) {
        if (spam_prot::newInstance()->_get('sp_honeypot') == '1') {
            osc_add_hook('item_form', 'sp_add_honeypot');
            osc_add_hook('item_edit', 'sp_add_honeypot');
        }

        osc_add_hook('posted_item', 'sp_check_item');
        osc_add_hook('edited_item', 'sp_check_item');
    }
}

if (!osc_is_admin_user_logged_in() && spam_prot::newInstance()->_get('sp_comment_activate') == '1') {
    $trusted = spam_prot::newInstance()->_isBadOrTrusted(osc_logged_user_id(), 'comments', 'trusted');
    $bad = spam_prot::newInstance()->_isBadOrTrusted(osc_logged_user_id(), 'comments', 'bad');    

    if ($bad) {
        osc_add_hook('add_comment', 'sp_block_baduser_comment');
        osc_add_hook('edit_comment', 'sp_block_baduser_comment');

    } elseif (!$trusted) {
        osc_add_hook('add_comment', 'sp_check_comment');                        
        osc_add_hook('edit_comment', 'sp_check_comment');
    }
}

if (!osc_is_admin_user_logged_in() && spam_prot::newInstance()->_get('sp_contact_activate') == '1') {
    $trusted = spam_prot::newInstance()->_isBadOrTrusted(osc_logged_user_id(), 'contacts', 'trusted');
    $bad = spam_prot::newInstance()->_isBadOrTrusted(osc_logged_user_id(), 'contacts', 'bad');

    if ($bad) {
        osc_add_hook('item_contact_form', 'sp_block_baduser_contact');

    } elseif (!$trusted) {
        if (spam_prot::newInstance()->_get('sp_contact_honeypot') == '1') {
            osc_add_hook('item_contact_form', 'sp_contact_form');
        }

        osc_add_hook('hook_email_item_inquiry', 'sp_check_contact_item', 1);
        osc_add_hook('hook_email_contact_user', 'sp_check_contact_user', 1);
    }
}

osc_add_hook('delete_comment', 'sp_delete_comment');
osc_add_hook('actions_manage_items', 'sp_compare_items');

if ($sp->_get('sp_security_activate') == '1') {

    osc_add_hook('before_validating_login', 'sp_check_user_login', 1);

    if (spam_prot::newInstance()->_get('sp_security_login_hp') == '1') {
        osc_add_hook('user_login_form', 'sp_add_honeypot_security', 1);
    }
    if (spam_prot::newInstance()->_get('sp_security_register_hp') == '1') {
        osc_add_hook('user_register_form', 'sp_add_honeypot_security', 1);
    }
    if (spam_prot::newInstance()->_get('sp_security_recover_hp') == '1') {
        osc_add_hook('user_recover_form', 'sp_add_honeypot_security', 1);
    }

    if (spam_prot::newInstance()->_get('sp_security_login_unban') > '0') {
        if (spam_prot::newInstance()->_get('sp_security_login_cron') == '1') {
            osc_add_hook('cron_hourly', 'sp_unban_cron');
        } elseif (spam_prot::newInstance()->_get('sp_security_login_cron') == '2') {
            osc_add_hook('cron_daily', 'sp_unban_cron');
        } elseif (spam_prot::newInstance()->_get('sp_security_login_cron') == '3') {
            osc_add_hook('cron_weekly', 'sp_unban_cron');
        }
    }
}

if ($sp->_get('sp_admin_activate') == '1') {
    osc_add_hook('before_login_admin', 'sp_check_admin_login', 0);

    if (spam_prot::newInstance()->_get('sp_admin_login_hp') == '1') {
        osc_add_hook('login_admin_form', 'sp_admin_login', 1);
    }

    if (spam_prot::newInstance()->_get('sp_admin_login_unban') > '0') {
        if (spam_prot::newInstance()->_get('sp_admin_login_cron') == '1') {
            osc_add_hook('cron_hourly', 'sp_unban_cron_admin');
        } elseif (spam_prot::newInstance()->_get('sp_admin_login_cron') == '2') {
            osc_add_hook('cron_daily', 'sp_unban_cron_admin');
        } elseif (spam_prot::newInstance()->_get('sp_admin_login_cron') == '3') {
            osc_add_hook('cron_weekly', 'sp_unban_cron_admin');
        }
    }
}

if ((Params::getParam('sp_check_stopforumspam_mail') == '1' || Params::getParam('sp_check_stopforumspam_ip') == '1') || (Params::getParam('action') == 'register_post' && $sp->_get('sp_check_registrations') >= '2')) {
    osc_add_hook('before_user_register', 'sp_check_user_registrations', 1);

    if ($sp->_get('sp_stopforum_unban') > '0') {
        if (spam_prot::newInstance()->_get('sp_stopforum_cron') == '1') {
            osc_add_hook('cron_hourly', 'sp_cron_stopforum');
        } elseif (spam_prot::newInstance()->_get('sp_stopforum_cron') == '2') {
            osc_add_hook('cron_daily', 'sp_cron_stopforum');
        } elseif (spam_prot::newInstance()->_get('sp_stopforum_cron') == '3') {
            osc_add_hook('cron_weekly', 'sp_cron_stopforum');
        }
    }
}

if (
$sp->_get('sp_delete_expired') == '1' || 
$sp->_get('sp_delete_unactivated') == '1' || 
$sp->_get('sp_delete_spam') == '1' ||
$sp->_get('sp_commdel_unactivated') == '1' || 
$sp->_get('sp_commdel_spam') == '1' ||
$sp->_get('sp_user_unactivated') == '1') {
    osc_add_hook('cron_hourly', array($sp, '_cleanDatabase'));
}

$globallog = $sp->_get('sp_globallog_lifetime');

if (isset($globallog) && $globallog != '0') {
    osc_add_hook('cron_daily', 'sp_cron_globallog');
}

if ($sp->_get('sp_badtrusted_activate') == '1') {
    osc_add_hook('actions_manage_users', array($sp, '_userManageLinks'));
    osc_add_hook('admin_users_table', array($sp, '_userBadTrusted'));
    osc_add_filter('users_processing_row', array($sp, '_userBadTrustedData'));
}

if ($sp->_get('sp_tor_activate')) {

    $cron = $sp->_get('sp_tor_cron');
    if ($cron > '0') {
        if ($cron == '1') {
            osc_add_hook('cron_hourly', array($sp, '_refreshTOR'));
        } elseif ($cron == '2') {
            osc_add_hook('cron_daily', array($sp, '_refreshTOR'));
        } elseif ($cron == '3') {
            osc_add_hook('cron_weekly', array($sp, '_refreshTOR'));
        }
    }

    if ($sp->_get('sp_tor_ads')) {
        osc_add_hook('post_item', array($sp, '_checkTOR'));
        osc_add_hook('before_item_edit', array($sp, '_checkTOR'));
    }
}

/* check file system */
if ($sp->_get('sp_files_activate')) {

    $cron = $sp->_get('sp_files_interval');
    if ($cron > '0') {
        if ($cron == '1') {
            osc_add_hook('cron_hourly', array($sp, '_checkFiles'));
        } elseif ($cron == '2') {
            osc_add_hook('cron_daily', array($sp, '_checkFiles'));
        } elseif ($cron == '3') {
            osc_add_hook('cron_weekly', array($sp, '_checkFiles'));
        }
    }

    if (osc_is_admin_user_logged_in()) {
        osc_add_hook('footer', array($sp, '_showAlertFiles'));
    }
    osc_add_hook('admin_footer', array($sp, '_showAlertFiles'));

}
unset($sp);