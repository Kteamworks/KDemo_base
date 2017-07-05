<?php 
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
include_once("$srcdir/encounter.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/erx_javascript.inc.php");

?>
<html>
<head>

</head>
<body>

<?php
$q = $_GET['q'];

/*$con = mysqli_connect('localhost','root','','mangoo');
 if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"mangoo"); 
//$sql="SELECT * FROM list_options WHERE list_id='".General."' AND is_default = 0";
//$result = mysqli_query($con,$sql);*/

echo "<table>";


    $sql="SELECT * FROM list_options WHERE list_id ='".$q."' AND is_default IN(0,3)";
	$result = sqlStatement($sql);
	$i=0;
	while($row=sqlFetchArray($result))
	{
    echo "<tr>";
	$a=$row['title'];
    $b=$row['option_id'];
	$aw=$row['list_id'];
	$status=$row['is_default'];
    echo  "<td><b>"."<input type=\"hidden\" name=\"admit_to_ward\" value=\"$aw\" readonly></input>".$b."</b></td>";
    echo  "<td><b>("."<input type=\"hidden\" name=\"admit_to_bed\" value=\"$a\" readonly></input>".$a.")</b></td>";
	if($status==3)
	{echo  "<td>"."<label><img src=\"bed.png\" height='42', width='40'></lable>"."  Waiting"."</td>";	}
	else 
	{
		echo  "<td>"."<label><input type=\"radio\" name=\"adm_to\" value=\"$b\"></input><img src=\"bed.png\" height='42', width='40'></label>"."</td>";}
	
	}
    
	echo "</tr>";
echo "</table>";
?>


</body>
</html> 