<?php
$ignoreAuth = true;
global $ignoreAuth;

 require_once("../globals.php");
require_once ('../../controllers/PasswordController.php');

$result=0;
$id = $_POST['email'];
if(isset($id)) {
     if (is_numeric($id) && strlen((string)$id) == 10) {
		 
	 $mob_num = sqlQuery("select email,phonecell from users where phonecell=$id");

	 if($mob_num !=null) {
		 $otp = rand(100000,999999);
		 	
		 $number = $mob_num['phonecell'];
		 $mail_status = SendSMS($number,$otp);
		 
		 if($mail_status == 1) {
			$otp_result = sqlQuery("INSERT INTO otp_expiry(otp,mobile_no,is_expired,create_at) VALUES ('" . $otp . "','" . $number . "', 0, '" . date("Y-m-d H:i:s"). "')");
			//$current_id = mysqli_insert_id($conn);

				$result=123;
	
	}
	 } else {
		 $result = "Mobile No Does not exist!";
	 }
} else if(email_exists($id)) {
			$passwordHandler = new PasswordController();
			$result = $passwordHandler->postEmail($id);
} else {
    $result = 'User does not exist in the system.';

	 }
}	 


if(!empty($_POST["otp"])) {
	$result_check = sqlStatement("SELECT * FROM otp_expiry WHERE otp='" . $_POST["otp"] . "' AND is_expired!=1 AND NOW() <= DATE_ADD(create_at, INTERVAL 24 HOUR)");
	
	$count  = sqlNumRows($result_check);
	if(!empty($count)) {
		
		$result_otp = sqlQuery("UPDATE otp_expiry SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
		$result = json_encode(array("result" => "12", "otp" => $_POST["otp"]));		
	} else {
		$result = json_encode(array("result" => "11", "message" =>"Invalid OTP!"));;
		$error_message = "Invalid OTP!";
	}	
}

echo $result;
    /**
     * Check if the given email already exists in the DB
     *
     * @param $email string the email to check
     */
    function email_exists($email)
    {
        if (!empty($email)) {
            $ret_val = false;

            $query = sprintf(
                "SELECT email FROM users WHERE username = '$email'"
            );

            $result_email = sqlStatement($query);
            $num_rows = sqlNumRows($result_email);
            if ($num_rows > 0) {
                //email exists
                $ret_val = true;
            }
            return $ret_val;
        }
    }
	
	
function SendSMS($number,$otp)
{
	                     $user = 'kavaii';
						 $password = '12345';
						 $sender_id = 'KAVAII';//helloz welcom FAPcop abhiii'hiiiii
						 $sender = $number;//9673776599 9320491970
						 $msg = $otp.' is the Onetime password (OTP) to reset your password over Web Portal. This is usable and valid for 30 mins form the request. PLS DO NOT SHARE WITH ANYONE';

						 //$msg.=$event_date;
						 $priority = 'sdnd';
						 $sms_type = 'normal';
						 //$data = array('user'=>$user, 'pass'=>$password, 'sender'=>$sender_id, 'phone'=>$sender, 'text'=>$msg,  'stype'=>$sms_type);//'priority'=>$priority,
						 $data='user='.$user.'&pass='.$password.'&sender='.$sender_id.'&phone='.$sender.'&text='.$msg.'&stype='.$sms_type.'&priority=sdnd'; 
						 
						 //http://bhashsms.com/api/sendmsg.php?user='kavaii'&pass='12345'&sender='KAVAII'&phone='9782364064'&text='Hii'&stype='normal'&priority='sdnd'
						 
						 //http://bhashsms.com/api/sendmsg.php?user=kavaii&pass=12345&sender=kavaii%20&phone=9731960662%20&text=hii%20&priority=sdnd&stype=normal
						 $ch = curl_init('http://bhashsms.com/api/sendmsg.php?'.$data);
						 curl_setopt($ch, CURLOPT_POST, true);
						 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						 
						 try {
						  $response = curl_exec($ch);
						  
						  curl_close($ch);
						 
						  return 1;
						 }catch(Exception $e){
						  echo 'Message: ' .$e->getMessage();
						 }
}
?>