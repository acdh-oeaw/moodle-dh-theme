<?php
$footnote = theme_dariahteach_get_setting('footnote', 'format_html');

$address  = theme_dariahteach_get_setting('address');
$emailid  = theme_dariahteach_get_setting('emailid');
$phoneno  = theme_dariahteach_get_setting('phoneno');

?>
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  // tracker methods like "setCustomDimension" should be called before "trackPageView" 
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.apollo.arz.oeaw.ac.at/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '39']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->

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
<!--E.O.Footer-->

<?php  echo $OUTPUT->standard_end_of_body_html() ?>