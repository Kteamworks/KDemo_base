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
formHeader("Form:Bed Summary");
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
$formid = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$obj = $formid ? formFetch("t_form_bed_summary", $formid) : array();

?>
<html>
<head>
<?php html_header_show();?>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script language="JavaScript">
function checkAll(bx) {
    for (var tbls=document.getElementsByTagName("table"), i=tbls.length; i--; )
      for (var bxs=tbls[i].getElementsByTagName("input"), j=bxs.length; j--; )
         if (bxs[j].type=="checkbox")
            bxs[j].checked = bx.checked;
}


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
        xmlhttp.open("GET","getuser.php?q="+str,true);
        xmlhttp.send();
    }
}

function showDept(str) {
    if (str == "") {
        document.getElementById("txtDept").innerHTML = "";
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
                document.getElementById("txtDept").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","getdept.php?q="+str,true);
        xmlhttp.send();
    }
}


// callback from add_edit_issue.php:
function refreshIssue(issue, title) {
    top.restoreSession();
    location.reload();
}


function closeme() {
    if (parent.$) parent.$.fancybox.close();
    window.close();
 }

</script>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
</head>

<body class="body_top">
<p><span class="forms-title"><?php echo xlt('Beds Summary'); ?></span></p>
</br>
<?php
echo "<form method='post' name='my_form' " .
  "action='$rootdir/forms/Bed_summary/save.php?id=" . attr($formid) ."'>\n";
?>



<div id='patient_stats'>


 <div id="hideonprint">
<select name="users" onchange="showUser(this.value)">
  <option value="">Choose:</option>
  
  <!--<option value="2">Allocate</option>
  
  <option value="3">Discharge</option>-->
  
  <option value="1">Show Discharged</option>
  <option value="0">Show Occupied</option>
  </select>

<input type='button'  value="Print" onclick="printme()" class="button-css">&nbsp;
<!--<input type='submit' name="Update" value="Update"  class="button-css">&nbsp;
<input type='checkbox' onclick="checkAll(this)" /> <?php echo xlt('Select all')?>-->
</div>
<p>
<div id="txtHint"><b></b></div>

<p>

</form>

<script language="javascript">
/* required for popup calendar */
//Calendar.setup({inputField:"transfer_date", ifFormat:"%Y-%m-%d", button:"img_transfer_date"});
function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  divstyle.visibility = 'hidden';
  window.print();
  // divstyle.display = 'block';
 }
</script>
<?php
formFooter();
?>
