<?php 

 require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
 

//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
if (isset($_REQUEST['query'])) {
    $query = $_REQUEST['query'];
    $sql = sqlStatement ("SELECT genericname1,phone_cell, fname FROM patient_data WHERE genericname1 LIKE '%{$query}%' OR fname LIKE '%{$query}%' OR phone_cell LIKE '%{$query}%'");
	$array = array();
    while ($row = sqlFetchArray($sql)) {
        $array[] = array (
            'label' => $row['genericname1'].', '.$row['fname'].', '.$row['phone_cell'],
            'value' => $row['fname'],
        );
    }
    //RETURN JSON ARRAY
    echo json_encode ($array);
}

?>