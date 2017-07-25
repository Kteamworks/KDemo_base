<?php 
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");

?>
<html>
<head>
<title></title>
<script>

	
	
</script>
 </head>

<?php



if( isset($_GET['del']) )
	{
		$id = $_GET['del'];
		$sql= "DELETE FROM allocation WHERE id='$id'";
		$res= sqlStatement($sql);
		
	}
   echo "<meta http-equiv='refresh' content='0;url=view.php'>";
?>


