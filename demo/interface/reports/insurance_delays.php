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
 $from_date = fixDate($_POST['form_from_date']);
 $to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=insurance_report.csv");
  header("Content-Description: File Transfer");
  // CSV headers:
  if (true) {
   
    echo '"MEDNo",';
    echo '"Bill No.",';
	echo '"TPA.",';
    echo '"Pre-Auth No.",';
	echo '"Billing Date",';
	
	echo '"Discharge Date",';
	echo '"B-D Delay",';
    echo '"Claim Date",';
	
	echo '"D-C Delay",';
	
    echo '"Approval Date ",';
	echo '"C-A Delay",';
    echo '"Amt Rec Date",';
	echo '"A-Ar Delay",';
	echo '"Actual Rec Date",';
	echo '"Ar-AP Delay"'."\n";
    
    
  }
}
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl(' Insurance Delays','e'); ?></title>
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

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Insurance Progress','e'); ?></span>

<div id="report_parameters_daterange">
<?php echo date("d F Y", strtotime($form_from_date)) ." &nbsp; to &nbsp; ". date("d F Y", strtotime($form_to_date)); ?>
</div>

<form name='theform' method='post' action='insurance_delays.php' id='theform'>

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
  
  <th align='left'> <?php xl('MED No.','e'); ?> </th>
  <th align='left'> <?php xl('Bill No.','e'); ?> </th>
  <th align='left'> <?php xl('TPA','e'); ?> </th>
  <th align='left'> <?php xl('Pre-Auth No.','e'); ?> </th>
  <th align='left'> <?php xl('Billing Date','e'); ?> </th>
  <th align='right'> <?php xl('Discharge Date','e'); ?> </th>
  <th align='right'> <?php xl('B-D Delay','e'); ?> </th>
  <th align='right'> <?php xl('Claim Date','e'); ?> </th>
  <th align='right'> <?php xl('D-C Delay','e'); ?> </th>
  <th align='right'> <?php xl('Approval Date','e'); ?> </th>
  <th align='right'> <?php xl('C-A Delay','e'); ?> </th>
  <th align='right'> <?php xl('Amt Rec Date','e'); ?> </th>
  <th align='right'> <?php xl('A-Ar Delay','e'); ?> </th>
  <th align='right'> <?php xl('Actual Rec Date','e'); ?> </th>
  <th align='right'> <?php xl('Ar-AP Delay','e'); ?> </th>
 </thead>
 <tbody>
 
