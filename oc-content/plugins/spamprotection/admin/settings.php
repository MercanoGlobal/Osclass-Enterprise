<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
} if (!osc_is_admin_user_logged_in()) {
    die;
}

$sp = new spam_prot;

$htaccess_file = ABS_PATH.'/.htaccess';
$htaccess_writable = is_writable($htaccess_file);
if ($htaccess_writable) {
    $htaccess_content = file_get_contents($htaccess_file);     
}
?>
<div class="settings">

    <ul class="subtabs sp_tabs">
        <li class="subtab-link current" data-tab="sp_mainfeatures"><a><?php _e('Main Features', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_emailblock"><a><?php _e('E-Mail Block', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_stopwords"><a><?php _e('Stopwords', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_cleaner"><a><?php _e('Cleaner', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_save"><button type="submit" class="btn btn-info"><?php _e('Save', 'spamprotection'); ?></button></li>
    </ul>

    <div id="sp_options" class="sp_options <?php echo (empty($data['sp_activate']) || $data['sp_activate'] == '0' ? 'disabled' : 'enabled'); ?>">
        <div id="sp_mainfeatures" class="subtab-content current">

            <fieldset>
                <legend><?php _e("Ad Settings", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_activate" value="1"<?php if (!empty($data['sp_activate'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Activate the Spam Protection', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option activates the whole Spam Protection. Some features are optional and can be de/activated separately', 'spamprotection'); ?></small>
                </div>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_activate_inform" value="1"<?php if (!empty($data['sp_activate_inform'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Inform user about moderation.', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This option activates a message for the user, that the ad has to be moderated if spam was found.', 'spamprotection'); ?></small>
                </div>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_block_messages" value="1"<?php if (!empty($data['sp_block_messages'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Block all other messages.', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This option will block all other messages they appear after posting/editing an ad.', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Duplicates", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <div class="floating">
                        <label>
                            <?php _e('Check for duplicates', 'spamprotection'); ?>
                        </label><br />
                        <select id="sp_duplicates_as" name="sp_duplicates_as">
                            <option value="0"<?php if (empty($data['sp_duplicates_as']) || $data['sp_duplicates_as'] == '0') { echo ' selected="selected"'; } ?>><?php _e('Deactivated', 'spamprotection'); ?></option>
                            <option value="1"<?php if (!empty($data['sp_duplicates_as']) && $data['sp_duplicates_as'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Per user', 'spamprotection'); ?></option>
                            <option value="2"<?php if (!empty($data['sp_duplicates_as']) && $data['sp_duplicates_as'] == '2') { echo ' selected="selected"'; } ?>><?php _e('All items', 'spamprotection'); ?></option>
                        </select>                        
                    </div>
                    <div class="floating" id="sp_duplicates_cont"<?php if (empty($data['sp_duplicates_as']) || $data['sp_duplicates_as'] == '0') { echo ' style="display: none;"'; } ?>>
                        <label>
                            <?php _e('Search in', 'spamprotection'); ?>
                        </label><br />
                        <select id="sp_duplicates" name="sp_duplicates">
                            <option value="0"<?php if (empty($data['sp_duplicates']) || $data['sp_duplicates'] == '0') { echo ' selected="selected"'; } ?>><?php _e('Only title', 'spamprotection'); ?></option>
                            <option value="1"<?php if (!empty($data['sp_duplicates']) && $data['sp_duplicates'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Title and description', 'spamprotection'); ?></option>
                        </select>                        
                    </div>
                    <div class="floating" id="sp_duplicates_time_cont"<?php if (empty($data['sp_duplicates_as']) || $data['sp_duplicates_as'] != '2') { echo ' style="display: none;"'; } ?>>
                        <label><?php _e('Search in last x days', 'spamprotection'); ?> <small><em><?php _e('(0 to disable)', 'spamprotection'); ?></em></small></label><br />
                        <input type="text" name="sp_duplicates_time" class="form-control" value="<?php echo (isset($data['sp_duplicates_time']) ? $data['sp_duplicates_time'] : '30'); ?>" />                        
                    </div>

                    <div style="clear: both; margin: 10px;"></div>

                    <div class="floating" id="sp_duplicate_type_cont"<?php if (empty($data['sp_duplicates_as']) || $data['sp_duplicates_as'] == '0') { echo ' style="display: none;"'; } ?>>
                        <label>
                            <?php _e('Type of search', 'spamprotection'); ?>
                        </label><br />
                        <select id="sp_duplicate_type" name="sp_duplicate_type">
                            <option value="0"<?php if (empty($data['sp_duplicate_type']) || $data['sp_duplicate_type'] == '0') { echo ' selected="selected"'; } ?>><?php _e('md5/string comparition', 'spamprotection'); ?></option>
                            <option value="1"<?php if (!empty($data['sp_duplicate_type']) && $data['sp_duplicate_type'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Similar text', 'spamprotection'); ?></option>
                        </select>
                    </div>
                    <div class="floating" id="sp_duplicate_percent_cont"<?php if ((empty($data['sp_duplicates_as']) || $data['sp_duplicates_as'] == '0') || (empty($data['sp_duplicate_type']) || $data['sp_duplicate_type'] == '0')) { echo ' style="display: none;"'; } ?>>
                        <label>
                            <?php _e('Similar percent', 'spamprotection'); ?>
                        </label><br />
                        <input type="text" name="sp_duplicate_perc" value="<?php echo (isset($data['sp_duplicate_perc']) ? $data['sp_duplicate_perc'] : '85'); ?>" />
                    </div>
                    <div style="clear: both;"></div>
                    <small><?php _e('This Option enables the System to check new ads for duplicates and mark them as spam', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("MX Record", "spamprotection"); ?></legend>
                <div class="row form-group">                    
                    <label>
                        <input type="checkbox" name="sp_mxr" value="1"<?php if (!empty($data['sp_mxr'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Check MX Record of used Mail', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This option enables the System to check the MX Record of the submitted Email address', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Honeypot", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_honeypot" value="1"<?php if (!empty($data['sp_honeypot'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Activate the Honeypot form field', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option ads a hidden form field to the post page. Bots tap into your trap and can be banned or ignored.', 'spamprotection'); ?></small>
                    <div id="honeypot" class="hiddeninput<?php if (!empty($data['sp_honeypot'])) { echo ' visible'; } ?>">
                        <label for="honeypot_name"><?php _e('Enter the name of the hidden honeypot field', 'spamprotection'); ?> <span id="validname"></span></label><br />
                        <input type="text" class="form-control" name="honeypot_name" value="<?php if (!empty($data['honeypot_name'])) { echo $data['honeypot_name']; } ?>" /><br />
                        <small><?php _e('Good names would be "item_runtime, user_age, price_range or something else, dont name it honeypot ;)', 'spamprotection'); ?></small>                    
                    </div>
                </div>
            </fieldset>
        </div>

        <div id="sp_emailblock" class="subtab-content">
            <fieldset>
                <legend><?php _e("Blocked Mails", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_blocked" value="1"<?php if (!empty($data['sp_blocked'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Block banned E-Mail addresses', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This option enables the System to block ads from banned Email addresses', 'spamprotection'); ?></small>
                    <br /><br />
                    <div id="blocked" class="hiddeninput<?php if (!empty($data['sp_blocked'])) { echo ' visible'; } ?>">
                        <label for="blocked"><?php _e('Enter the blocked E-Mail addresses, separated by ,', 'spamprotection'); ?></label><br />
                        <textarea class="form-control" name="blocked" style="height: 150px;"><?php if (!empty($data['blocked'])) { echo $data['blocked']; } ?></textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Blocked Mail Hoster", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_blocked_tld" value="1"<?php if (!empty($data['sp_blocked_tld'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Block banned E-Mail TLD', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option enables the System to block ads from banned E-Mail Top-Level-Domains (e.g. mail.ru,gmail.com)', 'spamprotection'); ?></small>
                    <br /><br />
                    <div id="blocked_tld" class="hiddeninput<?php if (!empty($data['sp_blocked_tld'])) { echo ' visible'; } ?>">
                        <label for="blocked"><?php _e('Enter the blocked E-Mail TLD, separated by ,', 'spamprotection'); ?></label><br />
                        <textarea class="form-control" name="blocked_tld" style="height: 150px;"><?php if (!empty($data['blocked_tld'])) { echo $data['blocked_tld']; } ?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>

        <div id="sp_stopwords" class="subtab-content">
            <fieldset>
                <legend><?php _e("Stop Words", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <p>
                        <?php _e('Here you can define the search mechanism, how stopwords are checked. You can search for substrings or particular words', 'spamprotection'); ?>
                    </p>
                    <ol style="list-style: disc;">
                        <li>
                            <?php _e('Search for Substrings', 'spamprotection'); ?><br />
                            <?php _e('<small>This method searches in Title/Description for substrings and if found, marks ads as spam (e.g. <em>`are`</em> will be found in <em>`care`</em>)</small>', 'spamprotection'); ?>
                        </li>
                        <li>
                            <?php _e('Search for Words', 'spamprotection'); ?><br />
                            <?php _e('<small>This method searches in Title/Description for particular words and if found, marks ads as spam (e.g. <em>`are`</em> won\'t be found in <em>`care`</em>).</small>', 'spamprotection'); ?>
                        </li>
                    </ol>
                    <select name="sp_blockedtype">
                        <option value="substr"<?php if (empty($data['sp_blockedtype']) || !empty($data['sp_blockedtype']) && $data['sp_blockedtype'] == 'substr') { echo ' selected="selected"'; } ?>><?php _e('Substrings', 'spamprotection'); ?></option>
                        <option value="words"<?php if (!empty($data['sp_blockedtype']) && $data['sp_blockedtype'] == 'words') { echo ' selected="selected"'; } ?>><?php _e('Words', 'spamprotection'); ?></option>
                    </select>
                    <br /><br />
                    <?php _e('<strong>Enter here the words to be blocked in title or descriptions (separated by ,)</strong>', 'spamprotection'); ?>
                    <textarea class="form-control" name="sp_stopwords" style="height: 200px;"><?php if (!empty($data['sp_stopwords'])) { echo $data['sp_stopwords']; } ?></textarea>
                </div>
            </fieldset>
        </div>

        <div id="sp_cleaner" class="subtab-content">            
            <fieldset>
                <legend><?php _e("Delete unwanted listings", "spamprotection"); ?></legend>
                <div class="row form-group">                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <label>
                            <input type="checkbox" name="sp_delete_expired" value="1"<?php if (!empty($data['sp_delete_expired'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Delete expired listings', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('Here you can define if expired listings should be deleted automatically after x days.', 'spamprotection'); ?></small>
                    </div>                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('after', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_delete_expired_after" style="width: 50px;" value="<?php if (!empty($data['sp_delete_expired_after'])) { echo $data['sp_delete_expired_after']; } ?>" /> <span><?php _e('Days', 'spamprotection'); ?></span>
                            </label>
                        </div>
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('Max.', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_delete_expired_limit" style="width: 50px;" value="<?php if (!empty($data['sp_delete_expired_limit'])) { echo $data['sp_delete_expired_limit']; } ?>" /> <span><?php _e('at once', 'spamprotection'); ?></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="row form-group">                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <label>
                            <input type="checkbox" name="sp_delete_unactivated" value="1"<?php if (!empty($data['sp_delete_unactivated'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Delete inactive listings', 'spamprotection'); ?>
                        </label><br />                    
                        <small><?php _e('Here you can define if inactive listings should be deleted automatically after x days.', 'spamprotection'); ?></small>
                    </div>                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('after', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_delete_unactivated_after" style="width: 50px;" value="<?php if (!empty($data['sp_delete_unactivated_after'])) { echo $data['sp_delete_unactivated_after']; } ?>" /> <span><?php _e('Days', 'spamprotection'); ?></span>
                            </label>
                        </div>
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('Max.', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_delete_unactivated_limit" style="width: 50px;" value="<?php if (!empty($data['sp_delete_unactivated_limit'])) { echo $data['sp_delete_unactivated_limit']; } ?>" /> <span><?php _e('at once', 'spamprotection'); ?></span>
                            </label>
                        </div>                    
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="row form-group">                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <label>
                            <input type="checkbox" name="sp_delete_spam" value="1"<?php if (!empty($data['sp_delete_spam'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Delete listings marked as spam', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('Here you can define if as spam marked listings should be deleted automatically after x days.', 'spamprotection'); ?></small>
                    </div>                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('after', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_delete_spam_after" style="width: 50px;" value="<?php if (!empty($data['sp_delete_spam_after'])) { echo $data['sp_delete_spam_after']; } ?>" /> <span><?php _e('Days', 'spamprotection'); ?></span>
                            </label>
                        </div>
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('Max.', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_delete_spam_limit" style="width: 50px;" value="<?php if (!empty($data['sp_delete_spam_limit'])) { echo $data['sp_delete_spam_limit']; } ?>" /> <span><?php _e('at once', 'spamprotection'); ?></span>
                            </label>
                        </div>                    
                    </div>                    
                </div>
            </fieldset>
        </div>

        <div id="sp_save" class="subtab-content" style=" width: 250px; margin: 10% auto; text-align: center;">
            <h1 style="display: inline-block;"><i style="margin: 0 20px 0 -20px" class="sp-icon attention margin-right float-left rotateX"></i><?php _e("<strong>Saving</strong>", "spamprotection"); ?></h1> 
            <div style="font-size: 18px;"><?php _e("Saving data, please be patient.", "spamprotection"); ?></div> 
        </div> 
    </div> 

</div>
