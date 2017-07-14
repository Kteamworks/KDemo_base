<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
$res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
if($res['newcrop_user_role'] == 'erxnurse' || $res['newcrop_user_role'] == 'erxdoctor' ){
$url="../../patient_file/encounter/load_form.php?formname=ros";
header('Location: '.$url);
}
require ("C_FormVitals.class.php");
$c = new C_FormVitals();
echo $c->default_action_process($_POST);
@formJump();

?>
