
<?php
include_once("dbconnect.php");
error_reporting(0);
if(isset($_REQUEST['id']))
	$id=$_REQUEST['id'];
else
	$id="";
	
$fetch_query="select * from form_vitals where id=$id";

$rid=mysqli_query($conn,$fetch_query);
$info=mysqli_fetch_array($rid);

if(isset($_REQUEST['cancel']))
{
	header("location:treatment.php");
}
	  
if(isset($_REQUEST['update']))
{
$id=$_REQUEST['id'];
$date=$_REQUEST['date'];
$stcollege=$_REQUEST['stcollege'];
$complaints=$_REQUEST['complaints'];
$hb=$_REQUEST['hb'];
$pallor=$_REQUEST['pallor'];
$weight=$_REQUEST['weight'];
$bp=$_REQUEST['bp'];
$oedema=$_REQUEST['oedema'];
$pa=$_REQUEST['pa'];
$pv=$_REQUEST['pv'];
$exam=$_REQUEST['exam'];
$advise=$_REQUEST['advise'];


	 

$fetch_query= "update form_vitals set date='$date',bps='$bp',weight='$weight',note='$advise' where id='$id'";

mysqli_query($conn,$fetch_query) or die(mysqli_error());
 
header("location:treatment.php");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>




</head>
<body>
<?php include_once("common.php");?>
<tr><td colspan="3">

<form action="" method="post" name="editstudent">
<input type="hidden" name="id" value="<?php echo $id;?>" />

<table width="100" align="center" cellspacing="0" cellpadding="3" border="1"> 
  <tr>
    <th colspan="2" id="tr1">Edit Record</th>
    </tr>
  <tr>
    <th width="141">Date</th>
    <td width="388"><input type="text" name="date" value="<?php echo $info['date'];?>" /></td>
  </tr>
  <tr>
    <th>Complaints</th>
    <td><input type="text" name="complaints" value="<?php echo $info['complaint']; ?>"/></td>
  </tr>
  <tr>
    <th>Hb % Urine RE</th>
    <td><input type="text" name="hb" value="<?php echo $info['hb']; ?>" /></td>
  </tr>
  <tr>
    <th>Pallor</th>
    <td><input type='text' name="pallor" rows="7" cols="10"><?php echo $info['pallor']; ?></td>
  </tr>
  
  <tr>
    <th>Weight</th>
    <td><input type="text" name="weight" value="<?php echo $info['weight']; ?>" /></td>
  </tr>
  <tr>
    <th>BP</th>
    <td><input type="text" name="bp" value="<?php echo $info['bps']; ?>" ></td>
  </tr>  
  <tr>
    <th>Oedema</th>
    <td><input type="text" name="oedema" value="<?php echo $info['oedema']; ?>" /></td>
  </tr>
  <tr>
    <th>PA</th>
    <td><input type="text" name="pa" value="<?php echo $info['pa'];?>" /></td>
  </tr>
  <tr>
    <th>PV</th>
    <td><input type="text" name="pv" value="<?php echo $info['pa']; ?>" /></td>
  </tr>
  <tr>
    <th>Examination Findings</th>
   <td><textarea name="exam" rows="7" cols="20"><?php echo $info['exam']; ?></textarea></td>
  </tr>
  <tr>
    <th>Advise & Treatment</th>
     <td><textarea name="advise" rows="7" cols="20"><?php echo $info['note']; ?></textarea></td>
  </tr>
  
  
  <tr>
    <th colspan=2>
      <input type="submit" name="update" value="Update" /> 
	<input type='submit' name='cancel' value='Back'>
	  </th>
    </tr>
</table>
</form>
</td></tr></table>

</body>
</html>