<?php /* Smarty version 2.3.1, created on 2015-07-14 16:59:06
         compiled from default/user/search.html */ ?>

<?php 
 $this->assign('cal_ui', $_SESSION['cal_ui']);
 ?>
    <?php if ($this->_tpl_vars['PRINT_VIEW'] == 1): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/user/ajax_search_print.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php else: ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/user/ajax_search.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>