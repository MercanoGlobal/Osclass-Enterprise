<?php
class OsclassGdpr {

    public $debug; 

    private static $instance ;

    public static function newInstance()
    {
        if( !self::$instance instanceof self ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    public function __construct()
    {

        // route 
        osc_add_route('gdpr_download_user_data', 'gdpr', 'gdpr', osc_plugin_folder(__FILE__).'empty.php');

        if(osc_get_preference('portability_enabled', 'gdpr_osclass')=="1") {
            osc_add_filter('user_menu_filter',  function($options) {
                $options[] = array('name' => __('Request GDPR Info', 'gdpr_osclass'), 'url' => osc_route_url('gdpr_download_user_data'), 'class' => 'gdpr_opt_download');
                return $options;
            });
        }
        if(osc_get_preference('remove_account_enabled', 'gdpr_osclass')=="1") {
            osc_add_filter('user_menu_filter',  function($options) {
                $options[] = array('name' => __('Remove account', 'gdpr_osclass'), 'url' => '#', 'class' => 'gdpr_opt_remove_account');
                return $options;
            });

            osc_add_hook('footer', function() {
                $location = Rewrite::newInstance()->get_location();
                $section  = Rewrite::newInstance()->get_section();

                if( $location === 'user' && in_array($section, array('dashboard', 'profile', 'alerts', 'change_email', 'change_username',  'change_password', 'items')) ) {
                    $user = User::newInstance()->findByPrimaryKey( Session::newInstance()->_get('userId') );
                    View::newInstance()->_exportVariableToView('user', $user);
                ?>
                <!-- The Modal -->
                <div id="myModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="gdpr_osclass_close">&times;</span>
                        <p><?php _e('Are you sure you want to delete your account?', 'gdpr_osclass'); ?></p>
                        <button id="gdpr_remove_account"><?php echo osc_esc_html(__('Delete account', 'gdpr_osclass')); ?></button>
                    </div>

                </div>
                <style>
                    /* The Modal (background) */
                    .modal {
                        display: none; /* Hidden by default */
                        position: fixed; /* Stay in place */
                        z-index: 1; /* Sit on top */
                        left: 0;
                        top: 0;
                        width: 100%; /* Full width */
                        height: 100%; /* Full height */
                        overflow: auto; /* Enable scroll if needed */
                        background-color: rgb(0,0,0); /* Fallback color */
                        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                        z-index:9999;
                    }

                    /* Modal Content/Box */
                    .modal-content {
                        background-color: #fefefe;
                        margin: 15% auto; /* 15% from the top and centered */
                        padding: 20px;
                        border: 1px solid #888;
                        width: 300px; /* Could be more or less, depending on screen size */
                    }

                    /* The Close Button */
                    .gdpr_osclass_close {
                        color: #aaa;
                        float: right;
                        font-size: 28px;
                        font-weight: bold;
                    }

                    .gdpr_osclass_close:hover,
                    .gdpr_osclass_close:focus {
                        color: black;
                        text-decoration: none;
                        cursor: pointer;
                    }
                </style>
                <script type="text/javascript">
                    gdpr = {};
                    gdpr.user = {};
                    gdpr.user.id = '<?php echo osc_user_id(); ?>';
                    gdpr.user.secret = '<?php echo osc_user_field("s_secret"); ?>';

                    $(document).ready(function() {
                        // Get the modal
                        var modal = document.getElementById('myModal');

                        // Get the button that opens the modal
                        var btn = document.getElementsByClassName("gdpr_opt_remove_account")[0].firstElementChild;

                        var btn_action = document.getElementById("gdpr_remove_account");
                        btn_action.onclick = function() {
                            window.location = '<?php echo osc_base_url(true); ?>?page=user&action=delete&id=' + gdpr.user.id  + '&secret=' + gdpr.user.secret;
                            return false;
                        }

                        // Get the <span> element that closes the modal
                        var span = document.getElementsByClassName("gdpr_osclass_close")[0];

                        // When the user clicks on the button, open the modal 
                        btn.onclick = function() {
                            modal.style.display = "block";
                        }

                        // When the user clicks on <span> (x), close the modal
                        span.onclick = function() {
                            modal.style.display = "none";
                        }

                        // When the user clicks anywhere outside of the modal, close it
                        window.onclick = function(event) {
                            if (event.target == modal) {
                                modal.style.display = "none";
                            }
                        }

                });
                </script>
            <?php }
            }, 10);

        }

        osc_add_hook('init', array(&$this,'handle_post'));

        if(osc_get_preference('gdpr_enabled', 'gdpr_osclass')=="1") {
            // handle post 

            // item contact form + user contact
            osc_add_hook("item_contact_form", array(&$this,'agree_checkbox') );  // [item-sidebar.php]
            // contact + send friend
            osc_add_hook("contact_form", array(&$this,'agree_checkbox') );       // [item-send-friend.php], [contact.php]
            // register
            osc_add_hook("user_register_form", array(&$this,'agree_checkbox') ); // [user-register.php]

            // used for alert-form.php
            /*
                ...
                else if(data.indexOf("99")==0) { alert('<?php echo osc_esc_js(__('You must accept our "Terms and Conditions" and "Privacy Policy"', 'bender')); ?>'); }

                ...
                <?php osc_run_hook('gdpr'); ?>
            */

            if(!osc_is_web_user_logged_in()) {
                osc_add_hook("gdpr", array(&$this,'agree_checkbox') ); // CUSTOM HOOK
            }
        }
    }

    function agree_checkbox() { 
        $current_locale = osc_current_user_locale();

        $text = osc_get_preference('agree_text_' . $current_locale, 'gdpr_osclass');
        if($text=="") {
            return false;
        }

        $privacy_link = '';
        if(osc_get_preference('privacy_is_page', 'gdpr_osclass')=="1") {
            $page_slug = osc_get_preference('privacy_page', 'gdpr_osclass');
            osc_get_static_page($page_slug, $current_locale);
            $name = (osc_static_page_title($current_locale)!="") ? osc_static_page_title($current_locale) : __('Privacy Policy',  'gdpr_osclass');
            $privacy_link = '<a target="_blank" href="'.osc_static_page_url($current_locale).'">'.$name.'</a>';
        } else {
            $privacy_link = '<a target="_blank" href="'.osc_get_preference('privacy_page', 'gdpr_osclass').'">'.__('Privacy Policy',  'gdpr_osclass').'</a>';
        }

        $terms_link = '';
        if(osc_get_preference('terms_is_page', 'gdpr_osclass')=="1") {
            $page_slug = osc_get_preference('terms_page', 'gdpr_osclass');
            osc_get_static_page($page_slug, $current_locale);
            $name = (osc_static_page_title($current_locale)!="") ? osc_static_page_title($current_locale) : __('Terms and Conditions',  'gdpr_osclass');
            $terms_link = '<a target="_blank" href="'.osc_static_page_url($current_locale).'">'.$name.'</a>';
        } else {
            $terms_link = '<a target="_blank" href="'.osc_get_preference('terms_page', 'gdpr_osclass').'">'.__('Terms and Conditions',  'gdpr_osclass').'</a>';
        }

        $words   = array();
        $words[] = array(
            '{TERMS}',
            '{PRIVACY}',
        );

        $words[] = array(
            $terms_link,
            $privacy_link
        );

        $text = osc_mailBeauty($text, $words);
        echo '<br /><label style="float: none;width: inherit;"><input type="checkbox" name="gdpr_osclass" value="1"/>&nbsp;'.$text.'</label>';
    }

    /**
    * Handle post
    */
    function handle_post() {
        if(Params::getParam('route')=='gdpr_download_user_data') {
            // download 
            // or 
            // send email request to admin 
            if( osc_is_web_user_logged_in() ) {
                $is_download = osc_get_preference('portability_download', 'gdpr_osclass');
                if($is_download=="1") {
                    $return = $this->dump_user_data(osc_logged_user_id());
                    if($return['success']) {
                        osc_add_flash_ok_message( $return['msg'] );
                    } else {
                        osc_add_flash_error_message( $return['msg'] );
                    }
                    osc_redirect_to($return['url_redirect']);
                } else {
                    // send email to admin request portability user data 
                    $body = "";
                    $body .= "<p><b>".__('Requested user data', 'gdpr_osclass')."</b></p>";
                    $body .= "<p>".__('Name:', 'gdpr_osclass').osc_logged_user_name()."</p>";
                    $body .= "<p>".__('User id:', 'gdpr_osclass').osc_logged_user_id()."</p>";
                    $body .= "<p>".__('Email:', 'gdpr_osclass').osc_logged_user_email()."</p>";
                    $body .= "</br></br>";
                    $body .= "<p>".sprintf(__('Generate the user data from <a href="%s">Download user data manually</a>', 'gdpr_osclass'), osc_admin_render_plugin_url('gdpr_osclass/admin/portability.php'))."</p>";
                    $body .= "<p><em>".__('Remember you have 30 days from now to provide all data to the user.', 'gdpr_osclass')."</em></p>";

                    $emailParams = array(
                        'subject'  => __('[GDPR] [Requested User Data]', 'gdpr_osclass') . osc_base_url(),
                        'to'       => osc_contact_email(),
                        'to_name'  => 'admin',
                        'body'     => $body,
                        'alt_body' => $body
                    );

                    if( osc_sendMail($emailParams) ) {
                        osc_add_flash_ok_message( __('Successufuly requested user information, you will receive your data in a few days.', 'gdpr_osclass') );
                        osc_redirect_to( osc_base_url() );
                    } else {
                        osc_add_flash_error_message( __('An error occurred while requesting user information.', 'gdpr_osclass') );
                        osc_redirect_to( osc_base_url() );
                    }
                }
            } else {
                osc_add_flash_error_message( __('Only users', 'gdpr_osclass') );
                osc_redirect_to( osc_base_url() );
            }
        }

        if(osc_get_preference('gdpr_enabled', 'gdpr_osclass')=="1") {
            if(!$this->_validate_needed()) {
                return false;
            }

            if(Params::getParam('gdpr_osclass')!="1") {

                if(osc_is_current_page('ajax', 'alerts')) {
                    echo "99";
                    die();
                }

                $current_locale = osc_current_user_locale();
                $fm = osc_get_preference('error_agree_text_' . $current_locale, 'gdpr_osclass');
                if($fm!="") {
                    osc_add_flash_error_message( $fm );
                } else {
                    osc_add_flash_error_message( __('You must accept our "Terms and Conditions" and "Privacy Policy"', 'gdpr_osclass') );
                }

                $redirect_url = $this->_redirect_url();
                osc_redirect_to( $redirect_url );
                return false; // BREAK THE PROCESS
            }
        }
    }

    function _validate_needed() { 
        if(osc_is_current_page('item', 'item_add_post') && !osc_is_web_user_logged_in()) { return true; }
        if(osc_is_current_page('item', 'item_edit_post') && !osc_is_web_user_logged_in()) { return true; }
        if(osc_is_current_page('ajax', 'alerts') && !osc_is_web_user_logged_in() ) { return true; }
        if(osc_is_current_page('item', 'send_friend_post')) { return true; }
        if(osc_is_current_page('item', 'contact_post')) { return true; }
        if(osc_is_current_page('item', 'add_comment') && !osc_is_web_user_logged_in() && osc_comments_enabled()) { return true; }
        if(osc_is_current_page('register', 'register_post')) { return true; }
        // if(osc_is_current_page('login', 'recover_post')) { return true; }
        if(osc_is_current_page('contact', 'contact_post')) { return true; }
        if(osc_is_current_page('user', 'contact_post')) { return true; }
        return false; 
    }

    function _redirect_url() { 
        $redirect_url = osc_base_url(); 

        // item add post 
        if(osc_is_current_page('item', 'item_add_post')) {
            // SAVE form data before CSRF CHECK
            $mItems = new ItemActions(false);
            $mItems->prepareData(true);
            foreach( $mItems->data as $key => $value ) {
                Session::newInstance()->_setForm($key,$value);
            }

            $meta = Params::getParam('meta');
            if(is_array($meta)) {
                foreach( $meta as $key => $value ) {
                    Session::newInstance()->_setForm('meta_'.$key, $value);
                    Session::newInstance()->_keepForm('meta_'.$key);
                }
            }

            $redirect_url = osc_item_post_url();
        }
        // item edit post
        if(osc_is_current_page('item', 'item_edit_post')) {
            $secret = Params::getParam('secret');
            $id     = Params::getParam('id');
            // SAVE form data before CSRF CHECK
            $mItems = new ItemActions(false);
            // prepare data for ADD ITEM
            $mItems->prepareData(false);
            // set all parameters into session
            foreach( $mItems->data as $key => $value ) {
                Session::newInstance()->_setForm($key,$value);
            }

            $meta = Params::getParam('meta');
            if(is_array($meta)) {
                foreach( $meta as $key => $value ) {
                    Session::newInstance()->_setForm('meta_'.$key, $value);
                    Session::newInstance()->_keepForm('meta_'.$key);
                }
            }
            $redirect_url = osc_item_edit_url($secret, $id);
        }
        // item send to friend post
        if(osc_is_current_page('item', 'send_friend_post')) {
            $item = Item::newInstance()->findByPrimaryKey( Params::getParam('id') );
            View::newInstance()->_exportVariableToView('item', $item);
            $redirect_url = osc_item_send_friend_url();
        }
        if(osc_is_current_page('item', 'add_comment')) {

            $authorName     = Params::getParam('authorName');
            $authorEmail    = Params::getParam('authorEmail');
            $body           = Params::getParam('body');
            $title          = Params::getParam('title');
            $id             = Params::getParam('id');
            $userId         = Session::newInstance()->_get('userId');

            Session::newInstance()->_setForm('commentAuthorName', $authorName);
            Session::newInstance()->_setForm('commentTitle', $title);
            Session::newInstance()->_setForm('commentBody', $body);
            Session::newInstance()->_setForm('commentAuthorEmail', $authorEmail);

            $item = Item::newInstance()->findByPrimaryKey( Params::getParam('id') );
            $redirect_url = osc_item_url_from_item($item);
        }
        // item contact post
        if(osc_is_current_page('item', 'contact_post')) {
            Session::newInstance()->_setForm("yourEmail",   Params::getParam('yourEmail'));
            Session::newInstance()->_setForm("yourName",    Params::getParam('yourName'));
            Session::newInstance()->_setForm("phoneNumber", Params::getParam('phoneNumber'));
            Session::newInstance()->_setForm("message_body",Params::getParam('message'));
            $item = Item::newInstance()->findByPrimaryKey( Params::getParam('id') );
            $redirect_url = osc_item_url_from_item($item);
        }
        // user register
        if(osc_is_current_page('register', 'register_post')) {
            $redirect_url = osc_register_account_url();
        }
        // contact post
        if(osc_is_current_page('contact', 'contact_post')) {
            Session::newInstance()->_setForm('yourName', $yourName);
            Session::newInstance()->_setForm('yourEmail', $yourEmail);
            Session::newInstance()->_setForm('subject', $subject);
            Session::newInstance()->_setForm('message_body', $message);
            $redirect_url = osc_contact_url();
        }
        // user recover post + admin login (oc-admin)
        if(osc_is_current_page('login', 'recover_post')) {
            if( OC_ADMIN ) {
                $redirect_url = osc_admin_base_url(true).'?page=login&action=recover';
            } else {
                $redirect_url = osc_recover_user_password_url();
            }
        }
        // user contact form
        if(osc_is_current_page('user', 'contact_post')) {
            Session::newInstance()->_setForm("yourEmail",   Params::getParam('yourEmail'));
            Session::newInstance()->_setForm("yourName",    Params::getParam('yourName'));
            Session::newInstance()->_setForm("phoneNumber", Params::getParam('phoneNumber'));
            Session::newInstance()->_setForm("message_body",Params::getParam('message'));
            $redirect_url = osc_user_public_profile_url(Params::getParam('id')); 
        }

        return $redirect_url;
    }

    function dump_user_data($userId) {
        // get user data
        $user = User::newInstance()->findByPrimaryKey($userId);
        // get items 
        $items = ModelGdpr::newInstance()->findItemsByUserID($userId);
        // get alerts
        $alerts = ModelGdpr::newInstance()->findAlertsByUser($userId);

        $data['user'] = $user;
        $data['items'] = $items;
        $data['alerts'] = $alerts;

        $_file = sha1(date("Ymd").$userId);
        $tmppath = sys_get_temp_dir()."/";

        $path = $tmppath . $_file.'/';
        if(!mkdir($path, 0777)) {
            return array(
                'msg' => __('Error creating tmp folder (GDPR)', 'gdpr_osclass') ,
                'url_redirect' => osc_base_url(),
                'success'   => false
            );
            // osc_add_flash_error_message( __('Error creating tmp folder (GDPR)', 'gdpr_osclass') );
            // osc_redirect_to(osc_base_url());
        }
        if(!file_exists($path)) {
            return array(
                'msg' => __('Error, tmp folder does not exist (GDPR)', 'gdpr_osclass') ,
                'url_redirect' => osc_base_url(),
                'success'   => false
            );
            // osc_add_flash_error_message( __('Error, tmp folder does not exist (GDPR)', 'gdpr_osclass') );
            // osc_redirect_to(osc_base_url());
        }

        // save to file
        $filename = $path."all_data.txt";
        $fh = fopen($filename, 'w');
        fwrite($fh, json_encode($data, JSON_PRETTY_PRINT).PHP_EOL);
        fclose($fh); 

        // include image resources
        foreach($items as $item) {
            $resources = ItemResource::newInstance()->getAllResourcesFromItem($item['pk_i_id']);
            foreach($resources as $resource) {
                // error_log("RESOURCE ".osc_base_path().$resource['s_path'].$resource['pk_i_id'].'.'.$resource['s_extension']);
                osc_copy(osc_base_path().$resource['s_path'].$resource['pk_i_id'].'.'.$resource['s_extension'], $path);
            }
        }

        // zip file
        $zipfile = $tmppath . $_file . ".zip"; 
        if(!CustomZip::zip_folder($path, $zipfile)) { 
            return array(
                'msg' => __('Error, cannot create zip file (GDPR)', 'gdpr_osclass') ,
                'url_redirect' => osc_base_url(),
                'success'   => false
            );
            // osc_add_flash_error_message( __('Error, cannot create zip file (GDPR)', 'gdpr_osclass') );
            // osc_redirect_to(osc_base_url());
        }

        // remove tmp directory
        if (!$this->_deleteDirectory($path)) {
            return array(
                'msg' => __('Error, cannot remove tmp directory (GDPR)', 'gdpr_osclass') ,
                'url_redirect' => osc_base_url(),
                'success'   => false
            );
            // osc_add_flash_error_message( __('Error, cannot remove tmp directory (GDPR)', 'gdpr_osclass') . " " .$path);
            // osc_redirect_to(osc_base_url());
        }

        // move to final destination
        if(!rename($zipfile, osc_uploads_path().$_file.".zip")) {
            return array(
                'msg' => __('Error moving zip file to final destination (GDPR)', 'gdpr_osclass'),
                'url_redirect' => osc_base_url(),
                'success'   => false
            );
            // osc_add_flash_error_message( __('Error moving zip file to final destination (GDPR)', 'gdpr_osclass') . " " .$path);
            // osc_redirect_to(osc_base_url());
        }
        return array(
            'msg'       => sprintf(__('Download your data from this link: <a href="%s">Download</a> (GDPR)', 'gdpr_osclass'), osc_base_url().'oc-content/uploads/'.$_file.".zip"),
            'url_redirect' => osc_base_url(),
            'success'   => true
        );
        // osc_add_flash_ok_message( sprintf(__('Download your data from this link: <a href="%s">Download</a> (GDPR)', 'gdpr_osclass'), osc_base_url().'oc-content/uploads/'.$_file.".zip") );
        // osc_redirect_to(osc_base_url());
    }

    function _deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->_deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }
}

$OsclassGdpr = OsclassGdpr::newInstance();

if(!function_exists('osc_is_item_send_friend_page')) {
    /**
     * Get if user is on item send friend page
     *
     * @return boolean
     */
    function osc_is_item_send_friend_page() {
        return osc_is_current_page('item', 'send_friend');
    }
}
if(!function_exists('osc_user_contact_url')) {
    /**
     * Get if user is on item send friend page
     *
     * @return boolean
     */
    function osc_user_contact_url($userid = 1) {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . osc_get_preference('rewrite_user_profile') . '/' . $userid;
        } else {
            $path = osc_base_url(true) . '?page=user&action=contact_post&id='.$userid;
        }
        return $path;
    }
}