 <?php
/**
* Administrative loader for lab compendium data.
*
* Supports loading of lab order codes and related order entry questions from CSV
* format into the procedure_order and procedure_questions tables, respectively.
*
* Copyright (C) 2012-2013 Rod Roark <rod@sunsetsystems.com>
*
* LICENSE: This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://opensource.org/licenses/gpl-license.php>.
*
* @package   OpenEMR
* @author    Rod Roark <rod@sunsetsystems.com>
*/

set_time_limit(0);

$sanitize_all_escapes  = true;
$fake_register_globals = false;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
// This array is an important reference for the supported labs and their NPI
// numbers as known to this program.  The clinic must define at least one
// procedure provider entry for a lab that has a supported NPI number.
//
$lab_npi = array(
  '1235138868' => 'Diagnostic Pathology Medical Group',
  '1235186800' => 'Pathgroup Labs LLC',
  '1598760985' => 'Yosemite Pathology Medical Group',
);

/**
 * Get lab's ID from the users table given its NPI.  If none return 0.
 *
 * @param  string  $npi           The lab's NPI number as known to the system
 * @return integer                The numeric value of the lab's address book entry
 */
function getLabID($npi) {
  $lrow = sqlQuery("SELECT ppid FROM procedure_providers WHERE " .
    "npi = ? ORDER BY ppid LIMIT 1",
    array($npi));
  if (empty($lrow['ppid'])) return 0;
  return intval($lrow['ppid']);
}

//if (!acl_check('admin', 'super')) die(xlt('Not authorized','','','!'));

$form_step   = isset($_POST['form_step']) ? trim($_POST['form_step']) : '0';
$form_status = isset($_POST['form_status' ]) ? trim($_POST['form_status' ]) : '';

if (!empty($_POST['form_import'])) $form_step = 1;

// When true the current form will submit itself after a brief pause.
$auto_continue = false;

// Set up main paths.
$EXPORT_FILE = $GLOBALS['temporary_files_dir'] . "/openemr_config.sql";
?>


<?php 

$page = $_SERVER['PHP_SELF'];
$sec = "10";

?>


<html>

<head>
<meta http-equiv="refresh" content="<?php echo $sec?>; URL='<?php echo $page?>'">
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>
<title><?php echo xlt('Provide Cash Handover Details'); ?></title>
</head>

<body class="body_top">
<center>
&nbsp;<br />
<form method='post' action='ecash.php' enctype='multipart/form-data'>




<?php 
 $user=$_SESSION["authUser"];
 $st='complete';
 
 $main=sqlStatement("select * from funds where handover_to='$user' and level=1 and status!='$st'");
 $gresult = sqlFetchArray($main);
 $reqid=$gresult['id'];
 $gr= sqlStatement("select * from funds WHERE user='$user' and status!='$st'");
 $grd = sqlFetchArray($gr);

 
 if($grd['level']==1)

 {
	 echo "Handover Requested. Wait for Approval";
 }	 
  
 else if($grd['level']==2)
 {
	 echo "Handover Request Approved \n";
	 echo " \n <td><input type='submit' name='handover' value='Sign Out' onclick='top.restoreSession();'/></td>\n";
 }
 
 else if($gresult['id'])
 {
	 $tamt=$_POST['tamt'];
	 echo "You have a Handover Request to approve for Rs.".$tamt."\n";
	 echo " \n <td><input type='submit' name='handover' value='Approve' onclick='top.restoreSession();'/></td>\n";
 }
