 $(document).ready(function(){
            
    //get all form from the page
    var formIDs = [];
    $("form").each(function() {
        var formid = $(this).attr('id');
        //filter the unnamed form ids 
        if(typeof formid !== 'undefined'){
            //the lesson question forms started with mform id and plus a number
            // so we need to filter them
            var fid = formid.includes("mform");
            if(fid === true){
                formIDs.push($(this).attr('id'));
            }    
        }
    });

    // get the lesson next and previous button forms because they have no id or name 
    var noSpace= $('form:not([id]):not([class])'); 
    var urlAction = "";

    noSpace.each(function() {                
        urlAction = $(this).attr('action');
        var urlSplit = urlAction.split('/');
        urlSplit = urlSplit[urlSplit.length-1]
        //the lessons using the continue.php for action
        // so if the action is then continue.php 
        // then i am adding an ID to I can handle the form with jquery
        if(urlSplit === "continue.php"){
            $(this).attr("id", "StepButton");
        }                
    });

    //check the submit
    $("form").submit(function(ev){

        //get the actual submitted form id
        var actualFormID = $(this).attr('id');

        //check if this id is in our array, then the user submitted a lesson quiz
        if ($.inArray(actualFormID, formIDs) != -1){

            var actualPageID = $(this).find('input[name="pageid"]').val();
            var formData = {
                   'id' : $(this).find('input[name="id"]').val(), 
                   'pageid' : $(this).find('input[name="pageid"]').val(), 
                   'sesskey': $(this).find('input[name="sesskey"]').val(), 
            }; 

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'html',
                success: function (data) {
                    //show the json response quiz data in the actual div                            
                    $('#lesson-'+ actualPageID +' tbody').empty();
                    $('#lesson-'+ actualPageID +' tbody').append(data);                            
                },
                error: function(xhr, resp, text) {
                    console.log(xhr, resp, text);
                }
            });
            ev.preventDefault();
        }
    });

    $("ul").removeClass("nav-tabs");
    $("button").click(function(){
        $("p").removeClass("intro");
    });

    $("#collapse_course_menu").click(function(){
        if ($('.navbar.navbar-default').css('display') === 'none') {
            $(".navbar.navbar-default").show();
            $(".left_course_menu_hidden").removeClass("left_course_menu_hidden").addClass("left_course_menu");
            $(".course_content_hidden").removeClass("course_content_hidden").addClass("course_content");
        }
        else
        {
            $(".navbar.navbar-default").hide();
            $(".left_course_menu").removeClass("left_course_menu").addClass("left_course_menu_hidden");
            $(".course_content").removeClass("course_content").addClass("course_content_hidden");
        }
    });
    
     // the page accordion jquery settings
    $("#accordion_dh div").first().css('display', 'block');

    // Get all the links.
    var link = $("#accordion_dh a");

    // On clicking of the links do something.
    link.on('click', function(e) {

        if($(this).attr("class") !== "first"){
            $("#accordion_dh a.first").css('background-color', 'white');
            $("#accordion_dh a.first").css('color', '#016771');                                   
        }else{
            $("#accordion_dh a.first").css('background-color', '#016771');
            $("#accordion_dh a.first").css('color', 'white');                                   
        }

        $("#accordion_dh a").removeClass('active');
        e.preventDefault();
        var a = $(this).attr("href");
        $(this).addClass('active');
        $(a).slideDown('fast');
        //$(a).slideToggle('fast');
        $("#accordion_dh div").not(a).slideUp('fast');    
    });
      

        
});