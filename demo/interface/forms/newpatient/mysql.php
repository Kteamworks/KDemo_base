<?php 

//CREDENTIALS FOR DB
define ('DBSERVER', 'localhost');
define ('DBUSER', 'root');
define ('DBPASS','');
define ('DBNAME','mangoo');

//LET'S INITIATE CONNECT TO DB
$connection = mysql_connect(DBSERVER, DBUSER, DBPASS) or die("Can't connect to server. Please check credentials and try again");
$result = mysql_select_db(DBNAME) or die("Can't select database. Please check DB name and try again");

//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
if (isset($_REQUEST['query'])) {
    $query = $_REQUEST['query'];
    $sql = mysql_query ("SELECT genericname1, fname FROM patient_data WHERE genericname1 LIKE '%{$query}%' OR fname LIKE '%{$query}%'");
	$array = array();
    while ($row = mysql_fetch_array($sql)) {
        $array[] = array (
            'label' => $row['genericname1'].', '.$row['fname'],
            'value' => $row['fname'],
        );
    }
    //RETURN JSON ARRAY
    echo json_encode ($array);
}

?>