<?php /* Smarty version 2.6.2, created on 2015-03-31 11:53:09
         compiled from C:%5Cxampp%5Chtdocs%5Copenemr%5Cinterface%5Cforms%5Cprior_auth/templates/prior_auth/general_new.html */ ?>
<html>
<head>
<?php html_header_show();  echo '
 <style type="text/css" title="mystyles" media="all">
<!--
td {
	font-size:12pt;
	font-family:helvetica;
}
li{
	font-size:11pt;
	font-family:helvetica;
	margin-left: 15px;
}
a {
	font-size:11pt;
	font-family:helvetica;
}
.title {
	font-family: sans-serif;
	font-size: 12pt;
	font-weight: bold;
	text-decoration: none;
	color: #000000;
}

.form_text{
	font-family: sans-serif;
	font-size: 9pt;
	text-decoration: none;
	color: #000000;
}

-->
</style>
'; ?>

</head>
<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
">
<p><span class="title">Prior Authorization Form</span></p>
<form name="prior_auth" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/forms/prior_auth/save.php">
<table>
<tr>
	<td>Prior Authorization Number</td><td><input type="text" size="35" name="prior_auth_number" value="<?php echo $this->_tpl_vars['prior_auth']->get_prior_auth_number(); ?>
"></td>
</tr>
<tr>
	<td><br><br>Comments</td>
</tr>
<tr>
	<td colspan="2"><textarea name="comments" value="<?php echo $this->_tpl_vars['prior_auth']->get_comments(); ?>
" wrap="virtual" cols="75" rows="8"><?php echo $this->_tpl_vars['prior_auth']->get_comments(); ?>
</textarea></td>
</tr>
<?php if ($this->_tpl_vars['VIEW'] != true): ?>
<tr>
	<td><br><br><input type="submit" name="Submit" value="Save Form">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
" class="link">[Don't Save]</a></td>
</tr>
<?php endif; ?>
</table>
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['prior_auth']->get_id(); ?>
" />
<input type="hidden" name="activity" value="<?php echo $this->_tpl_vars['prior_auth']->get_activity(); ?>
">
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['prior_auth']->get_pid(); ?>
">
<input type="hidden" name="process" value="true">
</form>
</body>
</html>