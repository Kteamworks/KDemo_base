<?php /* Smarty version 2.6.2, created on 2016-07-15 12:11:55
         compiled from C:/xampp/htdocs/greencity/templates/x12_partners/general_list.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/greencity/templates/x12_partners/general_list.html', 2, false),)), $this); ?>
<a href="<?php echo $this->_tpl_vars['CURRENT_ACTION']; ?>
action=edit&id=default" <?php  if (!$GLOBALS['concurrent_layout']) echo "target='Main'";  ?> onclick="top.restoreSession()" class="css_button" >
<span><?php echo smarty_function_xl(array('t' => 'Add New Partner'), $this);?>
</span></a><br><br>
<table cellpadding="1" cellspacing="0" class="showborder">
	<tr class="showborder_head">
		<th width="200px"><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</th>
		<th width="130px"><?php echo smarty_function_xl(array('t' => 'Sender ID'), $this);?>
</th>
		<th width="130px"><?php echo smarty_function_xl(array('t' => 'Receiver ID'), $this);?>
</th>
		<th><?php echo smarty_function_xl(array('t' => 'Version'), $this);?>
</th>
	</tr>
	<?php if (count($_from = (array)$this->_tpl_vars['partners'])):
    foreach ($_from as $this->_tpl_vars['partner']):
?>
	<tr height="22">
		<td><a href="<?php echo $this->_tpl_vars['CURRENT_ACTION']; ?>
action=edit&x12_partner_id=<?php echo $this->_tpl_vars['partner']->id; ?>
" onclick="top.restoreSession()"><?php echo $this->_tpl_vars['partner']->get_name(); ?>
&nbsp;</a></td>
		<td><?php echo $this->_tpl_vars['partner']->get_x12_sender_id(); ?>
&nbsp;</td>
		<td><?php echo $this->_tpl_vars['partner']->get_x12_receiver_id(); ?>
&nbsp;</td>
		<td><?php echo $this->_tpl_vars['partner']->get_x12_version(); ?>
&nbsp;</td>
	</tr>
	<?php endforeach; unset($_from); else: ?>
	<tr height="25" class="center_display">
		<td colspan="4"><?php echo smarty_function_xl(array('t' => 'No Partners Found'), $this);?>
</td>
	</tr>
	<?php endif; ?>
</table>