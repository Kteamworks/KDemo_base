<?php 

    // Specify database from $arg[1] //
    $dir = 'sqlite:livedb.sqlite';

    // Instantiate PDO connection object or failure msg //
    $dbh = new PDO($dir) or die("cannot open database");

    // Define your SQL statement //
    $query = '
SELECT AdmPriorityName, AdmDateTime , Count(EmrID)
FROM EmergencyDept
WHERE AdmPriorityName = \'EMERGENCY\'
GROUP BY AdmDateTime, AdmPriorityName
ORDER BY AdmDateTime ASC ';
    
    // Iterate through the results and pass into JSON encoder //
    
    $json = array(); 
    
    foreach ($dbh->query($query) as $row) {

        $item = array(
            'AdmPriority' => (string)$row[0],
            'AdmDate' => (string)$row[1],
            'Count' => (string)$row[2]
        );

        $json[] = $item;

    }
        echo json_encode($json, JSON_UNESCAPED_SLASHES);
?> 

