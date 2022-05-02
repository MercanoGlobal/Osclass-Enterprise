<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
} if (!osc_is_admin_user_logged_in()) {
    die;
}

$sp     = new spam_prot;
$params = Params::getParamsAsArray();

if (isset($params['action']) && isset($params['id']) && is_numeric($params['id'])) {
    $sp->_spamActionContacts($params['action'], $params['id']);
}
?>
<div class="compare table-contains-actions" id="spamprot">
    <table class="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="col-status-border"></th>
                <th class="col-status"><?php _e('Status', 'spamprotection'); ?></th>
                <th class="col-author"><?php _e('Author', 'spamprotection'); ?></th>
                <th class="col-contact"><?php _e('Reason', 'spamprotection'); ?></th>
                <th class="col-date sorting_desc"><?php _e('Date', 'spamprotection'); ?></th>
            </tr>
        </thead>
        <tbody>

            <?php
                $contacts = $sp->_getResult('t_sp_contacts');

                if (is_array($contacts) || is_object($contacts)) {
                foreach($contacts as $key => $value) {
                    $user = $sp->_checkContactUser($value['s_user']);
                    if ($user) {

                    }
                    echo '
                    <tr class="status-blocked">
                        <td class="col-status-border"></td>
                        <td class="col-status">'.__('blocked', 'spamprotection').'</td>
                        <td class="col-author">
                            <a href="'.osc_admin_base_url(true).'?page=users&action=edit&id='. @$user['pk_i_id'].'">'. @$user['s_name'].'</a>
                            <div class="actions">
                                <ul>
                                    <li><a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_view.php?id='.$value['pk_i_id']).'">'.__('View', 'spamprotection').'</a></li>
                                    <li><a onclick="javascript:return confirm("'.__("Are you sure you want to forward this mail to the user?", "spamprotection").'");" href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php?action=forward&id='.$value['pk_i_id']).'">'.__('Forward', 'spamprotection').'</a></li>
                                    <li><a onclick="javascript:return confirm("'.__("Are you sure you want to delete this mail?", "spamprotection").'");" href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php?action=delete&id='.$value['pk_i_id']).'">'.__('Delete', 'spamprotection').'</a></li>
                                    <li><a onclick="javascript:return confirm("'.__("Are you sure you want to delete this mail and block this user for contact mails?", "spamprotection").'");" href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php?action=block&id='.$value['pk_i_id']).'">'.__('Block', 'spamprotection').'</a></li>
                                </ul>
                            </div>
                        </td>
                        <td class="col-contact">'.$value['s_reason'].'</td>
                        <td class="col-date">'.$value['dt_date'].'</td>
                    </tr>
                    ';
                }
                }
            ?>
        </tbody>
    </table>

</div>
