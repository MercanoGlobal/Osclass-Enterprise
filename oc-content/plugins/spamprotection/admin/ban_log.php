<?php
    if (!defined('OC_ADMIN')) {
        exit('Direct access is not allowed.');
    } if (!osc_is_admin_user_logged_in()) {
        die;
    }

    $sp = new spam_prot;
    $params = Params::getParamsAsArray();

    if (isset($params['action']) && isset($params['id'])) {
        $sp->_handleBanLog($params['action'], $params['id']);
    }

    $bans = $sp->_getResult('t_sp_ban_log');
?>
<div class="spamprot_bans table-contains-actions">
    <table class="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="col-name"><?php _e("User", "spamprotection"); ?></th>
                <th class="col-name"><?php _e("Reason", "spamprotection"); ?></th>
                <th class="col-ip"><?php _e("IP", "spamprotection"); ?></th>
                <th class="col-email "><?php _e("Email", "spamprotection"); ?></th>
                <th class="col-date "><?php _e("Date", "spamprotection"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if (is_array($bans)) {
                    foreach ($bans as $k => $v) { ?>
                    <tr>
                        <td class="col-name">
                            <?php
                                if (is_numeric($v['i_user_id'])) {
                                    $user = User::newInstance()->findByPrimaryKey($v['i_user_id']);
                                    echo (isset($user['s_name']) ? '<a href="'.osc_admin_base_url(true).'?page=users&action=edit&id='.$v['i_user_id'].'">'.$user['s_name'].'</a>' : '');
                                } else {
                                    echo '<?php _e("No user found", "spamprotection"); ?>';
                                }
                            ?>
                        </td>
                        <td class="col-name">
                            <?php echo $v['s_reason']; ?>

                            <div class="actions">
                                <ul>
                                    <li>
                                        <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/ban_log.php?action=delete&id='.$v['pk_i_id']); ?>"><?php _e("Delete entry", "spamprotection"); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo osc_admin_render_plugin_url('spamprotection/admin/ban_log.php?action=activate&id='.$v['pk_i_id']); ?>"><?php _e("Activate user", "spamprotection"); ?></a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td class="col-ip">
                            <?php echo $v['s_user_ip']; ?>
                        </td>
                        <td class="col-email">
                            <?php echo $v['s_user_email']; ?>
                        </td>
                        <td class="col-date">
                            <?php echo $v['dt_date_banned']; ?>
                        </td>
                    </tr>
                    <?php 
                    }
                }
            ?>
        </tbody>
    </table>
</div>