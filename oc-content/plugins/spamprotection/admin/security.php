<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
} if (!osc_is_admin_user_logged_in()) {
    die;
}

$sp = new spam_prot;
$sub = Params::getParam('sub');
$table = Params::getParam('table');

?>
<div class="settings">

    <ul class="subtabs sp_tabs">
        <li class="subtab-link <?php echo (empty($sub) || $sub == 'user' ? 'current' : ''); ?>" data-tab="sp_security_mainfeatures_user"><a><?php _e('User Protection', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (!isset($sub) || $sub == 'admin' ? 'current' : ''); ?>" data-tab="sp_security_mainfeatures_admin"><a><?php _e('Admin Protection', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (!isset($sub) || $sub == 'register' ? 'current' : ''); ?>" data-tab="sp_security_mainfeatures_register"><a><?php _e('Registrations', 'spamprotection'); ?></a></li>
        <li class="subtab-link <?php echo (!isset($sub) || $sub == 'cleaner' ? 'current' : ''); ?>" data-tab="sp_security_cleaner"><a><?php _e('Cleaner', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_security_save"><button type="submit" class="btn btn-info"><?php _e('Save', 'spamprotection'); ?></button></li>
    </ul>

    <div id="sp_security_options" class="sp_security_options">
        <div id="sp_security_mainfeatures_user" class="subtab-content <?php echo (empty($sub) || $sub == 'user' ? 'current' : ''); ?> <?php echo (empty($data['sp_security_activate']) || $data['sp_security_activate'] == '0' ? 'disabled' : 'enabled'); ?>">

            <fieldset>
                <legend><?php _e("User Protection", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_security_activate" value="1"<?php if (!empty($data['sp_security_activate'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Activate the Form Protection', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option activates the whole form protection. Some features are optional and can be de/activated separately', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("False logins", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <div class="halfrow" style="padding: 0;">
                        <label>
                            <?php _e('Max amount of wrong logins', 'spamprotection'); ?>
                        </label><br />
                        <input type="text" name="sp_security_login_count" style="width: 50px;" value="<?php echo (isset($data['sp_security_login_count']) ? $data['sp_security_login_count'] : '3'); ?>" />
                        <span style="display: inline-block;height: 28px;line-height: 28px;vertical-align: middle;">in</span>
                        <input type="text" name="sp_security_login_time" style="width: 50px;" value="<?php echo (isset($data['sp_security_login_time']) ? $data['sp_security_login_time'] : '30'); ?>" />
                        <span style="display: inline-block;height: 28px;line-height: 28px;vertical-align: middle;">min</span>
                    </div>
                    <div class="halfrow" style="padding: 0;">
                        <div class="floating">                        
                            <label>
                                <?php _e('Unban accounts after', 'spamprotection'); ?>
                            </label><br />
                            <datalist id="sp_security_login_unban_data">
                                <option value="0">Deakt.</option>
                                <option value="60">1 Std.</option>
                                <option value="180">3 Std.</option>
                                <option value="720">12 Std.</option>
                                <option value="1440">1 Tag</option>
                                <option value="4320">3 Tage</option>
                                <option value="21600">15 Tage</option>
                                <option value="43200">1 Monat</option>
                                <option value="259200">6 Monate</option>
                                <option value="518400">1 Jahr</option>
                                <option value="1555200">3 Jahr</option>
                                <option value="2592000">5 Jahr</option>
                            </datalist>
                            <input type="text" list="sp_security_login_unban_data" name="sp_security_login_unban" style="width: 50px;" value="<?php echo (isset($data['sp_security_login_unban']) ? $data['sp_security_login_unban'] : '180'); ?>" />        
                            <span style="display: inline-block;height: 28px;line-height: 28px;vertical-align: middle;">mins</span>
                        </div>
                        <div class="floating">
                            <label>
                                <?php _e('Run cron...', 'spamprotection'); ?>
                            </label><br />
                            <select id="sp_security_login_cron" name="sp_security_login_cron">
                                <option value="1"<?php if (empty($data['sp_security_login_cron']) || $data['sp_security_login_cron'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Every hour', 'spamprotection'); ?></option>
                                <option value="2"<?php if (!empty($data['sp_security_login_cron']) && $data['sp_security_login_cron'] == '2') { echo ' selected="selected"'; } ?>><?php _e('One time per day', 'spamprotection'); ?></option>
                                <option value="3"<?php if (!empty($data['sp_security_login_cron']) && $data['sp_security_login_cron'] == '3') { echo ' selected="selected"'; } ?>><?php _e('One time per week', 'spamprotection'); ?></option>
                            </select>        
                        </div>                
                        <div style="clear: both;"></div>
                        <small><?php _e('Use 0 minutes to disable auto unban', 'spamprotection'); ?></small>
                    </div>

                    <div style="clear: both;"></div>

                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Login limit reached", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <?php _e('Action done after false logins', 'spamprotection'); ?>
                    </label><br />
                    <select id="sp_security_login_action" name="sp_security_login_action">
                        <option value="1"<?php if (empty($data['sp_security_login_action']) || $data['sp_security_login_action'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Disable user account', 'spamprotection'); ?></option>
                        <option value="2"<?php if (!empty($data['sp_security_login_action']) && $data['sp_security_login_action'] == '2') { echo ' selected="selected"'; } ?>><?php _e('Add IP to Banlist', 'spamprotection'); ?></option>
                        <option value="3"<?php if (!empty($data['sp_security_login_action']) && $data['sp_security_login_action'] == '3') { echo ' selected="selected"'; } ?>><?php _e('Both', 'spamprotection'); ?></option>
                    </select>
                </div>
            </fieldset>            

            <fieldset>
                <legend><?php _e("Inform user", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_security_login_inform" value="1"<?php if (!empty($data['sp_security_login_inform'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Inform user how many tries are remaining', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This option allows to inform the user after each false login, how many tries are remainig, before your chosen action is done', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Honeypot", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label><?php _e('Add Honeypot to login/register/recover forms', 'spamprotection'); ?></label><br />
                    <small><?php _e('This Option ads a hidden form field to the login/register/recovery forms. After a bot tap into your trap, action following rules you have set above.', 'spamprotection'); ?></small><br />
                    <div class="floating">
                        <label>
                            <input type="checkbox" name="sp_security_login_hp" value="1"<?php if (!empty($data['sp_security_login_hp'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Login', 'spamprotection'); ?>
                        </label>
                    </div>

                    <div class="floating">
                        <label>
                            <input type="checkbox" name="sp_security_register_hp" value="1"<?php if (!empty($data['sp_security_register_hp'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Registration', 'spamprotection'); ?>
                        </label>
                    </div>

                    <div class="floating">
                        <label>
                            <input type="checkbox" name="sp_security_recover_hp" value="1"<?php if (!empty($data['sp_security_recover_hp'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Password recovery', 'spamprotection'); ?>
                        </label>
                    </div>

                    <div style="clear: both;"></div>

                    <br />
                    <div id="honeypotInfo">
                        <i class="sp-icon info small float-left"></i><?php _e("Important Info", "spamprotection"); ?>
                    </div>
                    <div id="sp_security_login_honeypots" style="display: none;"<?php  echo ' '; ?>>    
                        <br /><hr /><br />
                        <div style="margin-bottom: 15px;">
                            <div class="floating">
                                <i class="sp-icon attention margin-right"></i>
                            </div> 
                            <div class="floating">
                                <?php _e('To make this honeypot works for login and recover pages, you need to add one line of code in each of this files.', 'spamprotection'); ?><br />
                                <strong><?php _e('Insert it right before the closing <em>form</em> tag &lt;/form&gt;', 'spamprotection'); ?></strong>
                            </div>
                            <div style="clear: both;"></div> 
                        </div>

                        <div id="sp_security_login_hp_cont" style="float: left; width: calc(50% - 5px); margin: 0px; padding: 0px 10px 0px 0px;">
                            <strong>../oc-content/themes/yourtheme/user-login.php</strong>
                            <pre><code>&lt;?php osc_run_hook('user_login_form'); ?&gt;</code></pre>

                            <br /><div style="clear: both;"></div><br /><br />

                            <strong style="font-size: 20px;"><?php _e('Example', 'spamprotection'); ?></strong>
                            <pre>
    ...
    &lt;?php osc_run_hook('user_login_form'); ?&gt;
&lt;/form&gt;
                            </pre>
                        </div>

                        <div id="sp_security_recover_hp_cont" style="float: left; width: calc(50% - 5px); margin: 0; padding: 0;">
                            <strong>../oc-content/themes/yourtheme/user-recover.php</strong>
                            <pre><code>&lt;?php osc_run_hook('user_recover_form'); ?&gt;</code></pre>

                            <br /><div style="clear: both;"></div><br /><br />

                            <strong style="font-size: 20px;"><?php _e('Example', 'spamprotection'); ?></strong>
                            <pre>
    ...
    &lt;?php osc_run_hook('user_recover_form'); ?&gt;
&lt;/form&gt;
                            </pre>
                        </div>
                        

                    </div>

                </div>
            </fieldset>            

        </div>
    </div>

    <div id="sp_admin_options" class="sp_admin_options">
        <div id="sp_security_mainfeatures_admin" class="subtab-content <?php echo (isset($sub) && $sub == 'admin' ? 'current' : ''); ?> <?php echo (empty($data['sp_admin_activate']) || $data['sp_admin_activate'] == '0' ? 'disabled' : 'enabled'); ?>">

            <fieldset>
                <legend><?php _e("Admin Protection", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_admin_activate" value="1"<?php if (!empty($data['sp_admin_activate'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Activate the Admin Protection', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option activates the whole admin protection. Some features are optional and can be de/activated separately', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("False logins", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <div class="halfrow" style="padding: 0;">
                        <label>
                            <?php _e('Max amount of wrong logins', 'spamprotection'); ?>
                        </label><br />
                        <input type="text" name="sp_admin_login_count" style="width: 50px;" value="<?php echo (isset($data['sp_admin_login_count']) ? $data['sp_admin_login_count'] : '3'); ?>" />
                        <span style="display: inline-block;height: 28px;line-height: 28px;vertical-align: middle;">in</span>
                        <input type="text" name="sp_admin_login_time" style="width: 50px;" value="<?php echo (isset($data['sp_admin_login_time']) ? $data['sp_admin_login_time'] : '30'); ?>" />
                        <span style="display: inline-block;height: 28px;line-height: 28px;vertical-align: middle;">min</span>
                    </div>
                    <div class="halfrow" style="padding: 0;">
                        <div class="floating">                        
                            <label>
                                <?php _e('Unban accounts after', 'spamprotection'); ?>
                            </label><br />
                            <datalist id="sp_admin_login_unban_data">
                                <option value="0">Deakt.</option>
                                <option value="60">1 Std.</option>
                                <option value="180">3 Std.</option>
                                <option value="720">12 Std.</option>
                                <option value="1440">1 Tag</option>
                                <option value="4320">3 Tage</option>
                                <option value="21600">15 Tage</option>
                                <option value="43200">1 Monat</option>
                                <option value="259200">6 Monate</option>
                                <option value="518400">1 Jahr</option>
                                <option value="1555200">3 Jahr</option>
                                <option value="2592000">5 Jahr</option>
                            </datalist>                                
                            <input type="text" list="sp_admin_login_unban_data" name="sp_admin_login_unban" style="width: 50px;" value="<?php echo (isset($data['sp_admin_login_unban']) ? $data['sp_admin_login_unban'] : '180'); ?>" />        
                            <span style="display: inline-block;height: 28px;line-height: 28px;vertical-align: middle;">mins</span>
                        </div>
                        <div class="floating">
                            <label>
                                <?php _e('Run cron...', 'spamprotection'); ?>
                            </label><br />
                            <select id="sp_admin_login_cron" name="sp_admin_login_cron">
                                <option value="1"<?php if (empty($data['sp_admin_login_cron']) || $data['sp_admin_login_cron'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Every hour', 'spamprotection'); ?></option>
                                <option value="2"<?php if (!empty($data['sp_admin_login_cron']) && $data['sp_admin_login_cron'] == '2') { echo ' selected="selected"'; } ?>><?php _e('One time per day', 'spamprotection'); ?></option>
                                <option value="3"<?php if (!empty($data['sp_admin_login_cron']) && $data['sp_admin_login_cron'] == '3') { echo ' selected="selected"'; } ?>><?php _e('One time per week', 'spamprotection'); ?></option>
                            </select>        
                        </div>                
                        <div style="clear: both;"></div>
                        <small><?php _e('Use 0 minutes to disable auto unban', 'spamprotection'); ?></small>
                    </div>

                    <div style="clear: both;"></div>

                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Login limit reached", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <?php _e('Action done after false logins', 'spamprotection'); ?>
                    </label><br />
                    <select id="sp_admin_login_action" name="sp_admin_login_action">
                        <option value="1"<?php if (empty($data['sp_admin_login_action']) || $data['sp_admin_login_action'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Disable user account', 'spamprotection'); ?></option>
                        <option value="2"<?php if (!empty($data['sp_admin_login_action']) && $data['sp_admin_login_action'] == '2') { echo ' selected="selected"'; } ?>><?php _e('Add IP to Banlist', 'spamprotection'); ?></option>
                        <option value="3"<?php if (!empty($data['sp_admin_login_action']) && $data['sp_admin_login_action'] == '3') { echo ' selected="selected"'; } ?>><?php _e('Both', 'spamprotection'); ?></option>
                    </select>
                </div>
            </fieldset>            

            <fieldset>
                <legend><?php _e("Inform user", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_admin_login_inform" value="1"<?php if (!empty($data['sp_admin_login_inform'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Inform admin how many tries are remaining', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This option allows to inform the admin after each false login, how many tries are remainig, before your chosen action is done', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Honeypot", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_admin_login_hp" value="1"<?php if (!empty($data['sp_admin_login_hp'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Add Honeypot to admin login form', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option ads a hidden form field to the admin login forms. After a bot tap into your trap, action following rules you have set above.', 'spamprotection'); ?></small><br />
                </div>
            </fieldset>

        </div>
    </div>

    <div id="sp_register_options" class="sp_register_options">
        <div id="sp_security_mainfeatures_register" class="subtab-content <?php echo (isset($sub) && $sub == 'register' ? 'current' : ''); ?>">
            
            <fieldset>
                <legend><?php _e("Check registrations", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <?php _e('Select type of registrations check', 'spamprotection'); ?>
                    </label><br />
                    <select id="sp_check_registrations" name="sp_check_registrations">
                        <option value="1"<?php if (empty($data['sp_check_registrations']) || $data['sp_check_registrations'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Deactivate', 'spamprotection'); ?></option>
                        <option value="2"<?php if (!empty($data['sp_check_registrations']) && $data['sp_check_registrations'] == '2') { echo ' selected="selected"'; } ?>><?php _e('Allow only following hoster', 'spamprotection'); ?></option>
                        <option value="3"<?php if (!empty($data['sp_check_registrations']) && $data['sp_check_registrations'] == '3') { echo ' selected="selected"'; } ?>><?php _e('Disallow following hoster', 'spamprotection'); ?></option>
                    </select><br />
                    <small><?php _e('This option allows to define which emails can be used for registering an account on your page.', 'spamprotection'); ?></small>
                    
                    <div id="sp_check_registration_mails" class="hiddeninput<?php if (isset($data['sp_check_registrations']) && $data['sp_check_registrations'] == '2' || $data['sp_check_registrations'] == '3') { echo ' visible'; } ?>">
                        <label for="sp_check_registration_mails"><?php _e('Enter email hoster, separated by , (e.g. mail.ru,gmail.com,yahoo.com)', 'spamprotection'); ?></label><br>
                        <textarea class="form-control" name="sp_check_registration_mails" style="height: 150px;"><?php if (!empty($data['sp_check_registration_mails'])) { echo $data['sp_check_registration_mails']; } ?></textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("StopForumSpam", "spamprotection"); ?></legend>               
                <div class="row form-group">
                    <div class="halfrow">
                        <label>
                            <input type="checkbox" name="sp_check_stopforumspam_mail" value="1"<?php if (isset($data['sp_check_stopforumspam_mail']) && $data['sp_check_stopforumspam_mail'] == '1') { echo ' checked="checked"'; } ?> />
                            <?php _e('Check email address', 'spamprotection'); ?>
                        </label>
                        <br />
                        <label>
                            <input type="checkbox" name="sp_check_stopforumspam_ip" value="1"<?php if (isset($data['sp_check_stopforumspam_ip']) && $data['sp_check_stopforumspam_ip'] == '1') { echo ' checked="checked"'; } ?> />
                            <?php _e('Check IP', 'spamprotection'); ?>
                        </label>
                        <br />
                        <small><?php _e('This options allows to check used emails and IP\'s against StopForumSpam for your registrations', 'spamprotection'); ?></small>
                        <br /><br /><br />                        
                        <label>
                            <input type="checkbox" name="sp_autoban_stopforumspam" value="1"<?php if (isset($data['sp_autoban_stopforumspam']) && $data['sp_autoban_stopforumspam'] = '1') { echo ' checked="checked"'; } ?> />
                            <?php _e('Add Email or IP to Ban list if found on StopForumSpam', 'spamprotection'); ?>
                        </label>
                        <br />
                        <small><?php _e('This will prevent too much traffic on StopForumSpam and minimize your requests.', 'spamprotection'); ?></small>
                        <br /><br />
                        <div>
                            <div class="floating">                        
                                <label>
                                    <?php _e('Unban accounts after', 'spamprotection'); ?>
                                </label><br />
                                <datalist id="sp_stopforum_unban_data">
                                    <option value="0">Deakt.</option>
                                    <option value="60">1 Std.</option>
                                    <option value="180">3 Std.</option>
                                    <option value="720">12 Std.</option>
                                    <option value="1440">1 Tag</option>
                                    <option value="4320">3 Tage</option>
                                    <option value="21600">15 Tage</option>
                                    <option value="43200">1 Monat</option>
                                    <option value="259200">6 Monate</option>
                                    <option value="518400">1 Jahr</option>
                                    <option value="1555200">3 Jahr</option>
                                    <option value="2592000">5 Jahr</option>
                                </datalist>
                                <input type="text" list="sp_stopforum_unban_data" name="sp_stopforum_unban" style="width: 50px;" value="<?php echo (isset($data['sp_stopforum_unban']) ? $data['sp_admin_login_unban'] : '180'); ?>" />        
                                <span style="display: inline-block;height: 28px;line-height: 28px;vertical-align: middle;">mins</span>
                            </div>
                            <div class="floating" style="float: right;">
                                <label>
                                    <?php _e('Run cron...', 'spamprotection'); ?>
                                </label><br />
                                <select id="sp_stopforum_cron" name="sp_stopforum_cron">
                                    <option value="1"<?php if (empty($data['sp_stopforum_cron']) || $data['sp_stopforum_cron'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Every hour', 'spamprotection'); ?></option>
                                    <option value="2"<?php if (!empty($data['sp_stopforum_cron']) && $data['sp_stopforum_cron'] == '2') { echo ' selected="selected"'; } ?>><?php _e('One time per day', 'spamprotection'); ?></option>
                                    <option value="3"<?php if (!empty($data['sp_stopforum_cron']) && $data['sp_stopforum_cron'] == '3') { echo ' selected="selected"'; } ?>><?php _e('One time per week', 'spamprotection'); ?></option>
                                </select>
                            </div>                
                            <div style="clear: both;"></div>
                            <small><?php _e('Use 0 minutes to disable auto unban', 'spamprotection'); ?></small>
                        </div>

                    </div>
                    <div id="sp_stopforumspam_settings" class="halfrow"<?php if (empty($data['sp_check_stopforumspam_mail']) && empty($data['sp_check_stopforumspam_ip'])) { echo ' style="display: none;'; } ?>>
                        <label for="sp_stopforumspam_freq">
                            <?php _e('Max frequency of reports', 'spamprotection'); ?>
                        </label><br />
                        <input type="text" name="sp_stopforumspam_freq" style="width: 50px;" value="<?php echo (isset($data['sp_stopforumspam_freq']) ? $data['sp_stopforumspam_freq'] : '3'); ?>" />
                        <span>
                            <small style="display: inline-block; margin-left: 5px;">
                                <?php _e("(0 - 255)", "spamprotection"); ?><br />
                                <?php _e("3 = Default", "spamprotection"); ?>
                            </small>
                        </span>
                        <br /><br />
                        <label for="sp_stopforumspam_susp">
                            <?php _e('Max percentage of suspiciousness', 'spamprotection'); ?>
                        </label><br />
                        <input type="text" name="sp_stopforumspam_susp" style="width: 50px;" value="<?php echo (isset($data['sp_stopforumspam_susp']) ? $data['sp_stopforumspam_susp'] : '50'); ?>" />
                        <span>
                            <small style="display: inline-block; margin-left: 5px;">
                                <?php _e("(0 = high confidence, 100 = low confidence)", "spamprotection"); ?><br />
                                <?php _e("50 = Default", "spamprotection"); ?>
                            </small>
                        </span>
                        <br /><br />

                        <small><?php _e('Here you can define the max frequency of reports and the percentage of max suspiciousness', 'spamprotection'); ?></small>
                    </div>
                    <div style="clear: both;"></div>                
                </div>
            </fieldset>

        </div>
    </div>

    <div id="sp_cleaner_options" class="sp_cleaner_options">
        <div id="sp_security_cleaner" class="subtab-content <?php echo (isset($sub) && $sub == 'cleaner' ? 'current' : ''); ?>">
            <fieldset>                               
                <legend><?php _e("Delete inactive user accounts", "spamprotection"); ?></legend>                
                <div class="row form-group">                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <label>
                            <input type="checkbox" name="sp_user_unactivated" value="1"<?php if (!empty($data['sp_user_unactivated'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Delete inactive users', 'spamprotection'); ?>
                        </label><br />                    
                        <small><?php _e('Here you can define if inactive users should be deleted automatically after x days.', 'spamprotection'); ?></small>
                    </div>                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('after', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_user_unactivated_after" style="width: 50px;" value="<?php if (!empty($data['sp_user_unactivated_after'])) { echo $data['sp_user_unactivated_after']; } ?>" /> <span>Days</span>
                            </label>
                        </div>
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('Max.', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_user_unactivated_limit" style="width: 50px;" value="<?php if (!empty($data['sp_user_unactivated_limit'])) { echo $data['sp_user_unactivated_limit']; } ?>" /> <span>at once</span>
                            </label>
                        </div>                    
                    </div>                    
                </div>
            </fieldset>

            <fieldset id="settingsUnwantedUser">
                <legend><?php _e("Delete unused user accounts", "spamprotection"); ?></legend>                
                <div class="row form-group">            
                    <div style="float: left; width: calc(33.33333% - 20px); margin: 0 10px;">

                        <div style="float: left; width: calc(40% - 10px); margin: 0 10px 0 0;">
                            <label for="sp_user_minAge"><?php _e('Last Login', 'spamprotection'); ?></label>
                            <input type="text" name="sp_user_minAge" style="cursor: pointer;" value="<?php echo date('Y-m-d', strtotime(date('Y-m-d', time()).' -1 year')); ?>" />
                        </div>
                        <div style="float: left; width: calc(40% - 10px); margin: 0 0 0 10px;">
                            <label for="sp_user_maxAcc"><?php _e('Limit', 'spamprotection'); ?></label>
                            <input type="text" list="sp_user_maxAcc_list" name="sp_user_maxAcc" value="25" />
                            <datalist id="sp_user_maxAcc_list">
                                <option value="25">25 Acc.</option>
                                <option value="50">50 Acc.</option>
                                <option value="100">100 Acc.</option>
                            </datalist>
                        </div>
                        <div style="float: left; width: calc(16% - 10px); margin: 14px 0 0 20px;">
                            <a id="searchUnwantedUser" class="btn btn-blue" data-link="<?php echo osc_ajax_plugin_url('spamprotection/functions/searchUser.php'); ?>"><?php _e("Search", "spamprotection"); ?></a>
                        </div>

                        <div style="clear: both;"></div>

                        <script>
                            $("input[name=sp_user_minAge]").datepicker({
                                maxDate: "-1y",
                                dateFormat: "yy-mm-dd"
                            });
                        </script>
                    </div>          
                    <div style="float: left; width: calc(33.33333% - 20px); margin: 0 10px;"> 
                        <label for="sp_user_banned">
                            <input class="sp_check_unwanted" type="checkbox" name="sp_user_banned" />
                            <strong><?php _e('Must be banned', 'spamprotection'); ?></strong>
                        </label><br />
                        <label for="sp_user_activated">
                            <input class="sp_check_unwanted" type="checkbox" name="sp_user_activated" />
                            <?php _e('Must be an activated account', 'spamprotection'); ?>
                        </label><br />
                        <label for="sp_user_enabled">
                            <input class="sp_check_unwanted" type="checkbox" name="sp_user_enabled" />
                            <?php _e('Must be an enabled account', 'spamprotection'); ?>
                        </label>          
                    </div>                    
                    <div style="float: left; width: calc(33.33333% - 20px); margin: 0 10px;">
                        <label for="sp_user_zeroads">
                            <input class="sp_check_unwanted" type="checkbox" name="sp_user_zeroads" checked="checked" />
                            <?php _e('Must have 0 ads', 'spamprotection'); ?>
                        </label><br />
                        <label for="sp_user_noAdmin">
                            <input class="sp_check_unwanted" type="checkbox" name="sp_user_noAdmin" checked="checked" />
                            <span style="color: red;"><?php _e('User has no admin account', 'spamprotection'); ?></span>
                        </label><br />
                        <label for="sp_user_neverlogged">
                            <input class="sp_check_unwanted" type="checkbox" name="sp_user_neverlogged" checked="checked" />
                            <span style="color: #00bf00;"><?php _e('User has never logged in', 'spamprotection'); ?></span>
                        </label>    
                    </div>

                    <div style="clear: both;"></div>

                </div>

                <div class="row for-group">
                    <div id="printUnwantedUser">

                    </div>
                </div>
            </fieldset>
        </div>

        <div id="sp_security_save" class="subtab-content" style=" width: 250px; margin: 10% auto; text-align: center;">
            <h1 style="display: inline-block;"><i style="margin: 0 20px 0 -20px" class="sp-icon attention margin-right float-left rotateX"></i><?php _e("<strong>Saving</strong>", "spamprotection"); ?></h1> 
            <div style="font-size: 18px;"><?php _e("Saving data, please be patient.", "spamprotection"); ?></div> 
        </div>

    </div>

</div>