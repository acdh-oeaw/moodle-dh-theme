

<div class="row" style="" id="first_lesson_incourse_header">
                     
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 ">
             
        <div style="" class="course_first_lesson_header_img" >
            <?php 
                $courseimage = getDHCourseImage($COURSE->id);
                echo "<img src='".$courseimage."' class='img-responsive'>";
            ?>
        </div>        
         
    </div>

    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5"><?php 
        echo $COURSE->summary;
        ?>
    </div>
    
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" id="first_header_lesson">
        <?php
        $actualUrl = $PAGE->url->__toString();
        
        if (strpos($actualUrl, '/course/') !== false) {
            //generate the course first menu header elements
            $course_menu = new block_course_custom_menu();        
            $course_section_header = $course_menu->getSectionName($COURSE->id, 0);
            $course_menu_list = $course_menu->getCourseSectionNames($COURSE->id, 0);
        
            echo "<div class='up_left_course_menu'>";
            echo "<ul>";
            foreach($course_menu_list as $v){
                echo "<li>";
                echo $v;
                echo "</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        
        ?>
    </div>
</div>