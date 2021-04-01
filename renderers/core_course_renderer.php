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
 *
 * @package   theme_lambda
 * @copyright 2019 redPIthemes
 *
 */
 
 defined('MOODLE_INTERNAL') || die();
 
 require_once($CFG->dirroot . "/course/renderer.php");
 
 class theme_lambda_core_course_renderer extends core_course_renderer {
	
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        global $CFG;
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            return '';
        }
        if ($course instanceof stdClass) {
            require_once($CFG->libdir. '/coursecatlib.php');
            $course = new course_in_list($course);
        }
        $content = '';
		
		// display course overview files
        $contentimages = $contentfiles = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {
                $contentimages .= '<div class="courseimage" style="background-image: url('.$url.');"></div>';
            } else {
                $image = $this->output->pix_icon(file_file_icon($file, 24), $file->get_filename(), 'moodle');
                $filename = html_writer::tag('span', $image, array('class' => 'fp-icon')).
                        html_writer::tag('span', $file->get_filename(), array('class' => 'fp-filename'));
                $contentfiles .= html_writer::tag('span',
                        html_writer::link($url, $filename),
                        array('class' => 'coursefile fp-filename-icon'));
            }
        }
        $content .= $contentimages. $contentfiles;

        // display course summary
            $content .= html_writer::start_tag('div', array('class' => $course->visible ? 'summary' : 'summary dimmed'));
			
			$coursename = $chelper->get_course_formatted_name($course);
        	$coursenamelink = html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                                            $coursename, array('class' => $course->visible ? '' : 'dimmed'));
        	$content .= html_writer::tag('h3', $coursenamelink, array('class' => 'coursename'));
			
            $content .= '<div>'.$chelper->get_course_formatted_summary($course,
                    array('overflowdiv' => true, 'noclean' => true, 'para' => false)).'</div>';
			
            $content .= html_writer::end_tag('div'); // .summary

        // display course contacts. See course_in_list::get_course_contacts()
        if ($course->has_course_contacts()) {
			$content .= '<div class="teachers">';
			$current_role = '';
			$i = 0;
			$list_course_contacts = $course->get_course_contacts();
			
            foreach ($list_course_contacts as $userid => $coursecontact) {
				if ($i == 0) {
					$current_role = $coursecontact['rolename'];
					$content .= $current_role.': ';
					$name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $coursecontact['username']);
					$content .= $name;
					}
				if (($i > 0) AND ($coursecontact['rolename'] == $current_role)) {
					$content .= ', ';
					$name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $coursecontact['username']);
					$content .= $name;
				}
				else if ($i > 0) {
					$content .= '</div>';
					$content .= '<div class="teachers">';
					$current_role = $coursecontact['rolename'];
					$content .= $current_role.': ';
					$name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $coursecontact['username']);
					$content .= $name;
				}
				$i++;
            }
            $content .= '</div>'; // .teachers
        }
		
		$content .= '<div class="course-btn"><p><a class="btn btn-primary" href="'.new moodle_url('/course/view.php', array('id' => $course->id)).'">'.get_string('entercourse').'</a></p></div>';

        // display course category if necessary (for example in search results)
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_EXPANDED_WITH_CAT) {
            if ($CFG->version < 2018120300) {
				require_once($CFG->libdir. '/coursecatlib.php');
            	if ($cat = coursecat::get($course->category, IGNORE_MISSING)) {
                	$content .= html_writer::start_tag('div', array('class' => 'coursecat'));
                	$content .= get_string('category').': '.
                        html_writer::link(new moodle_url('/course/index.php', array('categoryid' => $cat->id)),
                                $cat->get_formatted_name(), array('class' => $cat->visible ? '' : 'dimmed'));
                	$content .= html_writer::end_tag('div'); // .coursecat
            	}
			} else {
            	if ($cat = core_course_category::get($course->category, IGNORE_MISSING)) {
                	$content .= html_writer::start_tag('div', array('class' => 'coursecat'));
                	$content .= get_string('category').': '.
                        html_writer::link(new moodle_url('/course/index.php', array('categoryid' => $cat->id)),
                                $cat->get_formatted_name(), array('class' => $cat->visible ? '' : 'dimmed'));
                	$content .= html_writer::end_tag('div'); // .coursecat
				}
			}
        }
        return $content;
    }
    
    
    public function frontpage_dh_courses(bool $isEnglish = true) {
        global $CFG, $OUTPUT, $DB, $USER;
        //require_once($CFG->libdir. '/coursecatlib.php');
        $orderby = "date_desc";
        $languages = "";
        $ects = "";
        $header = "";
        $footer = "";
        
        if($isEnglish){
            if(isset($_GET['orderby_en'])) { $orderby = $_GET['orderby_en']; }
            if(isset($_GET['ects_en'])) { $ects = $_GET['ects_en']; }
        }else {
            if(isset($_GET['orderby'])) { $orderby = $_GET['orderby']; }
            if(isset($_GET['languages'])) { $languages = $_GET['languages'];}
            if(isset($_GET['ects'])) { $ects = $_GET['ects']; }
        }
        
        $data = array();
        $userid = 0;
        $userid = $USER->id;
        $data = $this->getFrontpageCourseList($isEnglish, $orderby, $languages, $ects, $userid, true);
       
        $header .= '<div class="row-fluid">';
        
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
                $language = "";
 
                $param = $this->courseCF["Main Language"]->data;
                $param1 = explode("\n", $this->courseCF["Main Language"]->param1);
                $language = $param1[$param];

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
                    $numOfTitleChars = 80;
                    $numOfTagChars = 50;
                    $numOfAuthorChars = 60;
                    
                    $courseTitle = substr(strip_tags($course->get_formatted_name(), "<a><br><img>"), 0, $numOfTitleChars);
                    (strlen($course->get_formatted_name()) > 80) ? $courseTitle = $courseTitle."..." : $courseTitle;
                                        
                    if(!empty($authors)) {
                        $authors = substr(strip_tags($authors, "<a><br><img>"), 0, $numOfAuthorChars);
                        (strlen($authors) > 60) ? $authors = $authors."..." : $authors;
                    }
                    

                    $icon = "fa-angle-double-right";
                    if (right_to_left()) {
                        $icon = "fa-angle-double-left";
                    }
                    
                    $content .= '<div class="coursebox span3">';
                        $content .= '<div class="content">';
                        
                            $content .= '<div class="courseimage" style="background-image: url('.$imgurl.');">';
                            $content .= '</div>';

                            $content .= '<div class="summary">';
                                $content .= '<div width="100%" style="height:50px"> ';
                                    $content .= '<h5><a href="'.$courseurl.'" title="'.$course->get_formatted_name().'">'.$courseTitle.'</a></h5>';
                                $content .= '</div>';
                                
                                    $content .= '<div width="100%" style="height:48px"> ';
                                    
                                        $content .= '<div class="fp-authors-div"> ';
                                            $content .= '<i class="fas fa-pen"></i>&nbsp;';
                                            if(!empty($authors)) {
                                                $content .= '<span class="">'.$authors.'</span>';
                                            }
                                        $content .= '</div>';
                                    
                                    $content .= '</div>';
                                
                                $content .= '<div width="100%" style="height:22px" > ';
                                
                                    $content .= '<div class="fp-language-div"> ';
                                        $content .= '<i class="fa fa-globe"></i>&nbsp;';
                                        if($language) {
                                            $content .= strtoupper($language);
                                        }
                                    $content .= '</div>';
                                    
                                    $ects = 1;
                                    $content .= '<div class="fp-ects-div"> ';
                                        $content .= '<i class="fas fa-user-graduate"></i>&nbsp;';
                                        if($d->ects) {
                                            $ects = $d->ects;
                                        }
                                        $content .= $ects.' ECTS';
                                    $content .= '</div>';
                                    //
                                $content .= '</div>';
                                
                                $content .= '<div width="100%"  style="height:23px"> ';
                                    $content .= '<i class="fa fa-tag"></i>&nbsp;';
                                    if($d->tag_name) {
                                        $tags = explode(",", $d->tag_name);

                                            if(count($tags) > 0) {
                                                $i = 0;
                                                
                                                foreach($tags as $t) {
                                                    if($i >= 3) { break; }
                                                    $content .= "<a href='/tag/index.php?tc=1&tag=".$t."' class='fp-courseinfo-tag'>".$t."</a> ";
                                                    $i++;
                                                }
                                            }
                                    }
                                $content .= '</div>';

                            $content .= '</div>';
                            
                            $content .= '<div class="course-btn" style="margin-bottom: 5px; margin-top: 0px;">';
                                $content .= '<a href="'.$courseurl.'" class="btn btn-primary">Click to enter this course</a>';
                            $content .= '</div>';
                            
                            
                        $content .= '</div>';
                        
                    $content .= '</div>';
                    $cocnt++;
                }
                
            }
        $coursehtml = $header.$content.$footer;
        echo $coursehtml;
    }
    
    
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
                                $languages[$d->id] = rtrim($param1[$param]);
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
        $lngcode = rtrim($lngcode);
        $languages = array("en" => "English", "fr" => "French", "hu" => "Hungarian", "el" => "Greek", 
            "uk" => "English - UK", "aa" => "Afar", "sq" => "Albanian", "ar" => "Arabic", "bs" => "Bosnian",
            "bg" => "Bulgarian", "hr" => "Croatian", "cs" => "Czech", "da" => "Danish", "nl" => "Dutch", "de" => "German", "es" => "Spanish");
        
        if(array_key_exists($lngcode, $languages)) {
            return $languages[$lngcode];
        }
        return "English";        
    }
    
   public function frontpage_ects_box(bool $isEnglish = true) {
        $string = "";        
        if($isEnglish) { $en = '-en';} else{ $en ='';}
        $ects = $this->getTheCourseECTS();  
        if(count($ects) > 0) {            
            $ects = array_unique($ects);
            sort($ects);
            $string = '<select name="fp-ects-box'.$en.'" class="fp-ects-box'.$en.' fp-select" >';                
                $string .= '<option value="" selected>All</option>';
                $string .= '<option value="1">1</option>';
                foreach($ects as $v) {
                    $string .= '<option value="'.$v.'">'.$v.'</option>';
                }
            $string .= '</select>';
        }
        echo $string;
   }
    
    
    public function frontpage_languages_box(bool $isEnglish = true) {
        $string = "";        
        if($isEnglish) { $en = '-en';} else{ $en ='';}
        $languages = $this->getTheCourseLanguages();
        
        $lng_text = array();
        foreach($languages as $lcode) {
            $lcode = rtrim($lcode);
            $lng_text[$lcode] = $this->getLanguageLabel($lcode);
        }
       
        if(count($lng_text) > 0) {            
            $string = '<select id="fp-languages-box" name="fp-languages-box'.$en.'" class="fp-languages-box fp-select">';                
                $string .= '<option value="" selected="selected">All</option>';
                foreach($lng_text as $k => $v) {
                    $string .= '<option value="'.rtrim($k).'">'.$v.'</option>';
                }
            $string .= '</select>';
        }
        echo $string;
    }
    
    private function getFrontpageCourseList(bool $isEnglish = true, string $orderby = "date_desc", string $lang = "none", string $ects = "none", int $userid = 0, bool $devsite = true) {
        global $CFG, $DB, $USER;

        //require_once($CFG->libdir. '/coursecatlib.php');
        if($devsite){
            $user_join = "";
            $user_where = "";
            //susan and costas
            if( $userid == 84 || $userid == 86 ) {
                $userid = 0;
            }

            if( $userid != 0 && !is_siteadmin() ) {
                $user_join = "  LEFT JOIN context as co ON c.id = co.instanceid 
                    left join role_assignments as r on r.contextid = co.id 
                    LEFT JOIN user as u on u.id = r.userid  ";
                $user_where = " u.id = $userid and ";
            }
        }
        
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
        
        //get the english course languages
        $encourseIds = implode(',',(array_keys($this->getTheCourseLanguages(), 'en')));
        
        
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
                    ) as ects,
                ';   
                
                $sql .='(
                        SELECT cid2.data 
                        FROM custom_info_data as cid2 
                        LEFT JOIN custom_info_field as cif on cid2.fieldid = cif.id 
                        WHERE cif.objectname = "course" and cid2.objectid = c.id and cif.name = "Main Language" ';
                
                       
                $sql .= '        
                    ) as lang
                    ';
                
                $sql .= '
                FROM course as c
                LEFT JOIN tag_instance as ti on ti.itemid = c.id
                LEFT JOIN tag as t on ti.tagid = t.id';
            if($devsite){
                if(!empty($user_join)) {
                    $sql .= $user_join;
                }
            }
            
            
            $sql .= ' WHERE ';
            if($devsite){
                if(!empty($user_where)) {
                    $sql .= $user_where;
                }
            }
            $sql .= ' c.visible = 1 and c.id != 1 ';
            
            if($isEnglish) {
                $sql .= '  and c.id IN ('.(string)$encourseIds.') ';
            }else{
                $sql .= '  and c.id not IN ('.(string)$encourseIds.') ';
            }
          
            $sql .= ' GROUP BY
                    c.id, c.fullname, c.summary ';
            $sql .= ' ORDER BY '.$orderby;
            
            //echo $sql;
            
            $sqlResult = $DB->get_records_sql($sql);
            
            foreach($sqlResult as $k => $v) {
                if($v->lang == null){
                    unset($sqlResult[$k]);
                }
            }
            
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

}