<?php
include_once("../globals.php");

//if ($GLOBALS['full_new_patient_form']) {

  //require("new_comprehensive.php");
  //exit;
//}

// For a layout field return 0=unused, 1=optional, 2=mandatory.
function getLayoutUOR($form_id, $field_id) {
  $crow = sqlQuery("SELECT uor FROM layout_options WHERE " .
    "form_id = '$form_id' AND field_id = '$field_id' LIMIT 1");
  return 0 + $crow['uor'];
}

// Determine if the registration date should be requested.
$regstyle = getLayoutUOR('DEM','regdate') ? "" : " style='display:none'";

$form_pubpid    = $_POST['pubpid'   ] ? trim($_POST['pubpid'   ]) : '';
$form_title     = $_POST['title'    ] ? trim($_POST['title'    ]) : '';
$form_fname     = $_POST['fname'    ] ? trim($_POST['fname'    ]) : '';
$form_mname     = $_POST['mname'    ] ? trim($_POST['mname'    ]) : '';
$form_lname     = $_POST['lname'    ] ? trim($_POST['lname'    ]) : '';
$form_refsource = $_POST['refsource'] ? trim($_POST['refsource']) : '';
$form_sex       = $_POST['sex'      ] ? trim($_POST['sex'      ]) : '';
$form_refsource = $_POST['refsource'] ? trim($_POST['refsource']) : '';
$form_dob       = $_POST['DOB'      ] ? trim($_POST['DOB'      ]) : '';
$form_regdate   = $_POST['regdate'  ] ? date('Y-m-d') : date('Y-m-d');
?>
<html>

<head>
<?php html_header_show(); ?>
<link rel="stylesheet" href="<?php echo xl($css_header,'e');?>" type="text/css">
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>

<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>

<script LANGUAGE="JavaScript">

 var mypcc = '1';

 function validate() {
  var f = document.forms[0];
<?php if ($GLOBALS['inhouse_pharmacy']) { ?>
  if (f.refsource.selectedIndex <= 0) {
   alert('Please select a referral source!');
   return false;
  }
<?php } ?>
<?php if (getLayoutUOR('DEM','sex') == 2) { ?>
  if (f.sex.selectedIndex <= 0) {
   alert('Please select a value for sex!');
   return false;
  }
<?php } ?>
<?php if (getLayoutUOR('DEM','DOB') == 2) { ?>
  if (f.DOB.value.length == 0) {
   alert('Please select a birth date!');
   return false;
  }
<?php } ?>
  top.restoreSession();
  return true;
 }

</script>

</head>

<body class="body_top" onload="javascript:document.new_patient.fname.focus();">

<?php if ($GLOBALS['concurrent_layout']) { ?>
<form name='new_patient' method='post' action="new_patient_save_opd.php"
 onsubmit='return validate()'>
<span class='title'><?php xl('Add Patient Record','e');?></span>
<?php } else { ?>
<form name='new_patient' method='post' action="new_patient_save_opd.php"
 target='_top' onsubmit='return validate()'>
<a class="title" href="../main/main_screen.php" target="_top" onclick="top.restoreSession()">
<?php xl('Add Patient Record','e');?></a>
<?php } ?>

<br><br>

<center>

<?php if ($GLOBALS['omit_employers']) { ?>
   <input type='hidden' name='title' value='' />
<?php } ?>

<table border='0'>

<?php if (!$GLOBALS['omit_employers']) { ?>
 <tr>
  <td>
   <span class='bold'><?php xl('Title','e');?>: </span>
  </td>
  <td>
   <select name='title'>
<?php
$ores = sqlStatement("SELECT option_id, title FROM list_options " .
  "WHERE list_id = 'titles' ORDER BY seq");
while ($orow = sqlFetchArray($ores)) {
  echo "    <option value='" . $orow['option_id'] . "'";
  if ($orow['option_id'] == $form_title) echo " selected";
  echo ">" . $orow['title'] . "</option>\n";
}
?>
   </select>
  </td>
 </tr>
<?php } ?>

 <tr>
  <td>
   <span class='bold'><?php xl('First Name','e');?>: </span>
  </td>
  <td>
   <input type='entry' size='15' name='fname' value='<?php echo $form_fname; ?>'>
  </td>
 </tr>

 

 <tr>
  <td>
   <span class='bold'><?php xl('Mobile','e');?>: </span>
  </td>
  <td>
   <input type='entry' size='15' name='mb' value='<?php echo $form_mb; ?>'>
  </td>
 </tr>
 
 
 <tr>
  <td>
   <span class='bold'><?php xl('Age','e');?>: </span>
  </td>
  <td>
   <input type='entry' size='15' name='age' value='<?php echo $form_age; ?>'>
  </td>
 </tr>



<?php if ($GLOBALS['inhouse_pharmacy']) { ?>

<?php } ?>

 <tr>
  <td>
   <span class='bold'><?php // xl('Birth Date','e');?> </span>
  </td>
  <td>
   
<input type='hidden' size='10' name='DOB' id='DOB'
    value='<?php echo  date('Y-m-d'); ?>'>
	</input>
  </td>
 </tr>

 <tr<?php echo $regstyle; ?>>
  <td>
   <span class='bold'><?php xl('Registration Date','e');?>: </span>
  </td>
  <td>
   <input type='text' size='10' name='regdate' id='regdate'
    value='<?php echo $form_regdate; ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'
    title='yyyy-mm-dd' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_regdate' border='0' alt='[?]' style='cursor:pointer'
    title='Click here to choose a date'>
   <script LANGUAGE="JavaScript">
    Calendar.setup({inputField:"regdate", ifFormat:"%Y-%m-%d", button:"img_regdate"});
   </script>
  </td>
 </tr>

 

 <tr>
  <td colspan='2'>
   &nbsp;<br>
   <input type='submit' name='form_create' value=<?php xl('Create New Patient','e'); ?> />
  </td>
  <td>
  </td>
 </tr>

</table>
</center>
</form>
<script language="Javascript">
<?php
if ($form_pubpid) { 
  echo "alert('" . xl('This patient ID is already in use!') . "');\n";
}
?>
</script>

</body>
</html>
