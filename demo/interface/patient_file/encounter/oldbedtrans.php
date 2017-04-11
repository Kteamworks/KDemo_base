.................................
<p><span class="forms-title"><?php echo xlt('Position of Empty Beds'); ?></span></p>
</br>


<?php
echo "<form method='post' name='my_form' " .
  "action='$rootdir/patient_file/encounter/transfer_form.php?id=" . attr($formid) ."'>\n";





$q = intval($_GET['q']);

$con = mysqli_connect('localhost','root','','openemr');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
$wards="wards";
mysqli_select_db($con,"openemr");
//$sql="SELECT * FROM list_options WHERE list_id='".General."' AND is_default = 0";
//$result = mysqli_query($con,$sql);

echo "<table>";


    $sql="SELECT * FROM list_options WHERE list_id IN ('".General."','".Semi_General."','".OT."') AND is_default IN(0,3)";
	$result = mysqli_query($con,$sql);
	$i=0;
	while($row=mysqli_fetch_array($result))
	{
    echo "<tr>";
	$a=$row['title'];
    $b=$row['option_id'];
	$aw=$row['list_id'];
	$status=$row['is_default'];
    echo  "<td><b>"."<input type=\"hidden\" name=\"admit_to_ward\" value=\"$aw\" readonly></input>"."$aw</b></td>";
    echo  "<td>"."<input type=\"hidden\" name=\"admit_to_bed\" value=\"$a\" readonly></input>"."</td>";
	if($status==3)
	{echo  "<td>"."<label><img src=\"bed.png\" height='42', width='40'></lable>"."  Waiting"."</td>";	}
	else 
	{echo  "<td>"."<label><input type=\"radio\" name=\"adm_to\" value=\"$b\"></input><img src=\"bed.png\" height='42', width='40'></label>"."</td>";}
	
	}
    
	echo "</tr>";
echo "</table>";?>

<b><center><input type="submit" name="" value="Admit" onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'"></input></center>
<?php
echo "</div>";
mysqli_close($con);
?>