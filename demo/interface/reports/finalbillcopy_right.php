<?php
/**
 * 
 * Superbill Report
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
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once(dirname(__file__)."/../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/report.inc");
require_once("$srcdir/classes/Document.class.php");
require_once("$srcdir/classes/Note.class.php");
require_once("$srcdir/formatting.inc.php");

$startdate = $enddate = "";
$e=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
 $encounter=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
  setencounter($encounter);
 if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }
if(empty($_POST['start']) || empty($_POST['end'])) {
    // set some default dates
    $startdate = date('Y-m-d', (time() - 10*24*60*60));
    $enddate = date('Y-m-d', time());
}
else {
    // set dates
    $startdate = $_POST['start'];
    $enddate = $_POST['end'];
}
//Patient related stuff
if ($_POST["form_patient"])
$form_patient = isset($_POST['form_patient']) ? $_POST['form_patient'] : '';
//$form_pid = isset($_POST['form_pid']) ? $_POST['form_pid'] : '';
$form_pid= $_SESSION["pid"];
if ($form_patient == '' ) $form_pid =  $_SESSION["pid"];
//if ($form_patient == '' ) $form_pid = '';
?>
<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<style  type="text/css">
table {
  border-collapse: collapse;

  
  }


@media print {
	.title {
		visibility: hidden;
	}
	#margindiv{
		margin:0px;
		width:0px;
		}
    .pagebreak {
        page-break-after: always;
        border: none;
        visibility: hidden;
    }

	#superbill_description {
		visibility: hidden;
	}

	#report_parameters {
		visibility: hidden;
	}
    #superbill_results {
       margin-top: 0px;
    }
}

@media screen {
	.title {
		visibility: visible;
	}
	#superbill_description {
		visibility: visible;
	}
    .pagebreak {
        width: 100%;
        border: 2px dashed black;
    }
	#report_parameters {
		visibility: visible;
	}
}
#superbill_description {
   margin: 10px;
}
#superbill_startingdate {
    margin: 0px;
}
#superbill_endingdate {
    margin: 0px;
}

#superbill_patientdata {
}
#superbill_patientdata h1 {
    font-weight: bold;
    font-size: 0.8em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_insurancedata {
    margin-top: 10px;
}
#superbill_insurancedata h1 {
    font-weight: bold;
    font-size: 0.8em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_insurancedata h2 {
    font-weight: bold;
    font-size: 0.8em;
    margin: 0px;
    padding: 0px;
    width: 100%;
    background-color: #eee;
}
#superbill_billingdata {
    margin-top: 3px;
}
#superbill_billingdata h1 {
    font-weight: bold;
    font-size: 0.8em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_signature {
}
#superbill_logo {
}

@page  
{ 
    size: auto;   /* auto is the initial value */ 

    /* this affects the margin in the printer settings */ 
    margin: 3mm 5mm 10mm 10mm;  
} 

body  
{ 
    /* this affects the margin on the content before sending to printer */ 
    margin: 0px;  
} 
</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script language="Javascript">
// CapMinds :: invokes  find-patient popup.
 function sel_patient() {
  dlgopen('../main/calendar/find_patient_popup.php?pflag=0', '_blank', 500, 400);
 }

// CapMinds :: callback by the find-patient popup.
 function setpatient(pid, lname, fname, dob) {
  var f = document.theform;
  f.form_patient.value = lname + ', ' + fname;
  f.form_pid.value = pid;

 }

</script>
</head>

<body class="body_top">
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" />
<hr>


</form>

<div id="superbill_results">

