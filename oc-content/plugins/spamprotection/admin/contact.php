<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
} if (!osc_is_admin_user_logged_in()) {
    die;
}

$sp = new spam_prot;

?>
<div class="settings">

    <ul class="subtabs sp_tabs">
        <li class="subtab-link current" data-tab="sp_contact_mainfeatures"><a><?php _e('Main Settings', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_contact_emailblock"><a><?php _e('E-Mail Block', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_contact_stopwords"><a><?php _e('Stopwords', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_contact_save"><button type="submit" class="btn btn-info"><?php _e('Save', 'spamprotection'); ?></button></li>
    </ul>

    <div id="sp_contact_options" class="sp_contact_options <?php echo (empty($data['sp_contact_activate']) || $data['sp_contact_activate'] == '0' ? 'disabled' : 'enabled'); ?>">

        <div id="sp_contact_mainfeatures" class="subtab-content current">

            <fieldset>
                <legend><?php _e("Contact Settings", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_contact_activate" value="1"<?php if (!empty($data['sp_contact_activate'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Activate the Contact Form Spam Protection', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option activates the Contact Form Spam Protection. Some features are optional and can be de/activated separately', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Check for Links", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <div>
                        <select name="sp_contact_links">
                            <option value="0"<?php if (empty($data['sp_contact_links']) || $data['sp_contact_links'] == '0') { echo ' selected="selected"'; } ?>><?php _e('Deactivated', 'spamprotection'); ?></option>
                            <option value="1"<?php if (!empty($data['sp_contact_links']) && $data['sp_contact_links'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Activated', 'spamprotection'); ?></option>
                        </select><br />
                        <small><?php _e('This Option enables the System to check for links in contacts and if found, marks mail as spam', 'spamprotection'); ?></small>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Honeypot", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_contact_honeypot" value="1"<?php if (!empty($data['sp_contact_honeypot'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Activate the Honeypot form field', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option ads a hidden form field to the contact form. Bots tap into your trap and mails can be moderated.', 'spamprotection'); ?></small>
                    <div id="contact_honeypot" class="hiddeninput<?php if (!empty($data['sp_contact_honeypot'])) { echo ' visible'; } ?>">
                        <div class="halfrow" style="padding-left: 0; padding-top: 0; padding-bottom: 0;">
                            <label for="contact_honeypot_name"><?php _e('Enter the name of the hidden honeypot field', 'spamprotection'); ?> <span id="contact_validname"></span></label><br />
                            <input type="text" class="form-control" name="contact_honeypot_name" value="<?php if (!empty($data['contact_honeypot_name'])) { echo $data['contact_honeypot_name']; } ?>" /><br />
                        </div>
                        <div class="halfrow" style="padding-left: 0; padding-top: 0; padding-bottom: 0;">
                            <label for="contact_honeypot_value"><?php _e('Enter the value of the hidden honeypot field', 'spamprotection'); ?></label><br />
                            <input type="text" class="form-control" name="contact_honeypot_value" value="<?php if (!empty($data['contact_honeypot_value'])) { echo $data['contact_honeypot_value']; } ?>" /><br />
                        </div>
                        <div style="clear: both;"></div>
                        <small><?php _e('Good names would be "address, time, date or something else, dont name it honeypot ;)', 'spamprotection'); ?></small>

                    </div>
                </div>
            </fieldset>

        </div>

        <div id="sp_contact_emailblock" class="subtab-content">

            <fieldset>
                <legend><?php _e("Blocked Mails", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_contact_blocked" value="1"<?php if (!empty($data['sp_contact_blocked'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Block banned E-Mail addresses', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This option enables the System to block sending contact mails from banned E-Mail addresses', 'spamprotection'); ?></small>
                    <br /><br />
                    <div id="contact_blocked" class="hiddeninput<?php if (!empty($data['sp_contact_blocked'])) { echo ' visible'; } ?>">
                        <label for="contact_blocked"><?php _e('Enter the blocked E-Mail addresses, separated by ,', 'spamprotection'); ?></label><br />
                        <textarea class="form-control" name="contact_blocked" style="height: 150px;"><?php if (!empty($data['contact_blocked'])) { echo $data['contact_blocked']; } ?></textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Blocked Mail Hoster", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_contact_blocked_tld" value="1"<?php if (!empty($data['sp_contact_blocked_tld'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Block banned E-Mail TLD', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option enables the System to block sending contact mails from banned E-Mail Top-Level-Domains (e.g. mail.ru,gmail.com)', 'spamprotection'); ?></small>
                    <br /><br />
                    <div id="contact_blocked_tld" class="hiddeninput<?php if (!empty($data['sp_contact_blocked_tld'])) { echo ' visible'; } ?>">
                        <label for="contact_blocked_tld"><?php _e('Enter the blocked E-Mail TLD, separated by ,', 'spamprotection'); ?></label><br />
                        <textarea class="form-control" name="contact_blocked_tld" style="height: 150px;"><?php if (!empty($data['contact_blocked_tld'])) { echo $data['contact_blocked_tld']; } ?></textarea>
                    </div>
                </div>
            </fieldset>        
        </div>

        <div id="sp_contact_stopwords" class="subtab-content">

            <fieldset>
                <legend><?php _e("Stop Words", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <p>
                        <?php _e('Here you can define the search mechanism, how stopwords are checked. You can search for substrings or particular words', 'spamprotection'); ?>
                    </p>
                    <ol style="list-style: disc;">
                        <li>
                            <?php _e('Search for Substrings', 'spamprotection'); ?><br />
                            <?php _e('<small>This method searches in Contact Form for substrings and if found, marks mail as spam (e.g. <em>`are`</em> will be found in <em>`care`</em>)</small>', 'spamprotection'); ?>
                        </li>
                        <li>
                            <?php _e('Search for Words', 'spamprotection'); ?><br />
                            <?php _e('<small>This method searches in Contact Form for particular words and if found, marks mail as spam (e.g. <em>`are`</em> won\'t be found in <em>`care`</em>).</small>', 'spamprotection'); ?>
                        </li>
                    </ol>
                    <select name="sp_contact_blockedtype">
                        <option value="substr"<?php if (empty($data['sp_contact_blockedtype']) || !empty($data['sp_contact_blockedtype']) && $data['sp_contact_blockedtype'] == 'substr') { echo ' selected="selected"'; } ?>>Substrings</option>
                        <option value="words"<?php if (!empty($data['sp_contact_blockedtype']) && $data['sp_contact_blockedtype'] == 'words') { echo ' selected="selected"'; } ?>>Words</option>
                    </select>
                    <br /><br />
                    <?php _e('<strong>Enter here the words to be blocked in title or contacts (separated by ,)</strong>', 'spamprotection'); ?>
                    <textarea class="form-control" name="sp_contact_stopwords" style="height: 200px;"><?php if (!empty($data['sp_contact_stopwords'])) { echo $data['sp_contact_stopwords']; } ?></textarea>
                </div>
            </fieldset>         
        </div>

        <div id="sp_contact_save" class="subtab-content" style=" width: 250px; margin: 10% auto; text-align: center;">
            <h1 style="display: inline-block;"><i style="margin: 0 20px 0 -20px" class="sp-icon attention margin-right float-left rotateX"></i><?php _e("<strong>Saving</strong>", "spamprotection"); ?></h1> 
            <div style="font-size: 18px;"><?php _e("Saving data, please be patient.", "spamprotection"); ?></div> 
        </div>            

    </div> 

</div>
