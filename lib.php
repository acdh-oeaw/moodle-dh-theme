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


/**
 * Load the Jquery and migration files
	* Load the our theme js file
	*
 */
function theme_dariahteach_page_init(moodle_page $page) {
    //$page->requires->jquery();
    /*
    $page->requires->js('/theme/dariahteach/javascript/jquery-3.3.1.min.js');
    $page->requires->js('/theme/dariahteach/javascript/jquery.awesomeCloud-0.2.js');
    $page->requires->js('/theme/dariahteach/javascript/main.js');
    $page->requires->js('/theme/dariahteach/javascript/cookie.js');
    $page->requires->js('/theme/dariahteach/javascript/bootstrap.min.js');
    $page->requires->js('/theme/dariahteach/lightbox/lightbox.js');*/
}


/**
 * Loads the CSS Styles and replace the background images.
	* If background image not available in the settings take the default images.
 *
 * @param $css 
	* @param $theme
	* @return string
 */	
	
function theme_dariahteach_process_css($css, $theme) {

    // Set the background image for the logo.
    $logo = $theme->setting_file_url('logo', 'logo');
    $css = theme_dariahteach_set_logo($css, $logo);

    // Set custom CSS.
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = theme_dariahteach_set_customcss($css, $customcss);
    $css = theme_dariahteach_set_fontwww($css);

    return $css;
}

/**
 * Adds the logo to CSS.
 *
 * @param string $css The CSS.
 * @param string $logo The URL of the logo.
 * @return string The parsed CSS
 */
function theme_dariahteach_set_logo($css, $logo) {
    $tag = '[[setting:logo]]';
    $replacement = $logo;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_dariahteach_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    static $theme;

    if (empty($theme)) {
        $theme = theme_config::load('dariahteach');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
								
        if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        } else if ($filearea === 'footerlogo') {
            return $theme->setting_file_serve('footerlogo', $args, $forcedownload, $options);
        } else if ($filearea === 'style') {
            theme_dariahteach_serve_css($args[1]);
        } else if ($filearea === 'pagebackground') {
            return $theme->setting_file_serve('pagebackground', $args, $forcedownload, $options);
        } else if (preg_match("/slide[1-9][0-9]*image/", $filearea) !== false) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        }	else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

/**
 * Serves CSS for image file updated to styles.
 *
 * @param string $filename
	* @return string
 */
function theme_dariahteach_serve_css($filename) {
    global $CFG;
    if (!empty($CFG->themedir)) {
        $thestylepath = $CFG->themedir . '/dariahteach/style/';
    } else {
        $thestylepath = $CFG->dirroot . '/theme/dariahteach/style/';
    }
    $thesheet = $thestylepath . $filename;
    $etagfile = md5_file($thesheet);
    // File.
    $lastmodified = filemtime($thesheet);
    // Header.
    $ifmodifiedsince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
    $etagheader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

    if ((($ifmodifiedsince) && (strtotime($ifmodifiedsince) == $lastmodified)) || $etagheader == $etagfile) {
        theme_dariahteach_send_unmodified($lastmodified, $etagfile);
    }
    theme_dariahteach_send_cached_css($thestylepath, $filename, $lastmodified, $etagfile);
}

// Set browser cache used in php header
function theme_dariahteach_send_unmodified($lastmodified, $etag) {
    $lifetime = 60 * 60 * 24 * 60;
    header('HTTP/1.1 304 Not Modified');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Content-Type: text/css; charset=utf-8');
    header('Etag: "' . $etag . '"');
    if ($lastmodified) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    }
    die;
}

// Cached css
function theme_dariahteach_send_cached_css($path, $filename, $lastmodified, $etag) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/configonlylib.php'); // For min_enable_zlib_compression().
    // 60 days only - the revision may get incremented quite often.
    $lifetime = 60 * 60 * 24 * 60;

    header('Etag: "' . $etag . '"');
    header('Content-Disposition: inline; filename="'.$filename.'"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Pragma: ');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Accept-Ranges: none');
    header('Content-Type: text/css; charset=utf-8');
    if (!min_enable_zlib_compression()) {
        header('Content-Length: ' . filesize($path . $filename));
    }

    readfile($path . $filename);
    die;
}


/**
 * Adds any custom CSS to the CSS before it is cached.
 *
 * @param string $css The original CSS.
 * @param string $customcss The custom CSS to add.
 * @return string The CSS which now contains our custom CSS.
 */
