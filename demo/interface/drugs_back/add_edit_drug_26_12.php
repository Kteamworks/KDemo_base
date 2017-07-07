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
        <link rel="stylesheet" href="datepicker/public/css/style.css" type="text/css">
		<link type="text/css" rel="stylesheet" href="datepicker/libraries/syntaxhighlighter/public/css/shCoreDefault.css">

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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script language="JavaScript">


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
	
	var mrp = $("#mrp1").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t1").val(result);
	
});

$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d2").val();
	var t = $("#tp2").val();
	var q = $("#q2").val();
	var v = $("#v2").val();
	var mrp = $("#mrp2").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t2").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d3").val();
	var t = $("#tp3").val();
	var q = $("#q3").val();
	var v = $("#v3").val();
	var mrp = $("#mrp3").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t3").val(result);
});

$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d4").val();
	var t = $("#tp4").val();
	var q = $("#q4").val();
	var v = $("#v4").val();
    var mrp = $("#mrp4").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t4").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d5").val();
	var t = $("#tp5").val();
	var q = $("#q5").val();
	var v = $("#v5").val();
	var mrp = $("#mrp5").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t5").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d6").val();
	var t = $("#tp6").val();
	var q = $("#q6").val();
	var v = $("#v6").val();
	var mrp = $("#mrp6").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t6").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d7").val();
	var t = $("#tp7").val();
	var q = $("#q7").val();
	var v = $("#v7").val();
	var mrp = $("#mrp7").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t7").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d8").val();
	var t = $("#tp8").val();
	var q = $("#q8").val();
	var v = $("#v8").val();
	var mrp = $("#mrp8").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t8").val(result);
});

$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d9").val();
	var t = $("#tp9").val();
	var q = $("#q9").val();
	var v = $("#v9").val();
	var mrp = $("#mrp9").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t9").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d10").val();
	var t = $("#tp10").val();
	var q = $("#q10").val();
	var v = $("#v10").val();
	var mrp = $("#mrp10").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t10").val(result);
});

$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d11").val();
	var t = $("#tp11").val();
	var q = $("#q11").val();
	var v = $("#v11").val();
	var mrp = $("#mrp11").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t11").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d12").val();
	var t = $("#tp12").val();
	var q = $("#q12").val();
	var v = $("#v12").val();
	var mrp = $("#mrp12").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t12").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d13").val();
	var t = $("#tp13").val();
	var q = $("#q13").val();
	var v = $("#v13").val();
	var mrp = $("#mrp13").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t13").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d14").val();
	var t = $("#tp14").val();
	var q = $("#q14").val();
	var v = $("#v14").val();
    var mrp = $("#mrp1").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t14").val(result);
});

$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d15").val();
	var t = $("#tp15").val();
	var q = $("#q15").val();
	var v = $("#v15").val();
	var mrp = $("#mrp15").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t15").val(result);
});



$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d16").val();
	var t = $("#tp16").val();
	var q = $("#q16").val();
	var v = $("#v16").val();
	var mrp = $("#mrp16").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t16").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d17").val();
	var t = $("#tp17").val();
	var q = $("#q17").val();
	var v = $("#v17").val();
	var mrp = $("#mrp17").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t17").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d18").val();
	var t = $("#tp18").val();
	var q = $("#q18").val();
	var v = $("#v18").val();
	var mrp = $("#mrp18").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t18").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d19").val();
	var t = $("#tp19").val();
	var q = $("#q19").val();
	var v = $("#v19").val();
	var mrp = $("#mrp19").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t19").val(result);
});
$(document).on("focus", ".sum", function() {
   // var d = 0;
    var d = $("#d20").val();
	var t = $("#tp20").val();
	var q = $("#q20").val();
	var v = $("#v20").val();
	var mrp = $("#mrp20").val();
	var ma = [(+mrp)*100]/(100 + (+v));
	var dv= [(+d) * (+t)]/100;
	var vv= [(+v) * (+ma)*(+q)]/100;
	var result = (+q)*[(+t) - (+dv) ]+ (+vv);
	$("#t20").val(result);
});










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

