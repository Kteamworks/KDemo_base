
<?php

include_once("../../globals.php");



// when the Cancel button is pressed, where do we go?
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

if ($_POST['submit']) {
    // set the discharge flag of the indicated form
	$pid=$_SESSION['pid'];
	$encounter=$GLOBALS['encounter'];
	sqlInsert("INSERT into discharge_clearence SET
	pid={$_SESSION["pid"]},
	date= NOW(),
	user = '" . $_SESSION["authUser"] . "',
   bill_id          = '" . add_escape_custom($_POST["bill_id"]) . "',
  amount          = '" . add_escape_custom($_POST["amount"]) . "',
  printed             = 1,
   encounter          = '" . add_escape_custom($_POST["encounter"]) . "'");
	
}
?>
<html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<!-- supporting javascript code -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

<script type="text/javascript" src="../../../library/dialog.js"></script>
<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<style>
.right{
    float:right;
}

.left{
    float:left;
}
u.dotted{
  border-bottom: 1px dotted #999;
  text-decoration: none; 
}
hr.dotted1 {
 
  width:80%;
}
</style>
</head>

<body class="body_top">
<form method="post" action="<?php echo $rootdir;?>/patient_file/encounter/discharge_clearance.php" name="my_form" id="my_form">
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" />
<hr>

<?php
// output each GET variable as a hidden form input
echo "<center><h4><u>".xlt("DISCHARGE CLEARANCE")."</u></h4></center>";
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}

?>

<input type="hidden" id="confirm" name="confirm" value="1"/>
 <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
	$result1= getPatientData($pid, "*");
  }
   $admit_to_ward=($result['admit_to_ward']);
    $admit_to_bed=($result['admit_to_bed']);
	$admit_date=($result['admit_date']);
	$status=$result['status'];
	$discharge_date=($result['discharge_date']);
	$patient_name=($result1['fname'])." ".($result1['mname'])." ".($result1['lname']);
	$age=$result1['age'];
   $age_months=$result1['age_months'];
   $age_days=$result1['age_days'];
   $encounter=$GLOBALS['encounter'];
   $billing=sqlStatement("select sum(fee) fees,bill_id from billing  where encounter='".$encounter."' and activity=1");
   $billid=sqlFetchArray($billing);
   $payment=sqlStatement("SELECT SUM(pay_amount) payments FROM ar_activity WHERE encounter='".$encounter."'");
   $payments=sqlFetchArray($payment);
   $lastpaid=sqlStatement("select amount1,amount2 from payments where encounter='".$encounter."' and activity=1 order by receipt_id desc limit 1");
   $last=sqlFetchArray($lastpaid);
   $discount=sqlStatement("SELECT SUM(adj_amount)discounts FROM ar_activity WHERE encounter='".$encounter."'");
   $discounts=sqlFetchArray($discount);
   $approved=sqlStatement("SELECT sum(approved_amt) approved_amt,auth_no FROM billing_activity_final WHERE encounter='".$encounter."'");
   $approved_amt=sqlFetchArray($approved);
   $pid=$_SESSION['pid'];
   $insurance=sqlStatement("SELECT COUNT( DISTINCT TYPE ) NumberOfInsurance FROM insurance_data
			where pid = ? and provider>0 ",array($pid));
   $insu = sqlFetchArray($insurance);
   $ins=$insu['NumberOfInsurance'];

   $app=$approved_amt['approved_amt'];
   $dis=$discounts['discounts'];
   $pay=$payments['payments'];
   $fee=$billid['fees'];
   $auth=$approved_amt['auth_no'];
   $balance=$fee-$pay-$dis;
    if($last['amount1']==0)
	 {
		 $amt=$last['amount2'];
	 }
	 else
	 {
		 $amt=$last['amount1'];
	 }
   if($ins>0)
{
	if($auth==null)
	{
		 echo "Please Enter the Pre-Auth  Number";
	exit;
	}
}
else
{
   if($balance>0)
   {
    echo "Please Clear the Balance";
	exit;
   
   }
}
  if($result==0)
  {
    echo "Not an InPatient";
	exit;
    } else
if($status=="admit")
{
	echo "Please Discharge the Patient";
	exit;
}
   
	
   ?>
  
  <p align=right>Admission Date: <?php echo text(date('d/M/y h:i:s A',strtotime($admit_date)));?></p>
  <p align=right>Discharge Date: <?php echo text(date('d/M/y h:i:s A',strtotime($discharge_date)));?></p>
  
 <input type="hidden" id="bill_id" name="bill_id" value='<?php echo attr($billid['bill_id']);?>'>
<input type="hidden" id="amount" name="amount" value='<?php echo attr($billid['fees']);?>'>
<input type="hidden" id="encounter" name="encounter" value='<?php echo attr($encounter);?>'>

<p>
Name &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo attr($result1['title'])?><?php echo attr($patient_name);?>( <?php echo attr($result1['genericname1']);?> ) <?php if($auth!=null){?>(<? echo attr($auth);?>)<?php } ?> </u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;<br><br>Age<b><i>&nbsp;&nbsp;&nbsp;&nbsp;
<u class="dotted">
<?php if($age!=0)
	{
	  echo attr($age);echo attr(Years);
	}else
	if($age_months!=0)
	{
	  echo attr($age_months);echo attr(Months);
	}else
	{
		echo attr($age_days);echo attr(Days);
	}?></u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;
Sex &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['sex'])?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
Ward &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($admit_to_ward);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
Against the Bill No. &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($billid['bill_id']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
Amount &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($billid['fees']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b> Last Amount Paid &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($amt);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>   Self / Corporate / Insurance / Hospital Employee / Due Bill&nbsp;&nbsp;&nbsp;&nbsp;
<br><br>
This is to inform you that the patient &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo attr($result1['title'])?><?php echo attr($patient_name);?></u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;can be discharged from the ward.
<br><br>
Remarks ______________________________________________________________
</p>
<br><br><br><h4><span class=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo xlt('Discharged by')?></span>
<h4><span class=right><?php echo xlt('Authorised by')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<br><br>
<p align="center"><b>Note: If the Amount Paid is less than the Bill Amount then the Authorized Signature is Mandatory</b></p>
<?php
$printed=sqlStatement("SELECT printed from discharge_clearence where encounter='".$encounter."'");
$pri=sqlFetchArray($printed);
$pr=$pri['printed'];
if($pr==0)
{
?>

 <div id="hideonprint" align="center"><!--<input type='submit'  value='<?php echo xlt('Save');?>' class="button-css">&nbsp;-->
	 <input type='submit'  id='submit' name='submit' value="Print" onclick="printme()" class="button-css">&nbsp;
	<input type='button' class="button-css" value='<?php echo xlt('Cancel');?>'
 onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'" />
 </div>
<?}?>
</form>

</body>
<script language="javascript">
/* required for popup calendar */
//Calendar.setup({inputField:"admit_date", ifFormat:"%Y-%m-%d ", button:"img_transfer_date"});
//Calendar.setup({inputField:"discharge_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_end_date",showsTime:'true'});
</script>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  divstyle.visibility = 'hidden';
  window.print();
  // divstyle.display = 'block';
 }

</script>

</html>