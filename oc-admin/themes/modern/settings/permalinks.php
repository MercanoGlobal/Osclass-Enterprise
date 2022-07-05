<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    osc_enqueue_script('jquery-validate');

    //customize Head
    function customHead() { ?>
<script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $("form[name=permalinks_form]").validate({
                    rules: {
                        rewrite_item_url: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_page_url: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_cat_url: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_url: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_country: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_region: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_city: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_city_area: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_category: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_user: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_pattern: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_contact: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_feed: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_language: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_mark: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_send_friend: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_contact: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_activate: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_renew: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_edit: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_delete: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_resource_delete: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_login: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_dashboard: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_logout: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_register: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_activate: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_activate_alert: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_profile: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_items: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_alerts: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_recover: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_forgot: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_change_password: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_change_email: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_change_username: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_change_email_confirm: {
                            required: true,
                            minlength: 1
                        }
                    },
                    messages: {
                        rewrite_item_url: {
                            required: '<?php echo osc_esc_js( __("Listings url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Listings url: this field is required")); ?>.'
                        },
                        rewrite_page_url: {
                            required: '<?php echo osc_esc_js( __("Page url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Page url: this field is required")); ?>.'
                        },
                        rewrite_cat_url: {
                            required: '<?php echo osc_esc_js( __("Categories url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Categories url: this field is required")); ?>.'
                        },
                        rewrite_search_url: {
                            required: '<?php echo osc_esc_js( __("Search url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Search url: this field is required")); ?>.'
                        },
                        rewrite_search_country: {
                            required: '<?php echo osc_esc_js( __("Search country: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Search country: this field is required")); ?>.'
                        },
                        rewrite_search_region: {
                            required: '<?php echo osc_esc_js( __("Search region: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Search region: this field is required")); ?>.'
                        },
                        rewrite_search_city: {
                            required: '<?php echo osc_esc_js( __("Search city: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Search city: this field is required")); ?>.'
                        },
                        rewrite_search_city_area: {
                            required: '<?php echo osc_esc_js( __("Search city area: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Search city area: this field is required")); ?>.'
                        },
                        rewrite_search_category: {
                            required: '<?php echo osc_esc_js( __("Search category: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Search category: this field is required")); ?>.'
                        },
                        rewrite_search_user: {
                            required: '<?php echo osc_esc_js( __("Search user: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Search user: this field is required")); ?>.'
                        },
                        rewrite_search_pattern: {
                            required: '<?php echo osc_esc_js( __("Search pattern: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Search pattern: this field is required")); ?>.'
                        },
                        rewrite_contact: {
                            required: '<?php echo osc_esc_js( __("Contact url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Contact url: this field is required")); ?>.'
                        },
                        rewrite_feed: {
                            required: '<?php echo osc_esc_js( __("Feed url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Feed url: this field is required")); ?>.'
                        },
                        rewrite_language: {
                            required: '<?php echo osc_esc_js( __("Language url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Language url: this field is required")); ?>.'
                        },
                        rewrite_item_mark: {
                            required: '<?php echo osc_esc_js( __("Listing mark url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Listing mark url: this field is required")); ?>.'
                        },
                        rewrite_item_send_friend: {
                            required: '<?php echo osc_esc_js( __("Listing send friend url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Listing send friend url: this field is required")); ?>.'
                        },
                        rewrite_item_contact: {
                            required: '<?php echo osc_esc_js( __("Listing contact url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Listing contact url: this field is required")); ?>.'
                        },
                        rewrite_item_new: {
                            required: '<?php echo osc_esc_js( __("New listing url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("New listing url: this field is required")); ?>.'
                        },
                        rewrite_item_activate: {
                            required: '<?php echo osc_esc_js( __("Activate listing url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Activate listing url: this field is required")); ?>.'
                        },
                        rewrite_item_renew: {
                            required: '<?php echo osc_esc_js( __("Listing renewal url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Listing renewal url: this field is required")); ?>.'
                        },
                        rewrite_item_edit: {
                            required: '<?php echo osc_esc_js( __("Edit listing url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Edit listing url: this field is required")); ?>.'
                        },
                        rewrite_item_delete: {
                            required: '<?php echo osc_esc_js( __("Delete listing url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Delete listing url: this field is required")); ?>.'
                        },
                        rewrite_item_resource_delete: {
                            required: '<?php echo osc_esc_js( __("Delete listing resource url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Delete listing resource url: this field is required")); ?>.'
                        },
                        rewrite_user_login: {
                            required: '<?php echo osc_esc_js( __("Login url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Login url: this field is required")); ?>.'
                        },
                        rewrite_user_dashboard: {
                            required: '<?php echo osc_esc_js( __("User dashboard url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("User dashboard url: this field is required")); ?>.'
                        },
                        rewrite_user_logout: {
                            required: '<?php echo osc_esc_js( __("Logout url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Logout url: this field is required")); ?>.'
                        },
                        rewrite_user_register: {
                            required: '<?php echo osc_esc_js( __("User register url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("User register url: this field is required")); ?>.'
                        },
                        rewrite_user_activate: {
                            required: '<?php echo osc_esc_js( __("Activate user url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Activate user url: this field is required")); ?>.'
                        },
                        rewrite_user_activate_alert: {
                            required: '<?php echo osc_esc_js( __("Activate alert url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Activate alert url: this field is required")); ?>.'
                        },
                        rewrite_user_profile: {
                            required: '<?php echo osc_esc_js( __("User profile url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("User profile url: this field is required")); ?>.'
                        },
                        rewrite_user_items: {
                            required: '<?php echo osc_esc_js( __("User listings url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("User listings url: this field is required")); ?>.'
                        },
                        rewrite_user_alerts: {
                            required: '<?php echo osc_esc_js( __("User alerts url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("User alerts url: this field is required")); ?>.'
                        },
                        rewrite_user_recover: {
                            required: '<?php echo osc_esc_js( __("Recover user url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Recover user url: this field is required")); ?>.'
                        },
                        rewrite_user_forgot: {
                            required: '<?php echo osc_esc_js( __("User forgot url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("User forgot url: this field is required")); ?>.'
                        },
                        rewrite_user_change_password: {
                            required: '<?php echo osc_esc_js( __("Change password url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Change password url: this field is required")); ?>.'
                        },
                        rewrite_user_change_email: {
                            required: '<?php echo osc_esc_js( __("Change email url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Change email url: this field is required")); ?>.'
                        },
                        rewrite_user_change_username: {
                            required: '<?php echo osc_esc_js( __("Change username url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Change username url: this field is required")); ?>.'
                        },
                        rewrite_user_change_email_confirm: {
                            required: '<?php echo osc_esc_js( __("Change email confirm url: this field is required")); ?>.',
                            minlength: '<?php echo osc_esc_js( __("Change email confirm url: this field is required")); ?>.'
                        }
                    },
                    wrapper: "li",
                    errorLabelContainer: "#error_list",
                    invalidHandler: function(form, validator) {
                        $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                    },
                    submitHandler: function(form){
                        $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
                        form.submit();
                    }
                });
            });

            function showhide() {
                $("#inner_rules").toggle();
                if($("#show_hide a").html()=='<?php echo osc_esc_js(__('Show rules')); ?>') {
                    $("#show_hide a").html('<?php echo osc_esc_js(__('Hide rules')); ?>');
                    resetLayout();
                } else {
                    $("#show_hide a").html('<?php echo osc_esc_js(__('Show rules')); ?>')
                }
            }

            $(function() {
                $("#rewrite_enabled").click(function(){
                    $("#custom_rules").toggle();
                });
            });
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    function render_offset(){
        return 'row-offset';
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function addHelp() {
        echo '<p>' . __("Activate this option if you want your site's URLs to be more attractive to search engines and intelligible for users, and if you want to boost security. <strong>Be careful</strong>: depending on your hosting service, this might not work correctly.") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Permalinks &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="mail-setting">
    <!-- settings form -->
                    <div id="mail-settings">
                        <h2 class="render-title"><?php _e('Permalinks'); ?></h2>
                        <?php _e('By default Osclass uses web URLs which have question marks and lots of numbers in them. However, Osclass offers you friendly URLs. This can improve the aesthetics, usability, and forward-compatibility of your links. By activating the Permalinks, you will also enable the <a href="https://perishablepress.com/7g-firewall/" target="_blank">7G WAF</a> that offers server-level protection against a wide range of malicious requests, bad bots, automated attacks, spam, and many other types of threats.'); ?>
                        <ul id="error_list"></ul>
                        <form name="settings_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="permalinks_post" />
                            <fieldset>
                            <div class="form-horizontal">
                            <div class="form-row">
                                <div class="form-label"><?php _e('Enable friendly urls'); ?></div>
                                <div class="form-controls">
                                    <div class="form-label-checkbox"><input type="checkbox" <?php echo ( osc_rewrite_enabled() ? 'checked="checked"' : '' ); ?> name="rewrite_enabled" id="rewrite_enabled" value="1" />
                                    </div>
                                </div>
                            </div>
                            <div id="custom_rules" <?php if( !osc_rewrite_enabled() ) { echo 'class="hide"'; } ?>>
                                <div id="show_hide" ><a href="#" onclick="javascript:showhide();"><?php _e('Show rules'); ?></a></div>
                                <div id="inner_rules" class="hide">
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing URL:'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_url" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_url')); ?>" />
                                            <div class="help-box">
                                                <?php echo sprintf(__('Accepted keywords: %s'), '{ITEM_ID},{ITEM_TITLE},{ITEM_CITY},{CATEGORIES}'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Page URL:'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_page_url" value="<?php echo osc_esc_html(osc_get_preference('rewrite_page_url')); ?>" />
                                            <div class="help-box">
                                                <?php echo sprintf(__('Accepted keywords: %s'), '{PAGE_ID}, {PAGE_SLUG}'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Category URL:'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_cat_url" value="<?php echo osc_esc_html(osc_get_preference('rewrite_cat_url')); ?>" />
                                            <div class="help-box">
                                                <?php echo sprintf(__('Accepted keywords: %s'), '{CATEGORY_ID},{CATEGORY_NAME},{CATEGORIES}'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search prefix URL:'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="seo_url_search_prefix" value="<?php echo osc_esc_html(osc_get_preference('seo_url_search_prefix')); ?>" />
                                            <div class="help-box">
                                                <?php _e('It always appear before the category, region or city url.'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search URL:'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_search_url" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_url')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search keyword country'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_search_country" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_country')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search keyword region'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_search_region" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_region')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search keyword city'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_search_city" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_city')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search keyword city area'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_search_city_area" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_city_area')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search keyword category'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_search_category" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_category')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search keyword user'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_search_user" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_user')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Search keyword pattern'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_search_pattern" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_pattern')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Contact'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_contact" value="<?php echo osc_esc_html(osc_get_preference('rewrite_contact')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Feed'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_feed" value="<?php echo osc_esc_html(osc_get_preference('rewrite_feed')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Language'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_language" value="<?php echo osc_esc_html(osc_get_preference('rewrite_language')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing mark'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_mark" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_mark')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing send friend'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_send_friend" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_send_friend')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing contact'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_contact" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_contact')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing new'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_new" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_new')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing activate'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_activate" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_activate')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing renew'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_renew" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_renew')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing edit'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_edit" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_edit')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing delete'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_delete" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_delete')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Listing resource delete'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_item_resource_delete" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_resource_delete')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User login'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_login" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_login')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User dashboard'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_dashboard" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_dashboard')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User logout'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_logout" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_logout')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User register'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_register" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_register')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User activate'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_activate" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_activate')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User activate alert'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_activate_alert" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_activate_alert')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User profile'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_profile" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_profile')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User listings'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_items" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_items')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User alerts'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_alerts" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_alerts')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User recover'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_recover" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_recover')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User forgot'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_forgot" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_forgot')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User change password'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_change_password" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_change_password')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User change email'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_change_email" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_change_email')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User change email confirm'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_change_email_confirm" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_change_email_confirm')); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('User change username'); ?></div>
                                        <div class="form-controls">
                                            <input type="text" class="input-large" name="rewrite_user_change_username" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_change_username')); ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if( osc_rewrite_enabled() ) { ?>
                            <?php if( file_exists(osc_base_path() . '.htaccess') ) { ?>
                            <div class="form-row">
                                <h3 class="separate-top"><?php _e('Your .htaccess file') ?></h3>
                                <pre><?php
                                    $htaccess_content =  file_get_contents(osc_base_path() . '.htaccess');
                                    echo htmlentities($htaccess_content);
                                ?></pre>
                            </div>
                            <div class="form-row">
                                <h3 class="separate-top"><?php _e('What your .htaccess file should look like'); ?></h3>
                                <pre><?php
                                    $rewrite_base = REL_WEB_URL;
                                    $htaccess     = <<<HTACCESS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$rewrite_base}
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$rewrite_base}index.php [L]
</IfModule>

