<?php /* Smarty version 2.6.2, created on 2016-01-04 16:15:04
         compiled from C:/xampp/htdocs/kavaii/templates/document_categories/general_list.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/kavaii/templates/document_categories/general_list.html', 17, false),)), $this); ?>
<?php echo '
 <style type="text/css" title="mystyles" media="all">
<!--
.treeMenuDefault {
	font-style: italic;
}

.treeMenuBold {
	font-style: italic;
	font-weight: bold;
}

-->
</style>
'; ?>

<script type="text/javascript">
var deleteLabel="<?php echo smarty_function_xl(array('t' => 'Delete'), $this);?>
";
</script>
<script src="CategoryTreeMenu.js" language="JavaScript" type="text/javascript"></script>
<table>
	<tr>
		<td height="20" valign="top"><?php echo smarty_function_xl(array('t' => 'Document Categories'), $this);?>
</td>

	</tr>
	<tr>
		<td valign="top"><?php echo $this->_tpl_vars['tree_html']; ?>
</td>
		<?php if ($this->_tpl_vars['message']): ?>
		<td valign="top"><?php echo $this->_tpl_vars['message']; ?>
</td>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['add_node'] == true): ?>
		<td width="25"></td>
		<td valign="top">
		<?php echo smarty_function_xl(array('t' => "The new category will be a sub-category of "), $this);?>
 <?php echo $this->_tpl_vars['parent_name']; ?>
<br>
		<form method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
" onsubmit="return top.restoreSession()">
		<?php echo smarty_function_xl(array('t' => 'Category Name'), $this);?>
:&nbsp;<input type="text" name="name" onKeyDown="PreventIt(event)" >&nbsp;&nbsp;
		<input type="submit" name="Add Category" value="<?php echo smarty_function_xl(array('t' => 'Add Category'), $this);?>
">
		<input type="hidden" name="parent_is" value="<?php echo $this->_tpl_vars['parent_is']; ?>
">
		<input type="hidden" name="process" value="<?php echo $this->_tpl_vars['PROCESS']; ?>
" />
		</form>
		</td>
		<?php endif; ?>
	</tr>

</table>