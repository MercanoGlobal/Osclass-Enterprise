<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

class SearchEmail
{
    public function __construct()
    {
        $page   = Params::getParam('page');
        $action = Params::getParam('action');

        osc_add_hook(
            'ajax_admin_search_email',
            array(&$this, 'ajax_search_email')
            );

        osc_add_hook(
            'ajax_admin_lai_delete_alert',
            array(&$this, 'delete_alert')
            );
        // custom header
        if(Params::getParam('page') == 'plugins' &&  Params::getParam('route') == "search-emails" ) {
            osc_add_hook('admin_header',        array(&$this, '_remove_title_header'));
            osc_add_hook('admin_page_header',   array(&$this, 'lai_customPageHeader_search_email'));
        }

        osc_add_filter('admin_title', array(&$this, 'lai_plugin_title'));
    }

    /**
     * meta title
     */
    function lai_plugin_title($title)
    {
        if(Params::getParam('route') == "search-emails") {
            $title = preg_replace('|^(.*)&raquo;|', __('Search email', 'admin_search_email').' &raquo;', $title);
        }
        return $title;
    }

    function _remove_title_header() {
        osc_remove_hook('admin_page_header','customPageHeader');
    }

    /**
     * Custom header for vacancies page - manage listings
     */
    function lai_customPageHeader_search_email() { ?>
        <h1><?php _e('Search email', 'admin_search_email'); ?></h1>
    <?php
    }

    function ajax_search_email()
    {
        $aData = '';

        $email = Params::getParam('s_email');
        // user
        // boton delete user
        $user = User::newInstance()->findByEmail($email);

        $aData .= '<div>';
        if($user!=array()) {
            if(function_exists('osc_csrf_token_url')) {
                $aData    .= '<div><span style=" display: inline-block; top: -10px;position: relative;">'.__('Found a user with this email:','admin_search_email').'</span><div style="display:inline-block;"><a class="btn" target="_black" style="margin-left:5px;" href="' . osc_admin_base_url(true) . '?page=users&action=edit&amp;id=' . $user['pk_i_id'] . '&amp;' . osc_csrf_token_url() . '">' . __('Edit user', 'admin_search_email') . '</a></div></div>';
            } else {
                $aData    .= '<div><span style=" display: inline-block; top: -10px;position: relative;">'.__('Found a user with this email:','admin_search_email').'</span><div style="display:inline-block;"><a class="btn" target="_black" style="margin-left:5px;" href="' . osc_admin_base_url(true) . '?page=users&action=edit&amp;id=' . $user['pk_i_id'] . '">' . __('Edit user', 'admin_search_email') . '</a></div></div>';
            }
        } else {
            $aData    .= '<span>'.__('There is no user with this email.', 'admin_search_email').'</span>';
        }
        $aData .= '</div><div style="clear:both;"></div></br>';
        unset($user);

        // search alerts
        // eliminar todas las alertas de ese email
        $alerts = Alerts::newInstance()->findByEmail($email);
        $aData .= '<div>';
        if($alerts!=array()) {
            $aData    .= '<div><span style=" display: inline-block; top: -10px;position: relative;">'.__('Found alerts with this email:','admin_search_email').'</span><div style="display:inline-block;"><a class="btn" target="_black" style="margin-left:5px;" href="' . osc_admin_base_url(true) .'?page=ajax&action=runhook&hook=lai_delete_alert' . '&s_email='.$email.'"><i class="circle circle-red btn-red"> <b>'.count($alerts).'</b> </i>' . __('Delete alerts', 'admin_search_email') . '</a></div></div>';
        } else {
            $aData .= '<span>'.__('There are no alerts with this email.', 'admin_search_email').'</span>';
        }
        $aData .= '</div><div style="clear:both;"></div></br>';
        unset($alerts);

        // listings no usuario registrado
        $total  = 0;
        $conn   = DBConnectionClass::newInstance();
        $data   = $conn->getOsclassDb();
        $dao    = new DBCommandClass($data);

        $dao->select('count(1) as total');
        $dao->from(DB_TABLE_PREFIX . 't_item');
        $dao->where('s_contact_email', $email);
        $result = $dao->get();

        if($result!==false) {
            $row = $result->row();
            $total = (isset($row['total'])) ? $row['total'] : 0;
        }

        $aData .= '<div>';
        if($total>0) {
            $manage_listings_url = '';
            $aData .= '<div><span style=" display: inline-block; top: -10px;position: relative;">'. sprintf(__('%s listings with email', 'admin_search_email'), '<b>'.$total.'</b>').' '.$email.':</span><div style="display:inline-block;"><a class="btn" target="_black" style="margin-left:5px;" href="'.osc_admin_base_url(true).'?page=items&user='.urlencode($email).'">' . __('View listings', 'admin_search_email') . '</a></div></div>';
        } else {
            $aData .= '<span>'.__('There are no listings with this email.', 'admin_search_email').'</span>';
        }

        $aData .= '</div><div style="clear:both;"></div>';
        unset($aItems);
        echo ($aData);
    }

    function delete_alert()
    {
        $email  = Params::getParam('s_email');
        $alerts = Alerts::newInstance()->findByEmail($email);

        $mAlerts = new Alerts();

        $iDeleted = 0;
        foreach($alerts as $a) {
            $id = $a['pk_i_id'];
            Log::newInstance()->insertLog('user', 'delete_alerts', $id, $id, 'admin', osc_logged_admin_id());
            $iDeleted += $mAlerts->delete(array('pk_i_id' => $id));
        }

        echo $iDeleted .' ' .__("Alerts removed!", 'admin_search_email');
    }
}
?>