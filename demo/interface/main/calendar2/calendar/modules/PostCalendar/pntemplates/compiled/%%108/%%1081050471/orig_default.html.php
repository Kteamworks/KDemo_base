<?php /* Smarty version 2.3.1, created on 2016-08-17 17:02:18
         compiled from default/views/month/orig_default.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'fetch', 'default/views/month/orig_default.html', 7, false),
array('function', 'eval', 'default/views/month/orig_default.html', 8, false),
array('function', 'assign', 'default/views/month/orig_default.html', 39, false),
array('function', 'pc_url', 'default/views/month/orig_default.html', 48, false),
array('function', 'pc_sort_events', 'default/views/month/orig_default.html', 67, false),
array('function', 'pc_popup', 'default/views/month/orig_default.html', 102, false),
array('modifier', 'pc_date_format', 'default/views/month/orig_default.html', 17, false),
array('modifier', 'date_format', 'default/views/month/orig_default.html', 52, false),
array('modifier', 'strip_tags', 'default/views/month/orig_default.html', 91, false),
array('modifier', 'truncate', 'default/views/month/orig_default.html', 98, false),)); ?>
<?php $this->_config_load("default.conf", null, 'local'); ?>

<?php $this->_config_load("lang.$USER_LANG", null, 'local'); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/header.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->_plugins['function']['fetch'][0](array('file' => "$TPL_STYLE_PATH/month.css",'assign' => "css"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php $this->_plugins['function']['eval'][0](array('var' => $this->_tpl_vars['css']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php if ($this->_tpl_vars['PRINT_VIEW'] != 1): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/global/navigation.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="monthheader">
	<tr align="center">
    	<td nowrap width="100%" align="center" valign="top" class="monthheader">
			<a href="<?php echo $this->_tpl_vars['PREV_MONTH_URL']; ?>
">&lt;&lt;</a>
    		<?php echo $this->_run_mod_handler('pc_date_format', true, $this->_tpl_vars['DATE'], $this->_config[0]['vars']['_PC_DATE_FORMAT_MY']); ?>

			<a href="<?php echo $this->_tpl_vars['NEXT_MONTH_URL']; ?>
">&gt;&gt;</a>
		</td>
		<?php if ($this->_tpl_vars['PRINT_VIEW'] != 1): ?>
		<td nowrap align="right" valign="top" class="monthheader">
		</td>
		<?php endif; ?>
	</tr>
</table>

<table width="100%" border="0" cellpadding="1" cellspacing="0" class="calcontainer"><tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <?php if (count((array)$this->_tpl_vars['S_LONG_DAY_NAMES'])):
    foreach ((array)$this->_tpl_vars['S_LONG_DAY_NAMES'] as $this->_tpl_vars['day']):
?>
        <td width="14%" align="center" valign="middle" class="daynames"><?php echo $this->_tpl_vars['day']; ?>
</td>
        <?php endforeach; endif; ?>
    </tr>
    
	<?php if (isset($this->_foreach["weeks"])) unset($this->_foreach["weeks"]);
$this->_foreach["weeks"]['name'] = "weeks";
$this->_foreach["weeks"]['total'] = count((array)$this->_tpl_vars['CAL_FORMAT']);
$this->_foreach["weeks"]['show'] = $this->_foreach["weeks"]['total'] > 0;
if ($this->_foreach["weeks"]['show']):
$this->_foreach["weeks"]['iteration'] = 0;
    foreach ((array)$this->_tpl_vars['CAL_FORMAT'] as $this->_tpl_vars['days']):
        $this->_foreach["weeks"]['iteration']++;
        $this->_foreach["weeks"]['first'] = ($this->_foreach["weeks"]['iteration'] == 1);
        $this->_foreach["weeks"]['last']  = ($this->_foreach["weeks"]['iteration'] == $this->_foreach["weeks"]['total']);
?>
	<tr>
		<?php if (isset($this->_foreach["days"])) unset($this->_foreach["days"]);
$this->_foreach["days"]['name'] = "days";
$this->_foreach["days"]['total'] = count((array)$this->_tpl_vars['days']);
$this->_foreach["days"]['show'] = $this->_foreach["days"]['total'] > 0;
if ($this->_foreach["days"]['show']):
$this->_foreach["days"]['iteration'] = 0;
    foreach ((array)$this->_tpl_vars['days'] as $this->_tpl_vars['date']):
        $this->_foreach["days"]['iteration']++;
        $this->_foreach["days"]['first'] = ($this->_foreach["days"]['iteration'] == 1);
        $this->_foreach["days"]['last']  = ($this->_foreach["days"]['iteration'] == $this->_foreach["days"]['total']);
?>
		<?php if ($this->_tpl_vars['date'] == $this->_tpl_vars['TODAY_DATE']): ?>
			<?php $this->_plugins['function']['assign'][0](array('var' => "stylesheet",'value' => "monthtoday"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
		<?php elseif (( $this->_tpl_vars['date'] < $this->_tpl_vars['MONTH_START_DATE'] || $this->_tpl_vars['date'] > $this->_tpl_vars['MONTH_END_DATE'] )): ?>
			<?php $this->_plugins['function']['assign'][0](array('var' => "stylesheet",'value' => "monthoff"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
		<?php else: ?>
			<?php $this->_plugins['function']['assign'][0](array('var' => "stylesheet",'value' => "monthon"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
		<?php endif; ?>
		<td height="75" align="left" valign="top" class="<?php echo $this->_tpl_vars['stylesheet']; ?>
"
		    onmouseover="this.style.backgroundColor='<?php echo $this->_config[0]['vars']['CellHighlight']; ?>
'"
			onmouseout="this.style.backgroundColor=''"
			onclick="javascript:location.href='<?php $this->_plugins['function']['pc_url'][0](array('action' => "day",'date' => $this->_tpl_vars['date']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>';">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left" valign="top"><a
						href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "day",'date' => $this->_tpl_vars['date']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>"><?php echo $this->_run_mod_handler('date_format', true, $this->_tpl_vars['date'], "%d"); ?>
</a>
					<?php if ($this->_foreach['days']['iteration'] == 1): ?>
					<a href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "week",'date' => $this->_tpl_vars['date']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>">[<?php echo $this->_config[0]['vars']['_PC_MV_WEEK']; ?>
]</a>
					<?php endif; ?>
					</td>
					<td align="right" valign="top">
						<?php if ($this->_tpl_vars['ACCESS_ADD'] == true): ?>
						<a href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "submit",'date' => $this->_tpl_vars['date']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>"><img
                 		src="<?php echo $this->_tpl_vars['TPL_IMAGE_PATH']; ?>
/new.gif" width="10" height="10" border="0" /></a>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" valign="top" align="left">
					
					<?php $this->_plugins['function']['pc_sort_events'][0](array('var' => "S_EVENTS",'sort' => "time",'order' => "asc",'value' => $this->_tpl_vars['A_EVENTS']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
					<?php $this->_plugins['function']['assign'][0](array('var' => "oldCat",'value' => ""), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
					<?php if (isset($this->_foreach["events"])) unset($this->_foreach["events"]);
$this->_foreach["events"]['name'] = "events";
$this->_foreach["events"]['total'] = count((array)$this->_tpl_vars['S_EVENTS'][$this->_tpl_vars['date']]);
$this->_foreach["events"]['show'] = $this->_foreach["events"]['total'] > 0;
if ($this->_foreach["events"]['show']):
$this->_foreach["events"]['iteration'] = 0;
    foreach ((array)$this->_tpl_vars['S_EVENTS'][$this->_tpl_vars['date']] as $this->_tpl_vars['event']):
        $this->_foreach["events"]['iteration']++;
        $this->_foreach["events"]['first'] = ($this->_foreach["events"]['iteration'] == 1);
        $this->_foreach["events"]['last']  = ($this->_foreach["events"]['iteration'] == $this->_foreach["events"]['total']);
?>
						<?php $this->_plugins['function']['assign'][0](array('var' => "cCat",'value' => $this->_tpl_vars['event']['catname']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
						<?php if ($this->_tpl_vars['oldCat'] != $this->_tpl_vars['cCat']): ?>
							<?php if ($this->_foreach['event']['first'] != true): ?>
								</div>
							<?php endif; ?>
							<div width="100%" style="padding: 1px; background-color: <?php echo $this->_tpl_vars['event']['catcolor']; ?>
;">
								<span style="font-size:10pt;"><?php echo $this->_tpl_vars['event']['catname']; ?>
</span>
							</div>
							<div width="100%" style="padding: 2px; border:solid 1px <?php echo $this->_tpl_vars['event']['catcolor']; ?>
;">
						<?php endif; ?>

						
						<?php if ($this->_tpl_vars['event']['alldayevent'] != true): ?>
							<?php if ($this->_tpl_vars['24HOUR_TIME']): ?>
								<?php $this->_plugins['function']['assign'][0](array('var' => "timestamp",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['event']['startTime'], $this->_config[0]['vars']['_PC_TIME_FORMAT_24'])), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
							<?php else: ?>
								<?php $this->_plugins['function']['assign'][0](array('var' => "timestamp",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['event']['startTime'], $this->_config[0]['vars']['_PC_TIME_FORMAT_12'])), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
							<?php endif; ?>
							
							<?php $this->_plugins['function']['assign'][0](array('var' => "title",'value' => $this->_run_mod_handler('strip_tags', true, $this->_tpl_vars['event']['title'])), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>

						<?php else: ?>
							<?php $this->_plugins['function']['assign'][0](array('var' => "timestamp",'value' => ""), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
							
							<?php $this->_plugins['function']['assign'][0](array('var' => "title",'value' => $this->_run_mod_handler('strip_tags', true, $this->_tpl_vars['event']['title'])), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
						<?php endif; ?>
						<?php $this->_plugins['function']['assign'][0](array('var' => "desc",'value' => $this->_run_mod_handler('truncate', true, $this->_tpl_vars['event']['hometext'], 255, "...")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
						
						<a style="font-size: 7pt; text-decoration: none;"
						   href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "detail",'eid' => $this->_tpl_vars['event']['eid'],'date' => $this->_tpl_vars['date']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>"
						<?php $this->_plugins['function']['pc_popup'][0](array('delay' => "500",'bgcolor' => "$event[catcolor]",'timeout' => "3600",'caption' => "$timestamp $event[title]",'text' => "$desc"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>>
                    	<?php echo $this->_tpl_vars['timestamp']; ?>
 <?php echo $this->_tpl_vars['title']; ?>
</a>
						<span style="font-size:7pt;text-decoration:none;">
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/user/patient_line.html", array('event' => $this->_tpl_vars['event'],'show_icons' => false,'show_provider' => true,'show_patient' => true));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span><br />
						<?php $this->_plugins['function']['assign'][0](array('var' => "oldCat",'value' => $this->_tpl_vars['event']['catname']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
						<?php if ($this->_foreach['event']['last']): ?>
							
							</div>
						<?php endif; ?>
					<?php endforeach; endif; ?>
					</td>
				</tr>
			</table>			
		</td>
		<?php endforeach; endif; ?>
	</tr>
	<?php endforeach; endif; ?>
</table>
</td></tr></table>
<?php if ($this->_tpl_vars['PRINT_VIEW'] != 1): ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="right">
            <a href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "month",'print' => "true"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>"><?php echo $this->_config[0]['vars']['_PC_THEME_PRINT']; ?>
</a>
        </td>
    </tr>
</table>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/global/footer.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/footer.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>