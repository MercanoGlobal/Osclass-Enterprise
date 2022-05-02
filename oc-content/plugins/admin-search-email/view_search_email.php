<div id="search_email">
    <p><?php _e('Type an email address and find <b>alerts, users and listings</b> that contain this address.', 'admin_search_email'); ?></p>
    <input id="input_s_email" type="text" value="" name="s_email"/>
    <a href="#" class="btn" style="float:none!important;position: relative;top: 5px;" id="button_search_email"><?php _e('Search email', 'admin_search_email'); ?></a>
</div>

<div style="clear: both;"></div>

<div id="results" style="padding-top: 30px;">
</div>

<script>
    $("#input_s_email, #button_search_email").keyup(function (e) {
        if (e.keyCode == 13) {
            ajax_search_email();
        }
    });
    $("#button_search_email").click(function(){
        ajax_search_email();
    });

    function ajax_search_email() {
        $.ajax({
            url: '<?php echo osc_admin_base_url(true) .'?page=ajax&action=runhook&hook=search_email'; ?>&s_email='+$('#input_s_email').prop('value'),
            success:function(result){
                $("#results").html(result);
            }
        });
    }
</script>