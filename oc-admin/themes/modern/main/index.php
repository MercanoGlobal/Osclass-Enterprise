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

    $numItemsPerCategory = __get('numItemsPerCategory');
    $numItems            = __get('numItems');
    $numUsers            = __get('numUsers');
    $numComments         = __get('numComments');

    osc_enqueue_script('fancybox');
    osc_enqueue_style('fancybox', osc_assets_url('js/fancybox/jquery.fancybox.css'));

    osc_add_filter('render-wrapper','render_offset');
    function render_offset() {
        return 'row-offset';
    }

    osc_add_filter('admin_body_class','addBodyClass');
    if(!function_exists('addBodyClass')) {
        function addBodyClass($array) {
            $array[] = 'dashboard';
            return $array;
        }
    }

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader() { ?>
        <h1><?php _e('Dashboard'); ?></h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Dashboard &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    function customHead() {
        $items    = __get('item_stats');
        $users    = __get('user_stats');
        $comments = __get('comment_stats');
        ?>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.load('visualization', '1', {'packages':['corechart']});
            google.setOnLoadCallback(drawChartListing);
            google.setOnLoadCallback(drawChartUser);
            google.setOnLoadCallback(drawChartComment);

            function drawChartListing() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', '<?php _e('Date'); ?>');
                data.addColumn('number', '<?php _e('Listings'); ?>');
                data.addColumn({type:'boolean',role:'certainty'});
                <?php $k = 0;
                echo "data.addRows(" . count($items) . ");";
                foreach($items as $date => $num) {
                    echo "data.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }
                $k = 0;
                ?>

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('placeholder-listing'));
                chart.draw(data, {
                    colors:['#058dc7','#e6f4fa'],
                        areaOpacity: 0.1,
                        lineWidth:3,
                        hAxis: {
                        gridlines: {
                            color: '#333',
                            count: 3
                        },
                        viewWindow:'explicit',
                        showTextEvery: 2,
                        slantedText: false,
                        textStyle: {
                            color: '#058dc7',
                            fontSize: 10
                        }
                        },
                        vAxis: {
                            gridlines: {
                                color: '#DDD',
                                count: 4,
                                style: 'dooted'
                            },
                            viewWindow:'explicit',
                            baselineColor:'#bababa'
                        },
                        pointSize: 6,
                        legend: 'none',
                        chartArea: {
                            left:10,
                            top:10,
                            width:"95%",
                            height:"88%"
                        }
                    });
            }

            function drawChartUser() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', '<?php _e('Date'); ?>');
                data.addColumn('number', '<?php _e('Users'); ?>');
                data.addColumn({type:'boolean',role:'certainty'});
                <?php $k = 0;
                echo "data.addRows(" . count($users) . ");";
                foreach($users as $date => $num) {
                    echo "data.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }
                $k = 0;
                ?>

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('placeholder-user'));
                chart.draw(data, {
                    colors:['#058dc7','#e6f4fa'],
                    areaOpacity: 0.1,
                    lineWidth:3,
                    hAxis: {
                    gridlines: {
                        color: '#333',
                        count: 3
                    },
                    viewWindow:'explicit',
                    showTextEvery: 2,
                    slantedText: false,
                    textStyle: {
                        color: '#058dc7',
                        fontSize: 10
                    }
                    },
                    vAxis: {
                        gridlines: {
                            color: '#DDD',
                            count: 4,
                            style: 'dooted'
                        },
                        viewWindow:'explicit',
                        baselineColor:'#bababa'
                    },
                    pointSize: 6,
                    legend: 'none',
                    chartArea: {
                        left:10,
                        top:10,
                        width:"95%",
                        height:"88%"
                    }
                });
            }

            function drawChartComment() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', '<?php echo osc_esc_js(__('Date')); ?>');
                data.addColumn('number', '<?php echo osc_esc_js(__('Comments')); ?>');
                <?php $k = 0;
                echo "data.addRows(" . count($comments) . ");";
                foreach($comments as $date => $num) {
                    echo "data.setValue(" . $k . ", 0, \"" . $date . "\");";
                    echo "data.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }
                ?>

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('placeholder-comment'));
                chart.draw(data, {
                    colors:['#058dc7','#e6f4fa'],
                    areaOpacity: 0.1,
                    lineWidth:3,
                    hAxis: {
                    gridlines: {
                        color: '#333',
                        count: 3
                    },
                    viewWindow:'explicit',
                    showTextEvery: 2,
                    slantedText: false,
                    textStyle: {
                        color: '#058dc7',
                        fontSize: 10
                    }
                    },
                    vAxis: {
                        gridlines: {
                            color: '#DDD',
                            count: 4,
                            style: 'dooted'
                        },
                        viewWindow:'explicit',
                        baselineColor:'#bababa'
                    },
                    pointSize: 6,
                    legend: 'none',
                    chartArea: {
                        left:10,
                        top:10,
                        width:"95%",
                        height:"88%"
                    }
                });
            }

            $(document).ready(function() {
                $("#widget-box-stats-select").bind('change', function () {
                    if( $(this).val() == 'users' ) {
                        $('#widget-box-stats-listings').css('visibility', 'hidden');
                        $('#widget-box-stats-users').css('visibility', 'visible');
                        $('#widget-box-stats-comments').css('visibility', 'hidden');
                    } if( $(this).val() == 'listing' ) {
                        $('#widget-box-stats-comments').css('visibility', 'hidden');
                        $('#widget-box-stats-users').css('visibility', 'hidden');
                        $('#widget-box-stats-listings').css('visibility', 'visible');
                    } if( $(this).val() == 'comment' ) {
                        $('#widget-box-stats-listings').css('visibility', 'hidden');
                        $('#widget-box-stats-users').css('visibility', 'hidden');
                        $('#widget-box-stats-comments').css('visibility', 'visible');
                    }
                });
            });
        </script>
