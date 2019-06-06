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
 * renderers/core_renderer.php
 *
 * @package    theme_dh
 * @copyright  2019 ACDH
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
/**
 * dh theme core renderer class
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_dh_core_renderer extends theme_boost\output\core_renderer {
    /**
     * Header custom menu renderer.
     *
     * @param custom_menu $menu
     * @return string
     */
    public function custom_menu_render(custom_menu $menu) {
        global $CFG;
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        if (!$menu->has_children() && !$haslangmenu) {
            return '';
        }
        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('theme_dh/custom_menu_item', $context);
        }
        return $content;
    }
    
       /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header_course() {
        global $PAGE, $COURSE;
        
        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($PAGE->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        //$sitename = format_string($SITE->fullname, true, array('context' => context_course::instance(SITEID)));
        $courseimage = getCourseImage($COURSE->id);
        $header->courseImg = $courseimage;
        $header->coursename = $COURSE->fullname;
        $header->coursesummary = s(strip_tags(format_text($COURSE->summary, FORMAT_HTML)));        
        return $this->render_from_template('theme_dh/header_course', $header);
    }
}