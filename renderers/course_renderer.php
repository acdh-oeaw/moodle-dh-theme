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
 * renderers/course_renderer.php
 *
 * @package    theme_dh
 * @copyright  2019 ACDH
 * @author    LMSACE Dev Team , lmsace.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . "/course/renderer.php");

/**
 * dh theme course renderer class
 *
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_dh_core_course_renderer extends core_course_renderer {
    
    private $courseIds = array();
    private $courseCF;
    private $fpCourses = array();
       
    
    private function getCourseIds() {
        global $CFG, $OUTPUT, $DB;
        //require_once($CFG->libdir. '/coursecatlib.php');

        $chelper = new coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->
            set_courses_display_options(array(
                'recursive' => true,
                'limit' => $CFG->frontpagecourselimit,
                'viewmoreurl' => new moodle_url('/course/index.php'),
                'viewmoretext' => new lang_string('fulllistofcourses')));

        $chelper->set_attributes(array('class' => 'frontpage-course-list-all'));
        //$courses = coursecat::get(0)->get_courses($chelper->get_courses_display_options());
        //$totalcount = coursecat::get(0)->get_courses_count($chelper->get_courses_display_options());
        $activeCourses = array();
        try {
            $activeCourses = $DB->get_records_sql('
            SELECT 
                DISTINCT(c.id) as id
            FROM course as c
            WHERE
                 c.visible = 1');
            //rawname != "Workshop" and rawname != "translated" and
            
            if(count($activeCourses) > 0){
                foreach($activeCourses as $val){
                    $this->courseIds[] = $val->id;
                }
            }
            
        } catch (Exception $ex) {
            $this->courseIds = array();                    
        }        
    }
    
    public function getFPCourses() {
        $this->fpCourses = $this->fp_order_box_sql();
    }
    
    private function fp_order_box_sql(string $orderby = "c.fullname desc") {
        global $CFG, $DB;
        //require_once($CFG->libdir. '/coursecatlib.php');
                
        $result = array();
        try {
            $result = $DB->get_records_sql('
                SELECT 
                    c.id, c.fullname, c.summary, GROUP_CONCAT(t.name) as tag_name,
                    (
                        SELECT cid.data 
                        FROM custom_info_data as cid 
                        LEFT JOIN custom_info_field as cif on cid.fieldid = cif.id 
                        WHERE cif.objectname = "course" and cid.objectid = c.id and cif.name = "ECTS"
                    ) as ects
                FROM course as c
                LEFT JOIN tag_instance as ti on ti.itemid = c.id
                LEFT JOIN tag as t on ti.tagid = t.id
                WHERE
                    c.visible = 1
                GROUP BY
                    c.id, c.fullname, c.summary
                ORDER BY  '.$orderby
            );
            
        } catch (Exception $ex) {
            $result = array();                    
        }
        
        return $result;
    }
    
    private function getCourseCustomFields(int $courseid) {
        global $CFG, $DB;
        $result = array();
        try {
            $result = $DB->get_records_sql('
                        SELECT cif.name, cid.data, cif.datatype, cif.param1
                        FROM {custom_info_data} as cid                        
                        LEFT JOIN {custom_info_field} as cif on cid.fieldid = cif.id
                        WHERE cid.objectid = '.$courseid.' and cif.objectname = "course" ');
        } catch (Exception $ex) {
            $result = array();                    
        }        
        return $result;
    }
    
    private function getCoursesCustomFields() {
        global $CFG, $DB;
        
        $result = array();
        
             
        $sql = '
            SELECT 
                cid.id, cid.objectid, cif.name, cid.data, cif.datatype, cif.param1
            FROM 
                {custom_info_data} as cid                        
            LEFT JOIN 
                {custom_info_field} as cif on cid.fieldid = cif.id
            LEFT JOIN 
                {course} as c on cid.objectid = c.id
            WHERE cif.objectname = "course" and c.visible = 1 
            ORDER BY cid.objectid 
                ';        
        
        try {
            $result = $DB->get_records_sql($sql);
        } catch (Exception $ex) {
            $result = array();                    
        }        
        return $result;
    }
    
    private function getCoursesCustomFieldsByCourse(int $courseid) {
        global $CFG, $DB;
        
        $result = array();
                     
        $sql = '
            SELECT 
                cid.id, cid.objectid, cif.name, cid.data, cif.datatype, cif.param1
            FROM 
                {custom_info_data} as cid                        
            LEFT JOIN 
                {custom_info_field} as cif on cid.fieldid = cif.id
            LEFT JOIN 
                {course} as c on cid.objectid = c.id
            WHERE cif.objectname = "course" and c.visible = 1 and c.id = '.$courseid.'
            ORDER BY cid.objectid 
                ';        
        
        try {
            $result = $DB->get_records_sql($sql);
        } catch (Exception $ex) {
            $result = array();                    
        }        
        return $result;
    }
       
    private function getTheCourseLanguages(): array {
        $languages = array();
        if(count($this->fpCourses) > 0) {
            foreach($this->fpCourses as $d) {
                if($d->id) {
                    $ccf = $this->getCoursesCustomFieldsByCourse($d->id);
                    if(count($ccf) > 0) {
                        foreach($ccf as $v) {
                            $name = ($v->name) ? $v->name : "";
                            if(isset($v->datatype) &&  $v->datatype == "menu" 
                                && !empty($name) && strtolower($name) == strtolower("Main Language") ) {
                                $param = $v->data;
                                $param1 = explode("\n", $v->param1);
                                $languages[$d->id] = $param1[$param];
                            }
                        }
                    }
                }
            }
        }
        return $languages;
    }
    
    private function getTheCourseECTS(): array {
        $result = array();
        if(count($this->fpCourses) > 0) {
            foreach($this->fpCourses as $d) {
                if($d->id) {
                    $ccf = $this->getCoursesCustomFieldsByCourse($d->id);
                    if(count($ccf) > 0) {
                        foreach($ccf as $v) {
                            $name = ($v->name) ? $v->name : "";
                            if(isset($v->datatype) &&  $v->datatype == "text" 
                                && !empty($name) && strtolower($name) == strtolower("ECTS") ) {
                                $param = $v->data;
                                $param1 = explode("\n", $v->param1);
                                $result[] = $v->data;
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
    
    private static function getLanguageLabel(string $lngcode): string {
        $languages = array("en" => "English", "fr" => "French", "hu" => "Hungarian", "gr" => "Greek", 
            "uk" => "English - UK", "aa" => "Afar", "sq" => "Albanian", "ar" => "Arabic", "bs" => "Bosnian",
            "bg" => "Bulgarian", "hr" => "Croatian", "cs" => "Czech", "da" => "Danish", "nl" => "Dutch", "de" => "German");
        if(array_key_exists($lngcode, $languages)) {
            return $languages[$lngcode];
        }
        return "English";        
    }
    
   public function frontpage_ects_box() {
        $string = "";        
        $ects = $this->getTheCourseECTS();  
        if(count($ects) > 0) {            
            $ects = array_unique($ects);
            sort($ects);
            $string = '<select name="fp-ects-box" class="fp-ects-box fp-select" >';                
                $string .= '<option value="" selected>All</option>';
                $string .= '<option value="1">1</option>';
                foreach($ects as $v) {
                    $string .= '<option value="'.$v.'">'.$v.'</option>';
                }
            $string .= '</select>';
        }
        echo $string;
   }
    
    
    public function frontpage_languages_box() {
        $string = "";        
        $languages = $this->getTheCourseLanguages();
        
        $lng_text = array();
        foreach($languages as $lcode) {
            $lng_text[$lcode] = $this->getLanguageLabel($lcode);
        }
       
        if(count($lng_text) > 0) {            
            $string = '<select id="fp-languages-box" name="fp-languages-box" class="fp-languages-box fp-select">';                
                $string .= '<option value="" selected="selected">All</option>';
                foreach($lng_text as $k => $v) {
                    $string .= '<option value="'.$k.'">'.$v.'</option>';
                }
            $string .= '</select>';
        }
        echo $string;
    }
    
    private function getFrontpageCourseList(string $orderby = "date_desc", string $lang = "none", string $ects = "none") {
        global $CFG, $DB;
        //require_once($CFG->libdir. '/coursecatlib.php');
        
        switch ($orderby) {
            case "date_desc":
                $orderby = "c.timemodified desc";
                break;
            case "date_asc":
                $orderby = "c.timemodified asc";
                break;
            case "title_asc":
                $orderby = "c.fullname asc";
                break;
            case "title_desc":
                $orderby = "c.fullname desc";
                break;
            case "auth_asc":
                $orderby = "c.fullname asc";
                break;
            case "auth_desc":
                $orderby = "c.fullname desc";
                break;
            default:
                $orderby = "c.fullname asc";
                break;
        }
        
        $result = array();
        $resultLang = array();
        $resultEcts = array();
        try {
            $sqlResult = array();
            $sql = '
                SELECT 
                    c.id, c.fullname, c.summary, GROUP_CONCAT(t.name) as tag_name,
                    (
                        SELECT cid.data 
                        FROM custom_info_data as cid 
                        LEFT JOIN custom_info_field as cif on cid.fieldid = cif.id 
                        WHERE cif.objectname = "course" and cid.objectid = c.id and cif.name = "ECTS"
                    ) as ects
                FROM course as c
                LEFT JOIN tag_instance as ti on ti.itemid = c.id
                LEFT JOIN tag as t on ti.tagid = t.id
                WHERE
                    c.visible = 1 and c.id != 1 ';
            $sql .= ' GROUP BY
                    c.id, c.fullname, c.summary ';
            $sql .= ' ORDER BY '.$orderby;
            $sqlResult = $DB->get_records_sql($sql);
            
            if(!empty($lang)) {
                $languages = $this->getTheCourseLanguages();
                $cLang = array();
                foreach($languages as $k => $v) {
                    if($v == $lang) {
                        $cLang[] = $k;
                    }else if($lang == "none") {
                        $cLang[] = $k;
                    }
                }
                
                if(count($cLang) > 0) {
                    foreach($sqlResult as $v) {
                        if(in_array($v->id, $cLang)) {
                            $resultLang[$v->id] = $v; 
                        }
                    }
                }
            }
            
            if(!empty($ects)) { 
                foreach($sqlResult as $k => $v) {
                    $v_ects = ($v->ects) ? $v->ects : "1";
                    if($v_ects == $ects ) {
                        $resultEcts[$k] = $v;
                    }else if($ects == "none") {
                         $resultEcts[$k] = $v;
                    }
                }
            }

            if(count($resultEcts) > 0 || count($resultLang) > 0) {
                
                if(count($resultEcts) > 0 && count($resultLang) > 0) {
                    foreach (array_intersect(array_keys($resultEcts), array_keys($resultLang)) as $key) {
                        $result[$key] = $resultEcts[$key];
                    }
                }else {
                    if(count($resultLang) > 0 && ($ects == "none" || empty($ects)) ) {
                        $result = $resultLang;
                    }else if(count($resultEcts) > 0 && ($lang == "none" || empty($lang))) {
                        $result = $resultEcts;
                    }else {
                        $result = array();
                    }
                    
                }
                
            }else {
                $result = $sqlResult;
            }
            
            
        } catch (Exception $ex) {
            $result = array();                    
        }
        
        return $result;
    }
    
    
    public function frontpage_dh_courses() {
        global $CFG, $OUTPUT, $DB;
        //require_once($CFG->libdir. '/coursecatlib.php');
        $orderby = "date_desc";
        $languages = "";
        $ects = "";
        
        if(isset($_GET['orderby'])) { $orderby = $_GET['orderby']; }
        if(isset($_GET['languages'])) { $languages = $_GET['languages'];}
        if(isset($_GET['ects'])) { $ects = $_GET['ects']; }
        
        $data = array();
        $data = $this->getFrontpageCourseList($orderby, $languages, $ects);
       
        $header = '<div id="frontpage-course-list">';            
            $header .= '<div class="courses frontpage-course-list-all">';
                $header .= '<div class="container-fluid">';
                    $header .= '<div class="row">'; 
                        $header .= '<div class="col-xs-12 col-lg-12">';
                            $header .= '<div class="card-deck justify-content-center d-flex">';
                    
                            $footer = '</div>';
                        $footer .= '</div>';
                    $footer .= '</div>';
                $footer .= '</div>';
            $footer .= '</div>';
        $footer .= '</div>';
                
        $content = '';
        $morecourses = false;
        if(!empty($data)){ 
            $cocnt = 1;
            foreach ($data as $d) {
                $course = get_course($d->id);
                $this->courseCF = $this->getCourseCustomFields($d->id);
                
                $authors = "";
                (isset($this->courseCF['Authors']->data)) ? $authors = $this->courseCF['Authors']->data : $authors = "";
                
                if ($course instanceof stdClass) {
                    //require_once($CFG->libdir. '/coursecatlib.php');
                    $course = new core_course_list_element($course);
                }
                
                $noimgurl = $OUTPUT->image_url('no-image', 'theme');
                $courseurl = new moodle_url('/course/view.php', array('id' => $d->id ));
                
                $imgurl = '';
                $context = context_course::instance($course->id);
                    foreach ($course->get_course_overviewfiles() as $file) {
                        $isimage = $file->is_valid_image();
                        $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                        '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                        if (!$isimage) {
                            $imgurl = $noimgurl;
                        }
                    }
                    if (empty($imgurl)) {
                        $imgurl = $noimgurl;
                    }
                    $numOfTitleChars = 50;
                    $numOfTagChars = 40;
                    $numOfAuthorChars = 60;
                    
                    $courseTitle = substr(strip_tags($course->get_formatted_name(), "<a><br><img>"), 0, $numOfTitleChars);
                    
                    (strlen($course->get_formatted_name()) > 50) ? $courseTitle = $courseTitle."..." : $courseTitle;
                                        
                    if(!empty($authors)) {
                        $authors = substr(strip_tags($authors, "<a><br><img>"), 0, $numOfAuthorChars);
                        (strlen($authors) > 45) ? $authors = substr($authors,0,45)."..." : $authors;
                    }
                    

                    $icon = "fa-angle-double-right";
                    if (right_to_left()) {
                        $icon = "fa-angle-double-left";
                    }
                    
                    $content .= '<div class="cards col-fp-coursebox">';
                        $content .=  '<div class="fp-coursebox">';
                        
                            $content .= '<div class="fp-coursethumb">';
                                $content .= '<a href="'.$courseurl.'"><img src="'.$imgurl.'" width="243" height="165" alt="" class="img-responsive"></a>';
                            $content .= '</div>';
                            
                            $content .= '<div class="fp-courseinfo-upper-info">';
                                if($d->tag_name) {
                                    $tags = explode(",", $d->tag_name);
                                    $content .= '<div class="fp-courseinfo-tags">';
                                        if(count($tags) > 0) {
                                            $i = 0;
                                            $sum = 0;
                                            $content .= '<i class="fa fa-tag"></i>';
                                            foreach($tags as $t) {
                                                $sum = strlen($t) + $sum;
                                                if($i >= 3 || $sum > 35) { break; }
                                                $content .= "<a href='/tag/index.php?tc=1&tag=".$t."' class='fp-courseinfo-tag'>".$t."</a> ";
                                                $i++;
                                            }
                                            
                                        }
                                        
                                    $content .= '</div>';
                                }
                                $content .= '<div class="fp-courseinfo-ects">';
                                    $ects = 1;
                                    if($d->ects) {
                                        $ects = $d->ects;
                                    }
                                    $content .= $ects.' ECTS';
                                $content .= '</div>';
                                    
                                
                            $content .= '</div>';
                            
                            $content .= '<div class="fp-courseinfo">';
                                $content .= '<div class="fp-courseinfo-title">';
                                    $content .= '<h5><a href="'.$courseurl.'" title="'.$course->get_formatted_name().'">'.$courseTitle.'</a></h5>';
                                    if(!empty($authors)) {
                                        $content .= '<span class="fp-author-text">'.$authors.'</span>';
                                    }
                                $content .= '</div>';
                                
                            $content .= '</div>';
                                                         
                        $content .= '</div>';
                    $content .= '</div>';
                    /*
                    if($cocnt == 6 ) {
                        //$morecourses = true;
                        $content .= "<div class='more_courses w-100 text-center font-weight-bold mb-10'><a href='#' id='show_more_course'>Show More</a></div>";                        
                        $content .= "<div class='hide_courses w-100 text-center font-weight-bold mb-10'><a href='#' id='hide_more_course'>Hide Courses</a></div>";
                    }
                    
                     
                    if (($cocnt == 6)) {
                        $content .= '</div>';
                        $content .= '<div class="clearfix hidexs"></div>';
                        $content .= '<div class="card-deck justify-content-center d-flex more_courses_div">';
                    }
                    */
                    $cocnt++;
                }
                
            }
        //}
            /*
            if($morecourses) {
                $content .= '</div>';
            }*/
        $coursehtml = $header.$content.$footer;
        echo $coursehtml;
    }
    
    /**
     * Renderer the course cat course box from the parent
     *
     * @param coursecat_helper $chelper
     * @param int $course
     * @param string $additionalclasses
     * @return $content
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $CFG;
        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            //require_once($CFG->libdir. '/coursecatlib.php');
            $course = new core_course_list_element($course);
        }
        $content = '';
        $classes = trim('coursebox clearfix '. $additionalclasses);
        if ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $nametag = 'h3';
        } else {
            $classes .= ' collapsed';
            $nametag = 'div';
        }
        // Coursebox.
        if (empty($course->get_course_overviewfiles())) {
            $coursecontent = "content-block";
        } else {
            $coursecontent = "";
        }
        $content .= html_writer::start_tag('div', array(
            'class' => $classes.' '.$coursecontent,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
        ));
        $content .= html_writer::start_tag('div', array('class' => 'info'));
        // Course name.
        $coursename = $chelper->get_course_formatted_name($course);
        $coursenamelink = html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                                            $coursename, array('class' => $course->visible ? '' : 'dimmed'));
        $content .= html_writer::tag($nametag, $coursenamelink, array('class' => 'coursename'));
        // If we display course in collapsed form but the course has summary or course contacts, display the link to the info page.
        $content .= html_writer::start_tag('div', array('class' => 'moreinfo'));
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            if ($course->has_summary() || $course->has_course_contacts() || $course->has_course_overviewfiles()) {
                $url = new moodle_url('/course/info.php', array('id' => $course->id));
                $image = html_writer::empty_tag('img', array('src' => $this->output->image_url('i/info'),
                    'alt' => $this->strings->summary));
                $content .= html_writer::link($url, $image, array('title' => $this->strings->summary));
                // Make sure JS file to expand course content is included.
                $this->coursecat_include_js();
            }
        }
        $content .= html_writer::end_tag('div'); // Moreinfo.
        // Print enrolmenticons.
        if ($icons = enrol_get_course_info_icons($course)) {
            $content .= html_writer::start_tag('div', array('class' => 'enrolmenticons'));
            foreach ($icons as $pixicon) {
                $content .= $this->render($pixicon);
            }
            $content .= html_writer::end_tag('div'); // Enrolmenticons.
        }
        $content .= html_writer::end_tag('div'); // Info.
        if (empty($course->get_course_overviewfiles())) {
            $class = "content-block";
        } else {
            $class = "";
        }
        $content .= html_writer::start_tag('div', array('class' => 'content '.$class));
        $content .= $this->coursecat_coursebox_content($chelper, $course);
        $content .= html_writer::end_tag('div'); // Content.
        $content .= html_writer::end_tag('div'); // Coursebox.
        return $content;
    }
    
     
}