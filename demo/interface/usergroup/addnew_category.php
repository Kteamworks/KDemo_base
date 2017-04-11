<?php
include_once("../globals.php");
// For a layout field return 0=unused, 1=optional, 2=mandatory.
if ($_POST['submit'])
{
	$head=$_POST['hd'];
	//$option_id=$head;
	//$list_id='Doctor_Qualification';
	$qr = sqlInsert("INSERT INTO t_category_master( " .
    "Category_name,Category_created_by" .
    ") VALUES ( " .
    "'" . $_POST['hd']     . "', " .
	  "'" . $_SESSION['authUser']     . "'" .
   ")");
	
	$address = "{$GLOBALS['rootdir']}/usergroup/usergroup_admin_add.php";
    echo "\n<script language='Javascript'>top.restoreSession();window.location='$address';</script>\n";
    exit;
}


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
</head>

<body class="body_top" onload="javascript:document.new_patient.fname.focus();">
<form name='new_category' method='post' action="addnew_category.php" onsubmit='return top.restoreSession()'>
<span class='title'><?php xl('Add New Record','e');?></span>
<br><br><center>

<?php if ($GLOBALS['omit_employers']) { ?>
   <input type='hidden' name='title' value='' />
<?php } ?>

<table border='0'>
<tr>
  <td>
   <span class='bold'><?php xl('Category','e');?>: </span>
  </td>
  <td></td>
  <td>
   <input type='entry' size='15' name='hd' value='<?php echo ''; ?>'>
  </td>
 </tr>
 
 <tr>
  <td colspan='2'>
   &nbsp;<br>
   <input type='submit' name='submit' id='submit' value=<?php xl('Create New Category','e'); ?> />
  </td>
  <td>
  </td>
 </tr>
</table>
</center>
</form>
</body>
</html>
