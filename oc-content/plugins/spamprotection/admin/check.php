<?php
if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
} if (!osc_is_admin_user_logged_in()) {
    die;
}

$sp = new spam_prot;
$id = Params::getParam('itemid');

$item = $sp->_getRow('t_sp_items', array('key' => 'fk_i_item_id', 'value' => $id), 'pk_i_id', 'DESC');
$item_info = Item::newInstance()->findByPrimaryKey($id);

if (isset($item) && !empty($item)) {
    $item_spam = $sp->_getResult('t_item_description', array('key' => 'fk_i_item_id', 'value' => $item['fk_i_item_id']));
    $user = $sp->_getRow('t_user', array('key' => 'pk_i_id', 'value' => $item['fk_i_user_id']));
    $user_spams = $sp->_countRows('t_sp_items', array('key' => 's_user_mail', 'value' => $item['s_user_mail']));
}
if ($user_spams > 0) {
    $item_spams = $sp->_getResult('t_sp_items', array('key' => 's_user_mail', 'value' => $item['s_user_mail']));
}

?>

<div class="compare" id="spamprot">

    <h2><?php if (isset($item['s_reason'])) { echo $item['s_reason']; } ?></h2>

    <div class="infobox halfrow">        

        <table style="width: 100%">
            <?php if (!empty($item['fk_i_user_id'])) { ?>
            <tr>
                <td colspan="2"><strong><?php _e('Registered User', 'spamprotection'); ?></strong></td>
            </tr>
            <?php } else { ?>
             <tr>
                <td colspan="2"><strong><?php _e('User is not registered', 'spamprotection'); ?></strong></td>
            </tr>
            <?php } ?>
            <tr>
                <td class="key"><?php _e('Blocked ads summary', 'spamprotection'); ?></td>
                <td class="value"><button id="info_blocked_ads"><?php echo $user_spams; ?></button></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <?php if (isset($item['fk_i_user_id'])) { ?>
            <tr>
                <td class="key"><?php _e('User ID', 'spamprotection'); ?></td>
                <td class="value"><?php echo '<a target="_blank" href="'.osc_admin_base_url(true).'?page=users&action=edit&id='.$item['fk_i_user_id'].'">'.$item['fk_i_user_id'].'</a>'; ?></td>
            </tr>
            <?php } ?>
            <?php if (isset($user['s_name'])) { ?>
            <tr>
                <td class="key"><?php _e('User Name', 'spamprotection'); ?></td>
                <td class="value"><?php echo '<a target="_blank" href="'.osc_admin_base_url(true).'?page=users&action=edit&id='.$item['fk_i_user_id'].'">'.$user['s_name'].'</a>'; ?></td>
            </tr>
            <?php } ?>
            <?php if (isset($item['s_user_mail'])) { ?>
            <tr>
                <td class="key"><?php _e('User Email', 'spamprotection'); ?></td>
                <td class="value"><?php echo '<a target="_blank" href="'.osc_admin_base_url(true).'?page=items&userId='.$item['fk_i_user_id'].'">'.$item['s_user_mail'].'</a>'; ?></td>
            </tr>
            <?php } ?>
            <?php if (isset($user['dt_reg_date'])) { ?>
            <tr>
                <td class="key"><?php _e('Date registered', 'spamprotection'); ?></td>
                <td class="value"><?php echo $user['dt_reg_date']; ?></td>
            </tr>
            <?php } ?>
            <?php if (isset($user['dt_access_date'])) { ?>
            <tr>
                <td class="key"><?php _e('Last access', 'spamprotection'); ?></td>
                <td class="value"><?php echo $user['dt_access_date']; ?></td>
            </tr>
            <?php } ?>
            <?php if (isset($user['s_access_ip'])) { ?>
            <tr>
                <td class="key"><?php _e('Last IP', 'spamprotection'); ?></td>
                <td class="value"><?php echo $user['s_access_ip']; ?></td>
            </tr>
            <?php } ?>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td class="key"><?php _e('Ad Date', 'spamprotection'); ?></td>
                <td class="value"><?php if (isset($item['dt_date'])) { echo $item['dt_date']; } ?></td>
            </tr>
            <?php if (isset($item_info['s_city']) || isset($item_info['s_country'])) { ?>
            <tr>
                <td class="key"><?php _e('Location', 'spamprotection'); ?></td>
                <td class="value"><?php echo (isset($item_info['s_city']) ? $item_info['s_city'].', ' : '').$item_info['s_country']; ?></td>
            </tr>
            <?php } ?>
        </table>

        <div class="actionbuttons">
            <a class="btn btn-submit" onclick="return confirm('Are you sure you want to activate this item?');" href="<?php echo osc_admin_base_url(true).'?page=items&spam=activate&item='.$id; ?>"><?php _e('Activate Ad', 'spamprotection'); ?></a>
            <a class="btn"onclick="return delete_dialog('<?php echo $id; ?>');" href="<?php list($csrfname, $csrftoken) = osc_csrfguard_generate_token(); echo osc_admin_base_url(true).'?page=items&spam=delete&item='.$id.'&CSRFName='.$csrfname.'&CSRFToken='.$csrftoken; ?>"><?php _e('Delete Ad', 'spamprotection'); ?></a>
            <div style="clear: both; margin: 15px 0;"></div>
            <a class="btn btn-red" onclick="return confirm('Are you sure you want to block this user?');" href="<?php echo osc_admin_base_url(true).'?page=items&spam=block&mail='.(isset($item['s_user_mail']) ? $item['s_user_mail'] : ''); ?>"><?php _e('Block User', 'spamprotection'); ?></a>
            <a class="btn btn-red" onclick="return confirm('Are you sure you want to ban this user completely?');" href="<?php echo osc_admin_base_url(true).'?page=items&addIpBan='.$id; ?>"><?php _e('IP Ban', 'spamprotection'); ?></a>
        </div>
    </div>
    <div class="infobox halfrow">
        <div class="container">

            <div class="viewToggle" title="Toggle view of description between HTML/Code">
                <i class="fa fa-code"></i>
            </div>

            <ul class="langtabs">
                <?php foreach(osc_get_locales() as $k => $v) {
                $current = '';
                if (osc_locale_code() == $v['pk_c_code']) {
                    $current = ' current';
                }
                ?>
                <li class="langtab-link<?php echo $current; ?>" data-tab="tab-<?php echo $v['pk_c_code']; ?>"><a><?php echo $v['s_name']; ?></a></li>
                <?php } ?>
            </ul>

            <?php 
            foreach(osc_get_locales() as $k => $v) {
                $current = '';
                if (osc_locale_code() == $v['pk_c_code']) {
                    $current = ' current';
                }
                if (is_array($item_spam) || is_object($item_spam)) {
                    foreach($item_spam as $ik => $iv) {
                        if ($v['pk_c_code'] == $iv['fk_c_locale_code']) { ?>

                        <div id="tab-<?php echo $v['pk_c_code']; ?>" class="langtab-content<?php echo $current; ?>">
                            <div class="form-group">
                                <h3><?php echo osc_esc_html($iv['s_title']); ?></h3>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php _e('Description', 'spamprotection'); ?></label><br />
                                <textarea id="descriptionCode" class="form-control" readonly="readonly"><?php echo osc_esc_html($iv['s_description']); ?></textarea>
                                <div id="descriptionHTML"><?php echo $iv['s_description']; ?></div>
                            </div>
                        </div>
            <?php
                        }
                    }
                }
            } ?>

        </div>
    </div>
