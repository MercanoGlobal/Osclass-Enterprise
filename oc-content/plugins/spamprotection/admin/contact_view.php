<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
} if (!osc_is_admin_user_logged_in()) {
    die;
}

$sp     = new spam_prot;
$params = Params::getParamsAsArray();

$contact = $sp->_getRow('t_sp_contacts', array('key' => 'pk_i_id', 'value' => $params['id']));
$item    = $sp->_getRow('t_desc', array('key' => 'fk_i_item_id', 'value' => $contact['fk_i_item_id']));

if ($contact['fk_i_user_id']) {
    $user = User::newInstance()->findByPrimaryKey($contact['fk_i_user_id']);
    View::newInstance()->_exportVariableToView('users', $user);
    $user_link = '<a href="'.osc_admin_base_url(true).'?page=users&action=edit&id='.$user['pk_i_id'].'" target="_blank">'.$user['s_name'].'</a>';
}
$contacts = $sp->_countRows('t_sp_contacts', array('key' => 's_user_mail', 'value' => isset($user['s_email']) ? $user['s_email'] : ''));
?>

<div id="view_contact">

    <h3><?php echo $contact['s_reason']; ?></h3>

    <div class="contact_wrapper">
        <div class="author_info infobox halfrow">
            <h4><?php _e("About the Author", "spamprotection"); ?></h4>
            <p><?php echo sprintf(__("Name: %s", "spamprotection"), $contact['s_user']); ?></p>
            <p><?php echo sprintf(__("Existing user account: %s", "spamprotection"), (isset($user_link) ? $user_link : 'No')); ?></p>
            <p><?php echo sprintf(__("Used E-Mail Address: %s", "spamprotection"), $contact['s_user_mail']); ?></p>
            <p><?php echo sprintf(__("Used Phone Number: %s", "spamprotection"), $contact['s_user_phone']); ?></p>
            <p><?php echo sprintf(__("Active spam contacts by user: %d", "spamprotection"), $contacts); ?></p>
        </div>
        <div class="contact_info infobox halfrow">
            <h4><?php _e("About the Mail", "spamprotection"); ?></h4>
            <p class="contact_title"><?php echo sprintf(__("Item: %s", "spamprotection"), '<a target="_blank" href="'.osc_admin_base_url(true).'?page=items&action=item_edit&id='.$contact['fk_i_item_id'].'">'.$item['s_title'].'</a>'); ?></p>
            <p class="contact_title"><?php echo sprintf(__("Date: %s", "spamprotection"), $contact['dt_date']); ?></p>
            <p class="contact_body"><?php echo sprintf(__("Message: %s", "spamprotection"), $contact['s_user_message']); ?></p>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="contact_actions halfrow">
        <a class="btn btn-submit" onclick="return confirm('<?php _e("Are you sure you want to forward this mail to the user?", "spamprotection"); ?>');" href="<?php echo osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php?action=forward&id='.$params['id']); ?>"><?php _e('Forward mail', 'spamprotection'); ?></a>
        <a class="btn" onclick="return confirm('<?php _e("Are you sure you want to delete this mail?", "spamprotection"); ?>');" href="<?php echo osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php?action=delete&id='.$params['id']); ?>"><?php _e('Delete mail', 'spamprotection'); ?></a>
        <a class="btn btn-red" onclick="return confirm('<?php _e("Are you sure you want to delete this mail and block this user for contact mails?", "spamprotection"); ?>');" href="<?php echo osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php?action=block&id='.$params['id']); ?>"><?php _e('Delete mail and block user', 'spamprotection'); ?></a>
    </div>

</div>