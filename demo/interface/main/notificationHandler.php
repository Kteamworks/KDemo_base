<?php
 require_once("../globals.php");
require_once ('../../controllers/NotificationController.php');

$result=0;
$id = $_POST['id'];
     $notification = new NotificationController();
if(isset( $id )) {
     $result = $notification->markRead($id);
}
if(isset($_POST['del-id'])) {
     $result = $notification->deleteNotification($_POST['del-id']);
}	
if(isset($_POST['del-all'])) {
     $result = $notification->deleteAll();
}	
echo $result;
 
 ?>