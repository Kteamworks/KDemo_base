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


	$result = getProblemData();
	$result2=getHisData();
	$result3=getObstetricData();
	$row = sqlFetchArray($result);
	$row2=sqlFetchArray($result2);
	$row3=sqlFetchArray($result3);
	$pCount =0;
	//while ($row = sqlFetchArray($result)) {
	
	do {
		
		$pCount++;

		$e_Problem = $ccr->createElement('Problem');
		$e_Problems->appendChild($e_Problem);

		$e_CCRDataObjectID = $ccr->createElement('CCRDataObjectID', 'PROB'.$pCount);
		$e_Problem->appendChild($e_CCRDataObjectID);

		$e_DateTime = $ccr->createElement('DateTime');
		$e_Problem->appendChild($e_DateTime);
		
		$date = date_create($row['date']);
		
		$e_ExactDateTime = $ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
		$e_DateTime->appendChild($e_ExactDateTime);
		
		$e_IDs = $ccr->createElement('IDs');
		$e_Problem->appendChild($e_IDs);
		
		$e_ID = $ccr->createElement('ID', $row['pid']);
		$e_IDs->appendChild($e_ID);

		$e_IDs->appendChild(sourceType($ccr, $sourceID));
		
		$e_Type = $ccr->createElement('Type');
		$e_Problem->appendChild($e_Type);
    
    $e_Text = $ccr->createElement('Text', 'Problem'); // Changed to pass through validator, Problem type must be one of the required string values: Problem, Condition, Diagnosis, Symptom, Finding, Complaint, Functional Limitation.
		//$e_Text = $ccr->createElement('Text', $row['prob_title']);
    $e_Type->appendChild($e_Text);

		$e_Description = $ccr->createElement('Description' );
		$e_Problem->appendChild($e_Description);

		$e_Text = $ccr->createElement('Text', $row2['a_h']);
		$e_Description->appendChild($e_Text);
		
		
		$ObsHis = "Married Life ".$row3['marriedlife'].  "LMP: ". $row3['lmp']." EDD: ".$row3['edd'];
		$e_ObstetricDesc = $ccr->createElement('ObstetricDesc' );
		$e_Problem->appendChild($e_ObstetricDesc);

		$e_Text = $ccr->createElement('Text', $ObsHis);
		$e_ObstetricDesc->appendChild($e_Text);
		
		$e_PreAbdomen = $ccr->createElement('PreAbdomen' );
		$e_Problem->appendChild($e_PreAbdomen);

		$e_Text = $ccr->createElement('Text', $row3['preabdomen']);
		$e_PreAbdomen->appendChild($e_Text);
		
		
		$e_Surgery = $ccr->createElement('Surgery' );
		$e_Problem->appendChild($e_Surgery);

		$e_Text = $ccr->createElement('Text', $row3['surgery']);
		$e_Surgery->appendChild($e_Text);
		
		
		$e_OtNotes = $ccr->createElement('OtNotes' );
		$e_Problem->appendChild($e_OtNotes);

		$e_Text = $ccr->createElement('Text', $row3['additional_notes']);
		$e_OtNotes->appendChild($e_Text);
		
		
		$e_Findings = $ccr->createElement('Findings' );
		$e_Problem->appendChild($e_Findings);

		$e_Text = $ccr->createElement('Text', $row3['findings']);
		$e_Findings->appendChild($e_Text);
		
		$e_UrineOP = $ccr->createElement('UrineOP' );
		$e_Problem->appendChild($e_UrineOP);

		$e_Text = $ccr->createElement('Text', $row3['urineoutput']);
		$e_UrineOP->appendChild($e_Text);
		
		$e_TreatmentGvn = $ccr->createElement('TreatmentGvn' );
		$e_Problem->appendChild($e_TreatmentGvn);

		$e_Text = $ccr->createElement('Text', $row3['treatmentgiven']);
		$e_TreatmentGvn->appendChild($e_Text);
		
		
		
		$e_CIH = $ccr->createElement('CIH' );
		$e_Problem->appendChild($e_CIH);

		$e_Text = $ccr->createElement('Text', $row3['hospitalization']);
		$e_CIH->appendChild($e_Text);
		
		
		$e_OnExam = $ccr->createElement('OnExam' );
		$e_Problem->appendChild($e_OnExam);

		$e_Text = $ccr->createElement('Text', $row3['onexamination']);
		$e_OnExam->appendChild($e_Text);
		

		$e_Code = $ccr->createElement('Code');
		$e_Description->appendChild($e_Code);

		$e_Value = $ccr->createElement('Value',$row['diagnosis']);
		$e_Code->appendChild($e_Value);
    
    $e_Value = $ccr->createElement('CodingSystem', 'ICD9-CM');
		$e_Code->appendChild($e_Value);
		
		$e_Status = $ccr->createElement('Status');
		$e_Problem->appendChild($e_Status);

		// $e_Text = $ccr->createElement('Text', $row['outcome']);
		$e_Text = $ccr->createElement('Text', 'Active');
		$e_Status->appendChild($e_Text);
		
		//$e_CommentID = $ccr->createElement('CommentID', $row['comments']);
		//$e_Problem->appendChild($e_CommentID);
    
    $e_Source = $ccr->createElement('Source');
		
		$e_Actor = $ccr->createElement('Actor');
		$e_Source->appendChild($e_Actor);
		
		$e_ActorID = $ccr->createElement('ActorID',$uuid);
		$e_Actor->appendChild($e_ActorID);
    
    $e_Problem->appendChild($e_Source);
    
    $e_CommentID = $ccr->createElement('CommentID', $row['comments']);
		$e_Problem->appendChild($e_CommentID);
		
		$e_Episodes = $ccr->createElement('Episodes' );
		$e_Problem->appendChild($e_Episodes);

		$e_Number = $ccr->createElement('Number');
		$e_Episodes->appendChild($e_Number);
	
		$e_Episode = $ccr->createElement('Episode');
		$e_Episodes->appendChild($e_Episode);
	
		$e_CCRDataObjectID = $ccr->createElement('CCRDataObjectID', 'EP'.$pCount);
		$e_Episode->appendChild($e_CCRDataObjectID);

		$e_Episode->appendChild(sourceType($ccr, $sourceID));
		
		$e_Episodes->appendChild(sourceType($ccr, $sourceID));
		
		$e_HealthStatus = $ccr->createElement('HealthStatus' );
		$e_Problem->appendChild($e_HealthStatus);

		$e_DateTime = $ccr->createElement('DateTime');
		$e_HealthStatus->appendChild($e_DateTime);

		$e_ExactDateTime = $ccr->createElement('ExactDateTime' );
		$e_DateTime->appendChild($e_ExactDateTime);

		$e_Description = $ccr->createElement('Description' );
		$e_HealthStatus->appendChild($e_Description);

		$e_Text = $ccr->createElement('Text',$row2['a_h']);
		$e_Description->appendChild($e_Text);
	
		$e_HealthStatus->appendChild(sourceType($ccr, $sourceID));
	
	} while ($row3 = sqlFetchArray($result3));
	//}

	// complex type should go in different find and should be included in createCCR.php
/*
	function sourceType($ccr, $uuid){
		
		$e_Source = $ccr->createElement('Source');
		
		$e_Actor = $ccr->createElement('Actor');
		$e_Source->appendChild($e_Actor);
		
		$e_ActorID = $ccr->createElement('ActorID',$uuid);
		$e_Actor->appendChild($e_ActorID);
		
		return $e_Source;
	}
*/
?>
