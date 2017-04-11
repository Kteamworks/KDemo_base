<?php /* Smarty version 2.6.2, created on 2016-08-08 11:06:21
         compiled from C:/xampp/htdocs/mangoo/templates/insurance_companies/general_edit.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/mangoo/templates/insurance_companies/general_edit.html', 29, false),array('function', 'html_options', 'C:/xampp/htdocs/mangoo/templates/insurance_companies/general_edit.html', 78, false),)), $this); ?>
<?php echo '
<script language="javascript">
function submit_insurancecompany() {
    if(document.insurancecompany.name.value.length>0) { 
        top.restoreSession();
	    document.insurancecompany.submit();
		//Z&H Removed redirection
    } else{
        document.insurancecompany.name.style.backgroundColor="red";
        document.insurancecompany.name.focus();
    }
}

function jsWaitForDelay(delay) {
     var startTime = new Date();
     var endTime = null;
     do {
        endTime = new Date();
     } while ((endTime - startTime) < delay);
}
</script>
'; ?>

<form name="insurancecompany" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
">
<!-- it is important that the hidden form_id field be listed first, when it is called it populates any old information attached with the id, this allows for partial edits
		if it were called last, the settings from the form would be overwritten with the old information-->
<input type="hidden" name="form_id" value="<?php echo $this->_tpl_vars['insurancecompany']->id; ?>
" />
<table style="font-size:9pt;" width="500px" CELLSPACING="0" CELLPADDING="3">
<tr>
	<td width="220px" VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</td>
	<td VALIGN="MIDDLE" >
		<input type="text" size="40" name="name" value="<?php echo $this->_tpl_vars['insurancecompany']->get_name(); ?>
" onKeyDown="PreventIt(event)" /> (<?php echo smarty_function_xl(array('t' => 'Required'), $this);?>
)
	</td>
</tr>
<tr>
	<td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Attn'), $this);?>
</td>
	<td VALIGN="MIDDLE" >
		<input type="text" size="40" name="attn" value="<?php echo $this->_tpl_vars['insurancecompany']->get_attn(); ?>
" onKeyDown="PreventIt(event)" />
	</td>
</tr>

<tr>
	<td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Address'), $this);?>
</td>
	<td VALIGN="MIDDLE" >
		<input type="text" size="40" name="address_line1" value="<?php echo $this->_tpl_vars['insurancecompany']->address->line1; ?>
" onKeyDown="PreventIt(event)" />
	</td>
</tr>
<tr>
	<td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Address'), $this);?>
</td>
	<td VALIGN="MIDDLE" >
		<input type="text" size="40" name="address_line2" value="<?php echo $this->_tpl_vars['insurancecompany']->address->line2; ?>
" onKeyDown="PreventIt(event)" />
	</td>
</tr>
<tr>
	<td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'City, State Zip'), $this);?>
</td>
	<td VALIGN="MIDDLE" >
		<input type="text" size="25" name="city" value="<?php echo $this->_tpl_vars['insurancecompany']->address->city; ?>
" onKeyDown="PreventIt(event)" /> , <input type="text" size="2" maxlength="2" name="state" value="<?php echo $this->_tpl_vars['insurancecompany']->address->state; ?>
" onKeyDown="PreventIt(event)" /> <input type="text" size="5" name="zip" value="<?php echo $this->_tpl_vars['insurancecompany']->address->zip; ?>
" onKeyDown="PreventIt(event)" />
	</td>
</tr>
<tr>
	<td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Phone'), $this);?>
</td>
	<td VALIGN="MIDDLE" >
		<input TYPE="TEXT" NAME="phone" SIZE="12" VALUE="<?php echo $this->_tpl_vars['insurancecompany']->get_phone(); ?>
" onKeyDown="PreventIt(event)" />
	</td>
</tr>
<tr>
	<td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'CMS ID'), $this);?>
</td>
	<td VALIGN="MIDDLE" >
		<input type="text" size="15" name="cms_id" value="<?php echo $this->_tpl_vars['insurancecompany']->get_cms_id(); ?>
" onKeyDown="PreventIt(event)" />
<?php  if ($GLOBALS['support_encounter_claims']) {  ?>
    &nbsp;&nbsp;<?php echo smarty_function_xl(array('t' => 'For Encounter Claims'), $this);?>
:
		<input type="text" size="15" name="alt_cms_id" value="<?php echo $this->_tpl_vars['insurancecompany']->get_alt_cms_id(); ?>
" onKeyDown="PreventIt(event)" />
<?php  }  ?>
	</td>
</tr>
<tr>
	<td VALIGN="MIDDLE"><?php echo smarty_function_xl(array('t' => 'Payer Type'), $this);?>
</td>
	<td VALIGN="MIDDLE">
		<?php echo smarty_function_html_options(array('name' => 'freeb_type','options' => $this->_tpl_vars['insurancecompany']->freeb_type_array,'selected' => $this->_tpl_vars['insurancecompany']->get_freeb_type()), $this);?>

	</td>
</tr>
<!--
This is now deprecated use the newer x12 partner code instead
<tr>
	<td COLSPAN="1" ALIGN="LEFT" VALIGN="MIDDLE">X12 Receiver ID</td>
	<td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE">
		<input type="text" name="x12_receiver_id" value="<?php echo $this->_tpl_vars['insurancecompany']->get_x12_receiver_id(); ?>
" >
	</td>
</tr>-->
<tr>
	<td VALIGN="MIDDLE"><?php echo smarty_function_xl(array('t' => 'Default X12 Partner'), $this);?>
</td>
	<td VALIGN="MIDDLE">
		<?php echo smarty_function_html_options(array('name' => 'x12_default_partner_id','options' => $this->_tpl_vars['x12_partners'],'selected' => $this->_tpl_vars['insurancecompany']->get_x12_default_partner_id()), $this);?>

	</td>
</tr>
<tr height="25"><td colspan=2>&nbsp;</td></tr>
<tr>
	<td colspan="2"><a href="javascript:submit_insurancecompany();" class="css_button"><span><?php echo smarty_function_xl(array('t' => 'Save'), $this);?>
</span></a><a href="controller.php?practice_settings&insurance_company&action=list"  <?php  if (!$GLOBALS['concurrent_layout']) echo "target='Main'";  ?> class="css_button" onclick="top.restoreSession()">
<span><?php echo smarty_function_xl(array('t' => 'Cancel'), $this);?>
</span></a></td>
</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['insurancecompany']->id; ?>
" />
<input type="hidden" name="process" value="<?php echo $this->_tpl_vars['PROCESS']; ?>
" />
</form>