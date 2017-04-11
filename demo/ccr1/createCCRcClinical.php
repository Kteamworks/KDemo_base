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


$result = getClinicalData();
$row = sqlFetchArray($result);

do {

//while ($row = sqlFetchArray($result)) {

	$e_Clinical = $ccr->createElement('Clinical');
	$e_Clinicals->appendChild($e_Clinical);

	$e_CCRDataObjectID = $ccr->createElement('CCRDataObjectID', getUuid());
	$e_Clinical->appendChild($e_CCRDataObjectID);


	
	$e_IDs = $ccr->createElement('IDs');
	$e_Clinical->appendChild($e_IDs);

	$e_ID = $ccr->createElement('ID', $row['pid']);
	$e_IDs->appendChild($e_ID);

	$e_IDs->appendChild(sourceType($ccr, $sourceID));
	
	

	$e_AOD = $ccr->createElement('AOD');
	$e_Clinical->appendChild($e_AOD);

	$e_Text = $ccr->createElement('Text', $row['advice_on_discharge']);
	$e_AOD->appendChild($e_Text);
	
	$e_COT = $ccr->createElement('COT');
	$e_Clinical->appendChild($e_COT);

	$e_Text = $ccr->createElement('Text', $row['course_of_treatment']);
	$e_COT->appendChild($e_Text);
	
	$e_COD= $ccr->createElement('COD');
	$e_Clinical->appendChild($e_COD);

	$e_Text = $ccr->createElement('Text', $row['condition_on_discharge']);
	$e_COD->appendChild($e_Text);
	

	/*$e_COD = $ccr->createElement('COD');
	$e_Clinical->appendChild($e_COD);

	$e_COD = $ccr->createElement('Value', $row['condition_on_discharge']);
	$e_Clinical->appendChild($e_COD);*/
	
	$e_FP= $ccr->createElement('FP');
	$e_Clinical->appendChild($e_FP);

	$e_Text = $ccr->createElement('Text', $row['future_plan']);
	$e_FP->appendChild($e_Text);
	
	$e_FLP= $ccr->createElement('FLP');
	$e_Clinical->appendChild($e_FLP);

	$e_Text = $ccr->createElement('Text', $row['recommendation_for_follow_up']);
	$e_FLP->appendChild($e_Text);
	
	
	

	} while ($row = sqlFetchArray($result));
	//}

?>