# 7G Firewall:[CORE]
ServerSignature Off
Options -Indexes

# 7G Firewall:[QUERY STRING]
<IfModule mod_rewrite.c>

	RewriteCond %{QUERY_STRING} ([a-z0-9]{2000,}) [NC,OR]
	RewriteCond %{QUERY_STRING} (/|%2f)(:|%3a)(/|%2f) [NC,OR]
	RewriteCond %{QUERY_STRING} (order(\s|%20)by(\s|%20)1--) [NC,OR]
	RewriteCond %{QUERY_STRING} (/|%2f)(\*|%2a)(\*|%2a)(/|%2f) [NC,OR]
	RewriteCond %{QUERY_STRING} (`|<|>|\^|\|\\|0x00|%00|%0d%0a) [NC,OR]
	RewriteCond %{QUERY_STRING} (ckfinder|fck|fckeditor|fullclick) [NC,OR]
	RewriteCond %{QUERY_STRING} ((.*)header:|(.*)set-cookie:(.*)=) [NC,OR]
	RewriteCond %{QUERY_STRING} (cmd|command)(=|%3d)(chdir|mkdir)(.*)(x20) [NC,OR]
	RewriteCond %{QUERY_STRING} (/|%2f)((wp-)?config)((\.|%2e)inc)?((\.|%2e)php) [NC,OR]
	RewriteCond %{QUERY_STRING} (thumbs?(_editor|open)?|tim(thumbs?)?)((\.|%2e)php) [NC,OR]
	RewriteCond %{QUERY_STRING} (absolute_|base|root_)(dir|path)(=|%3d)(ftp|https?) [NC,OR]
	RewriteCond %{QUERY_STRING} (localhost|loopback|127(\.|%2e)0(\.|%2e)0(\.|%2e)1) [NC,OR]
	RewriteCond %{QUERY_STRING} (s)?(ftp|inurl|php)(s)?(:(/|%2f|%u2215)(/|%2f|%u2215)) [NC,OR]
	RewriteCond %{QUERY_STRING} (\.|20)(get|the)(_|%5f)(permalink|posts_page_url)(\(|%28) [NC,OR]
	RewriteCond %{QUERY_STRING} ((boot|win)((\.|%2e)ini)|etc(/|%2f)passwd|self(/|%2f)environ) [NC,OR]
	RewriteCond %{QUERY_STRING} (((/|%2f){3,3})|((\.|%2e){3,3})|((\.|%2e){2,2})(/|%2f|%u2215)) [NC,OR]
	RewriteCond %{QUERY_STRING} (benchmark|char|exec|fopen|function|html)(.*)(\(|%28)(.*)(\)|%29) [NC,OR]
	RewriteCond %{QUERY_STRING} (php)([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}) [NC,OR]
	RewriteCond %{QUERY_STRING} (e|%65|%45)(v|%76|%56)(a|%61|%31)(l|%6c|%4c)(.*)(\(|%28)(.*)(\)|%29) [NC,OR]
	RewriteCond %{QUERY_STRING} (/|%2f)(=|%3d|$&|_mm|cgi(\.|-)|inurl(:|%3a)(/|%2f)|(mod|path)(=|%3d)(\.|%2e)) [NC,OR]
	RewriteCond %{QUERY_STRING} (<|%3c)(.*)(e|%65|%45)(m|%6d|%4d)(b|%62|%42)(e|%65|%45)(d|%64|%44)(.*)(>|%3e) [NC,OR]
	RewriteCond %{QUERY_STRING} (<|%3c)(.*)(i|%69|%49)(f|%66|%46)(r|%72|%52)(a|%61|%41)(m|%6d|%4d)(e|%65|%45)(.*)(>|%3e) [NC,OR]
	RewriteCond %{QUERY_STRING} (<|%3c)(.*)(o|%4f|%6f)(b|%62|%42)(j|%4a|%6a)(e|%65|%45)(c|%63|%43)(t|%74|%54)(.*)(>|%3e) [NC,OR]
	RewriteCond %{QUERY_STRING} (<|%3c)(.*)(s|%73|%53)(c|%63|%43)(r|%72|%52)(i|%69|%49)(p|%70|%50)(t|%74|%54)(.*)(>|%3e) [NC,OR]
	RewriteCond %{QUERY_STRING} (\+|%2b|%20)(d|%64|%44)(e|%65|%45)(l|%6c|%4c)(e|%65|%45)(t|%74|%54)(e|%65|%45)(\+|%2b|%20) [NC,OR]
	RewriteCond %{QUERY_STRING} (\+|%2b|%20)(i|%69|%49)(n|%6e|%4e)(s|%73|%53)(e|%65|%45)(r|%72|%52)(t|%74|%54)(\+|%2b|%20) [NC,OR]
	RewriteCond %{QUERY_STRING} (\+|%2b|%20)(s|%73|%53)(e|%65|%45)(l|%6c|%4c)(e|%65|%45)(c|%63|%43)(t|%74|%54)(\+|%2b|%20) [NC,OR]
	RewriteCond %{QUERY_STRING} (\+|%2b|%20)(u|%75|%55)(p|%70|%50)(d|%64|%44)(a|%61|%41)(t|%74|%54)(e|%65|%45)(\+|%2b|%20) [NC,OR]
	RewriteCond %{QUERY_STRING} (\\x00|(\"|%22|\'|%27)?0(\"|%22|\'|%27)?(=|%3d)(\"|%22|\'|%27)?0|cast(\(|%28)0x|or%201(=|%3d)1) [NC,OR]
	RewriteCond %{QUERY_STRING} (g|%67|%47)(l|%6c|%4c)(o|%6f|%4f)(b|%62|%42)(a|%61|%41)(l|%6c|%4c)(s|%73|%53)(=|\[|%[0-9A-Z]{0,2}) [NC,OR]
	RewriteCond %{QUERY_STRING} (_|%5f)(r|%72|%52)(e|%65|%45)(q|%71|%51)(u|%75|%55)(e|%65|%45)(s|%73|%53)(t|%74|%54)(=|\[|%[0-9A-Z]{2,}) [NC,OR]
	RewriteCond %{QUERY_STRING} (j|%6a|%4a)(a|%61|%41)(v|%76|%56)(a|%61|%31)(s|%73|%53)(c|%63|%43)(r|%72|%52)(i|%69|%49)(p|%70|%50)(t|%74|%54)(:|%3a)(.*)(;|%3b|\)|%29) [NC,OR]
	RewriteCond %{QUERY_STRING} (b|%62|%42)(a|%61|%41)(s|%73|%53)(e|%65|%45)(6|%36)(4|%34)(_|%5f)(e|%65|%45|d|%64|%44)(e|%65|%45|n|%6e|%4e)(c|%63|%43)(o|%6f|%4f)(d|%64|%44)(e|%65|%45)(.*)(\()(.*)(\)) [NC,OR]
	RewriteCond %{QUERY_STRING} (@copy|\$_(files|get|post)|allow_url_(fopen|include)|auto_prepend_file|blexbot|browsersploit|(c99|php)shell|curl(_exec|test)|disable_functions?|document_root|elastix|encodeuricom|exploit|fclose|fgets|file_put_contents|fputs|fsbuff|fsockopen|gethostbyname|grablogin|hmei7|input_file|null|open_basedir|outfile|passthru|phpinfo|popen|proc_open|quickbrute|remoteview|root_path|safe_mode|shell_exec|site((.){0,2})copier|sux0r|trojan|user_func_array|wget|xertive) [NC,OR]
	RewriteCond %{QUERY_STRING} (;|<|>|\'|\"|\)|%0a|%0d|%22|%27|%3c|%3e|%00)(.*)(/\*|alter|base64|benchmark|cast|concat|convert|create|encode|declare|delete|drop|insert|md5|request|script|select|set|union|update) [NC,OR]
	RewriteCond %{QUERY_STRING} ((\+|%2b)(concat|delete|get|select|union)(\+|%2b)) [NC,OR]
	RewriteCond %{QUERY_STRING} (union)(.*)(select)(.*)(\(|%28) [NC,OR]
	RewriteCond %{QUERY_STRING} (concat|eval)(.*)(\(|%28) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_QUERY_STRING:%1___%2___%3]

</IfModule>

# 7G Firewall:[REQUEST URI]
<IfModule mod_rewrite.c>

	RewriteCond %{REQUEST_URI} (\^|`|<|>|\|\|) [NC,OR]
	RewriteCond %{REQUEST_URI} ([a-z0-9]{2000,}) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(\*|\"|\'|\.|,|&|&amp;?)/?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (\.)(php)(\()?([0-9]+)(\))?(/)?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(vbulletin|boards|vbforum)(/)? [NC,OR]
	RewriteCond %{REQUEST_URI} /((.*)header:|(.*)set-cookie:(.*)=) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(ckfinder|fck|fckeditor|fullclick) [NC,OR]
	RewriteCond %{REQUEST_URI} (\.(s?ftp-?)config|(s?ftp-?)config\.) [NC,OR]
	RewriteCond %{REQUEST_URI} (\{0\}|\"?0\"?=\"?0|\(/\(|\.\.\.|\+\+\+|\\") [NC,OR]
	RewriteCond %{REQUEST_URI} (thumbs?(_editor|open)?|tim(thumbs?)?)(\.php) [NC,OR]
	RewriteCond %{REQUEST_URI} (\.|20)(get|the)(_)(permalink|posts_page_url)(\() [NC,OR]
	RewriteCond %{REQUEST_URI} (///|\?\?|/&&|/\*(.*)\*/|/:/|\\|0x00|%00|%0d%0a) [NC,OR]
	RewriteCond %{REQUEST_URI} (/%7e)(root|ftp|bin|nobody|named|guest|logs|sshd)(/) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(etc|var)(/)(hidden|secret|shadow|ninja|passwd|tmp)(/)?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (s)?(ftp|http|inurl|php)(s)?(:(/|%2f|%u2215)(/|%2f|%u2215)) [NC,OR]
	RewriteCond %{REQUEST_URI} (\.)(ds_store|htaccess|htpasswd|init?|mysql-select-db)(/)?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(bin)(/)(cc|chmod|chsh|cpp|echo|id|kill|mail|nasm|perl|ping|ps|python|tclsh)(/)?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(::[0-9999]|%3a%3a[0-9999]|127\.0\.0\.1|localhost|loopback|makefile|pingserver|wwwroot)(/)? [NC,OR]
	RewriteCond %{REQUEST_URI} (/)?j((\s)+)?a((\s)+)?v((\s)+)?a((\s)+)?s((\s)+)?c((\s)+)?r((\s)+)?i((\s)+)?p((\s)+)?t((\s)+)?(%3a|:) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(awstats|(c99|php|web)shell|document_root|error_log|listinfo|muieblack|remoteview|site((.){0,2})copier|sqlpatch|sux0r) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)((php|web)?shell|crossdomain|fileditor|locus7|nstview|php(get|remoteview|writer)|r57|remview|sshphp|storm7|webadmin)(.*)(\.|\() [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(author-panel|bitrix|class|database|(db|mysql)-?admin|filemanager|htdocs|httpdocs|https?|mailman|mailto|msoffice|mysql|_?php-my-admin(.*)|tmp|undefined|usage|var|vhosts|webmaster|www)(/) [NC,OR]
	RewriteCond %{REQUEST_URI} (base64_(en|de)code|benchmark|child_terminate|curl_exec|e?chr|eval|function|fwrite|(f|p)open|html|leak|passthru|p?fsockopen|phpinfo|posix_(kill|mkfifo|setpgid|setsid|setuid)|proc_(close|get_status|nice|open|terminate)|(shell_)?exec|system)(.*)(\()(.*)(\)) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(^$|00.temp00|0day|3index|3xp|70bex?|admin_events|bkht|(php|web)?shell|c99|config(\.)?bak|curltest|db|dompdf|filenetworks|hmei7|index\.php/index\.php/index|jahat|kcrew|keywordspy|libsoft|marg|mobiquo|mysql|nessus|php-?info|racrew|sql|vuln|(web-?|wp-)?(conf\b|config(uration)?)|xertive)(\.php) [NC,OR]
	RewriteCond %{REQUEST_URI} (\.)(7z|ab4|ace|afm|ashx|aspx?|bash|ba?k?|bin|bz2|cfg|cfml?|cgi|conf\b|config|ctl|dat|db|dist|dll|eml|engine|env|et2|exe|fec|fla|git|hg|inc|ini|inv|jsp|log|lqd|make|mbf|mdb|mmw|mny|module|old|one|orig|out|passwd|pdb|phtml|pl|profile|psd|pst|ptdb|pwd|py|qbb|qdf|rar|rdf|save|sdb|sql|sh|soa|svn|swf|swl|swo|swp|stx|tar|tax|tgz|theme|tls|tmd|wow|xtmpl|ya?ml|zlib)$ [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_REQUEST_URI:%1___%2___%3]

</IfModule>

# 7G Firewall:[USER AGENT]
<IfModule mod_rewrite.c>

	RewriteCond %{HTTP_USER_AGENT} ([a-z0-9]{2000,}) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (&lt;|%0a|%0d|%27|%3c|%3e|%00|0x00) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (ahrefs|alexibot|majestic|mj12bot|rogerbot) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} ((c99|php|web)shell|remoteview|site((.){0,2})copier) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (econtext|eolasbot|eventures|liebaofast|nominet|oppo\sa33) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (base64_decode|bin/bash|disconnect|eval|lwp-download|unserialize|\\\x22) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (acapbot|acoonbot|asterias|attackbot|backdorbot|becomebot|binlar|blackwidow|blekkobot|blexbot|blowfish|bullseye|bunnys|butterfly|careerbot|casper|checkpriv|cheesebot|cherrypick|chinaclaw|choppy|clshttp|cmsworld|copernic|copyrightcheck|cosmos|crescent|cy_cho|datacha|demon|diavol|discobot|dittospyder|dotbot|dotnetdotcom|dumbot|emailcollector|emailsiphon|emailwolf|extract|eyenetie|feedfinder|flaming|flashget|flicky|foobot|g00g1e|getright|gigabot|go-ahead-got|gozilla|grabnet|grafula|harvest|heritrix|httrack|icarus6j|jetbot|jetcar|jikespider|kmccrew|leechftp|libweb|linkextractor|linkscan|linkwalker|loader|masscan|miner|mechanize|morfeus|moveoverbot|netmechanic|netspider|nicerspro|nikto|ninja|nutch|octopus|pagegrabber|petalbot|planetwork|postrank|proximic|purebot|pycurl|python|queryn|queryseeker|radian6|radiation|realdownload|scooter|seekerspider|semalt|siclab|sindice|sistrix|sitebot|siteexplorer|sitesnagger|skygrid|smartdownload|snoopy|sosospider|spankbot|spbot|sqlmap|stackrambler|stripper|sucker|surftbot|sux0r|suzukacz|suzuran|takeout|teleport|telesoft|true_robots|turingos|turnit|vampire|vikspider|voideye|webleacher|webreaper|webstripper|webvac|webviewer|webwhacker|winhttp|wwwoffle|woxbot|xaldon|xxxyy|yamanalab|yioopbot|youda|zeus|zmeu|zune|zyborg) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_USER_AGENT:%1]

</IfModule>

# 7G Firewall:[REMOTE HOST]
<IfModule mod_rewrite.c>

	RewriteCond %{REMOTE_HOST} (163data|amazonaws|colocrossing|crimea|g00g1e|justhost|kanagawa|loopia|masterhost|onlinehome|poneytel|sprintdatacenter|reverse.softlayer|safenet|ttnet|woodpecker|wowrack) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_REMOTE_HOST:%1]

</IfModule>

# 7G Firewall:[HTTP REFERRER]
<IfModule mod_rewrite.c>

	RewriteCond %{HTTP_REFERER} (semalt.com|todaperfeita) [NC,OR]
	RewriteCond %{HTTP_REFERER} (order(\s|%20)by(\s|%20)1--) [NC,OR]
	RewriteCond %{HTTP_REFERER} (blue\spill|cocaine|ejaculat|erectile|erections|hoodia|huronriveracres|impotence|levitra|libido|lipitor|phentermin|pro[sz]ac|sandyauer|tramadol|troyhamby|ultram|unicauca|valium|viagra|vicodin|xanax|ypxaieo) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_HTTP_REFERRER:%1]

</IfModule>

# 7G Firewall:[REQUEST METHOD]
<IfModule mod_rewrite.c>

	RewriteCond %{REQUEST_METHOD} ^(connect|debug|move|trace|track) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_REQUEST_METHOD:%1]

</IfModule>
HTACCESS;
                                    echo htmlentities($htaccess);
                                ?></pre>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <div class="form-actions">
                                <input type="submit" id="save_changes" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-submit" />
                            </div>
                        </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /settings form -->
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>