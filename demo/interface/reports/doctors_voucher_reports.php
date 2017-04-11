<?php
 // Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

 // This report lists front office receipts for a given date range.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");

 //$from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
 //$to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));
$from_date = date('Y-m-d H:i:s', strtotime($_POST['form_from_date']));
$to_date = date('Y-m-d H:i:s', strtotime($_POST['form_to_date']));

 function bucks($amt) {
  return ($amt != 0.00) ? oeFormatMoney($amt) : '';
 }
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Expenditure Report','e'); ?></title>
<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>

<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 // The OnClick handler for receipt display.
 function show_receipt(id,timestamp) {
  dlgopen('../reports/payment_voucher.php?receipt=1&patient=' + pid +
   '&time=' + timestamp, '_blank', 550, 400);
 }

</script>

<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">
/* specifically include & exclude from printing */
@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results {
       margin-top: 30px;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
</style>
</head>

<body class="body_top">

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<span class='title'><?php xl('','e'); ?>  <?php xl('HEAD WISE Reports','e'); ?></span>

<div id="report_parameters_daterange">
<?php echo date("d F Y", strtotime($form_from_date)) ." &nbsp; to &nbsp; ". date("d F Y", strtotime($form_to_date)); ?>
</div>

<form name='theform' method='post' action='doctors_voucher_reports.php' id='theform'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

<table>
 <tr>
  <td width='750px'>
	<div style='float:left'>

	<table class='text'>
		<tr>
			<td class='label'>
			   <?php xl('From','e'); ?>:
			</td>
			<td>
			   <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
				onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
			   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
				id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
				title='<?php xl('Click here to choose a date','e'); ?>'>
			</td>
			<td class='label'>
			   <?php xl('To','e'); ?>:
			</td>
			<td>
			   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
				onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
			   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
				id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
				title='<?php xl('Click here to choose a date','e'); ?>'>
			</td>
			<td class='label'>
			   <?php xl('Head','e'); ?>:
			</td>
			<td>
			<select name='usr' id='usr'>
			<option value='all' selected>All</option>
			<?php 
			
			$ures = sqlStatement("SELECT distinct head FROM vouchers ");

while ($urow = sqlFetchArray($ures)) {
  $uname = $urow['head'];
  //if ($urow['fname']) $uname .= ", " . $urow['fname'];
  echo " <option value='" . $urow['head'] . "'";
  echo ">$uname</option>\n";
  //$optto .= $tmp1 . $tmp2;
  //echo $optto;
  //if ($urow['id'] == $_SESSION['authUserID']) $tmp1 .= " selected";
 // $optfrom .= $tmp1 . $tmp2;
 // $ulist .= "ulist[$i] = '" . addslashes($uname) . "|" .
 ////   $urow['id'] . "|" . addslashes($urow['specialty']) . "';\n";
//  ++$i;
}
			
						
			
			
			
			?>
		</select>	</td>
		<td class='label'>
			   <?php xl('Payee','e'); ?>:
			</td>
		
			<td>
			<select name='payto' id='payto'>
			<option value='all' selected>All</option>
			<?php 
			
			$ures = sqlStatement("SELECT distinct pay_to FROM vouchers where pay_to is not null");

while ($urow = sqlFetchArray($ures)) {
  $uname = $urow['pay_to'];
  //if ($urow['fname']) $uname .= ", " . $urow['fname'];
  echo " <option value='" . $urow['pay_to'] . "'";
  echo ">$uname</option>\n";
  //$optto .= $tmp1 . $tmp2;
  //echo $optto;
  //if ($urow['id'] == $_SESSION['authUserID']) $tmp1 .= " selected";
 // $optfrom .= $tmp1 . $tmp2;
 // $ulist .= "ulist[$i] = '" . addslashes($uname) . "|" .
 ////   $urow['id'] . "|" . addslashes($urow['specialty']) . "';\n";
//  ++$i;
}
			
						
			
			
			
			?>
		</select>	</td>
		</tr>
	</table>

	</div>

  </td>
  <td align='left' valign='middle' height="100%">
	<table style='border-left:1px solid; width:100%; height:100%' >
		<tr>
			<td>
				<div style='margin-left:15px'>
					<a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
					<span>
						<?php xl('Submit','e'); ?>
					</span>
					</a>

					<?php if ($_POST['form_refresh']) { ?>
					<a href='#' class='css_button' onclick='window.print()'>
						<span>
							<?php xl('Print','e'); ?>
						</span>
					</a>
					<?php } ?>
				</div>
			</td>
		</tr>
	</table>
  </td>
 </tr>
</table>
</div> <!-- end of parameters -->

<?php
 if ($_POST['form_refresh'] || $_POST['form_orderby']) {
?>
<div id="report_results">
<div><p>
<b><?php $payto=$_POST['payto'];
$usr=$_POST['usr'];
echo "Report for Payee-".$payto." under Head-".$usr;
?></p></b>
</div>
<table>
 <thead>
  <th> <?php xl('Date','e'); ?> </th>
  <th> <?php xl('Voucher No','e'); ?> </th>
    <th> <?php xl('Payee','e'); ?> </th>
  

  <th> <?php xl('Description','e'); ?> </th>
  <th> <?php xl('Amount','e'); ?> </th>
  <th> <?php xl('User','e'); ?> </th>
 </thead>
 <tbody>
<?php
 if (true || $_POST['form_refresh']) {
  $total1 = 0.00;
  $total2 = 0.00;
  $payto=$_POST['payto'];
$usr=$_POST['usr'];

if ($usr=='all' & $payto=='all')
{
	$cod='';
}
if($usr=='all' & $payto!='all') {
	 $cod=" AND pay_to='$payto' ";
 }
if($usr!='all' & $payto=='all') {
	 $cod=" AND head='$usr' ";
 }
 if($usr!='all' & $payto!='all') {
	 $cod=" AND pay_to='$payto' AND head='$usr'";
 }

  $query = "SELECT id, voucher_no,posted_date,amount,auth_user, description, pay_to FROM vouchers WHERE " .
    "posted_date >= '$from_date' AND " .
    "posted_date <= '$to_date'" .
	$cod.
    "GROUP BY posted_date ORDER BY posted_date";
  // echo "<!-- $query -->\n"; // debugging
  $res = sqlStatement($query);

  while ($row = sqlFetchArray($res)) {
    // Make the timestamp URL-friendly.
    $timestamp = preg_replace('/[^0-9]/', '', $row['posted_date']);
?>
 <tr>
  <td nowrap>
   <a href="javascript:show_receipt(<?php echo $row['id'] . ",'$timestamp'"; ?>)">
   <?php echo oeFormatShortDate(substr($row['posted_date'], 0, 10)) . substr($row['posted_date'], 10, 6); ?>
   </a>
  </td>
 
  <td>
   <?php echo $row['voucher_no'] ?>
  </td>
  <td>
   <?php echo $row['pay_to'] ?>
  </td>
 
  <td>
   <?php echo $row['description'] ?>
  </td>
  <td align=''>
   <?php echo bucks($row['amount']) ?>
  </td>
 
 <td align=''>
   <?php echo ($row['auth_user']) ?>
  </td></tr>
<?php
    $total1 += $row['amount'];

  }
?>

 <tr>
  <td colspan='8'>
   &nbsp;
  </td>
 </tr>

 <tr class="report_totals">
  <td colspan='4'>
   <?php xl('Totals','e'); ?>
  </td>
  <td align='left'>
   <?php echo bucks($total1) ?>
  </td>
  <td>
  </td>
 </tr>

<?php
 }
?>
</tbody>
</table>
</div> <!-- end of results -->
<?php } else { ?>
<div class='text'>
 	<?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php } ?>

</form>
</body>
<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_from_date", showsTime:true});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d  %H:%M:%S", button:"img_to_date", showsTime:true});
</script>
</html>