<?php
    }
    osc_add_hook('admin_header', 'customHead', 10);

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="dashboard">
<div class="grid-system">
    <div class="grid-row grid-first-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Listings by category'); ?></h3></div>
                <div class="widget-box-content">
                    <?php
                    $countEvent = 1;
                    if( !empty($numItemsPerCategory) ) { ?>
                    <table class="table" cellpadding="0" cellspacing="0">
                        <tbody>
                        <?php
                        $even = false;
                        foreach($numItemsPerCategory as $c) { ?>
                            <tr<?php if($even == true) { $even = false; echo ' class="even"'; } else { $even = true; } if($countEvent == 1) { echo ' class="table-first-row"'; } ?>>
                                <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_name']; ?></a></td>
                                <td><?php echo $c['i_num_items'] . "&nbsp;" . ( ( $c['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ); ?></td>
                            </tr>
                            <?php foreach($c['categories'] as $subc) { ?>
                                <tr<?php if($even == true){ $even = false; echo ' class="even"'; } else { $even = true; } ?>>
                                    <td class="children-cat"><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $subc['pk_i_id'];?>"><?php echo $subc['s_name']; ?></a></td>
                                    <td><?php echo $subc['i_num_items'] . " " . ( ( $subc['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ); ?></td>
                                </tr>
                            <?php
                            $countEvent++;
                            }
                            ?>
                        <?php
                        $countEvent++;
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <?php _e("There aren't any uploaded listings yet"); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Statistics'); ?> <select id="widget-box-stats-select" class="widget-box-selector select-box-big input-medium"><option value="listing"><?php _e('New listings'); ?></option><option value="users"><?php _e('New users'); ?></option><option value="comment"><?php _e('New comments'); ?></option></select></h3></div>
                <div class="widget-box-content widget-box-content-stats" style="overflow-y: visible;">
                    <div id="widget-box-stats-listings" class="widget-box-stats">
                        <b class="stats-title"><?php _e('New listings'); ?></b>
                        <div class="stats-detail"><?php printf(__('Total number of listings: %s'), $numItems); ?></div>
                        <div id="placeholder-listing" class="graph-placeholder"></div>
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items" class="btn"><?php _e('Listing statistics'); ?></a>
                    </div>
                    <div id="widget-box-stats-users" class="widget-box-stats" style="visibility: hidden;">
                        <b class="stats-title"><?php _e('New users'); ?></b>
                        <div class="stats-detail"><?php printf(__('Total number of users: %s'), $numUsers); ?></div>
                        <div id="placeholder-user" class="graph-placeholder"></div>
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=users" class="btn"><?php _e('User statistics'); ?></a>
                    </div>
                    <div id="widget-box-stats-comments" class="widget-box-stats" style="visibility: hidden;">
                        <b class="stats-title"><?php _e('New comments'); ?></b>
                        <div class="stats-detail"><?php printf(__('Total number of comments: %s'), $numComments); ?></div>
                        <div id="placeholder-comment" class="graph-placeholder"></div>
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=comments" class="btn"><?php _e('Comments statistics'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-33">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><span><?php _e('Listings activity'); ?></span></h3></div>
                <div class="widget-box-content">
                <?php
                  $items_last_day = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_item WHERE dt_pub_date >= "%s"', DB_TABLE_PREFIX, date('Y-m-d H:i:s', strtotime('- 1 day')))); 
                  $items_last_week = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_item WHERE dt_pub_date >= "%s"', DB_TABLE_PREFIX, date('Y-m-d H:i:s', strtotime('- 7 day')))); 
                  $items_all = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_item', DB_TABLE_PREFIX)); 
                  $items_pending = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_item WHERE b_active = 1 and b_spam = 0 and b_enabled = 0', DB_TABLE_PREFIX));
                ?>

                <div class="row st">
                  <?php echo sprintf(__('Published in last 24 hours: %s'), '<strong>' . $items_last_day . '</strong>'); ?>
                </div>

                <div class="row st">
                  <?php echo sprintf(__('Published in last 7 days: %s'), '<strong>' . $items_last_week . '</strong>'); ?>
                </div>

                <div class="row st">
                  <?php echo sprintf(__('Overall published: %s'), '<strong>' . $items_all . '</strong>'); ?>
                </div>

                <div class="row st">
                  <a href="<?php echo osc_admin_base_url(true); ?>?page=items&iDisplayLength=10&sort=date&direction=desc&sSearch=&catId=&countryName=&countryId=&region=&regionId=&city=&cityId=&user=&userId=&b_premium=&b_active=&b_enabled=0&b_spam="><?php echo sprintf(__('Pending validation: %s'), '<strong>' . $items_pending . '</strong>'); ?></a>
                </div>

                <div class="row"></div>

                <h4><?php _e('Recently published'); ?></h4>

                <?php
                  $mSearch = new Search();
                  $mSearch->addConditions('1=1 OR 1=1');
                  $mSearch->order('dt_pub_date', 'DESC');
                  $mSearch->limit(0, 6);
                  $items = $mSearch->doSearch(); 
                ?>

                <?php if(count($items) <= 0) { ?>
                  <div class="empty"><?php _e('No listings have been found'); ?></div>
                <?php } else { ?>
                  <?php foreach($items as $i) { ?>
                    <div class="row">
                      <?php 
                        if($i['b_active'] == 1 && $i['b_enabled'] == 1 && $i['b_spam'] == 0) {
                          $title = __('Active');
                          $class = 'active';
                        } else if($i['b_active'] == 0) {
                          $title = __('Not validated');
                          $class = 'inactive';
                        } else if($i['b_spam'] == 1) {
                          $title = __('Spam');
                          $class = 'spam';
                        } else if($i['b_enabled'] == 0) {
                          $title = __('Blocked');
                          $class = 'blocked';
                        }
                      ?>

                      <span class="date"><?php echo osc_format_date($i['dt_pub_date'], 'd. M, H:i'); ?></span>
                      <a href="<?php echo osc_admin_base_url(true); ?>?page=items&action=item_edit&id=<?php echo $i['pk_i_id']; ?>">
                        <i class="fa fa-circle <?php echo $class; ?>" title="<?php echo osc_esc_html($title); ?>"></i>
                        <span title="<?php echo $i['s_title']; ?>"><?php echo osc_highlight($i['s_title'], 12); ?></span>
                      </a>
                    </div>
                  <?php } ?>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-33">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><span><?php _e('Users activity'); ?></span></h3></div>
                <div class="widget-box-content">
                <?php
                  $users = osc_get_query_results(sprintf('SELECT * FROM %st_user ORDER BY dt_reg_date DESC LIMIT 0, 7', DB_TABLE_PREFIX)); 
                  $users_last_day = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_user WHERE dt_reg_date >= "%s"', DB_TABLE_PREFIX, date('Y-m-d H:i:s', strtotime('- 1 day')))); 
                  $users_last_week = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_user WHERE dt_reg_date >= "%s"', DB_TABLE_PREFIX, date('Y-m-d H:i:s', strtotime('- 7 day')))); 
                  $users_all = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_user', DB_TABLE_PREFIX)); 
                ?>

                <div class="row st">
                  <?php echo sprintf(__('Registered in last 24 hours: %s'), '<strong>' . $users_last_day . '</strong>'); ?>
                </div>

                <div class="row st">
                  <?php echo sprintf(__('Registered in last 7 days: %s'), '<strong>' . $users_last_week . '</strong>'); ?>
                </div>

                <div class="row st">
                  <?php echo sprintf(__('Overall registered: %s'), '<strong>' . $users_all . '</strong>'); ?>
                </div>

                <div class="row"></div>

                <h4><?php _e('Recently registered'); ?></h4>

                <?php if(count($users) <= 0) { ?>
                  <div class="empty"><?php _e('No users have been found'); ?></div>
                <?php } else { ?>
                  <?php foreach($users as $u) { ?>
                    <div class="row">
                      <?php 
                        if($u['b_active'] == 1 && $u['b_enabled'] == 1) {
                          $title = __('Active');
                          $class = 'active';
                        } else if($u['b_active'] == 0) {
                          $title = __('Not validated');
                          $class = 'inactive';
                        } else if($u['b_enabled'] == 0) {
                          $title = __('Blocked');
                          $class = 'blocked';
                        }
                      ?>

                      <span class="date"><?php echo osc_format_date($u['dt_reg_date'], 'd. M, H:i'); ?></span>
                      <a href="<?php echo osc_admin_base_url(true); ?>?page=users&action=edit&id=<?php echo $u['pk_i_id']; ?>">
                        <i class="fa fa-circle <?php echo $class; ?>" title="<?php echo osc_esc_html($title); ?>"></i>
                        <span title="<?php echo $u['s_name']; ?>"><?php echo osc_highlight($u['s_name'], 12); ?></span>
                      </a>
                    </div>
                  <?php } ?>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-33">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><span><?php _e('Comments activity'); ?></span></h3></div>
                <div class="widget-box-content">
                <?php
                  $comments = osc_get_query_results(sprintf('SELECT * FROM %st_item_comment ORDER BY dt_pub_date DESC LIMIT 0, 6', DB_TABLE_PREFIX)); 
                  $comments_last_day = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_item_comment WHERE dt_pub_date >= "%s"', DB_TABLE_PREFIX, date('Y-m-d H:i:s', strtotime('- 1 day')))); 
                  $comments_last_week = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_item_comment WHERE dt_pub_date >= "%s"', DB_TABLE_PREFIX, date('Y-m-d H:i:s', strtotime('- 7 day')))); 
                  $comments_all = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_item_comment', DB_TABLE_PREFIX)); 
                  $comments_pending = osc_get_count_query_data(sprintf('SELECT count(*) FROM %st_item_comment WHERE b_active = 0 and b_spam = 0 and b_enabled = 1', DB_TABLE_PREFIX)); 
                ?>

                <div class="row st">
                  <?php echo sprintf(__('Published in last 24 hours: %s'), '<strong>' . $comments_last_day . '</strong>'); ?>
                </div>

                <div class="row st">
                  <?php echo sprintf(__('Published in last 7 days: %s'), '<strong>' . $comments_last_week . '</strong>'); ?>
                </div>

                <div class="row st">
                  <?php echo sprintf(__('Overall published: %s'), '<strong>' . $comments_all . '</strong>'); ?>
                </div>

                <div class="row st">
                  <a href="<?php echo osc_admin_base_url(true); ?>?page=comments"><?php echo sprintf(__('Pending validation: %s'), '<strong>' . $comments_pending . '</strong>'); ?></a>
                </div>

                <div class="row"></div>

                <h4><?php _e('Recently published'); ?></h4>

                <?php if(count($comments) <= 0) { ?>
                  <div class="empty"><?php _e('No comments have been found'); ?></div>
                <?php } else { ?>
                  <?php foreach($comments as $c) { ?>
                    <div class="row">
                      <?php 
                        if($c['b_active'] == 1 && $c['b_enabled'] == 1 && $c['b_spam'] == 0) {
                          $title = __('Active');
                          $class = 'active';
                        } else if($c['b_active'] == 0) {
                          $title = __('Not validated');
                          $class = 'inactive';
                        } else if($c['b_spam'] == 1) {
                          $title = __('Spam');
                          $class = 'spam';
                        } else if($c['b_enabled'] == 0) {
                          $title = __('Blocked');
                          $class = 'blocked';
                        }
                      ?>

                      <span class="date"><?php echo osc_format_date($c['dt_pub_date'], 'd. M, H:i'); ?></span>
                      <a href="<?php echo osc_admin_base_url(true); ?>?page=comments&action=comment_edit&id=<?php echo $c['pk_i_id']; ?>">
                        <i class="fa fa-circle <?php echo $class; ?>" title="<?php echo osc_esc_html($title); ?>"></i>
                        <span title="<?php echo $c['s_title']; ?>"><?php echo ($c['s_title'] == '' ? __('Comment #' . $c['pk_i_id']) : osc_highlight($c['s_title'], 12)); ?></span>
                      </a>
                    </div>
                  <?php } ?>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
    <?php osc_run_hook('main_dashboard'); ?>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>
