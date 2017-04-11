<?php
//extract ($_POST);
$s=$_POST['s'];  //get value from ajax
$filename='zp.csv'; //zipcode csv file(must reside in same folder)
$f = fopen($filename, "r");
$arealist=array();

while ($row = fgetcsv($f))
{
if ($row[1] == $s) //1 mean number of column of zipcode file
{
//$district[] = $result->$element;
//$district=array_push($district,$row[3]);
$city=$row[3];
$arealist[]=$row[0];
$state=$row[2]; //4-Number of state column
}
}
fclose($f);
echo json_encode(
array("dist" => $city,
"arealist" => $arealist,
"state"=>$state,
"zip" => $s)
);  //Pass those details by json
?>