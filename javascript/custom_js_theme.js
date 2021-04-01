$(window).on('load resize', function () {
if (window.matchMedia('(min-width: 980px)').matches) {
$('.navbar .dropdown').hover(function() {
	$(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
}, function() {
	$(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();
});
} else {$('.dropdown-menu').removeAttr("style"); $('.navbar .dropdown').unbind('mouseenter mouseleave');}
});
(function($) {
    $(document).ready(function() {
    
        //if the user is student then we need to hide the h5p from the course lists, because we are using them only inside the content
        if($('#student-rights').val() == "student") {
            $('.content li.activity.hvp.modtype_hvp').hide();
            //also remove the content from the left menu
            var valuesArr = $('p.tree_item a');
            $.each( valuesArr, function( key, value ) {
                var hrefVal = value.href;
                if(hrefVal.indexOf("/mod/hvp/view") >= 0) {
                    //console.log('#'+value.id);
                    $('#'+value.id).hide();
                }
            });
        }

        var offset = 220;
        var duration = 500;
        $(window).scroll(function() {
            if ($(this).scrollTop() > offset) {
                $('.back-to-top').fadeIn(duration);
            } else {
                $('.back-to-top').fadeOut(duration);
            }
        });
        $('.back-to-top').click(function(event) {
            event.preventDefault();
            $('html, body').animate({scrollTop: 0}, duration);
            return false;
        });

        $(document).on('click', 'button[data-toggle="dropdown"]', function(event) {
            event.preventDefault();
            $(this).next('.dropdown-menu').slideToggle("fast");
        });

        $(document).on('click', 'a[data-toggle="dropdown"]', function(event) {
            event.preventDefault();
            $(this).next('.dropdown-menu').slideToggle("fast");
        });

        $(document).on('click', function (e) {
            if(!$('button[data-toggle="dropdown"]').is(e.target) && !$('button[data-toggle="dropdown"]').has(e.target).length && !$('a[data-toggle="dropdown"]').is(e.target) && !$('a[data-toggle="dropdown"]').has(e.target).length && !$(".atto_hasmenu").is(e.target)){
                $('.dropdown .dropdown-menu:not(.lambda-login)').slideUp("fast");
            }                       
        });

        if (window.location.href.indexOf("/local/") > -1) {
            $('#block-region-side-pre').hide();
            $('#region-main').removeClass('span8').addClass('span12');
        }


    });
})(jQuery);
var togglesidebar = function() {
  var sidebar_open = Y.one('body').hasClass('sidebar-open');
  if (sidebar_open) {
    Y.one('body').removeClass('sidebar-open');
    M.util.set_user_preference('theme_lambda_sidebar', 'sidebar-closed');
  } else {
    Y.one('body').addClass('sidebar-open');
    M.util.set_user_preference('theme_lambda_sidebar', 'sidebar-open');
  }
};

M.theme_lambda = M.theme_lambda || {};
M.theme_lambda.sidebar =  {
  init: function() {
    Y.one('body').delegate('click', togglesidebar, '#sidebar-btn');
  }
};