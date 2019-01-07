
         
$(document).mouseup(function (e) {
    var container = new Array();
    container.push($('#mySidenav'));

    $.each(container, function(key, value) {
        if (!$(value).is(e.target) // if the target of the click isn't the container...
            && $(value).has(e.target).length === 0) {
            $(value).css("width", "0");
            $('body').css("background-color","white");
        }
    });
});

/***  READY START  ****/
$(document).ready(function(){

    /* the lesson content boxes */
   $(".box.view_pages_box tbody").hide();
   $(".box.view_pages_box thead").click(function(){
       $(".box.view_pages_box tbody").hide();
       $('.generaltable.boxaligncenter thead th').css('color', '#016771');
       $('.generaltable.boxaligncenter thead th').css('background-color', 'white');
       var id = $(this).closest('table').attr('id')                
       $("#"+id+" tbody").show();
       $("#"+id+".generaltable.boxaligncenter thead th").css('color', 'white');
       $("#"+id+".generaltable.boxaligncenter thead th").css('background-color', '#016771');
   }); 

    //get parameter from the url
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
            return null;
        }
        else{
            return results[1] || 0;
        }
    }
    /* 
     * The block menu JQuery commands
     * hide all menupoint in the first load */

    var siteURL = $(location).attr('href');
    var currentCourse = $('#course_id').val();
    //f.e oeaw_cmc_26-3

    //var opened = $.cookie("openContentID");            
    var opened = readCookie("openContentID");
    //var openRootID = $.cookie("openRootID");            
    var openRootID = readCookie("openRootID");            
    //var selectedLessonContentRoot =  $.cookie("menu_root_element");            
    var selectedLessonContentRoot = readCookie("menu_root_element");
    //var cookieRootID = $.cookie("rootID");            
    var cookieRootID = readCookie("rootID");

    // if the first course page loads in then we
    // opens all of the left menu elements
    if (siteURL.indexOf("course/view.php") <= 0){
        //we are not in the course view, f.e. we are in the lesson
        $(".oeaw_custom_menu_content").hide();

        if(selectedLessonContentRoot && opened){
            if(opened !== selectedLessonContentRoot){
                $("#"+opened).show();
                var actualOpenedRoot = opened.replace('oeaw_cmc_', '#oeaw_custom_menu_root_');
                $(".oeaw_custom_menu_root").removeClass('active');
                $(actualOpenedRoot).addClass('active');
            } else {
                $("#"+selectedLessonContentRoot).show();
                var actualOpenedRoot = selectedLessonContentRoot.replace('oeaw_cmc_', '#oeaw_custom_menu_root_');
                $(".oeaw_custom_menu_root").removeClass('active');
                $(actualOpenedRoot).addClass('active');
            }
        }
        $(".oeaw_custom_menu_content_row a").click(function(){
            var lessonID = $(this).attr('id');
            var myRoot = $(this).parent().parent().parent().parent();
            createCookie("menu_root_element", myRoot.attr('id'), 20);
        });
    }else {
        //we are in the course view
        if($.urlParam('section') !== null){
            $(".oeaw_custom_menu_content").hide();
            var openContent = "oeaw_cmc_"+currentCourse+"-"+$.urlParam('section');
            var openRoot = "#oeaw_custom_menu_root_"+currentCourse+"-"+$.urlParam('section');
            createCookie("openContentID", openContent,20);
            createCookie("openRootID", openRoot, 20);
            $("#"+openContent).show();
            $(".oeaw_custom_menu_root").removeClass('active');
            $("#oeaw_custom_menu_root_"+currentCourse+"-"+$.urlParam('section')).addClass('active');
        }else {
            $(".oeaw_custom_menu_content").show();
        }
        $(".oeaw_custom_menu_content_row a").click(function(){
            var lessonID = $(this).attr('id');
            var myRoot = $(this).parent().parent().parent().parent();
            createCookie("menu_root_element", myRoot.attr('id') , 20);
            createCookie("openContentID", myRoot.attr('id') , 20);
        });
        // item clicked on the content area
       $(".activityinstance a").click(function(){
           if($.urlParam('section') === null){
               var section = 0;
           }else {
               var section = $.urlParam('section');
           }
           $(".oeaw_custom_menu_content").hide();
           var openContent = "oeaw_cmc_"+currentCourse+"-"+section;
           var openRoot = "#oeaw_custom_menu_root_"+currentCourse+"-"+section;
           createCookie("openContentID", openContent, 20);
           createCookie("openRootID", openRoot, 20);
           $("#"+openContent).show();
           $(".oeaw_custom_menu_root").removeClass('active');
           $("#oeaw_custom_menu_root_"+currentCourse+"-"+section).addClass('active');
       });
    }
    //if the user double clicked the header then hide the content
    $(".oeaw_custom_menu_root_header a").dblclick(function(){
        $(".oeaw_custom_menu_content").hide();
    });


    theme_custom_js();

});

/***  READY END  ****/


 //hide all menupoint in the first load
//$(".block_course_custom_menu").hide();
$(".fp-coursebox .readmore").hide();
 /* the world cloud settings */
