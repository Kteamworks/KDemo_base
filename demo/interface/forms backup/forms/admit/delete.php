<?php
include_once("../../globals.php");

// allow a custom 'delete' form

$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

if ($_POST['confirm']) {
    // set the deleted flag of the indicated form
	$q= sqlStatement("SELECT * from t_form_admit where encounter='".$_POST['encounter']."'");
	$q1=sqlFetchArray($q);
	$admit_to_ward=($q1['admit_to_ward']);
    $admit_to_bed=($q1['admit_to_bed']);
	 if($admit_to_ward=='Private')
  {
	  $pr=sqlStatement("select option_id, concat('SP',substring(option_id,2,instr(option_id,',')-2)) Bed1,concat('SP-',substring(option_id,instr(option_id,',')+1)) Bed2 from list_options where option_id like '".$admit_to_bed. "'");
	  $pr1=sqlFetchArray($pr);
	  $bed1=$pr1['Bed1'];
	  $bed2=$pr1['Bed2'];
	  sqlStatement("UPDATE list_options SET is_default=0 WHERE option_id= '".$bed1. "'");
	  sqlStatement("UPDATE list_options SET is_default=0 WHERE option_id= '".$bed2. "'");
	  
	  
  }
	 sqlStatement("update list_options set is_default=0 where list_id='".$admit_to_ward."'
	and option_id='".$admit_to_bed."'");
	sqlStatement("update billing set activity=0 where encounter='".$_POST['encounter']."' and code like '".$admit_to_ward."%'");
	 sqlStatement("DELETE FROM t_form_admit WHERE " .
        "encounter = '".$_POST['encounter']."'");
    $sql = "update forms set deleted=1 where id=".$_POST['id'];
    if ($_POST['id'] != "*" && $_POST['id'] != '') sqlInsert($sql);
	
    // log the event   
    newEvent("delete", $_SESSION['authUser'], $_SESSION['authProvider'], 1, "Form ".$_POST['formname']." deleted from Encounter ".$_POST['encounter']);

    // redirect back to the encounter
    $address = "{$GLOBALS['rootdir']}/patient_file/encounter/$returnurl";
    echo "\n<script language='Javascript'>top.restoreSession();window.location='$address';</script>\n";
    exit;
}
?>
<html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<!-- supporting javascript code -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

</head>

<body class="body_top">

<span class="title">Delete Encounter Form</span>

<form method="post" action="<?php echo $rootdir;?>/forms/admit/delete.php" name="my_form" id="my_form">
<?php
// output each GET variable as a hidden form input
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}
?>
<input type="hidden" id="confirm" name="confirm" value="1"/>
<p>
You are about to delete the form  '<?php echo $_GET['formname'];?>' from <?php xl('This Encounter','e'); ?>.
</p>
<input type="button" id="confirmbtn" name="confirmbtn" value="Yes, Delete this form">
<input type="button" id="cancel" name="cancel" value="Cancel">
</form>

</body>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#confirmbtn").click(function() { return ConfirmDelete(); });
    $("#cancel").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl";?>'; });
});

function ConfirmDelete() {
    if (confirm("This action cannot be undone. Are you sure you wish to delete this form?")) {
        top.restoreSession();
        $("#my_form").submit();
        return true;
    }
    return false;
}

</script>

</html>