$(document).ready(function(){ 
    
    if ($("#spamprot_upgrade_overlay").length > 0) {
        $("#spamprot_upgrade_overlay").fadeIn(1000, function(){
            $("#spamprot_upgrade").slideDown(1000);
        });    
    }
    
    $(document).on("click", "#spamprot_upgrade_close", function(){
        $("#spamprot_upgrade").slideUp(1000, function(){
            $("#spamprot_upgrade_overlay").fadeOut(1000);    
        });    
    });
    
    $(document).on("click", ".sp_review_close", function(){
        $("#sp_review_wrap").fadeOut("slow");  
    });
    
});