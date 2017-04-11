<?php /* Smarty version 2.6.2, created on 2015-02-25 10:56:21
         compiled from C:/xampp/htdocs/openemr/templates/x12_partners/general_edit.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/openemr/templates/x12_partners/general_edit.html', 31, false),array('function', 'html_options', 'C:/xampp/htdocs/openemr/templates/x12_partners/general_edit.html', 58, false),)), $this); ?>
<?php echo '
<script language="javascript">
function add_x12()
{
if (document.x12_partner.name.value.length>0)
{
top.restoreSession();
document.x12_partner.submit();
}
else
{
document.x12_partner.name.style.backgroundColor="red";
document.x12_partner.name.focus();
}
}

 function Waittoredirect(delaymsec) {
 var st = new Date();
 var et = null;
 do {
 et = new Date();
 } while ((et - st) < delaymsec);

   }
</script>
'; ?>


<form name="x12_partner" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
">
<table width="400px" style="font-size:9pt;" CELLSPACING="0" CELLPADDING="3" border="0">
    <tr>
        <td colspan="2"><?php echo smarty_function_xl(array('t' => 'X12 Partner'), $this);?>
</td>
    </tr>
    <tr>
        <td ALIGN="LEFT" VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Partner'), $this);?>
&nbsp;<?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</td>
        <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" ><input type="text" size="20" name="name" value="<?php echo $this->_tpl_vars['partner']->get_name(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td ALIGN="LEFT" VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'ID'), $this);?>
&nbsp;<?php echo smarty_function_xl(array('t' => 'Number'), $this);?>
&nbsp;(<?php echo smarty_function_xl(array('t' => 'ETIN'), $this);?>
)</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="id_number" value="<?php echo $this->_tpl_vars['partner']->get_id_number(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'User logon Required Indicator (ISA01~ use 00 or 03)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="2" name="x12_isa01" value="<?php echo $this->_tpl_vars['partner']->get_x12_isa01(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'User Logon (If 03 above, else leave spaces) (ISA02)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_isa02" value="<?php echo $this->_tpl_vars['partner']->get_x12_isa02(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'User password required Indicator (ISA03~ use 00 or 01)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="2" name="x12_isa03" value="<?php echo $this->_tpl_vars['partner']->get_x12_isa03(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'User Password (ISA04~ if 01 above, else leave spaces)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_isa04" value="<?php echo $this->_tpl_vars['partner']->get_x12_isa04(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Sender ID Qualifier (ISA05)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><?php echo smarty_function_html_options(array('name' => 'x12_isa05','options' => $this->_tpl_vars['partner']->get_idqual_array(),'selected' => $this->_tpl_vars['partner']->get_x12_isa05()), $this);?>
</td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Sender ID (ISA06)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_sender_id" value="<?php echo $this->_tpl_vars['partner']->get_x12_sender_id(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Receiver ID Qualifier (ISA07)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><?php echo smarty_function_html_options(array('name' => 'x12_isa07','options' => $this->_tpl_vars['partner']->get_idqual_array(),'selected' => $this->_tpl_vars['partner']->get_x12_isa07()), $this);?>
</td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Receiver ID (ISA08)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_receiver_id" value="<?php echo $this->_tpl_vars['partner']->get_x12_receiver_id(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Acknowledgment Requested (ISA14)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><?php echo smarty_function_html_options(array('name' => 'x12_isa14','options' => $this->_tpl_vars['partner']->get_x12_isa14_array(),'selected' => $this->_tpl_vars['partner']->get_x12_isa14()), $this);?>
</td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Usage Indicator (ISA15)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><?php echo smarty_function_html_options(array('name' => 'x12_isa15','options' => $this->_tpl_vars['partner']->get_x12_isa15_array(),'selected' => $this->_tpl_vars['partner']->get_x12_isa15()), $this);?>
</td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Application Sender Code (GS02)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_gs02" value="<?php echo $this->_tpl_vars['partner']->get_x12_gs02(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Submitter EDI Access Number (PER06)'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_per06" value="<?php echo $this->_tpl_vars['partner']->get_x12_per06(); ?>
" onKeyDown="PreventIt(event)" /></td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Version'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><?php echo smarty_function_html_options(array('name' => 'x12_version','options' => $this->_tpl_vars['partner']->get_x12_version_array(),'selected' => $this->_tpl_vars['partner']->get_x12_version()), $this);?>
</td>
    </tr>
    <tr>
        <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Processing'), $this);?>
&nbsp;<?php echo smarty_function_xl(array('t' => 'Format'), $this);?>
</td>
        <td COLSPAN="2" VALIGN="MIDDLE" ><?php echo smarty_function_html_options(array('name' => 'processing_format','options' => $this->_tpl_vars['partner']->get_processing_format_array(),'selected' => $this->_tpl_vars['partner']->get_processing_format()), $this);?>
</td>
    </tr>
    <tr>
	<td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Application Receiver Code (GS03 - If blank ISA08 will be used)'), $this);?>
</td>
	<td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_gs03" value="<?php echo $this->_tpl_vars['partner']->get_x12_gs03(); ?>
" onKeyDown="PreventIt(event)" maxlength="15"/></td>
    </tr>
<tr height="25"><td colspan="2">&nbsp;</td></tr>
<tr>
	<td colspan="2">
		<?php if ($this->_tpl_vars['partner']->get_id() == ""): ?>
			<a class="css_button" href="javascript:add_x12();"><span><?php echo smarty_function_xl(array('t' => 'Save'), $this);?>
</span></a>

		<?php else: ?>
			<a class="css_button" href="javascript:add_x12();"><span><?php echo smarty_function_xl(array('t' => 'Save'), $this);?>
</span></a>
		<?php endif; ?>
	<a href="controller.php?practice_settings&x12_partner&action=list"  <?php  if (!$GLOBALS['concurrent_layout']) echo "target='Main'";  ?> class="css_button" onclick="top.restoreSession()">
<span><?php echo smarty_function_xl(array('t' => 'Cancel'), $this);?>
</span></a></td>
</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['partner']->id; ?>
" />
<input type="hidden" name="process" value="<?php echo $this->_tpl_vars['PROCESS']; ?>
" />
<input type="hidden" name="sub" value="no" />
</form>