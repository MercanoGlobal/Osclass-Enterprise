$(document).ready(function(){
    
    $("#spamprot ul.subtabs").each(function(index, element){
        var height      = $(element).closest("div.tab-content").height(),
            minheight   = $(document).height()-180;
        $(element).css({
            "minHeight": minheight,
            "height": height    
        });
    });
    
    if ($("input[name=sp_activate]").is(":checked")) {            
        $("#sp_options").removeClass("disabled").addClass("enabled").find("input, textarea");
    } if ($("input[name=sp_comment_activate]").is(":checked")) {            
        $("#sp_comment_options").removeClass("disabled").addClass("enabled").find("input, textarea");
    } if ($("input[name=sp_contact_activate]").is(":checked")) {            
        $("#sp_contact_options").removeClass("disabled").addClass("enabled").find("input, textarea");
    } if ($("input[name=sp_security_activate]").is(":checked")) {            
        $("#sp_security_mainfeatures_user").removeClass("disabled").addClass("enabled").find("input, textarea");
    } if ($("input[name=sp_admin_activate]").is(":checked")) {            
        $("#sp_security_mainfeatures_admin").removeClass("disabled").addClass("enabled").find("input, textarea");
    } if ($("input[name=sp_honeypot]").is(":checked")) {
        $("#honeypot").addClass("visible");        
    } if ($("input[name=sp_blocked]").is(":checked")) {
        $("#blocked").addClass("visible");        
    } if ($("input[name=sp_blocked_tld]").is(":checked")) {
        $("#blocked_tld").addClass("visible");        
    } if ($("input[name=sp_comment_blocked]").is(":checked")) {
        $("#comment_blocked").addClass("visible");        
    } if ($("input[name=sp_comment_blocked_tld]").is(":checked")) {
        $("#comment_blocked_tld").addClass("visible");        
    } if ($("input[name=sp_contact_blocked]").is(":checked")) {
        $("#contact_blocked").addClass("visible");        
    } if ($("input[name=sp_contact_blocked_tld]").is(":checked")) {
        $("#contact_blocked_tld").addClass("visible");        
    } if ($("input[name=sp_security_login_check]").is(":checked")) {
        $("#sp_security_login_count").addClass("visible");        
    }
    
    $(document).on("click", "input[name=sp_activate]", function(){
        if ($("input[name=sp_activate]").is(":checked")) {
            $("#sp_options").removeClass("disabled").addClass("enabled").find("input, textarea");        
        } else {
            $("#sp_options").removeClass("enabled").addClass("disabled").find("input, textarea");
            $("input[name=sp_activate]").prop("disabled", false);
        }  
    });
    
    $(document).on("click", "input[name=sp_comment_activate]", function(){
        if ($("input[name=sp_comment_activate]").is(":checked")) {
            $("#sp_comment_options").removeClass("disabled").addClass("enabled").find("input, textarea");        
        } else {
            $("#sp_comment_options").removeClass("enabled").addClass("disabled").find("input, textarea");
            $("input[name=sp_comment_activate]").prop("disabled", false);
        }  
    });
    
    $(document).on("click", "input[name=sp_contact_activate]", function(){
        if ($("input[name=sp_contact_activate]").is(":checked")) {
            $("#sp_contact_options").removeClass("disabled").addClass("enabled").find("input, textarea");        
        } else {
            $("#sp_contact_options").removeClass("enabled").addClass("disabled").find("input, textarea");
            $("input[name=sp_contact_activate]").prop("disabled", false);
        }  
    });
    
    $(document).on("click", "input[name=sp_security_activate]", function(){
        if ($("input[name=sp_security_activate]").is(":checked")) {
            $("#sp_security_mainfeatures_user").removeClass("disabled").addClass("enabled").find("input, textarea");        
        } else {
            $("#sp_security_mainfeatures_user").removeClass("enabled").addClass("disabled").find("input, textarea");
            $("input[name=sp_security_activate]").prop("disabled", false);
        }  
    });
    
    $(document).on("click", "input[name=sp_admin_activate]", function(){
        if ($("input[name=sp_admin_activate]").is(":checked")) {
            $("#sp_security_mainfeatures_admin").removeClass("disabled").addClass("enabled").find("input, textarea");        
        } else {
            $("#sp_security_mainfeatures_admin").removeClass("enabled").addClass("disabled").find("input, textarea");
            $("input[name=sp_admin_activate]").prop("disabled", false);
        }  
    });
    
    
    $(document).on("click", "input[name=sp_honeypot]", function(){
        if ($("input[name=sp_honeypot]").is(":checked")) {
            $("#honeypot").addClass("visible");        
        } else {
            $("#honeypot").removeClass("visible");
        }    
    });
    
    $(document).on("click", "input[name=sp_contact_honeypot]", function(){
        if ($("input[name=sp_contact_honeypot]").is(":checked")) {
            $("#contact_honeypot").addClass("visible");        
        } else {
            $("#contact_honeypot").removeClass("visible");
        }    
    });
    
    $(document).on("click", "input[name=sp_blocked]", function(){
        if ($("input[name=sp_blocked]").is(":checked")) {
            $("#blocked").addClass("visible");        
        } else {
            $("#blocked").removeClass("visible");
        }    
    });
    
    $(document).on("click", "input[name=sp_comment_blocked]", function(){
        if ($("input[name=sp_comment_blocked]").is(":checked")) {
            $("#comment_blocked").addClass("visible");        
        } else {
            $("#comment_blocked").removeClass("visible");
        }    
    });
    
    $(document).on("click", "input[name=sp_contact_blocked]", function(){
        if ($("input[name=sp_contact_blocked]").is(":checked")) {
            $("#contact_blocked").addClass("visible");        
        } else {
            $("#contact_blocked").removeClass("visible");
        }    
    });
    
    $(document).on("click", "input[name=sp_blocked_tld]", function(){
        if ($("input[name=sp_blocked_tld]").is(":checked")) {
            $("#blocked_tld").addClass("visible");        
        } else {
            $("#blocked_tld").removeClass("visible");
        }    
    });
    
    $(document).on("click", "input[name=sp_comment_blocked_tld]", function(){
        if ($("input[name=sp_comment_blocked_tld]").is(":checked")) {
            $("#comment_blocked_tld").addClass("visible");        
        } else {
            $("#comment_blocked_tld").removeClass("visible");
        }    
    });
    
    $(document).on("click", "input[name=sp_contact_blocked_tld]", function(){
        if ($("input[name=sp_contact_blocked_tld]").is(":checked")) {
            $("#contact_blocked_tld").addClass("visible");        
        } else {
            $("#contact_blocked_tld").removeClass("visible");
        }    
    });
    /*
    $(document).on("click", "input[name=sp_security_login_hp], input[name=sp_security_recover_hp]", function(){
        
        var login   = $("input[name=sp_security_login_hp]"),
            recover = $("input[name=sp_security_recover_hp]");
        
        if (login.is(":checked")) {
            $("#sp_security_login_honeypots, #sp_security_login_hp_cont").fadeIn("slow");        
        } else {
            if (!recover.is(":checked")) {
                $("#sp_security_login_honeypots").fadeOut("slow");
            }
            $("#sp_security_login_hp_cont").fadeOut("slow");
        }
            
        if (recover.is(":checked")) {
            $("#sp_security_login_honeypots, #sp_security_recover_hp_cont").fadeIn("slow");        
        } else {
            if (!login.is(":checked")) {
                $("#sp_security_login_honeypots").fadeOut("slow");
            }
            $("#sp_security_recover_hp_cont").fadeOut("slow");
        }    
    });
    */
    $(document).on("click", "ul.tabs li", function(){
        var tab_id = $(this).attr('data-tab');

        $("input#sp_tab").val(tab_id);
        
        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    });
    
    $(document).on("click", "ul.langtabs li", function(){
        var tab_id      = $(this).attr('data-tab'),
            addtab      = $(this).closest('form').children("input[name=tab]");
            findtab     = $(this).closest('form').children("input[name=table]");        
        
        $('ul.langtabs li').removeClass('current');
        $('.langtab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
        
        if (findtab.length < 1) {
            $('<input type="hidden" name="table" id="table" value="'+tab_id+'" />').insertAfter(addtab);
        } else {
            $(findtab).val(tab_id);    
        }            
    });
    
    $(document).on("click", "ul.subtabs li", function(){
        var tab_id      = $(this).attr('data-tab'),
            div         = $(this).closest('div'),
            currentid   = div.parent().prop('id'),
            addtab      = $(this).closest('form').children("input[name=tab]");
            findtab     = $(this).closest('form').children("input[name=sub]");
            
        $('#'+currentid+' ul.subtabs li').removeClass('current');
        $('#'+currentid+' .subtab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
        
        if (findtab.length < 1) {
            $('<input type="hidden" name="sub" id="sp_subtab" value="'+tab_id+'" />').insertAfter(addtab);
        } else {
            $(findtab).val(tab_id);    
        }
        
        var child       = div.children("div"),
            height      = child.height(),
            minheight   = screen.height;    
        
        $(this).closest("ul").css({
            "minHeight": minheight-180,
            "height": height    
        });
        
    });
    
    $(document).on("focusout", "input[name=honeypot_name]", function(){
        $(this).removeClass("valid, invalid");
        $("#validname").html("").css("color", "");    
    });
    
    $(document).on("focusout", "input[name=contact_honeypot_name]", function(){
        $(this).removeClass("valid, invalid");
        $("#contact_validname").html("").css("color", "");    
    });
    
        
    $(document).on("click", "#sp_review", function(event){
        event.preventDefault();
        $("#sp_review_wrap").fadeToggle("slow");  
    });
    
    
    $(document).on("click", ".viewToggle", function(event){
        event.preventDefault();
        
        if ($("textarea#descriptionCode").is(":visible")) {
            var icon = $(this).children("i");
            $("textarea#descriptionCode").fadeOut("slow", function(){
                $(icon).removeClass("fa-eye").addClass("fa-code");
                $("div#descriptionHTML").fadeIn("slow");
            })
        } else if ($("div#descriptionHTML").is(":visible")) {
            var icon = $(this).children("i");
            $("div#descriptionHTML").fadeOut("slow", function(){
                $(icon).removeClass("fa-code").addClass("fa-eye");
                $("textarea#descriptionCode").fadeIn("slow");
            })
        }
    });
    
    $(document).on("change", "#sp_duplicates_as", function(event){
        
        event.preventDefault();        
        var value = $(this).val(),
            type  = $("#sp_duplicate_type").val();
        
        if (value && value == '1' || value == '2') {
            
            $("#sp_duplicates_cont, #sp_duplicate_type_cont").fadeIn("slow");
            
            if (value && value == '2') {
                $("#sp_duplicates_time_cont").fadeIn("slow");        
            } else {
                $("#sp_duplicates_time_cont").fadeOut("slow");    
            }
            
            if (type && type == '1') {
                $("#sp_duplicate_percent_cont").fadeIn("slow");    
            }                
        } else {
            $("#sp_duplicates_cont, #sp_duplicate_type_cont, #sp_duplicate_percent_cont").fadeOut("slow");    
        }    
    });
    
    $(document).on("change", "#sp_duplicate_type", function(event){
        
        event.preventDefault();        
        var type = $(this).val();
        
        if (type && type == '1') {
            $("#sp_duplicate_percent_cont").fadeIn("slow");    
        } else if (type && type == '0') {
            $("#sp_duplicate_percent_cont").fadeOut("slow");    
        }    
    });
    
    $(document).on("change", "#sp_security_login_check", function(event){
        
        event.preventDefault();        
        var type = $(this).val();
        
        if (type && type == '1') {
            $("#sp_security_login_count_cont, #sp_security_login_action_cont").fadeIn("slow");    
        } else if (type && type == '0') {
            $("#sp_security_login_count_cont, #sp_security_login_action_cont").fadeOut("slow");    
        }    
    });
    
    $(document).on("change", "#sp_check_registrations", function(event){
        
        event.preventDefault();        
        var type = $(this).val();
        
        if (type && (type == '2' || type == '3')) {
            $("#sp_check_registration_mails").fadeIn("slow");    
        } else {
            $("#sp_check_registration_mails").fadeOut("slow");    
        }    
    });
    
    $(document).on("click", "input[name=sp_check_stopforumspam_mail], input[name=sp_check_stopforumspam_ip]", function(event){
        var mail    = $("input[name=sp_check_stopforumspam_mail]"),
            ip      = $("input[name=sp_check_stopforumspam_ip]");
        
        if ($(mail).is(":checked") || $(ip).is(":checked")) {            
            $("#sp_stopforumspam_settings").fadeIn("slow");                
        } else {
            $("#sp_stopforumspam_settings").fadeOut("slow");    
        }    
    });
    
    $(document).on("click", "input[name=sp_activate_menu], input[name=sp_activate_topicon]", function(event){
        var sidebar    = $("input[name=sp_activate_menu]"),
            topbar     = $("input[name=sp_activate_topicon]");
        
        if ($(sidebar).is(":checked")) {            
            $("#sp_menu_appearance_cont").fadeIn("slow");                
        } else {
            $("#sp_menu_appearance_cont").fadeOut("slow");    
        }
        
        if ($(topbar).is(":checked")) {            
            $("#sp_topicon_appearance_cont").fadeIn("slow");                
        } else {
            $("#sp_topicon_appearance_cont").fadeOut("slow");    
        }
        
        if ($(sidebar).is(":checked") || $(topbar).is(":checked")) {            
            $("#sp_activate_pulsemenu_cont").fadeIn("slow");                
        } else {
            $("#sp_activate_pulsemenu_cont").fadeOut("slow");    
        }    
    });
    
    $(document).on("click", "input[name=sp_activate_pulsemenu]", function(){
        if ($("input[name=sp_activate_pulsemenu]").is(":checked")) {
            $("ul.oscmenu li#menu_spamprotection a").addClass("pulse");        
        } else {
            $("ul.oscmenu li#menu_spamprotection a").removeClass("pulse");
        }    
    });
    
    $(document).on("click", "input[name=sp_badtrusted_activate]", function(){
        if ($("input[name=sp_badtrusted_activate]").is(":checked")) {
            $("#bot_table").fadeIn("slow");        
        } else {
            $("#bot_table").fadeOut("slow");
        }    
    });
    
    $(document).on("keyup", "input[name=searchNewTrusted]", function(event){
        $("#sp_loader").show("fast");
            
        event.preventDefault();
        
        var form    = $(this),
            val     = form.val(),
            file    = $("#search_file").val(),
            data    = form.serialize();
                    
        $.ajax({
            url: file,
            type: "post",
            data: data,
            success: function(data){                        
                $(".addBadOrTrusted #trusted-body").html(data);            
            }
        });
    });
    
    $(document).on("click", "a#add_bad_or_trusted", function(event){  
        event.preventDefault();
        $("#addBadOrTrustedUser").fadeIn("slow");
    });
    
    $(document).on("click", "a.action_bot", function(event){  
        event.preventDefault();
        
        var file = $(this).attr("href");
        
        $.get(file, function(response) {                                                    
            $(".addBadOrTrusted #trusted-body").html(response);                        
        });
    });
    
    $(document).on("change", "#sp_theme", function(event){  
        event.preventDefault();
        var val = $(this).val();
        
        $("#spamprot").prop("class", val);
    }); 
    
    $(document).on("click", "#addIpToBan", function(event){  
        event.preventDefault();
        var target  = $(this).attr("href");
        var ip      = $("#addIpBan").val();
        
        $.get(target+'&ip='+ip, function(result){
            $("tbody#dataIpBan").html(result);            
        });
    }); 
    
    $(document).on("click", ".deleteIpBan", function(event){  
        event.preventDefault();
        var target  = $(this).attr("href");
        var ip      = $(this).data("ip");
        
        $.get(target+'&ip='+ip, function(result){
            $("tbody#dataIpBan").html(result);            
        });
    }); 
    
    $(document).on("click", "#openCreateFile", function(event){  
        event.preventDefault();
        var target  = $(this).attr("href");
        
        $.get(target, function(result){
            $("#IpBanFlash").html(result);            
        });
    }); 
    
    $(document).on("click", "#createFileNow", function(event){  
        //event.preventDefault();
        var target  = $(this).attr("href");
        
        $.get(target, function(result){
            $("#IpBanFlash").html(result);            
        });
    }); 
    
    $(document).on("click", "#deleteUserAll", function(event){  
        $("input[name^=deleteUserID]").prop("checked", $(this).prop("checked"));
        countDeleteUsers();
    }); 
    
    $(document).on("click", "input[name^=deleteUserID]", function(event){  
        countDeleteUsers();
    }); 
    
    $(document).on("click", "input[name=sp_user_zeroads]", function(){  
        if (!$(this).is(":checked")) {
            if (!confirm("Are you sure you want to show accounts that have posted ads? You could delete accounts that are still in use!")) {
                event.preventDefault();    
            }
        }
    }); 
    
    $(document).on("click", "input[name=sp_user_neverlogged]", function(){  
        if (!$(this).is(":checked")) {
            if (!confirm("Are you sure you want to show accounts that have logged in? You could delete accounts that are still in use!")) {
                event.preventDefault();    
            }
        }
    });
    
    $(document).on("click", "a#searchUnwantedUser, input.sp_check_unwanted", function(event){            
        //event.preventDefault();
        
        var form    = $("#settingsUnwantedUser"),
            file    = $("#searchUnwantedUser").data("link"),
            data    = form.serialize();
                    
        $.ajax({
            url: file,
            type: "post",
            data: data,
            success: function(data){                        
                $("#printUnwantedUser").html(data);            
            }
        });
    });
    
    $(document).on("click", "a#deleteNowUnwantedAccounts", function(event){            
        event.preventDefault();
        if (confirm("You really want to delete this "+countDeleteUsers()+" user accounts? This action cannot be undone!")) {
            var form    = $("#settingsUnwantedUser"),
                file    = $(this).data("link"),
                data    = form.serialize();
                        
            $.ajax({
                url: file,
                type: "post",
                data: data,
                success: function(data){                        
                    $("#printUnwantedUser").html(data);            
                }
            });
        }
    });

    $(document).on("click change", ".logSearch, .logPagination, select[name=logLimit]", function(event){

        event.preventDefault();
        var type    = event.type,
            element = $(this).attr("name");

        if (element == "logLimit" && type == "click") {
            return true;    
        }

        var form    = $("#logDetails"),
            file    = $("input[name=logLink]").val(),
            search  = $("input[name=logSearch]").val(),
            limit   = $("select[name=logLimit]").val(),
            page    = $(this).data("page"),
            date    = $("input[name=logDate]").val(),
            data    = form.serialize()+'&logSearch='+search+'&logLimit='+limit+'&logPage='+page+'&logDate='+date;

        $.ajax({
            url: file,
            type: "post",
            data: data,
            success: function(data){
                var source = $('<div>' + data + '</div>');            
                table = source.find('#logTable').html();            
                pagin = source.find('#logPagination').html();            
                pages = source.find('#logPages').html();            
                $("#logTable").html(table);            
                $("#logPagination").html(pagin);            
                $("#logPages").html(pages);            
            }
        });
    });

    $(document).on("click", "#honeypotInfo", function(event){
        event.preventDefault();
        $("#sp_security_login_honeypots").slideToggle("slow");
    });    

});

function countDeleteUsers() {
    var i = 0;
    $("input[name^=deleteUserID]").each(function(index, element){
        if ($(element).is(":checked")) {
            i++;    
        }
    });
    $("#markedAccounts").html(i);
    return i;   
}