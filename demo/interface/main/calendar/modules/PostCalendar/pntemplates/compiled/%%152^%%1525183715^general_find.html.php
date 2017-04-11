<?php /* Smarty version 2.6.2, created on 2015-04-09 12:47:44
         compiled from C:/xampp/htdocs/kavaii/templates/patient_finder/general_find.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/kavaii/templates/patient_finder/general_find.html', 37, false),)), $this); ?>
<html>
<head>
<?php html_header_show(); ?>

<?php echo '
 <style type="text/css" title="mystyles" media="all">
<!--
td {
	font-size:12pt;
	font-family:helvetica;
}
.small {
	font-size:9pt;
	font-family:helvetica;
	text-decoration: none;
}
.small:hover {
	text-decoration: underline;
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
-->
</style>
'; ?>

</head>
<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
">
<form name="patientfinder" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
" onsubmit="return top.restoreSession()">
<table>
	<tr>
		<td><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</td>
		<td>
			<input type="text" size="40" name="searchstring" value=""/>
		</td>
	</tr>
	<tr>
		<td>
			<input type="submit" value="<?php echo smarty_function_xl(array('t' => 'search'), $this);?>
"/>
		</td>
	</tr>
</table>
<input type="hidden" name="process" value="<?php echo $this->_tpl_vars['PROCESS']; ?>
" />
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['hidden_ispid']; ?>
" />
</form>
<table>
<?php if (count ( $this->_tpl_vars['result_set'] ) > 0): ?>
	<tr>
		<td><?php echo smarty_function_xl(array('t' => 'Results Found For Search'), $this);?>
 '<?php echo $this->_tpl_vars['search_string']; ?>
'</td>
	</tr>
	<tr>
		<td><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'DOB'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'Patient ID'), $this);?>
</td>
<?php endif;  if (isset($this->_foreach['search_results'])) unset($this->_foreach['search_results']);
$this->_foreach['search_results']['name'] = 'search_results';
$this->_foreach['search_results']['total'] = count($_from = (array)$this->_tpl_vars['result_set']);
$this->_foreach['search_results']['show'] = $this->_foreach['search_results']['total'] > 0;
if ($this->_foreach['search_results']['show']):
$this->_foreach['search_results']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['result']):
        $this->_foreach['search_results']['iteration']++;
        $this->_foreach['search_results']['first'] = ($this->_foreach['search_results']['iteration'] == 1);
        $this->_foreach['search_results']['last']  = ($this->_foreach['search_results']['iteration'] == $this->_foreach['search_results']['total']);
?>
	<tr>
		<td>
			<a href="javascript:<?php echo '{}'; ?>
" onclick="window.opener.document.<?php echo $this->_tpl_vars['form_id']; ?>
.value='<?php if ($this->_tpl_vars['ispub'] == true):  echo $this->_tpl_vars['result']['pubpid'];  else:  echo $this->_tpl_vars['result']['pid'];  endif; ?>'; window.opener.document.<?php echo $this->_tpl_vars['form_name']; ?>
.value='<?php echo $this->_tpl_vars['result']['name']; ?>
'; window.close();"><?php echo $this->_tpl_vars['result']['name']; ?>
</a>
		</td>
		<td><?php echo $this->_tpl_vars['result']['DOB']; ?>
</td>
		<td><?php echo $this->_tpl_vars['result']['pubpid']; ?>
</td>
	</tr>
<?php endforeach; unset($_from); else: ?>
	<?php if (is_array ( $this->_tpl_vars['result_set'] )): ?>
	<tr>
		<td><?php echo smarty_function_xl(array('t' => 'No Results Found For Search'), $this);?>
 '<?php echo $this->_tpl_vars['search_string']; ?>
'</td>
	</tr>
	<?php endif;  endif; ?>
	</table>
  </body>
</html>
  