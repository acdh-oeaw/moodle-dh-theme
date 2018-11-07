<?php
$surl = new moodle_url('/course/search.php');
?>
 
    <script>

     /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
    function openNav() {
        document.getElementById("mySidenav").style.width = "400px";
        //document.getElementById("main").style.marginRight = "400px";
        document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    }

    /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        //document.getElementById("main").style.marginRight = "0";
        document.body.style.backgroundColor = "white";
    }
    </script>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/theme/dariahteach/javascript/jquery.awesomeCloud-0.2.js"></script>    
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/theme/dariahteach/javascript/main.js"></script>            
    <script type="text/javascript">
     
        $(document).mouseup(function (e)
        {
            var container = new Array();
            container.push($('#mySidenav'));

            $.each(container, function(key, value) {
                if (!$(value).is(e.target) // if the target of the click isn't the container...
                    && $(value).has(e.target).length === 0) // ... nor a descendant of the container
                {
                    $(value).css("width", "0");
                    $('body').css("background-color","white");

                }
            });
        });
                
        $(document).ready(function(){
            /* the world cloud settings */
            $("#wordcloud1").awesomeCloud({
                    "size" : {
                            "grid" : 8,
                            "factor" : 0,
                    },
                    "options" : {
                            "color" : "random-dark",
                            "rotationRatio" : 0.35,
                            //"printMultiplier" : 3,
                            "sort" : "random"
                    },
                    "font" : "arvoregular, Helvetica, serif",
                    "shape" : "square"
            });
            /* the lesson content boxes */
            $(".box.view_pages_box tbody").hide();
            $(".box.view_pages_box thead").click(function(){
                $(".box.view_pages_box tbody").hide();
                $('.generaltable.boxaligncenter thead th').css('color', '#016771');
                $('.generaltable.boxaligncenter thead th').css('background-color', 'white');
                var id = $(this).closest('table').attr('id')                
                $("#"+id+" tbody").show();
                $("#"+id+".generaltable.boxaligncenter thead th").css('color', 'white');
                $("#"+id+".generaltable.boxaligncenter thead th").css('background-color', '#016771');
            }); 
            
            //get parameter from the url
            $.urlParam = function(name){
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                if (results==null){
                    return null;
                }
                else{
                    return results[1] || 0;
                }
            }
            /* 
             * The block menu JQuery commands
             * hide all menupoint in the first load */
            
            var siteURL = $(location).attr('href');
            var currentCourse = $('#course_id').val();
            //f.e oeaw_cmc_26-3
            
            //var opened = $.cookie("openContentID");            
            var opened = readCookie("openContentID");
            //var openRootID = $.cookie("openRootID");            
            var openRootID = readCookie("openRootID");            
            //var selectedLessonContentRoot =  $.cookie("menu_root_element");            
            var selectedLessonContentRoot = readCookie("menu_root_element");
            //var cookieRootID = $.cookie("rootID");            
            var cookieRootID = readCookie("rootID");
            
            // if the first course page loads in then we
            // opens all of the left menu elements
            if (siteURL.indexOf("course/view.php") <= 0){
                //we are not in the course view, f.e. we are in the lesson
                $(".oeaw_custom_menu_content").hide();
                
                if(selectedLessonContentRoot && opened){
                    if(opened !== selectedLessonContentRoot){
                        $("#"+opened).show();
                        var actualOpenedRoot = opened.replace('oeaw_cmc_', '#oeaw_custom_menu_root_');
                        $(".oeaw_custom_menu_root").removeClass('active');
                        $(actualOpenedRoot).addClass('active');
                    } else {
                        $("#"+selectedLessonContentRoot).show();
                        var actualOpenedRoot = selectedLessonContentRoot.replace('oeaw_cmc_', '#oeaw_custom_menu_root_');
                        $(".oeaw_custom_menu_root").removeClass('active');
                        $(actualOpenedRoot).addClass('active');
                    }
                }
                $(".oeaw_custom_menu_content_row a").click(function(){
                    var lessonID = $(this).attr('id');
                    var myRoot = $(this).parent().parent().parent().parent();
                    createCookie("menu_root_element", myRoot.attr('id'), 20);
                });
            }else {
                //we are in the course view
                if($.urlParam('section') !== null){
                    $(".oeaw_custom_menu_content").hide();
                    var openContent = "oeaw_cmc_"+currentCourse+"-"+$.urlParam('section');
                    var openRoot = "#oeaw_custom_menu_root_"+currentCourse+"-"+$.urlParam('section');
                    createCookie("openContentID", openContent,20);
                    createCookie("openRootID", openRoot, 20);
                    $("#"+openContent).show();
                    $(".oeaw_custom_menu_root").removeClass('active');
                    $("#oeaw_custom_menu_root_"+currentCourse+"-"+$.urlParam('section')).addClass('active');
                }else {
                    $(".oeaw_custom_menu_content").show();
                }
                $(".oeaw_custom_menu_content_row a").click(function(){
                    var lessonID = $(this).attr('id');
                    var myRoot = $(this).parent().parent().parent().parent();
                    createCookie("menu_root_element", myRoot.attr('id') , 20);
                    createCookie("openContentID", myRoot.attr('id') , 20);
                });
                 // item clicked on the content area
                $(".activityinstance a").click(function(){
                    if($.urlParam('section') === null){
                        var section = 0;
                    }else {
                        var section = $.urlParam('section');
                    }
                    $(".oeaw_custom_menu_content").hide();
                    var openContent = "oeaw_cmc_"+currentCourse+"-"+section;
                    var openRoot = "#oeaw_custom_menu_root_"+currentCourse+"-"+section;
                    createCookie("openContentID", openContent, 20);
                    createCookie("openRootID", openRoot, 20);
                    $("#"+openContent).show();
                    $(".oeaw_custom_menu_root").removeClass('active');
                    $("#oeaw_custom_menu_root_"+currentCourse+"-"+section).addClass('active');
                });
            }
             //if the user double clicked the header then hide the content
            $(".oeaw_custom_menu_root_header a").dblclick(function(){
                 $(".oeaw_custom_menu_content").hide();
            });
    });
    </script>
    
        
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