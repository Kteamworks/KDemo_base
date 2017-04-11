<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
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
<body class="body_top">

<?php
$q = intval($_GET['q']);

$con = mysqli_connect('localhost','root','admin123','greencity');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
$wards="wards";
mysqli_select_db($con,"greencity");


echo "<table>
<tr>
<th>Patient</th>
<th>Encounter</th>
<th>Department</th>
<th>Ward</th>
<th>Bed</th>

<th>Admit Date</th>
<th>Discharge Date</th>
<th>Duration Of Stay</th>
<th>Diagnosis</th>
<th></th>
</tr>";
    $adm='discharge';
	$sql2="select * from t_form_admit where status='".$adm."'";
	$result2=mysqli_query($con,$sql2);

	while($row2=mysqli_fetch_array($result2))
	{
	$dur = abs(strtotime($row2['discharge_date']) - strtotime($row2['admit_date']));
	$years = floor($dur / (365*60*60*24));
	$months = floor(($dur - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($dur - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    echo "<tr>";
	echo "<td>" . $row2['client_name'] . "</td>";
	echo "<td>" . $row2['encounter'] . "</td>";
	echo "<td>" . "" . "</td>";
	echo "<td>" . $row2['admit_to_ward'] . "</td>";
    echo "<td>" . $row2['admit_to_bed'] . "</td>";
    echo "<td>".$row2['admit_date']."</td>";
    echo "<td>".$row2['discharge_date']."</td>"; 
	echo "<td>".$days." days"."</td>";
	echo "<td>".$row2['diagnosis']."</td>";
	echo "<td>" ."<input type=\"submit\" name=\'fd\' value=\"Final Discharge\"></input>"."</td></tr>";
	}
echo "</table>";




mysqli_close($con);
?>
</body>
</html>