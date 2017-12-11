<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
$res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
if($res['newcrop_user_role'] == 'erxnurse' || $res['newcrop_user_role'] == 'erxdoctor' ){
	$rid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='ros' order by form_id desc limit 1 ");
		$rid1=sqlFetchArray($rid);
		$rid2=$rid1['form_id'];
		if($rid2 == null) { 
			$url="../../patient_file/encounter/load_form.php?formname=ros";
			} else { 	
$url="../../patient_file/encounter/view_form.php?formname=ros&id=$rid2";
						 }
header('Location: '.$url);
}
require ("C_FormVitals.class.php");
$c = new C_FormVitals();
echo $c->default_action_process($_POST);
@formJump();

?>
