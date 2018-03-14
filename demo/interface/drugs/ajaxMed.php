<?php
 require_once("../globals.php");
 require_once("$srcdir/acl.inc");
 //require_once("drugs.inc.php");
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/formdata.inc.php");
 require_once("$srcdir/htmlspecialchars.inc.php");
 //$con=mysqli_connect("localhost","asharma","asharphdv123","greencity_test");

if($_POST['id'] && $_POST['action']=='med')
{
$id1=$_POST['id'];
//$id1=mysqli_real_escape_string($con,$id);
$sql=sqlStatement("SELECT Medicine_Type FROM medicine_master WHERE Medicine_Name = '$id1'");

while($row=sqlFetchArray($sql))
{
$id=$row['Medicine_Type'];
$data=$row['Medicine_Type'];
echo '<option value="'.$id.'">'.$data.'</option>';

}
}


if($_POST['id'] && $_POST['action']=='schedule')
{
$id1=$_POST['id'];
//$id1=mysqli_real_escape_string($con,$id);
$sql=sqlStatement("SELECT schedule_h FROM medicine_master WHERE Medicine_Name = '$id1'");

while($row=sqlFetchArray($sql))
{
$id=$row['schedule_h'];
$data=$row['schedule_h'];
echo '<option value="'.$id.'">'.$data.'</option>';

}
}




if($_POST['id'] && $_POST['action']=='manu')
{
$id1=$_POST['id'];
//$id1=mysqli_real_escape_string($con,$id);
$sql=sqlStatement("SELECT Medicine_Manufacturer FROM medicine_master WHERE Medicine_Name = '$id1'");

while($row=sqlFetchArray($sql))
{
$id=$row['Medicine_Manufacturer'];
$data=$row['Medicine_Manufacturer'];
echo '<option value="'.$id.'">'.$data.'</option>';

}
}


if($_POST['id'] && $_POST['action']=='vat')
{
$id1=$_POST['id'];
//$id1=mysqli_real_escape_string($con,$id);
$sql=sqlStatement("SELECT Medicine_Tax FROM medicine_master WHERE Medicine_Name = '$id1'");

while($row=sqlFetchArray($sql))
{
$id=$row['Medicine_Tax'];
$data=$row['Medicine_Tax'];
echo '<option value="'.$id.'">'.$data.'</option>';

}
}
 	
?>