<?php

 // Copyright (C) 2006-2011 Rod Roark <rod@sunsetsystems.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

$sanitize_all_escapes  = true;
$fake_register_globals = false;

 require_once("../globals.php");
 require_once("$srcdir/acl.inc");
 require_once("drugs.inc.php");
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/formdata.inc.php");
 require_once("$srcdir/htmlspecialchars.inc.php");

 $alertmsg = '';
 $drug_id = $_REQUEST['drug'];
 $info_msg = "";
 $tmpl_line_no = 0;

 if (!acl_check('admin', 'drugs')) die(xlt('Not authorized'));

// Format dollars for display.
//
function bucks($amount) {
  if ($amount) {
    $amount = sprintf("%.2f", $amount);
    if ($amount != 0.00) return $amount;
  }
  return '';
}

// Write a line of data for one template to the form.
//
function writeTemplateLine($selector, $dosage, $period, $quantity, $refills, $prices, $taxrates) {
  global $tmpl_line_no;
  ++$tmpl_line_no;

  echo " <tr>\n";
  echo "  <td class='tmplcell drugsonly'>";
  echo "<input type='text' name='form_tmpl[$tmpl_line_no][selector]' value='" . attr($selector) . "' size='8' maxlength='100'>";
  echo "</td>\n";
  echo "  <td class='tmplcell drugsonly'>";
  echo "<input type='text' name='form_tmpl[$tmpl_line_no][dosage]' value='" . attr($dosage) . "' size='6' maxlength='10'>";
  echo "</td>\n";
  echo "  <td class='tmplcell drugsonly'>";
  generate_form_field(array(
    'data_type'   => 1,
    'field_id'    => 'tmpl[' . $tmpl_line_no . '][period]',
    'list_id'     => 'drug_interval',
    'empty_title' => 'SKIP'
    ), $period);
  echo "</td>\n";
  echo "  <td class='tmplcell drugsonly'>";
  echo "<input type='text' name='form_tmpl[$tmpl_line_no][quantity]' value='" . attr($quantity) . "' size='3' maxlength='7'>";
  echo "</td>\n";
  echo "  <td class='tmplcell drugsonly'>";
  echo "<input type='text' name='form_tmpl[$tmpl_line_no][refills]' value='" . attr($refills) . "' size='3' maxlength='5'>";
  echo "</td>\n";
  foreach ($prices as $pricelevel => $price) {
    echo "  <td class='tmplcell'>";
    echo "<input type='text' name='form_tmpl[$tmpl_line_no][price][" . attr($pricelevel) . "]' value='" . attr($price) . "' size='6' maxlength='12'>";
    echo "</td>\n";
  }
  $pres = sqlStatement("SELECT option_id FROM list_options " .
    "WHERE list_id = 'taxrate' ORDER BY seq");
  while ($prow = sqlFetchArray($pres)) {
    echo "  <td class='tmplcell'>";
    echo "<input type='checkbox' name='form_tmpl[$tmpl_line_no][taxrate][" . attr($prow['option_id']) . "]' value='1'";
    if (strpos(":$taxrates", $prow['option_id']) !== false) echo " checked";
    echo " /></td>\n";
  }
  echo " </tr>\n";
}

// Translation for form fields used in SQL queries.
//
function escapedff($name) {
  return add_escape_custom(trim($_POST[$name]));
}
function numericff($name) {
  $field = trim($_POST[$name]) + 0;
  return add_escape_custom($field);
}
?>
<html>
<head>
        
        <link rel="stylesheet" href="public/css/default.css" type="text/css">
        
		
		 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		 <link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/stylesheet.css">
		<script src="js/jquery.js"></script>
		<script src="../dist/js/standalone/selectize.js"></script>
		<script src="js/index.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		
		
		

<?php html_header_show(); ?>
<title><?php echo $drug_id ? xlt("Edit") : xlt("Add New"); echo ' ' . xlt('Drug'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>

 



input[class=rgt] { text-align:right }

td { font-size:10pt; }


input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
     text-align:right;
}


<?php if ($GLOBALS['sell_non_drug_products'] == 2) { ?>
.drugsonly { display:none; }
<?php } else { ?>
.drugsonly { }
<?php } ?>

<?php if (empty($GLOBALS['ippf_specific'])) { ?>
.ippfonly { display:none; }
<?php } else { ?>
.ippfonly { }
<?php } ?>




</style>

<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>

 



<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
 var s = f.form_related_code.value;
 if (code) {
  if (s.length > 0) s += ';';
  s += codetype + ':' + code;
 } else {
  s = '';
 }
 f.form_related_code.value = s;
}

// This invokes the find-code popup.
function sel_related() {
 dlgopen('../patient_file/encounter/find_code_popup.php', '_blank', 500, 400);
}



</script>





</head>

<script language="JavaScript">


$(document).ready(function()
{
	<?php $a=1;
	while($a<=10){ ?>
	$('#<?php echo "toggle_doc".$a ?>').click(function() { 
	
		
$('#<?php echo "input_dr".$a ?> > input').attr("disabled",false);
	$(this).find('i').toggleClass('fa-plus-circle fa-minus-circle');
	$('#<?php echo "select_dr".$a ?>, #<?php echo "input_dr".$a ?>').toggle();

   });
	
	<?php $a++; } ?>
	
$("#name1").change(function()
{
	
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype1").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h1").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=manu",
cache: false,
success: function(html)
{
$("#manu1").html(html);
} 
});
/*$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v1").html(html);
} 
});
*/

});

