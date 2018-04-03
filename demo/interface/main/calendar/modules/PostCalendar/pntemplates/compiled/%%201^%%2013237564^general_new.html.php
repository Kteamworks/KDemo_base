<?php /* Smarty version 2.6.2, created on 2018-04-03 13:51:47
         compiled from C:%5Cxampp%5Chtdocs%5CKDemo_base%5Cdemo%5Cinterface%5Cforms%5Cvitals/templates/vitals/general_new.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:\xampp\htdocs\KDemo_base\demo\interface\forms\vitals/templates/vitals/general_new.html', 35, false),array('function', 'math', 'C:\xampp\htdocs\KDemo_base\demo\interface\forms\vitals/templates/vitals/general_new.html', 184, false),array('modifier', 'date_format', 'C:\xampp\htdocs\KDemo_base\demo\interface\forms\vitals/templates/vitals/general_new.html', 153, false),)), $this); ?>
<html>
<head>
<?php html_header_show(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<script src="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
<style type="text/css">@import url(<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/library/dynarch_calendar.js"></script>
<?php  include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php");  ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/library/dynarch_calendar_setup.js"></script>

<script type="text/javascript">
var mypcc = '<?php  echo $GLOBALS['phone_country_code']  ?>';
<?php echo '
// Only load jquery if not already closing. This page serves two purposes now,
//  for entring of vitals and for graphing/trending previous vitals by being embedded
//  in the interface/patient_file/encounter/trend_form.php page.
if (typeof jQuery == \'undefined\') {
  document.write("<script type=\'text/javascript\' src=\'';  echo $GLOBALS['webroot'];  echo '/library/js/jquery.js\'><\\/script>")
}

function vitalsFormSubmitted() {
	var invalid = "";
	
	var elementsToValidate = new Array();
	
	elementsToValidate[0] = new Array();
	elementsToValidate[0][0] = \'weight_input\';
	elementsToValidate[0][1] = \'';  echo smarty_function_xl(array('t' => 'Weight'), $this); echo '\' + \' (\' + \'';  echo smarty_function_xl(array('t' => 'lbs'), $this); echo '\' + \')\';
	
	elementsToValidate[1] = new Array();
	elementsToValidate[1][0] = \'weight_input_metric\';
	elementsToValidate[1][1] = \'';  echo smarty_function_xl(array('t' => 'Weight'), $this); echo '\' + \' (\' + \'';  echo smarty_function_xl(array('t' => 'kg'), $this); echo '\' + \')\';
	
	elementsToValidate[2] = new Array();
	elementsToValidate[2][0] = \'height_input\';
	elementsToValidate[2][1] = \'';  echo smarty_function_xl(array('t' => "Height/Length"), $this); echo '\' + \' (\' + \'';  echo smarty_function_xl(array('t' => 'in'), $this); echo '\' + \')\';
	
	elementsToValidate[3] = new Array();
	elementsToValidate[3][0] = \'height_input_metric\';
	elementsToValidate[3][1] = \'';  echo smarty_function_xl(array('t' => "Height/Length"), $this); echo '\' + \' (\' + \'';  echo smarty_function_xl(array('t' => 'cm'), $this); echo '\' + \')\';

	elementsToValidate[4] = new Array();
	elementsToValidate[4][0] = \'bps_input\';
	elementsToValidate[4][1] = \'';  echo smarty_function_xl(array('t' => 'BP Systolic'), $this); echo '\';
	
	elementsToValidate[5] = new Array();
	elementsToValidate[5][0] = \'bpd_input\';
	elementsToValidate[5][1] = \'';  echo smarty_function_xl(array('t' => 'BP Diastolic'), $this); echo '\';
	
	for (var i = 0; i < elementsToValidate.length; i++) {
		var current_elem_id = elementsToValidate[i][0];
		var tag_name = elementsToValidate[i][1];
		
		document.getElementById(current_elem_id).classList.remove(\'error\');
		
		if (isNaN(document.getElementById(current_elem_id).value)) {
			invalid += "';  echo smarty_function_xl(array('t' => 'The following field has an invalid value'), $this); echo '" + ": " + tag_name + "\\n";
			document.getElementById(current_elem_id).className = document.getElementById(current_elem_id).className + " error";
			document.getElementById(current_elem_id).focus();
		}
	}
	
	if (invalid.length > 0) {
		invalid += "\\n" + "';  echo smarty_function_xl(array('t' => "Please correct the value(s) before proceeding!"), $this); echo '";
		alert(invalid);	
		
		return false;
	} else {

		return top.restoreSession();
	}
}
</script>
<style type="text/css" title="mystyles" media="all">
.title {
	font-size: 120%;
	font-weight: bold;
}
.currentvalues {
    border-right: 1px solid black;
    padding-right:5px;
    text-align: left;
}
.valuesunfocus {
    border-right: 1px solid black;
    padding-right:5px;
    background-color: #ccc;
    text-align: left;
}
.unfocus {
    background-color: #ccc;
}
.historicalvalues {
    background-color: #ccc;
    border-bottom: 1px solid #ddd;
    border-right: 1px solid #ddd;
    text-align: right;
}
table {
    border-collapse: collapse;
}
td,th {
    padding-right: 10px;
    padding-left: 10px;
}
.hide {
    display:none;
}
.readonly {
    display:none;
}

.error {
  border:2px solid red;
}
</style>
'; ?>


</head>
<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
" style="background-color:white"> 
<section  style="    padding-left: 8px;">
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
		<li id="vitals" style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a><i class="fa fa-note" style="margin-right: 8px;"></i>Medical Issues</a></li>
			<li class="current" style="<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"></i><em>Vitals</em></li>
			<li id="ros" style="<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
			<li id="visit" style="<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a><i class="fa fa-note" style="margin-right: 8px;"></i>Visit Notes</a></li>
			<li id="lab" style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a><i class="fa fa-note" style="margin-right: 8px;"></i>Lab Tests</a></li>
			<li id="prescription" style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a><i class="fa fa-note" style="margin-right: 8px;"></i>Prescription</a></li>
			<li id="plan" style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a><i class="fa fa-note" style="margin-right: 8px;"></i>Plan</a></li>
			<li id="referral" style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a><i class="fa fa-note" style="margin-right: 8px;"></i>Referral</a></li>
			<li id="admission" style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a><i class="fa fa-note" style="margin-right: 8px;"></i>Admission</a></li>
			<li id="summary"  style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a>Summary</a></li>
		</ol>
	</nav>
</section>
<p><table><tr><td><span class="title"><?php echo smarty_function_xl(array('t' => 'Vitals'), $this);?>
</span></td><td>&nbsp;&nbsp;&nbsp;<a href="../summary/demographics.php" class="readonly css_button_small" onclick="top.restoreSession()"> <span><?php echo smarty_function_xl(array('t' => 'View Patient'), $this);?>
</span></a></td><td class='float:right'>

</td></tr></table></p>
<form name="vitals" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/forms/vitals/save.php" onSubmit="return vitalsFormSubmitted()">
<div id="chart"></div>
<table>
	<tr><th align="left"><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</th><th align="left"><?php echo smarty_function_xl(array('t' => 'Unit'), $this);?>
</th>
	<th class='currentvalues' title='<?php echo smarty_function_xl(array('t' => 'Date and time of this observation'), $this);?>
'>
    <input type='text' size='14' name='date' id='date'
     value='<?php echo ((is_array($_tmp=$this->_tpl_vars['vitals']->get_date())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M")); ?>
'
     onkeyup='datekeyup(this,mypcc,true)' onblur='dateblur(this,mypcc,true)' />
    <img src='<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/pic/show_calendar.gif' id='img_date' align='absbottom'
     width='24' height='22' border='0' alt='[?]' style='cursor:pointer' />
</th>
	<?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
		<th class='historicalvalues'><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M")); ?>
</th>
	<?php endforeach; unset($_from); endif; ?></tr>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 3): ?><tr class="hide"><?php else: ?><tr><?php endif; ?>
                <?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="unfocus graph" id="temperature_metric"><?php else: ?><td class="graph" id="temperature_metric"><?php endif;  echo smarty_function_xl(array('t' => 'Temperature'), $this);?>
</td>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="unfocus"><?php else: ?><td><?php endif;  echo smarty_function_xl(array('t' => "°F"), $this);?>
</td>
                <?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="valuesunfocus"><?php else: ?><td class='currentvalues'><?php endif; ?>
                <input type="number" min="90" max="105" size='5'  autocomplete='off' name='temperature' id='temperature_input_metric' value="<?php if ($this->_tpl_vars['vitals']->get_temperature() != 0):  echo $this->_tpl_vars['vitals']->get_temperature();  endif; ?>" ;"/>
                </td>
        <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
                <td  class='historicalvalues'><?php if ($this->_tpl_vars['result']['temperature'] != 0):  echo $this->_tpl_vars['result']['temperature'];  endif; ?></td>
        <?php endforeach; unset($_from); endif; ?></tr>
	<?php if ($this->_tpl_vars['units_of_measurement'] == 4): ?><tr class="hide"><?php else: ?><tr class="hide"><?php endif; ?>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 2): ?><td class="unfocus graph" id="weight"><?php else: ?><td class="graph" id="weight"><?php endif;  echo smarty_function_xl(array('t' => 'Weight'), $this);?>
</td>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 2): ?><td class="unfocus"><?php else: ?><td><?php endif;  echo smarty_function_xl(array('t' => 'lbs'), $this);?>
</td>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 2): ?><td class="valuesunfocus"><?php else: ?><td class='currentvalues'><?php endif; ?>
                <input type="text" size='5' name='weight' id='weight_input'   value="<?php if ($this->_tpl_vars['vitals']->get_weight() != 0):  echo $this->_tpl_vars['vitals']->get_weight();  endif; ?>"  onChange="convLbtoKg('weight_input');" title='<?php echo smarty_function_xl(array('t' => "Decimal pounds or pounds and ounces separated by #(e.g. 5#4)"), $this);?>
'/>
                </td>		
	<?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
		<td  class='historicalvalues'><?php echo $this->_tpl_vars['vitals']->display_weight($this->_tpl_vars['result']['weight']); ?>
</td>
	<?php endforeach; unset($_from); endif; ?></tr>
	
	<?php if ($this->_tpl_vars['units_of_measurement'] == 3): ?><tr class="hide"><?php else: ?><tr><?php endif; ?>
                <?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="unfocus graph" id="weight_metric"><?php else: ?><td class="graph" id="weight_metric"><?php endif;  echo smarty_function_xl(array('t' => 'Weight'), $this);?>
</td>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="unfocus"><?php else: ?><td><?php endif;  echo smarty_function_xl(array('t' => 'kg'), $this);?>
</td>
                <?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="valuesunfocus"><?php else: ?><td class='currentvalues'><?php endif; ?>
                <input type="number" min="0" max="400"  autocomplete='off' id='weight_input_metric' value="<?php if ($this->_tpl_vars['vitals']->get_weight() != 0):  echo smarty_function_math(array('equation' => "number * constant",'number' => $this->_tpl_vars['vitals']->get_weight(),'constant' => "0.45359237",'format' => "%.2f"), $this); endif; ?>" onChange="convKgtoLb('weight_input');"/>
                </td>
        <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
                <td  class='historicalvalues'><?php if ($this->_tpl_vars['result']['weight'] != 0):  echo smarty_function_math(array('equation' => "number * constant",'number' => $this->_tpl_vars['result']['weight'],'constant' => "0.45359237",'format' => "%.2f"), $this); endif; ?></td>
        <?php endforeach; unset($_from); endif; ?></tr>
	
	<?php if ($this->_tpl_vars['units_of_measurement'] == 4): ?><tr class="hide"><?php else: ?><tr><?php endif; ?>
	        <?php if ($this->_tpl_vars['units_of_measurement'] == 2): ?><td class="unfocus graph" id="height"><?php else: ?><td class="graph" id="height"><?php endif;  echo smarty_function_xl(array('t' => "Height/Length"), $this);?>
</td>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 2): ?><td class="unfocus"><?php else: ?><td><?php endif;  echo smarty_function_xl(array('t' => 'in'), $this);?>
</td>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 2): ?><td class="valuesunfocus"><?php else: ?><td class='currentvalues'><?php endif; ?>
                <input type="number" min="0" max="11" autocomplete='off' name='height' id='height_input' value="<?php if ($this->_tpl_vars['vitals']->get_height() != 0):  echo $this->_tpl_vars['vitals']->get_height();  endif; ?>" onChange="convIntoCm('height_input');"/>
                </td>
	<?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
		<td class='historicalvalues'><?php if ($this->_tpl_vars['result']['height'] != 0):  echo $this->_tpl_vars['result']['height'];  endif; ?></td>
	<?php endforeach; unset($_from); endif; ?></tr>
	
	<?php if ($this->_tpl_vars['units_of_measurement'] == 3): ?><tr class="hide"><?php else: ?><tr><?php endif; ?>
                <?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="unfocus graph" id="height_metric"><?php else: ?><td class="graph" id="height_metric"><?php endif;  echo smarty_function_xl(array('t' => "Height/Length"), $this);?>
</td>
		<?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="unfocus"><?php else: ?><td><?php endif;  echo smarty_function_xl(array('t' => 'cm'), $this);?>
</td>
                <?php if ($this->_tpl_vars['units_of_measurement'] == 1): ?><td class="valuesunfocus"><?php else: ?><td class='currentvalues'><?php endif; ?>
                <input type="number" min="0" step=".01" size='5' id='height_input_metric' value="<?php if ($this->_tpl_vars['vitals']->get_height() != 0):  echo smarty_function_math(array('equation' => "number * constant",'number' => $this->_tpl_vars['vitals']->get_height(),'constant' => "2.54",'format' => "%.2f"), $this); endif; ?>" onChange="convCmtoIn('height_input');"/>
                </td>
        <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
                <td class='historicalvalues'><?php if ($this->_tpl_vars['result']['height'] != 0):  echo smarty_function_math(array('equation' => "number * constant",'number' => $this->_tpl_vars['result']['height'],'constant' => "2.54",'format' => "%.2f"), $this); endif; ?></td>
        <?php endforeach; unset($_from); endif; ?></tr>
	
	<tr><td class="graph" id="bps"><?php echo smarty_function_xl(array('t' => 'BP Systolic'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'mmHg'), $this);?>
</td>
		<td class='currentvalues'><input type="number"  autocomplete='off' min="50" max="250" size='5'
			name='bps' id='bps_input' value="<?php echo $this->_tpl_vars['vitals']->get_bps(); ?>
"/></td>
	<?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
		<td class='historicalvalues'><?php echo $this->_tpl_vars['result']['bps']; ?>
</td>
	<?php endforeach; unset($_from); endif; ?></tr>

	<tr><td class="graph" id="bpd"><?php echo smarty_function_xl(array('t' => 'BP Diastolic'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'mmHg'), $this);?>
</td>
		<td class='currentvalues'><input type="number" autocomplete='off' min="0" max="100" size='5'
			name='bpd' id='bpd_input' value="<?php echo $this->_tpl_vars['vitals']->get_bpd(); ?>
"/></td>
	<?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
		<td  class='historicalvalues'><?php echo $this->_tpl_vars['result']['bpd']; ?>
</td>
	<?php endforeach; unset($_from); endif; ?></tr>

  <tr><td><?php echo smarty_function_xl(array('t' => 'Examination Findings'), $this);?>
<td></td></td>
    <td class='currentvalues'><input type="text" size='50'
      name="note" id='note' value="<?php echo $this->_tpl_vars['vitals']->get_note(); ?>
" /></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td class='historicalvalues'><?php echo $this->_tpl_vars['result']['note']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?></tr>

	<tr>
	<td colspan='3' style='text-align:center'>
	<?php if ($this->_tpl_vars['patient_age'] <= 20 || ( preg_match ( '/month/' , $this->_tpl_vars['patient_age'] ) )): ?>
	<!-- only show growth-chart button for patients < 20 years old -->
	<!-- <input type="button" id="growthchart" value="<?php echo smarty_function_xl(array('t' => "Growth-Chart"), $this);?>
" style='margin-left: 20px;'> -->
	<input type="button" id="pdfchart" value="<?php echo smarty_function_xl(array('t' => "Growth-Chart"), $this);?>
 (<?php echo smarty_function_xl(array('t' => 'PDF'), $this);?>
)" style='margin-left: 20px;'>
        <input type="button" id="htmlchart" value="<?php echo smarty_function_xl(array('t' => "Growth-Chart"), $this);?>
 (<?php echo smarty_function_xl(array('t' => 'HTML'), $this);?>
)" style='margin-left: 20px;'>
	<?php endif; ?>
	</td>
	</tr>

	<tr><td colspan='3' style='text-align:center'>&nbsp;</td></tr>
	<tr>
	<td colspan='3' style='text-align:center'>
        <input type="submit" class="editonly" name="Submit" value="<?php echo smarty_function_xl(array('t' => 'Save Form'), $this);?>
">
        <input type="button" class="editonly" id="cancel" value="<?php echo smarty_function_xl(array('t' => "Don't Save"), $this);?>
">
        </td>
</tr>
</table>
<br><br>
<input type="hidden" name="id" id='id' value="<?php echo $this->_tpl_vars['vitals']->get_id(); ?>
" />
<input type="hidden" name="activity" id='activity' value="<?php echo $this->_tpl_vars['vitals']->get_activity(); ?>
">
<input type="hidden" name="pid" id='pid' value="<?php echo $this->_tpl_vars['vitals']->get_pid(); ?>
">
<input type="hidden" name="process" id='process' value="true">
</form>
</body>

<script language="javascript">
var formdate = '<?php echo ((is_array($_tmp=$this->_tpl_vars['vitals']->get_date())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y%m%d") : smarty_modifier_date_format($_tmp, "%Y%m%d")); ?>
';
// vitals array elements are in the format:
//   date-height-weight-head_circumference
var vitals = new Array();
// get values from the current form elements
vitals[0] = formdate+'-<?php echo $this->_tpl_vars['vitals']->get_height(); ?>
-<?php echo $this->_tpl_vars['vitals']->get_weight(); ?>
-<?php echo $this->_tpl_vars['vitals']->get_head_circ(); ?>
';
// historic values
<?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
vitals[vitals.length] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['result']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y%m%d") : smarty_modifier_date_format($_tmp, "%Y%m%d")); ?>
-<?php echo $this->_tpl_vars['result']['height']; ?>
-<?php echo $this->_tpl_vars['result']['weight']; ?>
-<?php echo $this->_tpl_vars['result']['head_circ']; ?>
';
<?php endforeach; unset($_from); endif; ?>
var patientAge='<?php echo $this->_tpl_vars['patient_age']; ?>
';
var patient_dob='<?php echo $this->_tpl_vars['patient_dob']; ?>
';
var webroot = '<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
';
var pid = '<?php echo $this->_tpl_vars['vitals']->get_pid(); ?>
';
var cancellink = '<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
';
var nextlink = '<?php echo $this->_tpl_vars['NEXT_LINK']; ?>
';
var backlink = '<?php echo $this->_tpl_vars['BACK_LINK']; ?>
';
var visitlink = '<?php echo $this->_tpl_vars['VISIT_LINK']; ?>
';
var vitalslink = '<?php echo $this->_tpl_vars['VITALS_LINK']; ?>
';
var roslink = '<?php echo $this->_tpl_vars['ROS_LINK']; ?>
';
var lablink = '<?php echo $this->_tpl_vars['LAB_LINK']; ?>
';
var prescriptionlink = '<?php echo $this->_tpl_vars['prescription_LINK']; ?>
';
var planlink = '<?php echo $this->_tpl_vars['plan_LINK']; ?>
';
var referrallink = '<?php echo $this->_tpl_vars['referral_LINK']; ?>
';
var admissionlink = '<?php echo $this->_tpl_vars['admission_LINK']; ?>
';
var summarylink = '<?php echo $this->_tpl_vars['summary_LINK']; ?>
';
var birth_xl='<?php echo smarty_function_xl(array('t' => "Birth-24 months"), $this);?>
'
var older_xl='<?php echo smarty_function_xl(array('t' => "2-20 years"), $this);?>
';
<?php echo '
function addGCSelector()
{
    var options=new Array();
    var birth={\'display\':birth_xl,\'param\':\'birth\'};
    var age2={\'display\':older_xl,\'param\':\'2-20\'}
    if((patientAge.indexOf(\'24 month\')>=0) || (patientAge.indexOf(\'month\')==-1))
        {
            var dob_data=patient_dob.split("-");
            var dob_date=new Date(dob_data[0],parseInt(dob_data[1])-1,dob_data[2]);
            options[0]=age2;
            for(var idx=0;idx<vitals.length;idx++)
                {
                    var str_data_date=vitals[idx].split("-")[0];
                    var data_date=new Date(str_data_date.substr(0,4),parseInt(str_data_date.substr(4,2))-1,str_data_date.substr(6,2));
                    if(((data_date-dob_date)/86400000)<=2*365)
                        {
                            idx=vitals.length;
                            options[1]=birth
                        }
                }
        }
        else
        {
            options[0]=birth;
        }
        var chart_buttons_cell=$("#pdfchart").parent("td");
        var select=$("<select id=\'chart_type\'></select>");
        chart_buttons_cell.prepend(select);
        for(idx=0;idx<options.length;idx++)
            {
                var option=$("<option value=\'"+options[idx].param+"\'>"+options[idx].display+"</option>");
                select.append(option);
            }
        select.find("option:first").attr("selected","true");
        if(options.length<2)
            {
                select.css("display","none");
            }
}

$(document).ready(function(){

 $( "#bps_input" ).blur(function () {
var bp = $( "#bps_input" ).val();
if (bp > 130) {
$( "span" ).remove();
 $( "#bps_input" ).after("<span style=\'color:#cc0000;\'>&#8226; Above range </span>");
}
else if (bp < 120) {
$( "span" ).remove();
 $( "#bps_input" ).after("<span style=\'color:#cc0000;\'>&#8226; Below range </span>");
}
else {
$( "span" ).remove();
}
});
 $( "#bpd_input" ).blur(function () {
var bp = $( "#bpd_input" ).val();
if (bp > 80) {
$( "span" ).remove();
 $( "#bpd_input" ).after("<span style=\'color:#cc0000;\'>&#8226; Above range </span>");
}
else if (bp < 60) {
$( "span" ).remove();
 $( "#bpd_input" ).after("<span style=\'color:#cc0000;\'>&#8226; Below range </span>");
}
else {
$( "span" ).remove();
}
});
 $("dl").css(\'display\',\'none\');
    $("#growthchart").click(function() { ShowGrowthchart(); });
    $("#pdfchart").click(function() { ShowGrowthchart(1); });
    $("#htmlchart").click(function() { ShowGrowthchart(2); });
    $("#cancel").click(function() { location.href=cancellink; });
	$("#back").click(function() { location.href=backlink; });
	  $("#next").click(function() { location.href=nextlink; });
	  	$("#visit").click(function() { location.href=visitlink; });
	  $("#vitals").click(function() { location.href=vitalslink; });
	  	$("#ros").click(function() { location.href=roslink; });
	  $("#lab").click(function() { location.href=lablink; });
	  	$("#prescription").click(function() { location.href=prescriptionlink; });
	  $("#plan").click(function() { location.href=planlink; });
	  	$("#referral").click(function() { location.href=referrallink; });
	  $("#admission").click(function() { location.href=admissionlink; });
	  	$("#summary").click(function() { location.href=summarylink; });
	
    addGCSelector();
});

function ShowGrowthchart(doPDF) {
    // get values from the current form elements
    '; ?>

    vitals[0] = formdate+'-'+$("#height_input").val()+'-'+$("#weight_input").val()+'-'+$("#head_circ_input").val();
    <?php echo '
    // build the data string
    var datastring = "";
    for(var i=0; i<vitals.length; i++) {
        datastring += vitals[i]+"~";
    }
    newURL = webroot+\'/interface/forms/vitals/growthchart/chart.php?pid=\'+pid+\'&data=\'+datastring;
    if (doPDF == 1) newURL += "&pdf=1";
    if (doPDF == 2) newURL += "&html=1";
    newURL+="&chart_type="+$("#chart_type").val();
    // do the new window stuff
    top.restoreSession();
    window.open(newURL, \'_blank\', "menubar=1,toolbar=1,scrollbars=1,resizable=1,width=600,height=450");
}

function convLbtoKg(name) {
    var lb = $("#"+name).val();
    var hash_loc=lb.indexOf("#");
    if(hash_loc>=0)
    {
        var pounds=lb.substr(0,hash_loc);
        var ounces=lb.substr(hash_loc+1);
        var num=parseInt(pounds)+parseInt(ounces)/16;
        lb=num;
        $("#"+name).val(lb);
    }
    if (lb == "0") {
        $("#"+name+"_metric").val("0");
    }		
    else if (lb == parseFloat(lb)) {
	kg = lb*0.45359237;
        kg = kg.toFixed(2);
        $("#"+name+"_metric").val(kg);
    }
    else {
        $("#"+name+"_metric").val("");
    }

    if (name == "weight_input") {
        calculateBMI();
    }
} 

function convKgtoLb(name) {
    var kg = $("#"+name+"_metric").val();

    if (kg == "0") {
        $("#"+name).val("0");
    }    
    else if (kg == parseFloat(kg)) {
        lb = kg/0.45359237;
        lb = lb.toFixed(2);
        $("#"+name).val(lb);
    }
    else {
        $("#"+name).val("");
    }

    if (name == "weight_input") {
        calculateBMI();
    }
}

function convIntoCm(name) {
    var inch = $("#"+name).val();

    if (inch == "0") {
        $("#"+name+"_metric").val("0");
    }    
    else if (inch == parseFloat(inch)) {
        cm = inch*2.54;
        cm = cm.toFixed(2);
        $("#"+name+"_metric").val(cm);
    }
    else {
        $("#"+name+"_metric").val("");
    }

    if (name == "height_input") {
        calculateBMI();
    }
}

function convCmtoIn(name) {
    var cm = $("#"+name+"_metric").val();

    if (cm == "0") {
        $("#"+name).val("0");
    }    
    else if (cm == parseFloat(cm)) {
        inch = cm/2.54;
        inch = inch.toFixed(2);
        $("#"+name).val(inch);
    }
    else {
        $("#"+name).val("");
    }    

    if (name == "height_input") {
        calculateBMI();
    }
}

function convFtoC(name) {
    var Fdeg = $("#"+name).val();
    if (Fdeg == "0") {
        $("#"+name+"_metric").val("0");
    }
    else if (Fdeg == parseFloat(Fdeg)) {
        Cdeg = (Fdeg-32)*0.5556;
        Cdeg = Cdeg.toFixed(2);
        $("#"+name+"_metric").val(Cdeg);
    }
    else {
        $("#"+name+"_metric").val("");
    }
}

function convCtoF(name) {
    var Cdeg = $("#"+name+"_metric").val();
    if (Cdeg == "0") {
        $("#"+name).val("0");
    }
    else if (Cdeg == parseFloat(Cdeg)) {
        Fdeg = (Cdeg/0.5556)+32;
        Fdeg = Fdeg.toFixed(2);
        $("#"+name).val(Fdeg);
    }
    else {
        $("#"+name).val("");
    }
}

function calculateBMI() {
    var bmi = 0;
    var height = $("#height_input").val();
    var weight = $("#weight_input").val();
    if(height == 0 || weight == 0) {
        $("#BMI").val("");
    }
    else if((height == parseFloat(height)) && (weight == parseFloat(weight))) {
        bmi = weight/height/height*703;
        bmi = bmi.toFixed(1);
        $("#BMI_input").val(bmi);
    }
    else {
        $("#BMI_input").val("");
    }
}

Calendar.setup({inputField:"date", ifFormat:"%Y-%m-%d %H:%M", button:"img_date", showsTime:true});

</script>
'; ?>


</html>