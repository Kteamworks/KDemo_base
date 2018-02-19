<?php

 include_once("../../globals.php");
 include_once("$srcdir/formdata.inc.php");

        // In the ajax, I told it to use $_POST, so change to
        // post instead of get
        if(isset($_POST['did'])) {
            $id=$_POST['did'];
            // Don't do this anymore.............vvv
			$user_id = sqlQuery("select user_id from users where id='$id'");
			$user = $user_id['user_id'];
           $newcrop_user_role=sqlStatement("select code,code_text,pr_price from codes a,prices b where code_type=8 and a.id = b.pr_id and pr_level='standard' and a.code='$user'"); 

							  while($newcrop_user_roles = sqlFetchArray($newcrop_user_role)){								                
                ?>

  <ul class="price" id="doctor_price_list">
    <li class="header">Doctor Charges</li>
    <li class="grey">₹ <?php echo $newcrop_user_roles['pr_price']; ?> / visit</li>

  </ul>
		            

                <?php 
                }
            }


?>
