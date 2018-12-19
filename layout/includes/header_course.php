<?php
$surl = new moodle_url('/course/search.php');

?>
  
<header id="header">
    


 <div class="header-main">
    <div class="header-main-content">   
        
        <div class="row-fluid">
            <div class="col-xs-3 col-sm-1 col-md-1 col-lg-1 header_dh_logo" >
                <a href="<?php echo $CFG->wwwroot;?>/"><img src="<?php echo $CFG->wwwroot.'/theme/dariahteach/pix/logo_darkGreen_100.png'; ?>" ></a>
            </div>
            <div class="col-xs-9 col-sm-10 col-md-10 col-lg-11 " >            
                <div class="row-fluid top_upper_section" id="header_right_upper">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 header_text_div">
                        <span style="font-size: 20px; font-style: italic; color: #333333;">DH teaching material</span><br> <span style="color: #333333;">open-source, high quality, multilingual teaching materials for the digital arts and humanities</span>          
                    </div>                    
                </div>
            
                <div class="row-fluid" id="header_right_lower">              
                    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-6" style="padding-top:5px;">
                        <span style="margin-right: 10px;" ><a href="<?php echo new moodle_url('/local/staticpage/view.php?page=about'); ?>">About</a></span> <span style="margin-right: 10px;"><a href="<?php echo new moodle_url('/course/index.php#courses'); ?>">Courses</a></span> 
                                        <span style="margin-right: 10px;"> <a href="<?php echo new moodle_url('/course/index.php#workshops'); ?>">Workshops</a></span> <span > <a href="<?php echo new moodle_url('/local/simple_contact_form/'); ?>">Contact</a></span> 				
                    </div>
              
                    <div class="col-xs-9 col-sm-1 col-md-4 col-lg-3" id="header_search_box"> 
                        <div class="top-search-new" >
                            <form action="<?php echo new moodle_url('/search/index.php'); ?>" method="get">
                                    <input type="text" placeholder="search" name="q" value="" id="top-search-input-search">
                                    <input type="submit" value="Search" id="top-search-input-submitbtn">
                            </form>
                        </div>
                    </div>
              
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" id="header_course_menu_hamburger">
                        <?php if($CFG->branch > "27"): ?>
                            <?php echo $OUTPUT->user_menu(); ?>            
                        <?php endif; ?> 
                        <div class="openNav_class_header">
                            <span onclick="openNav()"><p style="">&#9776; </p></span> 
                        </div>
                      
                    </div>
                </div>
            </div>        
        </div>
    </div>
</div>  
</header>
    
<!--E.O.Header-->