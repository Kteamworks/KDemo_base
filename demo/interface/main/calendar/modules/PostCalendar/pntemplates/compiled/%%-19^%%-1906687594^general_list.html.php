<?php /* Smarty version 2.6.2, created on 2016-07-15 12:11:53
         compiled from C:/xampp/htdocs/greencity/templates/insurance_companies/general_list.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/greencity/templates/insurance_companies/general_list.html', 2, false),array('modifier', 'upper', 'C:/xampp/htdocs/greencity/templates/insurance_companies/general_list.html', 13, false),)), $this); ?>
<a href="controller.php?practice_settings&<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
insurance_company&action=edit" <?php  if (!$GLOBALS['concurrent_layout']) echo "target='Main'";  ?> onclick="top.restoreSession()" class="css_button" >
<span><?php echo smarty_function_xl(array('t' => 'Add a Company'), $this);?>
</span></a><br>
<br>
<table cellpadding="1" cellspacing="0" class="showborder">
	<tr class="showborder_head">
		<th width="140px"><b><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</b></th>
		<th width="300px"><b><?php echo smarty_function_xl(array('t' => 'City, State'), $this);?>
</b></th>
		<th><b><?php echo smarty_function_xl(array('t' => 'Default X12 Partner'), $this);?>
</b></th>
	</tr>
	<?php if (count($_from = (array)$this->_tpl_vars['icompanies'])):
    foreach ($_from as $this->_tpl_vars['insurancecompany']):
?>
	<tr height="22">
		<td><a href="<?php echo $this->_tpl_vars['CURRENT_ACTION']; ?>
action=edit&id=<?php echo $this->_tpl_vars['insurancecompany']->id; ?>
" onsubmit="return top.restoreSession()"><?php echo $this->_tpl_vars['insurancecompany']->name; ?>
&nbsp;</a></td>
		<td><?php echo $this->_tpl_vars['insurancecompany']->address->city; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['insurancecompany']->address->state)) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
&nbsp;</td>
		<td><?php echo $this->_tpl_vars['insurancecompany']->get_x12_default_partner_name(); ?>
&nbsp;</td>
	</tr>
	<?php endforeach; unset($_from); else: ?>
	<tr class="center_display">
		<td colspan="3"><?php echo smarty_function_xl(array('t' => 'No Insurance Companies Found'), $this);?>
</td>
	</tr>
	<?php endif; ?>
</table>