<?php /* Smarty version 2.6.2, created on 2016-08-05 15:43:33
         compiled from C:%5Cxampp%5Chtdocs%5Cgreencity%5Cinterface%5Cforms%5Cnursing_notes/templates/general_new.html */ ?>
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
<p><span class="title">Nursing Notes</span></p>
<form name="soap" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/forms/nursing_notes/save.php"
 onsubmit="return top.restoreSession()">
<table>
	<tr>
		<td align="left">Initial Assessment</td>
		<td width="90%">
			<textarea name="assessment" cols="60" rows="8"><?php echo $this->_tpl_vars['data']->get_assessment(); ?>
</textarea>
		</td>
	</tr>

	<tr>
		<td align="left">Procedures Done</td>
		<td width="90%">
			<textarea name="procedures" cols="60" rows="8"><?php echo $this->_tpl_vars['data']->get_procedures(); ?>
</textarea>
		</td>
	</tr>

	<tr>
		<td align="left">Discharge Teaching</td>
		<td width="90%">
			<textarea name="discharge" cols="60" rows="8"><?php echo $this->_tpl_vars['data']->get_discharge(); ?>
</textarea>
		</td>
	</tr>
	
	<tr>
		<td>
			<input type="submit" name="Submit" value="Save Form">
		</td>
		<td>
			<a href="<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
" class="link">[Don't Save]</a>
		</td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['data']->get_id(); ?>
" />
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['data']->get_pid(); ?>
">
<input type="hidden" name="process" value="true">
</form>
</body>
</html>