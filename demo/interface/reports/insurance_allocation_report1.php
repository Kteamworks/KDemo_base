<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

 // This module shows relative insurance usage by unique patients
 // that are seen within a given time period.  Each patient that had
 // a visit is counted only once, regardless of how many visits.

 include_once("../globals.php");
 include_once("../../library/patient.inc");
 include_once("../../library/acl.inc");
 require_once("../../library/formatting.inc.php");

 // Might want something different here.
 //
 // if (! acl_check('acct', 'rep')) die("Unauthorized access.");
function qescape($str) {
  $str = str_replace('\\', '\\\\', $str);
  return str_replace('"', '\\"', $str);
} 
 //
function bucks($amount) {
  if ($amount) {
    $amount = oeFormatMoney($amount);
    return $amount;
  }
  return '';
}
 
 $from_date = fixDate($_POST['form_from_date']);
 $to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

if ($_POST['form_csvexport']) {
  $st=$_POST['status'];	
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=insurance_report.csv");
  header("Content-Description: File Transfer");
  // CSV headers:
  // comment for 31-03-16
  
  if (true) {
    echo '"Date",';
    echo '"MEDNo",';
	echo '"Patient Name",';
    echo '"Bill No.",';
	echo '"TPA.",';
    echo '"Pre-Auth No.",';
	echo '"Total Amount",';
    echo '"Claim Amount",';
	
    echo '"Approved Amount ",';
	if($st!='R' & $st!='O')
	{
    echo '"Patient Paid"'."\n";
	}
	else
	{
		echo '"Patient Paid",';
	}
	if($st=='R')
	{
		echo '"Received Amount ",';
		echo '"Received Date ",';
		echo '"TDS ",';
		echo '"Insurance Disallowance ",';
		echo '"Days"' . "\n";
	}
    if($st=='O')
	{
	echo '"Outstanding Days",' ;		
    echo '"Outstanding Amount"'. "\n";
    
	}
  }
}
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Patient Insurance','e'); ?></title>
<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/calendar.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>
<script language="JavaScript">
 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';
</script>

<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
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
    #report_results table {
       margin-top: 0px;
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

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Insurance','e'); ?></span>

<div id="report_parameters_daterange">
<?php echo date("d F Y", strtotime($form_from_date)) ." &nbsp; to &nbsp; ". date("d F Y", strtotime($form_to_date)); ?>
</div>

<form name='theform' method='post' action='insurance_allocation_report1.php' id='theform'>

<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

