<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
} if (!osc_is_admin_user_logged_in()) {
    die;
}

$sp = new spam_prot;
$sub = Params::getParam('sub');
$table = Params::getParam('table');
$files = Params::getParam('files');

$yes = '<span class="true">'.__("Yes", "spamprotection").'</span>';
$no  = '<span class="false">'.__("No", "spamprotection").'</span>';

if ($files == 'clear') {
    $sp->_clearAlertFiles();
} elseif ($files == 'check') {
    if ($sp->_checkFiles(true)) {
        header('Location: '.osc_admin_base_url(true)."?page=plugins&action=renderplugin&file=spamprotection/admin/main.php&tab=sp_tools&sub=files");
        exit;    
    }
}
?>
<div class="settings">

    <ul class="subtabs sp_tabs">
        <li class="subtab-link <?php echo (empty($sub) || $sub == 'badtrusted' ? 'current' : ''); ?>" data-tab="sp_tools_badtrusted"><a><?php _e('Bad/Trusted User', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (!isset($sub) || $sub == 'ipban' ? 'current' : ''); ?>" data-tab="sp_tools_ipban"><a><?php _e('IP Ban', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (!isset($sub) || $sub == 'tor' ? 'current' : ''); ?>" data-tab="sp_tools_tor"><a><?php _e('TOR Network', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (!isset($sub) || $sub == 'files' ? 'current' : ''); ?>" data-tab="sp_tools_files"><a><?php _e('File monitor', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_tools_save"><button type="submit" class="btn btn-info"><?php _e('Save', 'spamprotection'); ?></button></li>
    </ul>

    <div id="sp_badtrusted_options" class="sp_badtrusted_options">
        <div id="sp_tools_badtrusted" class="subtab-content <?php echo (empty($sub) || $sub == 'badtrusted' ? 'current' : ''); ?>">

            <fieldset>
                <legend><?php _e("Bad/Trusted User", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_badtrusted_activate" value="1"<?php if (!empty($data['sp_badtrusted_activate'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Activate the Bad/Trusted User Feature', 'spamprotection'); ?>
                    </label><br />
                    <small>
                        <?php _e('This Features allows you to set user to bad- or trusted lists. There you can define actions they are always trusted or forbidden.', 'spamprotection'); ?>
                        <ul>
                            <li>&raquo; <strong><?php _e('Trusted User', 'spamprotection'); ?></strong> - <?php _e('This actions are always trusted and won\'t checked for spam', 'spamprotection'); ?></li>
                            <li>&raquo; <strong><?php _e('Bad User', 'spamprotection'); ?></strong> - <?php _e('This actions cannot be done anymore, the user will always be blocked for this.', 'spamprotection'); ?></li>
                        </ul>
                    </small>
                </div>
            </fieldset>

            <fieldset id="bot_table"<?php echo (empty($data['sp_badtrusted_activate']) || $data['sp_badtrusted_activate'] == '0' ? ' style="display: none;"' : ''); ?>>
                <legend><?php _e("Bad/Trusted User Lists", "spamprotection"); ?></legend>
                <div class="row form-group" style="position: relative;">
                    <ul class="langtabs" style="padding: 0;">
                        <li class="langtab-link <?php echo (empty($table) || $table == 'trusteduser' ? 'current' : ''); ?>" data-tab="trusteduser"><a><?php _e('Trusted User', 'spamprotection'); ?></a></li>
                        <li class="langtab-link <?php echo (isset($table) && $table == 'baduser' ? 'current' : ''); ?>" data-tab="baduser"><a><?php _e('Bad User', 'spamprotection'); ?></a></li>
                    </ul>

                    <a id="add_bad_or_trusted" class="btn btn-green"><?php _e("Organize", "spamprotection"); ?></a>

                    <div id="trusteduser" class="langtab-content <?php echo (empty($table) || $table == 'trusteduser' ? 'current' : ''); ?>">
                        <table class="badtrusted">
                            <thead>
                                <tr>
                                    <td class="name"><?php _e('Name', 'spamprotection'); ?></td>
                                    <td class="email"><?php _e('Email', 'spamprotection'); ?></td>
                                    <td class="ads"><?php _e('Ads', 'spamprotection'); ?></td>
                                    <td class="comments"><?php _e('Comm.', 'spamprotection'); ?></td>
                                    <td class="actions">
                                        <?php _e('Trusted', 'spamprotection'); ?><br />
                                        <?php _e('Ads', 'spamprotection'); ?>
                                        <?php _e('Comm.', 'spamprotection'); ?>
                                        <?php _e('Cont.', 'spamprotection'); ?>
                                    </td>
                                <tr>
                            </thead>
                            <tbody>
                                <?php
                                    $trusted = $sp->_getResult('t_sp_users', array('key' => 'i_reputation', 'value' => '2'));

                                    if ($trusted) {
                                        foreach($trusted as $v) { 
                                            $user = User::newInstance()->findByPrimaryKey($v['pk_i_id']);
                                            if (isset($v['s_reputation']) && !empty($v['s_reputation'])) {
                                                $rep = unserialize($v['s_reputation']);
                                            }  ?>
                                            <tr>
                                                <td class="name"><?php echo $user['s_name']; ?></td>
                                                <td class="email"><?php echo $user['s_email']; ?></td>
                                                <td class="ads"><?php echo $user['i_items']; ?></td>
                                                <td class="comments"><?php echo $user['i_comments']; ?></td>
                                                <td class="actions">
                                                    <input type="checkbox" name="trusted[<?php echo $v['pk_i_id']; ?>][trustedads]" value="1"<?php echo (isset($rep['trustedads']) ? ' checked="checked"' : ''); ?> />
                                                    <input type="checkbox" name="trusted[<?php echo $v['pk_i_id']; ?>][trustedcomments]" value="1"<?php echo (isset($rep['trustedcomments']) ? ' checked="checked"' : ''); ?> />
                                                    <input type="checkbox" name="trusted[<?php echo $v['pk_i_id']; ?>][trustedcontacts]" value="1"<?php echo (isset($rep['trustedcontacts']) ? ' checked="checked"' : ''); ?> />
                                                </td>
                                            <tr>
                                        <?php }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="baduser" class="langtab-content <?php echo (isset($table) && $table == 'baduser' ? 'current' : ''); ?>">
                        <table class="badtrusted">
                            <thead>
                                <tr>
                                    <td class="name"><?php _e('Name', 'spamprotection'); ?></td>
                                    <td class="email"><?php _e('Email', 'spamprotection'); ?></td>
                                    <td class="ads"><?php _e('Ads', 'spamprotection'); ?></td>
                                    <td class="comments"><?php _e('Comm.', 'spamprotection'); ?></td>
                                    <td class="actions">
                                        <?php _e('Forbidden', 'spamprotection'); ?><br />
                                        <?php _e('Ads', 'spamprotection'); ?>
                                        <?php _e('Comm.', 'spamprotection'); ?>
                                        <?php _e('Cont.', 'spamprotection'); ?>
                                    </td>
                                <tr>
                            </thead>
                            <tbody>
                                <?php
                                    $bad = $sp->_getResult('t_sp_users', array('key' => 'i_reputation', 'value' => '1'));
                                    if ($bad) {
                                        foreach($bad as $v) {
                                            $user = User::newInstance()->findByPrimaryKey($v['pk_i_id']);
                                            if (isset($v['s_reputation']) && !empty($v['s_reputation'])) {
                                                $rep = unserialize($v['s_reputation']);
                                            } ?>
                                            <tr>
                                                <td class="name"><?php echo $user['s_name']; ?></td>
                                                <td class="email"><?php echo $user['s_email']; ?></td>
                                                <td class="ads"><?php echo $user['i_items']; ?></td>
                                                <td class="comments"><?php echo $user['i_comments']; ?></td>
                                                <td class="actions">
                                                    <input type="checkbox" name="bad[<?php echo $v['pk_i_id']; ?>][badads]" value="1"<?php echo (isset($rep['badads']) ? ' checked="checked"' : ''); ?> />
                                                    <input type="checkbox" name="bad[<?php echo $v['pk_i_id']; ?>][badcomments]" value="1"<?php echo (isset($rep['badcomments']) ? ' checked="checked"' : ''); ?> />
                                                    <input type="checkbox" name="bad[<?php echo $v['pk_i_id']; ?>][badcontacts]" value="1"<?php echo (isset($rep['badcontacts']) ? ' checked="checked"' : ''); ?> />
                                                </td>
                                            <tr>
                                        <?php }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="addBadOrTrustedUser" class="addBadOrTrusted" style="display: none;">
                        <div id="BadOrTrusted-inner">
                            <span><?php _e("Organize bad and trusted users", "spamprotection"); ?></span>
                            <a href="<?php echo osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/main.php&tab=sp_tools&sub=badtrusted'); ?>" id="BadOrTrusted-close">x</a>

                            <div id="BadOrTrusted-head">
                                <div class="form-group" style="width: 50%; float: right; padding-right: 20px;">
                                    <label><?php _e("Search for name, email or location", "spamprotection"); ?></label>
                                    <input type="text" name="searchNewTrusted" onkeypress="return event.keyCode != 13;" />
                                    <input type="hidden" id="search_file" value="<?php echo osc_ajax_plugin_url('spamprotection/functions/search.php'); ?>" />
                                </div>
                                <div style="clear: both;"></div>
                            </div>

                            <div id="BadOrTrusted-body">
                                <table style="width: calc(100% - 40px);margin: 20px auto;">
                                    <thead id="tableTrustedUser">
                                        <tr>
                                            <td style="min-width: 30px"><?php _e('Type', 'spamprotection'); ?></td>
                                            <td style="width: 250px"><?php _e('Name', 'spamprotection'); ?></td>
                                            <td style="width: calc(100% - 370px)"><?php _e('Email', 'spamprotection'); ?></td>
                                            <td style="width: 90px"><?php _e('Actions', 'spamprotection'); ?></td>
                                        </tr>
                                    </thead>
                                    <tbody id="trusted-body">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </fieldset>

        </div>
    </div>

    <div id="sp_ipban_options" class="sp_ipban_options">
        <div id="sp_tools_ipban" class="subtab-content <?php echo (isset($sub) && $sub == 'ipban' ? 'current' : ''); ?>">

            <fieldset>
                <legend><?php _e("IP Ban", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <div class="halfrow" style="width: 44%;">
                        <label>
                            <input type="checkbox" name="sp_ipban_activate" value="1"<?php if (!empty($data['sp_ipban_activate'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Activate the IP Ban Function', 'spamprotection'); ?>
                        </label><br />
                        <small>
                            <?php _e("Check this option to activate the IP Ban. Choose your favorite action to the right and add unwanted IP's to the table.", "spamprotection"); ?>
                        </small>
                    </div>
                    <div class="halfrow" style="width: 48%;">

                        <div id="IpBanCreateFile" style="width: 55%; float:left; padding: 0;">

                            <?php if (file_exists(osc_base_path().'forbidden.php')) { ?>

                            <label for="sp_ipban_redirect">
                                <input type="radio" name="sp_ipban_redirect" value="1"<?php if (!isset($data['sp_ipban_redirect']) || $data['sp_ipban_redirect'] == '1') { echo ' checked="checked"'; } ?> />
                                <?php _e('Use standard file', 'spamprotection'); ?>
                            </label><br />
                            <small><?php echo '../forbidden.php'; ?></small>

                            <?php } else { ?>

                            <div>
                                <?php _e('Create standard file', 'spamprotection'); ?><br />
                                <a id="openCreateFile" class="btn btn-blue" href="<?php echo osc_ajax_plugin_url('spamprotection/functions/ipban.php&createFile=1'); ?>"><?php _e('Create', 'spamprotection'); ?></a>
                                <div style="clear: both;"></div>   
                            </div>

                            <?php } ?>
                        </div>
                        <div style="width: 45%; float:left; padding: 0;">
                            <label for="sp_ipban_redirect">
                                <input type="radio" name="sp_ipban_redirect" value="404"<?php if (isset($data['sp_ipban_redirect']) && $data['sp_ipban_redirect'] == '404') { echo ' checked="checked"'; } ?> />
                                <?php _e('Cause 404 Error', 'spamprotection'); ?>
                            </label><br />
                            <label for="sp_ipban_redirect">
                                <input type="radio" name="sp_ipban_redirect" value="500"<?php if (isset($data['sp_ipban_redirect']) && $data['sp_ipban_redirect'] == '500') { echo ' checked="checked"'; } ?> />
                                <?php _e('Cause 500 Error', 'spamprotection'); ?>
                            </label><br />
                        </div>

                        <div style="clear: both;"></div>                            

                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="row form-group">
                    <div class="halfrow" style="width: 44%;">                                
                        <span style="float: left;">                                
                            <i class="sp-icon info margin-right"></i>
                        </span>                                
                        <span style="float: left;">                                
                            <small>
                                <?php _e('If you want to redirect users to another location, enter URL here.', 'spamprotection'); ?><br />
                                <strong><?php _e('Don\'t use your base domain, it will cause redirect loops.', 'spamprotection'); ?></strong>
                            </small>
                        </span>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="halfrow" style="width: 48%;">
                        <label for="sp_ipban_redirectURL">
                            <input type="radio" name="sp_ipban_redirect" value="2"<?php if (isset($data['sp_ipban_redirect']) && $data['sp_ipban_redirect'] == '2') { echo ' checked="checked"'; } ?> />
                            <?php _e('Or redirect banned users to', 'spamprotection'); ?><br />
                            <input type="text" name="sp_ipban_redirectURL" placeholder="Enter URL" value="<?php if (isset($data['sp_ipban_redirectURL'])) { echo $data['sp_ipban_redirectURL']; } ?>" />
                        </label>
                    </div>                        
                    <div style="clear: both;"></div>
                </div>

            </fieldset>

            <fieldset style="position: relative;">
                <legend><?php _e("IP Ban Table", "spamprotection"); ?></legend>
                <div style="position: absolute;top: 15px;right: 0;">
                    <a id="addIpToBan" href="<?php echo osc_ajax_plugin_url('spamprotection/functions/ipban.php&do=add'); ?>"><i class="btn btn-green ico ico-32 ico-add-white float-right" style="float: right;width: 11px;height: 16px;margin-top: 5px;"></i></a>
                    <input id="addIpBan" name="addIpBan" placeholder="<?php _e('Enter IP', 'spamprotection'); ?>" style="float: right;margin: 5px 5px 0 0;height: 29px;border-radius: 3px;border: 1px solid #999;padding: 2px;" />    
                </div>                                                                                        
                <div class="row form-group">
                    <table class="ipban" style="margin-top: 50px;">
                        <thead>
                            <td style="width: 40px;"></td>
                            <td style="width: 200px;"><?php _e("IP", "spamprotection"); ?></td>
                            <td><?php _e("Date added", "spamprotection"); ?></td>
                        </thead>
                        <tbody id="dataIpBan">
                        <?php
                            $ips = spam_prot::newInstance()->_listIpBanTable();
                            if (isset($ips) && is_array($ips)) {
                                foreach($ips as $k => $v) {
                                    echo '
                                    <tr>
                                        <td><a class="deleteIpBan" href="'.osc_ajax_plugin_url('spamprotection/functions/ipban.php&do=delete').'" data-ip="'.$k.'"><i class="sp-icon delete xs"></i></a></td>
                                        <td>'.$k.'</td>
                                        <td>'.date("d.m.Y H:i:s", $v).'</td>
                                    </tr>
                                    ';
                                }
                            } else {
                                echo '<tr><td colspan="3"><h3>No IP\'s saved</h3></td></tr>';
                            }    
                        ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>

            <div id="IpBanFlash" style="display: none;"></div>
        </div>
    </div>

    <div id="sp_tor_options" class="sp_tor_options">
        <div id="sp_tools_tor" class="subtab-content <?php echo (isset($sub) && $sub == 'ipban' ? 'current' : ''); ?>">

            <fieldset>
                <legend><?php _e("TOR Network Protection"); ?></legend>
                <div class="row form-group">
                    <div class="halfrow">
                        <label>
                            <input type="checkbox" name="sp_tor_activate" value="1"<?php if (!empty($data['sp_tor_activate'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Activate the TOR Network Protection', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('This will activate the TOR Network Protection. Disable this to deactivate the whole Protection.', 'spamprotection'); ?></small>
                        <br /><br />
                        <label>
                            <input type="checkbox" name="sp_tor_notify" value="1"<?php if (!empty($data['sp_tor_notify'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Send notification', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('Activate this to send a notification to the user that the action is blocked because of using TOR Network.', 'spamprotection'); ?></small>
                    </div>
                    <div class="halfrow">
                        <label>
                            <?php _e('Refresh TOR Network Nodes list', 'spamprotection'); ?>
                        </label><br />

                        <select id="sp_tor_cron" name="sp_tor_cron">
                            <option value="1"<?php if (empty($data['sp_tor_cron']) || $data['sp_tor_cron'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Every hour', 'spamprotection'); ?></option>
                            <option value="2"<?php if (!empty($data['sp_tor_cron']) && $data['sp_tor_cron'] == '2') { echo ' selected="selected"'; } ?>><?php _e('One time per day', 'spamprotection'); ?></option>
                            <option value="3"<?php if (!empty($data['sp_tor_cron']) && $data['sp_tor_cron'] == '3') { echo ' selected="selected"'; } ?>><?php _e('One time per week', 'spamprotection'); ?></option>
                        </select>

                        <br /><br />

                        <div class="">
                            <i class="fa fa-info"></i> <span>To manually refresh the list of TOR Network Nodes open <a target="_blank" href="https://check.torproject.org/cgi-bin/TorBulkExitList.py?ip=<?php echo $_SERVER['SERVER_ADDR']; ?>">this link</a> and put the content to:</span>
                            <p><small><?php echo osc_plugin_path('spamprotection/tor_nodes.txt'); ?></small></p>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Disabled actions for TOR Network user"); ?></legend>
                <div class="row form-group">
                    <div class="halfrow">
                        <label>
                            <input type="checkbox" name="sp_tor_ads" value="1"<?php if (!empty($data['sp_tor_ads'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Disable new ads for TOR user', 'spamprotection'); ?>
                        </label><br />
                        <label>
                            <input type="checkbox" name="sp_tor_login" value="1"<?php if (!empty($data['sp_tor_login'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Disable login for TOR user', 'spamprotection'); ?>
                        </label><br />
                        <label>
                            <input type="checkbox" name="sp_tor_registration" value="1"<?php if (!empty($data['sp_tor_registration'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Disable registration for TOR user', 'spamprotection'); ?>
                        </label>
                    </div>

                    <div class="halfrow">
                        <label>
                            <input type="checkbox" name="sp_tor_comments" value="1"<?php if (!empty($data['sp_tor_comments'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Disable comments for TOR user', 'spamprotection'); ?>
                        </label><br />
                        <label>
                            <input type="checkbox" name="sp_tor_contact" value="1"<?php if (!empty($data['sp_tor_contact'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Disable contact mails for TOR user', 'spamprotection'); ?>
                        </label>
                    </div>
                </div>
            </fieldset>

        </div>
    </div>

    <div id="sp_files_options" class="sp_files_options">
        <div id="sp_tools_files" class="subtab-content <?php echo (isset($sub) && $sub == 'files' ? 'current' : ''); ?>">

            <ul class="langtabs">
                <li class="langtab-link current" data-tab="tab-file-monitor"><a><?php _e("File Monitor", "spamprotection"); ?></a></li>
                <li class="langtab-link" data-tab="tab-file-settings"><a><?php _e("Settings", "spamprotection"); ?></a></li>
                <li class="langtab-link" data-tab="tab-file-check" style="float: right;"><a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=files&files=check'); ?>" class="btn btn-red" style="padding: 3px 8px; color: white !important;"><?php _e("Check Now", "spamprotection"); ?></a></li>
            </ul>

            <div id="tab-file-monitor" class="langtab-content current">                
                <?php 
                    echo spam_prot::newInstance()->_listFilesChanges(); 
                ?>    
            </div>

            <div id="tab-file-settings" class="langtab-content">
                <fieldset>
                    <legend><?php _e("Settings", "spamprotection"); ?></legend>

                    <div class="row form-group" style="padding: 25px;">
                        <label>
                            <input type="checkbox" name="sp_files_activate" value="1"<?php if (!empty($data['sp_files_activate'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Activate file system monitor', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('If this option is activated, Osclass will check the whole file system for potentially changed files and inform you about.', 'spamprotection'); ?></small>
                    </div>
                    <div class="row form-group">

                        <div class="halfrow">
                            <label><?php _e("Scan path: ", "spamprotection"); echo '<small>'.osc_base_path().'</small>'; ?></label><br />
                            <input type="text" name="sp_files_directory" value="<?php echo (isset($data['sp_files_directory']) ? $data['sp_files_directory'] : osc_base_path()); ?>" placeholder="<?php echo sprintf(__('Scanned path: %s', 'spamprotection'), osc_base_path()); ?>" />

                            <br /><br />

                            <label><?php _e("Excluded file extensions", "spamprotection"); ?></label><br />
                            <input type="text" name="sp_files_extensions" value="<?php echo (isset($data['sp_files_extensions']) ? $data['sp_files_extensions'] : 'jpg,jpeg,gif,png,css,scss,zip,txt,log'); ?>" placeholder="<?php _e("Enter extensions you want to exclude (comma separated)", "spamprotection"); ?>" />

                            <br /><br />

                            <label><?php _e("Send alerts to", "spamprotection"); ?></label><br />
                            <input type="text" name="sp_files_alerts" value="<?php echo (isset($data['sp_files_alerts']) ? $data['sp_files_alerts'] : osc_contact_email()); ?>" placeholder="<?php _e("Enter Email address where are alerts sended to.", "spamprotection"); ?>" />

                            <br /><br />

                            <label><?php _e("Check Filesystem", "spamprotection"); ?></label><br />
                            <div class="floating">
                                <select id="sp_files_interval" name="sp_files_interval">
                                    <option value="1"<?php if (empty($data['sp_files_interval']) || $data['sp_files_interval'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Every hour', 'spamprotection'); ?></option>
                                    <option value="2"<?php if (!empty($data['sp_files_interval']) && $data['sp_files_interval'] == '2') { echo ' selected="selected"'; } ?>><?php _e('One time per day', 'spamprotection'); ?></option>
                                    <option value="3"<?php if (!empty($data['sp_files_interval']) && $data['sp_files_interval'] == '3') { echo ' selected="selected"'; } ?>><?php _e('One time per week', 'spamprotection'); ?></option>
                                </select>
                            </div>
                            <div class="floating" style="float: right; margin: 0;">
                                <a id="sp_files_check" class="btn btn-info" style="padding: 5px;" href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=files&files=check'); ?>"><?php _e("Check Now", "spamprotection"); ?></a>
                            </div>
                        </div>

                        <div class="halfrow" style="position: relative;">
                            <i id="sp_add_exclude_dir" class="btn btn-green ico ico-32 ico-add-white float-right" style="position: absolute;width: 11px;height: 16px;top: 0;right: 0;transform: scale(0.5);"></i>
                            <label><?php _e("Exclude directories: ", "spamprotection"); echo '<small>'.osc_base_path().'</small>'; ?></label><br />
                            <div id="sp_excluded_directories">
                            <?php
                            if (isset($data['sp_files_exclude'])) {
                                $dirs = unserialize($data['sp_files_exclude']);
                                foreach((array) $dirs as $exclude) {
                                ?>
                                <input type="text" name="sp_files_exclude[]" value="<?php echo (isset($exclude) ? $exclude : ''); ?>" placeholder="<?php _e("Enter path you want to exclude from monitoring", "spamprotection"); ?>" />
                                <?php
                                }
                            } else {
                                ?>
                                <input type="text" name="sp_files_exclude[]" value="oc-content/uploads" placeholder="<?php _e("Enter path you want to exclude from monitoring", "spamprotection"); ?>" />
                                <?php
                            }
                            ?>
                            </div>
                            <script>
                            $(document).ready(function(){
                                $(document).on("click", "#sp_add_exclude_dir", function(event){
                                    event.preventDefault();
                                    $("#sp_excluded_directories").append('<input type="text" name="sp_files_exclude[]" value="" placeholder="<?php _e("Enter path you want to exclude from monitoring", "spamprotection"); ?>" />');
                                });
                            });
                            </script>
                        </div>

                    </div>

                </fieldset>
            </div>

            <div id="tab-file-check" class="langtab-content" style="width: 250px; margin: 10% auto; text-align: center;">
                <h1 style="display: inline-block;"><i style="margin: 0 20px 0 -20px" class="sp-icon download margin-right float-left rotateY"></i><?php _e("<strong>Scanning...</strong>", "spamprotection"); ?></h1> 
                <div style="font-size: 18px;"><?php _e("Checking file system, please be patient.", "spamprotection"); ?></div> 
            </div>

        </div>
    </div>

    <div class="sp_tools_save">
        <div id="sp_tools_save" class="subtab-content" style="width: 250px; margin: 10% auto; text-align: center;">
            <h1 style="display: inline-block;"><i style="margin: 0 20px 0 -20px" class="sp-icon attention margin-right float-left rotateX"></i><?php _e("<strong>Saving</strong>", "spamprotection"); ?></h1> 
            <div style="font-size: 18px;"><?php _e("Saving data, please be patient.", "spamprotection"); ?></div> 
        </div>
    </div>

    <div class="sp_tools_filessave">
        <div id="sp_tools_filessave" class="subtab-content" style="width: 250px; margin: 10% auto; text-align: center;">
            <h1 style="display: inline-block;"><i style="margin: 0 20px 0 -20px" class="sp-icon download margin-right float-left rotateY"></i><?php _e("<strong>Scanning...</strong>", "spamprotection"); ?></h1> 
            <div style="font-size: 18px;"><?php _e("Checking file system, please be patient.", "spamprotection"); ?></div> 
        </div>
    </div>

</div>
