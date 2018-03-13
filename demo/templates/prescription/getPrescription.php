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
						$interval = $pres['drug_intervals'];
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