<body class="body_top">
<?php
// If we are saving, then save and close the window.
// First check for duplicates.
if ($_POST['sbtrow']) {
session_start();
//$_SESSION['row']=$_POST['trr'];
//$tableRow=$_SESSION['row'];
$tableRow=$_POST['trr'];

}



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
		
		
		
		if($selected=='add')
		{
		  $selected = $_POST['form_name1'][$j];
		
		
		}
		
		$batch= $_POST['batch'][$j];
		$date= $_POST['date'][$j];
		$qty= $_POST['qty'][$j];
		$pack= $_POST['pack'][$j];
		$mrp= $_POST['mrp'][$j];
		
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
		
		
		
			 $drug_id = sqlInsert("INSERT INTO drugs ( " .
    "name,mfr,inStock,supplier,batch,quantity,totalStock,free,date,pack,packType,expdate,mrp,mrpa,PricePerUnit,tradePrice,discount,vat,totalValue,invoice,max_level, form, " .
    "size, unit, route, cyp_factor, related_code, " .
    "allow_multiple, allow_combining, active " .
    ") VALUES ( " .
    "'" . $selected          . "', " .
    "'" . $mfr          . "', " .
    "'" . $instock          . "', " .
	 "'" .$sup. "', " .
    "'" . $batch    . "', " .
    "'" . $qty      . "', " .
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
	"'" . $selected          . "', " .
	"'" . $selected          . "', " .
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
        "'" . $selected . "', " .
		 "'" . $totalStock . "', " .
        "'" . add_escape_custom($form_notes) . "' )");
		 
		 
		 
	
	
	
	
	
	
	
	$j++;
		}  
		
  }
		else{
			
			$invoice= $_POST['invoice'];
			
			 $j=0;
	foreach($_POST['form_name'] as $selected) {
		
		
		if($selected=='add')
		{
		  $selected = $_POST['form_name1'][$j];
		
		
		}
		
		$batch= $_POST['batch'][$j];
		$date= $_POST['date'][$j];
		$qty= $_POST['qty'][$j];
		$pack= $_POST['pack'][$j];
		$mrp= $_POST['mrp'][$j];
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

	
    $drug_id = sqlInsert("INSERT INTO drugs ( " .
    "name,mfr,inStock,supplier,batch,quantity,totalStock,free,date,pack,packType,expdate,mrp,mrpa,PricePerUnit,tradePrice,discount,vat,totalValue,invoice,max_level, form, " .
    "size, unit, route, cyp_factor, related_code, " .
    "allow_multiple, allow_combining, active " .
    ") VALUES ( " .
    "'" . $selected          . "', " .
	"'" . $mfr          . "', " .
	"'" . $instock          . "', " .
	 "'" .escapedff('form_supplier'). "', " .
    "'" . $batch    . "', " .
    "'" . $qty      . "', " .
	 "'" . $totalStock      . "', " .
	"'" . $free      . "', " .
	"'" . date('Y/m/d') . "', " .
	 "'" . $pack      . "', " .
	 "'" . $type      . "', " .
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
	"'" . $selected          . "', " .
	"'" . $selected          . "', " .
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
	
	//----------------------------------Drugs_warehouse---------------------------------------//
	
	
	
	 $warehouse = 	sqlInsert("INSERT INTO product_warehouse ( " .
        "pw_drug_id, pw_warehouse, pw_min_level, pw_max_level ) VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', "                            .
          "'onsite ', " .
          "' 1, " .
         "' 10000' " .
         ")");	
	
//--------------------------------------------drug_inventory-----------------------------------------//
	
	$lot_id = sqlInsert("INSERT INTO drug_inventory ( " .
          "drug_id, lot_number, manufacturer, expiration, " .
          "vendor_id, warehouse_id, on_hand " .
          ") VALUES ( " .
          "'" . add_escape_custom($drug_id) . "', "                            .
          "'" . $batch. "', " .
          "'" . $mfr . "', " .
         "'" . $date . "', " .
          "'" . add_escape_custom($_POST['form_vendor_id'])    . "', " .
          "' onsite ', " .
          "'" . $totalStock              . "' "  .
          ")");	
		  
		  
		 //$totalStock = $instock +  ($qty + $free)*$pack ; 
	//-----------------------------Drug_sales------------------------------------------------------//	
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

		//-----------------------------drug_templates---------------------------------//
		
		
		
		
		 sqlInsert("INSERT INTO drug_templates ( " .
      "drug_id, selector,quantity,taxrates " .
      ") VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', " .
        "'" . $selected . "', " .
		 "'" . $totalStock . "', " .
        "'" . add_escape_custom($form_notes) . "' )");
		  

		  
	
	$j++;
		}
			
  }
   
	/*-----logic for unique primary key stats--------*/
	
	 
	 $test = sqlStatement("SELECT  * FROM `list_options` WHERE `list_id`='drug_supplier' order by CONVERT(SUBSTRING(option_id, 1), SIGNED INTEGER) desc limit 1");
 while($test1 = sqlFetchArray($test)){

 $test2=$test1['option_id'];
 }
  $test3= $test2+1;
  
	/*-------logic Ends-------------------------------*/
	
	
	
   if(isset($_POST['supplier'])&& $_POST['supplier']!=""){
			 
			  $sup=$_POST['supplier'];
			
			  
	$valid = sqlQuery("SELECT title FROM list_options WHERE " .
"title = '"  . $sup  . "' " );

   if(!$valid)	
   {	   
		echo "value";	  
   
	$supply_id = sqlInsert("INSERT INTO list_options ( " .
    "list_id,title,option_id " .
    ") VALUES ( " .
    "'" . 'drug_supplier'          . "', " .
	 "'"  . $sup ."', " .
	  "'" . $test3          . "' " .
    ")");
	
   }
   }
   else{
	   
	    
		$valid = sqlQuery("SELECT title FROM list_options WHERE " .
"title = '"  . escapedff('form_supplier')  . "' " );

  
  
        if(!$valid)
		{
	       
		 
	   $supply_id = sqlInsert("INSERT INTO list_options ( " .
    "list_id,title,option_id " .
    ") VALUES ( " .
    "'" . 'drug_supplier'          . "', " .
	 "'"  . escapedff('form_supplier') ."', " .
	  "'" . $test3          . "' " .
    ")");
	   
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
      if ($value) {
        sqlStatement("INSERT INTO prices ( " .
          "pr_id, pr_selector, pr_level, pr_price ) VALUES ( " .
          "?, ?, ?, ? )",
          array($drug_id, $selector, $key, $value));
      }
     } // end foreach price
    } // end if selector is present
   } // end for each selector
   // Save warehouse-specific mins and maxes for this drug.
  // sqlStatement("DELETE FROM product_warehouse WHERE pw_drug_id = ?", array($drug_id));
   foreach ($_POST['form_wh_min'] as $whid => $whmin) {
    $whmin = 0 + $whmin;
    $whmax = 0 + $_POST['form_wh_max'][$whid];
    if ($whmin != 0 || $whmax != 0) {
      sqlStatement("INSERT INTO product_warehouse ( " .
        "pw_drug_id, pw_warehouse, pw_min_level, pw_max_level ) VALUES ( " .
        "?, ?, ?, ? )", array($drug_id, $whid, $whmin, $whmax));
    }
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

<form method="post" name="row" action="" >

<center>
<table  width='50%'  cellspacing="10" style="border: 1px solid black;">
<tr> <th>
<input type="number" name="trr" placeholder="Enter Number of Rows" max="20" size="50" pattern="\d*"></th> <th> <input type="submit" value="Enter" name="sbtrow"></th>
</tr>
</center>
</table>


</form>



<form method='post' name='theform' action='add_edit_drug.php?drug=<?php echo $drug_id; ?>'>
<center>


	
<table  width='50%'  cellspacing="10" style="border: 1px solid black;">

 <tr>
 <td width="20%"  valign='top' nowrap><b><?php echo xlt('Select Supplier'); ?>:</b></td>
	
<td>
 <select style="width:100%;height:2em;"  name="supplier">
    
       <option value="">Select</option>
    <?php
         $list = sqlStatement("SELECT  * FROM `list_options` WHERE `list_id`='drug_supplier'");
 while($list1 = sqlFetchArray($list)){

         
 
   
          ?> 
            <option value="<?php echo $list1['title'];?><?php if($list1['title']==$supply) echo "selected"; ?>"> <?php echo $list1['title'];?></option>
       <?php   }?>
 
    </select>
 
 </td>


 
 
  <tr>
  <td valign='top' width="20%" nowrap><b><?php echo xlt('Add Supplier'); ?>:</b></td>
  <td>
   <input type='text' size='10' name='form_supplier' value="<?php echo $supply;?>" maxlength='80' style='width:100%' placeholder="Enter Supplier if it is not in Dropdown List" />
  </td>
 </tr>
 
 <tr>
  <td valign='top' width="20%" nowrap><b><?php echo xlt('Invoice Number'); ?>:</b></td>
  <td>
   <input type='text' size='10' name='invoice' value="" maxlength='80' style='width:100%' placeholder="Please Enter Invoice Number" required/>
  </td>
 </tr>
 
</table>
 
 <br><br><br><br><br><br>
 
 <table border='0' width='100%'   id="dataTable" style=" border: 1px solid black;"> 
 <tr>
  <th nowrap>S.No.</th>
   <th nowrap>Manufacturer</th>
   <th nowrap>Name</th>
   <th nowrap>New</br>Medicine</th>
   <th nowrap>In </br>Stock</th>
   <th  nowrap>Batch </br>Number</th>
  <th  nowrap>Quantity</th>
  <th  nowrap>Free</th>
  <th  nowrap>Pack</th>
  <th  nowrap>Pack </br>Type</th>
  <th  nowrap>Expiry </br>Date</th>
   <th  nowrap>M.R.P.</th>
    
   <th  nowrap>Trade</br>Price</th>
    <th  nowrap>Vat %</th>
	 <th  nowrap>Discount</br>(%)</th>
	 <th  nowrap>Net Value</th>
 </tr>
 
 
 
 <?php
 $i=1;
  while($i<=$tableRow) 
  {
  
     
?> 
 
 
 
 <tr>
 
   <td>
   <?php echo $i ?>
  </td> 
  <td>
   <input type='text'  name='mfr[]' maxlength='80' value='' style='width:100%'  placeholder="Manufacturer" />
  </td>
  
  
 <!-- <td>
  <!-- <input type='text' name='mfr[]' maxlength='80' value='' style='width:100%' placeholder="Manufacturer Name" />-->
   <!--input type="text" name="form_name[]" class="typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="Medicine">
  </td-->
 

<td>
 <select style="width:100%;height:2em;"  name="form_name[]" id="<?php echo 'name'.$i?>">
    
       <option value="">Select</option>
	   <option value="add">Add New</option>
    <?php
         $list1 = sqlStatement("SELECT  name FROM `drugs`");
 while($list2 = sqlFetchArray($list1)){

         
 
   
          ?> 
            <option value="<?php echo $list2['name'];?>"> <?php echo $list2['name'];?></option>
       <?php   }?>
 
    </select>
 
 </td>
 
 
 <td>
   <input type="text" name="form_name1[]" autocomplete="off" id="<?php echo 'nm'.$i?>" placeholder="Name">
  </td>
 

  <td>
   <input type='text' size="10" name='instock[]' maxlength='80' value='0' readonly class="rgt" style='width:100%' placeholder="interger" pattern="\d*"/>
  </td>
  
  <td>
   <input type='text' name='batch[]' maxlength='80' value='' style='width:100%' placeholder="Batch Number" id="<?php echo 'b'.$i?>" />
  </td>
  
  <td>
   <input type='text' size="10" name='qty[]' maxlength='80' value='' class="rgt" style='width:100%' id="<?php echo 'q'.$i?>"  pattern="\d*" placeholder="Integer" />
  </td>
  
  
  <td>
   <input type='text' size="10" name='free[]' maxlength='80' value='' class="rgt" style='width:100%' pattern="\d*" placeholder="interger" />
  </td>
  
  <td>
   <input type='text' size="10" name='pack[]' maxlength='80'  class="rgt" style='width:100%'   placeholder="interger" id="<?php echo 'p'.$i?>" />
  </td>
  
   <td>
 <input type='text' size="10" name='type[]' maxlength='80'  class="rgt" style='width:100%'  placeholder="Type" />
 </td>
  
  
  <td>
   <input type="text" size="10" name="date[]" value="" class="datepicker-example11" placeholder="MM-YYYY" id="<?php echo 'dt'.$i?>" />
  </td>
  
  <td>
  <input type='text' size="10" name='mrp[]' maxlength='80' value='' class="rgt" style='width:100%'  id="<?php echo 'mrp'.$i?>" placeholder="00.00"/>
  </td>
  
  
  
  <td>
  <input type='text' size="10" name='trade[]' maxlength='80' value='' class="rgt" style='width:100%' id="<?php echo 'tp'.$i?>"  placeholder="00.00" />
   
  </td>
  
 
  
  <td>
 
 <select name="vat[]" id="<?php echo 'v'.$i?>">
  <option value="0">0%</option>
  <option value="5.50">5.50%</option>
  <option value="14.50">14.50%</option>
  
</select> 
 
  </td>
  
   <td>
  <input type='text' size="10" name='discount[]'  value='' class="discount" id="<?php echo 'd'.$i; ?>" style='width:100%' placeholder="discount"   />
  </td>
  
  <td>
  <input type='number' step="any" size='10' name='total[]' maxlength='80' value='' class="sum" id="<?php echo 't'.$i?>" style='width:100%'  placeholder="00.00" />
  </td>
  
  </tr>
  
  <?php
   

 $i++;  

         
}
 

?>

 <tr><th colspan="15" style="text-align:right">Total Amount: </th><th><input type="number" step="any" class="total" id="rgt" value="" /></th><tr> 
 
  
  

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

 <script type="text/javascript" src="datepicker/public/javascript/jquery-1.12.0.js"></script>
        <script type="text/javascript" src="public/javascript/zebra_datepicker.js"></script>
        <script type="text/javascript" src="datepicker/public/javascript/core.js"></script>



 <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/XRegExp.js"></script>
        <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/shCore.js"></script>
        <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/shLegacy.js"></script>
        <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/shBrushJScript.js"></script>
        <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/shBrushXML.js"></script>

        <script type="text/javascript">
            SyntaxHighlighter.defaults['toolbar'] = false;
            SyntaxHighlighter.all();
        </script>
		
		
		
		

  
    <script src="typeahead.min.js"></script>
    <script>
    $(document).ready(function(){
    $('input.typeahead').typeahead({
        name: 'typeahead',
        remote:'search.php?key=%QUERY',
        limit : 3
    });
});
    </script>
    <style type="text/css">


.typeahead {
	background-color: #FFFFFF;
}
.typeahead:focus {
	border: 2px solid #0097CF;
}
.tt-query {
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
}
.tt-hint {
	color: #999999;
}
.tt-dropdown-menu {
	background-color: #FFFFFF;
	border: 1px solid rgba(0, 0, 0, 0.2);
	border-radius: 8px;
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	margin-top: 12px;
	padding: 8px 0;
	width: 422px;
}
.tt-suggestion {
	font-size: 24px;
	line-height: 24px;
	padding: 3px 20px;
}
.tt-suggestion.tt-is-under-cursor {
	background-color: #0097CF;
	color: #FFFFFF;
}
.tt-suggestion p {
	margin: 0;
}
</style>		
		
		



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
