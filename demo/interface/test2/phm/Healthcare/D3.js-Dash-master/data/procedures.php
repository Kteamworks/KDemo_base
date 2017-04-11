<?php 

$username='cjoshi'; 
$password='kavaii123';   
$host='103.230.38.89';
$database='openemr';
$server = mysql_connect($host, $username, $password);
$connection = mysql_select_db($database, $server);

//for last 48 hours
$myquery = 'select code Description,code Provider,code ProviderName,count(*) radius,bill_id LocationID,bill_id LocationName from billing where date >
date_sub(curdate(),interval 2 day) and activity=1 
group by code 
order by radius
DESC  Limit 5';
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
<?php
/*
  // Specify database from $arg[1] //
    $dir = 'sqlite:livedb.sqlite';

    // Instantiate PDO connection object or failure msg //
    $dbh = new PDO($dir) or die("cannot open database");

    // Define your SQL statement //
    $query = 'SELECT * FROM procedures WHERE radius > 2 AND radius < 25 LIMIT 25';
    
    // Iterate through the results and pass into JSON encoder //
    
    $json = array(); 
    
    foreach ($dbh->query($query) as $row) {

        $item = array(
            'CPT' => (string)$row[0],
            'Description' => (string)$row[1],
            'Provider' => (string)$row[2],
            'ProviderName' => (string)$row[3],
            'radius' => (string)$row[4],
            'LocationID' => (string)$row[5],
            'LocationName' => (string)$row[6]
        );

        $json[] = $item;

    }
        echo json_encode($json);


		?> 

*/