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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        $(document).ready(function(){

            $("ul").removeClass("nav-tabs");
            $("button").click(function(){
                $("p").removeClass("intro");
            });
            
            $("#collapse_course_menu").click(function(){
                console.log("clicked");
                
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
            


        });
    </script>
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php /*?><header role="banner" class="navbar navbar-fixed-top<?php echo $html->navbarclass ?> moodle-has-zindex">
    <nav role="navigation" class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="<?php echo $CFG->wwwroot;?>"><?php echo
                format_string($SITE->shortname, true, array('context' => context_course::instance(SITEID)));
                ?></a>
            <a class="btn btn-navbar" data-toggle="workaround-collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <?php echo $OUTPUT->user_menu(); ?>
            <div class="nav-collapse collapse">
                <?php echo $OUTPUT->custom_menu(); ?>
                <ul class="nav pull-right">
                    <li><?php echo $OUTPUT->page_heading_menu(); ?></li>
                </ul>
            </div>
        </div>
    </nav>
</header><?php */?>
<?php  require_once(dirname(__FILE__) . '/includes/header_course.php');  ?>


    
<div id="page" class="container-fluid">

    
    <header id="page-header" class="clearfix">          
        <div >
            <nav class="breadcrumb-nav"><?php echo $OUTPUT->navbar(); ?></nav>
            <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
        </div>
    </header>
    
    <div id="page-content" class="row-fluid">
        <div id="<?php echo $regionbsid ?>" class="">
            <div class="row-fluid">

                <div style=" width:100%; height:100%;">
                    
                    <div class="left_course_menu">
                        <?php  echo $OUTPUT->blocks('center-post', 'oeaw_custom_menu'); ?>
                    </div>                    
                    
                    <div class="course_content">
                        <section id="region-main" class="">
                            forum
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

    <?php /*?><footer id="page-footer">
        <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
        <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
        <?php
        echo $html->footnote;
        echo $OUTPUT->login_info();
        echo $OUTPUT->home_link();
        echo $OUTPUT->standard_footer_html();
        ?>
    </footer>

    <?php echo $OUTPUT->standard_end_of_body_html() ?><?php */?>

</div>

<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>  
    <?php echo $OUTPUT->blocks('side-pre', 'span3'); ?>
</div>
</body>
</html>
