<?php /* Smarty version 2.6.2, created on 2015-03-31 12:01:00
         compiled from C:/xampp/htdocs/openemr/interface/clickmap/template/general_new.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:/xampp/htdocs/openemr/interface/clickmap/template/general_new.html', 25, false),)), $this); ?>
<html>
<head>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/jquery-ui-1.8.6.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/clickmap.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['form']->template_dir; ?>
/css/ui-lightness/jquery-ui-1.8.6.custom.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['form']->template_dir; ?>
/css/clickmap.css" />
</head>
<body>
<div class="outer-container">
	<div id="container" class="container">
		<img src="<?php echo $this->_tpl_vars['form']->image; ?>
"/>
	</div>
	<div id="legend" class="legend">
		<div class="body">
			<ul></ul>
		</div>
	</div>
</div>
<p style="clear:both">

<div class="nav">
	<button id="btn_save"><?php echo smarty_function_xl(array('t' => 'Save'), $this);?>
</button>
	<button id="btn_clear"><?php echo smarty_function_xl(array('t' => 'Clear'), $this);?>
</button>
	<button id="cancel"><?php echo smarty_function_xl(array('t' => 'Cancel'), $this);?>
</button>
	<p>
        <?php echo smarty_function_xl(array('t' => "Click a spot on the graphic to add a new annotation, click it again to remove it"), $this);?>
 <br/>
        <?php echo smarty_function_xl(array('t' => "The 'Clear' button will remove all annotations."), $this);?>

    </p>
</div>

<div class="dialog-form" style="display:none">
	<fieldset>
	<label for="label"><?php echo smarty_function_xl(array('t' => 'Label'), $this);?>
</label>
	<input type="text" name="label" id="label" class="text ui-widget-content ui-corner-all label" />
	<label for="options"></label>
	<select name="options">
	</select>
	<label for="detail"><?php echo smarty_function_xl(array('t' => 'Detail'), $this);?>
</label>
	<textarea name="detail" id="detail" class="textarea ui-widget-content ui-corner-all detail"></textarea>
	</fieldset>
</div>

<div class="marker-template" style="display:none">
	<span class='count'></span>
</div>

<script type="text/javascript">
var cancellink = '<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
';
<?php echo '
    $(document).ready( function() {
                $("#cancel").click(function() { location.href=cancellink; });
		var optionsLabel = ';  echo $this->_tpl_vars['form']->optionsLabel;  echo ';
		var options = ';  echo $this->_tpl_vars['form']->optionList;  echo ';
		var data = ';  echo $this->_tpl_vars['form']->data;  echo ';
		var hideNav = ';  echo $this->_tpl_vars['form']->hideNav;  echo ';

		clickmap( {
                        hideNav: hideNav,
                        data: data,
			dropdownOptions: { label: optionsLabel, options: options },
			container: $("#container")
		} );
	});
'; ?>

</script>

<form id="submitForm" name="submitForm" method="post" action="<?php echo $this->_tpl_vars['form']->saveAction; ?>
" onsubmit="return top.restoreSession()">
    <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['form']->get_id(); ?>
" />
    <input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['form']->get_pid(); ?>
" />
    <input type="hidden" name="process" value="true" />
    <input type="hidden" name="data" id="data" value=""/>
</form>
</body>

</html>