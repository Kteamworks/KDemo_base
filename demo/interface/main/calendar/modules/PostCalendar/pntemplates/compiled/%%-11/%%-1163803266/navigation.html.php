<?php /* Smarty version 2.3.1, created on 2015-03-18 11:50:11
         compiled from default/views/global/navigation.html */ ?>

<?php 
 $this->assign('cal_ui', $_SESSION['cal_ui']);
 ?>
<?php if ($this->_tpl_vars['cal_ui'] == 2): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/global/fancy_navigation.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?> 
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/global/orig_navigation.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>