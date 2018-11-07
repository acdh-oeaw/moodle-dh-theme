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
 * @package    theme_dariahteach
 * @copyright  2018 ACDH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

$THEME->name = 'dariahteach';

$THEME->doctype = 'html5';
$THEME->parents = array('bootstrapbase');
$THEME->sheets = array('custom','theme','font-awesome.min');
$THEME->sheets = array('custom','theme','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');

$THEME->supportscssoptimisation = false;
$THEME->yuicssmodules = array();
$THEME->enable_dock = true;
$THEME->editor_sheets = array();

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->csspostprocess = 'theme_dariahteach_process_css';

$THEME->layouts = array(
        // The site home page.
        'frontpage' => array(
                        'file' => 'frontpage.php',
                        'regions' => array('side-pre', 'side-post', 'side-content', 'center-post'),
                        'defaultregion' => 'side-pre',
                        'options' => array('nonavbar' => true),
        ),
        'course' => array(
                        'file' => 'course.php',
                        'regions' => array('side-pre', 'side-post', 'side-content', 'center-post'),
                        'defaultregion' => 'side-pre',
                        'options' => array('nonavbar' => true),
        ),
        'coursecategory' => array(
                        'file' => 'course-cat.php',
                        'regions' => array('side-pre', 'side-post', 'side-content', 'center-post'),
                        'defaultregion' => 'side-pre',
                        'options' => array('nonavbar' => true),
        ),
        'incourse' => array(
                        'file' => 'incourse.php',
                        'regions' => array('side-pre', 'side-post', 'side-content', 'center-post'),
                        'defaultregion' => 'side-pre',
                        'options' => array('nonavbar' => true),
       ),
       'hubcommit' => array(
                        'file' => 'hubcommit.php',
                        'regions' => array('side-pre', 'side-post', 'side-content', 'center-post'),
                        'defaultregion' => 'side-pre',
                        'options' => array('nonavbar' => true),
       ),
        'dhvideo' => array(
                        'file' => 'page.php',
                        'regions' => array('side-pre', 'side-post', 'side-content', 'center-post'),
                        'defaultregion' => 'side-pre',
                        'options' => array('nonavbar' => true),
        ) 
);

$THEME->blockrtlmanipulations = array(
    'side-pre' => 'side-post',
    'side-post' => 'side-pre'
);
