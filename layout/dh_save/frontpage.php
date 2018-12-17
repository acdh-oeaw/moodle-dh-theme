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

$PAGE->requires->js('/theme/dariahteach/javascript/bootstrap-carousel.js');
$PAGE->requires->js('/theme/dariahteach/javascript/bootstrap-transition.js');
$courserenderer = $PAGE->get_renderer('core', 'course');

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:url"           content="https://teach.dariah.eu/" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="#dariahTeach" />
    <meta property="og:description"   content="open-source, high quality, multilingual teaching materials for the digital arts and humanities" />
    <meta property="og:image"         content="https://teach.dariah.eu/theme/dariahteach/pix/logo_darkGreen_100.png" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    
    <?php echo $OUTPUT->standard_head_html() ?>
        
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header_course.php');  ?>
<!--Custom theme header-->
<script type="text/javascript">
        $(document).ready(function(){
            //hide all menupoint in the first load
            $(".block_course_custom_menu").hide();
            $(".fp-coursebox .readmore").hide();
        });            
</script>            
<?php


//block_course_custom_menu
?>

<!--Custom theme Who We Are block-->
<div id="page" class="container-fluid">

    <div class="row-fluid">        
         <div class="col-xs-0 col-sm-2 col-md-2 col-lg-1">

        </div>
        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-11 frontpage_up_boxes ">
            <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">                    
                <?php //echo $OUTPUT->blocks('side-content', ''); 
                    echo $OUTPUT->course_content_header();
                    echo $OUTPUT->main_content();                
                    echo $OUTPUT->course_content_footer();
            ?>
            </div>

            <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6" id="tagcloud_dh">                    
                <center>
                    <?php echo $OUTPUT->blocks('fp-tag', ''); ?>
                </center>
            </div>

        </div>
    </div>


    <div id="page-content" class="row-fluid">

         <div class="col-xs-3 col-sm-2 col-md-1 col-lg-1">

        </div>
        <div class="col-xs-9 col-sm-10 col-md-10 col-lg-11 ">
            <?php
           echo $courserenderer->frontpage_available_courses();

          ?>
        </div>
    </div>              
</div>    


    <?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>   
    <!--Custom theme footer-->
    <div id="mySidenav" class="sidenav" style="margin-bottom:-20px;">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>  
        <div style=" height:100%; ">
            <?php echo $OUTPUT->blocks('side-pre', 'span12'); ?>
        </div>      
    </div>
 
</body>
</html>