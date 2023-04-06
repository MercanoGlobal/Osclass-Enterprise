<?php
class spam_prot extends DAO {

    private static $instance;

    public static function newInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    function __construct() {
        $this->_table_user         = '`'.DB_TABLE_PREFIX.'t_user`';
        $this->_table_admin        = '`'.DB_TABLE_PREFIX.'t_admin`';
        $this->_table_item         = '`'.DB_TABLE_PREFIX.'t_item`';
        $this->_table_comment      = '`'.DB_TABLE_PREFIX.'t_item_comment`';
        $this->_table_desc         = '`'.DB_TABLE_PREFIX.'t_item_description`';
        $this->_table_bans         = '`'.DB_TABLE_PREFIX.'t_ban_rule`';
        $this->_table_pref         = '`'.DB_TABLE_PREFIX.'t_preference`';
        $this->_table_sp_ban_log   = '`'.DB_TABLE_PREFIX.'t_spam_protection_ban_log`';
        $this->_table_sp_items     = '`'.DB_TABLE_PREFIX.'t_spam_protection_items`';
        $this->_table_sp_users     = '`'.DB_TABLE_PREFIX.'t_spam_protection_users`';
        $this->_table_sp_comments  = '`'.DB_TABLE_PREFIX.'t_spam_protection_comments`';
        $this->_table_sp_contacts  = '`'.DB_TABLE_PREFIX.'t_spam_protection_contacts`';
        $this->_table_sp_logins    = '`'.DB_TABLE_PREFIX.'t_spam_protection_logins`';        
        $this->_table_sp_globallog = '`'.DB_TABLE_PREFIX.'t_spam_protection_global_log`';

        $this->configFiles         = array(
                                        'active'       => $this->_get("sp_files_activate"),
                                        'interval'     => $this->_get("sp_files_interval"),
                                        'scanDir'      => $this->_get("sp_files_directory"),
                                        'excludeDir'   => unserialize($this->_get("sp_files_exclude")),
                                        'excludeFile'  => explode(",", $this->_get("sp_files_extensions")),
                                        'alertAddress' => $this->_get("sp_files_alerts")
                                    );

        parent::__construct();
    }

    function _correctDatabase() {
        $this->dao->select('*');
        $this->dao->from($this->_table_user);
        $result = $this->dao->get();

        if ($result && $result->numRows() > 0) {
            $data = $result->result();
            foreach ($data as $user) {
                if (isset($user['i_reputation'])) {
                    $this->dao->insert($this->_table_sp_users, array('pk_i_id' => $user['pk_i_id'], 'i_reputation' => $user['i_reputation'], 's_reputation' => $user['s_reputation']));
                }   
            }
        }
        $result = $this->dao->query(sprintf('SHOW COLUMNS FROM %s LIKE "i_reputation";', $this->_table_user));
        if ($result && $result->numRows() > 0) {
            $this->dao->query(sprintf('ALTER TABLE %s DROP i_reputation;', $this->_table_user));
        }
        $result = $this->dao->query(sprintf('SHOW COLUMNS FROM %s LIKE "s_reputation";', $this->_table_user));
        if ($result && $result->numRows() > 0) {
            $this->dao->query(sprintf('ALTER TABLE %s DROP s_reputation;', $this->_table_user));
        }
    }

    function _install() {

        $file = osc_plugin_resource('spamprotection/assets/create_table.sql');
        $sql = file_get_contents($file);

        if (!$this->dao->importSQL($sql)) {
            throw new Exception( "Error importSQL::spam_prot<br>".$file ) ;
        }        

        $this->_correctDatabase();

        $opts = self::newInstance()->_opt(); $pref = self::newInstance()->_sect();       
        foreach ($opts AS $k => $v) {
            if (!osc_set_preference($k, $v[0], $pref, $v[1])) {
                return false;
            }
        }

        return true;            
    }

    function _uninstall() {
        $pref = $this->_sect();                
        Preference::newInstance()->delete(array("s_section" => $pref));
        $this->dao->query(sprintf('DROP TABLE %s', $this->_table_sp_ban_log));
        $this->dao->query(sprintf('DROP TABLE %s', $this->_table_sp_items));
        $this->dao->query(sprintf('DROP TABLE %s', $this->_table_sp_users));
        $this->dao->query(sprintf('DROP TABLE %s', $this->_table_sp_comments));
        $this->dao->query(sprintf('DROP TABLE %s', $this->_table_sp_contacts));
        $this->dao->query(sprintf('DROP TABLE %s', $this->_table_sp_logins));
    }

    function _sect() {
        return 'plugin_spamprotection';
    }

    function _opt($key = false) {                
        $opts = array(
            'sp_first_install'              => array('1', 'BOOLEAN'),
            'sp_activate'                   => array('1', 'BOOLEAN'),
            'sp_comment_activate'           => array('1', 'BOOLEAN'),
            'sp_contact_activate'           => array('1', 'BOOLEAN'),
            'sp_security_activate'          => array('1', 'BOOLEAN'),
            'sp_admin_activate'             => array('1', 'BOOLEAN'),
            'sp_ipban_activate'             => array('0', 'BOOLEAN'),
            'sp_activate_inform'            => array('0', 'BOOLEAN'),
            'sp_block_messages'             => array('1', 'BOOLEAN'),
            'sp_duplicates'                 => array('1', 'BOOLEAN'),
            'sp_duplicates_as'              => array('1', 'BOOLEAN'),
            'sp_duplicate_type'             => array('0', 'BOOLEAN'),
            'sp_duplicate_perc'             => array('85', 'BOOLEAN'),
            'sp_duplicates_time'            => array('30', 'STRING'),
            'sp_honeypot'                   => array('0', 'BOOLEAN'),
            'sp_contact_honeypot'           => array('0', 'BOOLEAN'),
            'honeypot_name'                 => array('sp_price_range', 'STRING'),
            'contact_honeypot_name'         => array('yourDate', 'STRING'),
            'contact_honeypot_value'        => array('asap', 'STRING'),
            'sp_blocked'                    => array('0', 'BOOLEAN'),
            'sp_blocked_tld'                => array('0', 'BOOLEAN'),
            'sp_blockedtype'                => array('strpos', 'STRING'),
            'sp_comment_blocked'            => array('0', 'BOOLEAN'),
            'sp_comment_blocked_tld'        => array('0', 'BOOLEAN'),
            'sp_comment_blockedtype'        => array('strpos', 'STRING'),
            'sp_contact_blocked'            => array('0', 'BOOLEAN'),
            'sp_contact_blocked_tld'        => array('0', 'BOOLEAN'),
            'sp_contact_blockedtype'        => array('strpos', 'STRING'),
            'sp_mxr'                        => array('0', 'BOOLEAN'),
            'blocked'                       => array('', 'STRING'),
            'blocked_tld'                   => array('', 'STRING'),
            'comment_blocked'               => array('', 'STRING'),
            'comment_blocked_tld'           => array('', 'STRING'),
            'contact_blocked'               => array('', 'STRING'),
            'contact_blocked_tld'           => array('', 'STRING'),
            'sp_stopwords'                  => array('', 'STRING'),
            'sp_comment_stopwords'          => array('', 'STRING'),
            'sp_contact_stopwords'          => array('', 'STRING'),
            'sp_comment_links'              => array('', 'STRING'),
            'sp_contact_links'              => array('', 'STRING'),
            'sp_security_login_count'       => array('3', 'STRING'),
            'sp_admin_login_count'          => array('3', 'STRING'),
            'sp_security_login_time'        => array('30', 'STRING'),
            'sp_admin_login_time'           => array('30', 'STRING'),
            'sp_security_login_action'      => array('', 'STRING'),
            'sp_admin_login_action'         => array('', 'STRING'),
            'sp_security_login_inform'      => array('0', 'BOOLEAN'),
            'sp_admin_login_inform'         => array('0', 'BOOLEAN'),
            'sp_security_login_hp'          => array('0', 'BOOLEAN'),
            'sp_admin_login_hp'             => array('0', 'BOOLEAN'),
            'sp_security_register_hp'       => array('0', 'BOOLEAN'),
            'sp_security_recover_hp'        => array('0', 'BOOLEAN'),
            'sp_security_login_unban'       => array('180', 'STRING'),
            'sp_security_login_cron'        => array('1', 'STRING'),
            'sp_admin_login_unban'          => array('180', 'STRING'),
            'sp_admin_login_cron'           => array('1', 'STRING'),
            'sp_activate_topbar'            => array('1', 'BOOLEAN'),
            'sp_topbar_type'                => array('text', 'STRING'),
            'sp_activate_menu'              => array('0', 'BOOLEAN'),
            'sp_activate_topicon'           => array('1', 'BOOLEAN'),
            'sp_activate_pulsemenu'         => array('0', 'BOOLEAN'),
            'sp_menu_after'                 => array('menu_dash', 'STRING'),
            'sp_topicon_position'           => array('left', 'STRING'),
            'sp_update_check'               => array('0', 'BOOLEAN'),
            'sp_check_registrations'        => array('1', 'BOOLEAN'),
            'sp_check_registration_mails'   => array('1', 'BOOLEAN'),
            'sp_check_stopforumspam_mail'   => array('0', 'BOOLEAN'),
            'sp_check_stopforumspam_ip'     => array('0', 'BOOLEAN'),
            'sp_stopforumspam_freq'         => array('3', 'STRING'),
            'sp_stopforumspam_susp'         => array('50', 'STRING'),
            'sp_autoban_stopforumspam'      => array('0', 'BOOLEAN'),
            'sp_stopforum_unban'            => array('0', 'STRING'),
            'sp_stopforum_cron'             => array('1', 'STRING'),
            'sp_badtrusted_activate'        => array('0', 'BOOLEAN'),
            'sp_mailtemplates'              => array('', 'STRING'),
            'sp_theme'                      => array('black', 'STRING'),
            'sp_ipban_table'                => array('', 'STRING'),
            'sp_ipban_redirect'             => array('404', 'STRING'),
            'sp_ipban_redirectURL'          => array('', 'STRING'),
            'sp_delete_expired'             => array('0', 'BOOLEAN'),
            'sp_delete_expired_after'       => array('3', 'STRING'),
            'sp_delete_expired_limit'       => array('100', 'STRING'),
            'sp_delete_unactivated'         => array('0', 'BOOLEAN'),
            'sp_delete_unactivated_after'   => array('7', 'STRING'),
            'sp_delete_unactivated_limit'   => array('100', 'STRING'),
            'sp_delete_spam'                => array('0', 'BOOLEAN'),
            'sp_delete_spam_after'          => array('7', 'STRING'),
            'sp_delete_spam_limit'          => array('100', 'STRING'),            
            'sp_commdel_unactivated'        => array('0', 'BOOLEAN'),
            'sp_commdel_unactivated_after'  => array('7', 'STRING'),
            'sp_commdel_unactivated_limit'  => array('100', 'STRING'),
            'sp_commdel_spam'               => array('0', 'BOOLEAN'),
            'sp_commdel_spam_after'         => array('3', 'STRING'),
            'sp_commdel_spam_limit'         => array('200', 'STRING'),            
            'sp_user_unactivated'           => array('0', 'BOOLEAN'),
            'sp_user_unactivated_after'     => array('360', 'STRING'),
            'sp_user_unactivated_limit'     => array('100', 'STRING'),
            'sp_globallog_activate'         => array('0', 'BOOLEAN'),
            'sp_globallog_limit'            => array('25', 'STRING'),
            'sp_globallog_lifetime'         => array('1 week', 'STRING'),
            'sp_tor_activate'               => array('0', 'BOOLEAN'),
            'sp_tor_notify'                 => array('1', 'BOOLEAN'),
            'sp_tor_cron'                   => array('1', 'BOOLEAN'),
            'sp_tor_ads'                    => array('1', 'BOOLEAN'),
            'sp_tor_login'                  => array('1', 'BOOLEAN'),
            'sp_tor_registration'           => array('1', 'BOOLEAN'),
            'sp_tor_comments'               => array('1', 'BOOLEAN'),
            'sp_tor_contact'                => array('1', 'BOOLEAN'),
            'sp_files_activate'             => array('0', 'BOOLEAN'),
            'sp_files_directory'            => array('', 'STRING'),
            'sp_files_extensions'           => array('', 'STRING'),
            'sp_files_alerts'               => array('', 'STRING'),
            'sp_files_interval'             => array('', 'STRING'),
            'sp_files_exclude'              => array('', 'STRING'),
        );

        if ($key) { return $opts[$key]; }

        return $opts;
    }

    function _get($opt = false, $type = false) {
        $pref = $this->_sect();
        if ($opt) {        
            return osc_get_preference($opt, $pref);
        } else {

            $this->dao->select('*');
            $this->dao->from($this->_table_pref);
            $this->dao->where('s_section', $pref);

            $result = $this->dao->get();
            if (!$result) { return false; }
            $opts = array();

            foreach($result->result() as $k => $v) {
                $opts[$v['s_name']] = $v['s_value'];
            }

            return $opts;
        }
    }

