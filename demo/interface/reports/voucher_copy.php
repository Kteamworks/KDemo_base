<?php 
/**
 * This is a report of Financial Summary by Service Code.
 *
 * This is a summary of service code charge/pay/adjust and balance,
 * with the ability to pick "important" codes to either highlight or
 * limit to list to. Important codes can be configured in
 * Administration->Service section by assigning code with
 * 'Service Reporting'.
 *
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Visolve
 * @link    http://www.open-emr.org
 */

$sanitize_all_escapes=true;
$fake_register_globals=false;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/sql-ledger.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";
require_once "$srcdir/appointments.inc.php";
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
?>

<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<?php html_header_show();?>
<script type="text/javascript">

function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  divstyle.visibility = 'hidden';
  window.print();
  // divstyle.display = 'block';
 }

</script>

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
       margin-top:25px;
    }
	
	#report {
       margin:25px;
    }
}
table{
    border-style: none;
    border-top: none;
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
#report_parameters {
  background-color: #ececec;
  margin-top: 25px; }

  #report_parameters table {
    border: none;
    width: 100%;
    border-collapse: collapse; }

    #report_parameters table table td {
      padding: 5px; }

	  #report_parameters table table table {
      border: none;
      border-collapse: collapse;
      font-size: 1.0em; }

      #report_parameters table table table td.label {
        text-align: right; }

#report_results table {
  
  width: 100%;
  border-collapse: collapse;
  margin-top: 0px; }
  #report_results table thead {
    padding: 5px;
    display: table-header-group;
    background-color: #ddd;
    text-align: left;
    font-weight: bold;
    font-size: 1.0em; }
  #report_results table th {
   
    padding: 5px; }
  #report_results table td {
    padding: 5px;
    
    font-size: 1.0em; }

	
	#report table {
  
  width: 100%;
  border-collapse: collapse;
  margin-top: 0px; }
  #report table thead {
    padding: 5px;
    display: table-header-group;
    background-color: #ddd;
    text-align: left;
    font-weight: bold;
    font-size: 1.0em; }
  #report th {
   
    padding: 5px; }
  #report table td {
    
    font-size: 1.0em; }
</style>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">


<form method='post' action='voucher_copy.php' id='theform'>
<div id="report_parameters">
<div id="hideonprint">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<input type='hidden' name='form_save' id='form_save' value=''/>
Enter Voucher Number: <input type='text' name='voucher_no' id='voucher_no' value=''/>
<input type='submit' name='submit' id='submit' value='Submit'/>
<input type='button' class="button-css" value="Print" onclick="printme()" class="button-css">
</div>
 <!-- end of parameters -->
</div>
  

</form>

<?php
if($_POST['submit'])
{

$voch=$_POST['voucher_no'];
$vouchers="SELECT * from vouchers where voucher_no='".$voch."'";
	  $voucher=sqlStatement($vouchers);
	  $voche=sqlFetchArray($voucher);
	  //$pid=$enc['pid'];
	  $authuser=$_SESSION["authUser"];
	  
if($voche['voucher_no']==null)
{
	 echo( "The Voucher number entered is not there in the List!" );
}	
else
{
 //sqlStatement("update discharge_clearence set printed=2 ,reprinted_by='" . $_SESSION["authUser"] . "' where encounter='".$enc['encounter']."'");
echo "<p style=margin-top:70px><center><h4>".xlt("General Voucher")."</h4></center></p>";
 ?>
<form method="post" action="<?php echo $rootdir;?>/reports/voucher_copy.php" name="my_form" id="my_form">
<div id="report">
<table style='border-top: 1px solid #000;' width=100%>
<tr width=100% style='border-top: 1px solid #000;' >
<td>
Voucher No:<?php echo text($voch);?>
</td>
<td align="right">

Date: <?php echo text(date(' d/M/y ',strtotime($voche['posted_date'])));?>
</td>

</tr>

<tr width=100%>

<td>
Pay To: 

<?php 
$pr=$_POST['payee'];

/* $qr="select username from users where id='".$pr."'";
$q=sqlStatement($qr);

$rs=sqlFetchArray($q);
$rse=$rs['username']; */
echo "".text($voche['pay_to'])."</td>";

 ?>
<td></td>
<td></td>
<td></td>
</tr>



 </table>
 <table>
 <thead style='border-top: 1px solid #000;height="30"'>
  <th>
   <?php echo xlt('Posted Date'); ?>
  </th>
  <th >
   <?php echo xlt('Description'); ?>
  </th>
  <th >
   <?php echo xlt('Category'); ?>
  </th>
  <th>
   <?php echo xlt('Amount'); ?>
  </th>
<?php $a=$_POST['amt'];?>
 </thead style='border-top: 1px solid #000;'>
 <tr style='border-top: 1px solid #000;height="30"'>
 <td><?php echo text(date(' d/M/y ',strtotime($voche['posted_date'])));?></td>
 <td><?php echo text($voche['description']);?></td>
 <td> </td>
 <td><?php echo text(oeFormatMoney($voche['amount']));?></td>
</tr>
</table>
<?php
      $number =$voche['amount'];
      $no = round($number);
      $point = round($number - $no, 2) * 100;
      $hundred = null;
      $digits_1 = strlen($no);
     $i = 0;
     $str = array();
    $words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
?>
<table style='border-top: 1px solid #000;'>
<tr > </tr>
<tr style='border-top: 1px solid #000; height="30"'>
<td>Tax Amount:<?php echo text(oeFormatMoney($voche['tax']));?></td>
<td align="center">TDS Amount:<?php echo text(oeFormatMoney($voche['tds']));?></td>
<td> </td>

<?php 
$amt=$voche['amount'];
$tds=$voche['tds'];
$tax=$voche['tax'];
$Totalamt=$amt-($tds+$tax);?>
<td align="center">Total Amount:<?php echo text(oeFormatMoney($Totalamt))?></td>
</tr>
</table>
<table>
<tr height="30"><td nowrap>Amount in Words: <b><?php echo $result ?> Rupees Only</b></td></tr>
</table>
<table>
<tr> </tr>
<tr height="30">
<td>Remarks:</td>
</tr>
<tr> </tr>
<tr height="30">
<td>Payment Mode:<?php echo text($voche['payment_mode'])?></td>
<td align="right">Reference No:</td>
</tr>
<tr> </tr>
<tr> </tr>
<tr> </tr>
<tr height="50"> 
<td>Prepared by</td>
<td>Authorized by</td>
<td> </td>
<td>Receiver's Signature</td>
</tr>
</table>

<table>
<div id="report_parameters" align="center">
	 <input type='button'  value="Print" onclick="printme()" class="button-css">&nbsp;
 </div>
</table>
<?php }} ?>
</form>

</body>

</html>