<?php
if(1) {
    $sql = "select * from facility where billing_location = 1";
    $db = $GLOBALS['adodb']['db'];
    $results = $db->Execute($sql);
    $facility = array();
    if (!$results->EOF) {
        $facility = $results->fields;
?>


<?php
    }
		$sqlBindArray = array();
		$e=$_POST["encounter"];
		$res_query = 	"select * from forms where " .
                        "form_name = 'New Patient Encounter'";
                array_push($sqlBindArray);
		if($form_pid) {
		$e=$_SESSION["encounter"];
		$res_query.= " and pid=?";	
		array_push($sqlBindArray,$form_pid);
		}
        $res_query.=     " order by date DESC" ;
		$res =sqlStatement($res_query,$sqlBindArray);
	
    while($result = sqlFetchArray($res)) {
        if ($result{"form_name"} == "New Patient Encounter" and $result["encounter"]== $e) {
            $newpatient[] = $result{"form_id"}.":".$result{"encounter"};
			
			$pids[] = $result{"pid"};	
        }
    }
    $N = 6;

    function postToGet($newpatient, $pids) {
        $getstring="";
        $serialnewpatient = serialize($newpatient);
        $serialpids = serialize($pids);
        $getstring = "newpatient=".urlencode($serialnewpatient)."&pids=".urlencode($serialpids);

        return $getstring;
    }

    $iCounter = 0;
    if(empty($newpatient)){ $newpatient = array(); }
    foreach($newpatient as $patient){
        /*
        $inclookupres = sqlStatement("select distinct formdir from forms where pid='".$pids[$iCounter]."'");
        while($result = sqlFetchArray($inclookupres)) {
            include_once("{$GLOBALS['incdir']}/forms/" . $result{"formdir"} . "/report.php");
        }
        */

        print "<div id='superbill_patientdata'>";
        //print "<h1>".xlt('Patient Data').":</h1>";
               $patdata = getPatientData($pids[$iCounter], 'phone_cell,title,age,age_days,age_months,rateplan,date,sex,DOB,genericname1,fname,mname,lname,pubpid,street,city,state,postal_code,providerID');
        function ageCalculator($dob){
	if(!empty($dob)){
		$birthdate = new DateTime($dob);
		$today   = new DateTime('today');
		$age = $birthdate->diff($today)->y;
		return $age;
	}else{
		return 0;
	}
}

$dob =text($patdata['DOB']) ;
$enc=sqlStatement("select * from form_encounter where encounter='".$encounter."'");
$enc1=sqlFetchArray($enc);
$provider=$enc1['provider_id'];
$row1 = sqlStatement("SELECT * from users where id='".$provider."'");
$row2=  sqlFetchArray($row1);
$billing=sqlStatement("select * from billing  where encounter='".$encounter."'");
$billid=sqlFetchArray($billing);

$billingdate=sqlStatement("select max(date) as d from billing  where encounter='".$encounter."'");
$billdate=sqlFetchArray($billingdate);

$admit=sqlStatement("select * from t_form_admit  where encounter='".$encounter."'");
$admit1=sqlFetchArray($admit);
$row32=sqlStatement("select * from insurance_data where pid='".$form_pid."'");
$row3=sqlFetchArray($row32);
$provider1=$row3['provider'];
$insurance=sqlStatement("select * from insurance_companies where id='".$provider1."'");
$insurance1=sqlFetchArray($insurance);
$age=$patdata['age'];
$age_months=$patdata['age_months'];
$age_days=$patdata['age_days'];
$rateplan=$patdata['rateplan'];

    echo "<center><h4>".xlt("Final Bill")."</h4></center>";
	echo "<table border=1 rules=cols style='width:100%'>";
	
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Name') . ": <b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".text($patdata['title']) ."  ". text($patdata['fname']) . "  " . text($patdata['mname'])."  ".text($patdata['lname']) . "</b></td>";
	echo "<td style='padding-right: '  >" . xlt('Bill No') . ":&nbsp&nbsp&nbsp&nbsp&nbsp " . text($billid['bill_id']) . "</td>";
	if($age!=0)
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($patdata['age']) ." ".xlt('Years')." , ".text($patdata['sex']). "</td>";
	}else
	if($age_months!=0)
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($patdata['age_months']) ." ".xlt('Months')." , ".text($patdata['sex']). "</td>";
	}else
	{
		echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($patdata['age_days']) ." ".xlt('Days')." , ".text($patdata['sex']). "</td>";
	}
	
	echo "<td  style='padding-right: 10px;' >" . xlt('Bill Date') . ":&nbsp&nbsp ". text(date('d/M/y h:i:s A',strtotime($billdate['d'])))."</td>";
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Address:') . " &nbsp&nbsp&nbsp&nbsp&nbsp" . "".text($patdata['street']). "</td>";
	echo "<td  style='padding-right: 10px;'>" . xlt('MR No') . ": &nbsp&nbsp&nbsp&nbsp&nbsp" . text($patdata['genericname1']) . "</td></tr>";
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Location:') . " &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . "".text($patdata['city']).", ".text($patdata['state']) ."</td>";
	echo "<td  style='padding-right: 10px;' >" . xlt('Visit ID') . ":&nbsp&nbsp&nbsp&nbsp&nbsp " . text($enc1['encounter_ipop']) . "</td></tr>";
	echo "<tr><td style='padding-right: 100px;'  >" . xlt('Doctor') . ":&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp" . text($row2['username']). "</td>";
	echo "<td  style='padding-right: 10px;' >" . xlt('Department') . ": " . text($row2['specialty']) . "</td></tr>";
	if($admit1['admit_to_ward']!=NULL)
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('ADM Date') . ": ". text(date('d/M/y h:i:s A',strtotime($admit1['admit_date'])))."</td>";
	echo "<td style='padding-right: 10px;'  >" . xlt('Ward/Bed') . ":&nbsp " . text($admit1['admit_to_ward']) ." , ".text($admit1['admit_to_bed']). "</td></tr>";
	echo "<tr><td   style='padding-right: 100px;'>" . xlt('DCH Date') . ":&nbsp". text(date('d/M/y h:i:s A',strtotime($admit1['discharge_date'])))."</td>";
    echo "<td  style='padding-right: 10px;' >" . xlt('Rate Plan') . ": &nbsp&nbsp&nbsp" . text($patdata['rateplan']) . "</td></tr>";
	}
	if($rateplan=="TPAInsurance")
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('TPA Insurance') . ": ".$insurance1['name'] ."</td>";
	echo "<td  style='padding-right: 10px;' >" .xlt(''). "</td></tr>";
	}
	/* echo "<tr><td  style='padding-right: 10px;' >" . xlt('Date') . ":&nbsp&nbsp ". text(date('d/M/y',strtotime($patdata['date'])))."</td>";
	echo "<td  style='padding-right: 10px;' >" . xlt('Bill Date') . ":&nbsp&nbsp ". text(date(' d/M/y'))."</td></tr>";
	echo "<tr><td  style='padding-right: 10px;' >" . xlt('Name') . ": <b>&nbsp&nbsp" . text($patdata['fname']) . "  " . text($patdata['lname']) . "</b></td>";
	echo "<td  style='padding-right: 10px;'>" . xlt('Patient ID') . ": &nbsp&nbsp" . text($patdata['genericname1']) . "</td></tr>";
	echo "<tr><td  style='padding-right: 10px;' >" . xlt('Age/Gender') . ": &nbsp&nbsp" . ageCalculator($dob) ." , ".text($patdata['sex']). "</td>";
    
	echo "<tr><td  style='padding-right: 10px;' >" . xlt('Department') . ": &nbsp&nbsp" . text($row2['specialty']) . "</td>";
	echo "<td  style='padding-right: 10px;' >" . xlt('Encounter') . ":&nbsp&nbsp " . text($enc1['encounter_ipop']) . "</td></tr>";
	echo "<tr><td style='padding-right: 10px;'  >" . xlt('Bill No') . ":&nbsp&nbsp " . text($billid['bill_id']) . "</td>";
	echo "<td style='padding-right: 10px;'  >" . xlt('Ward/Bed') . ":&nbsp&nbsp " . text($admit1['admit_to_ward']) ." , ".text($admit1['admit_to_bed']). "</td></tr>";
	echo "<tr><td  style='padding-right: 10px;' >" . xlt('Admission Date') . ":&nbsp&nbsp ". text(date('d/M/y',strtotime($admit1['admit_date'])))."</td>";
	echo "<td   style='padding-right: 10px;'>" . xlt('Discharge Date') . ":&nbsp&nbsp ". text(date(' d/M/y',strtotime($admit1['discharge_date'])))."</td></tr>";
	 */echo "</table>";
		print "</div>";
    
  

        print "<div id='superbill_billingdata'>";
        //print "<h1>".xlt('Billing Information').":</h1>";
        if (count($patient) > 0) {
            $billings = array();
            echo "<table width='100%' style='#000;'>";
            echo "<tr style='border-top: 1px solid #000;' >";
			echo "<b>";
            echo "<td class='bold' width='20%'>".xlt('Date')."</td>";
            echo "<td class='bold' width='20%'>".xlt('Head')."</td>";
			echo "<td class='bold' width='30%'>".xlt('Description')."</td>";
			echo "<td class='bold' width='10%' align='right'>".xlt('Rate')."</td>";
			echo "<td class='bold' width='10%' align='right'>".xlt('Qty')."</td>";
            echo "<td class='bold' width='10%' align='right'>".xlt('Fee')."</td></tr><tr style='border-bottom: 1px solid #000;'><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
			echo "</b>";
			
            $total = 0.00;
            $copays = 0.00;
            //foreach ($patient as $be) {

                $ta = split(":",$patient);
                $billing = getPatientBillingEncounter($pids[$iCounter],$ta[1]);

                $billings[] = $billing;
				
				$item_code=null;
				//$sub_total=sqlStatement("SELECT sum(fee) as fees,code_type,count(code_type) as c  from billing where encounter='".$encounter."' group by code_type ");
                
				foreach ($billing as $b)				
				{
				    //Discount
					 $memo="Discount";
                    $discount=0;
                    $discount = sqlQuery("SELECT SUM(adj_amount) AS adjustments FROM ar_activity WHERE " .
	                                          "memo=? and pid = ? and encounter= ? ",array($memo,$form_pid,$encounter));
					$totaldis=$discount['adjustments'];
					
					//$subtotal=0;
                    // grab the date to reformat it in the output
                    $bdate = strtotime($b['date']);
					$ct=$b['code_type'];
					
					$counta=sqlStatement("SELECT max(date) d,sum(fee) as st ,code_type,notecodes,count(code_type) as c from billing where activity='1' and encounter='".$encounter."' and code_type='".$ct."'");
					$cc=sqlFetchArray($counta);
					
					 if($item_code!=$b['code_type']){
					
					 $cct=1;
					  $item_code=$b['code_type'];
					  
					  echo "<td><h5>".$item_code."</td></h5>";
					  
					}
					else
					{
						$cct+=1;
				    }	
					
					$rate=$b['fee']/$b['units'];
					
                    echo "<tr>";
					//if($b['last_update_date']==null)
					//{
                    echo "<td class='text' style='font-size: 0.8em'>" . text(date('d/M/y',strtotime($cc['d']))) ."</td>";
					//}else
					//{
						// echo "<td class='text' style='font-size: 0.8em'>" . text(date('d/M/y',strtotime($b['last_update_date']))) ."</td>";
					//}
                    echo "<td class='text'>". text($b['code_type'])."</td>\n";
					echo "<td class='text'>".text($b['code_text'].' '.$b['notecodes']) . "</td>";
					echo "<td class='text' align='right'>" .text(oeFormatMoney($rate)) . "</td>";
			        echo "<td class='text' align='right'>" .text($b['units']) . "</td>";
                    echo "<td class='text' align='right'>";
                    echo oeFormatMoney($b['fee']);
                    echo "</td>";
					
                    echo "</tr>";
					
			        if($cct==$cc['c'] )  
					{		
						
						echo "\n\n<tr style='border-top: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('')."</td><td class='text'>" . "</td></tr>";
						echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('Sub-Total:')."&nbsp&nbsp"."</td><td class='text' align='right'>" . oeFormatMoney($cc['st']) . "</td></tr>";
						
						//$cct=0;
					}
                    $total += $b['fee'];
					
					
                }
				//echo "<tr><td class='bold' colspan=3 style='text-align:right'>".xlt('SubTotal')."</td><td class='text'>" . oeFormatMoney($sub_total) . "</td></tr>";
            // Calculate the copay for the encounter
            $copays = getPatientCopay($pids[$iCounter],$ta[1]);


           
//		   echo "<tr style='border-top: 1px solid #000;'><td colspan=6>&nbsp; </td></tr>";
	//	   echo "<tr style='border-bottom: 1px solid #000;'><td colspan=6>&nbsp; </td></tr>";
			echo "<tr style='border-top: 1px solid #000;'><td>&nbsp; </td></tr>";
            echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('Bill Amount:')."&nbsp&nbsp"."</td><td class='text' align='right'>" . oeFormatMoney($total + abs($copays)) . "</td></tr>";

            //echo "<tr><td class='bold' colspan=5 style='text-align:right'>".xlt('Total:')."&nbsp&nbsp"."</td><td class='text'>" . oeFormatMoney($total) . "</td></tr>";
            echo "</table>";
            echo "<pre>";

            echo "</pre>";
			 
            echo "<table style='border-top: 1px solid #000;' width='100%'>";
            echo "<b><tr style='border-bottom: 1px solid #000;'>";
            echo "<td class='bold'  width='20%'>".xlt('Payments')."</td>";
            echo "<td class='bold' width='20%'>".xlt('Receipt No')."</td>";
			echo "<td class='bold' width='20%'>".xlt('Mode')."</td>";
			echo "<td class='bold' width='10%' nowrap>".xlt('Reference No.')."</td>";
			echo "<td class='bold' width='10%'>".xlt('')."</td>";
            echo "<td class='bold' width='10%' align='right'>".xlt('Amount')."</td></b></tr>\n";
             
			  $inres = sqlStatement("SELECT user,dtime,amount1,amount2,receipt_id,method FROM payments WHERE " .
          "pid = ? AND encounter = ?  AND activity=1  " .
      "ORDER BY dtime", array($form_pid,$encounter) );
    while ($inrow = sqlFetchArray($inres)) {
      $payer = empty($inrow['payer_type']) ? 'Pt' : ('Ins' . $inrow['payer_type']);
      $charges -= sprintf('%01.2f', $inrow['amount1']);
		 $amount = sprintf('%01.2f', 0 - $amount); // make it negative
     echo " <tr>\n\n\n";
       echo "  <td style='font-size: 0.8em'>" .  text(date('d/M/y',strtotime($inrow['dtime']))) . "</td>\n";
     echo "  <td class='text'>" .  text($inrow['receipt_id']) . "</td>\n";
     echo "  <td class='text'>"  .text($inrow['method'])."</td>\n";
     echo "  <td class='text'>".text($inrow['source'])."</td>\n";
	 echo "<td class='text'>".xlt('')."</td>";
	 if($inrow['amount1']==0)
	 {
		 $amt=$inrow['amount2'];
	 }
	 else
	 {
		 $amt=$inrow['amount1'];
	 }
	 //$amt=$inrow['amount1'];
    echo "  <td class='text' align='right'>" . text(oeFormatMoney($amt)) . "</td>\n";
    echo " </tr>\n";
	 $nettotal += $amt;
	

    }
	// echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=6 style='text-align:right'>".xlt('Net Payments :')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."</td><td class='text'>" . oeFormatMoney($nettotal) . "</td></tr>";
	echo "<tr style='border-top: 1px solid #000;' ><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;' style='text-align:right' nowrap>".xlt('Net Payments')."</td><td class='text' style='border-bottom: 1px solid #000;'align='right' >" ."". oeFormatMoney($nettotal) . "</td></tr>";
     echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 0px solid #000;'  style='text-align:right' nowrap>".xlt('Total Bill Amount')."</td><td class='text' style='border-bottom: 0px solid #000;' align='right'>" . oeFormatMoney($total) . "</td></tr>";
     echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right'>".xlt('Discount')."</td><td class='text' style='border-bottom: 1px solid #000;' align='right'>" . oeFormatMoney($totaldis) . "</td></tr>";
    echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right' nowrap>".xlt('Net Amount')."</td><td class='text' style='border-bottom: 1px solid #000;' align='right'>" . oeFormatMoney($total-$totaldis) . "</td></tr>";
    echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;' style='text-align:right' nowrap>".xlt('Balance Due')."</td><td class='text' style='border-bottom: 1px solid #000;' align='right'>" . oeFormatMoney($total-$nettotal-$totaldis) . "</td></tr>";
	echo "</table>";
	   }
        echo "</div>";

        ++$iCounter;

		echo "<div align='right'>";
        print "<br/><br/>".xlt('Signature').":  __________";
		 $auth=$_SESSION['authUser'];
         print "<br/><br/>". (text($auth))."&nbsp;&nbsp;&nbsp;<br/>";
		echo "</div>";
    }
}
    ?>
	
