
<?php

 require_once("../globals.php");
 require_once("$srcdir/patient.inc");
 require_once("$srcdir/options.inc.php");
 require_once("../drugs/drugs.inc.php");
 require_once("$srcdir/formatting.inc.php");
 require_once "$srcdir/formdata.inc.php";

require_once("$srcdir/classes/postmaster.php"); 

        $message='<div id="report_results"><table> <thead><th>' . htmlspecialchars(xl('Patient'), ENT_NOQUOTES). 
         ' </th><th>'.         
		htmlspecialchars(xl('ID'), ENT_NOQUOTES). 
		'  </th><th>'.		
		htmlspecialchars(xl('RX'), ENT_NOQUOTES). 
		' </th><th>'.
		htmlspecialchars(xl('Drug Name'), ENT_NOQUOTES). 
		' </th><th>'.
		htmlspecialchars(xl('NDC'), ENT_NOQUOTES). 
		' </th><th>'.
		htmlspecialchars(xl('Units'), ENT_NOQUOTES). ' </th><th>'.
		htmlspecialchars(xl('Refills'), ENT_NOQUOTES). ' </th><th>'.
		htmlspecialchars(xl('Instructed'), ENT_NOQUOTES). ' </th><th>'.
		htmlspecialchars(xl('Reactions'), ENT_NOQUOTES). ' </th><th>'.
		htmlspecialchars(xl('Qty'), ENT_NOQUOTES). ' </th><th>'.
		
		$message .='</thead></table></div>'; 
		$mail = new MyMailer();
		$email_subject=xl('Test Prescriptions');
		$email_sender="msusdrive@gmail.com";
		$mail->AddReplyTo($email_sender, $email_sender);
		$mail->SetFrom($email_sender, $email_sender);
		$mail->AddAddress("chandnijoshi09@gmail.com", $pt_name);
		$mail->Subject = $email_subject;
		$mail->MsgHTML("<html><body><div class='wrapper'>".$message."</div></body></html>");
		$mail->IsHTML(true);
		$mail->AltBody = $message;
		if ($mail->Send()) {
        return true;
    } else {
        $email_status = $mail->ErrorInfo;
        error_log("EMAIL ERROR: ".$email_status,0);
        return false;
    }		    
	
	?>