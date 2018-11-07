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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $OUTPUT->standard_head_html() ?>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/theme/dariahteach/javascript/cookie.js"></script>  
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/theme/dariahteach/javascript/main.js"></script>  
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

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
        <div id="page-content" class="row-fluid">
            <div id="<?php echo $regionbsid ?>" class="">
                <div class="row-fluid">
                    
                    <?php  require_once(dirname(__FILE__) . '/includes/course_firstlesson_header.php');  ?>
                    
                    <div class="col-md-3">
                        <div class="left_course_menu">
                            <?php  echo $OUTPUT->blocks('center-post', 'oeaw_custom_menu'); ?>
                        </div>                    
                    </div>
                    
                    <div class="col-md-9">
                        <div class="course_content">
                            <section id="region-main" class="">      
pageben                                
                                <?php
                                echo $OUTPUT->course_content_header();                            
                                echo $OUTPUT->main_content();
                                echo $OUTPUT->course_content_footer();
                                ?>
                            </section>
                        </div>                    
                    </div>

                    <?php //echo $OUTPUT->blocks('side-pre', 'span4 desktop-first-column'); ?>
                </div>
            </div>
            <?php //echo $OUTPUT->blocks('side-post', 'span3'); ?>
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
