    <!-- Piwik -->
    <script type="text/javascript">
      var _paq = _paq || [];
      // tracker methods like "setCustomDimension" should be called before "trackPageView" 
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//matomo.acdh.oeaw.ac.at//";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', '39']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    
    
    <!-- End Piwik Code -->
    <link rel="stylesheet" href="<?php echo $CFG->wwwroot;?>/theme/dariahteach/style/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $CFG->wwwroot;?>/theme/dariahteach/style/bootstrap-theme.min.css">
    <link rel="stylesheet" href="<?php echo $CFG->wwwroot;?>/theme/dariahteach/lightbox/lightbox.css">    
    
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
    
   
<footer id="footer">
    <div class="footer-main">
        <div class="container-fluid">
            <div class="row-fluid">
          
                <div class="col-xs-12 col-sm-5 col-md-5">          
                    <div class="footer-logo">
                        <br>               
                        <img src="<?php echo $CFG->wwwroot;?>/theme/dariahteach/pix/dariah-eu_logo_300.png" width="150px"  alt="moodle">              
                    </div>    
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2">

                    <div class="footer-logo">
                        <br>
                        <center>                            
                            <img src="<?php echo $CFG->wwwroot;?>/theme/dariahteach/pix/moodle-logo-banner_350.png" width="150px"  alt="moodle"><br>
                            <a href="<?php echo new moodle_url('/local/staticpage/view.php?page=impressum'); ?>">Impressum</a></span>
                        </center>
                    </div>
                </div>  

                <div class="col-xs-12 col-sm-2 col-md-3">&nbsp;</div>       

                <div class="col-xs-12 col-sm-3 col-md-2">          
                    <div class="footer-logo">
                        <br><br>
                        <img src="<?php echo $CFG->wwwroot; ?>/theme/dariahteach/pix/erasmuslogo.png" width="150px" height="70%">
                    </div>

                </div>
            </div>
        </div>
    </div>    
</footer>
<?php  echo $OUTPUT->standard_end_of_body_html() ?>