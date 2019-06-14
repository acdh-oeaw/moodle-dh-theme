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
 * frontpage.php
 *
 * @package   theme_dh
 * @copyright 2019 ACDH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
// Get the HTML for the settings bits.
$html = theme_dh_get_html_for_settings($OUTPUT, $PAGE);

if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

$courserenderer = $PAGE->get_renderer('core', 'course');
$courserenderer->getFPCourses();
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php
require_once(dirname(__FILE__) . '/includes/header.php');
echo $headerlayout;
 ?>
<!--Custom theme header-->
<div class="">
    <?php
        $toggleslideshow = theme_dh_get_setting('toggleslideshow');
    if ($toggleslideshow == 1) {
        require_once(dirname(__FILE__) . '/includes/slideshow.php');
    }
    ?>
</div>    
<!--Custom theme Who We Are block-->
<div id="page" class="container-fluid">
    <header id="page-header" class="clearfix">
        <?php echo $html->heading; ?>
        <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
        </div>
    </header>
    <div id="page-content" class="row">
    <?php
        $class = "col-md-12";
    ?>
        
        
        
         <div class="container-fluid frontpage-container-fluid">             
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">                    
                    <div class="container-fluid">             
                        <div class="row fp-main-sub-title">
                            <div class="col-md-4 col-lg-6">                                
                                 <h2>Courses</h2>
                                <div class="fp-main-course-subtext">Courses represent the equivalent level of student effort as a 5 or 10 ECTS module.</div>
                            </div>
                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 mb-10 header-filter-div">
                                
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 float-right" style="max-width: 75px;">
                                    <h5>Credits: </h5>   
                                    <?php
                                        $courserenderer->frontpage_ects_box(); 
                                    ?>
                                </div>
                                
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 float-right" style="max-width: 150px;">
                                    <h5>Languages: </h5>
                                    <?php
                                        $courserenderer->frontpage_languages_box(); 
                                    ?>
                                </div>
                                
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 float-right" style="max-width: 200px;">
                                    <h5>Order by: </h5>
                                    <select name="fp-order-by-box" class="fp-order-by-box fp-select">                     
                                        <option value="date_desc" selected="selected">Release Date Desc</option>
                                        <option value="date_asc">Release Date Asc</option>
                                        <option value="title_asc">Title Asc</option>
                                        <option value="title_desc">Title Desc</option>
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " >
                    <?php 
                    echo $courserenderer->frontpage_dh_courses(); 
                    ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " >
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <p class="text-center font-weight-bold"><a href="#" class="show_hide_frontpage_courses">Show All Courses</a></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 justify-content-center d-flex" >
                    <div class="container-fluid">    
                        <div class="row">    
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 justify-content-center d-flex" >
                                <div class="fp-info-box">
                                    <h3 class="text-center"><a href="#">The Publication Concept</a></h3>
                                    <span class="font-weight-normal">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren</span>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 justify-content-center d-flex" >
                                <div class="fp-info-box">
                                    <h3 class="text-center"><a href="#">How to Collaborate</a></h3>
                                    <span class="font-weight-normal">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren</span>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 justify-content-center d-flex" >
                                <div class="fp-info-box">
                                    <h3 class="text-center"><a href="#">Submit a Course</a></h3>
                                    <span class="font-weight-normal">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren</span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " >
                    <div class="container-fluid">    
                        <div class="row">    
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 h-100" >
                                <?php
                                    echo $OUTPUT->course_content_header();
                                    echo $OUTPUT->main_content();
                                    echo $OUTPUT->course_content_footer();
                                ?>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="tagcloud_dh">
                                <center>
                                    <?php echo $OUTPUT->blocks('fp-tag', ''); ?>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>       
    </div>
    <?php echo (isset($flatnavbar)) ? $flatnavbar : ""; ?>
</div>
<?php
    require_once(dirname(__FILE__) . '/includes/footer.php');
    echo $footerlayout;

?>
<!--Custom theme footer-->

</body>
</html>