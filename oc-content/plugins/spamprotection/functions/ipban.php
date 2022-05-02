<?php

$do     = Params::getParam("do");
$ip     = Params::getParam("ip");
$create = Params::getParam("createFile");

//add or delete ip
if (!empty($do) && !empty($ip)) {
    $ips = spam_prot::newInstance()->_doIpBan($do, $ip);
    if (is_array($ips)) {
        foreach($ips as $k => $v) {
            echo '<tr><td><a class="deleteIpBan" href="'.osc_ajax_plugin_url('spamprotection/functions/ipban.php&do=delete').'" data-ip="'.$k.'"><i class="sp-icon delete xs"></i></a></td><td>'.$k.'</td><td>'.date("d.m.Y H:i:s", $v).'</td></tr>';
        }
    } else {
        echo '<tr><td colspan="3"><h3>Error while saving IP</h3></td></tr>';
    }
}

// open create file flash
elseif (!empty($create)) {
    ob_clean();
    if (is_writable(osc_base_path())) {
        echo '
            <div id="flash">'.spam_prot::newInstance()->_showPopup(
                '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Path writable.</strong>", "spamprotection").'</h1>', 
                '<span><a id="createFileNow" class="btn btn-green" href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_security&sub=ipban&createFileNow=1').'">Create now</a></span>',
                '', false, true, false, 'style="width: 400px;"').'</div>';
    } else {
        $file = file_get_contents(osc_plugin_path().'spamprotection/assets/forbidden.php');
        echo '
            <div id="flash">'.spam_prot::newInstance()->_showPopup(
                '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("<strong>Path NOT writable.</strong>", "spamprotection").'</h1>', 
                '
                <div id="createIpRedirect">
                    <strong>Please create file: '.osc_base_url().'forbidden.php</strong>
                    <fieldset>
                        <legend>'.__("Example content", "spamprotection").'</legend>
                        <pre style="text-align: left;">'.htmlentities($file).'</pre>
                    </fieldset>
                </div>
                ',
                '', false, true, false, 'style="margin-top: 50px; width: 600px;"').'</div>';
    } 
    echo '
    <script>
        $(document).ready(function(){ 
            $("#IpBanFlash").fadeIn();
        });
    </script>
    ';   
}

return true;
?>