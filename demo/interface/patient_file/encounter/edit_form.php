<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");

// when the Cancel button is pressed, where do we go?
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';


?>
<html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<!-- supporting javascript code -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>
<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>

</head>

<body class="body_top">

<span class="title">EDIT ADMISSION FORM</span>

<form method="post" action="<?php echo $rootdir;?>/patient_file/encounter/edit_form.php" name="my_form" id="my_form">
<?php
// output each GET variable as a hidden form input
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}
?>
<input type="hidden" id="confirm" name="confirm" value="1"/>
 <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
  }
   $client_name=($result['client_name']);
   $admit_date=($result['admit_date']);
    $discharge_date=($result['discharge_date']);
		
	
   ?>
   <input type="hidden" name="encounter" value="<?php echo $GLOBALS['encounter']?>"/>
    <input type="hidden" id="client_name" name="client_name" value='<?php echo attr($client_name);?>'>  
 


<p>
You are about to EDIT the Patient ADMISSION By

<?php echo attr($unit['units']); ?>
	<?php 
		$today = date('Y-m-d',strtotime("+0 days"));
		
		$tomorrow = date('Y-m-d',strtotime("+1 days"));
		?>
		 <tr>
		<td align="left" class="forms"><?php echo xlt('Admit date'); ?>:</td>
	   	<td class="forms">
			   <input type='text' size='10' name='admitted_date' id='admitted_date' <?php echo attr ($disabled)?>;
       value='<?php echo attr($admit_date); ?>'
       title='<?php echo xla('yyyy-mm-dd Date of Admit'); ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
        <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22'
        id='img_transfer_date' border='0' alt='[?]' style='cursor:pointer;cursor:hand'
        title='<?php echo xla('Click here to choose a date'); ?>'>
		</td>
		</tr>
		<tr>
		<td align="left" class="forms"><?php echo xlt('Discharge date'); ?>:</td>
	   	<td class="forms">
		
			   <input type='text' size='10' name='discharged_date' id='discharged_date' <?php echo attr ($disabled)?>;
       value='<?php echo attr($discharge_date); ?>' 
       title='<?php echo xla('yyyy-mm-dd Date of Discharge'); ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
        <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22'
        id='img_end_date' border='0' alt='[?]' style='cursor:pointer;cursor:hand'
        title='<?php echo xla('Click here to choose a date'); ?>'>
		</td>
      
	</tr>
	
</p>
<input type="button" id="confirmbtn" name="confirmbtn" value="Yes, Change the Date">
<input type="button" id="cancel" name="cancel" value="Cancel">
</form>
<?php
if ($_POST['confirm']) {
    // set the deleted flag of the indicated form
   
	$days="SELECT round(TIME_TO_SEC(TIMEDIFF('".$_POST['discharged_date']."','".$_POST['admitted_date']."'))/86400,0) days";
	//$days="SELECT TIMEDIFF('2015-08-09 00:00:00','2015-08-09 10:00:00') ";
	$day=sqlStatement($days);
	$daa=sqlFetchArray($day);
	$da=$daa['days'];
	$status='admit';
  	$e=$GLOBALS['encounter'];
	$units="SELECT sum(units) units from billing  where code_type='Ward Charges'  and encounter='".$e."' and code_text like '%Ward Charges'  and activity=1";
    $unit=sqlStatement($units);
	$unii=sqlFetchArray($unit);
	$uni=$unii['units'];
	$daa=$da-$uni;
	if($uni==$da)
	{
		sqlStatement("UPDATE t_form_admit SET admit_date='".$_POST['admitted_date']."',discharge_date='".$_POST['discharged_date']."',date_edited_by= '" . $_SESSION["authUser"] . "' where encounter='".$e."'");
       // redirect back to the encounter
	   
       $address = "{$GLOBALS['rootdir']}/patient_file/encounter/$returnurl";
        echo "\n<script language='Javascript'>top.restoreSession();window.location='$address';</script>\n";
        exit;
	}
	else if($da==0)
	{  
		sqlStatement("UPDATE t_form_admit SET admit_date='".$_POST['admitted_date']."',discharge_date='".$_POST['discharged_date']."',date_edited_by= '" . $_SESSION["authUser"] . "' where encounter='".$e."'");
		$address = "{$GLOBALS['rootdir']}/patient_file/encounter/$returnurl";
		echo "\n<script language='Javascript'>top.restoreSession();window.location='$address';</script>\n";
        exit;
	
	}else
	{
		sqlStatement("UPDATE billing SET fee=((fee/units)*(units+'".($daa)."')) , units=units+'".($daa)."' where encounter='".$e."' and code_type='Ward Charges' and admitted=1 and activity=1");
		sqlStatement("UPDATE t_form_admit SET admit_date='".$_POST['admitted_date']."',discharge_date='".$_POST['discharged_date']."',date_edited_by= '" . $_SESSION["authUser"] . "' where encounter='".$e."'");
		$message = "Units in the Billing has been Increased/Decreased for Ward Charges";
		  $address = "{$GLOBALS['rootdir']}/patient_file/encounter/$returnurl";
        echo "\n<script language='Javascript'>alert('$message');top.restoreSession();window.location='$address';</script>\n";
		 exit;
		
	}

  
	
}
?>
</body>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#confirmbtn").click(function() { 
	var startDate = new Date($('#admitted_date').val());
    var endDate = new Date($('#discharged_date').val());
	if (startDate > endDate){
		
		return validate();
		
	}else
	{
	return ConfirmDelete(); 
	}
	});
    $("#cancel").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl";?>'; });
});

function ConfirmDelete() {
	
    if (confirm("This action cannot be undone. Are you sure you wish to change the date for this Patient?")) {
        top.restoreSession();
        $("#my_form").submit();
        return true;
    }
    return false;
}

function validate()
{
	var startDate = new Date($('#admitted_date').val());
    var endDate = new Date($('#discharged_date').val());

if (startDate > endDate){
alert("Please Put the Start and the End Date Properly");
}
}
/* required for popup calendar */
Calendar.setup({inputField:"admitted_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_transfer_date",showsTime:'true'});
Calendar.setup({inputField:"discharged_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_end_date",showsTime:'true'});
</script>

</html>
