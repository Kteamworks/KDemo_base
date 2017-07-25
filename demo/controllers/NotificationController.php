<?php
require_once($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");

class NotificationController extends Controller
{

    public function create($model_id, $userid_created, $type_id, $forwhome = [])
    {
        try {
            if (empty($forwhome)) {
                $qry = "SELECT * from users WHERE newcrop_user_role='erxadmin'";
				$exec = sqlStatement($qry);
				$forwhome = sqlFetchArray($exec);
            }
            // system notification
			$insert_notification = sqlInsert("INSERT INTO notifications (model_id,userid_created,type_id) values ($model_id,$userid_created,$type_id)");
		   foreach ($forwhome as $user) {
              $uid = 1;
                sqlInsert("INSERT INTO user_notification (notification_id,user_id,is_read) values ($insert_notification,$uid,'0')");
           }
        } catch (Exception $e) {
			echo $e->getMessage();
			header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

}
