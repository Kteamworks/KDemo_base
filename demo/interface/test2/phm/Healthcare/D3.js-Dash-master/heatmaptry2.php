<?php 

$username='cjoshi'; 
$password='kavaii123';   
$host='103.230.38.89';
$database='openemr';
$server = mysql_connect($host, $username, $password);
$connection = mysql_select_db($database, $server);
$myquery = 'select weekday(regdate) day, hour(regdate) hour, count(*) value from billing_main_copy where pid <> 0 and regdate is not null
group by weekday(regdate), hour(regdate)';
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