else
{
?>
<table>

<?php

  
  
  echo " <tr>\n";
  echo "  <td width='1%' nowrap>" . xlt('User') . "</td>\n";
  echo "  <td><input name='user' readonly value='$user'></input>";
  
  echo "</td>\n";
  echo " </tr>\n";
  $gres1= sqlStatement("select sum(amount1) a,sum(amount2) b from payments " .
    "WHERE esign=0 and user='$user'");
  $grow = sqlFetchArray($gres1);
  $a=$grow['a'];
  $a1=$grow['b'];
  $sum=$a+$a1;
  
  
  
  echo " <tr>\n";
  echo "  <td nowrap>" . xlt('Type') . "</td>\n";
  echo "  <td><select name='type'>";
 
  echo "<option value='cash'>" . xlt('Cash Collection'    ) . "</option>";
  echo "<option value='credit'>" . xlt('Credit Collection') . "</option>";
  echo "<option value='refund'>" . xlt('Total Refund'  ) . "</option>";
  echo "<option value='discount'>" . xlt('Total Discount'  ) . "</option>";
  echo "</td>\n";
  echo " </tr>\n";
  
  echo " <tr>\n";
  echo "  <td width='1%' nowrap>" . xlt('Amount') . "</td>\n";
  echo "  <td><input name='amount' readonly value='$sum'></input>";
  
  echo "</td>\n";
  echo " </tr>\n";
  

  echo " <tr>\n";
  echo "  <td nowrap>" . xlt('Handover to') . "</td>\n";
  echo "  <td><select name='group'>";
  $gres = sqlStatement("SELECT username FROM users " .
    "WHERE federaldrugid='0'");
	
  while ($grow = sqlFetchArray($gres)) {
	if($user!=$grow['username'])
    {echo "<option value='" . text($grow['username']) . "'>" .
      text($grow['username']) . "</option>";
	}
  }
  echo "</select></td>\n";
  echo " </tr>\n";

  
  echo " <tr>\n";
  echo "  <td nowrap>" . xlt('Transferring Amount') . "</td>\n";
  echo "<td><input name='tamt'></input></td>";
 
  echo " </tr>\n";
  echo " <tr>\n";
  echo "  <td nowrap>" . xlt('Comments') . "</td>\n";
  echo "<td><textarea name='comment'>Enter your comments here...</textarea></td>";
 
  echo " </tr>\n";

 
 
  
  echo " <tr>\n";
  echo "  <td nowrap>&nbsp;</td>\n";
  
  
 
  echo "  <td><input type='submit' name='handover' value='Handover Request' onclick='window.close()' '/></td>\n";
  
  
  // onclick="top.restoreSession();location='<?php echo "$rootdir/reports/custom_report_range_bill.php" 
  
  echo " </tr>\n";

  echo " <tr>\n";
  echo "  <td colspan='2'>\n";

?>

  </td>
 </tr>
</table>


<?php 
/* if($_POST['handover']=='Handover Request')
{
$gr= sqlStatement("select * from funds " .
  "WHERE user='$user' and status is null");
  $grd = sqlFetchArray($gr);
 


echo xlt('Request made with Id');

}
*/

 }
 
 
 if($_POST['handover']=='Handover Request') {
	
	
$camt=$_POST['amount'];
$user=$_POST['user'];
$type=$_POST['type'];
$comments=$_POST['comment'];
$handover=$_POST['group'];	
//$level=$_POST[''];
$tamt=$_POST['tamt'];
	
$fundid = sqlInsert("INSERT INTO funds SET " .
                  "date = now(), collectionamt = ?,  user= ?, reqtransamt=?,type = ?,level=1, status='null',comments = ?,handover_to=?,sending_time=now()",
                  array($camt, $user, $tamt,$type, $comments, $handover));


sqlStatement("UPDATE payments SET " .
					  "esign= 4 " .
                      "WHERE user = '" . $user . "' and esign=0");

				  
	}
	
if($_POST['handover']=='Sign Out')	
	
	{
		$st='complete';
		 $user=$_SESSION['authUser'];
		 sqlStatement("UPDATE payments SET " .
					  "esign= 1 " .
                      "WHERE user = '" . $user . "' and esign=4");
					  
		sqlStatement("UPDATE funds SET " .
					  "level= 3," .
					  "status= 'complete', " .
					  "signout_time= now() " .
                      "WHERE user = '" . $user . "' and status!='$st'");			  
	}
	
if($_POST['handover']=='Approve')	
	
	{
		$st='complete';
		$user=$_SESSION['authUser'];
		$reqid=$_POST['reqid'];
		sqlStatement("UPDATE funds SET " .
					  "level= 2," .			
					  "approval_time= now() " .
                      "WHERE handover_to = '" . $user . "' and status!='$st'");			  
	}
	
	
	
	
	
	
	
?> 


<input type='hidden' name='form_status' value='<?php echo $form_status; ?>' />

</form>

<?php
ob_flush();
flush();
?>

</center>

<?php if ($auto_continue) { ?>
<script language="JavaScript">
 setTimeout("document.forms[0].submit();", 500);
</script>
<?php } ?>

</body>
</html>

