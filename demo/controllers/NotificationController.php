<?php
require_once("../../library/classes/Controller.class.php");

class NotificationController extends Controller
{

    public function create($model_id, $userid_created, $type_id, $forwhome = [])
    {
        try {
            if (empty($forwhome)) {
                $qry = "SELECT * from users WHERE newcrop_user_role='erxadmin'";
				$exec = sqlStatement($qry);
				
            }
            // system notification
			$insert_notification = sqlInsert("INSERT INTO notifications (model_id,userid_created,type_id) values ($model_id,$userid_created,$type_id)");
		   while ($forwhome = sqlFetchArray($exec)) {
              $uid = $forwhome['id'];
                sqlInsert("INSERT INTO user_notification (notification_id,user_id,is_read) values ($insert_notification,$uid,'0')");
           }
        } catch (Exception $e) {
			$msg = 'Message: ' .$e->getMessage();
			header('Location: ' . $_SERVER['HTTP_REFERER'].'?message='.$msg);
        }
    }
	
    public function markAllRead($id)
    {
        $markasread_qry = sqlStatement("SELECT * from user_notification where user_id=". $_SESSION['authUserID'] ." and is_read= '0'");
        $markasread = sqlFetchArray($markasread_qry);
		foreach ($markasread as $mark) {
			sqlQuery("UPDATE user_notification SET is_read='1' WHERE id=".$mark['id']);
        }

        return 1;
    }

    public function markRead($id)
    {
       $markasread_qry = sqlStatement("SELECT * from user_notification where notification_id=".$id." and user_id=". $_SESSION['authUserID'] ." and is_read= '0'");
        $markasread = sqlFetchArray($markasread_qry);
		
      	sqlQuery("UPDATE user_notification SET is_read='1' WHERE id=".$markasread['id']);
       

        return 1;
    }
    public function deleteNotification($id)
    {
      	sqlQuery("DELETE from user_notification WHERE id=".$id);
        return 1;
    }

    public function deleteAll()
    {
        try {
sqlQuery("DELETE from user_notification WHERE user_id =".$_SESSION['authUserID']);
        return 1;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
