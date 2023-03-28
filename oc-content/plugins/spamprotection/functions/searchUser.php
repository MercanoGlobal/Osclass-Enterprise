<?php

$del = Params::getParam("deleteUserID");

$age = Params::getParam("sp_user_minAge");
$max = Params::getParam("sp_user_maxAcc");

$zero      = Params::getParam("sp_user_zeroads");
$activated = Params::getParam("sp_user_activated");
$enabled   = Params::getParam("sp_user_enabled");

$checkAdmin  = Params::getParam("sp_user_noAdmin");
$neverLogged = Params::getParam("sp_user_neverlogged");

if (isset($del)) {
    spam_prot::newInstance()->_deleteUnwantedUser($del);    
}

if (!empty($age)) {
    $user = spam_prot::newInstance()->_searchUnwantedUser($age, $max, $activated, $enabled, $zero, $neverLogged);
    if ($user === false) {
        echo 'No users found - Feature under development.';
    }
}

if (isset($user) && !empty($user)) {
    echo '

<table id="tableUnwantedUser" style="width: 100%;">
    <thead>
        <tr>
            <td><input type="checkbox" id="deleteUserAll" /></td>
            <td>'.__("ID", "spamprotection").'</td>
            <td>'.__("Name", "spamprotection").'</td>
            <td>'.__("Email", "spamprotection").'</td>
            <td>'.__("Registered", "spamprotection").'</td>
            <td>'.__("Last Login", "spamprotection").'</td>
            <td>'.__("Listings", "spamprotection").'</td>
            <td>'.__("Comments", "spamprotection").'</td>
        </tr>
    </thead>
    <tbody>
    ';
    foreach($user as $v) {
        $color = '#000';
        $isAdmin = spam_prot::newInstance()->_searchUnwantedAdmin($v['s_email']);
        
        if ($isAdmin) {
            $color = 'red';
        } elseif ($v['dt_access_date'] == "0000-00-00 00:00:00" || $v['dt_reg_date'] == $v['dt_access_date']) {
            $color = '#00bf00';
        }

        if (!$checkAdmin || ($checkAdmin && !$isAdmin)) {
            echo '
            <tr style="color: '.$color.'">
                <td>'.(!$isAdmin ? '<input type="checkbox" name="deleteUserID[]" value="'.$v['pk_i_id'].'" />' : '').'</td>
                <td>'.$v['pk_i_id'].'</td>
                <td>'.$v['s_name'].'</td>
                <td>'.$v['s_email'].'</td>
                <td>'.$v['dt_reg_date'].'</td>
                <td>'.$v['dt_access_date'].'</td>
                <td>'.$v['i_items'].'</td>
                <td>'.$v['i_comments'].'</td>

            </tr>';
        }
    }
    echo '
    </tbody>
</table>
<div style="clear: both;"</div>
<a id="deleteNowUnwantedAccounts" class="btn btn-red" data-link="'.osc_ajax_plugin_url('spamprotection/functions/searchUser.php').'" style="float: right; margin: 20px 0 0 0;">'.__("Delete Now", "spamprotection").'</a>
<div style="clear: both;"</div>
    ';
}
?>