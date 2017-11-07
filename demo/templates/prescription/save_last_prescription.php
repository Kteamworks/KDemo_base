<?php
require_once("../../interface/globals.php");
$qry2 = "SELECT * from prescriptions join (
SELECT distinct form_encounter.encounter FROM `prescriptions` left join form_encounter on prescriptions.encounter=form_encounter.encounter
and  patient_id = ? 
ORDER by patient_id, encounter desc limit 1,1)a on prescriptions.encounter=a.encounter";

$encounter = $_SESSION["encounter"];
$pid = $_SESSION["pid"];
          $prescription = sqlStatement($qry2, array($pid));
          
		   
		   			while ( $pres=sqlFetchArray($prescription)) {
						$qry = 'INSERT INTO prescriptions (patient_id,date_added,encounter,drug_id,drug,form,dosage,drug_meal_time,size,unit,route,drug_intervals,note,duration) values ("'.$pid.'","'. date('Y-m-d h:i:s').'","' . $encounter . '","'.$pres['drug_id'].'","'.$pres['drug'].'","'.$pres['form'].'","'.$pres['dosage'].'","'.$pres['drug_meal_time'].'","'.$pres['size'].'","'.$pres['unit'].'","'.$pres['route'].'","'.$pres['drug_intervals'].'","'.$pres['note'].'","'.$pres['duration'].'")';
						    $qry_res = sqlStatement($qry);
					}
							 if ($qry_res) {
        $arr = array('msg' => "Prescription saved Successfully!");
        $jsn = json_encode($arr);
        print_r($jsn);
    } else {
		$dberr = mysql_error();
        $arr = array('msg' => "", 'error' => 'Error In inserting record');
        $jsn = json_encode($arr);
        print_r($jsn);
		print_r($dberr);
    }
?>