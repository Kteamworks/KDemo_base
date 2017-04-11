<?php /* Smarty version 2.6.2, created on 2015-03-31 11:52:53
         compiled from C:%5Cxampp%5Chtdocs%5Copenemr%5Cinterface%5Cforms%5Cevaluation/templates/evaluation/general_new.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_checkboxes', 'C:\xampp\htdocs\openemr\interface\forms\evaluation/templates/evaluation/general_new.html', 204, false),)), $this); ?>
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

<?php echo '

<script language="javascript">



function calculate_bmi(button) {

	bmi = ( ( document.evaluation.wt.value  / ( document.evaluation.ht.value * document.evaluation.ht.value ) ) * 703);

	if (!isNaN(bmi)) {

		document.evaluation.bmi.value = Math.round(bmi*100)/100;

	}

}



</script>

'; ?>


<p><span class="title">Evaluation Examination</span></p>

<form name="evaluation" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/forms/evaluation/save.php"

 onsubmit="return top.restoreSession()">

<table>

<tr>

	<td>

		<span class=text>TEMP: </span><input size=3 type=entry name="temp" value="<?php echo $this->_tpl_vars['evaluation']->get_temp(); ?>
" ></input>

		<span class=text>P: </span><input size=3 type=entry name="p" value="<?php echo $this->_tpl_vars['evaluation']->get_p(); ?>
" ></input>

		<span class=text>R: </span><input size=3 type=entry name="r" value="<?php echo $this->_tpl_vars['evaluation']->get_r(); ?>
" ></input>

		<span class=text>BP: </span><input size=3 type=entry name="bp" value="<?php echo $this->_tpl_vars['evaluation']->get_bp(); ?>
" ></input>

		<span class=text>HT: </span><input size=3 type=entry name="ht" value="<?php echo $this->_tpl_vars['evaluation']->get_ht(); ?>
" ></input>

		<span class=text>WT: </span><input size=3 type=entry name="wt" value="<?php echo $this->_tpl_vars['evaluation']->get_wt(); ?>
" ></input>

		<span class=text>LMP: </span><input size=10 type=entry name="lmp" value="<?php echo $this->_tpl_vars['evaluation']->get_lmp(); ?>
" ></input>

	</td>

<tr>

	<td>

		<span class=text>BMI: </span><input size=5 type=entry name="bmi" value="<?php echo $this->_tpl_vars['evaluation']->get_bmi(); ?>
" ></input>

		<input type="button" onClick="calculate_bmi(this);" value="Calculate">

	</td>

</tr>

<tr>

	<td><br><br></td>

</tr>

<tr>

	<td>Chief Complaint</td>

</tr>

<tr>

	<td><p><textarea name="complaint" cols="75" rows="3" wrap="virtual" ><?php echo $this->_tpl_vars['evaluation']->get_complaint(); ?>
</textarea></p><br /></td>

</tr>

<tr>

	<td>HPI</td>

</tr>

<tr>

	<td><p><textarea name="hpi" cols="75" rows="3" wrap="virtual" ><?php echo $this->_tpl_vars['evaluation']->get_hpi(); ?>
</textarea></p><br /></td>

</tr>

</table>

<table>



<?php if (isset($this->_foreach['loops'])) unset($this->_foreach['loops']);
$this->_foreach['loops']['name'] = 'loops';
$this->_foreach['loops']['total'] = count($_from = (array)$this->_tpl_vars['checks']);
$this->_foreach['loops']['show'] = $this->_foreach['loops']['total'] > 0;
if ($this->_foreach['loops']['show']):
$this->_foreach['loops']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['title'] => $this->_tpl_vars['headings']):
        $this->_foreach['loops']['iteration']++;
        $this->_foreach['loops']['first'] = ($this->_foreach['loops']['iteration'] == 1);
        $this->_foreach['loops']['last']  = ($this->_foreach['loops']['iteration'] == $this->_foreach['loops']['total']);
?>

<tr>

	<td>

		<?php if ($this->_foreach['loops']['first'] != 1): ?><br><?php endif; ?>

		<?php echo $this->_tpl_vars['title']; ?>


	</td>

</tr>

<tr>

	<td>

	  <?php if (count($_from = (array)$this->_tpl_vars['headings'])):
    foreach ($_from as $this->_tpl_vars['row_title'] => $this->_tpl_vars['row']):
?>

			<tr>

				<?php if (! is_numeric ( $this->_tpl_vars['row_title'] )): ?>

					<td><?php echo $this->_tpl_vars['row_title']; ?>
</td>

					<td class="form_text"><?php echo smarty_function_html_checkboxes(array('name' => 'checks','options' => $this->_tpl_vars['row'],'selected' => $this->_tpl_vars['evaluation']->checks,'separator' => "&nbsp;</td><td class=\"form_text\">"), $this);?>
</td>

				<?php else: ?>

					<td width="20"></td>

					<td colspan="6"><table><tr><td class="form_text">

					

					<?php echo smarty_function_html_checkboxes(array('name' => 'checks','options' => $this->_tpl_vars['row'],'selected' => $this->_tpl_vars['evaluation']->checks,'separator' => "&nbsp;&nbsp;&nbsp;</td><td class=\"form_text\">"), $this);?>


					

					</td></tr></table></td>

					<?php if ($this->_tpl_vars['title'] == 'Eyes' && $this->_tpl_vars['row_title'] == 2): ?>

						</tr>

						<tr>

						<td></td>

						<td class="form_text">OD 20/<input type="text" name="eyes_od" size="4" value="<?php echo $this->_tpl_vars['evaluation']->get_eyes_od(); ?>
"></td>

						<td class="form_text">OS 20/<input type="text" name="eyes_os" size="4" value="<?php echo $this->_tpl_vars['evaluation']->get_eyes_os(); ?>
"></td>

						<td class="form_text">OU 20/<input type="text" name="eyes_ou" size="4" value="<?php echo $this->_tpl_vars['evaluation']->get_eyes_ou(); ?>
"></td>

					<?php endif; ?>

				<?php endif; ?>

				

			</tr>

	  <?php endforeach; unset($_from); endif; ?>

	  

	</td>

</tr>

<?php endforeach; unset($_from); endif; ?>

</table>

<br><br>

<table>

<tr>

	<td>Comments/Abnormal Findings</td>

</tr>

<tr>

	<td><textarea name="comments" wrap="virtual" cols="75" rows="8"><?php echo $this->_tpl_vars['evaluation']->get_comments(); ?>
</textarea></td>

</tr>

<tr>

	<td>Assesment/Plan</td>

</tr>

<tr>

	<td><textarea name="assesment" wrap="virtual" cols="75" rows="8"><?php echo $this->_tpl_vars['evaluation']->get_assesment(); ?>
</textarea></td>

</tr>

<?php if ($this->_tpl_vars['VIEW'] != true): ?>

<tr>

	<td><input type="submit" name="Submit" value="Save Form">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <a href="<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
" class="link" onclick="top.restoreSession()">[Don't Save]</a></td>

</tr>

<?php endif; ?>

</table>

<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['evaluation']->get_id(); ?>
" />

<input type="hidden" name="activity" value="<?php echo $this->_tpl_vars['evaluation']->get_activity(); ?>
">

<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['evaluation']->get_pid(); ?>
">

<input type="hidden" name="process" value="true">

</form>

</body>

</html>