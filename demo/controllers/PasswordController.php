<?php
require_once("../../library/classes/Controller.class.php");
require_once("../../library/classes/postmaster.php"); 

class PasswordController extends Controller
{

    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */
	 public function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
    public function postEmail($email)
    {
		
        try {
            $date = date('Y-m-d H:i:s');
			$qry = "SELECT * from users WHERE username = '$email' OR phonecell = '$email'";

			$user = sqlQuery($qry);
            if (isset($user)) {
                $user1 = $user['username'];
            //gen new code and pass
            $code = $this->generateRandomString(60);
			$qry_reset = "SELECT * from password_resets WHERE email = '$user1'";
			$password_reset_table = sqlStatement($qry_reset);

                if (sqlNumRows($password_reset_table)) {
					$password_reset_table = sqlQuery("UPDATE password_resets SET token = '$code', created_at = '$date' where email = '$user1'");
                    // $password_reset_table->token = $code;
                // $password_reset_table->update(['token' => $code]);
                } else {
                    $create_password_reset = sqlQuery("INSERT INTO password_resets (email,token,created_at) values ('$user1','$code','$date')");
					 }
					 $mail = new MyMailer();
					 
    $email_subject=xl('Your Password Reset Link');
    $email_sender="kavaiidev01@gmail.com";
    $mail->AddReplyTo($email_sender, $email_sender);
    $mail->SetFrom($email_sender, $email_sender);
    $mail->Subject = $email_subject;
   // $mail->MsgHTML("<html><body><div class='wrapper'>".$logo."&nbsp;".$message."</div></body></html>");
    $mail->MsgHTML("<html><body><div class='wrapper'><p>Click on the url below to reset your password.<br> <a href='http://demo.medsmart.co.in/".$GLOBALS['webroot']."/interface/reset/reset.php?token=".$code."'>Reset Link</a></p></div></body></html>");
    $mail->IsHTML(true);
    $mail->AltBody = 'Password Reset';
	 $mail->AddAddress($user['email'], 'MedSmart');
	 if ($mail->Send()) {
        return 1;
    } else {
        $email_status = $mail->ErrorInfo;
        error_log("EMAIL ERROR: ".$email_status,0);
        return $email_status;
    }
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
