<?php
$sp = new spam_prot; 

$limit  = Params::getParam("logLimit");
$page   = Params::getParam("logPage");
$date   = Params::getParam("logDate");
$search = Params::getParam("logSearch");

if (!isset($limit) || !is_numeric($limit)) {
    $limit = $sp->_get('sp_globallog_limit');
    if (!isset($limit) || !is_numeric($limit)) {
        $limit = '25';
    }
} if (!isset($page) || $page <= 0) {
    $page = '1';
}

$log   = $sp->_readGlobalLog($limit, $page, $date, $search, false);
$count = $sp->_countGlobalLog();
$pages = ceil($count/$limit);
?>
<div id="logDetails" class="logHeader">

    <div id="logPagination" class="form-group" style="float: left;">
        <?php if (isset($page) && is_numeric($page) && $page > 1) { ?>
        <a class="logPrev btn logPagination" data-page="<?php echo $page-1; ?>" style="padding: 5px;width: 15px;height: 16px;text-align: center;"><</a>
        <?php } else { ?>
        <a class="logPrev btn disabled"><</a>
        <?php } if ($count > $limit*$page) { ?>
        <a class="logNext btn logPagination" data-page="<?php echo $page+1; ?>" style="padding: 5px;width: 15px;height: 16px;text-align: center;">></a>
        <?php } else { ?>
        <a class="logNext btn disabled">></a>
        <?php } ?>        
    </div>

    <div id="logPages" class="form-group" style="float: left;">
        <input type="text" readonly="readonly" style="width: 60px;margin-right: 5px;text-align: center;font-size: 15px;font-weight: bold;color: #bbb;" value="<?php echo $page.'/'.$pages; ?>" />
    </div>

    <div id="logLimitation" class="form-group" style="float: left;">
        <select name="logLimit">
            <option value="25"<?php echo ($limit == '25' ? ' selected="selected"' : ''); ?>><?php _e("Show 25 log entries", "spamprotection"); ?></option>
            <option value="50"<?php echo ($limit == '50' ? ' selected="selected"' : ''); ?>><?php _e("Show 50 log entries", "spamprotection"); ?></option>
            <option value="100"<?php echo ($limit == '100' ? ' selected="selected"' : ''); ?>><?php _e("Show 100 log entries", "spamprotection"); ?></option>
        </select>
    </div>
    <div class="form-group" style="float: right;">

        <input type="text" name="logDate" style="width: 150px;" placeholder="<?php _e("Select Date", "spamprotection"); ?>" />

        <input type="text" name="logSearch" style="width: 150px;" value="<?php echo $search; ?>" placeholder="<?php _e("Search in logs", "spamprotection"); ?>" />

        <a class="logSearch btn btn-blue" style="float: right; margin-left: 3px; padding: 5px; position: relative; width: 30px; height: 16px;">
            <i class="sp-icon search" style="position: absolute;top: -3px;left: 5px;transform: scale(0.7);"></i>
        </a>

        <script>
            $("input[name=logDate]").datepicker({
                dateFormat: "yy-mm-dd"
            });
        </script>

        <input type="hidden" name="logLink" value="<?php echo osc_ajax_plugin_url('spamprotection/functions/log.php'); ?>" />
        <input type="hidden" name="logPage" value="<?php echo (isset($page) ? $page : 0); ?>" />
    </div>
    <div style="clear: both;"></div>
</div>
<div id="logTable">
    <table>
        <thead>
            <tr>
                <td style="width: 180px; text-align: left;"><?php _e("Date", "spamprotection"); ?></td>
                <td><?php _e("Action", "spamprotection"); ?></td>
                <td style="width: 130px; text-align: right;"><?php _e("Info", "spamprotection"); ?></td>
                <td style="width: 130px; text-align: right;"><?php _e("Done by", "spamprotection"); ?></td>
            </tr>
        </thead>
        <tbody>
        <?php
            if (isset($log) && is_array($log)) {
                foreach($log as $data) {
                    echo '
            <tr>
                <td style="width: 180px; text-align: left;">'.osc_format_date($data['dt_date'], osc_date_format().' - '.osc_time_format()).'</td>
                <td>'.$data['s_reason'].'</td>
                <td style="width: 130px; text-align: right;">'.$data['s_account'].'</td>
                <td style="width: 130px; text-align: right;">'.$data['s_done'].'</td>
            </tr>';    
                }
            }
        ?>
        </tbody>
    </table>
</div>