function theme_dariahteach_set_customcss($css, $customcss) {
    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    if (is_null($replacement)) {
        $replacement = '';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

/**
 * Returns an object containing HTML for the areas affected by settings.
 *
 * Do not add Clean specific logic in here, child themes should be able to
 * rely on that function just by declaring settings with similar names.
 *
 * @param renderer_base $output Pass in $OUTPUT.
 * @param moodle_page $page Pass in $PAGE.
 * @return stdClass An object with the following properties:
 *      - navbarclass A CSS class to use on the navbar. By default ''.
 *      - heading HTML to use for the heading. A logo if one is selected or the default heading.
 *      - footnote HTML to use as a footnote. By default ''.
 */
function theme_dariahteach_get_html_for_settings(renderer_base $output, moodle_page $page) {
    global $CFG;
    $return = new stdClass;
    $return->navbarclass = '';
    if (!empty($page->theme->settings->invert)) {
        $return->navbarclass .= ' navbar-inverse';
    }

    if (!empty($page->theme->settings->logo)) {
        $return->heading = html_writer::link($CFG->wwwroot, '', array('title' => get_string('home'), 'class' => 'logo'));
    } else {
        $return->heading = $output->page_heading();
    }

    $return->footnote = '';
    if (!empty($page->theme->settings->footnote)) {
        $return->footnote = '<div class="footnote text-center">'.format_text($page->theme->settings->footnote).'</div>';
    }

    return $return;
}

/**
 * Loads the CSS Styles and put the font path
 *
 * @param $css
	* @return string
 */
function theme_dariahteach_set_fontwww($css) {
    global $CFG, $PAGE;
    if(empty($CFG->themewww)){
        $themewww = $CFG->wwwroot."/theme";
    } else {
        $themewww = $CFG->themewww;
    }

    $tag = '[[setting:fontwww]]';
    $theme = theme_config::load('dariahteach');
    $css = str_replace($tag, $themewww.'/dariahteach/fonts/', $css);
    return $css;
}

/**
 * Logo Image URL Fetch from theme settings
	*
	* @return string
 */


if (!function_exists('get_logo_url')) {	
    function get_logo_url($type='header') {
        global $OUTPUT;
        static $theme;
        if(empty($theme)) { $theme = theme_config::load('dariahteach'); }
								
        if($type=="header") {
            $logo = $theme->setting_file_url('logo', 'logo');
            $logo = empty($logo)?$OUTPUT->image_url('home/logo', 'theme'):$logo;
        }
        return $logo;
    }
}

function theme_dariahteach_get_setting($setting, $format = false) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/weblib.php');
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('dariahteach');
    }
    if (empty($theme->settings->$setting)) {
        return false;
    } else if (!$format) {
        return $theme->settings->$setting;
    } else if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    } else if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    } else {
        return format_string($theme->settings->$setting);
    }
}

/**
 * Return the current theme url
 *
	* @return string
 */
if (!function_exists('theme_url'))  {
    function theme_url() {
        global $CFG,$PAGE;
        $theme_url =	$CFG->wwwroot.'/theme/'. $PAGE->theme->name;
        return $theme_url;
    }
}

function getDHCourseImage($courseid){
        
    global $CFG;
    if (empty($CFG->courseoverviewfileslimit)) {
        return array();
    }
    require_once($CFG->libdir. '/filestorage/file_storage.php');
    require_once($CFG->dirroot. '/course/lib.php');
    $fs = get_file_storage();
    $context = context_course::instance($courseid);
    $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', false, 'filename', false);
    if (count($files)) {
        $overviewfilesoptions = course_overviewfiles_options($courseid);
        $acceptedtypes = $overviewfilesoptions['accepted_types'];
        if ($acceptedtypes !== '*') {
            // Filter only files with allowed extensions.
            require_once($CFG->libdir. '/filelib.php');
            foreach ($files as $key => $file) {
                if (!file_extension_in_typegroup($file->get_filename(), $acceptedtypes)) {
                    unset($files[$key]);
                }
            }
        }
        if (count($files) > $CFG->courseoverviewfileslimit) {
            // Return no more than $CFG->courseoverviewfileslimit files.
            $files = array_slice($files, 0, $CFG->courseoverviewfileslimit, true);
        }
    }
    
    // Display course overview files.
    $courseimage = '';
    foreach ($files as $file) {
        $isimage = $file->is_valid_image();
        if ($isimage) {
            $courseimage = file_encode_url("$CFG->wwwroot/pluginfile.php",
            '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
            $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
        }
    }
    return $courseimage;
}

function theme_dariahteach_header_meta_data(){
        return '
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:url"           content="https://teach.dariah.eu/" />
        <meta property="og:type"          content="website" />
        <meta property="og:title"         content="#dariahTeach" />
        <meta property="og:description"   content="open-source, high quality, multilingual teaching materials for the digital arts and humanities" />
        <meta property="og:image"         content="https://teach.dariah.eu/theme/dariahteach/pix/logo_darkGreen_100.png" />
        ';
    }  

