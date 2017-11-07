<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
$res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
if($res['newcrop_user_role'] == 'erxdoctor' ){
$url="../../patient_file/encounter/load_form.php?formname=procedure_order";
header('Location: '.$url);
}
elseif($res['newcrop_user_role'] == 'erxnurse') { 
$url="../../main/finder/p_dynamic_finder.php";
header('Location: '.$url);
 $_SESSION['nurseVisit'] = $encounter; $_SESSION['LAST_ACTIVITY_nurse'] = time();
}
require ("C_FormROS.class.php");
$c = new C_FormROS();
echo $c->default_action_process($_POST);
@formJump();
?>
