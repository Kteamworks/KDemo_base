<?php 
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");

?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="mystyle.css" rel="stylesheet"/>
</head>
<body>
 <?php
 /*$localhost = 'localhost';
$user = 'root';
$password = '';
$conn = mysql_connect($localhost, $user, $password);

mysql_select_db('mangoo');*/
/*if(! $conn ) {
die('Could not connect: ' . mysql_error());
}*/
if(isset($_POST['add'])){
	 $doctor=$_POST['username'];
	   $room=$_POST['title'];
	   $sql0=sqlStatement("SELECT * from allocation WHERE roomnum='$room'");
	   //echo "SELECT * from allocation WHERE roomnum='$room'";
	   
	   if(mysql_num_rows($sql0)>0)
	   {
		   echo "<script>
		    alert('Room no is already used');
			window.location.href='create.php';
		   </script>";
		   exit;
	   }
	   else
	   {
$sql2 = "INSERT INTO allocation ". "(doctorname,roomnum) ". "VALUES('$doctor','$room')";
header('location:view.php');
	   }
//mysql_select_db('mangoo');
$retval2 = sqlStatement( $sql2);
/*if(! $retval2 ) {
die('Could not enter data: ' . mysql_error());
}*/
echo "Entered data successfully\n";
echo "<meta http-equiv='refresh' content='0; url= view.php'>";
}
$sql = "select * from users where authorized=1 and active=1";
$retval = sqlStatement( $sql );

?>
<INPUT Type="button" VALUE="Back"  style="height:30px;width:70px" onClick="location.href='view.php'">
<form  method = "post" action = "<?php $_PHP_SELF ?>">
<?php

echo "<select name='username'>";
while ($row = sqlFetchArray($retval)) {
    echo "<option value='" . $row['user_id'] ."'>" . $row['username'] ."</option>";
}
echo "</select>";
 ?>
 </br></br>
 <?php
 $sqll = "select * from list_options where list_id='patient_flow_board_rooms' order by seq";
$retvall = sqlStatement( $sqll, $conn );

echo "<select name='title'>";
while ($row2 = sqlFetchArray($retvall)) {
    echo "<option value='" . $row2['title'] ."'>" . $row2['title'] ."</option>";
}
echo "</select>";
 ?>
 </br></br></br></br></br></br>
 <div class= "wrapper">
<input name = "add" style="height:30px;width:70px" type = "submit" id = "add"
value = "Save">
</div>
</form>
 <?php
	
?>
 </body>
</html>



