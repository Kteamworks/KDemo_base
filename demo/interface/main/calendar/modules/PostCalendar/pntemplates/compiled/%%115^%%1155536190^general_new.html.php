<?php /* Smarty version 2.6.2, created on 2016-09-01 14:41:32
         compiled from C:%5Cxampp%5Chtdocs%5Cmaggo%5Cinterface%5Cforms%5Cros/templates/ros/general_new.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:\xampp\htdocs\maggo\interface\forms\ros/templates/ros/general_new.html', 77, false),array('function', 'html_radios', 'C:\xampp\htdocs\maggo\interface\forms\ros/templates/ros/general_new.html', 84, false),)), $this); ?>
<html>
<head>
<?php html_header_show();  echo '
 <style type="text/css" title="mystyles" media="all">
<!--
ttd {
	font-size:12pt;
	font-family:helvetica;
}
li{
	font-size:11pt;
	font-family:helvetica;
	margin-left: 15px;
}
a {
	font-size:11pt;
	font-family:helvetica;
}

.main_title{
	font-family: sans-serif;
	font-size: 14pt;
	font-weight: bold;
	text-decoration: none;
	color: #000000;
}
.section_title{
	font-family: sans-serif;
	font-size: 12pt;
	font-weight: bold;
	text-decoration: none;
	color: #000000;
}

.response_title {
	font-family: sans-serif;
	font-size: 10pt;
	font-weight: bold;
	font-style: italic;
	color: #000000;
}

.response_prompt{
	text-align: right;
	font-family: sans-serif;
	font-size: 9pt;
	text-decoration: none;
	color: #000000;
}

.response{
	border-width:1px;
	border-style:solid;
	border-color:black;
	text-align: center;
	font-family: sans-serif;
	font-size: 9pt;
	font-weight: lighter;
	text-decoration: none;
	color: #000000;
}

.responsetd{
	border-width:1px;
	border-style:solid;
	border-color:black;
}
-->
</style>
'; ?>

</head>
<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
">
<form name="ros" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/forms/ros/save.php"
 onsubmit="return top.restoreSession()">
<table><tr><td colspan='10'>
<p><span class="main_title"><?php echo smarty_function_xl(array('t' => 'Review Of Systems'), $this);?>
</span></p>
</td></tr>

<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Constitutional'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Weight Change'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'weight_change','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_weight_change(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Weakness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'weakness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_weakness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Fatigue'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'fatigue','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_fatigue(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Anorexia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'anorexia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_anorexia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Fever'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'fever','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_fever(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Chills'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'chills','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_chills(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Night Sweats'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'night_sweats','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_night_sweats(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Insomnia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'insomnia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_insomnia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Irritability'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'irritability','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_irritability(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Heat or Cold'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'heat_or_cold','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_heat_or_cold(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Intolerance'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'intolerance','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_intolerance(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Eyes'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Change in Vision'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'change_in_vision','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_weight_change(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Family History of Glaucoma'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'glaucoma_history','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_glaucoma_history(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Eye Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'eye_pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_eye_pain(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Irritation'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'irritation','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_irritation(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Redness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'redness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_redness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Excessive Tearing'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'excessive_tearing','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_excessive_tearing(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Double Vision'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'double_vision','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_double_vision(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Blind Spots'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'blind_spots','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_blind_spots(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Photophobia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'photophobia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_photophobia(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Ears'), $this);?>
, <?php echo smarty_function_xl(array('t' => 'Nose'), $this);?>
, <?php echo smarty_function_xl(array('t' => 'Mouth'), $this);?>
, <?php echo smarty_function_xl(array('t' => 'Throat'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hearing Loss'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hearing_loss','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hearing_loss(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Discharge'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'discharge','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_discharge(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_pain(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Vertigo'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'vertigo','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_vertigo(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Tinnitus'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'tinnitus','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_tinnitus(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Frequent Colds'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'frequent_colds','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_frequent_colds(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Sore Throat'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'sore_throat','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_sore_throat(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Sinus Problems'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'sinus_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_sinus_problems(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Post Nasal Drip'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'post_nasal_drip','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_post_nasal_drip(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Nosebleed'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'nosebleed','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_nosebleed(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Snoring'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'snoring','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_snoring(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Apnea'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'apnea','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_apnea(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Breast'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Breast Mass'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'breast_mass','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_breast_mass(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Discharge'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'breast_discharge','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_breast_discharge(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Biopsy'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'biopsy','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_biopsy(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Abnormal Mammogram'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'abnormal_mammogram','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_abnormal_mammogram(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Respiratory'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Cough'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'cough','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_cough(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Sputum'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'sputum','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_sputum(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Shortness of Breath'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'shortness_of_breath','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_shortness_of_breath(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Wheezing'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'wheezing','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_wheezing(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hemoptysis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hemoptsyis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hemoptsyis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Asthma'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'asthma','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_asthma(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'COPD'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'copd','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_copd(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Cardiovascular'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Chest Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'chest_pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_chest_pain(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Palpitation'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'palpitation','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_palpitation(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Syncope'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'syncope','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_syncope(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'PND'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'pnd','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_pnd(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'DOE'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'doe','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_doe(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Orthopnea'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'orthopnea','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_orthopnea(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Peripheral'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'peripheal','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_peripheal(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Edema'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'edema','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_edema(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => "Leg Pain/Cramping"), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'legpain_cramping','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_legpain_cramping(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'History of Heart Murmur'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'history_murmur','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_history_murmur(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Arrythmia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'arrythmia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_arrythmia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Heart Problem'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'heart_problem','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_heart_problem(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Gastrointestinal'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Dysphagia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'dysphagia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_dysphagia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Heartburn'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'heartburn','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_heartburn(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Bloating'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'bloating','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_bloating(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Belching'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'belching','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_belching(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Flatulence'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'flatulence','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_flatulence(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Nausea'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'nausea','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_nausea(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Vomiting'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'vomiting','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_vomiting(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hematemesis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hematemesis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hematemesis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'gastro_pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_gastro_pain(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Food Intolerance'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'food_intolerance','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_food_intolerance(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => "H/O Hepatitis"), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hepatitis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hepatitis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Jaundice'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'jaundice','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_jaundice(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hematochezia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hematochezia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hematochezia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Changed Bowel'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'changed_bowel','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_changed_bowel(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Diarrhea'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'diarrhea','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_diarrhea(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Constipation'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'constipation','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_constipation(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Genitourinary'), $this);?>
 <?php echo smarty_function_xl(array('t' => 'General'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Polyuria'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'polyuria','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_polyuria(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Polydypsia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'polydypsia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_polydypsia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Dysuria'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'dysuria','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_dysuria(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hematuria'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hematuria','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hematuria(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Frequency'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'frequency','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_frequency(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Urgency'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'urgency','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_urgency(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Incontinence'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'incontinence','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_incontinence(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Renal Stones'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'renal_stones','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_renal_stones(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'UTIs'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'utis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_utis(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Genitourinary'), $this);?>
 <?php echo smarty_function_xl(array('t' => 'Male'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hesitancy'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hesitancy','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hesitancy(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Dribbling'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'dribbling','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_dribbling(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Stream'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'stream','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_stream(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Nocturia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'nocturia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_nocturia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Erections'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'erections','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_erections(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Ejaculations'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'ejaculations','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_ejaculations(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Genitourinary'), $this);?>
 <?php echo smarty_function_xl(array('t' => 'Female'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Female G'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'g','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_g(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Female P'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'p','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_p(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Female AP'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'ap','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_ap(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Female LC'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'lc','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_lc(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Menarche'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'mearche','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_mearche(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Menopause'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'menopause','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_menopause(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'LMP'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'lmp','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_lmp(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Frequency'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'f_frequency','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_f_frequency(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Flow'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'f_flow','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_f_flow(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Symptoms'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'f_symptoms','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_f_symptoms(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Abnormal Hair Growth'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'abnormal_hair_growth','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_abnormal_hair_growth(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => "F/H Female Hirsutism/Striae"), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'f_hirsutism','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_f_hirsutism(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	</td>
</tr></table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Musculoskeletal'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Chronic Joint Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'joint_pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_joint_pain(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Swelling'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'swelling','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_swelling(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Redness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'm_redness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_m_redness(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Warm'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'm_warm','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_m_warm(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Stiffness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'm_stiffness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_m_stiffness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Muscle'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'muscle','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_muscle(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Aches'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'm_aches','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_m_aches(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'FMS'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'fms','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_fms(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Arthritis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'arthritis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_arthritis(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Neurologic'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'LOC'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'loc','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_loc(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Seizures'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'seizures','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_seizures(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Stroke'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'stroke','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_stroke(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'TIA'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'tia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_tia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Numbness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'n_numbness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_n_numbness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Weakness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'n_weakness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_n_weakness(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Paralysis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'paralysis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_paralysis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Intellectual Decline'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'intellectual_decline','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_intellectual_decline(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Memory Problems'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'memory_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_memory_problems(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Dementia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'dementia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_dementia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Headache'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'n_headache','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_n_headache(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Skin'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Cancer'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 's_cancer','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_s_cancer(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Psoriasis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'psoriasis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_psoriasis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Acne'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 's_acne','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_s_acne(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Other'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 's_other','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_s_other(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Disease'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 's_disease','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_s_disease(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Psychiatric'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Psychiatric Diagnosis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'p_diagnosis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_p_diagnosis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Psychiatric Medication'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'p_medication','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_p_medication(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Depression'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'depression','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_depression(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Anxiety'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'anxiety','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_anxiety(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Social Difficulties'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'social_difficulties','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_social_difficulties(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Endocrine'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Thyroid Problems'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'thyroid_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_thyroid_problems(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Diabetes'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'diabetes','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_diabetes(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Abnormal Blood Test'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'abnormal_blood','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_abnormal_blood(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Hematologic'), $this);?>
/<?php echo smarty_function_xl(array('t' => 'Allergic'), $this);?>
/<?php echo smarty_function_xl(array('t' => 'Immunologic'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Anemia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'anemia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_anemia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => "F/H Blood Problems"), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'fh_blood_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_fh_blood_problems(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Bleeding Problems'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'bleeding_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_bleeding_problems(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Allergies'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'allergies','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_allergies(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Frequent Illness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'frequent_illness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_frequent_illness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'HIV'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hiv','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hiv(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'HAI Status'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hai_status','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hai_status(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>

<tr>
<td>
	<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['form']->get_id(); ?>
" />
	<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['form']->get_pid(); ?>
" />
	<input type="hidden" name="process" value="true" />
</td>
</tr>
<tr>
<td>
	<input type="submit" name="Submit" value=<?php  xl('Save Form','e','"','"');  ?>
</td>
<td>
	<a href="<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
" class="link" onclick="top.restoreSession()">[<?php  xl("Don't Save","e");  ?>]</a>
</td>
</tr>
</table>
</body>
</html>