$("#name2").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype2").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h2").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu2").html(html);
} 
});
/*
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v2").html(html);
} 
});
*/
});
$("#name3").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype3").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h3").html(html);
} 
});
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu3").html(html);
} 
});
/*
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v3").html(html);
} 
});
*/
});
$("#name4").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype4").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h4").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu4").html(html);
} 
});
/*
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v4").html(html);
} 
});
*/
});

$("#name5").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype5").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h5").html(html);
} 
});


$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu5").html(html);
} 
});
/*
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v5").html(html);
} 
});
*/
});
$("#name6").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype6").html(html);
} 
});
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h6").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu6").html(html);
} 
});
/*$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v6").html(html);
} 
});
*/
});



$("#name7").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype7").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h7").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu7").html(html);
} 
});
/* $.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v7").html(html);
} 
});
*/
});

$("#name8").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype8").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h8").html(html);
} 
});


$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu8").html(html);
} 
});
/*
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v8").html(html);
} 
});
*/
});
$("#name9").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype9").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h9").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu9").html(html);
} 
});
/* $.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v9").html(html);
} 
});
*/
});


$("#name10").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#mtype10").html(html);
} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule",
cache: false,
success: function(html)
{
$("#sch_h10").html(html);
} 
});


$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#manu10").html(html);
} 
});
/* $.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=vat",
cache: false,
success: function(html)
{
$("#v10").html(html);
} 
});
*/
});


});





//--------------disable enter button---------------//
$(document).on('keypress', 'input', function(e) {

  if(e.keyCode == 13 && e.target.type !== 'submit') {
    e.preventDefault();
    return $(e.target).blur().focus();
  }

});

//------------------total sum-------------------------//

$(document).on("focus", ".total", function() {
    var sum = 0;
    $(".sum").each(function(){
        sum += +$(this).val();
    });
    $(".total").val(sum);
});

//--------------------new medicine disable/enable------------------//


$(document).ready(function(){
		
$("#nm1").attr('disabled','disabled');
$(document).on('change', '#name1', function() {
var name = $("#name1").val();
var nm = $("#nm1").val();
	if(name=='add')
	{
    $("#nm1").removeAttr('disabled');
	 
	}
	if(name!='add')
	{
		$("#nm1").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q1").prop('required',true);
		 $("#b1").prop('required',true);
		 $("#p1").prop('required',true);
		 $("#mrp1").prop('required',true);
		 
		 $("#tp1").prop('required',true);
		 $("#d1").prop('required',true);
		 
	}
	
});
});


$(document).ready(function(){
$("#nm2").attr('disabled','disabled');
$(document).on('change', '#name2', function() {
var name = $("#name2").val();
var nm = $("#nm2").val();
	if(name=='add')
	{
    $("#nm2").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm2").attr('disabled','disabled');
	}
	
	if((nm!='')||(name!='')){
		 $("#q2").prop('required',true);
		 $("#b2").prop('required',true);
		 $("#p2").prop('required',true);
		 $("#mrp2").prop('required',true);
		
		 $("#tp2").prop('required',true);
		 $("#d2").prop('required',true);
		 
	}
});
});


$(document).ready(function(){
$("#nm3").attr('disabled','disabled');

$(document).on('change', '#name3', function() {
var name = $("#name3").val();
var nm = $("#nm3").val();
	if(name=='add')
	{
    $("#nm3").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm3").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q3").prop('required',true);
		 $("#b3").prop('required',true);
		 $("#p3").prop('required',true);
		 $("#mrp3").prop('required',true);
		 
		 $("#tp3").prop('required',true);
		 $("#d3").prop('required',true);
		 
	}
	
	
});
});


$(document).ready(function(){
$("#nm4").attr('disabled','disabled');
$(document).on('change', '#name4', function() {
var name = $("#name4").val();
var nm = $("#nm4").val();
	if(name=='add')
	{
    $("#nm4").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm4").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q4").prop('required',true);
		 $("#b4").prop('required',true);
		 $("#p4").prop('required',true);
		 $("#mrp4").prop('required',true);
		 
		 $("#tp4").prop('required',true);
		 $("#d4").prop('required',true);
		 
	}
	
});
});

$(document).ready(function(){
$("#nm5").attr('disabled','disabled');
$(document).on('change', '#name5', function() {
var name = $("#name5").val();
var nm = $("#nm5").val();
	if(name=='add')
	{
    $("#nm5").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm5").attr('disabled','disabled');
	}
	
	if((nm!='')||(name!='')){
		 $("#q5").prop('required',true);
		 $("#b5").prop('required',true);
		 $("#p5").prop('required',true);
		 $("#mrp5").prop('required',true);
		
		 $("#tp5").prop('required',true);
		 $("#d5").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm6").attr('disabled','disabled');
$(document).on('change', '#name6', function() {
var name = $("#name6").val();
var nm = $("#nm6").val();
	if(name=='add')
	{
    $("#nm6").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm6").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q6").prop('required',true);
		 $("#b6").prop('required',true);
		 $("#p6").prop('required',true);
		 $("#mrp6").prop('required',true);
		
		 $("#tp6").prop('required',true);
		 $("#d6").prop('required',true);
		 
	}
	
});
});

$(document).ready(function(){
$("#nm7").attr('disabled','disabled');
$(document).on('change', '#name7', function() {
var name = $("#name7").val();
var nm = $("#nm7").val();
	if(name=='add')
	{
    $("#nm7").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm7").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q7").prop('required',true);
		 $("#b7").prop('required',true);
		 $("#p7").prop('required',true);
		 $("#mrp7").prop('required',true);
		 
		 $("#tp7").prop('required',true);
		 $("#d7").prop('required',true);
		 
	}
	
});
});

$(document).ready(function(){
$("#nm8").attr('disabled','disabled');
$(document).on('change', '#name8', function() {
var name = $("#name8").val();
var nm = $("#nm8").val();
	if(name=='add')
	{
    $("#nm8").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm8").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q8").prop('required',true);
		 $("#b8").prop('required',true);
		 $("#p8").prop('required',true);
		 $("#mrp8").prop('required',true);
		
		 $("#tp8").prop('required',true);
		 $("#d8").prop('required',true);
		 
	}
	
});
});