    function _admin_menu_draw() {
        $count    = $this->_countRows('t_item', array(array('key' => 'b_spam', 'value' => '1'), array('key' => 'b_active', 'value' => '0')));
        $comments = $this->_countRows('t_comment', array('key' => 'b_spam', 'value' => '1'));
        $contacts = $this->_countRows('t_sp_contacts');
        $bans     = $this->_countRows('t_sp_ban_log');
        $pulse    = $this->_get('sp_activate_pulsemenu');
        $sidebar  = $this->_get('sp_activate_menu');
        $topicon  = $this->_get('sp_activate_topicon');
        $topbar   = $this->_get('sp_activate_topbar');
        $toptype  = $this->_get('sp_topbar_type');

        if ($topicon == '1') {
            osc_add_hook("render_admintoolbar", array($this, "_admin_topicon"));
        }

        if ($count > 0 || $comments > 0 || $contacts > 0 || $bans > 0) {
            if ($sidebar == '1') {
                osc_add_admin_submenu_divider('spamprotection', __('Actions', 'spamprotection'), 'spamprotection_separator', 'administrator');
            }
        }
        if ($count > 0) {
            if ($sidebar == '1') {
                osc_add_admin_submenu_page('spamprotection', sprintf(__('%s Spam found', 'spamprotection'), $count), osc_admin_base_url(true).'?page=items&b_spam=1', 'spamprotection_spam_found', 'administrator');
            }

            if ($topbar == '1') {
                AdminToolbar::newInstance()->add_menu( array(
                    'id'     => 'spamprotection_ads',
                    'title'  => ($toptype == 'text' ? '<i class="circle circle-red">'.$count.'</i>'.__('Spam Detected', 'spamprotection') : '<i class="sp-alert ads'.($pulse ? ' pulse' : ' highlight').'"><span>'.$count.'</span></i>'),
                    'href'   => osc_admin_base_url(true).'?page=items&b_spam=1',
                    'meta'   => ($toptype == 'text' ? array('class' => 'action-btn action-btn-black') : array('style' => 'min-width: 0; padding: 0;')),
                    'target' => '_self'
                ));
            }
        }
        if ($comments > 0) {
            if ($sidebar == '1') {
                osc_add_admin_submenu_page('spamprotection', sprintf(__('%s Spam comment found', 'spamprotection'), $comments), osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/comments_check.php'), 'spamprotection_spamcomment_found', 'administrator');
            }

            if ($topbar == '1') {
                AdminToolbar::newInstance()->add_menu( array(
                    'id'     => 'spamprotection_comments',
                    'title'  => ($toptype == 'text' ? '<i class="circle circle-gray">'.$comments.'</i>'.__('Spam comment', 'spamprotection') : '<i class="sp-alert comments'.($pulse ? ' pulse' : ' highlight').'"><span>'.$comments.'</span></i>'),
                    'href'   => osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/comments_check.php'),
                    'meta'   => ($toptype == 'text' ? array('class' => 'action-btn action-btn-black') : array('style' => 'min-width: 0; padding: 0;')),
                    'target' => '_self'
                ));
            }
        }
        if ($contacts > 0) {
            if ($sidebar == '1') {
                osc_add_admin_submenu_page('spamprotection', sprintf(__('%s Spam Contact Mail found', 'spamprotection'), $contacts), osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php'), 'spamprotection_spamcontact_found', 'administrator');
            }

            if ($topbar == '1') {
                AdminToolbar::newInstance()->add_menu( array(
                    'id'     => 'spamprotection_contacts',
                    'title'  => ($toptype == 'text' ? '<i class="circle circle-gray">'.$contacts.'</i>'.__('Spam Mail', 'spamprotection') : '<i class="sp-alert mails'.($pulse ? ' pulse' : ' highlight').'"><span>'.$contacts.'</span></i>'),
                    'href'   => osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php'),
                    'meta'   => ($toptype == 'text' ? array('class' => 'action-btn action-btn-black') : array('style' => 'min-width: 0; padding: 0;')),
                    'target' => '_self'
                ));
            }
        }
        if ($bans > 0) {
            if ($sidebar == '1') {
                osc_add_admin_submenu_page('spamprotection', sprintf(__('%s Banned user', 'spamprotection'), $bans), osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/ban_log.php'), 'spamprotection_bans_found', 'administrator');
            }

            if ($topbar == '1') {
                AdminToolbar::newInstance()->add_menu( array(
                    'id'     => 'spamprotection_bans',
                    'title'  => ($toptype == 'text' ? '<i class="circle circle-gray">'.$bans.'</i>'.__('Banned user', 'spamprotection') : '<i class="sp-alert bans'.($pulse ? ' pulse' : ' highlight').'"><span>'.$bans.'</span></i>'),
                    'href'   => osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/ban_log.php'),
                    'meta'   => ($toptype == 'text' ? array('class' => 'action-btn action-btn-black') : array('style' => 'min-width: 0; padding: 0;')),
                    'target' => '_self'
                ));
            }
        }
    }

    function _admin_topicon() {
        $count = $this->_countRows('t_item', array(array('key' => 'b_spam', 'value' => '1'), array('key' => 'b_active', 'value' => '0')));
        $comments = $this->_countRows('t_comment', array('key' => 'b_spam', 'value' => '1'));
        $contacts = $this->_countRows('t_sp_contacts');
        $bans = $this->_countRows('t_sp_ban_log');

        echo '
        <div id="osc_toolbar_spamprotection" style="position: relative !important;margin: 0 0 0 -7px;">
            <a href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=settings').'"><i class="ico-spamprotection topbar"></i></a>
            <nav class="osc_admin_submenu" id="osc_toolbar_sub_spamprotection" style="position: absolute;">';                            

            echo '<ul>';

            if ($count > 0) { 
                echo '<li><a href="'.osc_admin_base_url(true).'?page=items&b_spam=1"><i class="circle circle-gray">'.$count.'</i>'.__('Spam ads', 'spamprotection').'</a></li>';
            }
            if ($comments > 0) { 
                echo '<li><a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/comments_check.php').'"><i class="circle circle-gray">'.$comments.'</i>'.__('Spam comments', 'spamprotection').'</a></li>';
            }
            if ($contacts > 0) { 
                echo '<li><a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/contact_check.php').'"><i class="circle circle-gray">'.$contacts.'</i>'.__('Spam mails', 'spamprotection').'</a></li>';
            }
            if ($bans > 0) { 
                echo '<li><a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/ban_log.php').'"><i class="circle circle-gray">'.$bans.'</i>'.__('Bans found', 'spamprotection').'</a></li>';
            }

        echo '
                    <li><a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)) . 'admin/main.php&tab=settings').'"'.($count > 0 || $comments > 0 || $contacts > 0 || $bans > 0 ? ' style="border-top: 1px solid #fff;"' : '').'>&raquo; '.__('Dashboard', 'spamprotection').'</a></li>
                    <li><a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)) . 'admin/main.php&tab=sp_config').'">&raquo; '.__('Settings', 'spamprotection').'</a></li>
                    <li><a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)) . 'admin/main.php&tab=sp_config&subtab=log').'">&raquo; '.__('Global Log', 'spamprotection').'</a></li>
                    <li><a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)) . 'admin/main.php&tab=sp_help').'">&raquo; '.__('Help', 'spamprotection').'</a></li>
                </ul>
            </nav>

        </div>';
    }

    /**
    * Shows popup message in backend - plugin area
    *
    * @param <h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>Your Title</h1>
    * @param <div style="font-size: 18px;">Your ContEnt</div>
    * @param <div>Your Footer</div>
    * @param false or int() 
    * @param mixed $icon set true if $time = false
    * @param mixed Set own class
    * @param mixed style="Your Style"
    */
    function _showPopup($head, $body, $footer, $time, $icon = true, $class = false, $style = false) {
        return '
            <div id="flash-inner" '.$class.$style.'><div id="flash-head">'.($icon ? '<div id="flash-close"><a class="ico-close">x</a></div>' : '').''.$head.'</div><div id="flash-body" style="display: inline-block;">'.$body.'</div>'.($footer ? '<div id="flash-footer">'.$footer.'</div>' : '').'</div>
            <script>$("#flashmessage").prop("id", "flash").removeClass(); $(document).ready(function(){ $("#flash").fadeIn("slow", function(){ '.($time != '0' ? ' $("#flash").delay('.$time.').fadeOut("slow"); ' : '').'});'.($icon ? ' $(document).on("click", "#flash a.ico-close", function(event){ event.preventDefault; $("#flash").fadeOut("slow"); }); ' : '').'});</script>';    
    }

    function _getRow($table, $where = false, $orderBy = false, $orderDir = 'DESC') {
        
        if ($table == 't_item')            { $table = $this->_table_item; }
        elseif ($table == 't_desc')        { $table = $this->_table_desc; }
        elseif ($table == 't_user')        { $table = $this->_table_user; }
        elseif ($table == 't_comment')     { $table = $this->_table_comment; }
        elseif ($table == 't_sp_ban_log')  { $table = $this->_table_sp_ban_log; }
        elseif ($table == 't_sp_items')    { $table = $this->_table_sp_items; }
        elseif ($table == 't_sp_comments') { $table = $this->_table_sp_comments; }
        elseif ($table == 't_sp_contacts') { $table = $this->_table_sp_contacts; }

        $this->dao->select('*');
        $this->dao->from($table);

        if (is_array($where)) {
            $this->dao->where($where['key'], $where['value']);
        }

        if ($orderBy) {
            $this->dao->orderBy($orderBy, $orderDir);
        }

        $result = $this->dao->get();
        if (!$result) { return false; }

        return $result->row();
    }

    function _getResult($table, $where = false, $orderBy = false, $orderDir = 'DESC') {

        if ($table == 't_item')                 { $table = $this->_table_item; }
        elseif ($table == 't_item_description') { $table = $this->_table_desc; }
        elseif ($table == 't_user')             { $table = $this->_table_user; }
        elseif ($table == 't_comment')          { $table = $this->_table_comment; }
        elseif ($table == 't_sp_ban_log')       { $table = $this->_table_sp_ban_log; }
        elseif ($table == 't_sp_items')         { $table = $this->_table_sp_items; }
        elseif ($table == 't_sp_users')         { $table = $this->_table_sp_users; }
        elseif ($table == 't_sp_comments')      { $table = $this->_table_sp_comments; }
        elseif ($table == 't_sp_contacts')      { $table = $this->_table_sp_contacts; }

        $this->dao->select('*');
        $this->dao->from($table);

        if (is_array($where)) {
            $this->dao->where($where['key'], $where['value']);
        }

        if ($orderBy) {
            $this->dao->orderBy($orderBy, $orderDir);
        }                

        $result = $this->dao->get();
        if (!$result || $result->numRows() <= 0) { return false; }

        return $result->result();
    }

    function _countRows($table, $where = false) {
        if ($table == 't_item')            { $table = $this->_table_item; }
        elseif ($table == 't_user')        { $table = $this->_table_user; }
        elseif ($table == 't_bans')        { $table = $this->_table_bans; }
        elseif ($table == 't_sp_items')    { $table = $this->_table_sp_items; }
        elseif ($table == 't_comment')     { $table = $this->_table_comment; }
        elseif ($table == 't_sp_ban_log')  { $table = $this->_table_sp_ban_log; }
        elseif ($table == 't_sp_contacts') { $table = $this->_table_sp_contacts; }

        $this->dao->select('count(*) as count');
        $this->dao->from($table);

        if (is_array($where)) {
            if (count($where, COUNT_RECURSIVE) > 2) {
                foreach($where as $v) {
                    $this->dao->where($v['key'], $v['value']);
                }
            } else {
                $this->dao->where($where['key'], $where['value']);
            }
        }  

        $result = $this->dao->get();
        if (!$result) { return false; }

        $row = $result->row();
        return $row['count'];
    }

    function _sort($sort) {
        $sort = explode(",", $sort);
        sort($sort);
        return implode(",", $sort);
    }

    function _saveSettings($params, $type = false) {

        $pref = $this->_sect();

        $forbidden      = array('CSRFName', 'CSRFToken', 'page', 'file', 'action', 'tab', 'subtab', 'settings', 'plugin_settings', 'save_mailtemplates', 'trusted', 'bad', 'sp_ipban_table', 'sp_user_minAge', 'sp_user_maxAcc', 'deleteUserID', 'sp_user_zeroads', 'sp_user_activated', 'sp_user_enabled', 'sp_user_noAdmin', 'sp_user_neverlogged');
        $sort           = array('blocked', 'blocked_tld', 'sp_stopwords', 'comment_blocked', 'comment_blocked_tld', 'sp_comment_stopwords', 'contact_blocked', 'contact_blocked_tld', 'sp_contact_stopwords');
        $serialize      = array('sp_files_exclude');
        $mailtemplates  = array('sp_mailtemplates', 'sp_mailuser_user', 'sp_titleuser_user', 'sp_mailuser_admin', 'sp_titleuser_admin', 'sp_mailadmin_user', 'sp_titleadmin_user', 'sp_mailadmin_admin', 'sp_titleadmin_admin');
        $pluginsettings = array('sp_activate_topbar', 'sp_topbar_type', 'sp_activate_menu', 'sp_activate_topicon', 'sp_activate_pulsemenu', 'sp_menu_after', 'sp_topicon_position', 'sp_update_check', 'sp_theme', 'sp_globallog_activate', 'sp_globallog_limit', 'sp_globallog_lifetime');
        $null           = array('sp_globallog_lifetime');

        if ($type == 'mails') {
            $data = array();
            foreach($params as $k => $v) {            
                if (!in_array($k, $forbidden)) {
                    $data[$k] = $v;
                }
            }
            if (!osc_set_preference('sp_mailtemplates', serialize($data), $pref, 'STRING')) {
                return false;
            }
            return true;

        } elseif ($type == 'plugin') {         
            foreach($pluginsettings as $k) {
                $opt = $this->_opt($k);
                if (isset($params[$k]) || in_array($k, $null)) {

                    if (!isset($params[$k])) { $value = '0'; }             
                    else { $value = $params[$k]; }

                    if (in_array($k, $sort)) {
                        $value = $this->_sort($value);

                    } if (!osc_set_preference($k, $value, $pref, $opt[1])) {
                        return false;
                    }
                } else {
                    osc_delete_preference($k, $pref);
                }
            }
            return true;
        } else {

            if (!empty($params['trusted'])) {
                $this->dao->delete($this->_table_sp_users, array('i_reputation' => '2'));
                foreach($params['trusted'] as $k => $v) {
                    $value = serialize($v);
                    $this->dao->insert($this->_table_sp_users, array('pk_i_id' => $k, 'i_reputation' => '2', 's_reputation' => $value));
                }    
            }

            if (!empty($params['bad'])) {
                $this->dao->delete($this->_table_sp_users, array('i_reputation' => '1'));
                foreach($params['bad'] as $k => $v) {
                    $value = serialize($v);
                    $this->dao->insert($this->_table_sp_users, array('pk_i_id' => $k, 'i_reputation' => '1', 's_reputation' => $value));
                }
            }

            $opts = $this->_opt();
            foreach($opts as $k => $v) {
                if (!in_array($k, $mailtemplates) && !in_array($k, $pluginsettings) && !in_array($k, $forbidden)) {
                    if (isset($params[$k])) {
                        $value = $params[$k];
                        if (in_array($k, $sort)) {
                            $value = $this->_sort($value);
                        } if (in_array($k, $serialize)) {
                            $value = serialize(array_filter($value, function($value) { return $value !== ''; }));
                        } if (!osc_set_preference($k, $value, $pref, $v[1])) {
                            return false;
                        }
                    } else {
                        osc_delete_preference($k, $pref);
                    }
                }
            }
            return true;
        }

        return true;
    }

    function _markAsSpam($data, $reason) {
        $this->dao->update($this->_table_item, array('b_enabled' => '0', 'b_active' => '0', 'b_spam' => '1'), array('pk_i_id' => $data['pk_i_id']));
        $this->dao->insert($this->_table_sp_items, array(
            'fk_i_item_id' => $data['fk_i_item_id'], 
            'fk_i_user_id' => $data['fk_i_user_id'], 
            's_reason'     => $reason, 
            's_user_mail'  => $data['s_contact_email']));
    }

    function _markCommentAsSpam($data, $reason) {               
        $this->dao->update($this->_table_comment, array('b_enabled' => '0', 'b_active' => '0', 'b_spam' => '1'), array('pk_i_id' => $data['pk_i_id']));
        $this->dao->insert($this->_table_sp_comments, array(
            'fk_i_comment_id' => $data['pk_i_id'], 
            'fk_i_item_id'    => $data['fk_i_item_id'], 
            'fk_i_user_id'    => $data['fk_i_user_id'], 
            's_reason'        => $reason, 
            's_user_mail'     => $data['s_author_email']));
    }

    function _markContactAsSpam($data, $reason, $token) { 
        $this->dao->insert($this->_table_sp_contacts, array( 
            'fk_i_item_id'   => $data['id'], 
            's_user'         => $data['yourName'], 
            'fk_i_user_id'   => (isset($data['user_id']) ? $data['user_id'] : ''), 
            's_user_mail'    => $data['yourEmail'], 
            's_user_phone'   => $data['phoneNumber'], 
            's_user_message' => $data['message'],
            's_reason'       => $reason,
            's_token'        => $token,
            )
        );
    }

    function _spamAction($type, $id, $ip = false) {

        if ($type == 'activate') {
            $this->dao->update($this->_table_item, array('b_spam' => '0', 'b_enabled' => '1', 'b_active' => '1'), array('pk_i_id' => $id));
        } elseif ($type == 'block') {
            $this->dao->update($this->_table_user, array('b_enabled' => '0'), array('s_email' => $id));
            $this->_addBanLog('block', 'spam', $id, $ip);
        } elseif ($type == 'ban') {
            $reason = __("Spam Protection - Banned because of spam ads", "spamprotection");
            $this->dao->insert($this->_table_bans, array('s_name' => $reason, 's_email' => $id));
            $this->_addBanLog('ban', 'spam', $id, $ip);
        } elseif ($type == 'delete') {
            Item::newInstance()->deleteByPrimaryKey($id);
        }
        return false;
    }

    function _spamActionComments($type, $id) {
        $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());
        $comment = $this->_getRow('t_sp_comments', array('key' => 'pk_i_id', 'value' => $id));

        if ($type == 'activate') {
            $this->_addGlobalLog('Comment activated', $id, $admin['s_name']);
            $this->dao->update($this->_table_comment, array('b_active' => '1', 'b_enabled' => '1', 'b_spam' => '0'), array('pk_i_id' => $comment['fk_i_comment_id']));
            $this->dao->delete($this->_table_sp_comments, 'pk_i_id = '.$comment['pk_i_id']);
        } elseif ($type == 'delete') {
            $this->_addGlobalLog('Comment deleted', $id, $admin['s_name']);
            $this->dao->delete($this->_table_comment, 'pk_i_id = '.$comment['fk_i_comment_id']);
            $this->dao->delete($this->_table_sp_comments, 'pk_i_id = '.$comment['pk_i_id']);
        } elseif ($type == 'block') {
            $blocked = explode(",", $this->_get('comment_blocked'));
            if (!in_array($comment['s_user_mail'], $blocked)) { $blocked[] = $comment['s_user_mail']; }
            $blocked = implode(",", array_filter($blocked));
            $this->_addGlobalLog('Comment blocked', $comment['s_user_mail'], $admin['s_name']);
            osc_set_preference('comment_blocked', $blocked, $this->_sect(), 'STRING');

            $this->dao->delete($this->_table_comment, 'pk_i_id = '.$comment['fk_i_comment_id']);
            $this->dao->delete($this->_table_sp_comments, 'pk_i_id = '.$comment['pk_i_id']);
        }
        return false;
    }

    function _spamActionContacts($type, $id) {
        $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());
        $contact = $this->_getRow('t_sp_contacts', array('key' => 'pk_i_id', 'value' => $id));
        if ($type == 'forward') {
            if ($this->_forwardMail($contact, $contact['fk_i_item_id'])) {
                $this->_addGlobalLog('Contact mail forwarded', $contact, $admin['s_name']);
                $this->dao->delete($this->_table_sp_contacts, 'pk_i_id = '.$id);
            }
        } elseif ($type == 'delete') {
            $this->_addGlobalLog('Contact mail deleted', $contact, $admin['s_name']);
            $this->dao->delete($this->_table_sp_contacts, 'pk_i_id = '.$id);
        } elseif ($type == 'block') {
            $blocked = explode(",", $this->_get('contact_blocked'));     
            if (!in_array($contact['s_user_mail'], $blocked)) { $blocked[] = $contact['s_user_mail']; }
            $blocked = implode(",", array_filter($blocked));
            $this->_addGlobalLog('Contact mail blocked', $contact['s_user_mail'], $admin['s_name']);    
            osc_set_preference('contact_blocked', $blocked, $this->_sect(), 'STRING');
            $this->dao->delete($this->_table_sp_contacts, 'pk_i_id = '.$id);
        }

        return true;

    }

    function _checkForSpam($item, $type = 'mail') {
        $params = Params::getParamsAsArray();
        if ($type == 'mail') { $user = $item['s_contact_email']; }
        else { $user = $item['fk_i_user_id']; }

        // Check for Honeypot
        if ($this->_get('sp_honeypot') == '1') {
            if ($this->_checkHoneypot($params)) {
                $this->_addGlobalLog('Honeypot detected while posting ad', $item['pk_i_id'], 'System');
                return array('params' => $item, 'reason' => 'Bot detected. The Honeypot was filled while creating an ad');
            }
        }

        // Check for blocked mailaddresses
        $blocked = $this->_get('blocked');
        if ($this->_get('sp_blocked') == '1' && !empty($blocked)) {
            if ($this->_checkBlocked($item['s_contact_email'])) {
                $this->_addGlobalLog('Blocked email address found while posting ad for itemID: '.$item['pk_i_id'], $item['s_contact_email'], 'System');
                return array('params' => $item, 'reason' => 'Blocked E-Mail-Address found.');
            }
        }

        // Check for blocked mailaddress tld
        $blocked_tld = $this->_get('blocked_tld');
        if ($this->_get('sp_blocked_tld') == '1' && !empty($blocked_tld)) {
            if ($this->_checkBlockedTLD($item['s_contact_email'])) {
                $this->_addGlobalLog('Blocked email hoster found while posting ad for itemID: '.$item['pk_i_id'], $item['s_contact_email'], 'System');
                return array('params' => $item, 'reason' => 'Blocked E-Mail-Address TLD found.');
            }
        }

        // Check for MX Record
        if ($this->_get('sp_mxr') == '1') {
            $check = $this->_validateMail($item['s_contact_email'], true);
            if ($check['status'] == false) {
                $this->_addGlobalLog('No MX Record found while posting ad for itemID: '.$item['pk_i_id'], $item['s_contact_email'], 'System');
                return array('params' => $item, 'reason' => $check['message']);
            }
        }

        // Check for TOR Network user
        if ($this->_get('sp_blockedtor') == '1') {
            $ip = $this->_IpUserLogin();
            if ($this->_checkTOR($ip)) {
                $this->_addGlobalLog('TOR user blocked for itemID: '.$item['pk_i_id'], $ip, 'System');
                return array('params' => $item, 'reason' => 'TOR user found ('.$ip.').');
            }
        }

        // Check for stopwords
        if ($this->_get('sp_stopwords')) {
            $stopwords = $this->_checkStopwords($item);
            if ($stopwords) {
                $this->_addGlobalLog('Stopword found in ad for itemID: '.$item['pk_i_id'], $stopwords, 'System');
                return array('params' => $item, 'reason' => 'Bad/Stopword found ('.$stopwords.').');
            }
        }

        // Check for duplicates
        $sia = $this->_get('sp_duplicates_as');
        $sim = $this->_get('sp_duplicates');

        if ($sia == '1') {
            $items  = $this->_getItemsByUser($user);
        } elseif ($sia == '2') {
            $items  = $this->_getItemsByAll($user);
        }

        if ($sia == '1' || $sia == '2') {
            while (osc_has_web_enabled_locales()) {
                foreach ($items as $ik => $iv) {
                    $checkTitle = $this->_checkForTitle($iv['pk_i_id'], $item['fk_i_item_id'], osc_locale_code());
                    if ($checkTitle && $item['pk_i_id'] != $iv['pk_i_id']) {
                        $this->_addGlobalLog('Duplicate title for itemID '.$iv['pk_i_id'].' detected: '.(is_numeric($checkTitle) ? 'Similarity: '.$checkTitle.'%' : ''), '', 'System');
                        return array('params' => $item, 'reason' => '<a href="'.osc_admin_base_url(true).'?page=items&action=item_edit&id='.$iv['pk_i_id'].'">Duplicate title found for ItemID: '.$iv['pk_i_id'].'. '.(is_numeric($checkTitle) ? 'Similarity: '.$checkTitle.'%' : '').'</a>');
                    }

                    if ($this->_get('sp_duplicates') == '1') {
                        $checkDescription = $this->_checkForDescription($iv['pk_i_id'], $item['fk_i_item_id'], osc_locale_code());
                        if ($checkDescription && $item['pk_i_id'] != $iv['pk_i_id']) {
                            $this->_addGlobalLog('Duplicate description for itemID '.$iv['pk_i_id'].' detected: '.(is_numeric($checkDescription) ? 'Similarity: '.$checkDescription.'%' : ''), '', 'System');
                            return array('params' => $item, 'reason' => '<a href="'.osc_admin_base_url(true).'?page=items&action=item_edit&id='.$iv['pk_i_id'].'">Duplicate description found for ItemID: '.$iv['pk_i_id'].'. '.(is_numeric($checkDescription) ? 'Similarity: '.$checkDescription.'%' : '').'</a>');
                        }
                    }
                }
            }
        }

        // No spam found
        return false;
    }

    function _checkComment($id) {
        $comment = $this->_getComment($id);
        $spcs = $this->_get('sp_comment_links');

        // Check for url's
        if ($spcs == '1' || $spcs == '2') {
            $regex = "/(?:(?:https?|ftps?|file):\/\/|www\.|ftps?\.)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            if (preg_match($regex, $comment['s_title'], $url)) {
                $this->_addGlobalLog('URL found in comment title', $url[0], 'System');
                return array('params' => $comment, 'reason' => 'URL found in title: '.$url[0]);
            }
            if ($spcs == '2' && preg_match($regex, $comment['s_body'], $url)) {
                $this->_addGlobalLog('URL found in comment', $url[0], 'System');
                return array('params' => $comment, 'reason' => 'URL found in comment: '.$url[0]);
            }
        }

        // Check for blocked mailaddresses
        $blocked = $this->_get('comment_blocked');
        if ($this->_get('sp_comment_blocked') == '1' && !empty($blocked)) {
            if ($this->_checkBlocked($comment['s_author_email'], 'comment')) {
                $this->_addGlobalLog('Blocked email address found in comment', $comment['s_author_email'], 'System');
                return array('params' => $comment, 'reason' => 'Blocked E-Mail-Address found.');
            }
        }

        // Check for blocked mailaddress tld
        $blocked_tld = $this->_get('comment_blocked_tld');
        if ($this->_get('sp_comment_blocked_tld') == '1' && !empty($blocked_tld)) {
            if ($this->_checkBlockedTLD($comment['s_author_email'], 'comment')) {
                $this->_addGlobalLog('Blocked email hoster found in comment', $comment['s_author_email'], 'System');
                return array('params' => $comment, 'reason' => 'Blocked E-Mail-Address TLD found.');
            }
        }

        // Check for stopwords
        if ($this->_get('sp_comment_stopwords')) {
            $stopwords = $this->_checkStopwordsComments($comment);
            if ($stopwords) {
                $this->_addGlobalLog('Stopword found in comment: ', $stopwords, 'System');
                return array('params' => $comment, 'reason' => 'Bad/Stopword found ('.$stopwords.').');
            }
        }

        // No spam found
        return false;
    }

    function _checkContact($data) {
        $params = Params::getParamsAsArray();
        $spcs   = $this->_get('sp_contact_links');

        // Check for url's
        if ($spcs == '1') {
            $regex = "/(?:(?:https?|ftps?|file):\/\/|www\.|ftps?\.)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            if (preg_match($regex, $params['message'], $url)) {
                $this->_addGlobalLog('URL detected in contact mail', $url[0], 'System');
                return array('params' => $params, 'reason' => 'URL found in message: '.$url[0]);
            }
        }

        // Check for Honeypot
        if ($this->_get('sp_contact_honeypot') == '1') {
            if ($this->_checkHoneypotContact($params)) {
                $this->_addGlobalLog('Honeypot detected in contact mail', '', 'System');
                return array('params' => $item, 'reason' => 'Honeypot failed. Maybe bot spam?');
            }
        }

        // Check for blocked mailaddresses
        $blocked = $this->_get('contact_blocked');
        if ($this->_get('sp_contact_blocked') == '1' && !empty($blocked)) {
            if ($this->_checkBlocked($params['yourEmail'], 'contact')) {
                $this->_addGlobalLog('Blocked email address found in contact mail', $params['yourEmail'], 'System');
                return array('params' => $params, 'reason' => 'Blocked E-Mail-Address found.');
            }
        }

        // Check for blocked mailaddress tld
        $blocked_tld = $this->_get('contact_blocked_tld');
        if ($this->_get('sp_contact_blocked_tld') == '1' && !empty($blocked_tld)) {
            if ($this->_checkBlockedTLD($params['yourEmail'], 'contact')) {
                $this->_addGlobalLog('Blocked email hoster found in contact mail', $params['yourEmail'], 'System');
                return array('params' => $params, 'reason' => 'Blocked E-Mail-Address TLD found.');
            }
        }

        // Check for stopwords
        $stopword = $this->_get('sp_contact_stopwords');
        if (!empty($stopword)) {
            $stopwords = $this->_checkStopwordsContact($params);
            if ($stopwords) {
                $this->_addGlobalLog('Stopword found in contact email:', $stopwords, 'System');
                return array('params' => $params, 'reason' => 'Bad/Stopword found ('.$stopwords.').');
            }
        }

        // No spam found
        return false;
    }

    function _checkHoneypot($params) {
        if ($this->_get('honeypot_name')) {
            $hp = $this->_get('honeypot_name');
        } else {
            $hp = 'sp_price_range';
        }

        if (!empty($params[$hp])) { return true; }
        return false;
    }

    function _checkHoneypotContact($params) {
        $name = $this->_get('contact_honeypot_name');
        $name1 = 'captcha';
        $value = $this->_get('contact_honeypot_value');

        if (empty($name)) {
            $name = 'yourDate';
        } if (empty($value)) {
            $value = 'asap';
        }

        if ($params[$name] != $value) { return true; }
        if (!empty($params[$name1])) { return true; }

        return false;
    }

    function _checkBlocked($mail, $type = false) {
        if ($type == 'comment') {
            $check = explode(",", $this->_get('comment_blocked'));
        } elseif ($type == 'contact') {
            $check = explode(",", $this->_get('contact_blocked'));
        } else {
            $check = explode(",", $this->_get('blocked'));
        }

        if (in_array($mail, $check)) {
            return true;
        }
        return false;
    }

    function _checkBlockedTLD($mail, $type = false) {
        if ($type == 'comment') {
            $check = explode(",", $this->_get('comment_blocked_tld'));
        } elseif ($type == 'contact') {
            $check = explode(",", $this->_get('contact_blocked_tld'));
        } else {
            $check = explode(",", $this->_get('blocked_tld'));
        }

        $tld = explode("@", $mail);
        if (in_array($tld[1], $check)) {
            return true;
        }
        return false;
    }

    function _validateMail($email, $test_mx = true) {
        $email = trim($email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($test_mx) {
                list($username, $domain) = explode("@", $email);
                if (getmxrr($domain, $mxrecords)) {
                    return array('status' => true);
                } else {
                    return array('status' => false, 'message' => 'MX Record not found.');
                }
            } else {
                return array('status' => true);
            }
        } else {
            return array('status' => false, 'message' => 'No valid E-Mail-Address.');
        }
    }

    function _checkStopwords($item) {
        $stopwords = explode(',',$this->_get('sp_stopwords'));
        foreach ($item['locale'] as $k => $v) {
            $title = strtolower($v['s_title']);
            $description = strtolower($v['s_description']);
            foreach ($stopwords as $sk => $sv) {
                $search = strtolower($sv);
                if ($this->_get('sp_blockedtype') == 'words') {
                    if (!!preg_match('#\b'.preg_quote($search, '#').'\b#i', $title)) {
                        return $sv;
                    } if (!!preg_match('#\b'.preg_quote($search, '#').'\b#i', $description)) {
                        return $sv;
                    }
                } else if (isset($search)) {
                    if (strpos($title, $search) !== false) {
                        return $sv;
                    } if (strpos($description, $search) !== false) {
                        return $sv;
                    }
                }
            }
        }
        return false;
    }

    function _checkTOR($action = false) {
        $valid = false;
        $ip = spam_prot::newInstance()->_IpUserLogin();
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {
            $file = fopen(osc_plugin_path('spamprotection/tor_nodes.txt'), 'r');
            while (($buffer = fgets($file)) !== false) {
                if (strpos($buffer, $ip) !== false) {
                    if ($action) {
                        return true;
                    } else {
                        $this->_addGlobalLog('New listing blocked', $ip, 'TOR Block');
                        ob_get_clean();
                        osc_add_flash_error_message(__("Posting a new ad is not allowed when using the TOR Network. Please disable TOR and try again."));
                        header('Location: '.osc_base_url());
                        exit;
                    }  
                    break;
                }
            }
            fclose($file);
        }
        return false;
    }

    function _refreshTOR() {
        $file = osc_plugin_path('spamprotection/tor_nodes.txt');
        $data = file_get_contents('https://check.torproject.org/torbulkexitlist?ip='.$_SERVER['SERVER_ADDR']);
        if (file_put_contents($file, $data)) {
            $this->_addGlobalLog('TOR Network Nodes refreshed', '', 'TOR Block');
            return true;
        }
        return false;
    }

    function _checkStopwordsComments($comment) {
        $stopwords = explode(',',$this->_get('sp_comment_stopwords'));
        $title = strtolower(trim($comment['s_title']));
        $description = strtolower(trim($comment['s_body']));

        foreach ($stopwords as $sk => $sv) {
            $search = strtolower(trim($sv));              
            if ($this->_get('sp_comment_blockedtype') == 'words') {
                if (!!preg_match('#\b' . preg_quote($search, '#') . '\b#i', $title)) {
                    return $sv;
                } if (!!preg_match('#\b' . preg_quote($search, '#') . '\b#i', $description)) {
                    return $sv;
                }
            } else if (isset($search)) {
                if (strpos($title, $search) !== false) {
                    return $sv;
                } if (strpos($description, $search) !== false) {
                    return $sv;
                }
            }
        }

        return false;
    }

    function _checkStopwordsContact($comment) {
        $stopwords = explode(',',$this->_get('sp_contact_stopwords'));            
        $message = strtolower(trim($comment['message']));

        foreach ($stopwords as $sk => $sv) {
            $search = strtolower(trim($sv));
            if ($this->_get('sp_contact_blockedtype') == 'words') {
                if (!!preg_match('#\b' . preg_quote($search, '#') . '\b#i', $message)) {
                    return $sv;
                }
            } else if (isset($search)) {
                if (strpos($message, $search) !== false) {
                    return $sv;
                }
            }
        }

        return false;
    }

    function _checkForTitle($item1, $item2, $locale) {
        if ($item1 == $item2) { return false; }

        $check1 = $this->_getItemData($item1, $locale);
        $check2 = $this->_getItemData($item2, $locale);

        if ($this->_get('sp_duplicate_type') == '1') {
            $percent = null;
            $similar = similar_text(@$check1['s_title'], @$check2['s_title'], $percent);
            $compare = ($this->_get('sp_duplicate_perc') ? $this->_get('sp_duplicate_perc') : 0);

            if ($percent >= $compare) {
                return number_format($percent, 0);
            } else {
                return false;
            }
        } else {
            if (strtolower(trim(@$check1['s_title'])) == strtolower(trim(@$check2['s_title']))) {
                return true;
            } else {
                return false;
            }
        }
    }

    function _checkForDescription($item1, $item2, $locale) {
        $check1 = $this->_getItemData($item1, $locale);
        $check2 = $this->_getItemData($item2, $locale);

        if ($this->_get('sp_duplicate_type') == '1') {

            $percent = null;
            $similar = similar_text(@$check1['s_description'], @$check2['s_description'], $percent);
            $compare = ($this->_get('sp_duplicate_perc') ? $this->_get('sp_duplicate_perc') : 0);

            if ($percent >= $compare) {
                return number_format($percent, 0);
            } else {
                return false;
            }
        } else {
            if (md5(strtolower(trim(@$check1['s_description']))) == md5(strtolower(trim(@$check2['s_description'])))) {
                return true;
            } else {
                return false;
            }
        }
    }

    function _getItemsByUser($user) {
        $this->dao->select('*');
        $this->dao->from($this->_table_item);

        if (is_numeric($user)) { $this->dao->where("fk_i_user_id", $user); }
        else { $this->dao->where("s_contact_email", $user); }

        $result = $this->dao->get();
        if (!$result) { return false; }

        return $result->result();
    }

    function _getItemsByAll() {
        $search = $this->_get('sp_duplicates_as');
        $time = $this->_get('sp_duplicates_time');
        $date = date("Y-m-d H:i:s", (time()-($time*24*60*60)));

        $this->dao->select('*');
        $this->dao->from($this->_table_item);

        if ($search == '2' && $time != '0') {
            $this->dao->where("dt_pub_date >= '".$date."'");
            $this->dao->orWhere("dt_mod_date >= '".$date."'");
        }

        $result = $this->dao->get();
        if (!$result) { return false; }

        return $result->result();
    }

    function _getItemData($item, $locale) {

        $this->dao->select('*');
        $this->dao->from($this->_table_desc);

        $this->dao->where("fk_i_item_id", $item);
        $this->dao->where("fk_c_locale_code", $locale);

        $result = $this->dao->get();
        if (!$result) { return false; }

        return $result->row();
    }

    function _getComment($id) {
        $this->dao->select('*');
        $this->dao->from($this->_table_comment);
        $this->dao->where("pk_i_id", $id);

        $result = $this->dao->get();
        if (!$result) { return false; }

        return $result->row();
    }

    function _checkContactUser($user) {
        $this->dao->select('*');
        $this->dao->from($this->_table_user);
        $this->dao->where("s_name", $user);

        $result = $this->dao->get();
        if (!$result) { return false; }

        return $result->row();
    }

    function _searchSpamContact($uniqid) {
        $this->dao->select('*');
        $this->dao->from($this->_table_sp_contacts);
        $this->dao->where("s_token", $uniqid);

        $result = $this->dao->get();
        if (!$result) { return false; }

        $return = $result->row();
        if (!empty($return['pk_i_id'])) { return $return['pk_i_id']; }
    }

    function _deleteMailByUser($id, $token) {
        $contact = $this->_getRow('t_sp_contacts', array('key' => 'pk_i_id', 'value' => $id));
        if ($contact['s_token'] == $token) {
            $this->_addGlobalLog('Blocked contact email deleted by user', $email, 'System');
            $this->dao->delete($this->_table_sp_contacts, 'pk_i_id = '.$id);
        }
    }

    function _forwardMail($data, $itemid) {
        $id          = $itemid;
        $yourEmail   = $data['s_user_mail'];
        $yourName    = $data['s_user'];
        $phoneNumber = $data['s_user_phone'];
        $message     = nl2br(strip_tags($data['s_user_message']));

        $path = null;
        $item = Item::newInstance()->findByPrimaryKey($id);
        View::newInstance()->_exportVariableToView('item', $item);

        $mPages = new Page();
        $aPage  = $mPages->findByInternalName('email_item_inquiry');
        $locale = osc_current_user_locale();

        if( isset($aPage['locale'][$locale]['s_title']) ) {
            $content = $aPage['locale'][$locale];
        } else {
            $content = current($aPage['locale']);
        }

        $item_url = osc_item_url();
        $item_link = '<a href="' . $item_url . '" >' . $item_url . '</a>';

        $words   = array();
        $words[] = array(
            '{CONTACT_NAME}',
            '{USER_NAME}',
            '{USER_EMAIL}',
            '{USER_PHONE}',
            '{ITEM_TITLE}',
            '{ITEM_URL}',
            '{ITEM_LINK}',
            '{COMMENT}'
        );

        $words[] = array(
            $item['s_contact_name'],
            $yourName,
            $yourEmail,
            $phoneNumber,
            $item['s_title'],
            $item_url,
            $item_link,
            $message
        );

        $title = osc_apply_filter('email_item_inquiry_title_after', osc_mailBeauty(osc_apply_filter('email_title', osc_apply_filter('email_item_inquiry_title', $content['s_title'], $data)), $words), $data);
        $body  = osc_apply_filter('email_item_inquiry_description_after', osc_mailBeauty(osc_apply_filter('email_description', osc_apply_filter('email_item_inquiry_description', $content['s_text'], $data)), $words), $data);

        $from      = osc_contact_email();
        $from_name = osc_page_title();

        $emailParams = array(
            'from'      => $from,
            'from_name' => $from_name,
            'subject'   => $title,
            'to'        => $item['s_contact_email'],
            'to_name'   => $item['s_contact_name'],
            'body'      => $body,
            'alt_body'  => $body,
            'reply_to'  => $yourEmail
        );

        if (osc_notify_contact_item()) {
            $emailParams['add_bcc'] = osc_contact_email();
        }

        if (osc_item_attachment()) {
            $attachment   = Params::getFiles('attachment');
            $resourceName = $attachment['name'];
            $tmpName      = $attachment['tmp_name'];
            $path         = osc_uploads_path() . time() . '_' . $resourceName;

            if (!is_writable(osc_uploads_path())) {
                osc_add_flash_error_message(_m('There have been some errors sending the message'));
            }

            if (!move_uploaded_file($tmpName, $path)) {
                unset($path);
            }
        }

        if (isset($path)) {
            $emailParams['attachment'] = $path;
        }

        $return = false;
        if (osc_sendMail($emailParams)) {
            $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());
            $this->_addGlobalLog('Blocked contact mail forwarded to:', $item['s_contact_email'], $admin['s_name']);
            $return = true;
        }
        @unlink($path);

        return $return;
    }


    // Functions for login protection
    function _checkAccount($search, $type = 'user') {
        $this->dao->select('*');

        if ($type == 'user') {
            $this->dao->from($this->_table_user);
            $this->dao->where("s_email", $search);    
        } elseif ($type == 'admin') {
            $this->dao->from($this->_table_admin);
            $this->dao->where("s_username", $search);
        }

        $result = $this->dao->get();
        if ($result && $result->numRows() > 0) { return true; }

        return false;
    }

    function _checkUserLogin($email, $password) {
        $user = User::newInstance()->findByEmail($email);
        if ($user && isset($user['pk_i_id'])) {
            if (!osc_verify_password($password, (isset($user['s_password']) ? $user['s_password'] : ''))) {
                return false;
            } else {
                return true;
            }
        } else {
            return osc_add_flash_error_message(_m("The user doesn't exist"));
        }
    }

    function _checkUserBan($email, $ip) {

        $table = unserialize($this->_get('sp_ipban_table'));
        if (is_array($table) && in_array($ip, $table)) {
            return true;
        }

        $this->dao->select('*');
        $this->dao->from($this->_table_user);
        $this->dao->where("s_email", $email);

        $result = $this->dao->get();
        if (!$result || $result->numRows() <= 0) {
            return false;
        }

        $user = $result->row();
        if ($user['b_enabled'] == '0') {
            return true;
        }

        return false;
    }

    function _handleUserLogin($email, $ip) {

        $action = $this->_get('sp_security_login_action');
        $reason = __("Spam Protection - Too many false login attempts", "spamprotection");

        if ($action == '1') {
            //$this->_addGlobalLog('User account blocked', $email, 'Login Limit');
            $this->dao->update($this->_table_user, array('b_enabled' => '0'), array('s_email' => $email));
            $this->_addBanLog('block', 'falselogin', $email, $ip);
        } elseif ($action == '2') {
            //$this->_addGlobalLog('User account banned', $email, 'Login Limit');
            $this->_doIpBan('add', $ip);
            $this->_addBanLog('ban', 'falselogin', $email, $ip);
        } elseif ($action == '3') {
            //$this->_addGlobalLog('User account blocked and banned', $email, 'Login Limit');
            $this->dao->update($this->_table_user, array('b_enabled' => '0'), array('s_email' => $email));
            $this->_doIpBan('add', $ip);
            $this->_addBanLog('blockban', 'falselogin', $email, $ip);
        }
    }

    function _checkAdminLogin($admin, $password) {
        if (!osc_verify_password($password, (isset($admin['s_password']) ? $admin['s_password'] : ''))) {
            return false;

        } else {
            return true;
        }
    }

    function _checkAdminBan($ip) {

        $table = unserialize($this->_get('sp_ipban_table'));
        if (is_array($table) && in_array($ip, $table)) {
            return true;
        }

        $this->dao->select('*');
        $this->dao->from($this->_table_bans);
        $this->dao->where("s_ip", $ip);
        $this->dao->like("s_name", "Admin/Mod");

        $result = $this->dao->get();
        if ($result && $result->numRows() > 0) {
            return true;
        }

        return false;        
    }

    function _handleAdminLogin($name, $ip) {
        $action = $this->_get('sp_admin_login_action');
        $reason = sprintf(__("Spam Protection - Admin/Mod %s blocked in due to too many false login attempts", "spamprotection"), $name);

        if ($action == '1') {
            //$this->_addGlobalLog('Admin account blocked', $name, 'Login Limit');
            $this->_addBanLog('block', 'falselogin', $name, $ip, 'admin');
        } elseif ($action == '2') {
            //$this->_addGlobalLog('Admin account banned', $name, 'Login Limit');
            //$this->dao->delete($this->_table_sp_logins, '`s_name` = "'.$name.'"');
            $this->_doIpBan('add', $ip);
            $this->_addBanLog('ban', 'falselogin', $name, $ip, 'admin');
        } elseif ($action == '3') {
            //$this->_addGlobalLog('Admin account blocked and banned', $name, 'Login Limit');
            $this->_doIpBan('add', $ip);
            $this->_addBanLog('blockban', 'falselogin', $name, $ip, 'admin');
        }
    }

    function _countLogin($search, $type = 'user', $ip = false) {
        $time = $this->_get('sp_security_login_time')*60;
        if (!$ip) { $ip = $this->_IpUserLogin(); }

        $this->dao->select('*');
        $this->dao->from($this->_table_sp_logins);
        $this->dao->where("dt_date_login > ".(time()-$time));

        if ($type == 'user') {
            $this->dao->where("s_type", $type);
            $this->dao->where("s_email", $search);
        } elseif ($type == 'admin') {
            $this->dao->where("s_type", $type);
            $this->dao->where("s_name", $search);
        }        

        $this->dao->orWhere("s_ip", $ip);

        $result = $this->dao->get();
        if (isset($result)) {
            $rows = $result->numRows();
        }

        if (isset($rows) && $rows > 0) { return $rows; }

        return false;
    }

    function _increaseUserLogin($email) {
        $ip = $this->_IpUserLogin();
        if ($this->dao->insert($this->_table_sp_logins, array('s_email' => $email, 's_ip' => $ip, 's_type' => 'user', 'dt_date_login' => time()))) {
            return true;
        } else {
            return false;
        }
    }

    function _increaseAdminLogin($name) {

        $ip = $this->_IpUserLogin();
        if ($this->dao->insert($this->_table_sp_logins, array('s_name' => $name, 's_ip' => $ip, 's_type' => 'admin', 'dt_date_login' => time()))) {
            return true;
        } else {
            return false;
        }
    }

    function _resetUserLogin($email) {
        $time = $this->_get('sp_security_login_time')*60;
        $ip = $this->_IpUserLogin();

        $this->dao->update($this->_table_user, array('b_enabled' => '1'), array('s_email' => $email));
        //$this->dao->delete($this->_table_bans, '`s_ip` = "'.$ip.'"');
        $this->_doIpBan('delete', $ip);
        $this->dao->delete($this->_table_sp_logins, '`s_email` = "'.$email.'"');
        $this->dao->delete($this->_table_sp_logins, '`s_ip` = "'.$ip.'"');
        $this->dao->delete($this->_table_sp_logins, '`dt_date_login` < "'.(time()-$time).'"');
    }

    function _resetAdminLogin($name) {
        $time = $this->_get('sp_security_login_time')*60;
        $ip = $this->_IpUserLogin();
        //$this->dao->delete($this->_table_bans, '`s_ip` = "'.$ip.'"');
        $this->_doIpBan('delete', $ip);
        $this->dao->delete($this->_table_sp_logins, '`s_name` = "'.$name.'"');
        $this->dao->delete($this->_table_sp_logins, '`s_ip` = "'.$ip.'"');
        $this->dao->delete($this->_table_sp_logins, '`dt_date_login` < "'.(time()-$time).'"');
    }

    function _unbanUser() {

        $time = $this->_get('sp_security_login_unban')*60;

        $this->dao->select('*');
        $this->dao->from($this->_table_sp_logins);
        $this->dao->where("dt_date_login < ".(time()-$time));

        $result = $this->dao->get();
        if ($result && $result->numRows() <= 0) { return false; }

        $bans = $result->result();

        foreach ($bans AS $k => $v) {
            $this->_addGlobalLog('Remove from ban_log', $v['s_email'], 'Cron');
            $this->dao->update($this->_table_user, array('b_enabled' => '1'), array('s_email' => $v['s_email']));
            $this->dao->delete($this->_table_bans, '`s_ip` = "'.$v['s_ip'].'"');
            $this->_doIpBan('delete', $v['s_ip']);
            $this->dao->delete($this->_table_sp_logins, '`pk_i_id` = "'.$v['pk_i_id'].'"');
        }
    }

    function _unbanAdmin() {
        $time = $this->_get('sp_admin_login_unban')*60;

        $this->dao->select('*');
        $this->dao->from($this->_table_sp_logins);
        $this->dao->where("dt_date_login < ".(time()-$time));

        $result = $this->dao->get();
        if ($result && $result->numRows() <= 0) { return false; }

        $bans = $result->result();

        foreach ($bans AS $k => $v) {
            $this->_addGlobalLog('Remove from ban_log', $v['s_name'], 'Cron');
            $this->dao->delete($this->_table_bans, '`s_ip` = "'.$v['s_ip'].'"');
            $this->_doIpBan('delete', $v['s_ip']);
            $this->dao->delete($this->_table_sp_logins, '`pk_i_id` = "'.$v['pk_i_id'].'"');
        }
    }

    function _unbanStopForumSpam() {
        $range = $this->_get('sp_stopforum_unban')*60;
        $time = date("Y-m-d H:i:s", time()-$range);

        $this->dao->select('*');
        $this->dao->from($this->_table_sp_logins);
        $this->dao->where("dt_date_banned < ".$range);

        $result = $this->dao->get();
        if ($result && $result->numRows() <= 0) { return false; }

        $bans = $result->result();

        foreach ($bans AS $k => $v) {
            $this->_addGlobalLog('Account listed on StopForumSpam is now unbanned', $v['s_email'], 'Cron');
            $this->dao->delete($this->_table_bans, '`s_ip` = "'.$v['s_ip'].'"');
            $this->_doIpBan('delete', $v['s_ip']);
            $this->dao->delete($this->_table_sp_logins, '`pk_i_id` = "'.$v['pk_i_id'].'"');    
        }
    }

    function _addBanLog($type, $reason, $email = false, $ip = false, $mode = 'user') {

        if (!$ip) { $ip = $this->_IpUserLogin(); }
        if (osc_is_admin_user_logged_in()) { $ip = ''; }

        if ($email && $mode == 'user') { $user = User::newInstance()->findByEmail($email); }
        elseif ($email && $mode == 'admin') { $user = Admin::newInstance()->findByUsername($email); }

        if ($type == 'block') {
            $reason_sql = __("User was blocked because of", "spamprotection");
        } elseif ($type == 'blockban') {
            $reason_sql = __("User was blocked and banned because of", "spamprotection");
        } else {
            $reason_sql = __("User was banned because of", "spamprotection");
        } if ($reason == 'falselogin') {
            $reason_sql = $reason_sql.'&nbsp;'.__("too many false logins", "spamprotection");
        } elseif ($reason == 'spam') {
            $reason_sql = $reason_sql.'&nbsp;'.__("spam ads", "spamprotection");
        }

        if ($this->dao->insert($this->_table_sp_ban_log, array('i_user_id' => (isset($user['pk_i_id']) ? $user['pk_i_id'] : false), 's_user_email' => $email, 's_user_ip' => $ip, 's_reason' => $reason_sql))) {
            return true;
        } else {
            return false;
        }
    }

    function _handleBanLog($action, $id) {
        if ($action == 'activate') {
            $log = $this->_getRow('t_sp_ban_log', array('key' => 'pk_i_id', 'value' => $id));
            if (isset($log['s_user_email'])) {
                $this->dao->update($this->_table_user, array('b_enabled' => '1'), array('s_email' => $log['s_user_email']));
                $this->dao->delete($this->_table_bans, '`s_email` = "'.$log['s_user_email'].'"');
                $this->dao->delete($this->_table_sp_logins, '`s_email` = "'.$log['s_user_email'].'"');
            } if (isset($log['s_user_ip'])) {
                $this->dao->delete($this->_table_bans, '`s_ip` = "'.$log['s_user_ip'].'"');
                $this->_doIpBan('delete', $log['s_user_ip']);
                $this->dao->delete($this->_table_sp_logins, '`s_ip` = "'.$log['s_user_ip'].'"');
            }  if (isset($id)) {
                $this->dao->delete($this->_table_sp_ban_log, '`pk_i_id` = "'.$id.'"');
            }
        } elseif ($action == 'delete') {
            if (isset($id)) {
                $this->dao->delete($this->_table_sp_ban_log, '`pk_i_id` = "'.$id.'"');
            }
        }
    }

    function _IpUserLogin() {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
           $ip = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            return false;
        }
        return $ip;
    }

    function _informUser($search, $target = 'user') {
        $ip   = $this->_IpUserLogin();
        $time = osc_format_date(date('Y-m-d H:i:s', time()), osc_date_format().' '.osc_time_format());

        if ($target == 'user') {
            $user = User::newInstance()->findByEmail($search);

            $email     = $search;
            $content   = array();
            $content[] = array('{PAGE_NAME}', '{MAIL_USER}', '{MAIL_USED}', '{MAIL_DATE}', '{MAIL_IP}', '{UNBAN_LINK}', '{PASSWORD_LINK}', '{BAN_LIST}');
            $content[] = array(osc_page_title(), (isset($user['s_name']) ? $user['s_name'] : ''), $search, $time, $ip, '<a href="'.osc_base_url(true).'?page=sp_activate_account&email='.$search.'&token='.md5((isset($user['s_secret']) ? $user['s_secret'] : '')).'">Click here</a>', '<a href="'.osc_recover_user_password_url().'">Click here</a>', '<a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/ban_log.php').'">Ban List</a>');
        } elseif ($target == 'admin') {
            $user = Admin::newInstance()->findByUsername($search);

            $email     = &$user['s_email'];
            $content   = array();
            $content[] = array('{PAGE_NAME}', '{MAIL_USER}', '{MAIL_USED}', '{MAIL_DATE}', '{MAIL_IP}', '{UNBAN_LINK}', '{PASSWORD_LINK}', '{BAN_LIST}');
            $content[] = array(osc_page_title(), (isset($user['s_name']) ? $user['s_name'] : ''), $search, $time, $ip, '<a href="'.osc_base_url(true).'?page=sp_activate_account&name='.$search.'&token='.md5((isset($user['s_secret']) ? $user['s_secret'] : '')).'">Click here</a>', '<a href="'.osc_admin_base_url(true).'?page=login&action=recover">Click here</a>', '<a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/ban_log.php').'">Ban List</a>');
        }

        $target_array = array('admin', 'user');            
        foreach($target_array as $target2) {

            $body_extra = '';
            if ($target2 == 'admin') {
                $info = osc_plugin_get_info("spamprotection/index.php");
                $body_extra = "\n\nThis Mail was sended from ".$info['plugin_name'];
            }

            $mail_title      = nl2br(strip_tags($this->_titleTemplate($target, $target2)));
            $mail_body_plain = nl2br(strip_tags($this->_mailTemplate('plain', $target, $target2).$body_extra));
            $mail_body_html  = nl2br(strip_tags($this->_mailTemplate('html', $target, $target2).$body_extra));

            $title      = osc_mailBeauty($mail_title, $content);
            $body_plain = osc_mailBeauty($mail_body_plain, $content);
            $body_html  = osc_mailBeauty($mail_body_html, $content);

            if ($target2 == 'admin' || ($target2 == 'user' && $this->_checkAccount($email, 'user'))) {
                $params = array(
                    'from'      => osc_contact_email(),
                    'from_name' => osc_page_title(),
                    'subject'   => $title,
                    'to'        => ($target2 == 'user' ? $email : osc_contact_email()),
                    'to_name'   => ($user['s_name'] ? $user['s_name'] : ''),
                    'body'      => $body_html,
                    'alt_body'  => $body_plain,
                    'reply_to'  => osc_contact_email()
                );

                osc_sendMail($params);
            }
        }

        return true;
    }

    function _testMail($target, $target2, $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $user = Admin::newInstance()->findByEmail($email);
        $ip   = $this->_IpUserLogin();
        $time = osc_format_date(date('Y-m-d H:i:s', time()), osc_date_format().' '.osc_time_format());

        $plain   = array();
        $plain[] = array('{PAGE_NAME}', '{MAIL_USER}', '{MAIL_USED}', '{MAIL_DATE}', '{MAIL_IP}', '{UNBAN_LINK}', '{PASSWORD_LINK}', '{BAN_LIST}');
        $plain[] = array(osc_page_title(), $user['s_name'], $email, $time, $ip, osc_base_url(true).'?page=sp_activate_account&name='.$email.'&token='.md5($user['s_secret']), osc_admin_base_url(true).'?page=login&action=recover', osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/ban_log.php'));

        $html   = array();
        $html[] = array('{PAGE_NAME}', '{MAIL_USER}', '{MAIL_USED}', '{MAIL_DATE}', '{MAIL_IP}', '{UNBAN_LINK}', '{PASSWORD_LINK}', '{BAN_LIST}');
        $html[] = array(osc_page_title(), $user['s_name'], $email, $time, $ip, '<a href="'.osc_base_url(true).'?page=sp_activate_account&name='.$email.'&token='.md5($user['s_secret']).'">Click here</a>', '<a href="'.osc_admin_base_url(true).'?page=login&action=recover">Click here</a>', '<a href="'.osc_admin_render_plugin_url(osc_plugin_folder(dirname(__FILE__)).'admin/ban_log.php').'">Ban List</a>');

        $body_extra = '';
        if ($target2 == 'admin') {
            $info       = osc_plugin_get_info("spamprotection/index.php");
            $body_extra = "\n\nThis Mail was sended from ".$info['plugin_name'];
        }

        $mail_title      = nl2br(strip_tags($this->_titleTemplate($target, $target2)));
        $mail_body_plain = nl2br(strip_tags($this->_mailTemplate('plain', $target, $target2).$body_extra));
        $mail_body_html  = nl2br(strip_tags($this->_mailTemplate('html', $target, $target2).$body_extra));

        $title      = osc_mailBeauty($mail_title, $html);
        $body_plain = osc_mailBeauty($mail_body_plain, $plain);
        $body_html  = osc_mailBeauty($mail_body_html, $html);

        $params = array(
            'from'      => osc_contact_email(),
            'from_name' => osc_page_title(),
            'subject'   => $title,
            'to'        => $email,
            'to_name'   => $user['s_name'],
            'body'      => $body_html,
            'alt_body'  => $body_plain,
            'reply_to'  => osc_contact_email()
        );

        osc_sendMail($params);    
    }

    function _titleTemplate($target = false, $target2 = false) {

        $title = unserialize($this->_get('sp_mailtemplates'));

        if ($title && !empty($title['sp_title'.$target.'_'.$target2])) {
            return $title['sp_title'.$target.'_'.$target2];    
        } else {
            return __("False logins on {PAGE_NAME}", "spamprotection");
        }
    }

    function _mailTemplate($type = 'html', $target = false, $target2 = false) {

        $template = unserialize($this->_get('sp_mailtemplates'));

        if ($template && !empty($template['sp_mail'.$target.'_'.$target2])) {
            return $template['sp_mail'.$target.'_'.$target2];
        } else {
            if ($type == 'html') {
                return __('Hello {MAIL_USER},','spamprotection').'<br /><br />
                    '.__('We have detected some false logins for your account {MAIL_USED} on {PAGE_NAME}. Last false login was on {MAIL_DATE} from IP {MAIL_IP}','spamprotection').'<br /><br />
                    '.__('As per our security policy, we have temporarily disabled your account and banned the used IP in our System. You can use the following link to unban and reactivate your account. If this was not you, please contact the support and change your password. You can use the password recovery function, if you don\'t remember your password.','spamprotection').'<br /><br /><br />
                    '.__('Unban your account: <a href="{UNBAN_LINK}">{UNBAN_LINK}</a> ','spamprotection').'<br /><br />
                    '.__('Password recovery: <a href="{PASSWORD_LINK}">{PASSWORD_LINK}</a> ','spamprotection').'<br /><br />
                    '.__('Best regards,','spamprotection').'<br />
                    {PAGE_NAME}';
            } elseif ($type == 'plain') {
                return __('Hello {MAIL_USER},','spamprotection').'\r\n\r\n
                    '.__('We have detected some false logins for your account {MAIL_USED} on {PAGE_NAME}. Last false login was on {MAIL_DATE} from IP {MAIL_IP}','spamprotection').'\r\n\r\n
                    '.__('As per our security policy, we have temporarily disabled your account and banned the used IP in our System. You can use the following link to unban and reactivate your account. If this was not you, please contact the support and change your password. You can use the password recovery function, if you don\'t remember your password.','spamprotection').'\r\n\r\n\r\n
                    '.__('Unban your account: {UNBAN_LINK} ','spamprotection').'\r\n\r\n
                    '.__('Password recovery: {PASSWORD_LINK} ','spamprotection').'\r\n\r\n
                    '.__('Best regards,','spamprotection').'\r\n
                    {PAGE_NAME}';
            }
        }
    }

    function _addBanRule($type, $data, $reason) {
        if ($type == 'email') { $string = 's_email'; $string2 = 's_user_email'; }
        elseif ($type == 'ip') { $string = 's_ip'; $string2 = 's_user_ip'; }
        $this->dao->insert($this->_table_bans, array('s_name' => $reason, $string => $data));
        $this->dao->insert($this->_table_sp_ban_log, array('s_name' => $reason, $string2 => $data));
    }

    function _checkBanRule($email = false, $ip = false) {
        $this->dao->select('*');
        $this->dao->from($this->_table_bans);        

        $this->dao->where("s_email", $email);
        $this->dao->orWhere("s_ip", $ip);

        $result = $this->dao->get();
        if ($result && $result->numRows() <= 0) { return false; }

        return true;
    }

    function _isBadOrTrusted($userID, $type = 'ads', $badortrusted = 'trusted') {
        $bt = $this->_get('sp_badtrusted_activate');

        if ($bt == '1') {
            $user = $this->_BadOrTrustedUsers($userID);
            if (isset($user) && !empty($user)) {
                $reputation = unserialize($user['s_reputation']);
                $trusted = array('ads' => 'trustedads', 'comments' => 'trustedcomments', 'contacts' => 'trustedcontacts');
                $bad = array('ads' => 'badads', 'comments' => 'badcomments', 'contacts' => 'badcontacts');

                if ($badortrusted == 'bad') {
                    if (isset($reputation[$bad[$type]]) && $reputation[$bad[$type]] == '1') {
                        return true;
                    }
                } else {
                    if (isset($reputation[$trusted[$type]]) && $reputation[$trusted[$type]] == '1') {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    function _userBadTrusted($table) {
        $table->addColumn('reputation', 'Reputation');
    }

    function _userBadTrustedData($row, $user) {
        $user = $this->_BadOrTrustedUsers($user['pk_i_id']);
        $rep = '';

        if (isset($user['i_reputation']) && $user['i_reputation'] == '1') {
            $rep = '
            <a href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=badtrusted').'">
                <i class="sp-icon thumbsdown" style="display: block; width: 32px; height: 32px; transform: scale(0.6);" title="'.__('This user is on your bad user list', 'spamprotection').'"></i>
            </a>
            ';
        } elseif (isset($user['i_reputation']) && $user['i_reputation'] == '2') {
            $rep = '
            <a href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=badtrusted').'">
                <i class="sp-icon thumbsup" style="display: block; width: 32px; height: 32px; transform: scale(0.6);" title="'.__('This user is on your trusted user list', 'spamprotection').'"></i>
            </a>';
        }

        $row['reputation'] = $rep;
        return $row ;
    }

    function _BadOrTrustedUsers($id) {

        $this->dao->select('*');
        $this->dao->from($this->_table_sp_users);
        $this->dao->where("pk_i_id", $id);

        $result = $this->dao->get();
        if (!$result) { return false; }

        return $result->row();
    }

    function _searchBadOrTrustedUser($search) {

        $this->dao->select('*');
        $this->dao->from($this->_table_user);

        $this->dao->like("s_name", $search);
        $this->dao->orLike("s_username", $search);
        $this->dao->orLike("s_email", $search);
        $this->dao->orLike("s_country", $search);
        $this->dao->orLike("s_address", $search);
        $this->dao->orLike("s_region", $search);
        $this->dao->orLike("s_city", $search);

        $result = $this->dao->get();
        if ($result && $result->numRows() <= 0) { return false; }

        return $result->result();
    }

    function _userManage($action, $user) {
        $this->_userManageAjax($action, $user);
        osc_redirect_to(osc_admin_base_url(true).'?page=users');
    }

    function _userManageAjax($action, $id) {

        $data = $this->_BadOrTrustedUsers($id);

        if ($data) {
            if ($action == 'remove') {
                $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());
                $user  = User::newInstance()->findByPrimaryKey($id);
                $this->_addGlobalLog('Bad/Trusted user removed', $user['s_name'], $admin['s_name']);
                $this->dao->delete($this->_table_sp_users, array('pk_i_id' => $id));
            } else {
                $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());
                $user  = User::newInstance()->findByPrimaryKey($id);
                $this->_addGlobalLog('Bad/Trusted user changed', $user['s_name'], $admin['s_name']);
                $this->dao->update($this->_table_sp_users, array('i_reputation' => $action), array('pk_i_id' => $id));
            }
        } else {
            $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());
            $user  = User::newInstance()->findByPrimaryKey($id);
            $this->_addGlobalLog('Bad/Trusted user added', $user['s_name'], $admin['s_name']);
            $this->dao->insert($this->_table_sp_users, array('pk_i_id' => $id, 'i_reputation' => $action));
        }
        return true;
    }

    function _userManageLinks($options, $user) {
        $bot = $this->_BadOrTrustedUsers($user['pk_i_id']);
        $return = $options;

        if (!isset($bot['i_reputation']) || $bot['i_reputation'] == '0') {        
            $return[] = '<a href="'.osc_admin_base_url(true).'?page=users&adduser=2&user='.$user['pk_i_id'].'">'.__('Trusted User', 'spamprotection').'</a>';
            $return[] = '<a href="'.osc_admin_base_url(true).'?page=users&adduser=1&user='.$user['pk_i_id'].'">'.__('Bad User', 'spamprotection').'</a>';
        } elseif ($bot['i_reputation'] == '1') {
            $return[] = '<a href="'.osc_admin_base_url(true).'?page=users&adduser=2&user='.$user['pk_i_id'].'">'.__('Trusted User', 'spamprotection').'</a>';
            $return[] = '<a href="'.osc_admin_base_url(true).'?page=users&adduser=remove&user='.$user['pk_i_id'].'">'.__('Remove Bad User', 'spamprotection').'</a>';
        } elseif ($bot['i_reputation'] == '2') {
            $return[] = '<a href="'.osc_admin_base_url(true).'?page=users&adduser=1&user='.$user['pk_i_id'].'">'.__('Bad User', 'spamprotection').'</a>';
            $return[] = '<a href="'.osc_admin_base_url(true).'?page=users&adduser=remove&user='.$user['pk_i_id'].'">'.__('Remove Trusted User', 'spamprotection').'</a>';
        }

        return $return;
    }

    function _listIpBanTable() {
        $table = $this->_get('sp_ipban_table');
        if (isset($table) && !empty($table)) {
            $table = unserialize($table);
            if (is_array($table)) {
                return $table;
            }
        }
        return false;
    }

    function _doIpBan($do, $ip) {
        $table = unserialize($this->_get('sp_ipban_table'));
        if ($do == 'add') {
            if (is_array($table)) {
                if (!array_key_exists($ip, $table)) {
                    $table[$ip] = time();
                }
                ksort($table);
            } else {
                $table = array($ip => time());
            }
        } elseif ($do == 'delete') {
            if (is_array($table)) {
                $ip = array($ip => '');
                $table = array_diff_key($table, $ip);
                ksort($table);
            }
        }
        if (osc_set_preference('sp_ipban_table', serialize($table), $this->_sect(), 'STRING')) {
            return $table;
        }
        return false;
    }

    function _userRowIpBan($options, $user) {
        $return = false;
        if (isset($user['s_access_ip'])) {
            $return = $options;
            $return[] = '<a href="'.osc_admin_base_url(true).'?page=users&addIpBan='.$user['s_access_ip'].'">'.__('Add IP Ban', 'spamprotection').'</a>';
        }
        return $return;
    }

    function _readComment($id) {
        $this->dao->select('*');
        $this->dao->from($this->_table_comment);

        $this->dao->where('pk_i_id', $id);

        $result = $this->dao->get();
        if ($result && $result->numRows() <= 0) { return false; }

        return $result->result();
    }

    function _deleteComment($id) {
        $comment = $this->_readComment($id);
        $user = User::newInstance()->findByPrimaryKey($id);

        $this->_addGlobalLog('Comment blocked from bad user', isset($comment['s_auhor_name']) ? $comment['s_auhor_name'] : '', 'Bad User');

        $this->dao->delete($this->_table_comment, '`pk_i_id` = "'.$id.'"');
    }

    function _upgradeDatabaseInfo($error) {
        $params = Params::getParamsAsArray();
        if (isset($params['file'])) { }
        $file = osc_plugin_resource('spamprotection/assets/create_table.sql');

        $tables = file_get_contents($file);
        $tables = str_replace( '/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $tables);
        $tables = preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#','',($tables));

        echo '
            <div id="spamprot_upgrade_overlay" style="display: none;">
                <div id="spamprot_upgrade" style="display: none;">
                    <div id="spamprot_upgrade_close">x</div>
                    <div id="spamprot_upgrade_info">
                        <h2>'.__("Spam Protection - Database needs an update!", "spamprotection").'</h2>
                        '.$error.'
                        <p>'.__("Since you have updated this plugin, the database need an update also.<br /><strong>To update it now, press the button below.</strong>", "spamprotection").'</p>
                        <p>'.__("If you want to backup your data before, you can use the export function.<br />You will find an import function in the plugin settings.", "spamprotection").'</p>
                        <p>'.__("Of course you can do the upgrade manually.<br /><em>For this case you will find the database tables here</em>", "spamprotection").'</p>

                        <div style="height: 180px; text-align: left; margin: 15px 0;">
                            <div id="spamprot_upgrade_tables">
                                <small><strong><textarea style="width: 100%; height: 100%; resize: none; overflow: overlay;">'.$tables.'</textarea></strong></small>
                            </div>
                        </div>
                    </div>

                    <div id="spamprot_upgrade_buttons">
                        <a class="btn btn-blue" href="?sp_upgrade=export">'.__("Export Data", "spamprotection").'</a>
                        <a class="btn btn-green" href="?sp_upgrade=upgrade">'.__("Upgrade now", "spamprotection").'</a>
                        <a class="btn btn-red" href="">'.__("Test again", "spamprotection").'</a>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
        ';
    }

    function _upgradeNow() {
        $file = osc_plugin_resource('spamprotection/assets/create_table.sql');
        $sql = file_get_contents($file);

        if (!$this->dao->importSQL($sql)) {
            throw new Exception( "Error importSQL::spam_prot<br>".$file );
        }
    }

    function _upgradeCheck() {
        $file = osc_plugin_resource('spamprotection/assets/upgrade_check.sql');

        $sql = file_get_contents($file);
        $sql = str_replace( '/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql);
        $sql = preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#','',($sql));

        $queries = $this->_upgradeCheckSplit($sql, ';');
        $exec = array(); $return = true;

        foreach($queries as $query) {
            $query = trim($query);

            if (!empty($query)) {
                $execute = $this->dao->_execute($query);
                if (!$execute) {
                    //$regex = '^.*`\/\*TABLE_PREFIX\*\/(.*)`.*^';  //this
                    //$regex = '^(.*)`\/\*TABLE_PREFIX\*\/(.*)`.*$';  //or this
                    //$exec[] = preg_match($regex, $execute, &$matches);

                    // return table
                    //$matches[1]
                    $return = false;
                }
            }
        }

        if (!$return) {
            return false;
        }

        return true;
    }

    private function _upgradeCheckSplit($sql, $explodeChars) {
        if (preg_match('|^(.*)DELIMITER (\S+)\s(.*)$|isU', $sql, $matches)) {
            $queries = explode($explodeChars, $matches[1]);
            $recursive = $this->splitSQL($matches[3], $matches[2]);

            return array_merge($queries, $recursive);
        }
        else {
            return explode($explodeChars, $sql);
        }
    }

    function _selectExport($table, $where = false) {
        $this->dao->select('*');
        $this->dao->from($table);

        if (is_array($where)) { $this->dao->where($where['key'], $where['value']); }

        $result = $this->dao->get();
        if ($result && $result->numRows() <= 0) { return false; }

        return $result->result();    
    }

    function _prepareExport($type = 'database') {
        if ($type == 'database') {
            $export = array(
                DB_TABLE_PREFIX.'t_ban_rule'                   => $this->_selectExport($this->_table_bans),
                DB_TABLE_PREFIX.'t_spam_protection_ban_log'    => $this->_selectExport($this->_table_sp_ban_log),
                DB_TABLE_PREFIX.'t_spam_protection_global_log' => $this->_selectExport($this->_table_sp_globallog),
                DB_TABLE_PREFIX.'t_spam_protection_items'      => $this->_selectExport($this->_table_sp_items),
                DB_TABLE_PREFIX.'t_spam_protection_users'      => $this->_selectExport($this->_table_sp_users),
                DB_TABLE_PREFIX.'t_spam_protection_comments'   => $this->_selectExport($this->_table_sp_comments),
                DB_TABLE_PREFIX.'t_spam_protection_contacts'   => $this->_selectExport($this->_table_sp_contacts),
                DB_TABLE_PREFIX.'t_spam_protection_logins'     => $this->_selectExport($this->_table_sp_logins)
            );
        } else {
            $export = $this->_selectExport($this->_table_pref, array('key' => 's_section', 'value' => $this->_sect()));
        }

        return $export;
    }
    function _export($type = 'database') {

        if ($type == 'database') {
            $xmlFile = osc_plugin_path(dirname(dirname(__FILE__))) . '/export/database.xml';
            $xml_info = new SimpleXMLElement("<?xml version=\"1.0\"?><database />");
            $this->array_to_xml($this->_prepareExport('database'),$xml_info);
            $xml_file = $xml_info->asXML($xmlFile);
        } else {
            $xmlFile = osc_plugin_path(dirname(dirname(__FILE__))) . '/export/settings.xml';
            $xml_info = new SimpleXMLElement("<?xml version=\"1.0\"?><settings />");
            $this->array_to_xml($this->_prepareExport('settings'),$xml_info);
            $xml_file = $xml_info->asXML($xmlFile);
        }

        if (file_exists($xmlFile)) {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dl = $dom->load($xmlFile);

            if (!$dl) {
                $title = '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("Attention", "spamprotection").'</h1>';
                $error = __("The export file could not be created", "spamprotection");
            }
            $dom->save($xmlFile);
        }

        if ($xml_file){
            $title = '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("Success", "spamprotection").'</h1>';
            $error = sprintf(__("Export file %s was created succesfully", "spamprotection"), ($type == 'database' ? __("for database", "spamprotection") : __("for plugin settings", "spamprotection")));
        } else{
            $title = '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("Attention", "spamprotection").'</h1>';
            $error = __("There was an error while creating the export file", "spamprotection");
        }

        $message = spam_prot::newInstance()->_showPopup($title, $error, "", 1500, false);
        return '<div id="flash">'.$message.'</div>';
    }

    function _import($data, $type = 'server') {

        if ($type == 'upload') {
            $xmlFile = $data;
        } else {
            $xmlFile = osc_plugin_path(dirname(dirname(__FILE__))).'/export/'.$data.'.xml';
        }

        $xml = simplexml_load_file($xmlFile); $return = array();
        $type = $xml->getName();

        if (!in_array($type, array('settings', 'database'))) {
            $title = '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("Error", "spamprotection").'</h1>';
            $error = __("This is not a valid import file", "spamprotection");
            $message = spam_prot::newInstance()->_showPopup($title, $error, "", 1500, false);
            return '<div id="flash">'.$message.'</div>';
        }

        $json  = json_encode($xml);
        $array = json_decode($json, TRUE);
        $error = '';

        if ($type == 'settings') {
            if (!empty($array['data'])) {
                foreach($array['data'] as $v) {
                    if (!empty($v['s_value'])) {
                        if (!osc_set_preference($v['s_name'], $v['s_value'], $v['s_section'], $v['e_type'])) {
                            $error .= sprintf(__("Error while importing settings: %s cannot be saved correctly", "spamprotection"), $v['s_name'])."\n";
                        }
                    }
                }
            }
        } elseif ($type == 'database') {
            foreach($array as $table => $data) {
                if (!empty($data['data'])) {
                    $insert = '';
                    foreach($data['data'] as $row) {
                        $insert = array();
                        foreach($row as $k => $v) {
                            if ($k != 'pk_i_id') {
                                if (empty($v)) { $v = ''; }
                                $insert[$k] = $v;
                            }
                        }
                        if (!$this->dao->insert($table, $insert)) {
                            $error .= sprintf(__("Error while importing data to table %s", "spamprotection"), $table)."\n";
                        }
                    }
                }
            }
        }
        $title   = '<h1 style="display: inline-block;"><i class="sp-icon attention margin-right float-left"></i>'.__("Import", "spamprotection").'</h1>';
        $error  .= __("Import done", "spamprotection");
        $message = spam_prot::newInstance()->_showPopup($title, $error, "", 1500, false);
        return '<div id="flash">'.$message.'</div>';
    }

    function array_to_xml($array, &$xml_info) {
        foreach($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)){
                    $subnode = $xml_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                } else {
                    $subnode = $xml_info->addChild("data");
                    $this->array_to_xml($value, $subnode);
                }
            } else {
                $xml_info->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

    function _firstInstalled() {
        $info = osc_plugin_get_info("spamprotection/index.php");
        $plugin = $info['plugin_name'].' v'.$info['version'];
        osc_delete_preference('sp_first_install', $this->_sect());

        echo '
            <div id="spamprot_installed_overlay" style="display: none;">
                <div id="spamprot_installed" style="margin-top: 50px; display: none;">
                    <div class="spamprot_installed_close">x</div>
                    <div id="spamprot_installed_info">
                        <h2>'.sprintf(__("Welcome to %s", "spamprotection"), $plugin).'</h2>
                        <p>'.__("<strong>Please read this notice carefully.</strong>", "spamprotection").'</p>
                        <p>'.__("This plugin changes the behavior of your whole OSClass installation,<br />because of this, please use it wise.", "spamprotection").'</p>

                        <ul style="margin: 25px auto;width: 450px;text-align: left; font-weight: bolder;">
                            <li style="margin-bottom: 10px"><strong>&raquo;</strong> '.__("Don't activate functions that you don't understand. Take a look in the help to learn more.", "spamprotection").'</li>
                            <li style="margin-bottom: 10px"><strong>&raquo;</strong> '.__("Don't panic if you get a lot of spam. You just need to fine-tune some options here.", "spamprotection").'</li>
                            <li style="margin-bottom: 10px"><strong>&raquo;</strong> '.__("This Plugin can block or ban users and admins. Keep an eye of all actions done on your website.", "spamprotection").'</li>
                        </ul>

                        <p>'.__("<em>We don't want you to ban or annoy users without a reason ;)</em>", "spamprotection").'</p>

                        <br />
                        <p>'.__("<strong>If you are using the check for contact mails, please add some hints into your privacy policy that mails will be saved and moderated, if spam was found inside.</strong>", "spamprotection").'</p>
                        <br />
                        <p>'.__("Feel free to contact me in OSClass-Forum for any kind of question.<br /><br />Best regards<br />Liath", "spamprotection").'</p>
                    </div>

                    <div id="spamprot_installed_buttons">
                        <a class="btn btn-green spamprot_installed_close" href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=settings').'">'.__("Plugin Settings", "spamprotection").'</a>
                        <a class="btn btn-blue spamprot_installed_close" href="https://mirror.osc4u.com/forums.osclass.org/plugins/(plugin)-spam-protection/" target="_blank">'.__("Go to Forum", "spamprotection").'</a>
                        <a class="btn btn-red spamprot_installed_close">'.__("Close", "spamprotection").'</a>
                        <div style="clear: both;"></div>
                    </div>    
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    $("#spamprot_installed_overlay").fadeIn(1250, function(){
                        $("#spamprot_installed").slideDown(1250);
                    });
                    $(document).on("click", ".spamprot_installed_close", function(){
                        $("#spamprot_installed").slideUp(800, function(){
                            $("#spamprot_installed_overlay").fadeOut(800);
                        });
                    })
                });
            </script>
        ';
    }

    function _cleanDatabase() {
        /* clearing ads */
        if ($this->_get('sp_delete_expired') == '1') {
            $interval = $this->_get('sp_delete_expired_after');
            $limit = $this->_get('sp_delete_expired_limit');
            $this->_cleanDatabaseDo('expired', $interval, $limit, 'ads');
        } if ($this->_get('sp_delete_unactivated') == '1') {
            $interval = $this->_get('sp_delete_unactivated_after');
            $limit = $this->_get('sp_delete_unactivated_limit');
            $this->_cleanDatabaseDo('unactivated', $interval, $limit, 'ads');
        } if ($this->_get('sp_delete_spam') == '1') {
            $interval = $this->_get('sp_delete_spam_after');
            $limit = $this->_get('sp_delete_spam_limit');
            $this->_cleanDatabaseDo('spam', $interval, $limit, 'ads');
        }

        /* clearing comments */
        if ($this->_get('sp_commdel_unactivated') == '1') {
            $interval = $this->_get('sp_commdel_unactivated_after');
            $limit = $this->_get('sp_commdel_unactivated_limit');
            $this->_cleanDatabaseDo('unactivated', $interval, $limit, 'comments');
        } if ($this->_get('sp_commdel_spam') == '1') {
            $interval = $this->_get('sp_commdel_spam_after');
            $limit = $this->_get('sp_commdel_spam_limit');
            $this->_cleanDatabaseDo('spam', $interval, $limit, 'comments');
        }

        /* clearing user */
        if ($this->_get('sp_user_unactivated') == '1') {
            $interval = $this->_get('sp_user_unactivated_after');
            $limit = $this->_get('sp_user_unactivated_limit');
            $this->_cleanDatabaseDo('unactivateduser', $interval, $limit, 'user');
        }    
    }

    function _cleanDatabaseDo($mode, $interval, $limit, $type = 'ads') {

        $clean = $this->_cleanDatabaseSearch($mode, $interval, $limit, $type);

        if (is_array($clean)) {
            if ($type == 'ads') {
                $items  = new ItemActions(true);
                foreach($clean as $item) {
                    $user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                    if ($items->delete($item['s_secret'], $item['pk_i_id'])) {
                        $this->_addGlobalLog('Ad deleted by Cleaner', $user['s_name'], 'Cron');
                    }
                }
            } elseif ($type == 'comments') {
                foreach ($clean as $comment) {
                    if ($this->dao->delete($this->_table_comment, array('pk_i_id' => $comment['pk_i_id']))) {
                        $this->_addGlobalLog('Comment deleted by Cleaner', $comment['s_auhor_name'], 'Cron');
                    }
                }
            } elseif ($type == 'user') {
                foreach ($clean as $id) {
                    $user = User::newInstance()->findByPrimaryKey($id['pk_i_id']);
                    if (isset($user['pk_i_id'])) {
                        if (User::newInstance()->deleteUser($user['pk_i_id'])) {
                            $this->_addGlobalLog('User account deleted by Cleaner (Account ID: '.$user['pk_i_id'].')', (isset($user['s_email']) ? $user['s_email'] : 'No Email address'), 'Cron');
                        }
                    }
                }
            }
        }
    }

    function _cleanDatabaseSearch($mode, $interval, $limit, $type = 'ads') {

        /* INTERVAL */
        $time = date('Y-m-d H:i:s', time()-($interval*24*60*60));

        /* TYPE */
        if ($type == 'ads') {
            $this->dao->select('s_secret, pk_i_id, fk_i_user_id');
            $this->dao->from($this->_table_item);
        } elseif ($type == 'comments') {
            $this->dao->select('pk_i_id, s_author_name');
            $this->dao->from($this->_table_comment);
        } elseif ($type == 'user') {
            $this->dao->select('pk_i_id');
            $this->dao->from($this->_table_user);
        }

        /* MODE */
        if ($mode == 'expired') {
            $this->dao->where('dt_expiration < "'.$time.'"');
        } elseif ($mode == 'unactivated') {
            $this->dao->where('b_active', 0);
            $this->dao->where('dt_pub_date < "'.$time.'"');
        } elseif ($mode == 'spam') {
            $this->dao->where('b_spam', 1);
            $this->dao->where('dt_pub_date < "'.$time.'"');
        } elseif ($mode == 'unactivateduser') {
            $this->dao->where('b_active', 0);
            $this->dao->where('dt_reg_date < "'.$time.'"');
        }

        /* LIMIT */
        if (is_numeric($limit) && $limit > 0) { $this->dao->limit($limit); }

        /* RESULT */
        $result = $this->dao->get();
        if (!$result || $result->numRows() <= 0) { return false; }

        return $result->result();
    }

    function _searchUnwantedAdmin($email) {
        $this->dao->select('*');
        $this->dao->from($this->_table_admin);
        $this->dao->where('s_email', $email);

        $result = $this->dao->get();
        if (!$result || $result->numRows() <= 0) {
            return false;
        }
        return true;
    }

    function _searchUnwantedUser($age = false, $max = false, $activated = false, $enabled = false, $zero = false, $logged = false) {

        if (isset($age) && !empty($age)) {
            $time = date("Y-m-d H:i:s", strtotime($age));
        } else {
            $time = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", time())." -1 year"));
        }

        $this->dao->select('u.*');
        $this->dao->from($this->_table_user.' u');

        if ($activated) { $this->dao->where('u.b_active', 1); }
        if ($enabled) { $this->dao->where('u.b_enabled', 1); }
        if ($zero) { $this->dao->where('u.i_items < 1'); }

        if ($logged) {
            $this->dao->where('u.dt_reg_date < "'.$time.'"');
            $this->dao->where('u.dt_access_date', '"0000-00-00 00:00:00"');
        } else {
            $this->dao->where('u.dt_access_date < "'.$time.'"');
        }

        if (is_numeric($max) && $max > 0 && $max < 100) {
            $this->dao->limit($max);
        } else {
            $this->dao->limit('50');
        }

        $result = $this->dao->get();
        if (!$result || $result->numRows() <= 0) { return false; }

        return $result->result();
    }

    function _deleteUnwantedUser($del) {
        if (isset($del) && is_array($del)) {
            foreach ($del as $id) {

                $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());
                $user = User::newInstance()->findByPrimaryKey($id);
                $this->_addGlobalLog('User account deleted by Cleaner', ($user['s_email'] ? $user['s_email'] : 'No Email address'), $admin['s_name']);

                User::newInstance()->deleteUser($id);
            }
        }
    }

    function _addGlobalLog($action, $account, $done) {
        $activate = $this->_get('sp_globallog_activate');
        if (isset($activate) && $activate = '1') {
            $this->dao->insert($this->_table_sp_globallog, array('s_reason' => $action, 's_account' => $account, 's_done' => $done));
        }
    }

    function _clearGlobalLog($by, $lifetime = false) {
        if ($lifetime) {
            $time = date('Y-m-d H:i:s', strtotime('-'.$lifetime));
        } else {
            $time = date('Y-m-d H:i:s', time());
        }
        $this->dao->delete($this->_table_sp_globallog, 'dt_date <= "'.$time.'"');
        $this->_addGlobalLog('Running automatically global log cleaner...', '', $by);
    }

    function _countGlobalLog() {
        $this->dao->select('COUNT(*) as count');
        $this->dao->from($this->_table_sp_globallog);

        $result = $this->dao->get();
        if (!$result || $result->numRows() <= 0) { return false; }

        $count = $result->row();
        return $count['count'];
    }

    function _readGlobalLog($limit, $page = false, $date = false, $search = false, $count = false) {

        $this->dao->select('*');
        $this->dao->from($this->_table_sp_globallog);

        if (isset($date) && !empty($date)) {
            $this->dao->where("DATE(dt_date)", $date);
        }

        if (isset($search) && !empty($search)) {
            $this->dao->like("s_reason", $search);
            $this->dao->orLike("s_account", $search);
            $this->dao->orLike("s_done", $search);
        }

        $offset = 0;
        if (isset($limit) && $limit > 1) {
            $offset = $limit*($page-1);
            $this->dao->limit($offset, $limit);
        }

        $this->dao->orderBy('dt_date', 'DESC');
        $result = $this->dao->get();
        if (!$result || $result->numRows() <= 0) { return false; }

        return $result->result();
    }

    /* compare file system with saved hashes */
    function _checkFiles($now = false) {
        /* get hashes */
        $current    = $this->_getNewHashes();
        $master     = $this->_loadMasterHashes();

        /* First run */
        if (empty($master)) {
            $this->_saveMasterHashes($current);
            $this->_addGlobalLog('File system was scanned first time!', '', 'File monitor');
            return;
        }

        /* filter variables */
        $changed    = array();
        $changes    = array();
        $new        = @array_diff_key($current, $master);
        $deleted    = @array_diff_key($master, $current);
        $keys       = @array_keys(array_intersect_key($master, $current));

        /* check for changes */
        foreach ($keys as $k) {
            if ($master[$k] !== $current[$k]) {
                $changed[$k] = $master[$k];
                $changes[$k] = $current[$k];
            }
        }

        /* returns all found changes */
        if (count($new) > 0 || count($deleted) > 0 || count($changed) > 0) {
            $return = array(
                'new'        => $new,
                'deleted'    => $deleted,
                'changed'    => $changed,
                'changes'    => $changes
            );

            if ($this->_saveMasterHashes($current) && $this->_saveChangedHashes(array_filter($return))) {
                osc_set_preference('sp_files_timeChanges', time(), $this->_sect(), 'STRING');
                $this->_addGlobalLog('<strong>FILE SYSTEM INTEGRITY HURT, CHECK FILE MONITOR!</strong>', '', 'File monitor');
            } else {
                $this->_addGlobalLog('<strong>ATTENTION!!! FILE SYSTEM INTEGRITY HURT, CHANGES COULD NOT BE SAVED, CHECK MANUALLY!</strong>', '', 'File monitor');
            }

            if (!$this->_emailFiles(array('new' => $return['new'], 'changed' => $return['changed'], 'deleted' => $return['deleted']))) {
                $this->_addGlobalLog('Alert email could not be sended to:', $this->_get('sp_files_alerts'), 'File monitor');
            }

            return true;

        } elseif (!empty($this->_loadChangedHashes())) {
            $this->_addGlobalLog('File system integrity was checked, no changes found.', '', 'File monitor');
            return true;
        }
    }

    /*scan file system */
    function _getNewHashes() {

        $config = $this->configFiles;
        $scanDir = osc_base_path().@$config['scanDir'];
        $data = array();

        if (!empty($scanDir)) {
            $iterator = new RecursiveDirectoryIterator($scanDir);
            $compare = array(); $data = array();

            foreach(new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) {
                if (!$this->_excludedPath($file->getPathname(), $config['excludeDir'] = null)) {
                    if (!$file->isDir()) {
                        $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
                        if (!in_array(strtolower($extension), $config['excludeFile'])) {

                            $name        = $file->getPathname();
                            $hash        = sha1_file($file->getPathname());

                            $data[$name] = array(
                                'hash'  => $hash,
                                'time'  => $file->getCTime(),
                                'size'  => $file->getSize(),
                                'exec'  => $file->isExecutable(),
                                'read'  => $file->isReadable(),
                                'write' => $file->isWritable()
                            );
                        }
                    }
                }
            }
        }

        return $data;
    }

    /* check for changes in files */
    function _hasFilesChanges($hashes) {
        if (is_array($hashes)) {
            return true;
        }
        return false;
    }

    /* list changes in files */
    function _listFilesChanges() {
        $hashes = $this->_loadChangedHashes();

        $yes = '<span class="true">'.__("Yes", "spamprotection").'</span>';
        $no  = '<span class="false">'.__("No", "spamprotection").'</span>';
        $typeName = array(
            'new'       => __("New Files", "spamprotection"),
            'deleted'   => __("Deleted Files", "spamprotection"),
            'changed'   => __("Changed Files", "spamprotection"),
        );

        if ($this->_hasFilesChanges($hashes)) {

            $list = '<a class="btn btn-green btn-success" style="float: right;" href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=files&files=clear').'">Clear list</a><br />';

            foreach($hashes as $type => $hash) {
                $changes = @$hashes['changes'];

                if (is_array($hash) && count($hash) > 0 && $type != 'changes') {
                    $list .= '
                    <fieldset>
                        <legend>'.$typeName[$type].' ('.count($hash).')</legend>
                        <ul id="sp_files_monitoring">
                            <li class="sp_monitor_header">
                                <ul class="sp_monitor_header_list">
                                    <li class="sp_monitor_file"><small>'.__("File", "spamprotection").'</small></li>
                                    <li class="sp_monitor_filesize"><small>'.__("Size changed", "spamprotection").'</small></li>
                                    <li class="sp_monitor_file_date"><small>'.__("Last change", "spamprotection").'</small></li>
                                    <li class="sp_monitor_file_info">
                                        <div><small>'.__("Executable", "spamprotection").'</small></div>
                                        <div><small>'.__("Writable", "spamprotection").'</small></div>
                                        <div><small>'.__("Readable", "spamprotection").'</small></div>
                                    </li>
                                </ul>
                            </li>';

                            foreach($hash as $key => $val) {
                                $is_array = is_array($val['hash']);
                                $file = str_replace(osc_base_path(), "../", $key);

                                if ($is_array) { $k = count($val['hash'])-1; }

                                $size = ($is_array ? $this->_sizeFiles(@$val['size'][@$k], @$changes[@$key]['size'][@$k]) : $this->_sizeFiles(@$val['size'], @$changes[@$key]['size']));
                                $time = ($is_array ? osc_format_date(date('Y-m-d H:i:s', @$changes[@$key]['time'][$k]), osc_date_format().' '.osc_time_format()) : osc_format_date(date('Y-m-d H:i:s', @$changes[@$key]['time']), osc_date_format().' '.osc_time_format()));
                                $exec = ($is_array ? ($val['exec'][$k] ? $yes : $no) : ($val['exec'] ? $yes : $no));
                                $writ = ($is_array ? ($val['write'][$k] ? $yes : $no) : ($val['write'] ? $yes : $no));
                                $read = ($is_array ? ($val['read'][$k] ? $yes : $no) : ($val['read'] ? $yes : $no));

                                $list .= '
                                <li class="sp_monitor_content">
                                    <ul class="sp_monitor_content_list">
                                        <li class="sp_monitor_file"><small>'.$file.'</small></li>
                                        <li class="sp_monitor_filesize"><small>'.$size.'</small></li>
                                        <li class="sp_monitor_file_date"><small>'.$time.'</small></li>
                                        <li class="sp_monitor_file_info">
                                            <div>'.$exec.'</div>
                                            <div>'.$writ.'</div>
                                            <div>'.$read.'</div>
                                        </li>
                                    </ul>
                                </li>';
                            }
                        $list .= '</ul>
                    </fieldset>';
                }
            }

            $list .= '<a class="btn btn-green btn-success" style="float: right;" href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=files&files=clear').'">Clear list</a>';
            return $list;
        } else {
            return '
            <h4>'.__("No changes found, all seems to be ok", "spamprotection").'</h4><br />
            <a class="btn btn-info" style="padding: 5px;" href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=files&files=check').'">'.__("Check Now", "spamprotection").'</a>
            ';
        }
    }

    /* load master hashes from database for compare */
    function _loadChangedHashes() {
        $hashes = $this->_get("sp_files_changedHashes");
        return unserialize($hashes);
    }

    /* add to array of changed hashes */
    function _saveChangedHashes($changes) {
        $hashes = $this->_loadChangedHashes();

        if (is_array($hashes)) {
            $changes = array_merge_recursive($hashes, $changes);
        }

        if (!osc_set_preference('sp_files_changedHashes', serialize(array_filter($changes)), $this->_sect(), 'STRING')) {
            return false;
        } else {
            return true;
        }
    }

    /* load master hashes from database for compare */
    function _loadMasterHashes() {
        $hashes = $this->_get("sp_files_masterHashes");
        return unserialize($hashes);
    }

    /* save array of file hashes */
    function _saveMasterHashes($hashes) {
        if (!osc_set_preference('sp_files_masterHashes', serialize(array_filter($hashes)), $this->_sect(), 'STRING')) {
            return false;
        } else {
            return true;
        }
    }

    /*show alerts on page */
    function _showAlertFiles() {
        $changed = $this->_get('sp_files_timeChanges');

        if (!empty($changed)) {            
            echo '
            <style>
            div#alertFilesOuter {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                height: 100%;
                text-align: center;
                background-color: rgba(0, 0, 0, 0.7);
                z-index: 1;
            }

            div#alertFilesInner {
                position: relative;
                margin: 100px auto;
                padding: 20px;
                width: 400px;
                background-color: white;
                border: 1px solid transparent;
                -webkit-transition: ease all 0.8s;
                -moz-transition: ease all 0.8s;
                transition: ease all 0.8s;
            }

            div#alertFilesInner > i {
                position: absolute;
                font-style: normal;
                top: 10px;
                right: 10px;
                cursor: pointer;
            }

            div#alertFilesInner > p {
                margin-bottom: 25px;
            }

            div#alertFilesInner > a {
                float: inherit;
            }

            div#alertFilesInner.glow {
                /*border-color: red;*/
                -webkit-box-shadow: -2px -2px 40px red, 2px 2px 40px red;
                -moz-box-shadow: -2px -2px 40px red, 2px 2px 40px red;
                box-shadow: -2px -2px 40px red, 2px 2px 40px red;
            }
            </style>
            <div id="alertFilesOuter">
                <div id="alertFilesInner">
                    <i class="alertFilesClose">x</i>
                    <h2>'.__("FILE SYSTEM INTEGRITY HURT!").'</h2>
                    <p>Please check immediately your file system monitor for possible break-in attempts.</p>
                    <a class="btn btn-green btn-success" href="'.osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=files').'">Go to file monitor</a>
                </div>
            </div>
            <script>
            $(document).ready(function(){
                if ($("#alertFilesInner").length > 0) {
                    window.setInterval(function() {  
                        $("#alertFilesInner").toggleClass("glow");
                    }, 1000);
                }

                $(document).on("click", "div#alertFilesInner > i", function(){
                    $("div#alertFilesInner").slideUp("slow", function(){
                        $("div#alertFilesOuter").fadeOut("slow");
                    });
                });
            });
            </script>
            ';

            osc_delete_preference('sp_files_timeChanges', $this->_sect());
        }
    }

    /* send email if changes are found */
    function _emailFiles($data) {        
        $mail = $this->_get('sp_files_alerts');
        $info = osc_plugin_get_info("spamprotection/index.php");

        $params = array(
            'from'      => osc_contact_email(),
            'from_name' => osc_page_title(),
            'subject'   => sprintf(__("%s ATTENTION, FILE SYSTEM INTEGRITY HURT!!!", "spamprotection"), osc_page_title()),
            'to'        => $mail,
            'to_name'   => 'Administrator',
            'body'      => "After checking your file system we found following issues:<br /><br />new files: ".count($data['new'])."<br />changed files: ".count($data['changed'])."<br />deleted files: ".count($data['deleted'])."<br /><br />Last scan: ".osc_format_date(date('Y-m-d H:i:s', time()), osc_date_format().' '.osc_time_format())."<br /><br />Please check this immediately in your dashboard for a possible break-in attempt<br /><br /><a href=\"".osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=files')."\">Open file monitor</a><br /><br />This Mail was sended from ".$info['plugin_name'],
            'alt_body'  => "After checking your file system we found following issues:\n\nnew files: ".count($data['new'])."\nchanged files: ".count($data['changed'])."\ndeleted files: ".count($data['deleted'])."\n\nLast scan: ".osc_format_date(date('Y-m-d H:i:s', time()), osc_date_format().' '.osc_time_format())."\n\nPlease check this immediately in your dashboard for a possible break-in attempt\n\n".osc_admin_render_plugin_url('spamprotection/admin/main.php&tab=sp_tools&sub=files')."\n\nThis Mail was sended from ".$info['plugin_name'],
            'reply_to'  => $mail
        );

        if (osc_sendMail($params)) {
            return true;
        } else {
            return false;
        }
    }

    /* human readable file size */
    function _sizeFiles($size, $compare) {

        $decimals = 2;
        $calc = floor((strlen($size) - 1) / 3);
        if ($calc > 0) { $sz = 'KMGT'; }

        if ($size > $compare) {
            $size = $size-$compare;    
        } elseif ($compare > $size) {
            $size = $compare-$size;
        }

        return sprintf("%.{$decimals}f", $size / pow(1024, $calc)) . @$sz[$calc - 1] . 'B';
    }

    /*check for excluded pathes */
    function _excludedPath($fullFilename = null, array $pathArray = null) {
        foreach ((array)$pathArray as $path) {
            $path = rtrim(osc_base_path().$path, '/\\');
            if (strpos($fullFilename, $path) === 0) {
                return true;
            }
        }
        return false;
    }

    /* clear list of changes */
    function _clearAlertFiles() {
        if (!osc_delete_preference('sp_files_changedHashes', $this->_sect())) {
            return false;
        } else {
            header('Location: '.osc_admin_base_url(true)."?page=plugins&action=renderplugin&file=spamprotection/admin/main.php&tab=sp_tools&sub=files");
            exit;
        }
    }
}
