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
 
$footerl = 'footer-left';
$footerm = 'footer-middle';
$footerr = 'footer-right';

$hasfootnote = (empty($PAGE->theme->settings->footnote)) ? false : $PAGE->theme->settings->footnote;
$hasfooterleft = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('footer-left', $OUTPUT));
$hasfootermiddle = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('footer-middle', $OUTPUT));
$hasfooterright = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('footer-right', $OUTPUT));
/*
$dhlogo = $CFG->wwwroot.'/theme/lambda/pix/dh_logo_200.png';
$eulogo = $CFG->wwwroot.'/theme/lambda/pix/eu_logo_200.png';
$moodlelogo = $CFG->wwwroot.'/theme/lambda/pix/md_logo_200.png';
$ignitelogo = $CFG->wwwroot.'/theme/lambda/pix/ignite_logo_200.png';
$logo = $CFG->wwwroot.'/theme/lambda/pix/dh_transparent_logo_100.png';
*/

$dhlogo = $CFG->wwwroot.'/theme/lambda/pix/footer/dariah_eu_logo.jpg';
$eulogo = $CFG->wwwroot.'/theme/lambda/pix/footer/eu_logo.jpg';
$moodlelogo = $CFG->wwwroot.'/theme/lambda/pix/footer/moodle_logo.jpg';
$ignitelogo = $CFG->wwwroot.'/theme/lambda/pix/footer/ignite_logo.jpg';
$acdhlogo = $CFG->wwwroot.'/theme/lambda/pix/footer/dh_acdh_logo.jpg';
?>

<div class="row-fluid">
    <?php
        echo $OUTPUT->footerblocks($footerl, 'span4');

        echo $OUTPUT->footerblocks($footerm, 'span4');

        echo $OUTPUT->footerblocks($footerr, 'span4');
    ?>
</div>

<div class="footerlinks">
    <div class="row-fluid">
        <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')); ?></p>
        <?php if ($hasfootnote) {
                $footnote_HTML = format_text($hasfootnote,FORMAT_HTML);
                echo '<div class="footnote">'.$footnote_HTML.'</div>';
        } ?>
    </div>
        
    <?php if($PAGE->theme->settings->socials_position==0) { ?>
        <?php require_once(dirname(__FILE__).'/socials.php');?>
    <?php
        } ?>
</div>

<div class="container-fluid" style="background-color: white;" >
    <div class="row-fluid">
        <div class="span1 footer_span1"></div>
        <div class="span2 footer_logo">
            <a href=""><img src="<?php echo $eulogo; ?>" class="img-responsive"></a>
        </div>

        <div class="span2 footer_logo">
            <a href=""><img src="<?php echo $dhlogo; ?>" class="img-responsive"></a>
        </div>

        <div class="span2 footer_logo">
            <a href="https://www.oeaw.ac.at/acdh/">
                <img src="<?php echo $acdhlogo; ?>" class="img-responsive">
                <!--<img src="https://fundament.acdh.oeaw.ac.at/common-assets/images/acdh_logo.svg" class="img-responsive" style="display:flex; margin: 0 auto;"> -->
            </a>
        </div>
        
        <div class="span2 footer_logo">
            <a href="https://ignite.acdh.oeaw.ac.at/"><img src="<?php echo $ignitelogo; ?>" class="img-responsive"></a>
        </div>

        <div class="span2 footer_logo">
            <a href="https://moodle.org/"><img src="<?php echo $moodlelogo; ?>"  class="img-responsive"></a>
        </div>
        <div class="span1 footer_span1"></div>
    </div>
    
</div>
