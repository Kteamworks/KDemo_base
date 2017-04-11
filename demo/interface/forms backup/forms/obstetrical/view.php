<!-- Forms generated from formsWiz -->
<?php
include_once("../../globals.php");
?>
<html><head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
</head>
<body class="body_top">
<?php
include_once("$srcdir/api.inc");
$obj = formFetch("form_obstetrical", $_GET["id"]);
?>
<form method=post action="<?php echo $rootdir?>/forms/obstetrical/save.php?mode=update&id=<?php echo $_GET["id"];?>" name="my_form">
<span class="title">Obstetrical Form</span><Br><br>


<table>

<tr><td><span class=text>Name: </span></td><td><input type=entry name="name" value="<?php echo $obj{"name"};?>" ></td>
<td><span class=text>LMP: </span></td><td><input type=entry name='lmp' size=10 value="<?php if ($obj{"lmp"} != "0000-00-00 00:00:00") {echo date("Y-m-d",strtotime($obj{"lmp"}));} else {echo "YYYY-MM-DD";}?>" ></td>
<td><span class=text>EDD: </span></td><td><input type=entry name='edd' size=10 value="<?php if ($obj{"edd"} != "0000-00-00 00:00:00") {echo date("Y-m-d",strtotime($obj{"edd"}));} else {echo "YYYY-MM-DD";}?>" ></td>
<td><span class=text>Birthdate: </span></td><td><input type=entry name='birthdate' size=10 value="<?php if ($obj{"birthdate"} != "0000-00-00 00:00:00") {echo date("Y-m-d",strtotime($obj{"birthdate"}));} else {echo "YYYY-MM-DD";}?>" ></td>
<td><span class=text>Married Life: </span></td><td><input type=entry name="marriedlife" value="<?php echo $obj{"marriedlife"};?>" ></td>
<td><span class=text>Birth Status: </span></td><td><input type=entry name="birth_status" value="<?php echo $obj{"birth_status"};?>" ></td>
<td><span class=text>Gender: </span></td><td><input type=entry name="gender" value="<?php echo $obj{"gender"};?>" ></td>
<td><input type=checkbox name='circumcised'  <?php if ($obj{"circumcised"} == "on") {echo "checked";};?> ></td><td><span class=text>Circumcised</span></td>
</tr>
</table>

<table>


<tr>
<td><span class=text>Pediatrician: </span></td><td><input type=entry name="pediatrician" value="<?php echo $obj{"pediatrician"};?>" ></td>
<td><span class=text>Birth Weight: </span></td><td><input type=entry name="birth_weight" value="<?php echo $obj{"birth_weight"};?>" ></td>
</tr><tr>
<td><span class=text>Length (Inches): </span></td><td><input type=entry name="length_inches" value="<?php echo $obj{"length_inches"};?>" ></td>
<td><span class=text>Head Circumference (Inches): </span></td><td><input type=entry name="head_circumference_inches" value="<?php echo $obj{"head_circumference_inches"};?>" ></td>
</tr><tr>
<td><span class=text>Feeding: </span></td><td><input type=entry name="feeding" value="<?php echo $obj{"feeding"};?>" ></td>
<td><span class=text>Delivery Method: </span></td><td><input type=entry name="delivery_method" value="<?php echo $obj{"delivery_method"};?>" ></td>
</tr><tr>
<td><span class=text>Labor Hours: </span></td><td><input type=entry name="labor_hours" value="<?php echo $obj{"labor_hours"};?>" ></td>
<td><span class=text>Pregnancy (Weeks): </span></td><td><input type=entry name="pregnancy_weeks" value="<?php echo $obj{"pregnancy_weeks"};?>" ></td>
</tr><tr>
<td><span class=text>Anesthesia: </span></td><td colspan=3><input type=entry name="anesthesia" value="<?php echo $obj{"anesthesia"};?>" ></td>
</tr>
</table>



<table>
<tr><td>
<span class=text>On Examination: </span><br><textarea cols=40 rows=4 wrap=virtual name="onexamination" ><?php echo $obj{"onexamination"};?></textarea></td>
<td><span class=text>Pre Abdomen: </span><br><textarea cols=40 rows=4 wrap=virtual name="preabdomen" ><?php echo $obj{"preabdomen"};?></textarea></td></tr>
<tr><td>
<span class=text>Urine Output: </span><br><textarea cols=40 rows=4 wrap=virtual name="urineoutput" ><?php echo $obj{"urineoutput"};?></textarea></td>
<td><span class=text>Treatment Given: </span><br><textarea cols=40 rows=4 wrap=virtual name="treatmentgiven" ><?php echo $obj{"treatmentgiven"};?></textarea></td></tr>
</table>



<table>
<tr>
<td><span class=text>Reactions to Medications and Immunizations: </span><br><textarea cols=40 rows=4 wrap=virtual name="reactions_to_medications_and_immunizations" ><?php echo $obj{"reactions_to_medications_and_immunizations"};?></textarea></td>
<td><span class=text>Birth Complications: </span><br><textarea cols=40 rows=4 wrap=virtual name="birth_complications" ><?php echo $obj{"birth_complications"};?></textarea></td>
</tr><tr>
<td><span class=text>Developmental Problems: </span><br><textarea cols=40 rows=4 wrap=virtual name="developmental_problems" ><?php echo $obj{"developmental_problems"};?></textarea></td>
<td><span class=text>Chronic Illness: </span><br><textarea cols=40 rows=4 wrap=virtual name="chronic_illness" ><?php echo $obj{"chronic_illness"};?></textarea></td>
</tr><tr>
<td><span class=text>Chronic Medication: </span><br><textarea cols=40 rows=4 wrap=virtual name="chronic_medication" ><?php echo $obj{"chronic_medication"};?></textarea></td>
<td><span class=text>Course in Hospital: </span><br><textarea cols=40 rows=4 wrap=virtual name="hospitalization" ><?php echo $obj{"hospitalization"};?></textarea></td>
</tr><tr>
<td><span class=text>Surgery: </span><br><textarea cols=40 rows=4 wrap=virtual name="surgery" ><?php echo $obj{"surgery"};?></textarea></td>
<td><span class=text>Injury: </span><br><textarea cols=40 rows=4 wrap=virtual name="injury" ><?php echo $obj{"injury"};?></textarea></td>
</tr><tr>
<td><span class=text>Findings: </span><br><textarea cols=40 rows=4 wrap=virtual name="findings" ><?php echo $obj{"findings"};?></textarea></td>
<td><span class=text>OT Notes: </span><br><textarea cols=40 rows=4 wrap=virtual name="additional_notes" ><?php echo $obj{"additional_notes"};?></textarea></td>
</tr>
</table>
<br>
<a href="javascript:top.restoreSession();document.my_form.submit();" class="link_submit">[Save]</a>
<br>
<a href="<?php echo $GLOBALS['form_exit_url']; ?>" class="link"
 onclick="top.restoreSession()">[Don't Save Changes]</a>
</form>
<?php
formFooter();
?>
