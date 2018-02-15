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
 $sanitize_all_escapes=true;

 //STOP FAKE REGISTER GLOBALS
 $fake_register_globals=false;
 
include_once("../../globals.php");
include_once("$srcdir/api.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
formHeader("Form:OT");
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
$formid = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$obj = $formid ? formFetch("t_form_ot", $formid) : array();

?>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<?php html_header_show();?>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<!-- pop up calendar -->

<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/jquery.js"></script>
<script language="JavaScript">

function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","getot.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
</head>

<body class="body_top">
<h2><span class="forms-title"><?php echo xlt('Available OT Room Slots'); ?></span></h2>
</br>
<div class="">
<div class="col-md-6">
<select name="abc" onchange="showUser(this.value)" class="form-control">
	<option value="">Choose:</option>
   
	<option value="OT1">OT Room1</option>
	<option value="OT2">OT Room2</option>
	<option value="OT3">OT Room3</option>
	<option value="OT4">OT Room4</option>
	<option value="OT5">OT Room5</option>
</select>





<?php
echo "<form method='post' name='my_form' " .
  "action='$rootdir/patient_file/encounter/ot_form.php?id=" . attr($formid) ."'>\n";

echo "<div id=\"txtHint\"></div>";

?>
<b><input type="submit" class="btn btn-info" style="margin-top:15px" name="" value="Save" onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'"></input>
<?php
echo "</div>";
mysqli_close($con);
?>
</div>
</div>
</body>
</html>