<table>
 <tr>
  <td >
	<div style='float:'>

	<table class='text'>
		<tr>
		
		
		<td class='label'>
				<?php echo xlt('Status'); 
				$st=$_POST['status'];
				?>:
			<select name='status'>
			<?php if($st=='O') {?>
            <option value='O' selected>Outstanding</option>
			<option value='A'>All</option>
			<option value='R'>Received</option>
			<?php } if($st=='R') { ?>
			<option value='R' selected >Received</option>
			<option value='O'>Outstanding</option>
			<option value='A'>All</option>
			
			<?php } ?>
			
			<?php  if($st=='A' or $st==null){ ?>
			<option value='A' selected>All</option>
			<option value='O'>Outstanding</option>
			<option value='R'>Received</option>
			
			<?php } ?>
			
			</select>
		</td>
                        <td><?php echo xlt('TPA'); ?>:</td>
        <td><?php
                        // Build a drop-down list of providers.
                                //
                                $query = "SELECT distinct provider from insurance_data WHERE ".
                                  "provider is not null and provider!=''"; //(CHEMED) facility filter
                                $ures = sqlStatement($query);
								
                                echo "   <select name='form_provider'>\n";
                                echo "    <option value='0'>-- " . xlt('All') . " --\n";
                                while ($urow = sqlFetchArray($ures)) {
										$p=$urow['provider'];
										$cname=sqlStatement("SELECT name from insurance_companies where id='$p'");
                                        $ccnm=sqlFetchArray($cname);
										$provid = $urow['provider'];
										$pn=$ccnm['name'];
                                        echo "    <option value='" . attr($p) ."'";
                                        if ($pn == $_POST['form_provider']) echo " selected";
                                        echo ">" . text($pn) . "</option>\n";
                                }
                                echo "   </select>\n";
                                ?>
				</td>
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
			<td></td>
		</tr>
	</table>

	</div>

  </td>
  <td align='left' valign='middle' height="100%">
	<table style='border-left:1px solid; width:100%; height:100%' >
		<tr>
			<td>
				<div style='margin-left:15px'>
					<a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#form_csvexport").attr("value",""); $("#theform").submit();'>
					<span>
						<?php xl('Submit','e'); ?>
					</span>
					</a>

					<?php if ($_POST['form_refresh']|| $_POST['form_csvexport']) { ?>
					<a href='#' class='css_button' onclick='window.print()'>
						<span>
							<?php xl('Print','e'); ?>
						</span>
					</a>
					<a href='#' class='css_button' onclick='$("#form_csvexport").attr("value","true"); $("#theform").submit();'>
					<span>
						<?php xl('Export to CSV','e'); ?>
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

</form>
</div> <!-- end parameters -->

<div id="report_results">
<table>

 <thead>
 <th align='left'> <?php xl('','e'); ?> </th>
 <th align='left'> <?php xl('Bill Date','e'); ?> </th>
  <th align='left'> <?php xl('MED No.','e'); ?> </th>
 <th align='left'> <?php xl(' Patient Name','e'); ?> </th>

  <th align='left'> <?php xl('Bill No.','e'); ?> </th>
  <th align='left'> <?php xl('TPA','e'); ?> </th>
  <th align='left'> <?php xl('Pre-Auth No.','e'); ?> </th>
  <th align='right'> <?php xl('Total Amount','e'); ?> </th>
  <th align='right'> <?php xl('Claim Amount','e'); ?> </th>
  <th align='right'> <?php xl('Approved Amount','e'); ?> </th>
  <th align='right'> <?php xl('Patient Paid','e'); ?> </th>
 
  <?php if($st=='O') { ?>
  <th align='right'> <?php xl('Ins Outstanding Amt','e'); ?> </th>
  <th align='right'> <?php xl('Outstanding Days','e'); ?> </th>
  <?php } ?>
  
  <?php if($st=='R') { ?>
  <th align='right'> <?php xl('Received Amount','e'); ?> </th>
  <th align='right'> <?php xl('Received Date','e'); ?> </th>
   <th align='right'> <?php xl('TDS','e'); ?> </th>
  <th align='right'> <?php xl('Insurance Disallowance','e'); ?> </th>
  <th align='right'> <?php xl('Days','e'); ?> </th>
  <?php } ?>
  <!--<th align='right'> <?php xl('Status','e'); ?> </th>-->
 </thead>
 <tbody>
<?php
} // end not export
if ($_POST['form_refresh'] || $_POST['form_csvexport']) {

  $from_date = fixDate($_POST['form_from_date']);
  $to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

 /*  $query = "SELECT b.pid, b.encounter, SUM(b.fee) AS charges, " .
    "MAX(fe.date) AS date " .
    "FROM form_encounter AS fe, billing AS b " .
    "WHERE fe.date >= '$from_date' AND fe.date <= '$to_date' " .
    "AND b.pid = fe.pid AND b.encounter = fe.encounter " .
    "AND b.code_type != 'COPAY' AND b.activity > 0 AND b.fee != 0 " .
    "GROUP BY b.pid, b.encounter ORDER BY b.pid, b.encounter"; */

/*   $res = sqlStatement($query);
  $insarr = array();
  $prev_pid = 0;
  $patcount = 0;

  while ($row = sqlFetchArray($res)) {
    $patient_id = $row['pid'];
    $encounter_date = $row['date'];
    $irow = sqlQuery("SELECT insurance_companies.name " .
      "FROM insurance_data, insurance_companies WHERE " .
      "insurance_data.pid = $patient_id AND " .
      "insurance_data.type = 'primary' AND " .
      "insurance_data.date <= '$encounter_date' AND " .
      "insurance_companies.id = insurance_data.provider " .
      "ORDER BY insurance_data.date DESC LIMIT 1");
    $plan = $irow['name'] ? $irow['name'] : '-- No Insurance --';
    $insarr[$plan]['visits'] += 1;
    $insarr[$plan]['charges'] += sprintf('%0.2f', $row['charges']);
    if ($patient_id != $prev_pid) {
      ++$patcount;
      $insarr[$plan]['patients'] += 1;
      $prev_pid = $patient_id;
    }
  }
  
  
  
  
  
  
  
  
  
  $query = "SELECT a.*,c.fee as pat_pay from billing_activity_final a LEFT JOIN insurance_data b ON a.pid=b.pid LEFT JOIN billing c ON b.pid=c.pid AND c.code='HOSPITAL CHARGES' and activity= 1 and a.date >= '$from_date' AND a.date <= '$to_date' group by a.pid"; 
 }
  else
  {$query = "SELECT a.*,c.fee as pat_pay from billing_activity_final a LEFT JOIN insurance_data b ON a.pid=b.pid LEFT JOIN billing c ON b.pid=c.pid AND c.code='HOSPITAL CHARGES' and b.provider='$prv' and activity= 1 and a.date >= '$from_date' AND a.date <= '$to_date' group by a.pid ".
			" ";}
			
			
 */
 $prv=$_POST['form_provider'];
 $status=$_POST['status'];
 if ($status=='O')
 {
	 $status="('0')";
 }
 else if($status=='R'){
	 $status="('1')";
 }
 else if ($status='A')
 {
	 $status="('0','1')";
 }
 
 if ($prv==0)
{
 //SELECT a.*,c.fee as pat_pay from billing_activity_final a LEFT JOIN insurance_data b ON a.pid=b.pid LEFT JOIN billing c ON b.pid=c.pid AND c.code='HOSPITAL CHARGES' and activity= 1 and a.date >= '$from_date' AND a.date <= '$to_date' group by a.pid
 /*$query = "SELECT * from billing_activity_final a, insurance_data b WHERE  a.pid=b.pid AND ".
			" a.date >= '$from_date 00:00:00' AND a.date <= '$to_date 23:59:59'"; */
$query="SELECT a.*,sum(c.fee) as pat_pay ,e.memo,e.adj_amount,d.genericname1,d.title as t, d.fname f , d.mname m, d.lname l, b.provider as provider,a.bill_date date from billing_activity_final a LEFT JOIN insurance_data b ON a.pid=b.pid ".
        "LEFT JOIN patient_data d ON a.pid=d.pid ".
        "LEFT JOIN billing c ON b.pid=c.pid AND c.code in ('HOSPITAL CHARGES','INSURANCE DIFFERENCE AMOUNT','INSURANCE CO PAYMENT','REGISTRATION CHARGES') and activity= 1 ".
        "LEFT JOIN ar_activity e ON b.pid=e.pid AND e.memo in ('Discount') WHERE a.bill_date >= '$from_date 00:00:00' AND a.bill_date <= '$to_date 23:59:59' AND a.status in $status  ".
		"group by a.date,a.pid order by a.bill_date asc";
}
  else
{
	  $query="SELECT a.*,sum(c.fee) as pat_pay,e.memo,e.adj_amount,d.genericname1,d.title as t, d.fname f , d.mname m, d.lname l,a.bill_date date,b.provider as provider from billing_activity_final a LEFT JOIN insurance_data b ON a.pid=b.pid ".
              "LEFT JOIN patient_data d ON a.pid=d.pid ".
	         "LEFT JOIN billing c ON b.pid=c.pid AND c.code in ('HOSPITAL CHARGES','INSURANCE DIFFERENCE AMOUNT','INSURANCE CO PAYMENT','REGISTRATION CHARGES')and activity= 1 ".
			 "LEFT JOIN ar_activity e ON b.pid=e.pid AND e.memo in ('Discount') WHERE a.bill_date >= '$from_date 00:00:00' AND a.bill_date <= '$to_date 23:59:59' AND b.provider='$prv' AND a.status in $status  ".
			 "group by a.date,a.pid order by a.bill_date asc";
	  //$query = "SELECT * from billing_activity_final a, insurance_data b WHERE  a.pid=b.pid and b.provider='$prv' AND"." a.date >= '$from_date' AND a.date <= '$to_date'"; 
}
  $res=sqlStatement($query);
 
  //ksort($insarr);

  /*  while (list($key, $val) = each($insarr)) {
    if ($_POST['form_csvexport']) {
        echo '"' . $key                                                . '",';
        echo '"' . oeFormatMoney($val['charges'])                      . '",';
        echo '"' . $val['visits']                                      . '",';
        echo '"' . $val['patients']                                    . '",';
        echo '"' . sprintf("%.1f", $val['patients'] * 100 / $patcount) . '"' . "\n";
    } 
    else { */
	$i=0;
	$cname=sqlStatement("SELECT name from insurance_companies where id='$prv'");
    $ccnm=sqlFetchArray($cname);
	$provid = $ccnm['name'];
	$total=0;
	$billtotal=0;
	$apprtotal=0;
	 $pattotal=0;
	 $rectotal=0;
  $ostotal=0;
  while ($row = sqlFetchArray($res)) {
  $i=$i+1;
  $pid=$row['pid'];
  $prv1=$row['provider'];
  $cname=sqlStatement("SELECT name from insurance_companies where id='$prv1'");
  $ccnm=sqlFetchArray($cname);									
  $provid = $ccnm['name'];		
  $pname=$row['t'].''.$row['f'].' ' . $row['m'].' '.$row['l'];
  $billid=$row['billid'];
  $authno=$row['auth_no'];
  $enc=$row['encounter'];
  //$qry="select * from ar_activity a, insurance_data b where a.encounter='$enc and order by a.claim_date";
  $total_amt=$row['total_amt'];
  $claim_amt=$row['claim_amt'];
  $claim_date=$row['claim_date'];
  $discount=$row['adj_amount'];
  $billdate=$row['date'];
  $approval_amt=$row['approved_amt'];
  $rec_amt=$row['rec_amt'];
  $tds = ceil($row['tds']);
  //$interpatpay=$row['pat_pay']-$discount;
//  $patpay=$interpatpay+$row['by_patient_amt'];
  $patpay=$row['by_patient_amt'];
  $patpay=$patpay+$row['new_col_bkp'];
  //Calculat
  //
  
	$disa=$row['disallowance_test'];
  
  $os=$approval_amt-$rec_amt;
  $ostotal+=$os;
  $rec_date=$row['rec_date'];
  $date1Timestamp = strtotime($billdate);
  $date = date('m/d/Y h:i:s a', time());
  
   if($rec_date==null)
  {
	   
	$date2Timestamp = strtotime($date);
  }
  else
  {
    $date2Timestamp = strtotime($rec_date);
  }
  
  
  //$date2Timestamp = strtotime($rec_date);
  $total=$total+$claim_amt;
  $billtotal+=$total_amt;
  $apprtotal+=$approval_amt;
  $pattotal+=$patpay;
  $rectotal+=$rec_amt;
  $tdstotal+=$tds;
  $distotal+=$disa;
  
  //$diff = $rec_date->diff($claim_date)->format("%a"); 
/*   $date1=date_create("2013-03-15");
$date2=date_create("2013-12-12");
$diff=date_diff($date1,$date2); */
  //$diff=date_diff($claim_date,$rec_date);
  /* if($rec_date==null)
  {
  $date2Timestamp=now();
   $date2Timestamp = strtotime($date2Timestamp);
  $diff = $date2Timestamp - $date1Timestamp;
$days = floor($diff / (60*60*24) );
$days=-($days);
} */
//$date2Timestamp=now();
//$date2Timestamp = strtotime($date2Timestamp);
$diff = ($date2Timestamp - $date1Timestamp);
$days = floor($diff / (60*60*24) );
$days=($days+1);
    $date=$claim_date;
    if ($_POST['form_csvexport']) {
    echo '"' . oeFormatShortDate(substr($billdate, 0, 10)) . '",';
    echo '"' . qescape($row['genericname1']) . '",';
	echo '"' . qescape($pname) . '",';
    echo '"' . qescape($billid) . '",';
    echo '"' . qescape($provid) . '",';
    echo '"' . qescape($authno) . '",';
    echo '"' . oeFormatMoney($total_amt). '",';
    echo '"' . oeFormatMoney($claim_amt) . '",';
	echo '"' . oeFormatMoney($approval_amt). '",';
    echo '"' . oeFormatMoney($patpay) . '",';
	if($st=='R')
	{
	echo '"' . oeFormatMoney($rec_amt) . '",';
	
	//echo '"' . oeFormatMoney($disallw). '",';
    echo '"' . oeFormatShortDate(substr($rec_date, 0, 10)) . '",';
	echo '"' . oeFormatMoney($tds) . '",';
	echo '"' . oeFormatMoney($disa) . '",'; 
    echo '"' . qescape($days) . '",';
	}
    else if($st=='O')
	{
		
	echo '"' . oeFormatMoney($os). '",';
    echo '"' .qescape($days) . '",';
	
	}
	echo "\r\n";
    } 
    
	else 
	{
  
?>
 <tr>
 
 <td>
   <?php echo $i ?>
  </td>
  
  <td>
   <?php echo text(date('d/M/y',strtotime($billdate))) ?>
  </td>
 
  <td>
   <?php echo $row['genericname1'] ?>
  </td>
  <td>
   <?php echo $pname ?>
  </td>
  <td>
   <?php echo $billid ?>
  </td>

  
  <td nowrap>
   <?php echo $provid ?>
  </td>
  <td nowrap>
   <?php echo $authno ?>
  </td>
  
  
  
   <td align='right'>
   <?php echo bucks($total_amt, 0)?>
  </td>
  <td align='right'>
   <?php echo bucks($claim_amt) ?>
  </td>
  <td align='right'>
   <?php echo bucks($approval_amt) ?>
  </td>
  <td align='right'>
   <?php echo bucks($patpay) ?>
  </td>
  <?php if($st=='R')
  {
  ?>
  <td align='right'>
   <?php echo bucks($rec_amt-$tds) ?>
  </td>
 
  <td align='right'>
   <?php echo $rec_date ?>
  </td>
  
  
  <td align='right'>
   <?php echo bucks($tds) ?>
  </td>
  
  <td align='right'>
   <?php echo bucks($disa) ?>
  </td>
  
  <td align='right'>
   <?php echo $days ?>
  </td>
  
  <?php } ?>
  
  
  <?php if($st=='O')
  {
  ?>
  <td align='right'>
   <?php echo round($os) ?>
  </td>
 <td align='right'>
   <?php echo $days ?>
  </td> 
    <?php 
	
	} ?>
<!--<td align='left'>
   <?php //echo $total ?>
  </td>-->
  
 </tr>
<?php
	
	}  // end not export
  } // end while
} // end if
	
