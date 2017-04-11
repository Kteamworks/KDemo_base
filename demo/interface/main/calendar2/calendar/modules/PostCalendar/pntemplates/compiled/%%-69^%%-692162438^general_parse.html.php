<?php /* Smarty version 2.6.2, created on 2015-03-25 17:05:53
         compiled from C:/xampp/htdocs/openemr/templates/hl7/general_parse.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/openemr/templates/hl7/general_parse.html', 16, false),)), $this); ?>
<html>
<head>
<?php html_header_show(); ?>

</head>
<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
">
<form name="prescribe" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
" onsubmit="return top.restoreSession()">
<table CELLSPACING="0" CELLPADDING="3" class="shownoborder">
<!--<tr><td>Example HL7 data<td></tr>
<tr><td>MSH|^~\&|ADT1|CUH|LABADT|CUH|198808181127|SECURITY|ADT^A01|MSG00001|P|2.3|
EVN|A01|198808181122||
PID|||PATID1234^5^M11||RYAN^HENRY^P||19610615|M||C|1200 N ELM STREET^^GREENSBORO^NC^27401-1020|GL|(919)379-1212|(919)271-3434 ||S||PATID12345001^2^M10|123456789|987654^NC|
NK1|JOHNSON^JOAN^K|WIFE||||||NK^NEXT OF KIN
PV1|1|I|2000^2053^01||||004777^FISHER^BEN^J.|||SUR||||ADM|A0|</td></tr>-->
<tr>
<td colspan="2"><b><?php echo smarty_function_xl(array('t' => 'Paste HL7 Data'), $this);?>
</b></td>
</tr>
<tr height="25">
	<td colspan="2" ><textarea rows="10" wrap="virtual" cols="70" name="hl7data"></textarea><br /><br />
	</td></tr>
	<tr class="text"><td colspan="2">
	<a href="javascript:document.forms[0].reset();" class="css_button"><span><?php echo smarty_function_xl(array('t' => 'Clear HL7 Data'), $this);?>
</span></a><a href="javascript:document.forms[0].submit();" class="css_button" ><span><?php echo smarty_function_xl(array('t' => 'Parse HL7'), $this);?>
</span></a>
	</td>

<?php if ($this->_tpl_vars['hl7_message_err']): ?>
	<tr height="25"><td colspan="2"><?php echo $this->_tpl_vars['hl7_message_err']; ?>
</td></tr>
<?php endif;  if ($this->_tpl_vars['hl7_array']): ?>
	<tr class="text"><td colspan="2">
	<table class="showborder">
	<?php if (count($_from = (array)$this->_tpl_vars['hl7_array'])):
    foreach ($_from as $this->_tpl_vars['hl7key'] => $this->_tpl_vars['hl7item']):
?>
	<tr height="25"><td colspan="3"><?php echo $this->_tpl_vars['hl7key']; ?>
</td></tr>
		<?php if (count($_from = (array)$this->_tpl_vars['hl7item'])):
    foreach ($_from as $this->_tpl_vars['segment_name'] => $this->_tpl_vars['segment_val']):
?>
			<tr><td>&nbsp;</td><td><?php echo $this->_tpl_vars['segment_name']; ?>
: </td><td><?php echo $this->_tpl_vars['segment_val']; ?>
</td></tr>
		<?php endforeach; unset($_from); endif; ?>
	<?php endforeach; unset($_from); endif; ?>
	</table>
	</td></tr>
<?php endif; ?>
<input type="hidden" name="process" value="<?php echo $this->_tpl_vars['PROCESS']; ?>
" />
</table>
</form>

</body>
</html>