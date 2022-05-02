<?php
/*
Plugin Name: Admin search email
Plugin URI: http://www.osclass.org/
Description: Helps to find out where an email is used
Version: 1.0.0
Author: Osclass
Author URI: http://www.osclass.org/
Short Name: admin_search_email
*/

require_once('class/SearchEmail.php');

$search_email = new SearchEmail();

osc_add_route('search-emails', 'search-emails?', 'search-emails', osc_plugin_folder(__FILE__) . 'view_search_email.php');

// add link for search by user email
function ase_add_menu_toolbar() {
    $title = '<i class="circle circle-green btn-grren"> <b>?</b> </i>'.__('Search Email', 'admin_search_email');
    AdminToolbar::newInstance()->add_menu(
        array('id'    => 'search_email',
              'title' => $title,
              'href'  => osc_route_admin_url('search-emails'),
              'meta'  => array('class' => 'action-btn action-btn-black')
        )
    );
}
osc_add_hook('add_admin_toolbar_menus', 'ase_add_menu_toolbar');
