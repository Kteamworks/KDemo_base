<?php
session_start();
ini_set('max_execution_time', 0);

//SANITIZE ALL ESCAPES
$fake_register_globals=false;
//STOP FAKE REGISTER GLOBALS
$sanitize_all_escapes=true;
//landing page definition -- where to go if something goes wrong
$landingpage = "index.php?site=".$_SESSION['site_id'];
//
// kick out if patient not authenticated
if ( isset($_SESSION['pid']) && isset($_SESSION['patient_portal_onsite']) ) {
  $pid = $_SESSION['pid'];
}
else {
  session_destroy();
  header('Location: '.$landingpage.'&w');
  exit;
}
//
$ignoreAuth = 1;
global $ignoreAuth;
 include_once("../interface/globals.php");
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
<form method="post" action="Paytm/pgRedirect.php" id="form1">
<input type="hidden" id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="">
<input type="hidden" id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?php echo $pid ?>">
<input type="hidden" id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail">
<input type="hidden" id="CHANNEL_ID" tabindex="4" maxlength="12" size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">
<input type="hidden" title="TXN_AMOUNT" tabindex="10" type="text" name="TXN_AMOUNT" value="<?php echo $newcrop_user_roles['pr_price']; ?>">
	
</form>

<form method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr" id="form2">

<input type="hidden" name="upload" value="1">
<input type="hidden" name="return" value="http://localhost/KDemo_base/demo/patients/summary_pat_portal.php?pid=<?php echo $pid ?>">
<input type="hidden" name="cmd" value="_xclick">

<input type="hidden" name="business" value="<?php echo  $GLOBALS['paypal_account_email']; ?>">
<input type="hidden" name="item_name" value="Doctor Fees" />
<input type="hidden" name="item_number" id="EIDSAV" value="" />
<input type="hidden" name="amount" value="1" /> 
   <input type="hidden" name="currency_code" value="<?php echo  $GLOBALS['currency_code']; ?>">
</form>

  <ul class="price" id="doctor_price_list">
    <li class="header">Doctor Charges</li>
    <li class="grey">₹ <?php echo $newcrop_user_roles['pr_price']; ?> / visit</li>
    <li class="grey"><button type="button" class="button-paytm" onclick="login()">Pay With Paytm</button></li>
	<li class="grey"><button type="button" class="button-paypal" onclick="loginpaypal()">Pay With PayPal</button></li>
  </ul>
		            
			    <script type="text/javascript">
				    function loginpaypal() {
		  var f = document.getElementById('theform');
  if (!f.form_date.value || !f.form_hour.value || !f.form_minute.value) {
   alert('Please click on "Find Open Appointment" to select a time.');
   return false;
  }
  else {
	  		  var data = $("#theform").serialize();
 // var dataString = 'form_category='+form_category+'&form_date='+form_date+'&form_title='+form_title+'&form_minute='+form_minute+'&form_ampm='+form_ampm+'&form_hour='+form_hour;
  	 		        $.ajax({
                // Where to send request
                url: 'save.event.php',
                // What to send
                data: data,
                // How to send
                type: 'post',
                // What to do when request succeeds
						beforeSend: function(){
			$("#loading").show();
		},
                success: function(response) {
				//	var ords = 'ORDS00000'+response;
        $("#EIDSAV").val(response);
		$("#loading").hide();
                       $('#form2').submit();
		
				}
        });

  }
    }
    function login() {
		  var f = document.getElementById('theform');
  if (!f.form_date.value || !f.form_hour.value || !f.form_minute.value) {
   alert('Please click on "Find Open Appointment" to select a time.');
   return false;
  }
  else {
	  		  var data = $("#theform").serialize();
 // var dataString = 'form_category='+form_category+'&form_date='+form_date+'&form_title='+form_title+'&form_minute='+form_minute+'&form_ampm='+form_ampm+'&form_hour='+form_hour;
  	 		        $.ajax({
                // Where to send request
                url: 'save.event.php',
                // What to send
                data: data,
                // How to send
                type: 'post',
                // What to do when request succeeds
						beforeSend: function(){
			$("#loading").show();
		},
                success: function(response) {
					var ords = 'ORDS00000'+response;
        $("#ORDER_ID").val(ords);
		$("#loading").hide();
                       $('#form1').submit();
		
				}
        });

  }
    }
    </script>    
                <?php 
                }
            }


?>
