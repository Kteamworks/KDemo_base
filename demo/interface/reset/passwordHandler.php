<?php
$ignoreAuth = true;
global $ignoreAuth;

 require_once("../globals.php");
require_once ('../../controllers/PasswordController.php');

$result=0;
$id = $_POST['email'];
     
if(isset( $id )) {
			$passwordHandler = new PasswordController();
			$result = $passwordHandler->postEmail($id);
}
echo $result;
?>