<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option)  any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * footer.php
 *
 * @package    theme_dh
 * @copyright  2019 ACDH
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
// Header content.
$logourl = get_logo_url();
$dhlogo = $CFG->wwwroot.'/theme/dh/pix/dh_logo_200.png';
$eulogo = $CFG->wwwroot.'/theme/dh/pix/eu_logo_200.png';
$moodlelogo = $CFG->wwwroot.'/theme/dh/pix/md_logo_200.png';
$surl = new moodle_url('/course/search.php');
if (! $PAGE->url->compare($surl, URL_MATCH_BASE)) {
    $compare = 1;
} else {
    $compare = 0;
}
$surl = new moodle_url('/course/search.php');
$ssearchcourses = get_string('searchcourses');
$shome = get_string('home', 'theme_dh');

$piwikid = 39;

$actual_url = (string)$PAGE->url->__toString();

if ( (strpos($actual_url, '//teach.dariah.eu/') !== false)  ) {
    $piwikid = 39;
}

if ( (strpos($actual_url, '//clarin.oeaw.ac.at/moodle-dev') !== false)  ) {
    $piwikid = 130;
}


// Footer Content.
$logourlfooter = get_logo_url('footer');
$footlogo = theme_dh_get_setting('footerlogo');

$footnote = theme_dh_get_setting('footnote', 'format_html');
$copyrightfooter = theme_dh_get_setting('copyright_footer');
$infolink = theme_dh_infolink();

$sinfo = get_string('info', 'theme_dh');
$scontactus = get_string('contact_us', 'theme_dh');
$sphone = get_string('phone', 'theme_dh');
$semail = get_string('email', 'theme_dh');
$sgetsocial = get_string('get_social', 'theme_dh');

$contact = ($emailid != '' || $address != '' || $phoneno != '') ? 1 : 0;
$url = ($fburl != '' || $pinurl != '' || $twurl != '' || $gpurl != '') ? 1 : 0;

if ($footlogo != '' || $footnote != '' || $infolink != '' || $url != 0 || $contact != 0 || $copyrightfooter != '') {
    $footerall = 1;
} else {
    $footerall = 0;
}

$block1 = ($footlogo != '' || $footnote != '') ? 1 : 0;
$infoslink = ($infolink != '') ? 1 : 0;
$blockarrange = $block1 + $infoslink + $contact + $url;

switch ($blockarrange) {
    case 4:
        $colclass = 'col-md-3';
        break;
    case 3:
        $colclass = 'col-md-4';
        break;
    case 2:
        $colclass = 'col-md-6';
        break;
    case 1:
        $colclass = 'col-md-12';
        break;
    case 0:
        $colclass = '';
        break;
    default:
        $colclass = 'col-md-3';
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
    "dhlogo" => $dhlogo,
    "eulogo" => $eulogo,
    "moodlelogo" => $moodlelogo,
    "compare" => $compare,
    "logourl_footer" => $logourlfooter,
    "footnote" => $footnote,
    "copyright_footer" => $copyrightfooter,
    "infolink" => $infolink,
    "url" => $url,
    "contact" => $contact,
    "footerall" => $footerall,
    "block1" => $block1,
    "colclass" => $colclass,
    "piwikid" => $piwikid
];

$templatecontext['flatnavigation'] = $PAGE->flatnav;
$footerlayout = $OUTPUT->render_from_template('theme_dh/footer', $templatecontext);