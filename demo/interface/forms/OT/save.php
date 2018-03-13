<?php
/**
 *
 * Copyright (C) 2012-2013 Naina Mohamed <naina@capminds.com> CapMinds Technologies
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
 * @author  Naina Mohamed <naina@capminds.com>
 * @link    http://www.open-emr.org
 */
 
  //SANITIZE ALL ESCAPES
 $sanitize_all_escapes=$_POST['true'];

 //STOP FAKE REGISTER GLOBALS
 $fake_register_globals=$_POST['false'];
  
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");

if (! $encounter) { // comes from globals.php
 die(xl("Internal error: we do not seem to be in an encounter!"));
}


$id = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$pid=$_POST['pid'];
$encounter=$_SESSION['encounter'];
$sets = "pid = '" . add_escape_custom($_SESSION["pid"]) . "',
  user = '" . $_SESSION["authUser"] . "',
  activity=1, date = NOW(),
  provider          = '" . add_escape_custom($_POST["provider"]) . "',
  admit_to_ot_room         = '" . add_escape_custom($_POST["admit_to_ot_room"]) . "',
  appointment_date        = '" . add_escape_custom($_POST['appointment_date']) . "',
  e_o_t          = '" . add_escape_custom($_POST['e_o_t']) . "',
    status       = '" . add_escape_custom('1') . "',
   encounter          = '" . add_escape_custom($_SESSION["encounter"]) . "',
     ot_instructions         = '" . add_escape_custom($_POST["ot_instructions"]) . "',
diagnosis                   = '" . add_escape_custom($_POST["diagnosis"]) ."'";


  if (empty($id)) {
		 
  $adm_to=add_escape_custom($_POST["admit_to_ot_room"]);
  $adm_time=add_escape_custom($_POST['appointment_date']);
  $newid = sqlInsert("INSERT INTO t_form_ot SET $sets");
  
  sqlStatement("UPDATE list_options SET is_default=1 WHERE list_id='".$adm_to. "'and option_id= '".$adm_time. "'");
  $pid=$_SESSION['pid'];
  $encounter=$_SESSION['encounter'];
 //addForm($encounter, "Admission", $newid, "admit", $pid, $userauthorized);

  }
else {
  sqlStatement("UPDATE form_transfer_summary SET $sets WHERE id = '". add_escape_custom("$id"). "'");
}

$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump("../../main/finder/p_dynamic_finder_ot.php");
formFooter();
?>

