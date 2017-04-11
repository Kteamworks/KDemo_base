<?php
include('db.php');
$did=$_REQUEST['name'];


$sql=mysql_query("select * from users where specialty='$did'");
echo '<select name="form_provider" id="p1">';
while($row=mysql_fetch_array($sql))
{
$usname=$row['fname'];
$ulname=$row['lname'];
$un=$usname. " ".$ulname;
$usrid=$row['id'];
echo '<option value="'.$usrid.'">'.$un.'</option>';

}
echo '</select>';


?>
