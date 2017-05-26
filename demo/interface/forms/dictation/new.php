<!-- Form generated from formsWiz -->
<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
formHeader("Form: dictation");
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
?>
<html><head>
<?php html_header_show();?>
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>

		<!--[if IE 8]><script src="js/es5.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/selectize.js"></script>
		<script src="js/index.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
</head>
<body class="body_top">
<form method=post action="<?php echo $rootdir;?>/forms/dictation/save.php?mode=new" name="my_form">
<span class="title"><?php echo xlt('Plan Details'); ?></span>
<div style="float:right;margin-right:900px">
 <a href="<?php echo "$rootdir/patient_file/transaction/add_transaction.php";?>" class='css_button'
 onclick="top.restoreSession()"><?php echo xlt('Next'); ?></a>
 </div>
<br><br>
<span class=text><?php echo xlt('Plan: '); ?></span><br><textarea cols=80 rows=24 wrap=virtual name="dictation" ></textarea><br>
<span class=text><?php echo xlt('Additional Notes:'); ?> </span><br><textarea cols=80 rows=8 wrap=virtual name="additional_notes" ></textarea><br><br><br>
 Review After: <!--<input type="text" name="reviewafter"><br><br>-->
 <?php
    echo "<input type='text' size='16' name='form_date_collected' id='form_date_collected'" .
      " value='" . substr($row['date_collected'], 0, 16) . "'" .
      " title='" . xl('Date and time that the sample was collected') . "'" .
      // " onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'" .
      " />" .
      "<img src='$rootdir/pic/show_calendar.gif' align='absbottom' width='24' height='22'" .
      " id='img_date_collected' border='0' alt='[?]' style='cursor:pointer'" .
      " title='" . xl('Click here to choose a date and time') . "' />";
?><br><br>
<!--<input type='text' size='16' name='form_date_collected' id='form_date_collected'
   title='Date and time that the sample was collected'
      />
      <img src='$rootdir/pic/show_calendar.gif' align='absbottom' width='24' height='22'
      id='img_date_collected' border='0' alt='[?]' style='cursor:pointer'"
       title='Click here to choose a date and time' />-->
<a href="javascript:top.restoreSession();document.my_form.submit();" class="link_submit">[<?php echo xlt('Save'); ?>]</a>
<br>
<a href="<?php echo "$rootdir/patient_file/encounter/$returnurl";?>" class="link"
 onclick="top.restoreSession()">[<?php echo xlt('Don\'t Save'); ?>]</a>
 <script language='JavaScript'>
Calendar.setup({inputField:'form_date_collected', ifFormat:'%Y-%m-%d %H:%M',
 button:'img_date_collected', showsTime:true});
</script>
</form>
<?php
formFooter();
?>