$(document).ready(function(){
$("#nm9").attr('disabled','disabled');
$(document).on('change', '#name9', function() {
var name = $("#name9").val();
var nm = $("#nm9").val();
	if(name=='add')
	{
    $("#nm9").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm9").attr('disabled','disabled');
	}
	
	if((nm!='')||(name!='')){
		 $("#q9").prop('required',true);
		 $("#b9").prop('required',true);
		 $("#p9").prop('required',true);
		 $("#mrp9").prop('required',true);
		
		 $("#tp9").prop('required',true);
		 $("#d9").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm10").attr('disabled','disabled');
$(document).on('change', '#name10', function() {
var name = $("#name10").val();
var nm = $("#nm10").val();
	if(name=='add')
	{
    $("#nm10").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm10").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q10").prop('required',true);
		 $("#b10").prop('required',true);
		 $("#p10").prop('required',true);
		 $("#mrp10").prop('required',true);
		 
		 $("#tp10").prop('required',true);
		 $("#d10").prop('required',true);
		 
	}
	
});
});

$(document).ready(function(){
$("#nm11").attr('disabled','disabled');
$(document).on('change', '#name11', function() {
var name = $("#name11").val();
var nm = $("#nm11").val();
	if(name=='add')
	{
    $("#nm11").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm11").attr('disabled','disabled');
	}
	
	if((nm!='')||(name!='')){
		 $("#q11").prop('required',true);
		 $("#b11").prop('required',true);
		 $("#p11").prop('required',true);
		 $("#mrp11").prop('required',true);
		 
		 $("#tp11").prop('required',true);
		 $("#d11").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm12").attr('disabled','disabled');
$(document).on('change', '#name12', function() {
var name = $("#name12").val();
var nm = $("#nm12").val();
	if(name=='add')
	{
    $("#nm12").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm12").attr('disabled','disabled');
	}
	
	if((nm!='')||(name!='')){
		 $("#q12").prop('required',true);
		 $("#b12").prop('required',true);
		 $("#p12").prop('required',true);
		 $("#mrp12").prop('required',true);
		
		 $("#tp12").prop('required',true);
		 $("#d12").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm13").attr('disabled','disabled');
$(document).on('change', '#name13', function() {
var name = $("#name13").val();
var nm = $("#nm13").val();
	if(name=='add')
	{
    $("#nm13").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm13").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q13").prop('required',true);
		 $("#b13").prop('required',true);
		 $("#p13").prop('required',true);
		 $("#mrp13").prop('required',true);
		
		 $("#tp13").prop('required',true);
		 $("#d13").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm14").attr('disabled','disabled');
$(document).on('change', '#name14', function() {
var name = $("#name14").val();
var nm = $("#nm14").val();
	if(name=='add')
	{
    $("#nm14").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm14").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q14").prop('required',true);
		 $("#b14").prop('required',true);
		 $("#p14").prop('required',true);
		 $("#mrp14").prop('required',true);
		
		 $("#tp14").prop('required',true);
		 $("#d14").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm15").attr('disabled','disabled');
$(document).on('change', '#name15', function() {
var name = $("#name15").val();
var nm = $("#nm15").val();
	if(name=='add')
	{
    $("#nm15").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm15").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q15").prop('required',true);
		 $("#b15").prop('required',true);
		 $("#p15").prop('required',true);
		 $("#mrp15").prop('required',true);
		
		 $("#tp15").prop('required',true);
		 $("#d15").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm16").attr('disabled','disabled');
$(document).on('change', '#name16', function() {
var name = $("#name16").val();
	if(name=='add')
	{
    $("#nm16").removeAttr('disabled');
	var nm = $("#nm16").val();
	}
	if(name!='add')
	{
		$("#nm16").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q16").prop('required',true);
		 $("#b16").prop('required',true);
		 $("#p16").prop('required',true);
		 $("#mrp16").prop('required',true);
		
		 $("#tp16").prop('required',true);
		 $("#d16").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm17").attr('disabled','disabled');
$(document).on('change', '#name17', function() {
var name = $("#name17").val();
var nm = $("#nm17").val();
	if(name=='add')
	{
    $("#nm17").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm17").attr('disabled','disabled');
	}
	
	if((nm!='')||(name!='')){
		 $("#q17").prop('required',true);
		 $("#b17").prop('required',true);
		 $("#p17").prop('required',true);
		 $("#mrp17").prop('required',true);
		 
		 $("#tp17").prop('required',true);
		 $("#d17").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm18").attr('disabled','disabled');
$(document).on('change', '#name18', function() {
var name = $("#name18").val();
var nm = $("#nm18").val();
	if(name=='add')
	{
    $("#nm18").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm18").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q18").prop('required',true);
		 $("#b18").prop('required',true);
		 $("#p18").prop('required',true);
		 $("#mrp18").prop('required',true);
		
		 $("#tp18").prop('required',true);
		 $("#d18").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm19").attr('disabled','disabled');
$(document).on('change', '#name19', function() {
var name = $("#name19").val();
var nm = $("#nm19").val();
	if(name=='add')
	{
    $("#nm19").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm19").attr('disabled','disabled');
	}
	
	if((nm!='')||(name!='')){
		 $("#q19").prop('required',true);
		 $("#b19").prop('required',true);
		 $("#p19").prop('required',true);
		 $("#mrp19").prop('required',true);
		
		 $("#tp19").prop('required',true);
		 $("#d19").prop('required',true);
		 
	}
});
});

$(document).ready(function(){
$("#nm20").attr('disabled','disabled');
$(document).on('change', '#name20', function() {
var name = $("#name20").val();
var nm = $("#nm20").val();
	if(name=='add')
	{
    $("#nm20").removeAttr('disabled');
	}
	if(name!='add')
	{
		$("#nm20").attr('disabled','disabled');
	}
	if((nm!='')||(name!='')){
		 $("#q20").prop('required',true);
		 $("#b20").prop('required',true);
		 $("#p20").prop('required',true);
		 $("#mrp20").prop('required',true);
		
		 $("#tp20").prop('required',true);
		 $("#d20").prop('required',true);
		 
	}
	
});
});



//--------------------Calculation-----------------------------------//
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d1").val();
	var t = $("#tp1").val();
	var q = $("#q1").val();
	var v = $("#v1").val();
	
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t1").val(result);
	
});

$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d2").val();
	var t = $("#tp2").val();
	var q = $("#q2").val();
	var v = $("#v2").val();
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t2").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d3").val();
	var t = $("#tp3").val();
	var q = $("#q3").val();
	var v = $("#v3").val();
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t3").val(result);
});

$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d4").val();
	var t = $("#tp4").val();
	var q = $("#q4").val();
	var v = $("#v4").val();
    var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t4").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d5").val();
	var t = $("#tp5").val();
	var q = $("#q5").val();
	var v = $("#v5").val();
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t5").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d6").val();
	var t = $("#tp6").val();
	var q = $("#q6").val();
	var v = $("#v6").val();
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t6").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d7").val();
	var t = $("#tp7").val();
	var q = $("#q7").val();
	var v = $("#v7").val();
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t7").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d8").val();
	var t = $("#tp8").val();
	var q = $("#q8").val();
	var v = $("#v8").val();
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t8").val(result);
});

$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d9").val();
	var t = $("#tp9").val();
	var q = $("#q9").val();
	var v = $("#v9").val();
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);	$("#t9").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d10").val();
	var t = $("#tp10").val();
	var q = $("#q10").val();
	var v = $("#v10").val();
	var value1 = [(100 + (+v))] * [(+t)/100] * (+q);
	var value2 = [(+d)/100] * (+value1);
	
	var result =  (+value1) - (+value2);
	$("#t10").val(result);
});



</script>



<body class="body_top">
<?php
// If we are saving, then save and close the window.
// First check for duplicates.




if (($_POST['form_save'] || $_POST['form_delete']) && !$alertmsg) {
	
  $new_drug = false;
  if ($drug_id) {
   if ($_POST['form_save']) { // updating an existing drug
    sqlStatement("UPDATE drugs SET " .
     "name = '"           . escapedff('name')          . "', " .
     "mfr = '"     . escapedff('mfr')    . "', " .
     "quantity = '"       . escapedff('qty')      . "', " .
     "batch = '"  . escapedff('batch') . "', " .
     "pack = '"      . escapedff('pack')     . "', " .
     "expdate = '"           . escapedff('date')          . "', " .
     "mrp = '"           . escapedff('mrp')          . "', " .
     "tradePrice = '"           . escapedff('trade')          . "', " .
     "discount = '"          . escapedff('discount')         . "', " .
     "vat = '"     . numericff('vat')    . "', " .
     "totalValue = '"   . escapedff('total')  . "', " .
	  "free = '"   . escapedff('free')  . "', " .
	   "instock = '"   . escapedff('instock')  . "', " .
     "allow_multiple = "  . (empty($_POST['form_allow_multiple' ]) ? 0 : 1) . ", " .
     "allow_combining = " . (empty($_POST['form_allow_combining']) ? 0 : 1) . ", " .
     "active = "          . (empty($_POST['form_active']) ? 0 : 1) . " " .
     "WHERE drug_id = ?", array($drug_id));
    sqlStatement("DELETE FROM drug_templates WHERE drug_id = ?", array($drug_id));
   }
   else { // deleting
    if (acl_check('admin', 'super')) {
     sqlStatement("DELETE FROM drug_inventory WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM drug_templates WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM drugs WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM prices WHERE pr_id = ? AND pr_selector != ''", array($drug_id));
	 sqlStatement("DELETE FROM product_warehouse WHERE pw_drug_id = ?", array($drug_id));
	 sqlStatement("DELETE FROM drug_sales WHERE drug_id = ?", array($drug_id));
    } 
   }
  }
  else if ($_POST['form_save']) { // saving a new drug
   $new_drug = true;
             
   
         if(isset($_POST['supplier'])&& $_POST['supplier']!=""){
			 
			  $sup=$_POST['supplier']; 
			  $invoice= $_POST['invoice'];
			  
			  $j=0;
	foreach($_POST['form_name'] as $selected){
		
		
		
		
		
		$batch= $_POST['batch'][$j];
		$medType= $_POST['medType'][$j];
		//$date= $_POST['date'][$j];
		$month= $_POST['month'][$j];
		$year= $_POST['year'][$j];
		$date= $year.'-'.$month.'-'.'01';
		$qty= $_POST['qty'][$j];
		$pack= $_POST['pack'][$j];
		$mrp= $_POST['mrp'][$j];
		$group= $_POST['group'][$j];
		$schedule_h= $_POST['schedule_h'][$j];
		
		$mfr= $_POST['mfr'][$j];
		$instock= $_POST['instock'][$j];
		$trade= $_POST['trade'][$j];
		$discount= $_POST['discount'][$j];
		$vat= $_POST['vat'][$j];
	$mrpa = ($mrp * 100)/(100+$vat);
	
		$total= $_POST['total'][$j];
		$type= $_POST['type'][$j];
		$unitPrice= $mrp/$pack;
		$free= $_POST['free'][$j];
		 
	    $totalStock =  ($instock*$pack) + ($qty*$pack) + ($free*$pack) ;
	  
		
        if(empty($selected))
			continue;
	
	//------------------Duplicate---------------------------------//	
	/*
		$crow = sqlStatement("SELECT COUNT(*) AS count,name FROM drugs WHERE " .
    "name = '"  .  $selected   . "' " );
   
      while ($croww = sqlFetchArray($crow))
	  {
		  
		  $med=$croww['name'];
		  
	 $msg = 'Cannot add '.$med.'  because it already exists!';
      
   
    if ($croww['name']) {
		
		
	echo "<script type='text/javascript'>alert('$msg');";
    //$alertmsg = addslashes(xl('Cannot add this entry because it already exists!'));
	echo "window.location.href = 'add_edit_drug.php'";
	echo "</script>";
	}
	  }
*/	  
//-------------------------------------------------------------------------------//		
		
		 include_once('dbconnect.php');
		
		 $sel = mysqli_real_escape_string($con,$selected);
		$master = sqlInsert("Insert into medicine_master (Medicine_Name,Medicine_Type,Medicine_Manufacturer,Medicine_Tax)
		VALUES ('$sel','$medType','$mfr','$vat')
		ON DUPLICATE KEY UPDATE Medicine_Name='$sel'");
 
	
			 $drug_id = sqlInsert("INSERT INTO drugs ( " .
    "name,mfr,inStock,supplier,batch,medType,schedule_h,quantity,medGroup,totalStock,free,date,pack,packType,expdate,mrp,mrpa,PricePerUnit,tradePrice,discount,vat,totalValue,invoice,max_level, form, " .
    "size, unit, route, cyp_factor, related_code, " .
    "allow_multiple, allow_combining,active " .
    ") VALUES ( " .
    "'" .  mysqli_real_escape_string($con,$selected)        . "', " .
    "'" . $mfr          . "', " .
    "'" . $instock          . "', " .
	 "'" .$sup. "', " .
    "'" . $batch    . "', " .
	 "'" . $medType    . "', " .
	  "'" . $schedule_h    . "', " .
    "'" . $qty      . "', " .
	 "'" . $group      . "', " .
	 "'" . $totalStock      . "', " .
	 "'" . $free      . "', " .
	 "'" . date('Y/m/d') . "', " .
	"'" . $pack      . "', " .
	"'" . $type     . "', " .
    "'" . $date . "', " .
	 "'" . $mrp . "', " .
	  "'" . $mrpa . "', " .
	  "'" . $unitPrice . "', " .
	  "'" . $trade . "', " .
	   "'" . $discount . "', " .
	    "'" . $vat . "', " .
		 "'" . $total . "', " .
		  "'" . $invoice . "', " .
    "'" . escapedff('form_max_level')     . "', " .
    "'" . escapedff('form_form')          . "', " .
    "'" . escapedff('form_size')          . "', " .
    "'" . escapedff('form_unit')          . "', " .
    "'" . escapedff('form_route')         . "', " .
    "'" . numericff('form_cyp_factor')    . "', " .
    "'" . escapedff('form_related_code')  . "', " .
    (empty($_POST['form_allow_multiple' ]) ? 0 : 1) . ", " .
    (empty($_POST['form_allow_combining']) ? 0 : 1) . ", " .
    (empty($_POST['form_active']) ? 0 : 1)        .
    ")");
	
	
	$codetyp=11;
	$cf=0;
	$active=1;
	$units=1;
	$codesmed=sqlInsert("INSERT INTO codes(".
	"code_text,code,code_type,units,fee,cyp_factor,active".
	") VALUES ( " .
	"'" . mysqli_real_escape_string($con,$selected)          . "', " .
	"'" . mysqli_real_escape_string($con,$selected)          . "', " .
	"'" . $codetyp          . "', " .
	"'" . $units          . "', " .
	"'" . $unitPrice      . "', " .
	"'" . $cf      . "', " .
	"'" . $active      . "'" .
	")");
	
	
	
	$medprice=sqlInsert("INSERT INTO prices (".
	"pr_id,pr_selector, pr_level, pr_price".
	") VALUES ( " .
	"'" .    $codesmed           . "', " .
	"'" .    ''           . "', " .
	"'" . 'standard'      . "', " .
	"'" . $unitPrice      . "'" .

	")");
	
	
//-------------------------------------------------------------------------------------------//

/*-----logic for unique primary key stats--------*/
/*	
	 
	 $test = sqlStatement("SELECT  * FROM `list_options` WHERE `list_id`='drug_supplier' order by CONVERT(SUBSTRING(option_id, 1), SIGNED INTEGER) desc limit 1");
 while($test1 = sqlFetchArray($test)){

 $test2=$test1['option_id'];
 }
  $test3= $test2+1;
  
	/*-------logic Ends-------------------------------
	$valid = sqlQuery("SELECT title FROM list_options WHERE " .
"title = '"  . $sup  . "' " );

   if(!$valid)	
   {	

$supply_id = sqlInsert("INSERT INTO list_options ( " .
    "list_id,title,option_id " .
    ") VALUES ( " .
    "'" . 'drug_supplier'          . "', " .
	 "'"  . $sup ."', " .
	  "'" . $test3          . "' " .
    ")");	
	
   }
	
	
	*/
	
	
	
	
	
//---------------------------Drugs_Inventory--------------------------------------------//

 $lot_id = sqlInsert("INSERT INTO drug_inventory ( " .
          "drug_id, lot_number, manufacturer, expiration, " .
          "vendor_id, warehouse_id, on_hand " .
          ") VALUES ( " .
          "'" . add_escape_custom($drug_id) . "', "                            .
          "'" . $batch   . "', " .
          "'" . $mfr . "', " .
         "'" . $date . "', " .
          "'" . add_escape_custom($_POST['form_vendor_id'])    . "', " .
          "'onsite', " .
          "'" . $totalStock              . "' "  .
          ")");	
	//------------------------------------------product_warehouse-------------------------------//
	
	$warehouse = 	sqlInsert("INSERT INTO product_warehouse ( " .
        "pw_drug_id, pw_warehouse, pw_min_level, pw_max_level ) VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', "                            .
          "'onsite ', " .
          "' 1', " .
         "' 10000' " .
         ")");	
		 
		 
		// $quantity = $qty + $free;
	//---------------------------------------------drug_sales---------------------------------//	 
		 
		       sqlInsert("INSERT INTO drug_sales ( " .
        "drug_id, inventory_id, prescription_id, pid, encounter, user, " .
        "sale_date, quantity, fee, xfer_inventory_id, distributor_id,rate,free,pack,vat,notes " .
        ") VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', " .
        "'" . add_escape_custom($lot_id) . "', '0', '0', '0', " .
        "'" . add_escape_custom($_SESSION['authUser']) . "', " .
        "'" . date('Y/m/d') . "', " .
        "'" . add_escape_custom(0 - $totalStock)  . "', " .
        "'" . add_escape_custom(0 - $total)      . "', " .
        "'" . add_escape_custom($form_source_lot) . "', " .
        "'" . add_escape_custom($form_distributor_id) . "', " .
		 "'" . $total . "', " .
		 "'" . $free . "', " .
		 "'" . $pack . "', " .
		 "'" . $vat . "', " .
        "'" . add_escape_custom($form_notes) . "' )");
		 
	//------------------------------------drug_templates------------------------------------//	
  
         //$quantity = $qty + $free;
		 
		 sqlInsert("INSERT INTO drug_templates ( " .
      "drug_id, selector,quantity,taxrates " .
      ") VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', " .
        "'" . mysqli_real_escape_string($con,$selected) . "', " .
		 "'" . $totalStock . "', " .
        "'" . add_escape_custom($form_notes) . "' )");
		 
	
	
	$j++;
		}  
		
  }
		
   

	
  }

  if ($_POST['form_save'] && $drug_id) {
	  
    $tmpl = $_POST['form_tmpl']; 
   // If using the simplified drug form, then force the one and only
   // selector name to be the same as the product name.
   if ($GLOBALS['sell_non_drug_products'] == 2) {
    $tmpl["1"]['selector'] = $_POST['form_name'];
   }
   
   sqlStatement("DELETE FROM prices WHERE pr_id = ? AND pr_selector != ''", array($drug_id));
   for ($lino = 1; isset($tmpl["$lino"]['selector']); ++$lino) {
    $iter = $tmpl["$lino"];
    $selector = trim($iter['selector']);
    if ($selector) {
     $taxrates = "";
     if (!empty($iter['taxrate'])) {
      foreach ($iter['taxrate'] as $key => $value) {
       $taxrates .= "$key:";
      }
     }
     

     // Add prices for this drug ID and selector.
     foreach ($iter['price'] as $key => $value) {
      $value = $value + 0;
   
     } // end foreach price
    } // end if selector is present
   } // end for each selector
   // Save warehouse-specific mins and maxes for this drug.
  // sqlStatement("DELETE FROM product_warehouse WHERE pw_drug_id = ?", array($drug_id));
   foreach ($_POST['form_wh_min'] as $whid => $whmin) {
    $whmin = 0 + $whmin;
    $whmax = 0 + $_POST['form_wh_max'][$whid];
   
   }
  } // end if saving a drug

  // Close this window and redisplay the updated list of drugs.
  //
  echo "<script language='JavaScript'>\n";
  if ($info_msg) echo " alert('$info_msg');\n";
  echo " if (opener.refreshme) opener.refreshme();\n";
  if ($new_drug) {
	  
	 // echo " window.location.href='add_edit_drug.php?drug=$drug_id&lot=0'\n";
	  echo " window.close();\n";
  } else {
   echo " window.close();\n";
  }
  echo "</script></body></html>\n";
  exit();
}

if ($drug_id) {
  $row = sqlQuery("SELECT * FROM drugs WHERE drug_id = ?", array($drug_id));
  $tres = sqlStatement("SELECT * FROM drug_templates WHERE " .
   "drug_id = ? ORDER BY selector", array($drug_id));
}
else {
  $row = array(
    'name' => '',
    'active' => '1',
    'allow_multiple' => '1',
    'allow_combining' => '',
    'ndc_number' => '',
    'on_order' => '0',
    'reorder_point' => '0',
    'max_level' => '0',
    'form' => '',
    'size' => '',
    'unit' => '',
    'route' => '',
	'supplier' => '',
    'cyp_factor' => '',
    'related_code' => '',
  );
}
?>





<form method='post' name='theform' action='add_edit_drug.php?drug=<?php echo $drug_id; ?>'>
<center>


	
<table  width='50%'  cellspacing="10" style="border: 1px solid black;">

 <tr>
 <td width="20%"  valign='top' nowrap><b><?php echo xlt('Select Supplier'); ?>:</b></td>
	
<td>
 <select style="width:100%;height:2em;"  name="supplier" class="selectbox" required>
    
       <option value="">Select</option>
    <?php
         $list = sqlStatement("SELECT  * FROM `list_options` WHERE `list_id`='drug_supplier'");
 while($list1 = sqlFetchArray($list)){

         
 
   
          ?> 
            <option value="<?php echo $list1['title'];?><?php if($list1['title']==$supply) echo "selected"; ?>"> <?php echo $list1['title'];?></option>
       <?php   }?>
 
    </select>
 
 </td>
 <td><a href='addnewsupplier.php'>Add New Supplier</a></td>


 
 

 
 <tr>
  <td valign='top' width="20%" nowrap><b><?php echo xlt('Invoice Number'); ?>:</b></td>
  <td>
   <input type='text' size='10'  name='invoice' value="" maxlength='80' style='width:100%' placeholder="Please Enter Invoice Number" required/>
  </td><td></td>
 </tr>
 
</table>
 
 <br><br><br><br><br><br>
 
 <table border='1' width='50%'   id="dataTable" style=" border: 1px solid black;"> 
 <tr>
  <th nowrap>S.No.</th>
   <th nowrap></th>
   <th nowrap></th>
  <th nowrap width='200px'>Medicine Name</th>
   <th nowrap>Manufacturer</th>
   
   <th nowrap>Medicine Type</th>
    <th nowrap>Group</th>
   <!--<th nowrap>New</br>Medicine</th>-->
   <th nowrap>In </br>Stock</th>
   <th  nowrap>Batch </br>Number</th>
    <th  nowrap>Schedule </br>H</th>
  <th  nowrap>Quantity</th>
  <th  nowrap>Free</th>
  <th  nowrap>Pack</th>
  <th  nowrap>Pack </br>Type</th>
  <th colspan='2' nowrap>Expiry Date</th>
   <th  nowrap>M.R.P.</th>
    
   <th  nowrap>Trade</br>Price</th>
    <th  nowrap>GST %</th>
	 <th  nowrap>Discount</br>(%)</th>
	 <th  nowrap>Net Value</th>
 </tr>
 
 
 
 <?php
 
 // $last_score=0;
  $list1 = sqlStatement("SELECT  Medicine_Name FROM `medicine_master`");
  while($list2=sqlFetchArray($list1)) 
  {
    $rows[] = $list2;
	
  }
 
  $rowCount = count($rows);

 $i=1;
  while($i<=10) 
  {
  
     
?> 
 
 <tr>
 
   <td>
   <?php echo $i ?>
  </td> 
  
   <td><a href="#" id="<?php echo 'toggle_doc'.$i ?>" title="Doctor Not listed? Add Doctor"><i class="fa fa-plus-circle"></i></a><td>
  <td class='text' id="<?php echo 'input_dr'.$i ?>" style="display:none">
	 <input type="text" name="form_name[]" disabled="disabled" style="width: 100%" >
	 </td>
	 
	 
	 
	 <td id="<?php echo 'select_dr'.$i ?>">
						<div>
				<div>
					
				
			     
               

			
					<select id="<?php echo 'name'.$i ?>" placeholder="Select Medicine" style="border:1px solid white;" name='form_name[]'></select>
				</div>
				
				<script>
				
				

				$('#<?php echo 'name'.$i ?>').selectize({
					maxItems: 1,
					valueField: 'id',
					labelField: 'title',
					searchField: 'title',
					options: 
                      [
					  
					   <?php
        
                            for($k=0;$k<$rowCount;$k++){
                            $id=$rows[$k]['Medicine_Name'];
						    $id1=str_replace("'", "", $id);
						    $title=$rows[$k]['Medicine_Name'];
						    $title1=str_replace("'", "", $title);
				  
                        ?>  
						{id: '<?php echo $id1; ?>', title: '<?php echo $title1; ?>'},
					<?php } ?>
					],
					create: false
				});
				</script>
			</div>
					</td>
                        
	
	<!--<td id="<!--?php echo 'select_dr'.$i ?>">
 <select style="width:100%;height:2em;"  name="form_name[]" class="selectbox" id="<?php echo 'name'.$i?>">
    
       <option value="">Select</option>
	   <!--<option value="add">Add New</option>-->
    <!--?php
        
      for($k=0;$k<$rowCount;$k++){
              
       ?> 
            <option value="<!--?php echo $rows[$k]['Medicine_Name'];?>"> <!--?php  echo $rows[$k]['Medicine_Name'];?></option>
       <!--?php   }?>
 
    </select>
 
 </td>-->
 
  <td class='text' id="<?php echo 'input_dr'.$i ?>" style="display:none">
	 <input type="text" name="mfr[]" disabled="disabled" style="width: 100%">
	 </td>
 <td id="<?php echo 'select_dr'.$i ?>">
 <select style="width:100%;height:2em;border:1px solid white;"  name="mfr[]"  id="<?php echo 'manu'.$i?>" >
    
       
    </select>
 
 </td>
 
  
  <td class='text' id="<?php echo 'input_dr'.$i ?>" style="display:none">
	 <input type="text" name="medType[]" disabled="disabled" style="width: 100%" >
	 </td>
 <td id="<?php echo 'select_dr'.$i ?>">
 <select style="width:100%;height:2em;border:1px solid white;"  name="medType[]" id="<?php echo 'mtype'.$i?>" >
    
       
    </select>
 
 </td>
 
 

 
  <td>
 
 <select  style="width:100px;height:2em;border:1px solid white;"   name="group[]">
  <option value="Medical">Medical</option>
  <option value="Non-Medical">Non-Medical</option>
  <option value="Cosmetics">Cosmetics</option>
   <option value="Surgical">Surgical </option>
  
  
</select> 
 
  </td>


  <td>
   <input type='text' name='instock[]' maxlength='80' value='0' class="rgt"  style="width:80px;height:2em;border:1px solid white;"   pattern="\d*"/>
  </td>
  
  <td>
   <input type='text' name='batch[]'  style="width:80px;height:2em;border:1px solid white;"  value=''  id="<?php echo 'b'.$i?>"/>
  </td>
  
  <td>
 <select style="width:100%;height:2em;border:1px solid white;"  name="schedule_h[]" id="<?php echo 'sch_h'.$i?>" >
    
       
    </select>
 
 </td>
  
  <td>
   <input type='text' size="10" name='qty[]' maxlength='80' value='' class="rgt " style="width:80px;height:2em;border:1px solid white;"  id="<?php echo 'q'.$i?>"  pattern="\d*" />
  </td>
  
  
  <td>
   <input type='text'  name='free[]' maxlength='80' value='' class="rgt" style="width:80px;height:2em;border:1px solid white;"  pattern="\d*" />
  </td>
  
  <td>
   <input type='text' size="10" name='pack[]' maxlength='80'  class="rgt" style="width:80px;height:2em;border:1px solid white;"  id="<?php echo 'p'.$i?>"/>
  </td>
  
   <td>
 <input type='text' size="10" name='type[]' maxlength='80'  class="rgt" style="width:80px;height:2em;border:1px solid white;" />
 </td>
  
  
   <td colspan>
 <select name="month[]" style="width:80px;height:2em;border:1px solid white;"  >
  <option value="01">Jan</option>
  <option value="02">Feb</option>
  <option value="03">Mar</option>
  <option value="04">Apr</option>
  <option value="05">May</option>
  <option value="06">June</option>
  <option value="07">July</option>
  <option value="08">Aug</option>
  <option value="09">Sep</option>
  <option value="10">Oct</option>
  <option value="11">Nov</option>
  <option value="12">Dec</option>
 </td>
 
  <td>
 <select name="year[]" style="width:80px;height:2em;border:1px solid white;"  >
  
  <option value="2018">2018</option>
  <option value="2019">2019</option>
  <option value="2020">2020</option>
  <option value="2021">2021</option>
  <option value="2022">2022</option>
  <option value="2023">2023</option>
  <option value="2024">2024</option>
  <option value="2025">2025</option>
	  
  
 </td>
  
 
  
  <td>
  <input type='text' size="10" name='mrp[]' maxlength='80' value='' class="rgt" style="width:80px;height:2em;border:1px solid white;"  id="<?php echo 'mrp'.$i?>" placeholder="00.00"/>
  </td>
  
  
  
  <td>
  <input type='text' name='trade[]' maxlength='80' value='' class="rgt" style="width:80px;height:2em;border:1px solid white;"  id="<?php echo 'tp'.$i?>"  placeholder="00.00" />
   
  </td>
  
 
   <!--<td class='text' id="<?php echo 'input_dr'.$i ?>" style="display:none">
	 <input type="text" name="vat[]" disabled="disabled" style="width: 100%">
	 </td>-->
  <td>
 
 <select name="vat[]" style="width:80px;height:2em;border:1px solid white;"  id="<?php echo 'v'.$i?>">
  <option value="12">12%</option>
  <option value="18">18%</option>
  <option value="28">28%</option>
  
  
  
</select> 
 
  </td>
  
   <td>
  <input type='text' name='discount[]'  value='' class="discount" id="<?php echo 'd'.$i; ?>" style="width:80px;height:2em;border:1px solid white;"  />
  </td>
  
  <td>
  <input type='number' step="any" size='10' name='total[]' maxlength='80' value='' class="sum" id="<?php echo 't'.$i?>" style="width:80px;height:2em;border:1px solid white;" />
  </td>
  
  </tr>
  
  <?php
   

 $i++;  

         
}
 

?>

 <tr><th colspan="20" style="text-align:right">Total Amount: </th>
 <th><input type="number" step="any" class="total" id="rgt" style="width:80px;height:2em;border:1px solid white;" value="" required /></th><tr> 
 
  
  

   </table>
 

<p>
<!--
<--INPUT type="button" value="Add Row" onclick="addRow('dataTable')" /> -->
<input type='submit' name='form_save' value='<?php echo xla('Save'); ?>' />

<?php if (acl_check('admin', 'super')) { ?>
&nbsp;
<input type='submit' name='form_delete' value='<?php echo xla('Delete'); ?>' style='color:red' />
<?php } ?>

&nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick='window.close()' />

</p>

</center>
</form>



<div width="100%">		
<script language="JavaScript">



<?php
 if ($alertmsg) {
  echo "alert('" . htmlentities($alertmsg) . "');\n";
 }
?>
</script>
</div>
</body>
</html>
