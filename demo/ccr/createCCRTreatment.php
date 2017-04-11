<?php
/**
 * CCR Script.
 *
 * Copyright (C) 2010 Garden State Health Systems <http://www.gshsys.com/>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Garden State Health Systems <http://www.gshsys.com/>
 * @link    http://www.open-emr.org
 */


$result = getAdmData();
$row = sqlFetchArray($result);
$result2 = getClinicalData();
$row2 = sqlFetchArray($result2);
do {

//while ($row = sqlFetchArray($result)) {

	$e_Treatment = $ccr->createElement('Treatment');
	$e_Treatments->appendChild($e_Treatment);

	$e_CCRDataObjectID = $ccr->createElement('CCRDataObjectID', getUuid());
	$e_Treatment->appendChild($e_CCRDataObjectID);




	$e_DateTime = $ccr->createElement('DateTime');
	$e_Treatment->appendChild($e_DateTime);	
	$date = date_create($row['admit_date']);
	$e_ExactDateTime = $ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
	$e_DateTime->appendChild($e_ExactDateTime);


    $e_DateTime1 = $ccr->createElement('DateTime1');
	$e_Treatment->appendChild($e_DateTime1);
	
	$date1 = date_create($row['discharge_date']);
	
	$e_ExactDateTime= $ccr->createElement('ExactDateTime', $date1->format('Y-m-d\TH:i:s\Z'));
	
	$e_DateTime1->appendChild($e_ExactDateTime);
	
	


	/* $e_DateTime1 = $ccr->createElement('DateTime1');
	$e_Treatment->appendChild($e_DateTime1);
	
	$date1 = date_create($row['discharge_date']);
	
	$e_ExactDateTime1 = $ccr->createElement('ExactDateTime1', $date1->format('Y-m-d\TH:i:s\Z'));
	$e_DateTime1->appendChild($e_ExactDateTime1); */

	
	$e_IDs = $ccr->createElement('IDs');
	$e_Treatment->appendChild($e_IDs);

	$e_ID = $ccr->createElement('ID', $row['pid']);
	$e_IDs->appendChild($e_ID);

	$e_IDs->appendChild(sourceType($ccr, $sourceID));
	
	

	$e_Diagnosis = $ccr->createElement('Diagnosis');
	$e_Treatment->appendChild($e_Diagnosis);

	$e_Text = $ccr->createElement('Text', $row['diagnosis']);
	$e_Diagnosis->appendChild($e_Text);
	
	$e_Consultant = $ccr->createElement('Consultant');
	$e_Treatment->appendChild($e_Consultant);

	$e_Text = $ccr->createElement('Text', $row['provider']);
	$e_Consultant->appendChild($e_Text);

	$e_Code = $ccr->createElement('Code');
	$e_Diagnosis->appendChild($e_Code);

	$e_Value = $ccr->createElement('Value', $row['diagnosis']);
	$e_Code->appendChild($e_Value);
	
	$e_Treatment->appendChild(sourceType($ccr, $sourceID));
	
	$e_Agent = $ccr->createElement('Agent');
	$e_Treatment->appendChild($e_Agent);
	
	$e_EnvironmentalAgents = $ccr->createElement('EnvironmentalAgents');
	$e_Agent->appendChild($e_EnvironmentalAgents);

	$e_EnvironmentalAgent = $ccr->createElement('EnvironmentalAgent');
	$e_EnvironmentalAgents->appendChild($e_EnvironmentalAgent);
  
	$e_CCRDataObjectID = $ccr->createElement('CCRDataObjectID', getUuid());
	$e_EnvironmentalAgent->appendChild($e_CCRDataObjectID);
	
	$e_DateTime = $ccr->createElement('DateTime');
	$e_EnvironmentalAgent->appendChild($e_DateTime);

	$e_ExactDateTime = $ccr->createElement('ExactDateTime', $row['date']);
	$e_DateTime->appendChild($e_ExactDateTime);
	
	$e_Diagnosis = $ccr->createElement('Diagnosis' );
	$e_EnvironmentalAgent->appendChild($e_Diagnosis);

	$e_Text = $ccr->createElement('Text', $row['diagnosis']);
	$e_Diagnosis->appendChild($e_Text);
	
	$e_Code = $ccr->createElement('Code');
	$e_Diagnosis->appendChild($e_Code);

	$e_Value = $ccr->createElement('Value');//,$row['codetext']
	$e_Code->appendChild($e_Value);

	$e_Status = $ccr->createElement('Status');
	$e_EnvironmentalAgent->appendChild($e_Status);

	$e_Text = $ccr->createElement('Text',$row['provider']);
	$e_Status->appendChild($e_Text);
	
	$e_EnvironmentalAgent->appendChild(sourceType($ccr, $sourceID));

	$e_Reaction = $ccr->createElement('Reaction');
	$e_Treatment->appendChild($e_Reaction);
	
	$e_Diagnosis = $ccr->createElement('Diagnosis');
	$e_Reaction->appendChild($e_Diagnosis);
	
	$e_Text = $ccr->createElement('Text', $row['reaction']);
	$e_Diagnosis->appendChild($e_Text);
	
	$e_Status = $ccr->createElement('Status');
	$e_Reaction->appendChild($e_Status);
	
	$e_Text = $ccr->createElement('Text', 'None');
	$e_Status->appendChild($e_Text); 

	} while ($row = sqlFetchArray($result));
	//}

?>
