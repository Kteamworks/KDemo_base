<?php 

 require_once("../globals.php");
 require_once("$srcdir/patient.inc");
 require_once("$srcdir/options.inc.php");
 require_once("../drugs/drugs.inc.php");
 require_once("$srcdir/formatting.inc.php");
 require_once "$srcdir/formdata.inc.php";
 
$query = "SELECT * FROM billing limit 10";

$res = sqlStatement($query);
if (!$res) die('Couldn\'t fetch records');
$headers = $res->fetch_fields();

foreach($headers as $header) {
    $head[] = $header->name;
}
$fp = fopen('export.csv', 'w');

if ($fp && $res) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, array_values($head)); 
    while ($row =sqlFetchArray($res) ) {
        fputcsv($fp, array_values($row));
    }
    die;
}

?>