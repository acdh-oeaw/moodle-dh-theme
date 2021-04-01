<?php
/**
 * @package    theme_klass
 * @copyright  2015 onwards Nephzat Dev Team (http://www.nephzat.com)
 * @authors    Nephzat Dev Team , nephzat.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */ 
	
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/course/renderer.php");

class theme_lambda_core_course_renderer extends core_course_renderer {

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
    
    
    
    private function getFrontpageCourseList(string $orderby = "date_desc", string $lang = "none", string $ects = "none", int $userid = 0) {
        global $CFG, $DB, $USER;

        //require_once($CFG->libdir. '/coursecatlib.php');
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
                LEFT JOIN tag as t on ti.tagid = t.id';
            
            if(!empty($user_join)) {
                $sql .= $user_join;
            }
            
            $sql .= ' WHERE ';
            if(!empty($user_where)) {
                $sql .= $user_where;
            }
            $sql .= ' c.visible = 1 and c.id != 1 ';
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
        global $CFG, $OUTPUT, $DB, $USER;
        //require_once($CFG->libdir. '/coursecatlib.php');
        $orderby = "date_desc";
        $languages = "";
        $ects = "";
        
        if(isset($_GET['orderby'])) { $orderby = $_GET['orderby']; }
        if(isset($_GET['languages'])) { $languages = $_GET['languages'];}
        if(isset($_GET['ects'])) { $ects = $_GET['ects']; }
        
        $data = array();
        $userid = $USER->id;
        $data = $this->getFrontpageCourseList($orderby, $languages, $ects, $userid);
       
        $header = '<div id="frontpage-course-list">';            
            $header .= '<div class="courses frontpage-course-list-all">';
                $header .= '<div class="container-fluid">';
                    $header .= '<div class="row">'; 
                        $header .= '<div class="col-xs-12 col-lg-12">';
                            $header .= '<div class="card-deck justify-content-center d-flex itt">';
                    
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
    
    
    public function new_courses() {
        /* New Courses */	
        global $CFG,$OUTPUT;
        $new_course = get_string('newcourses','theme_klass');

        $header = '<div id="frontpage-course-list">
                <h2>'.$new_course.'</h2>
                        <div class="courses frontpage-course-list-all">
                                <div class="row-fluid">';

        $footer = '</div>
                        </div>
        </div>';			
        $co_cnt = 1;
        $content = '';

        if ($ccc = get_courses('all', 'c.id DESC,c.sortorder ASC', 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                if($co_cnt>8)
                {
                                                break;
                }
                if($cc->visible=="0" || $cc->id=="1") {
                                        continue;
                }
                $course_id = $cc->id;
                $course = get_course($course_id);

                $noimg_url = $OUTPUT->pix_url('no-image', 'theme');
                $course_url = new moodle_url('/course/view.php',array('id' => $course_id ));

                if ($course instanceof stdClass) {
                                                                                require_once($CFG->libdir. '/coursecatlib.php');
                                                                                $course = new course_in_list($course);
                }

                $img_url = '';			
                $context = context_course::instance($course->id);

                foreach ($course->get_course_overviewfiles() as $file) {
                                                $isimage = $file->is_valid_image();
                                                $img_url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                                                                                                                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                                                                                                                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                                                if (!$isimage) {
                                                                                $img_url = $noimg_url;
                                                }
                }

                if(empty($img_url)) {
                                                $img_url = $noimg_url;
                }		

                $content .= '<div class="span3">
                <div class="fp-coursebox">
                                <div class="fp-coursethumb">
                                                <a href="'.$course_url.'">
                                                                <img src="'.$img_url.'"  alt="'.$course->fullname.'">
                                                        </a>
                                        </div>
                                        <div class="fp-courseinfo">
                                                <h5><a href="'.$course_url.'">'.$course->fullname.'</a></h5>
                                                <div class="readmore"></div>	
                                        </div>
                        </div>
                </div>';


                if(($co_cnt%4)=="0")
                {
                                                $content .= '<div class="clearfix hidexs"></div>';
                }

                $co_cnt++;
            }
        }				

        $course_html = $header.$content.$footer;
        $frontpage = isset($CFG->frontpage)?$CFG->frontpage:'';
        $frontpageloggedin = isset($CFG->frontpageloggedin)?$CFG->frontpageloggedin:'';

        $f1_pos = strpos($frontpage,'6');
        $f2_pos = strpos($frontpageloggedin,'6');
        $btn_html = '';
        if($co_cnt<=1 && !$this->page->user_is_editing() && has_capability('moodle/course:create', context_system::instance()))
        {
                                        $btn_html = $this->add_new_course_button();
        }

        if (!isloggedin() or isguestuser()) {
                                        if($f1_pos===false)
                                        {
                                            if($co_cnt>1)
                                                                        {
                                                                                                        echo $course_html;
                                                                        }
                                        }
        }else{
                                        if($f2_pos===false)
                                        {
                                                                        echo $course_html."<br/>".$btn_html;
                                        }
        }

    }



    public function frontpage_available_courses() {
        /* available courses */
        global $CFG,$OUTPUT, $DB;
        require_once($CFG->libdir. '/coursecatlib.php');

        $chelper = new coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->
            set_courses_display_options(array(
                'recursive' => true,
                'limit' => $CFG->frontpagecourselimit,
                'viewmoreurl' => new moodle_url('/course/index.php'),
                'viewmoretext' => new lang_string('fulllistofcourses')));

        $chelper->set_attributes(array('class' => 'frontpage-course-list-all'));
        $courses = coursecat::get(0)->get_courses($chelper->get_courses_display_options());
        $totalcount = coursecat::get(0)->get_courses_count($chelper->get_courses_display_options());

        
        /* get the courses which have the Workshop ID */  
        $workshoptags = array();
        $workshopTags = $DB->get_records_sql(
                'SELECT 
                    ti.itemid as tag_courseid
                FROM {tag} as t
                LEFT JOIN 
                    {tag_instance} as ti on t.id = ti.tagid
                Where 
                    rawname = "Workshop"');

        if(!empty($workshopTags)){
            $workTags = array();        
        
            foreach ($workshopTags as $workT){            
                array_push($workTags, $workT->tag_courseid);
            }
        }        
        
        $workshopIds = "";
        $workshopIds = implode(",",$workTags);
       
        foreach($workTags as $val){       
            $activeWorkshops = $DB->get_records_sql(
                'SELECT 
                    id
                FROM {course}                
                Where 
                    visible = 1 and id = '.$val);
            
            if(count($activeWorkshops) > 0){                
                $workshop_ids[] = $val;
            }
        }
        
     
        /*
       $courses = $DB->get_records_sql('SELECT 
            c.id,c.shortname,c.visible , ctx.id AS ctxid, ctx.path AS ctxpath, ctx.depth AS ctxdepth, ctx.contextlevel AS ctxlevel, ctx.instanceid AS ctxinstance
            FROM course c 
            LEFT JOIN context ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = '.CONTEXT_COURSE.') 
            WHERE c.id NOT IN ('.$workshopIds.') and c.visible = 1
            ORDER BY c.id,c.shortname,c.visible        
          ');
      */
        
        foreach(array_keys($courses) as $val){
            $activeCourses = $DB->get_records_sql(
                'SELECT 
                    id
                FROM {course}                
                Where 
                    visible = 1 and id = '.$val);
            
            if(count($activeCourses) > 0){                
                $course_ids[] = $val;
            }
        }
      
        $course_ids = array_diff($course_ids, $workshop_ids);
        
        $new_course = get_string('availablecourses');

        $header = '
            <div id="frontpage-course-list">
                <a name="courses"></a>                
                <h2 style="font-family: arvoregular;"><b>Courses</b></h2>
                Courses represent the equivalent level of student effort as a 5 or 10 ECTS module.<br><br>
                <div class="container-fluid frontpage_course_boxes">
                    <div class="courses frontpage-course-list-all">
                        <div class="container-fluid">
                            <div class="row-fluid">
';

        $footer = '</div>
                    </div>
                    </div>
                    </div>
        </div>';			
        $co_cnt = 1;
        $content = '';
      
        if ($ccc = get_courses('all', 'c.sortorder ASC', 'c.id,c.shortname,c.visible, c.summary')) {
            
            foreach ($course_ids as $course_id) {
                $course = get_course($course_id);

                $noimg_url = $OUTPUT->pix_url('no-image', 'theme');
                $course_url = new moodle_url('/course/view.php',array('id' => $course_id ));

                if ($course instanceof stdClass) {
                    require_once($CFG->libdir. '/coursecatlib.php');
                    $course = new course_in_list($course);
                }

                $img_url = '';			
                $context = context_course::instance($course->id);

                foreach ($course->get_course_overviewfiles() as $file) {
                    $isimage = $file->is_valid_image();
                    $img_url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                                                                                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                    if (!$isimage) {
                        $img_url = $noimg_url;
                    }
                }

                if(empty($img_url)) {
                    $img_url = $noimg_url;
                }
                
                $descriptionText = "";
                if($course->summary){
                    $descriptionText = substr(strip_tags($course->summary), 0, 220).'...';                    
                }else {
                    $descriptionText = "This course has no description";
                }
                
                $content .='
                 <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 frontpage_one_card">
                 
                    <div class="info-card">
                       <div class="panel panel-default" id="info_card_panel" >
                            <div class="front">
                                <div class="panel-heading" id="info_card_panel_heading" >
                                    <center><a href="'.$course_url.'">
                                        <img src="'.$img_url.'"  alt="'.$course->fullname.'" class="img-responsive pull-left" style="width:99%; height:auto; height:180px;" >
                                    </a>
                                </div>
                                <div class="panel-body" id="info_card_panel_body" >
                                    <p><a href="'.$course_url.'" style="color:white; font-size: 18px;">'.$course->fullname.'</a></p></center>
                                </div>
                            </div>
                            
                             <div class="back">
                                <div class="panel-heading" id="info_card_panel_heading_back">                                     
                                        '.$descriptionText.'
                                </div>
                                <div class="panel-body" id="info_card_panel_body" >
                                    <p><a href="'.$course_url.'" style="color:white; font-size: 18px;">'.$course->fullname.'</a></p></center>
                                </div>
                               
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>';
                
                /*if(($co_cnt%5)=="0")
                {                    
                    $content .='</div></div><div class="container-fluid">
                    <div class="row-fluid">'
                            . '';
                    
                }
*/
                $co_cnt++;

            }
        }				

        $course_html = $header.$content.$footer;
        echo $course_html;
        
        
        /* WORKSHOPS    */
        
      
        $header = '     
            <div id="frontpage-course-list">
                <a name="workshops"></a>
                
                <h2 style="font-family: arvoregular;"><b>Workshops</b></h2>
                 Workshops should take anywhere from several hours to two days to compete. They represent a short, focused introduction to a topic, method, or approach.<br><br>
                <div class="container-fluid frontpage_course_boxes">
                    <div class="courses frontpage-course-list-all">
                        <div class="container-fluid">
                            <div class="row-fluid">
';

        $footer = '</div>
                    </div>
                    </div>
                    </div>
        </div>';
        
        $co_cnt = 1;
        $content = '';

        if ($ccc = get_courses('all', 'c.sortorder ASC', 'c.id,c.shortname,c.visible, c.summary')) {
            
            if(!empty($workshop_ids)){               

                foreach ($workshop_ids as $course_id) {
                    if($course_id){
                        $course = get_course($course_id);

                        $noimg_url = $OUTPUT->pix_url('no-image', 'theme');
                        $course_url = new moodle_url('/course/view.php',array('id' => $course_id ));
                        
                        if ($course instanceof stdClass) {
                            require_once($CFG->libdir. '/coursecatlib.php');
                            $course = new course_in_list($course);
                        }

                        $img_url = '';			
                        $context = context_course::instance($course->id);

                        foreach ($course->get_course_overviewfiles() as $file) {
                            $isimage = $file->is_valid_image();
                            $img_url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                                                                                            $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                            if (!$isimage) {
                                $img_url = $noimg_url;
                            }
                        }
                        
                        if(empty($img_url)) {
                            $img_url = $noimg_url;
                        }		

                        $descriptionText = "";
                        if($course->summary){
                            $descriptionText = substr(strip_tags($course->summary), 0, 220).'...';
                        }else {
                            $descriptionText = "This course has no description";
                        }

                        $content .='
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 frontpage_one_card">

                           <div class="info-card">
                               <div class="panel panel-default" id="info_card_panel" >
                                   <div class="front">
                                       <div class="panel-heading" id="info_card_panel_heading" >
                                           <center><a href="'.$course_url.'">
                                               <img src="'.$img_url.'"  alt="'.$course->fullname.'" class="img-responsive pull-left" style="width:99%; height:auto; height:180px;" >
                                           </a>
                                       </div>
                                       <div class="panel-body" id="info_card_panel_body" >
                                           <p><a href="'.$course_url.'" style="color:white; font-size: 18px;">'.$course->fullname.'</a></p></center>
                                       </div>
                                   </div>

                                    <div class="back">
                                       <div class="panel-heading" id="info_card_panel_heading_back">                                
                                               '.$descriptionText.'
                                       </div>
                                       <div class="panel-body" id="info_card_panel_body" >
                                           <p><a href="'.$course_url.'" style="color:white; font-size: 18px;">'.$course->fullname.'</a></p></center>
                                       </div>

                                   </div>

                               </div>

                           </div>

                       </div>';
/*
                       if(($co_cnt%5)=="0")
                       {                    
                           $content .='</div></div><div class="container-fluid">
                           <div class="row-fluid">'
                                   . '';
                       }*/
                       $co_cnt++;
                    }
                }
            } else {
                $header = "";
                $content = "";
                $footer = "";
            }            
        }				

        $course_html = $header.$content.$footer;
        echo $course_html;

        if (!$totalcount && !$this->page->user_is_editing() && has_capability('moodle/course:create', context_system::instance())) {
            // Print link to create a new course, for the 1st available category.
            echo $this->add_new_course_button();
        }

    }
				
}
