(function($) {

    // the page accordion jquery settings
    $("#accordion_dh div").first().css('display', 'block');

    // Get all the links.
    var link = $("#accordion_dh a");

    // On clicking of the links do something.
    link.on('click', function(e) {

        if($(this).attr("class") !== "first"){
            $("#accordion_dh a.first").css('background-color', 'white');
            $("#accordion_dh a.first").css('color', '#016771');                                   
        }else{
            $("#accordion_dh a.first").css('background-color', '#016771');
            $("#accordion_dh a.first").css('color', 'white');                                   
        }

        $("#accordion_dh a").removeClass('active');
        e.preventDefault();
        var a = $(this).attr("href");
        $(this).addClass('active');
        $(a).slideDown('fast');
        //$(a).slideToggle('fast');
        $("#accordion_dh div").not(a).slideUp('fast');    
    });
    
    $(".side_menu_btn").on('click', function(e) {
        
        if($('#block-region-side-pre').is(':visible')) {
             $(".block-region-side-pre").animate({
                width: 0
            });
        }else {
            $(".block-region-side-pre").show();
             $(".block-region-side-pre").animate({
                width: 100
            });
        }
        
        
    });
    
    

})(jQuery);            