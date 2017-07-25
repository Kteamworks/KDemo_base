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
<meta charset="utf-8"/>
<link href="styling.css" rel="stylesheet"/>
</head>
<body>

<input type="button" VALUE="Back"  style="height:30px;width:70px" onClick="location.href='view.php'">
</br></br></br></br>
<form action="edit.php" method= "post">

<?php
if(isset($_GET['edit']))
{
$id=$_GET['edit'];
$sql2 = "SELECT a.id,a.doctorname,a.roomnum as room,u.username as name,u.user_id as uid FROM allocation a join users u on a.doctorname=u.user_id where a.id='$id'";
$retval1 = sqlStatement($sql2);
$uid = sqlFetchArray($retval1);

$sql = "SELECT * from users where authorized=1 and active=1";
$retval = sqlStatement( $sql);



echo "<select name='username'>";
while ($row = sqlFetchArray($retval)) {
	if($row['user_id'] == $uid['uid']) {
		
	$select = " selected='selected'" ;

	}
	else {
		$select = '';
	}
    echo "<option value='" . $row['user_id'] ."' ".$select.">" . $row['username'] ."</option>";
}
echo "</select>";

 }
 ?>
<?php
if(isset($_GET['edit']))
{
$id=$_GET['edit'];
$res=  sqlStatement("SELECT * From allocation WHERE id='$id'");
$row= mysql_fetch_array($res);
}
if (isset($_POST['username']))
{
$id = $_POST['id'];
$newName=$_POST['username'];
$sql0=sqlStatement("SELECT * from allocation WHERE doctorname='$newName'");
	   if(mysql_num_rows($sql0)>0)
	   {
		   echo "<script>
		    alert('doctorname is already used');
			window.location.href='edit.php?edit=$id';
		   </script>";
		   
	   }
	   else
		{
          sqlStatement("UPDATE allocation SET doctorname = '$newName' WHERE id = '$id'");
	   }	   
echo "<meta http-equiv='refresh' content='0; url= view.php'>";
    
}
?>

<input type="hidden" name="id" value="<?php echo $row[0]; ?>"><br/><br/></br></br>
<input type="submit" name= "newName" style="height:30px;width:70px" value="update">
</form>
</body>



 