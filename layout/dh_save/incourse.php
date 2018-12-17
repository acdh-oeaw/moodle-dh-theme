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
 * @copyright 2017 ACDH
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
    <?php echo theme_dariahteach_header_meta_data(); ?>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="<?php echo $CFG->wwwroot;?>/theme/dariahteach/lightbox/lightbox.js"></script>
    <link rel="stylesheet" href="<?php echo $CFG->wwwroot;?>/theme/dariahteach/lightbox/lightbox.css">    
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/theme/dariahteach/javascript/cookie.js"></script>      
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/theme/dariahteach/javascript/main.js"></script>        
    <?php echo $OUTPUT->standard_head_html() ?>
    <script type="text/javascript">
       
        $(document).on('click','#StepButton input:submit',function(e) {
            var formObj = $(this)[0].form;
            var mainUrl = window.location.href; 
            var formid = "";
            var formPageid = "";
            var formSesskey = "";
            var formJumpTo = "";
            
            $.each( formObj, function( key, value ) {
                if(value.name == "id") { formid = value.value; }
                if(value.name == "pageid") { formPageid = value.value; }
                if(value.name == "sesskey") { formSesskey = value.value; }
                if(value.name == "jumpto") { formJumpTo = value.value; }
            });
           
            if(formid == "" && formPageid == "" && formSesskey == "" && formJumpTo == ""){
                e.preventDefault();
                return false;
            }
               
            var actualPageID = formPageid;
            //create a formdata based on the pressed button hidden values
            var formData = {
                    'id' : formid, 
                    'pageid' : formPageid, 
                    'sesskey': formSesskey, 
                    'stepButton' : true,
                    'jumptoStep' : formJumpTo
            };
            
            //if we reached the end of the lesson button then we will display the last page
            if(formJumpTo == "-9"){
                var newURL = mainUrl.replace(/(pageid=).*?(&)/,'$1' + formJumpTo + '$2');
                if (newURL.indexOf("pageid=") <= 0){
                    newURL = newURL + "&pageid=-9"	
                }
                window.location.href = newURL;
            }else {
                //otherwise we will do the pageing
                $.ajax({
                    type: formObj.method, 
                    url: formObj.action, 
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
            }
            e.preventDefault();
        });
       
    </script>
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header_course.php');  ?>


    
<div id="page" class="container-fluid" style="margin-bottom: 20px;">

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
