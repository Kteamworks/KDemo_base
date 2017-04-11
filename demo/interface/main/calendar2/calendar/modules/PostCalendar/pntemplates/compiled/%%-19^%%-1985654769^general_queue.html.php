<?php /* Smarty version 2.6.2, created on 2016-01-04 16:15:00
         compiled from C:/xampp/htdocs/kavaii/templates/documents/general_queue.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/kavaii/templates/documents/general_queue.html', 14, false),array('modifier', 'escape', 'C:/xampp/htdocs/kavaii/templates/documents/general_queue.html', 38, false),)), $this); ?>
<?php echo '
<head>
<script language="javascript">
function submit_documents()
{
    top.restoreSession();
    document.queue.submit();
}
</script>
</head>
'; ?>


<a href="controller.php?practice_settings&<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
document_category&action=list" <?php  if (!$GLOBALS['concurrent_layout']) echo "target='Main'";  ?> onclick="top.restoreSession()" class="css_button" >
<span><?php echo smarty_function_xl(array('t' => 'Edit Categories'), $this);?>
</span></a><a href="#" onclick="submit_documents();" class="css_button" target="_self" ><span><?php echo smarty_function_xl(array('t' => 'Update files'), $this);?>
</span></a>
<input type="hidden" name="process" value="<?php echo $this->_tpl_vars['PROCESS']; ?>
" /><br><br>

<form name="queue" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
" onsubmit="return top.restoreSession()">
<table cellpadding="1" class="showborder">
	<tr class="center_display">
		<td colspan="6"><?php echo $this->_tpl_vars['messages']; ?>
</td>
	</tr>
	<tr class="showborder_head">
		<th colspan="2" width="110px"><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</td>
		<th width="100px"><?php echo smarty_function_xl(array('t' => 'Date'), $this);?>
</td>
		<th width="200px"><?php echo smarty_function_xl(array('t' => 'Patient'), $this);?>
</td>
		<th colspan="2"><?php echo smarty_function_xl(array('t' => 'Category'), $this);?>
</td>
	</tr>
	<?php if (isset($this->_foreach['queue_list'])) unset($this->_foreach['queue_list']);
$this->_foreach['queue_list']['name'] = 'queue_list';
$this->_foreach['queue_list']['total'] = count($_from = (array)$this->_tpl_vars['queue_files']);
$this->_foreach['queue_list']['show'] = $this->_foreach['queue_list']['total'] > 0;
if ($this->_foreach['queue_list']['show']):
$this->_foreach['queue_list']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['file']):
        $this->_foreach['queue_list']['iteration']++;
        $this->_foreach['queue_list']['first'] = ($this->_foreach['queue_list']['iteration'] == 1);
        $this->_foreach['queue_list']['last']  = ($this->_foreach['queue_list']['iteration'] == $this->_foreach['queue_list']['total']);
?>
	<tr>
		<td><input type="checkbox" name="files[<?php echo $this->_tpl_vars['file']['document_id']; ?>
][active]" value="1" <?php if (is_numeric ( $this->_tpl_vars['file']['patient_id'] )): ?>checked<?php endif; ?>></td>

		<td><a href="<?php echo $this->_tpl_vars['file']['web_path']; ?>
" onclick="top.restoreSession()"><?php echo $this->_tpl_vars['file']['filename']; ?>
</a><input type="hidden" name="files[<?php echo $this->_tpl_vars['file']['document_id']; ?>
][name]" value="<?php echo $this->_tpl_vars['file']['filename']; ?>
"></td>

		<td><?php echo $this->_tpl_vars['file']['mtime']; ?>
</td>

		<td><input type="text" name="files[<?php echo $this->_tpl_vars['file']['document_id']; ?>
][patient_id]" size="5" value="<?php echo $this->_tpl_vars['file']['patient_id']; ?>
"><input type="hidden" name="patient_name" value=""></td>

		<td><a href="javascript:<?php echo '{}'; ?>
" onclick="top.restoreSession();var URL='controller.php?patient_finder&find&form_id=queue<?php echo ((is_array($_tmp="['files[".($this->_tpl_vars['file']['document_id'])."][patient_id]']")) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&form_name=patient_name'; window.open(URL, 'queue', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=450,height=400,left = 425,top = 250');"><img src="images/stock_search-16.png" border="0"</a>&nbsp;&nbsp;&nbsp;</td>
		<td><select name="files[<?php echo $this->_tpl_vars['file']['document_id']; ?>
][category_id]"><?php echo $this->_tpl_vars['tree_html_listbox']; ?>
</select></td>

	</tr>
	<?php endforeach; unset($_from); else: ?>
	<tr height="25" class="center_display">
		<td colspan="6"><?php echo smarty_function_xl(array('t' => 'No Documents Found'), $this);?>
</td>
	</tr>
	<?php endif; ?>

</table><br><br>

</form>