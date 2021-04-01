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
    var windowurl = window.location.href;    
    
    if(getCookie("fp-orderby")) {
        $('.fp-order-by-box').val(getCookie("fp-orderby"));
    }
    if(getCookie("fp-languages")) {
        $('.fp-languages-box').val(getCookie("fp-languages"));
    }
    if(getCookie("fp-ects")) {
        $('.fp-ects-box').val(getCookie("fp-ects"));
    }
    if(getCookie("fp-orderby-en")) {
        $('.fp-order-by-box-en').val(getCookie("fp-orderby-en"));
    }
    
    if(getCookie("fp-ects-en")) {
        $('.fp-ects-box-en').val(getCookie("fp-ects-en"));
    }
    
    if(!getCookie("more_course_opened")) {
        //hide the course div
        $("#frontpage-course-list").css("height", "270px");
        $("#frontpage-course-list").css("overflow", "hidden");
        //show the button
        $(".show_frontpage_courses").show();
        $(".hide_frontpage_courses").hide();
    }else {
        $("#frontpage-course-list").css("height", "100%");
        $("#frontpage-course-list").css("overflow", "visible");
        $(".show_frontpage_courses").hide();
        $(".hide_frontpage_courses").show();
    }
    
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
    var orderby_en = url.searchParams.get("orderby_en");    
    var ects_en = url.searchParams.get("ects_en");    
    
    function createNewUrl(orderBy = "", languages = "", ects = "", lang = ""){
        
        if (history.pushState) {
            var path = window.location.pathname;
            
            var values = "";
            if(lang == 'en') {
                if(orderBy) {
                values = values + "&orderby_en="+orderBy;
                }
                if(ects) {
                    values = values + "&ects_en="+ects;
                }
            }else{
               if(orderBy) {
                values = values + "&orderby="+orderBy;
                }
                if(languages) {
                    values = values + "&languages="+languages;
                }
                if(ects) {
                    values = values + "&ects="+ects;
                } 
            }
            
            values = values.substring(1);
            if(window.location.host == "clarin.oeaw.ac.at") {
                var newurl = window.location.protocol + "//" + window.location.host  + "/moodle-dev/index.php?" + values;
            }else {
                var newurl = window.location.protocol + "//" + window.location.host  + "/index.php?" + values;
            }
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
    }else {
      $('.fp-languages-box').val(languages);  
    }
    if(!ects) {
        $('.fp-ects-box').val("");
    }
    if(!orderby_en) {
        $('.fp-order-by-box-en').val("date_desc");
    }
    if(!ects_en) {
        $('.fp-ects-box-en').val("");
    }
    
    //remove the custom course title
    $('.block_course_custom_menu > .card-body h5').remove();
    
    $('.fp-order-by-box').on('change', function() {
        setCookie("fp-orderby", this.value, 100);
        createNewUrl(this.value, languages, ects);
        window.location.reload();
    });
    
    $('.fp-order-by-box-en').on('change', function() {
        setCookie("fp-orderby-en", this.value, 100);
        createNewUrl(this.value, languages, ects, 'en');
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
    $('.fp-ects-box-en').on('change', function() {
        setCookie("fp-ects-en", this.value, 100);
        createNewUrl(orderby,languages,this.value, 'en');
        window.location.reload();
    });
    
    $(".show_frontpage_courses").click(function(e) {
        setCookie("more_course_opened", true, 100);
        $("#frontpage-course-list").css("height", "100%");
            $("#frontpage-course-list").css("overflow", "visible");
        $(".show_frontpage_courses").hide();
        $(".hide_frontpage_courses").show();
        e.preventDefault();
    });
    
     $(".hide_frontpage_courses").click(function(e) {
        setCookie("hide_course_opened", true, 100);
         $("#frontpage-course-list").css("height", "270px");
            $("#frontpage-course-list").css("overflow", "hidden");
         $(".show_frontpage_courses").show();
        $(".hide_frontpage_courses").hide();
        
        e.preventDefault();
    });
    

})(jQuery);

