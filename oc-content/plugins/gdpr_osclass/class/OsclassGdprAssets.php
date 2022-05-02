<?php

class OsclassGdprAssets {

    function __construct(){
        osc_add_hook('init_admin', function(){

            // common styles
            if(strpos(Params::getParam('file'),'gdpr_osclass/admin/')!==false) { 

                osc_register_script('jquery', osc_plugin_url('gdpr_osclass/admin/js/plugins/jquery/jquery.min.js') . 'jquery.min.js');
                osc_remove_script('fancybox');

                // <!-- Bootstrap 4 -->
                osc_register_script('bootstrap.bundle', osc_plugin_url('gdpr_osclass/admin/js/plugins/bootstrap/js/bootstrap.bundle.min.js') . 'bootstrap.bundle.min.js', array('jquery'));
                osc_enqueue_script('bootstrap.bundle');

                osc_enqueue_style('adminlte.min', osc_plugin_url('gdpr_osclass/admin/css/adminlte.css') . 'adminlte.css');

                osc_enqueue_style('admin-style-css', osc_plugin_url('gdpr_osclass/lib/admin_style.css') . 'admin_style.css');
            } 

        });
    }
}
$_OsclassGdprAssets = new OsclassGdprAssets();