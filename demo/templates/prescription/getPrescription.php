<?php
require_once("../../interface/globals.php");
$qry2 = "SELECT *
FROM prescriptions
WHERE patient_id = ?
AND encounter = ?";
$encounter = $_SESSION["encounter"];
$pid = $_SESSION["pid"];
          $prescription = sqlStatement($qry2, array($pid,$encounter));
          
		   					$data .= '<label>Current visit Prescriptions</label><table class="table table-striped"><thead>
<th>Name </th>
<th>Time </th>
<th>Meal </th>
<th>Duration</th>
<th>Quantity</th>
</thead>
<tbody>';
		   			while ( $pres=sqlFetchArray($prescription)) {
												$prev_drug = $pres['drug'];
						$drug_meal_time = $pres['drug_meal_time'];
						 $times = explode('-',$pres['drug_intervals']);
  $time1 = $times[0];
    $time2 = $times[1];
	  $time3 = $times[2];
	if($time1 == 0.5) {
		$f1= '<span>&#189;</span>';
	} else {
		$f1 = $time1;
	}
	if($time2 == 0.5) {
		$f2= '<span>&#189;</span>';
	} else {
		$f2 = $time2;
	}	if($time3 == 0.5) {
		$f3= '<span>&#189;</span>';
	} else {
		$f3 = $time3;
	}
$interval= $f1.'-'.$f2.'-'.$f3;
						$duration = $pres['duration'];
						$time_frame = $pres['time_frame'];
						$days = $pres['days'];
if($time_frame == 0) {
$time = 'N/A';
} elseif($time_frame == 1) {
	$time = 'Day(s)';
}
elseif($time_frame == 2) {
	$time = 'Weeks(s)';
}
elseif($time_frame == 3) {
	$time = 'Month(s)';
}
elseif($time_frame == 4) {
	$time = 'Year(s)';
}
$data .=
'<tr><td> '.$prev_drug.'</td><td>'.$interval.' </td><td>'.$drug_meal_time.'</td><td> '.$duration.' '.$time.'</td><td>'.$days.'</td><tr>';

					}
					$data .='</tbody></table>';
					echo $data;
					?>