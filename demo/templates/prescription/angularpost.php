<?php
require_once("../../interface/globals.php");
$data = file_get_contents("php://input");

// $usrname = mysql_real_escape_string($data->uname);
$ptid = $pid;
$values = json_decode($data);
$drug_intervals = $values->take1.'-'.$values->take2.'-'.$values->take3;
$drug_units = explode('-',$values->units);
$drug_dosage = $drug_units[0];
$drug_units = $drug_units[1];
// $con = mysql_connect('localhost', 'root', '');
$drug_id = implode(';', $values->drugId);

// mysql_select_db('mhat', $con);

	$qry_em = 'select count(*) as cnt from prescriptions where (drug_id ="' . $drug_id . '" AND patient_id = "' . $ptid . '" AND encounter= "'.$encounter.'")';
	    $qry_res1 = sqlStatement($qry_em);
if($qry_res1 == 'false') {
	echo "false statement";
}
$res = mysql_fetch_assoc($qry_res1);

if ($res['cnt'] == 0) {
	$drugs_tmp = 'select * from drug_templates where drug_id ="' . $drug_id . '"  limit 1';
		$drug_tmp1 = sqlStatement($drugs_tmp);
	$drug = 'select * from drugs where drug_id ="' . $drug_id . '"  limit 1';
	$drugs1 =  sqlStatement($drug);
	$drugs = mysql_fetch_object($drugs1);
$drug_tmp = mysql_fetch_object($drug_tmp1);
	
	$qry = 'INSERT INTO prescriptions (patient_id,date_added,encounter,drug_id,drug,form,dosage,drug_meal_time,size,unit,route,drug_intervals,note,duration,time_frame) values ("' . $ptid . '","'. date('Y-m-d h:i:s').'","' . $encounter . '","' . $drug_id . '","' .$drugs->name. '","' .$values->dosagetype. '","' .$drug_dosage. '","' .$values->quantity. '","' .$drugs->size. '","1","' .$drugs->route. '","' .$drug_intervals. '","' .$values->note. '","'. $values->duration .'","'. $values->time_frame .'")';
	
    $qry_res = sqlStatement($qry);
	
    if ($qry_res) {
        $arr = array('msg' => "Data saved Successfully!!!", 'error' => '');
        $jsn = json_encode($arr);
        print_r($jsn);
    } else {
		$dberr = mysql_error();
        $arr = array('msg' => "", 'error' => 'Error In inserting record');
        $jsn = json_encode($arr);
        print_r($jsn);
		print_r($dberr);
    }
	} else {
    $arr = array('msg' => "", 'error' => 'Record exists');
    $jsn = json_encode($arr);
    print_r($jsn);
}


	?>
