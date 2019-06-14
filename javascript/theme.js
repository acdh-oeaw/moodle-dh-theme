// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * theme.js
 *
 * @package     theme_dh
 * @copyright   2019 ACDH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

(function($) {
    var resized = false;
        
    if(window.location.pathname == "/") {
        $(window).bind('resize', function(e)
        {
          if (window.RT) clearTimeout(window.RT);
          window.RT = setTimeout(function()
          {
            this.location.reload(false); /* false to get page from cache */
          }, 100);
        });
        
        $("#wordcloud1").awesomeCloud({"size" : {
            "grid" : 8,
            "factor" : 0
        },
        "options" : {
            "color" : "random-dark",
            "rotationRatio" : 0.35,
            //"printMultiplier" : 3,
            "sort" : "random"
        },
        "font" : "arvoregular, Helvetica, serif",
        "shape" : "square"});
    }
    
    
    if(getCookie("fp-orderby")) {
        $('.fp-order-by-box').val(getCookie("fp-orderby"));
    }
    if(getCookie("fp-languages")) {
        $('.fp-languages-box').val(getCookie("fp-languages"));
    }
    if(getCookie("fp-ects")) {
        $('.fp-ects-box').val(getCookie("fp-ects"));
    }
    
    if(!getCookie("more_course_opened")) {
        //hide the course div
        $('.more_courses_div').hide();        
        //show the button
        $(".more_courses").show();
        $(".hide_courses").hide();
    }else {
        $('.more_courses_div').show();
        $(".more_courses").hide();
        $(".hide_courses").show();
    }
    
    var img = $("header#header").find('.avatars').find('img[src$="/u/f2"]');
    var src = img.attr('src');
    img.attr('src', src + '_white');
    var msg = $("header#header").find('#nav-message-popover-container .nav-link').find("img[src$='t/message']");
    var msgsrc = msg.attr('src');
    msg.attr('src', msgsrc + "_white");
    var note = $("header#header").find('#nav-notification-popover-container .nav-link').find("img[src$='i/notifications']");
    var notesrc = note.attr('src');
    note.attr('src', notesrc + "_white");

    /* ------- Check navbar button status -------- */
    if ($("#header .navbar-nav button").attr('aria-expanded') === "true") {
        $("#header .navbar-nav").find('button').addClass('is-active');
    }
    /* ------ Event for change the drawer navbar style  ------ */
    $("#header .navbar-nav button").click(function() {
        var $this = $(this);
        setTimeout(function() {
            if ($this.attr('aria-expanded') === "true") {
                $("#header .navbar-nav").find('button').addClass('is-active');
            } else {
                $("#header .navbar-nav").find('button').removeClass('is-active');
            }
        }, 200);
    });
    
    let aboutLink = "about-fp-link";
    let contactLink = "contact-fp-link";
    let workshopsLink = "workshops-fp-link";
    let coursesLink = "courses-fp-link";
    $("."+aboutLink).css("text-decoration", "none");
    $("."+contactLink).css("text-decoration", "none");
    $("."+workshopsLink).css("text-decoration", "none");
    $("."+coursesLink).css("text-decoration", "none");
    
    if(window.location.href.indexOf("/local/staticpage/view.php?page=about") >= 0 ){  
        $("."+aboutLink).css("text-decoration", "underline");
    }else if(window.location.href.indexOf("/course/index.php#courses") >= 0 ){  
        $("."+workshopsLink).css("text-decoration", "underline");
    }else if(window.location.href.indexOf("/local/simple_contact_form") >= 0 ){  
        $("."+contactLink).css("text-decoration", "underline");
    }else if(window.location.href.indexOf("/course/index.php#workshops") >= 0 ){  
        $("."+coursesLink).css("text-decoration", "underline");
    }else if(window.location.href.indexOf("/course/view.php") >= 0 ){  
        $("."+coursesLink).css("text-decoration", "underline");
    }else if(window.location.href.indexOf("/mod/") >= 0 ){  
        $("."+coursesLink).css("text-decoration", "underline");
    } 
    
    var url_string = window.location.href; //
    var url = new URL(url_string);
    var languages = url.searchParams.get("languages");    
    var orderby = url.searchParams.get("orderby");    
    var ects = url.searchParams.get("ects");    
        
    function createNewUrl(orderBy = "", languages = "", ects = ""){
        if (history.pushState) {
            var path = window.location.pathname;
            
            var values = "";
            if(orderBy) {
                values = values + "&orderby="+orderBy;
            }
            if(languages) {
                values = values + "&languages="+languages;
            }
            if(ects) {
                values = values + "&ects="+ects;
            }
            values = values.substring(1);
            
            var newurl = window.location.protocol + "//" + window.location.host  + "/index.php?" + values;
            window.history.pushState({path:newurl},'',newurl);
        }
    }
    
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    if(!orderby) {
        $('.fp-order-by-box').val("date_desc");
    }
    if(!languages) {
        $('.fp-languages-box').val("");
    }
    if(!ects) {
        $('.fp-ects-box').val("");
    }
    
    //remove the custom course title
    $('.block_course_custom_menu > .card-body h5').remove();
    
    $('.fp-order-by-box').on('change', function() {
        setCookie("fp-orderby", this.value, 100);
        createNewUrl(this.value, languages, ects);
        window.location.reload();
    });
    
    $('.fp-languages-box').on('change', function() {
        setCookie("fp-languages", this.value, 100);
        createNewUrl(orderby,this.value, ects);
        window.location.reload();
    });
    
    $('.fp-ects-box').on('change', function() {
        setCookie("fp-ects", this.value, 100);
        createNewUrl(orderby,languages,this.value);
        window.location.reload();
    });
    
    $("#show_more_course").click(function(e) {
        setCookie("more_course_opened", true, 100);
        $('.more_courses_div').show();
        $(".more_courses").hide();   
        $(".hide_courses").show();
        e.preventDefault();
    });
    
     $("#hide_more_course").click(function(e) {
        setCookie("hide_course_opened", true, 100);
         $('.more_courses_div').hide();
        $(".more_courses").show();        
        $(".hide_courses").hide();
        e.preventDefault();
    });
    
    
    $(".show_hide_frontpage_courses").click(function(e){
        var height = $("#frontpage-course-list").css("height");
        if(height > "320px") {
            $( "#frontpage-course-list" ).slideUp( "slow", function() {
                $("#frontpage-course-list").css("height", "320px");
                $("#frontpage-course-list").css("display", "block");
                $("#frontpage-course-list").css("overflow", "hidden");
            });
            $(".show_hide_frontpage_courses").text('Show All Courses');
            e.preventDefault();
        }else {
            $( "#frontpage-course-list" ).slideDown( "slow", function() {
                $("#frontpage-course-list").css("height", "100%");
                $("#frontpage-course-list").css("overflow", "visible");
            });
            $(".show_hide_frontpage_courses").text('Collapse Courses');
            e.preventDefault();
        }
        e.preventDefault();
    });
    
    

})(jQuery);

