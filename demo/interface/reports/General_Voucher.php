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

$grand_total_units  = 0;
$grand_total_amt_billed  = 0;
$grand_total_amt_paid  = 0;
$grand_total_amt_adjustment  = 0;
$grand_total_amt_balance  = 0;


  if (! acl_check('acct', 'rep')) die(xlt("Unauthorized access."));

  $INTEGRATED_AR = $GLOBALS['oer_config']['ws_accounting']['enabled'] === 2;

  if (!$INTEGRATED_AR) SLConnect();

  $form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
  $form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
  $form_facility  = $_POST['form_facility'];
  $form_provider  = $_POST['form_provider'];
  $form_des=$_POST['form_des'];
  if ($_POST['form_csvexport']) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=payment_voucher_".attr($form_from_date)."--".attr($form_to_date).".csv");
    header("Content-Description: File Transfer");
    // CSV headers:
    } // end export
  else {
?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
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
       margin-top:35px;
    }
	
	#report {
       margin:35px;
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

<title><?php echo xlt('General Voucher') ?></title>
</head>
<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">


<form method='post' action='General_Voucher.php' id='theform'>
<div id="report_parameters">
<div id="hideonprint">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<input type='hidden' name='form_save' id='form_save' value=''/>
<table>
 <tr>
  <td width='70%'>
	<div style='float:left'>
	<table class='text'>
		<tr>
			<td class='label'>
				<?php echo xlt('Facility'); ?>:
			
			
			<?php dropdown_facility($form_facility, 'form_facility', true); ?>
			</td>
			                        <td><?php echo xlt('Payee Name'); ?>:</td>
                <td><?php
                        // Build a drop-down list of providers.
                                //
                                $query = "SELECT title FROM list_options  WHERE ".
                                  "list_id ='Payee_Name' "; 
                                $ures = sqlStatement($query);
                                echo "   <select name='payee'>\n";
                                echo "    <option value=''>-- " . xlt('All') . " --\n";
                                while ($urow = sqlFetchArray($ures)) {
                                        $provid = $urow['title'];
                                        echo "    <option value='" . attr($provid) ."'";
                                        //if ($provid == $_POST['form_provider']) echo " selected";
                                        echo ">" . text($urow['title'])  . "\n";
                                }
                                echo "   </select>\n";
                                ?>
				</td>
				<td><a href='addnewpayee.php'>Add New Payee</a></td>
		
                        <td><?php echo xlt('Accounts Head'); ?>:</td>
                <td><?php
                        // Build a drop-down list of providers.
                                //
                                $query = "SELECT title FROM list_options  WHERE ".
                                  "list_id ='General_Heads' "; 
                                $ures = sqlStatement($query);
                                echo "   <select name='heads'>\n";
                                echo "    <option value=''>-- " . xlt('All') . " --\n";
                                while ($urow = sqlFetchArray($ures)) {
                                        $provid = $urow['title'];
                                        echo "    <option value='" . attr($provid) ."'";
                                        //if ($provid == $_POST['form_provider']) echo " selected";
                                        echo ">" . text($urow['title'])  . "\n";
                                }
                                echo "   </select>\n";
                                ?>
				</td>
				<td><a href='addnew.php'>Add New Head</a></td>
				<td><?php echo xlt('Amount:'); ?></td>
				<td><input type='text' name='amt' size=8></input>  </td>       
				
                                                 <td>
                           
                           <?php //echo xlt('Important Codes'); ?>
                        </td></tr>
						<tr>
							
							
							<!-- <td>
						<?php //echo xlt('Tax Amount'); ?>
						<input type="text" name="form_taxamt" size=5 id="form_taxamt"></input>
						</td>

						<td>
						<?php //echo xlt('TDS Amount'); ?>
						<input type="text" name="form_tdsamt" size=5 id="form_tdsamt"></input>
						</td> -->
						<td> Description:
						<input type="text" name="form_des" size=10 id="form_des"></input></td>
						<td><?php echo xlt('Payment Mode ');?><select id="form_paymode">
<option value=1>Cash</option>
<option value=2>Check</option>
</select>
</td>
</tr>
						
		
	</table>
	</div>
	<table style='border-left:0px solid; width:100%; height:100%' >
		<tr>
			<td>
				<div style='margin-left:15px'>
					<a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#form_csvexport").attr("value",""); $("#theform").submit();'>
					<span>
						<?php echo xlt('Submit'); ?>
					</span>
					</a>

					<?php  /*if ($_POST['form_refresh'] || $_POST['form_csvexport']) { ?>
					<div id="controls">
					<a href='#' class='css_button' onclick='window.print()'>
						<span>
							<?php echo xlt('Print'); ?>
						</span>
					</a>
					<a href='#' class='css_button' onclick='$("#form_refresh").attr("value",""); $("#form_csvexport").attr("value","true"); $("#theform").submit();'>
						<span>
							<?php echo xlt('CSV Export'); ?>
						</span>
					</a>
					</div>
					<?php } */?>
				</div>
			</td>
		</tr>
	</table>
  </td>
  <td align='left' valign='middle' height="100%">
	
  </td>
 </tr>