</div>

<?php if ($user_spams > 0) { ?>
<div id="blocked_ads">
    <div id="blocked_ads_inner">
        <div id="blocked_ads_close">x</div>

        <table>
            <tr>
                <td><?php _e('ID', 'spamprotection'); ?></td>
                <td><?php _e('Reason', 'spamprotection'); ?></td>
                <td><?php _e('Date', 'spamprotection'); ?></td>
            </tr>
            <?php 
            foreach($item_spams as $k => $v) { ?>
            <tr>
                <td><?php
                    $vItem = $sp->_getRow('t_item', array('key' => 'pk_i_id', 'value' => $v['fk_i_item_id']));
                    if ($vItem) {
                        echo '<a href="'.osc_admin_base_url(true).'?page=items&action=item_edit&id='.$v['fk_i_item_id'].'">'.$v['fk_i_item_id'].'</a>';
                    } else {
                        echo $v['fk_i_item_id'];
                    }
                ?></td>
                <td><?php echo $v['s_reason']; ?></td>
                <td><?php echo $v['dt_date']; ?></td>
            </tr>
            <?php }
        ?>
        </table>
    </div>
</div>
<script>
$(document).ready(function(){
    $(document).on("click", "#info_blocked_ads", function(event){
        event.preventDefault();
        $("#blocked_ads").fadeToggle("slow");
    });
    $(document).on("click", "#blocked_ads_close", function(event){
        event.preventDefault();
        $("#blocked_ads").fadeToggle("slow");
    });
});
</script>

<?php } ?>