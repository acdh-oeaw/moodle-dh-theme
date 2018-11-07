<?php
/**
 * @package    theme_dariahteach
 * @copyright  2017 ACDH
 * @authors    ACDH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */ 
	
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/course/renderer.php");

class theme_dariahteach_core_course_renderer extends core_course_renderer {

    public function new_courses() {
        /* New Courses */	
        global $CFG,$OUTPUT;
        $new_course = get_string('newcourses','theme_dariahteach');

        $header = '
            <div id="frontpage-course-list">
                <h2>'.$new_course.'</h2>
                <div class="courses frontpage-course-list-all">
                    <div class="row-fluid">';

        $footer = '
                </div>
            </div>
        </div>';

        $co_cnt = 1;
        $content = '';

        if ($ccc = get_courses('all', 'c.id DESC,c.sortorder ASC', 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                if($co_cnt>8){  break; }
                if($cc->visible=="0" || $cc->id=="1") { continue; }
                $course_id = $cc->id;
                $course = get_course($course_id);

                $noimg_url = $OUTPUT->image_url('no-image', 'theme');
                $course_url = new moodle_url('/course/view.php',array('id' => $course_id ));

                if ($course instanceof stdClass) {
                    require_once($CFG->libdir. '/coursecatlib.php');
                    $course = new course_in_list($course);
                }

                $img_url = '';			
                $context = context_course::instance($course->id);

                foreach ($course->get_course_overviewfiles() as $file) {
                    $isimage = $file->is_valid_image();
                    $img_url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/'. $file->get_contextid(). '/'. $file->get_component(). '/'. $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                    if (!$isimage) { $img_url = $noimg_url; }
                }

                if(empty($img_url)) { $img_url = $noimg_url; }		

                $content .= '
                    <div class="span3">
                        <div class="fp-coursebox">
                            <div class="fp-coursethumb">
                                <a href="'.$course_url.'"> <img src="'.$img_url.'"  alt="'.$course->fullname.'"> </a>
                            </div>
                            <div class="fp-courseinfo">
                                <h5><a href="'.$course_url.'">'.$course->fullname.'</a></h5>
                                <div class="readmore"></div>	
                            </div>
                        </div>
                </div>';

                if(($co_cnt%4)=="0") { $content .= '<div class="clearfix hidexs"></div>'; }
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
            if($f1_pos===false) {
                if($co_cnt>1) { echo $course_html; }
            }
        }else{
            if($f2_pos===false) {echo $course_html."<br/>".$btn_html; }
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

        $footer = '     </div>
                    </div>
                </div>
            </div>
        </div>';			
        $co_cnt = 1;
        $content = '';
      
        if ($ccc = get_courses('all', 'c.sortorder ASC', 'c.id,c.shortname,c.visible, c.summary')) {
            
            foreach ($course_ids as $course_id) {
                $course = get_course($course_id);

                $noimg_url = $OUTPUT->image_url('no-image', 'theme');
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
                            <div class="row-fluid">';

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

                        $noimg_url = $OUTPUT->image_url('no-image', 'theme');
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
