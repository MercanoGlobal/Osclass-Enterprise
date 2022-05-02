<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

$file = Params::getParam('file');
?>
<style>
    .settings-tabs{
        margin: 0;
        width: 200px; 
        padding: 3px;
        height: 100%;
        float: left;
    }
    .settings-tabs ul {
        padding:0px;
    }
    .settings-tabs li{
        display: block;
        height: 39px;
        width: 100%;
        margin-top: 7px;
    }
    .settings-tabs li a{
        padding: 10px 20px 9px;
        display: block;
        background-color: #4f777f;
        color: #fff;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        font-weight: 200;
        font-size: 14px;
    }
    .settings-tabs li.active a{
        background-color: #6baab6;
        color: white;
    }
    .tab-wrapper {
        margin-left: 230px;
        padding-top: 15px;
    }
    body.market #content-head {
        height: 90px;
    }
 
.btn *, .btn *::before, .btn *::after,
select *, select *::before, select *::after {
    box-sizing: content-box;
}
#content-page a.btn {
    height:inherit;
}
.form-label {
    font-size: 16px;
}

label:not(.form-check-label):not(.custom-file-label){
    font-weight: 300;
}
</style>
<div class="header-title-market">
    <h2><?php _e('Here you can manage the Osclass GDPR settings', 'gdpr_osclass'); ?></h2>
</div>
