<?php /* Smarty version 2.3.1, created on 2017-09-19 07:25:32
         compiled from default/views/month/default.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'assign', 'default/views/month/default.html', 3, false),)); ?>

<?php $this->_plugins['function']['assign'][0](array('var' => "MULTIDAY",'value' => true), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>

<?php 
 $this->assign('cal_ui', $_SESSION['cal_ui']);
 ?>
<?php if ($this->_tpl_vars['cal_ui'] == 3): ?>
  <?php if ($this->_tpl_vars['PRINT_VIEW'] == 1): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/month_print/outlook_ajax_template.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php else: ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/month/ajax_template.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
<?php else: ?> 
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/month/orig_default.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>