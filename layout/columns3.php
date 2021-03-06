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
 * The two column layout.
 *
 * @package   theme_dariahteach
 * @copyright   2018 ACDH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Get the HTML for the settings bits.
$html = theme_dariahteach_get_html_for_settings($OUTPUT, $PAGE);

$left = (!right_to_left());  // To know if to add 'pull-right' and 'desktop-first-column' classes in the layout for LTR.
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

<body <?php echo $OUTPUT->body_attributes('two-column'); ?>>
    <!-- column 3 -->
    <?php echo $OUTPUT->standard_top_of_body_html() ?>
    <?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

    <div id="page" class="container-fluid" >
        <header id="page-header" class="clearfix">
            <?php echo $html->heading; ?>
            <div id="page-navbar" class="clearfix" >
                <nav class="breadcrumb-nav"><?php echo $OUTPUT->navbar(); ?></nav>
                <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
            </div>
            <div id="course-header">
            </div>
        </header>

        <div id="page-content" class="row-fluid" style="margin-bottom: 20px;">     
            <section id="region-main" class="">
                <?php
                    echo $OUTPUT->course_content_header();            
                    echo $OUTPUT->main_content();
                    echo $OUTPUT->course_content_footer();
                ?>
            </section>
            <?php
                $classextra = '';
                if ($left) { $classextra = ' desktop-first-column'; }
                ?>
            <div  style="color: black;"></div>        
        </div>
    </div>

    <?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>

    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>  
        <?php echo $OUTPUT->blocks('side-pre', 'span3'); ?>
    </div>
</body>
</html>
