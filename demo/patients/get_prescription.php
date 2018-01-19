<?php
 // Copyright (C) 2011 Cassian LUP <cassi.lup@gmail.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

        require_once("verify_session.php");
		//require_once("../../interface/globals.php");

	$sql = "SELECT * FROM prescriptions WHERE patient_id = ?  ORDER BY date_added";
	
	$res = sqlStatement($sql, array($pid) );

	if(sqlNumRows($res)>0)
  	{
  		?>
  		<table class="class1">
  			<tr class="header">
  				<th><?php echo htmlspecialchars( xl('Date'),ENT_NOQUOTES); ?></th>
  				<th><?php echo htmlspecialchars( xl('Medicine'),ENT_NOQUOTES); ?></th>
  				<th><?php echo htmlspecialchars( xl('Time'),ENT_NOQUOTES); ?></th>
  				<th><?php echo htmlspecialchars( xl('Meal'),ENT_NOQUOTES); ?></th>
				<th><?php echo htmlspecialchars( xl('Duration'),ENT_NOQUOTES); ?></th>
  			</tr>
  		<?php
  		$even=false;
  		while ($row = sqlFetchArray($res)) {
  			if ($even) {
  				$class="class1_even";
  				$even=false;
  			} else {
  				$class="class1_odd";
  				$even=true;
  			}
  			echo "<tr class='".htmlspecialchars($class,ENT_QUOTES)."'>";
  			echo "<td>".htmlspecialchars($row['date_added'],ENT_NOQUOTES)."</td>";
  			echo "<td>".htmlspecialchars($row['drug'],ENT_NOQUOTES)."</td>";
  			echo "<td>".htmlspecialchars($row['drug_intervals'],ENT_NOQUOTES)."</td>";
  			echo "<td>".htmlspecialchars($row['drug_meal_time'],ENT_NOQUOTES)."</td>";
			echo "<td>".htmlspecialchars($row['duration'],ENT_NOQUOTES)."Weeks"."</td>";
  			echo "</tr>";
  		}
		echo "</table>";
  	}
	else
	{
		echo htmlspecialchars( xl("No Results"),ENT_NOQUOTES);
	}
?>
