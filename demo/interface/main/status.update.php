<?php
require_once("../globals.php");
require_once("$srcdir/formdata.inc.php");

        // In the ajax, I told it to use $_POST, so change to
        // post instead of get
		
        if(isset($_POST['did'])) {
			$user_id = $_SESSION['authUserID'];
            $id=$_POST['did'];
            // Don't do this anymore.............vvv
			if($id == 2) {
			
           sqlQuery("update users SET status='2' where id='$user_id'"); 
		                   ?>
						   <i class="fa fa-circle text-danger"></i> Busy
			<?php }
			else {
				           sqlQuery("update users SET status='1' where id='$user_id'"); 
						   ?>
						   <i class="fa fa-circle text-success"></i> Online
			<?php }
		}			?>