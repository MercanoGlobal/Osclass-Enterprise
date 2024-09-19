<?php   
/*
Plugin Name: Seller Verification
Plugin URI: https://github.com/MercanoGlobal/Osclass-Enterprise
Description: This plugin extends the User Profiles with a seller verification attribute that can only be added by admins
Version: 1.2.0
Author: AZR & Osclass Enterprise
Author URI: https://www.facebook.com/achmadzackyr
Short Name: sellerver
*/

require('SellerVerification.php');

// will  import table from struct.sql into database on  plugin install 
function sellerver_call_after_install() {
    SellerVerification::newInstance()->import('seller_verification/struct.sql');
}

// Self-explanatory
function sellerver_call_after_uninstall() {
    SellerVerification::newInstance()->uninstall();
}

// check if the seller verification has been set to that id or not
// if has been set, so will edit it else will make one
function sellerver_check_user($userID) {
    // get the details from database
    $detail = SellerVerification::newInstance()->getSellerVerificationAttr($userID);
    if(@$detail['b_seller_verification'] == '') {
        sellerver_form_post($userID);
    } else {
        sellerver_user_edit_post($userID);
    }
}

function sellerver_form_post($userID) {
    if(is_numeric($userID)) {
        $value = Params::getParam('plugin_seller_verification');
        $value_description = Params::getParam('plugin_seller_description');
        SellerVerification::newInstance()->insertValue($value, $value_description, $userID);
    } else { return false; }
}

function sellerver_pre_user_post() {
    Session::newInstance()->_setForm('pplugin_seller_verification', Params::getParam('plugin_seller_verification') );
    Session::newInstance()->_setForm('pplugin_seller_description', Params::getParam('plugin_seller_description') );
    // keep values on session
    Session::newInstance()->_keepForm('pplugin_seller_verification');
    Session::newInstance()->_keepForm('pplugin_seller_description');
}

// Self-explanatory
function sellerver_user_edit($userID = null) {
    if(is_numeric($userID)){
        // get the details from database
        $detail = SellerVerification::newInstance()->getSellerVerificationAttr($userID);
        require_once 'edit.php';
    } else {
        return false;
    }
}

// Self-explanatory
function sellerver_user_edit_post($userID) {
    if(is_numeric($userID)) {
        $value = Params::getParam('plugin_seller_verification');
        $value_description = Params::getParam('plugin_seller_description');
        SellerVerification::newInstance()->updateAttr($value,$value_description,$userID);
    } else { return false; }
}

// will delete from our table the data with pk_i_id =$userId
function sellerver_delete_user($userId) {
    if( is_numeric($userId)) {
        SellerVerification::newInstance()->deleteItem($userId);
    } 
}

// will  import detail.php into item  page
function sellerver_user_detail($userID) {
    // get the details from database where userID
    $detail = SellerVerification::newInstance()->getSellerVerificationAttr($userID);

    require 'detail.php';
}

 // this function will get parametes from edit.php defined fields hold into array and
 // send parametres to SellerVerification class for inserting into database

function _getSellerVerificationParameters() {
    // this array will hold the parameters must fit ->$seller_verification
    $array = array(
        'seller_verification' => $seller_verification,
        'seller_description'  => $seller_description
    );
    return $array;
}

function sellerver_admin_menu() {
    echo '<h3><a href="#">' . __('Seller Verification', 'seller_verification') . '</a></h3>
    <ul> 
        <li><a href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/help.php') . '">&raquo; ' . __('Help', 'seller_verification') . '</a></li>
    </ul>';
}

function sellerver_help() {
    osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/help.php');
}

// end admin

// this is needed in order to be able to activate the plugin
osc_register_plugin(osc_plugin_path(__FILE__), 'sellerver_call_after_install');
// this is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)

// this is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'sellerver_call_after_uninstall') ;
/* require('functions.php'); */

// when publishing an item we show an extra form with more attributes
osc_add_hook('seller_verification', 'sellerver_user_edit');

// to add that new information to our custom table
osc_add_hook('user_edit_completed', 'sellerver_check_user');

osc_add_hook('pre_user_post','sellerver_pre_user_post');
//sellerver_user_edit_post');

// show an item special attributes
osc_add_hook('seller_verification_show', 'sellerver_user_detail');

// delete item
osc_add_hook('delete_user', 'sellerver_delete_user');

// Add the Help URL to the menu
osc_add_hook('admin_menu', 'sellerver_admin_menu');
