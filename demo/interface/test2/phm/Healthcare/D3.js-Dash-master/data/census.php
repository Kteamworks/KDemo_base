<?php 
$username='cjoshi'; 
$password='kavaii123';   
$host='103.230.38.89';
$database='openemr';
$server = mysql_connect($host, $username, $password);
$connection = mysql_select_db($database, $server);
//weekly inpatient status
//$myquery = 'select admit_to_ward as Facility,count(*) as Census, admit_to_bed as Digit2 from t_form_admit where date BETWEEN CURDATE()-INTERVAL 1 WEEK AND CURDATE() group by admit_to_ward';
$myquery = "select admit_to_ward as Facility,count(*) as Census, admit_to_bed as Digit2 from t_form_admit where status in ('admit') group by admit_to_ward";

$query = mysql_query($myquery);
if ( ! $myquery ) {
echo mysql_error();
die;
}
$data = array();
for ($x = 0; $x < mysql_num_rows($query); $x++) {
$data[] = mysql_fetch_assoc($query);
}
echo json_encode($data);
mysql_close($server);


?> 

