<?php 

$username='cjoshi'; 
$password='kavaii123';   
$host='103.230.38.89';
$database='mangoo';
$server = mysql_connect($host, $username, $password);
$connection = mysql_select_db($database, $server);

/* $myquery = 'select weekday(regdate) day, hour(regdate) hour, count(*) value from billing_main_copy where pid <> 0 and regdate is not null
group by weekday(regdate), hour(regdate)';
*/

$myquery = 'select id,yseq,day1 day, hour1 hour, sum(value) value from newloc2 group by day1,hour1 order by day1 desc';

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