<?php

$u   = Params::getParam("user");
$set = Params::getParam("set");

if (!empty($u) && !empty($set)) {
    spam_prot::newInstance()->_userManageAjax($set, $u);
}

$search = Params::getParam("searchNewTrusted");
$user = spam_prot::newInstance()->_searchBadOrTrustedUser($search);

if (isset($user) && !empty($user)) {
    foreach($user as $v) {
        $ipb = spam_prot::newInstance()->_BadOrTrustedUsers($v['pk_i_id']);
        echo '
        <tr>
            <td>'.(isset($ipb['i_reputation']) && $ipb['i_reputation'] == '1' ? '<i class="sp-icon thumbsdown xs"></i>' : (isset($ipb['i_reputation']) && $ipb['i_reputation'] == '2' ? '<i class="sp-icon thumbsup xs"></i>' : '')).'</td>
            <td>'.$v['s_name'].'</td>
            <td>'.$v['s_email'].'</td>
            <td>
                '.(isset($ipb['i_reputation']) && $ipb['i_reputation'] == '1' ? '
                    <a class="action_bot" href="'.osc_ajax_plugin_url('spamprotection/functions/search.php&user='.$v['pk_i_id'].'&set=2').'"><i class="sp-icon thumbsup xs"></i></a>
                    <a class="action_bot" href="'.osc_ajax_plugin_url('spamprotection/functions/search.php&user='.$v['pk_i_id'].'&set=remove').'"><i class="sp-icon delete xs"></i></a>
                ' : (isset($ipb['i_reputation']) && $ipb['i_reputation'] == '2' ? '
                    <a class="action_bot" href="'.osc_ajax_plugin_url('spamprotection/functions/search.php&user='.$v['pk_i_id'].'&set=1').'"><i class="sp-icon thumbsdown xs"></i></a>
                    <a class="action_bot" href="'.osc_ajax_plugin_url('spamprotection/functions/search.php&user='.$v['pk_i_id'].'&set=remove').'"><i class="sp-icon delete xs"></i></a>
                ' : '
                    <a class="action_bot" href="'.osc_ajax_plugin_url('spamprotection/functions/search.php&user='.$v['pk_i_id'].'&set=2').'"><i class="sp-icon thumbsup xs"></i></a>
                    <a class="action_bot" href="'.osc_ajax_plugin_url('spamprotection/functions/search.php&user='.$v['pk_i_id'].'&set=1').'"><i class="sp-icon thumbsdown xs"></i></a>
                ')).'
            </td>
        </tr>
        ';
    }
}
?>