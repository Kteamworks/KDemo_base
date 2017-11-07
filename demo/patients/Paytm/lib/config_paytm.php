<?php
/*

- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.

*/
$ignoreAuth = 1;
global $ignoreAuth;
require_once("../../interface/globals.php");
 if ($GLOBALS['payment_flag'] ==1 ) {
	 $env = 'PROD';
 } else {
	 $env = 'TEST';
 }
define('PAYTM_ENVIRONMENT', $env); // PROD
define('PAYTM_MERCHANT_KEY', '2z3ISloH_nFWerut'); //Change this constant's value with Merchant key downloaded from portal
define('PAYTM_MERCHANT_MID', 'Etutor95965343424559'); //Change this constant's value with MID (Merchant ID) received from Paytm
define('PAYTM_MERCHANT_WEBSITE', 'WEB_STAGING'); //Change this constant's value with Website name received from Paytm
define('PAYTM_REDIRECT_URL', "http://".$_SERVER["HTTP_NAME"]."".$GLOBALS['webroot']."/patients/summary_pat_portal.php"); //Change this constant's value with Website name received from Paytm

$PAYTM_DOMAIN = "pguat.paytm.com";
if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_DOMAIN = 'secure.paytm.in';
}

define('PAYTM_REFUND_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/REFUND');
define('PAYTM_STATUS_QUERY_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/TXNSTATUS');
define('PAYTM_STATUS_QUERY_NEW_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/getTxnStatus');
define('PAYTM_TXN_URL', 'https://'.$PAYTM_DOMAIN.'/oltp-web/processTransaction');

?>