</div>

<div id="report_parameters">

<form method="post" name="theform" id='theform' action="finalbillcopy.php">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<table>
 <tr>
  <td width='0px'>
	<div style='float:left'>

	<table class='text'>
		<tr>
			<td class='label'>
			   <?php //echo xlt('Start Date'); ?>
			</td>
			<td>
			   <input type='hidden' name='start' id="form_from_date" size='10' value='<?php echo attr($startdate) ?>'
				onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
			  <!--<img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
				id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
				title='<?php //echo xla('Click here to choose a date'); ?>'> -->
			</td>
			<td class='label'>
			   <?php //echo xlt('End Date'); ?>
			</td>
			<td>
			   <input type='hidden' name='end' id="form_to_date" size='10' value='<?php echo attr($enddate) ?>'
				onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
			  <!-- <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
				id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
				title='<?php //echo xla('Click here to choose a date'); ?>'>-->
			</td>

			<td>
			&nbsp;&nbsp;<span class='hidden'><?php // echo xlt('Patient'); ?> </span>
			</td>
			<td>
			<input type='hidden' size='20' name='form_patient' style='width:100%;cursor:pointer;cursor:hand' value='<?php echo attr($form_patient) ? attr($form_patient) : xla('Click To Select'); ?>' onclick='sel_patient()' title='<?php echo xla('Click to select patient'); ?>' />
			<input type='hidden' name='form_pid' value='<?php echo attr($form_pid); ?>' />
			</td>
			</tr>
			<tr><td>
		</tr>
	</table>

	</div>

  </td>
  <td align='left' valign='middle' height="100%">
	<table style='border-left:1px solid; width:100%; height:100%' >
		<tr>
			<td>
				<div style='margin-left:15px'>
					

					<?php if (1) { ?>
					<a href='#' class='css_button' onclick='window.print()'>
						<span>
							<?php echo xlt('Print'); ?>
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



    </body>

<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>
<script type="text/javascript">
function setMyPatient() {
<?php if ($GLOBALS['concurrent_layout']) { ?>
 // Avoid race conditions with loading of the left_nav or Title frame.
 if (!parent.allFramesLoaded()) {
  setTimeout("setMyPatient()", 500);
  return;
 }
<?php 
 $result = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
 if (isset($_GET['set_pid'])) { ?>
 parent.left_nav.setPatient(<?php echo "'" . htmlspecialchars(($result['fname']) . " " . ($result['lname']),ENT_QUOTES) .
   "'," . htmlspecialchars($pid,ENT_QUOTES) . ",'" . htmlspecialchars(($result['genericname1']),ENT_QUOTES) .
   "','', ' " . htmlspecialchars(xl('DOB') . ": " . oeFormatShortDate($result['DOB_YMD']) . " " . xl('Age') . ": " . getPatientAgeDisplay($result['DOB_YMD']), ENT_QUOTES) . "'"; ?>);
 var EncounterDateArray = new Array;
 var CalendarCategoryArray = new Array;
 var EncounterIdArray = new Array;
 var Count = 0;
<?php
  //Encounter details are stored to javacript as array.
  $result4 = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? order by fe.date desc", array($pid));
  if(sqlNumRows($result4)>0) {
    while($rowresult4 = sqlFetchArray($result4)) {
?>
 EncounterIdArray[Count] = '<?php echo htmlspecialchars($rowresult4['encounter'], ENT_QUOTES); ?>';
 EncounterDateArray[Count] = '<?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($rowresult4['date']))), ENT_QUOTES); ?>';
 CalendarCategoryArray[Count] = '<?php echo htmlspecialchars(xl_appt_category($rowresult4['pc_catname']), ENT_QUOTES); ?>';
 Count++;
<?php
    }
  }
?>
 parent.left_nav.setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray);
  <?php
  $test = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? and  fe.encounter=? order by fe.date desc", array($pid,$e));
	 $test1=sqlFetchArray($test);
?>
//alert($e);
 EncounterIdArray1= '<?php echo htmlspecialchars($test1['encounter'], ENT_QUOTES); ?>';
 EncounterDateArray1 = '<?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($test1['date']))), ENT_QUOTES); ?>';
 CalendarCategoryArray1 = '<?php echo htmlspecialchars(xl_appt_category($test1['pc_catname']), ENT_QUOTES); ?>';
 parent.left_nav.setEncounter(EncounterDateArray1,EncounterIdArray1,CalendarCategoryArray1);
<?php } // end setting new pid ?>
 parent.left_nav.setRadio(window.name, 'dem');
 parent.left_nav.syncRadios();
<?php } // end concurrent layout ?>
}
$(window).load(function() {
 setMyPatient();
});
</script>
<script language="Javascript">
 /* Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
 */
</script>
</html>
