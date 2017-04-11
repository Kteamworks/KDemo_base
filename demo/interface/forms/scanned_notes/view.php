<?php
//////////////////////////////////////////////////////////////////////
// ------------------ DO NOT MODIFY VIEW.PHP !!! ---------------------
// View.php is an exact duplicate of new.php.  If you wish to make
// any changes, then change new.php and either (recommended) make
// view.php a symbolic link to new.php, or copy new.php to view.php.
//
// And if you check in a change to either module, be sure to check
// in the other (identical) module also.
//
// This nonsense will go away if we ever move to subversion.
//////////////////////////////////////////////////////////////////////

// Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
include_once("$srcdir/acl.inc");

$row = array();

if (! $encounter) { // comes from globals.php
 die("Internal error: we do not seem to be in an encounter!");
}
function insert_to_document()
 {
  global $pid,$imagepath;
  $newid = generate_id();
  $mimetype = $_FILES['form_image']['type'];
  $size = $_FILES['form_image']['size'];
	$query = "INSERT INTO documents ( " .
	  "id, type, size, date, url, mimetype, foreign_id, docdate" .
	  " ) VALUES ( " .
	  "'$newid', 'file_url', '$size', NOW(), 'file://$imagepath', " .
	  "'$mimetype', $pid, NOW() " .
	  ")";
	sqlStatement($query);
	
	$catrow = sqlQuery("SELECT * FROM categories WHERE name = 'Scanned Encounter Notes'");
	$catid = $catrow['id'];
	
	$query = "INSERT INTO categories_to_documents ( " .
	  "category_id, document_id" .
	  " ) VALUES ( " .
	  "'$catid', '$newid' " .
	  ")";
	sqlStatement($query);
	return $newid;
 }
function delete_document()
 {
   global $formid;
   $row = sqlQuery("SELECT * FROM form_scanned_notes fs left join documents on fs.document_id=documents.id WHERE " .
  "fs.id = '$formid' AND fs.activity = '1'");
  $imagepath = $row['url'];
  if(is_file($imagepath))
   {
    unlink($imagepath);
	return true;
   }
  else
   {
    return false;
   }
 }
$formid = $_GET['id'];
$imagedir = $GLOBALS['OE_SITE_DIR'] . "/documents/$pid/$encounter";
// If Save was clicked, save the info.
//
if ($_POST['bn_save']) {
//print_r($_FILES);die;
 // If updating an existing form...
 //
 if ($formid) {
  $query = "UPDATE form_scanned_notes SET " .
   "notes = '" . formData('form_notes','',true) . "' " .
   "WHERE id = '$formid'";
  sqlStatement($query);
 }

 // If adding a new form...
 //
 else {
  $query = "INSERT INTO form_scanned_notes ( " .
   "notes " .
   ") VALUES ( " .
   "'" . formData('form_notes','',true) . "' " .
   ")";
  $formid = sqlInsert($query);
  addForm($encounter, "Scanned Notes", $formid, "scanned_notes", $pid, $userauthorized);
 }

 $name=basename( $_FILES['form_image']['name']);
 $imagepath = "$imagedir/$name";

 // Upload new or replacement document.
 if ($_FILES['form_image']['size']) 
  {
  // If the patient's encounter image directory does not yet exist, create it.
	if(!is_dir($imagedir))
	 {
		if (!mkdir($imagedir, 0777, true)) 
		 {
			die('Failed to create folders...');
		 }
		chmod($imagedir, 0777);
	 }
//-------------------------------------------------------------------  
  	 $file_deleted=delete_document();
	 if (is_file($imagepath)) 
	  { 
	   $nameArray=split('.',$name);
	   $imagepath=$imagedir.'/'.$nameArray[0].'_'.time().'.'.$nameArray[1];
	  }
	 move_uploaded_file($_FILES['form_image']['tmp_name'], $imagepath);
	 chmod($imagepath, 0777);
	 if($file_deleted)
	  {
		  $mimetype = $_FILES['form_image']['type'];
		  $size = $_FILES['form_image']['size'];
			$query = "update  form_scanned_notes,documents set size='$size', date=NOW(), url='file://$imagepath', mimetype='$mimetype' where 
				form_scanned_notes.document_id=documents.id and form_scanned_notes.id='$formid'";
			sqlStatement($query);
	  }
	 else
	  {//first time entry
		 $document_id=insert_to_document();
		  $query = "UPDATE form_scanned_notes SET " .
		   "document_id = '" . $document_id . "' " .
		   "WHERE id = '$formid'";
		  sqlStatement($query);
	  }
  }

  formHeader("Redirecting....");
  formJump();
  formFooter();
  exit;
}


