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
 * columns2.php
 *
 * @package   theme_dh
 * @copyright 2019 ACDH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');


$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');


$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$actual_url = "";
$actual_url = (string)$PAGE->url->__toString();

$course_top_desc = false;
if ( (strpos($actual_url, '/course/') !== false) ||(strpos($actual_url, '/mod/') !== false)  ) {
    $course_top_desc = true;
}
$course_top_image = false;
if ( (strpos($actual_url, '/course/view.php') !== false)  ) {
    $course_top_image = true;
}

$course_edit = false;
if ( (strpos($actual_url, '/edit.php') !== false) ||  (strpos($actual_url, '/editpage.php') !== false)) {
    $course_edit = true;
}

$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
// Header content.
$logourl = get_logo_url();
$sitelogo = $CFG->wwwroot.'/theme/dh/pix/dh_transparent_logo_100.png';
$dhlogo = $CFG->wwwroot.'/theme/dh/pix/dh_logo_200.png';
$eulogo = $CFG->wwwroot.'/theme/dh/pix/eu_logo_200.png';
$moodlelogo = $CFG->wwwroot.'/theme/dh/pix/md_logo_200.png';

$surl = new moodle_url('/course/search.php');
if (!$PAGE->url->compare($surl, URL_MATCH_BASE)) {
    $compare = 1;
} else {
    $compare = 0;
}
$surl = new moodle_url('/course/search.php');
$ssearchcourses = get_string('searchcourses');
$shome = get_string('home', 'theme_dh');

$custom = $OUTPUT->custom_menu();

if ($custom == '') {
    $class = "navbar-toggler hidden-lg-up nocontent-navbar";
} else {
    $class = "navbar-toggler hidden-lg-up";
}

// Footer Content.
$logourlfooter = get_logo_url('footer');
$footlogo = theme_dh_get_setting('footerlogo');
$footnote = theme_dh_get_setting('footnote', 'format_html');
$address  = theme_dh_get_setting('address');
$emailid  = theme_dh_get_setting('emailid');
$phoneno  = theme_dh_get_setting('phoneno');
$copyrightfooter = theme_dh_get_setting('copyright_footer');
$infolink = theme_dh_get_setting('infolink');
$infolink = theme_dh_infolink();

$sinfo = get_string('info', 'theme_dh');
$scontactus = get_string('contact_us', 'theme_dh');
$sphone = get_string('phone', 'theme_dh');
$semail = get_string('email', 'theme_dh');
$sgetsocial = get_string('get_social', 'theme_dh');

$url = ($fburl != '' || $pinurl != '' || $twurl != '' || $gpurl != '') ? 1 : 0;
$contact = ($emailid != '' || $address != '' || $phoneno != '') ? 1 : 0;

if ($footlogo != '' || $footnote != '' || $infolink != '' || $url != 0 || $contact != 0 || $copyrightfooter != '') {
    $footerall = 1;
} else {
    $footerall = 0;
}

$block1 = ($footlogo != '' || $footnote != '') ? 1 : 0;
$infoslink = ($infolink != '') ? 1 : 0;
$blockarrange = $block1 + $infoslink + $contact + $url;

switch ($hasblocks) {
    case 4:
        $colclass = 'col-sm-12 col-md-8 col-lg-9 col-xl-9';
        break;    
    default:
        $colclass = 'col-sm-12 col-md-12 col-lg-12 col-xl-12';
    break;
}

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    "surl" => $surl,
    "s_searchcourses" => $ssearchcourses,
    "s_home" => $shome,
    "logourl" => $logourl,
    "sitelogo" => $sitelogo,
    "compare" => $compare,
    "logourl_footer" => $logourlfooter,
    "footnote" => $footnote,    
    "address" => $address,    
    "copyright_footer" => $copyrightfooter,
    "infolink" => $infolink,
    "s_info" => $sinfo,
    "s_contact_us" => $scontactus,
    "s_phone" => $sphone,
    "s_email" => $semail,
    "s_get_social" => $sgetsocial,
    "url" => $url,    
    "footerall" => $footerall,
    "customclass" => $class,
    "block1" => $block1,
    "colclass" => $colclass,
    "logourl" => $logourl,
    "dhlogo" => $dhlogo,
    "eulogo" => $eulogo,
    "moodlelogo" => $moodlelogo,
    "course_top_desc" => $course_top_desc,
    "course_top_image" => $course_top_image,
    "course_edit" => $course_edit
    
];

$templatecontext['flatnavigation'] = $PAGE->flatnav;
echo $OUTPUT->render_from_template('theme_dh/columns2', $templatecontext);