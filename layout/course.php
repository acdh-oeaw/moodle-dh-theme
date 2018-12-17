<?php
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
 * @package   theme_dariahteach
 * @copyright   2018 ACDH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Get the HTML for the settings bits.
$html = theme_dariahteach_get_html_for_settings($OUTPUT, $PAGE);

if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <meta property="og:url"           content="https://teach.dariah.eu/" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="#dariahTeach" />
    <meta property="og:description"   content="open-source, high quality, multilingual teaching materials for the digital arts and humanities" />
    <meta property="og:image"         content="https://teach.dariah.eu/theme/dariahteach/pix/logo_darkGreen_100.png" />
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    
    <?php echo $OUTPUT->standard_head_html() ?>
    
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript">
       
         $(document).on('click','#StepButton input:submit',function() {
      
            //get the step button pressed
           $("#StepButton").submit(function(ev){

               var actualPageID = $(this).find('input[name="pageid"]').val();
               //create a formdata based on the pressed button hidden values
               var formData = {
                       'id' : $(this).find('input[name="id"]').val(), 
                       'pageid' : $(this).find('input[name="pageid"]').val(), 
                       'sesskey': $(this).find('input[name="sesskey"]').val(), 
                       'stepButton' : true,
                       'jumptoStep' : $(this).find('input[name="jumpto"]').val(),                        
               }; 

               $.ajax({
                   type: $(this).attr('method'),                    
                   url: $(this).attr('action'),
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
                   console.log(xhr, resp, text);
               }
               });

               ev.preventDefault();

            });
        });
       
        $(document).ready(function(){
            
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
                    $(this).attr("id", "StepButton");
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
                }
                else
                {
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
            
            


        });
    </script>
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

    <?php echo $OUTPUT->standard_top_of_body_html() ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <?php  require_once(dirname(__FILE__) . '/includes/header_course.php');  ?>

    <div id="page" class="container-fluid">

    <input type="hidden" name="course_id" id="course_id" value="<?php echo $COURSE->id;?>">
    
    <header id="page-header" class="clearfix">          
        <div >            
            <nav class="breadcrumb-nav"><?php echo $OUTPUT->navbar(); ?></nav>
            <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
        </div>
    </header>
    

            
            <div class="container-fluid">
                <div id="<?php echo $regionbsid ?>" class="">
                    
                    <div class="row-fluid">
                        <div class="col-xs-0 col-sm-1 col-md-1 col-lg-1" ></div>
                        <div class="col-xs-12 col-sm-11 col-md-11 col-lg-11 " >
                            <?php  require_once(dirname(__FILE__) . '/includes/course_firstlesson_header.php');  ?>
                        </div>
                            
                    </div>
                </div>
            </div>
            
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="col-sm-1 col-md-1 col-lg-1" ></div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 moodle_incourse_left_side" >
                        <nav class="navbar navbar-default">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>                                
                            </div>
                            <div class="navbar-collapse collapse">
                                <?php  echo $OUTPUT->blocks('center-post', 'oeaw_custom_menu'); ?>
                            </div> 
                        </nav>    
                                
                    </div>

                    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 moodle_incourse_main_content">
                        <div class="course_content">
                            <section id="region-main" class="">
                                <?php
                                echo $OUTPUT->course_content_header();
                                echo $OUTPUT->main_content();
                                echo $OUTPUT->course_content_footer();
                                ?>
                            </section>    
                        </div>
                    </div>

                </div>
            </div>
          
        
    </div>

</div>

<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>  
    <?php echo $OUTPUT->blocks('side-pre', 'span3'); ?>
</div>
</body>
</html>