if ($formid) {
 $row = sqlQuery("SELECT * FROM form_scanned_notes fs left join documents on fs.document_id=documents.id WHERE " .
  "fs.id = '$formid' AND fs.activity = '1'");
 $imagepath = $row['url'];
 $mimetype=$row['mimetype'];

$imagename = basename(preg_replace("|^(.*)://|","",$imagepath));
$imagepath1=$web_root . "/sites/" . $_SESSION['site_id'] ."/documents/$pid/$encounter/$imagename";

 $formrow = sqlQuery("SELECT id FROM forms WHERE " .
  "form_id = '$formid' AND formdir = 'scanned_notes'");
}
?>
<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<style type="text/css">
 .dehead    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
</style>
<script type="text/javascript" src="../../../library/dialog.js"></script>

<script language='JavaScript'>

 function newEvt() {
  dlgopen('../../main/calendar/add_edit_event.php?patientid=<?php echo $pid ?>',
   '_blank', 550, 270);
  return false;
 }

 // Process click on Delete button.
 function deleteme() {
  dlgopen('../../patient_file/deleter.php?formid=<?php echo $formrow['id'] ?>', '_blank', 500, 450);
  return false;
 }

 // Called by the deleteme.php window on a successful delete.
 function imdeleted() {
  top.restoreSession();
  location = '<?php echo $GLOBALS['form_exit_url']; ?>';
 }

</script>

</head>

<body class="body_top">

<form method="post" enctype="multipart/form-data"
 action="<?php echo $rootdir ?>/forms/scanned_notes/new.php?id=<?php echo $formid ?>"
 onsubmit="return top.restoreSession()">

<center>

<p>
<table border='1' width='95%'>

 <tr bgcolor='#dddddd' class='dehead'>
  <td colspan='2' align='center'>Scanned Encounter Notes</td>
 </tr>

 <tr>
  <td width='5%'  class='dehead' nowrap>&nbsp;Comments&nbsp;</td>
  <td width='95%' class='detail' nowrap>
   <textarea name='form_notes' rows='4' style='width:100%'><?php echo $row['notes'] ?></textarea>
  </td>
 </tr>

 <tr>
  <td class='dehead' nowrap>&nbsp;Document&nbsp;</td>
  <td class='detail' nowrap>
<?php
$string='Upload';
if ($formid && is_file($imagepath)) 
 {
  list($width, $height, $type, $attr) = getimagesize($imagepath);
  if($mimetype=="application/pdf")
   {
	$width=1000;
	$height=1000;
   }
  else
   {
	$width+=25;
	$height+=25;
   }
	echo $imagename.'<br>';
	if($mimetype=="image/png" || $mimetype=="image/jpg" || $mimetype=="image/jpeg" || $mimetype=="image/gif" || $mimetype=="image/tiff" || $mimetype=="application/pdf")
	 {
		echo "<iframe frameborder='0' width='$width' height='$height' type='$mimetype' src='" . $GLOBALS['webroot'] . 
						"/controller.php?document&retrieve&patient_id=&document_id=" . $row['document_id'] . "&as_file=false'></iframe>";
	 }
	else
	 {
		echo "<iframe frameborder='0' type='application/octet-stream' width='75%' height='30%' src='" . $GLOBALS['webroot'] . 
						"/controller.php?document&retrieve&patient_id=&document_id=" . $row['document_id'] . "&as_file=true'></iframe>";
	 }
  $string='Change';
 }
?>
   <p>&nbsp;
   <?php xl("$string this file:",'e') ?>
   <input type="hidden" name="MAX_FILE_SIZE" value="12000000" />
   <input name="form_image" type="file" />
   <br />&nbsp;</p>
  </td>
 </tr>

</table>

<p>
<input type='submit' name='bn_save' value='Save' />
&nbsp;
<!-- input type='button' value='Add Appointment' onclick='newEvt()' 
&nbsp;-->
<input type='button' value='Back' onClick="top.restoreSession();location='<?php echo $GLOBALS['form_exit_url']; ?>'" />
<?php if ($formrow['id'] && acl_check('admin', 'super')) { ?>
&nbsp;
<input type='button' value='Delete' onclick='deleteme()' style='color:red' />
<?php } ?>
</p>

</center>

</form>
</body>
</html>