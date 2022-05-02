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
        <li class="subtab-link current" data-tab="sp_comm_mainfeatures"><a><?php _e('Main Settings', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_comm_emailblock"><a><?php _e('E-Mail Block', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_comm_stopwords"><a><?php _e('Stopwords', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_comm_cleaner"><a><?php _e('Cleaner', 'spamprotection'); ?></a></li>
        <li class="subtab-link" data-tab="sp_comm_save"><button type="submit" class="btn btn-info"><?php _e('Save', 'spamprotection'); ?></button></li>
    </ul>

    <div id="sp_comment_options" class="sp_comment_options <?php echo (empty($data['sp_comment_activate']) || $data['sp_comment_activate'] == '0' ? 'disabled' : 'enabled'); ?>">

        <div id="sp_comm_mainfeatures" class="subtab-content current">

            <fieldset>
                <legend><?php _e("Comment Settings", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_comment_activate" value="1"<?php if (!empty($data['sp_comment_activate'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Activate the Comment Spam Protection', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option activates the Comment Spam Protection. Some features are optional and can be de/activated separately', 'spamprotection'); ?></small>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Check for Links", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <div>
                        <select name="sp_comment_links">
                            <option value="0"<?php if (empty($data['sp_comment_links']) || $data['sp_comment_links'] == '0') { echo ' selected="selected"'; } ?>><?php _e('Deactivated', 'spamprotection'); ?></option>
                            <option value="1"<?php if (!empty($data['sp_comment_links']) && $data['sp_comment_links'] == '1') { echo ' selected="selected"'; } ?>><?php _e('Only title', 'spamprotection'); ?></option>
                            <option value="2"<?php if (!empty($data['sp_comment_links']) && $data['sp_comment_links'] == '2') { echo ' selected="selected"'; } ?>><?php _e('Title and description', 'spamprotection'); ?></option>
                        </select><br />
                        <small><?php _e('This Option enables the System to check for links in comments and if found, disable it', 'spamprotection'); ?></small>
                    </div>
                </div>
            </fieldset>

        </div>

        <div id="sp_comm_emailblock" class="subtab-content">

            <fieldset>
                <legend><?php _e("Blocked Mails", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_comment_blocked" value="1"<?php if (!empty($data['sp_comment_blocked'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Block banned E-Mail addresses', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This option enables the System to block comments from banned Email addresses', 'spamprotection'); ?></small>
                    <br /><br />
                    <div id="comment_blocked" class="hiddeninput<?php if (!empty($data['sp_comment_blocked'])) { echo ' visible'; } ?>">
                        <label for="comment_blocked"><?php _e('Enter the blocked E-Mail addresses, separated by ,', 'spamprotection'); ?></label><br />
                        <textarea class="form-control" name="comment_blocked" style="height: 150px;"><?php if (!empty($data['comment_blocked'])) { echo $data['comment_blocked']; } ?></textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php _e("Blocked Mail Hoster", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <label>
                        <input type="checkbox" name="sp_comment_blocked_tld" value="1"<?php if (!empty($data['sp_comment_blocked_tld'])) { echo ' checked="checked"'; } ?> />
                        <?php _e('Block banned E-Mail TLD', 'spamprotection'); ?>
                    </label><br />
                    <small><?php _e('This Option enables the System to block comments from banned E-Mail Top-Level-Domains (e.g. mail.ru,gmail.com)', 'spamprotection'); ?></small>
                    <br /><br />
                    <div id="comment_blocked_tld" class="hiddeninput<?php if (!empty($data['sp_comment_blocked_tld'])) { echo ' visible'; } ?>">
                        <label for="comment_blocked_tld"><?php _e('Enter the blocked E-Mail TLD, separated by ,', 'spamprotection'); ?></label><br />
                        <textarea class="form-control" name="comment_blocked_tld" style="height: 150px;"><?php if (!empty($data['comment_blocked_tld'])) { echo $data['comment_blocked_tld']; } ?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>

        <div id="sp_comm_stopwords" class="subtab-content">

            <fieldset>
                <legend><?php _e("Stop Words", "spamprotection"); ?></legend>
                <div class="row form-group">
                    <p>
                        <?php _e('Here you can define the search mechanism, how stopwords are checked. You can search for substrings or particular words', 'spamprotection'); ?>
                    </p>
                    <ol style="list-style: disc;">
                        <li>
                            <?php _e('Search for Substrings', 'spamprotection'); ?><br />
                            <?php _e('<small>This method searches in Title/Comment for substrings and if found, disable comments (e.g. <em>`are`</em> will be found in <em>`care`</em>)</small>', 'spamprotection'); ?>
                        </li>
                        <li>
                            <?php _e('Search for Words', 'spamprotection'); ?><br />
                            <?php _e('<small>This method searches in Title/Comment for particular words and if found, disable comments (e.g. <em>`are`</em> won\'t be found in <em>`care`</em>).</small>', 'spamprotection'); ?>
                        </li>
                    </ol>
                    <select name="sp_comment_blockedtype">
                        <option value="substr"<?php if (empty($data['sp_comment_blockedtype']) || !empty($data['sp_comment_blockedtype']) && $data['sp_comment_blockedtype'] == 'substr') { echo ' selected="selected"'; } ?>>Substrings</option>
                        <option value="words"<?php if (!empty($data['sp_comment_blockedtype']) && $data['sp_comment_blockedtype'] == 'words') { echo ' selected="selected"'; } ?>>Words</option>
                    </select>
                    <br /><br />
                    <?php _e('<strong>Enter here the words to be blocked in title or comments (separated by ,)</strong>', 'spamprotection'); ?>
                    <textarea class="form-control" name="sp_comment_stopwords" style="height: 200px;"><?php if (!empty($data['sp_comment_stopwords'])) { echo $data['sp_comment_stopwords']; } ?></textarea>
                </div>
            </fieldset>         
        </div>

        <div id="sp_comm_cleaner" class="subtab-content">            
            <fieldset>
                <legend><?php _e("Delete unwanted comments", "spamprotection"); ?></legend>                
                <div class="row form-group">                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <label>
                            <input type="checkbox" name="sp_commdel_unactivated" value="1"<?php if (!empty($data['sp_commdel_unactivated'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Delete inactive comments', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('Here you can define if inactive comments should be deleted automatically after x days.', 'spamprotection'); ?></small>
                    </div>
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('after', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_commdel_unactivated_after" style="width: 50px;" value="<?php if (!empty($data['sp_commdel_unactivated_after'])) { echo $data['sp_commdel_unactivated_after']; } ?>" /> <span>Days</span>
                            </label>
                        </div>
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('Max.', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_commdel_unactivated_limit" style="width: 50px;" value="<?php if (!empty($data['sp_commdel_unactivated_limit'])) { echo $data['sp_commdel_unactivated_limit']; } ?>" /> <span>at once</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="row form-group">                
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <label>
                            <input type="checkbox" name="sp_commdel_spam" value="1"<?php if (!empty($data['sp_commdel_spam'])) { echo ' checked="checked"'; } ?> />
                            <?php _e('Delete comments marked as spam', 'spamprotection'); ?>
                        </label><br />
                        <small><?php _e('Here you can define if as spam marked comments should be deleted automatically after x days.', 'spamprotection'); ?></small>
                    </div>
                    <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('after', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_commdel_spam_after" style="width: 50px;" value="<?php if (!empty($data['sp_commdel_spam_after'])) { echo $data['sp_commdel_spam_after']; } ?>" /> <span>Days</span>
                            </label>
                        </div>
                        <div style="float: left; width: calc(50% - 20px); padding: 10px;">
                            <label style="line-height: 28px;">
                                <?php _e('Max.', 'spamprotection'); ?>
                                <input type="text" class="form-control" name="sp_commdel_spam_limit" style="width: 50px;" value="<?php if (!empty($data['sp_commdel_spam_limit'])) { echo $data['sp_commdel_spam_limit']; } ?>" /> <span>at once</span>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>        
        </div>

        <div id="sp_comm_save" class="subtab-content" style=" width: 250px; margin: 10% auto; text-align: center;">
            <h1 style="display: inline-block;"><i style="margin: 0 20px 0 -20px" class="sp-icon attention margin-right float-left rotateX"></i><?php _e("<strong>Saving</strong>", "spamprotection"); ?></h1>
            <div style="font-size: 18px;"><?php _e("Saving data, please be patient.", "spamprotection"); ?></div>
        </div>            

    </div> 

</div>