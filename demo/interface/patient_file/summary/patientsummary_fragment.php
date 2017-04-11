<?php
/*******************************************************************************\
 * Copyright (C) 2014 Joe Slam (joe@produnis.de)                                *
 *                                                                              *
 * This program is free software; you can redistribute it and/or                *
 * modify it under the terms of the GNU General Public License                  *
 * as published by the Free Software Foundation; either version 2               *
 * of the License, or (at your option) any later version.                       *
 *                                                                              *
 * This program is distributed in the hope that it will be useful,              *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of               *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                *
 * GNU General Public License for more details.                                 *
 *                                                                              *
 * You should have received a copy of the GNU General Public License            *
 * along with this program; if not,                                             *
 * see <http://opensource.org/licenses/gpl-license.php>                          *
 ********************************************************************************
 * @package OpenEMR
 * @author Joe Slam <joe@produnis.de>
 * @link http://www.open-emr.org
 * */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once("../../globals.php");
include_once("$srcdir/lists.inc");
include_once("$srcdir/acl.inc");
include_once("$srcdir/options.inc.php");
include_once("$srcdir/formdata.inc.php");

?>
<div id='patientsummary' style='margin-top: 3px; margin-left: 10px; margin-right: 10px'><!--outer div-->
<br>
<?php
//retrieve most recent set of labdata.
$e=$GLOBALS['encounter'];
$spell = "SELECT date,pid,admit_to_ward,admit_to_bed,encounter from t_form_admit where encounter='".$e."' and pid=? ";
$result=sqlQuery($spell, array($pid) );
    
if ( !$result ) //If there are no lab data recorded
{ ?>
  <span class='text'> <?php echo htmlspecialchars(xl("No bed related data documented"),ENT_NOQUOTES); 
?>
  </span> 
<?php 
} else
{
?> 
  <span class='text'><b>
  <?php echo htmlspecialchars(xl('Most recent Patients Bed Summary data:'),ENT_NOQUOTES);
  ?>
  </b>
  <br />
  <br />
  <br />
  
  <?php 
  
 $res=sqlStatement("select * from prescriptions where patient_id=? and form='1' and active='1'",array($pid));
	
   echo "<b>".xlt('Admitted Date') . ":</b> " . text($result['date'])."<br>";
	echo "<b>".xlt('Admitted To Ward').":</b>".text($result['admit_to_ward'])."<br>";
	echo "<b>".xlt('Admitted To Bed').":</b>".text($result['admit_to_bed'])."<br>";
    echo "<b>".xlt('Encounter') . ":</b><a href='../../patient_file/encounter/encounter_top.php?set_encounter=" . attr($result['encounter']) . "' target='RBot'>" . text($result['encounter']) . "</a>"."<br>";
    echo "<b>".xlt('Services') . ":</b> <br>"; 	
	while($row_currentMed=sqlFetchArray($res))
  {
   $runit=generate_display_field(array('data_type'=>'1','list_id'=>'drug_units'),$row_currentMed['unit']);
    $rin=generate_display_field(array('data_type'=>'1','list_id'=>'drug_form'),$row_currentMed['form']);
    $rroute=generate_display_field(array('data_type'=>'1','list_id'=>'drug_route'),$row_currentMed['route']);
    $rint=generate_display_field(array('data_type'=>'1','list_id'=>'drug_interval'),$row_currentMed['interval']);
   echo $row_currentMed['drug'];
   $unit=''; if($row_currentMed['size']>0) $unit=$row_currentMed['size']." ".$runit." "; echo htmlspecialchars($unit." ".$row_currentMed['dosage']." ".$rin." ".$rroute." ".$rint,ENT_NOQUOTES)."<br>";

 }
	
?>

 
  <br />
  </span><span class='text'>
  <br /> 
 <!-- <a href='../summary/patientsummary.php' onclick='top.restoreSession()'>
 <?php //echo htmlspecialchars(xl('Click here to view the Patient Summary in Detail'),ENT_NOQUOTES);?></a>-->
  </span><?php
} ?>
<br />
<br />
</div>