</table>
</div> <!-- end of parameters -->
</div>
<?php
}
?>  <!-- End no export-->
<?php
if($_POST['form_save'])
{
 $sqlBindArray=array();
	$from_date = $form_from_date;
    $to_date   = $form_to_date;
	$description=$_POST['form_des'];
    $tds=$_POST['form_tdsamt'];
	$tax=$_POST['form_taxamt'];
	
	$authuser=$_SESSION["authUser"];
	
	$pr=$_POST['payee'];
	$hr=$_POST['heads'];
$qr="select username from users where id='".$pr."'";
$q=sqlStatement($qr);

$rs=sqlFetchArray($q);
$rse=$rs['username'];


 	$qry="UPDATE billing set voucherpaid_YN='1'  where code_text='".$rse."'and billed='1' and code_type='Doctor Charges' AND " ."date >=  ? AND date <= ?";
		array_push($sqlBindArray,"$from_date 00:00:00","$to_date 23:59:59");
	$r1=sqlStatement($qry,$sqlBindArray);
	$posted_date=$today;
	$payout=$_POST['amt'];
	 $Totalamt=$payout-($_POST['form_tdsamt']);
	 $r2=sqlInsert("INSERT into vouchers SET ".
	  "posted_date= NOW(), " .
	  "pay_to = '" . add_escape_custom($pr) . "', " .
	  "description = '" . add_escape_custom($_POST['form_des']) . "', " .
	  "amount= '" . add_escape_custom($Totalamt) . "', " .
	 // "payment_mode = '" . add_escape_custom($onset_date) . "', " .
	 // "reference_no = '" . add_escape_custom($onset_date) . "', " .
	  "tds = '" . add_escape_custom($_POST['form_tdsamt']) . "', " .
	  "head = '" . add_escape_custom($_POST['heads']) . "', " .
	  "auth_user = '" . add_escape_custom($authuser). "', " .
	  "tax = '" . add_escape_custom($_POST['form_taxamt']) . "'");
	  $voucher_no="SELECT voucher_no from vouchers order by voucher_no desc limit 1";
	  $voucher=sqlStatement($voucher_no);
	  $voch=sqlFetchArray($voucher);
	  $voc=$voch['voucher_no'];
	  echo "<p style=margin-top:0px><center><h4>".xlt("General Voucher")."</h4></center></p>";
	?>  
<div id="report">
<table style='border-top: 1px solid #000;' width=100%>
<tr width=100% style='border-top: 1px solid #000;' >
<td>
Voucher No:<?php echo text($voc);?>
</td>
<td align="right">

Date: <?php echo text(date(' d/M/y'));?>
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
echo "".text($pr)."</td>";

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
 <td><?php echo text(date(' d/M/y'));?></td>
 <td><?php echo text($description);?></td>
 <td> </td>
 <td><?php echo text(oeFormatMoney($a));?></td>
</tr>
</table>
<?php
      $number =$a;
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
<tr style='border-top: 1px solid #000; height="40"'><td></td><td></td><td></td><td></td></tr>
<tr height="30">
<td>Tax Amount:<?php echo text(oeFormatMoney($tax));?></td>
<td align="center">TDS Amount:<?php echo text(oeFormatMoney($tds));?></td>
<td> </td>
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
<td>Payment Mode:<?php echo text($paymode)?></td>
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
<?php	  
}

  if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
    $rows = array();
    $from_date = $form_from_date;
    $to_date   = $form_to_date;
    $sqlBindArray = array();
    if ($INTEGRATED_AR) {
    
	
	//

	$query = "select b.code,sum(b.units) as units,sum(b.fee) as billed,sum(payout) as payout, sum(ar_act.paid) as PaidAmount, " .
        "sum(ar_act.adjust) as AdjustAmount,(sum(b.fee)-(sum(ar_act.paid)+sum(ar_act.adjust))) as Balance, " .
        "c.financial_reporting " .
        "FROM form_encounter as fe " .
        "JOIN billing as b on b.pid=fe.pid and b.encounter=fe.encounter " .
        "JOIN (select pid,encounter,code,sum(pay_amount) as paid,sum(adj_amount) as adjust from ar_activity group by pid,encounter,code) as ar_act " .
        "ON ar_act.pid=b.pid and ar_act.encounter=b.encounter and ar_act.code=b.code " .
        "LEFT OUTER JOIN codes AS c ON c.code = b.code " .
        "INNER JOIN code_types AS ct ON ct.ct_key = b.code_type AND ct.ct_fee = '1' " .
        "WHERE b.code_type != 'COPAY' AND b.activity = 1 /* AND b.fee != 0 */ AND " .
        "fe.date >=  ? AND fe.date <= ?";
   array_push($sqlBindArray,"$from_date 00:00:00","$to_date 23:59:59");
    // If a facility was specified.
      if ($form_facility) {
        $query .= " AND fe.facility_id = ?";
       array_push($sqlBindArray,$form_facility);
      }
    // If a provider was specified.
      if ($form_provider) {
        $query .= " AND b.provider_id = ?";
        array_push($sqlBindArray,$form_provider);
      }
	  
	  
	  
      // If selected important codes
      if($_POST['form_details']) {
        $query .= " AND c.financial_reporting = '1'";
      }
      $query .= " GROUP BY b.payout ORDER BY b.code, fe.date, fe.id ";
      $res = sqlStatement($query,$sqlBindArray);
      $grand_total_units  = 0;
      $grand_total_amt_billed  = 0;
      $grand_total_amt_paid  = 0;
      $grand_total_amt_adjustment  = 0;
      $grand_total_amt_balance  = 0;
 
      while ($erow = sqlFetchArray($res)) {
	  $row = array();
      $row['pid'] = $erow['pid'];
      $row['provider_id'] = $erow['provider_id'];
      $row['Procedure codes'] = $erow['code'];
      $row['Units'] = $erow['units'];
      $row['Amt Billed'] = $erow['billed'];
      $row['Paid Amt'] = $erow['PaidAmount'];
      $row['Adjustment Amt'] = $erow['AdjustAmount'];
      $row['Balance Amt'] = $erow['Balance'];
	  $row['payout']=$erow['payout'];
      $row['financial_reporting'] = $erow['financial_reporting'];
      $rows[$erow['pid'] . '|' . $erow['code'] . '|' . $erow['units']] = $row;
      }
              if ($_POST['form_csvexport']) {
                // CSV headers:
                if (true) {
                  echo '"Payee Name",';
                  echo '"Description",';
                  echo '"Category",';
                  echo '"Paid Amt",';
		          echo '"Amt",';
                  echo '"Balance Amt",' . "\n";
                }
              } else {
?> 



<div id="report_results">

<table >
 

<tr width=100%>
<td>
Voucher No.
</td>
<td>

Date 
</td>

</tr>

<tr width=100%>

<td>
Pay To: 

<?php 
$pr=$_POST['payee'];
$hr=$_POST['heads'];
//$qr="select username from users where id='".$pr."'";
//$q=sqlStatement($qr);

//$rs=sqlFetchArray($q);
//$rse=$rs['username'];
echo "".text($pr)."</td>";?>
<input type='hidden' name='payee' value="<?php echo "".text($pr).""; ?>"> </input>
<input type='hidden' name='heads' value="<?php echo "".text($hr).""; ?>"> </input>
<td></td>
<td></td>

</tr>



 </table>

<table >
 <thead>
  <th>
   <?php echo xlt('Date'); ?>
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
  <th><?php echo xlt(''); ?></th>
  <th><?php echo xlt(''); ?></th>
 </thead>
 <?php
 
              }
			  $today = date('Y-m-d',strtotime("+0 days"));
     $orow = -1;
	 $form_des=$_POST['form_des'];
	 $amt=$_POST['amt'];
//$fd=$_POST['form_des'];
//sqlQuery('insert into billing where ');
	$vocquery="select sum(payout) as payout from billing where code_text='".$rse."'and billed='1' and code_type='Doctor Charges' AND voucherpaid_YN!=1 AND " .
        "date >=  ? AND date <= ?";
		array_push($sqlBindArray,"$from_date 00:00:00","$to_date 23:59:59");
	$res=sqlStatement($vocquery,$sqlBindArray);
	//sqlStatement($query,);
	$eerow=sqlFetchArray($res);
	$payout=$amt;?>
<input type='hidden' name='amt' id='amt' value='<?php echo $payout ?>'></input>
<input type='hidden' name='form_des' id='form_des' value='<?php echo $_POST['form_des']?>'></input>

  <?php  // foreach ($rows as $key => $row) {
$print = '';
$csv = '';
//
$today = date('Y-m-d',strtotime("+0 days"));
if($row['financial_reporting']){ $bgcolor = "#FFFFDD";  }else { $bgcolor = "#FFDDDD";  }
$print = "<tr bgcolor='$bgcolor'><td class='detail'>".text($today)."</td><td class='detail'>".text($form_des)."</td><td class='detail'>".text('')."</td><td class='detail'>".text(oeFormatMoney($payout))."</td><td class='detail'>".text('')."</td><td class='detail'>".text('')."</td>"; 

$csv = '"' . text($row['Procedure codes']) . '","' . text($row['Units']) . '","' . text(oeFormatMoney($row['Amt Billed'])) . '","' . text(oeFormatMoney($row['Paid Amt'])) . '","' . text(oeFormatMoney($row['Adjustment Amt'])) . '","' . text(oeFormatMoney($row['Balance Amt'])) . '"' . "\n";

$bgcolor = ((++$orow & 1) ? "#ffdddd" : "#ddddff");
                                $grand_total_units  += $row['Units'];
                                                $grand_total_amt_billed  += $row['Amt Billed'];
                                                $grand_total_amt_paid  += $row['Paid Amt'];
                                                $grand_total_amt_adjustment  += $row['Adjustment Amt'];
                                                $grand_total_amt_balance  += $row['Balance Amt'];

        if ($_POST['form_csvexport']) { echo $csv; } 
	else { echo $print;
 }
     //}
       if (!$_POST['form_csvexport']) {
         echo "<tr bgcolor='#ffffff'>\n";
         //echo " <td class='detail'>" . xlt("Grand Total") . "</td>\n"; 
         //echo " <td class='detail'>" . text($payout) . "</td>\n";
		 //echo " <td class='detail'>" .
         //text(oeFormatMoney($payout)) . "</td>\n";
		 //echo " <td class='detail'>" .
         //text(oeFormatMoney($payout)) . "</td>\n";
   		 //echo " <td class='detail'>" .
         //text(oeFormatMoney($payout)) . "</td>\n";
  		 //echo " <td class='detail'>" .
         //text(oeFormatMoney($payout)) . "</td>\n";
         echo " </tr>\n";
		 $amt=$_POST['amt'];
		 $Totalamt=$amt-($_POST['form_taxamt']+$_POST['form_tdsamt']);
		 $number =$amt;
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
         //echo $result . "Rupees Only  ";
		 echo "<tr><td class='detail'>".text("Amount in Words:   "). $result . text("Rupees Only")."</td></tr>";
		 echo "<tr><td class='detail'>".text("TAX Amount ").text($_POST['form_taxamt'])."</td>";
		 echo "<td class='detail'>".text("TDS Amount ").text($_POST['form_tdsamt'])."</td>";
		
		echo "<td class='detail'>".text("Total Amount ").text($Totalamt)."</td>";
		echo "<td>".text("         ")."</td></tr>";
		echo "<tr><td>".text("Payment Mode ")."</td>";
		echo "<td>".text("Card Type ")."</td>";
		echo "<td>".text("Bank Name ")."</td>";
		echo "<td>".text("Reference No ")."</td></tr>";

		 ?>
                </table> 
<table>
<tr>
<td><div align="center" id='report_parameters'>
						<?php echo xlt('Tax Amount'); ?>
						<input type="text" name="form_taxamt" size=5 id="form_taxamt"></input>
						</td>

						<td>
						<?php echo xlt('TDS Amount'); ?>
						<input type="text" name="form_tdsamt" size=5 id="form_tdsamt"></input>
						</td>
						</tr>
						<tr>
						
						</tr>
						</div>

</table>
				
				
				
				<div align="center" id='report_parameters'>
				<a href='#' class='css_button' onclick='$("#form_save").attr("value","true"); $("#form_csvexport").attr("value",""); $("#theform").submit();'>
						<span>
						<?php echo xlt('Save'); ?>
						</span>
						</a>
				
				</div>
        <?php
      }
    }
	}

  if (! $_POST['form_csvexport']) {
       if ( $_POST['form_refresh'] && count($print) != 1)
	{
		echo "<span style='font-size:10pt;'>";
                echo xlt('No matches found. Try search again.');
                echo "</span>";
		echo '<script>document.getElementById("report_results").style.display="none";</script>';
		echo '<script>document.getElementById("controls").style.display="none";</script>';
		}
		
if (!$_POST['form_refresh'] && !$_POST['form_csvexport']) { ?>
<div class='text'>
 	<?php // echo xlt('Please input search criteria above, and click Submit to view results.' ); ?>
</div>
<?php } ?>
</form>
</body>

<!-- stuff for the popup calendar -->

<!-- <link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
 top.restoreSession();-->
</script>
</html>
<?php
  } // End not csv export
?>
