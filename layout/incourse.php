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
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <meta property="og:url"           content="https://teach.dariah.eu/" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="#dariahTeach" />
    <meta property="og:description"   content="open-source, high quality, multilingual teaching materials for the digital arts and humanities" />
    <meta property="og:image"         content="https://teach.dariah.eu/theme/dariahteach/pix/logo_darkGreen_100.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $OUTPUT->standard_head_html() ?>
    <?php
        $PAGE->requires->js('/theme/dariahteach/javascript/jquery-3.3.1.min.js');
        $PAGE->requires->js('/theme/dariahteach/javascript/jquery.awesomeCloud-0.2.js');
        $PAGE->requires->js('/theme/dariahteach/javascript/main.js');
        $PAGE->requires->js('/theme/dariahteach/javascript/cookie.js');
        $PAGE->requires->js('/theme/dariahteach/javascript/bootstrap.min.js');
        $PAGE->requires->js('/theme/dariahteach/lightbox/lightbox.js');
    ?>
</head>

<body <?php if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'mod/hvp/view.php?id=') === false) { echo $OUTPUT->body_attributes(); } ?> >
    <!-- incourse -->
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
    <?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>  
        <?php echo $OUTPUT->blocks('side-pre', 'span3'); ?>
    </div>
</body>
</html>