if (! $_POST['form_csvexport']) {
?>
<tr class="report_totals">
  <td colspan='7'>
   <?php xl('Totals','e'); ?>
  </td>
 
  <td align='right'>
   <?php echo  bucks($billtotal) ?>
  </td>
  <td align='right'>
   <?php echo bucks($total) ?>
  </td>
  <td align='right'>
   <?php echo bucks($apprtotal) ?>
  </td>
  <td align='right'>
   <?php echo bucks($pattotal) ?>
  </td>
  
 <?php if($st=='O')
 {?>
  <td align='right'>
  
   <?php echo bucks($ostotal) ?>
  </td>
  <td colspan='2'>
  </td>
 <?php } 
 
 else if($st=='R')
 {  ?>
  <td align='right'>
   <?php echo bucks($rectotal) ?>
  </td>
 
  <td align='right'>
   
  </td>
  
  
  <td align='right'>
   <?php echo bucks($tdstotal) ?>
  </td>
  <td align='right'>
   <?php echo bucks($distotal) ?>
  </td>
  
  <td align='right'>
  
  </td>
  
  <?php } ?>
	 
   
 
 </tr>
<!-- <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td align='left'><B>
   
  </B></td></tr> -->
</tbody>
</table>
</div> <!-- end of results -->

</body>

<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>
</html>
<?php
} // end not export
?>