<?php
} // end not export
if ($_POST['form_refresh'] || $_POST['form_csvexport']) {

  $from_date = fixDate($_POST['form_from_date'],date('Y-m-d'));
  $to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

	$prv=$_POST['form_provider'];
	if($prv!=0)
	{
		$query="select a.billid,  c.genericname1, d.name, b.date billing_date, b.discharge_date d_d , a.claim_date c_d,a.approval_date a_d,a.last_bill_date lbd, a.rec_date  r_d,a.auth_no from billing_activity_final a, t_form_admit  b, patient_data c, insurance_companies d,insurance_data e where a.encounter=b.encounter and e.pid=a.pid and a.pid=c.pid and e.provider=d.id and a.last_bill_date >= '$from_date' AND a.last_bill_date <= '$to_date' and e.provider=$prv";
	}
	else
	{
		$query="select a.billid,  c.genericname1,d.name, b.date billing_date, b.discharge_date d_d , a.claim_date c_d,a.approval_date a_d,a.last_bill_date lbd, a.rec_date  r_d,a.auth_no from billing_activity_final a, t_form_admit  b, patient_data c, insurance_companies d,insurance_data e where a.encounter=b.encounter and e.pid=a.pid and a.pid=c.pid and a.last_bill_date >= '$from_date' AND a.last_bill_date <= '$to_date' and e.provider=d.id";
	}
	$res=sqlStatement($query);
 
  
	$i=0;
	while ($row = sqlFetchArray($res)) {
	
  $i=$i+1;
  
  $billid=$row['billid'];
  $medno=$row['genericname1'];
  $authno=$row['auth_no'];
  $enc=$row['encounter'];
  //$qry="select * from ar_activity a, insurance_data b where a.encounter='$enc and order by a.claim_date";
  $tpa=$row['name'];
  $claim_date=$row['c_d'];
  $billdate=$row['lbd'];
  $dis_date=$row['d_d'];
  $approval_date=$row['a_d'];
  $rec_date=$row['r_d'];
  
  //Calculate
  $os=$total_amt-$rec_amt-$patpay;
 
  $date1Timestamp = strtotime($billdate);
  $date = date('m/d/Y h:i:s a', time());
  $date2Timestamp = strtotime($date);
  $total=$total+$claim_amt;
  $billtotal+=$total_amt;
  $apprtotal+=$approval_amt;
  $pattotal+=$patpay;
  $rectotal+=$rec_amt;
  $ostotal+=$os;
  
  


$date1Timestamp=strtotime($billdate);
$date2Timestamp=strtotime($dis_date);
$diff = $date2Timestamp - $date1Timestamp;
$days = floor($diff / (60*60*24) );
$bddelay+=$days;

$date1Timestamp=strtotime($dis_date);
$date2Timestamp=strtotime($claim_date);
$diff = $date2Timestamp - $date1Timestamp;
$d_c_days = floor($diff / (60*60*24) );
$td_c_days+=$d_c_days;




$date1Timestamp=strtotime($claim_date);
$date2Timestamp=strtotime($approval_date);
$diff = $date2Timestamp - $date1Timestamp;
$c_a_days = floor($diff / (60*60*24) );
$tc_a_days+=$c_a_days;






$date1Timestamp=strtotime($approval_date);
$date2Timestamp=strtotime($rec_date);
$diff = $date2Timestamp - $date1Timestamp;
$a_ar_days = floor($diff / (60*60*24) );
if($a_ar_days>0)
$ta_ar_days+=$a_ar_days;



$date1Timestamp=strtotime($rec_date);
$date2Timestamp=strtotime($rec_date);
$diff = $date2Timestamp - $date1Timestamp;
$ar_ap_days = floor($diff / (60*60*24) );
$tar_ap_days+=$ar_ap_days;


    if ($_POST['form_csvexport']) {
    echo '"' . oeFormatShortDate(substr($billdate, 0, 10)) . '",';
    echo '"' . qescape($row['genericname1']) . '",';
    echo '"' . qescape($billid) . '",';
    echo '"' . qescape($provid) . '",';
    echo '"' . qescape($authno) . '",';
    echo '"' . oeFormatMoney($total_amt). '",';
    echo '"' . oeFormatMoney($claim_amt) . '",';
	echo '"' . oeFormatMoney($approval_amt). '",';
    echo '"' . oeFormatMoney($patpay) . '",';
	echo '"' . oeFormatMoney($rec_amt) . '",';
	
	echo '"' . oeFormatMoney($disallw). '",';
    echo '"' . oeFormatShortDate(substr($recdate, 0, 10)) . '",';
	echo '"' . oeFormatMoney($os). '",';
    echo '"' .qescape($days) . '"'."\n";
    } 
    else {
		
  
?>
 <tr>
 
 <td>
   <?php echo $i;?>
  </td>
  
  
 
  <td>
   <?php echo $medno ?>
  </td>
  
  
  <td>
   <?php echo $billid ?>
  </td>
  
   
  <td nowrap>
   <?php echo $tpa ?>
  </td>
  
  
  <td nowrap>
   <?php echo $authno ?>
  </td>
  
  
  <td>
   <?php echo text(date('d/M/y',strtotime($billdate))) ?>
  </td>
  
  
  
 
 
   <td align='right'>
   <?php echo text(date('d/M/y',strtotime($dis_date)))?>
  </td>
  
  
  <td align='right'>
   <?php echo $days ?>
  </td> 
  
  <td align='right'>
   <?php echo text(date('d/M/y',strtotime($claim_date))) ?>
  </td>
  
   <td align='right'>
   <?php echo $d_c_days ?>
  </td> 
  
  
  
  <td align='right'>
   <?php echo text(date('d/M/y',strtotime($approval_date))) ?>
  </td>
  
  
   <td align='right'>
   <?php echo $c_a_days ?>
  </td> 
  
  
  <td align='right'>
   <?php 
   if(text(date('d/M/y',strtotime($rec_date))) == '01/Jan/70')
	   echo '-';
   else
	   echo text(date('d/M/y',strtotime($rec_date))) ?>
  </td>
  
  
   <td align='right'>
   <?php if ($a_ar_days < 0 ) 
			 echo '-';
         else
			 echo $a_ar_days ?>
  </td> 
  
  
  <td align='right'>
   <?php 
   $arec='01/Jan/70';
   if(text(date('d/M/y',strtotime($arec))) == '01/Jan/70')
	   echo '-';
   else
	   echo text(date('d/M/y',strtotime($arec))) ?>
  
  </td>
  
  
   <td align='right'>
   <?php echo $ar_ap_days ?>
  </td> 
  
  
 </tr>
<?php
	}  // end not export
 
  } // end while
} // end if
	
if (! $_POST['form_csvexport']) {
	?>
<tr class="report_totals">
  <td colspan='6'>
 <?php xl('Totals','e'); ?>
  </td>
 
  <td align='right' colspan='2'>
  <?php echo  $bddelay; ?>
  </td>
  <td align='right' colspan='2'>
 <?php echo  $td_c_days; ?>
  </td>
  <td align='right' colspan='2'>
   <?php 
   if($ta_ar_days < 0)
	   echo '-';
   else
	   echo $tc_a_days; ?>
  </td>
  <td align='right' colspan='2'>
   <?php echo  $ta_ar_days; ?>
  </td>
  
 
  
  <td align='right' colspan='2'>
  <?php echo  $tar_ap_days; ?>
  </td>
  
   
 
 </tr>
<?php }
?>


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
 // end not export
?>
