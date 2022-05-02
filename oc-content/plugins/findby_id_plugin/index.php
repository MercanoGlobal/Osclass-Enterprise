<?php
/*
  Plugin Name: Find By ID Plugin
  Plugin URI: http://www.drizzlethemes.com/
  Description: This plugin will enable users to find an item by ID.
  Version: 1.0.1
  Author: DrizzleThemes
  Author URI: https://github.com/drizzlethemes
  Short Name: findby_id_plugin
  Plugin update URI: findby-id-plugin
 */

function dd_findbyid($params) { 
    if(isset($params['sKeyword'])) {
        $mSearch =  Search::newInstance();
        if(is_numeric($params['sKeyword'])) {
            $mSearch->addItemConditions(sprintf("%st_item.pk_i_id = %s ", DB_TABLE_PREFIX, $params['sKeyword']));
        } else {
            $mSearch->addPattern($params['sKeyword']);
        }
    }
}

function dd_fbi_script() { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=sPattern]').attr('name', 'sKeyword');
        <?php if (Params::getParam('sKeyword')) { ?>
            $('input[name=sKeyword]').val('<?php echo osc_esc_js(Params::getParam('sKeyword'));?>');
        <?php } ?>
    });
</script>
<?php }

osc_add_hook('footer', 'dd_fbi_script');
osc_add_hook('search_conditions', 'dd_findbyid');
?>