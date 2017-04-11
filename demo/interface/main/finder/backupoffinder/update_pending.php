<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
?>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 1px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
$encounter = $_GET["encounter"] ? $_GET["encounter"] : $_GET["encounter"];
$orderid = $_GET["orderid"] ? $_GET["orderid"] : $_GET["orderid"];
$name = $_GET["name"] ? $_GET["name"] : $_GET["name"];
if($name=="Pending")
{
$status="pending";
sqlStatement("UPDATE procedure_order SET order_status='".$status."' WHERE procedure_order_id='".$orderid."'");
}else if($name=="Cancelled")
{
$status="cancelled";
sqlStatement("UPDATE procedure_order SET order_status='".$status."' WHERE procedure_order_id='".$orderid."'");
}else if($name=="Complete")
{
$status="complete";
sqlStatement("UPDATE procedure_order SET order_status='".$status."' WHERE procedure_order_id='".$orderid."'");
}else if($name=="Routed")
{
$status="routed";
sqlStatement("UPDATE procedure_order SET order_status='".$status."' WHERE procedure_order_id='".$orderid."'");
}else if($name=="Collected")
{
$status=1;
sqlStatement("UPDATE procedure_report SET report_collected='".$status."' WHERE procedure_order_id='".$orderid."'");
}else if($name=="Sample Received")
{
$order=sqlStatement("SELECT * from procedure_sample where procedure_order_id='".$orderid."'");
$order1=sqlFetchArray($order);	
if($order1==0)
{
$address = "{$GLOBALS['rootdir']}/main/finder/p_dynamic_finder_lab.php";
$message="The Sample is not yet Collected";
echo"<script type='text/javascript'>alert('$message');top.restoreSession();window.location='$address';</script>";
	
}else
{
$status="received";
$today = date('Y-m-d H:i:s',strtotime("+0 days"));
sqlStatement("UPDATE procedure_order SET order_status='".$status."',sample_received_by='".$_SESSION["authUser"]."',sample_received_time='".$today."' WHERE procedure_order_id='".$orderid."'");
}
}
if($name=="Collected")
{
	$address = "{$GLOBALS['rootdir']}/main/finder/p_dynamic_finder_lab_report.php";
echo"<script type='text/javascript'>top.restoreSession();window.location='$address';</script>";
	
}else
{
$address = "{$GLOBALS['rootdir']}/main/finder/p_dynamic_finder_lab.php";
echo"<script type='text/javascript'>top.restoreSession();window.location='$address';</script>";
}

?>

</body>
</html>