$("#wordcloud1").awesomeCloud({
    "size" : {
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
    "shape" : "square"
});    

$(document).on('click','div.singlebutton input:submit',function(e) {      
    //quiz, where we dont have the necessary class
    let formId = $(this).closest("form").attr('id');
    if (formId.indexOf("StepButton") >= 0) {
        var actualPageID = $('#'+formId).find('input[name="pageid"]').val();
        var id = $('#'+formId).find('input[name="id"]').val();
        var sesskey = $('#'+formId).find('input[name="sesskey"]').val();
        var jumpToStepVal = $('#'+formId).find('input[name="jumpto"]').val();
        var url = $('#'+formId).attr('action');
        //create a formdata based on the pressed button hidden values
        var formData = {
                'id' : id, 
                'pageid' : actualPageID, 
                'sesskey': sesskey, 
                'stepButton' : true,
                'jumptoStep' : jumpToStepVal
        }; 

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: 'JSON',
            success: function (data) {
                $('#lesson-'+ data.nextstepid +' tbody').show();
                $('#lesson-'+ data.nextstepid +' thead th.header').css("background-color", "#016771");
                $('#lesson-'+ data.nextstepid +' thead th.header').css("color", "white");
                $('#lesson-'+ actualPageID +' tbody').hide();
                $('#lesson-'+ actualPageID +' thead tr th').css("background-color", "white");
                $('#lesson-'+ actualPageID +' thead tr th').css("color", "#016771");
            },
            error: function(xhr, resp, text) {
                console.log("itt");
                console.log(resp);
            }
        });
        e.preventDefault(); 
        
    }else {
        $(".StepButton").submit(function(ev){
            var actualPageID = $(this).find('input[name="pageid"]').val();
            var id = $(this).find('input[name="id"]').val();
            var sesskey = $(this).find('input[name="sesskey"]').val();
            var jumpToStepVal = $(this).find('input[name="jumpto"]').val();
            var method = $(this).attr('method');
            var url = $(this).attr('action');
            //create a formdata based on the pressed button hidden values
            var formData = {
                    'id' : id, 
                    'pageid' : actualPageID, 
                    'sesskey': sesskey, 
                    'stepButton' : true,
                    'jumptoStep' : jumpToStepVal
            }; 

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                dataType: 'JSON',
                success: function (data) {
                    $('#lesson-'+ data.nextstepid +' tbody').show();
                    $('#lesson-'+ data.nextstepid +' thead th.header').css("background-color", "#016771");
                    $('#lesson-'+ data.nextstepid +' thead th.header').css("color", "white");
                    $('#lesson-'+ actualPageID +' tbody').hide();
                    $('#lesson-'+ actualPageID +' thead tr th').css("background-color", "white");
                    $('#lesson-'+ actualPageID +' thead tr th').css("color", "#016771");
                },
                error: function(xhr, resp, text) {
                    console.log("itt");
                    console.log(xhr, resp, text);
                }
            });
            ev.preventDefault(); 
        });
    }
    
});    



function theme_custom_js() {
    //get all form from the page
    var formIDs = [];
    $("form").each(function() {
        var formid = $(this).attr('id');
        //filter the unnamed form ids 
        if(typeof formid !== 'undefined'){
            //the lesson question forms started with mform id and plus a number
            // so we need to filter them
            var fid = formid.includes("mform");
            if(fid === true){
                formIDs.push($(this).attr('id'));
            }    
        }
    });

    // get the lesson next and previous button forms because they have no id or name 
    var noSpace= $('form:not([id]):not([class])'); 
    var urlAction = "";

    noSpace.each(function() {                
        urlAction = $(this).attr('action');
        var urlSplit = urlAction.split('/');
        urlSplit = urlSplit[urlSplit.length-1]
        //the lessons using the continue.php for action
        // so if the action is then continue.php 
        // then i am adding an ID to I can handle the form with jquery
        if(urlSplit === "continue.php"){
            $(this).attr("class", "StepButton");
        }                
    });

    //check the submit
    $("form").submit(function(ev){

        //get the actual submitted form id
        var actualFormID = $(this).attr('id');

        //check if this id is in our array, then the user submitted a lesson quiz
        if ($.inArray(actualFormID, formIDs) != -1){

            var actualPageID = $(this).find('input[name="pageid"]').val();
            var formData = {
                   'id' : $(this).find('input[name="id"]').val(), 
                   'pageid' : $(this).find('input[name="pageid"]').val(), 
                   'sesskey': $(this).find('input[name="sesskey"]').val(), 
            }; 

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'html',
                success: function (data) {
                    //show the json response quiz data in the actual div                            
                    $('#lesson-'+ actualPageID +' tbody').empty();
                    $('#lesson-'+ actualPageID +' tbody').append(data);                            
                },
                error: function(xhr, resp, text) {
                    console.log(xhr, resp, text);
                }
            });
            ev.preventDefault();
        }
    });

    $("ul").removeClass("nav-tabs");

    $("button").click(function(){
        $("p").removeClass("intro");
    });

    $("#collapse_course_menu").click(function(){
        if ($('.navbar.navbar-default').css('display') === 'none') {
            $(".navbar.navbar-default").show();
            $(".left_course_menu_hidden").removeClass("left_course_menu_hidden").addClass("left_course_menu");
            $(".course_content_hidden").removeClass("course_content_hidden").addClass("course_content");
        } else {
            $(".navbar.navbar-default").hide();
            $(".left_course_menu").removeClass("left_course_menu").addClass("left_course_menu_hidden");
            $(".course_content").removeClass("course_content").addClass("course_content_hidden");
        }
    });

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
}
            