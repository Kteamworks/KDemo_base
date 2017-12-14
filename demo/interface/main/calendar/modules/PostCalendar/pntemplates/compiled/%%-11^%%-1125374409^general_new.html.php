<?php /* Smarty version 2.6.2, created on 2017-12-12 14:03:22
         compiled from C:%5Cxampp%5Chtdocs%5CKDemo_base%5Cdemo%5Cinterface%5Cforms%5Cros/templates/ros/general_new.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:\xampp\htdocs\KDemo_base\demo\interface\forms\ros/templates/ros/general_new.html', 640, false),array('function', 'html_radios', 'C:\xampp\htdocs\KDemo_base\demo\interface\forms\ros/templates/ros/general_new.html', 649, false),)), $this); ?>
<html>
<head>
<?php html_header_show(); ?>
<?php echo '
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href=\'http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700\' rel=\'stylesheet\' type=\'text/css\'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{$FORM_ACTION}/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="{$FORM_ACTION}/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<script src="{$FORM_ACTION}/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.slim.min.js"></script>
 <style type="text/css" title="mystyles" media="all">

 .button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
}
.button4 {background-color: #e7e7e7; color: black;    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;}
 /* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/

.cd-breadcrumb.triangle em, 
dl, dt, dd, ol, ul, li {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section, main {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: \'\';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}
<!--
ttd {
	font-size:12pt;
	font-family:helvetica;
}
li{

	font-family:helvetica;
	margin-left: 15px;
}
a {
	font-size:11pt;
	font-family:helvetica;
}

.main_title{
	font-family: sans-serif;
	font-size: 14pt;
	font-weight: bold;
	text-decoration: none;
	color: #000000;
}
.section_title{
	font-family: sans-serif;
	font-size: 12pt;
	font-weight: bold;
	text-decoration: none;
	color: #000000;
}

.response_title {
	font-family: sans-serif;
	font-size: 10pt;
	font-weight: bold;
	font-style: italic;
	color: #000000;
}

.response_prompt{
	text-align: right;
	font-family: sans-serif;
	font-size: 9pt;
	text-decoration: none;
	color: #000000;
}

.response{
	border-width:1px;
	border-style:solid;
	border-color:black;
	text-align: center;
	font-family: sans-serif;
	font-size: 9pt;
	font-weight: lighter;
	text-decoration: none;
	color: #000000;
}

.responsetd{
	border-width:1px;
	border-style:solid;
	border-color:black;
}
-->
/* -------------------------------- 

Primary style

-------------------------------- */
*, *::after, *::before {
  box-sizing: border-box;
}

html {
  font-size: 62.5%;
}


a {
  color: #96c03d;
  text-decoration: none;
}

section {
  /* used just to separate different styles */
  border-bottom: 1px solid #e6e6e6;
}

section h2 {
  width: 90%;
  margin: 0 auto 2em;
  color: #2c3f4c;
  font-size: 2rem;
  font-weight: 700;
  text-align: center;
}
@media only screen and (min-width: 1170px) {
  section {
    padding: 6em 0;
  }
  section h2 {
    margin: 0 auto 3em;
  }
}

/* -------------------------------- 

Basic Style

-------------------------------- */
.cd-breadcrumb, .cd-multi-steps {
  padding: 0.5em 1em;
  margin: 1em auto;
  background-color: #edeff0;
  border-radius: .25em;
}
.cd-breadcrumb:after, .cd-multi-steps:after {
  content: "";
  display: table;
  clear: both;
}
.cd-breadcrumb li, .cd-multi-steps li {
  display: inline-block;
  float: left;
  margin: 0.5em 0;
}
.cd-breadcrumb li::after, .cd-multi-steps li::after {
  /* this is the separator between items */
  display: inline-block;
  content: \'\\00bb\';
  margin: 0 .6em;
  color: #959fa5;
}
.cd-breadcrumb li:last-of-type::after, .cd-multi-steps li:last-of-type::after {
  /* hide separator after the last item */
  display: none;
}
.cd-breadcrumb li > *, .cd-multi-steps li > * {
  /* single step */
  display: inline-block;
  font-size: 1.4rem;
  color: #2c3f4c;
}
.cd-breadcrumb li.current > *, .cd-multi-steps li.current > * {
  /* selected step */
  color: #96c03d;
}
.no-touch .cd-breadcrumb a:hover, .no-touch .cd-multi-steps a:hover {
  /* steps already visited */
  color: #96c03d;
}
.cd-breadcrumb.custom-separator li::after, .cd-multi-steps.custom-separator li::after {
  /* replace the default arrow separator with a custom icon */
  content: \'\';
  height: 16px;
  width: 16px;
  background: url(../img/cd-custom-separator.svg) no-repeat center center;
  vertical-align: middle;
}
.cd-breadcrumb.custom-icons li > *::before, .cd-multi-steps.custom-icons li > *::before {
  /* add a custom icon before each item */
  content: \'\';
  display: inline-block;
  height: 20px;
 /* width: 20px;
  margin-right: .4em; */
  margin-top: -2px;
/*  background: url(../img/cd-custom-icons-01.svg) no-repeat 0 0; */
  vertical-align: middle;
}
.cd-breadcrumb.custom-icons li:not(.current):nth-of-type(2) > *::before, .cd-multi-steps.custom-icons li:not(.current):nth-of-type(2) > *::before {
  /* change custom icon using image sprites */
  background-position: -20px 0;
}
.cd-breadcrumb.custom-icons li:not(.current):nth-of-type(3) > *::before, .cd-multi-steps.custom-icons li:not(.current):nth-of-type(3) > *::before {
  background-position: -40px 0;
}
.cd-breadcrumb.custom-icons li:not(.current):nth-of-type(4) > *::before, .cd-multi-steps.custom-icons li:not(.current):nth-of-type(4) > *::before {
  background-position: -60px 0;
}
.cd-breadcrumb.custom-icons li.current:first-of-type > *::before, .cd-multi-steps.custom-icons li.current:first-of-type > *::before {
  /* change custom icon for the current item */
  background-position: 0 -20px;
}
.cd-breadcrumb.custom-icons li.current:nth-of-type(2) > *::before, .cd-multi-steps.custom-icons li.current:nth-of-type(2) > *::before {
  background-position: -20px -20px;
}
.cd-breadcrumb.custom-icons li.current:nth-of-type(3) > *::before, .cd-multi-steps.custom-icons li.current:nth-of-type(3) > *::before {
  background-position: -40px -20px;
}
.cd-breadcrumb.custom-icons li.current:nth-of-type(4) > *::before, .cd-multi-steps.custom-icons li.current:nth-of-type(4) > *::before {
  background-position: -60px -20px;
}
@media only screen and (min-width: 768px) {
  .cd-breadcrumb, .cd-multi-steps {
    padding: 0 1.2em;
  }
  .cd-breadcrumb li, .cd-multi-steps li {
    margin: 1.2em 0;
  }
  .cd-breadcrumb li::after, .cd-multi-steps li::after {
    margin: 0 1em;
  }
  .cd-breadcrumb li > *, .cd-multi-steps li > * {
    font-size: 1.3rem;
  }
    .cd-breadcrumb li > em, .cd-multi-steps li > em {
    font-size: 1.3rem !important;
  }
}

/* -------------------------------- 

Triangle breadcrumb

-------------------------------- */
@media only screen and (min-width: 768px) {
  .cd-breadcrumb.triangle {
    /* reset basic style */
    background-color: transparent;
    padding: 0;
  }
  .cd-breadcrumb.triangle li {
    position: relative;
    padding: 0;
    margin: 4px 4px 4px 0;
	  font-size: 1.6rem;
  }
  .cd-breadcrumb.triangle li:last-of-type {
    margin-right: 0;
  }

  .cd-breadcrumb.triangle li > * {
    position: relative;
    padding: 1.5em .6em 1em 1.5em;
    color: #2c3f4c;
    background-color: #edeff0;
    /* the border color is used to style its ::after pseudo-element */
    border-color: #edeff0;
  }
  .cd-breadcrumb.triangle li.current > * {
    /* selected step */
    color: #ffffff;
    background-color: #96c03d;
    border-color: #96c03d;
  }
  .cd-breadcrumb.triangle li:first-of-type > * {
    padding-left: 1.6em;
    border-radius: .25em 0 0 .25em;
  }
  .cd-breadcrumb.triangle li:last-of-type > * {
    padding-right: 1.6em;
    border-radius: 0 .25em .25em 0;
  }
  .cd-breadcrumb.triangle a:hover {
    /* steps already visited */
    color: #ffffff;
    background-color: #2c3f4c;
    border-color: #2c3f4c;
  }
  .cd-breadcrumb.triangle li::after, .cd-breadcrumb.triangle li > *::after {
    /* 
    	li > *::after is the colored triangle after each item
    	li::after is the white separator between two items
    */
    content: \'\';
    position: absolute;
    top: 0;
    left: 100%;
    content: \'\';
    height: 0;
    width: 0;
    /* 48px is the height of the <a> element */
    border: 24px solid transparent;
    border-right-width: 0;
    border-left-width: 20px;
  }
  .cd-breadcrumb.triangle li::after {
    /* this is the white separator between two items */
    z-index: 1;
    -webkit-transform: translateX(4px);
    -moz-transform: translateX(4px);
    -ms-transform: translateX(4px);
    -o-transform: translateX(4px);
    transform: translateX(4px);
    border-left-color: #ffffff;
    /* reset style */
    margin: 0;
  }
  .cd-breadcrumb.triangle li > *::after {
    /* this is the colored triangle after each element */
    z-index: 2;
    border-left-color: inherit;
  }
  .cd-breadcrumb.triangle li:last-of-type::after, .cd-breadcrumb.triangle li:last-of-type > *::after {
    /* hide the triangle after the last step */
    display: none;
  }
  .cd-breadcrumb.triangle.custom-separator li::after {
    /* reset style */
    background-image: none;
  }
  .cd-breadcrumb.triangle.custom-icons li::after, .cd-breadcrumb.triangle.custom-icons li > *::after {
    /* 50px is the height of the <a> element */
    border-top-width: 25px;
    border-bottom-width: 25px;
  }

  @-moz-document url-prefix() {
    .cd-breadcrumb.triangle li::after,
    .cd-breadcrumb.triangle li > *::after {
      /* fix a bug on Firefix - tooth edge on css triangle */
      border-left-style: dashed;
    }
  }
}
/* -------------------------------- 

Custom icons hover effects - breadcrumb and multi-steps

-------------------------------- */
@media only screen and (min-width: 768px) {
  .no-touch .cd-breadcrumb.triangle.custom-icons li:first-of-type a:hover::before, .cd-breadcrumb.triangle.custom-icons li.current:first-of-type em::before, .no-touch .cd-multi-steps.text-center.custom-icons li:first-of-type a:hover::before, .cd-multi-steps.text-center.custom-icons li.current:first-of-type em::before {
    /* change custom icon using image sprites - hover effect or current item */
    background-position: 0 -40px;
  }
  .no-touch .cd-breadcrumb.triangle.custom-icons li:nth-of-type(2) a:hover::before, .cd-breadcrumb.triangle.custom-icons li.current:nth-of-type(2) em::before, .no-touch .cd-multi-steps.text-center.custom-icons li:nth-of-type(2) a:hover::before, .cd-multi-steps.text-center.custom-icons li.current:nth-of-type(2) em::before {
    background-position: -20px -40px;
  }
  .no-touch .cd-breadcrumb.triangle.custom-icons li:nth-of-type(3) a:hover::before, .cd-breadcrumb.triangle.custom-icons li.current:nth-of-type(3) em::before, .no-touch .cd-multi-steps.text-center.custom-icons li:nth-of-type(3) a:hover::before, .cd-multi-steps.text-center.custom-icons li.current:nth-of-type(3) em::before {
    background-position: -40px -40px;
  }
  .no-touch .cd-breadcrumb.triangle.custom-icons li:nth-of-type(4) a:hover::before, .cd-breadcrumb.triangle.custom-icons li.current:nth-of-type(4) em::before, .no-touch .cd-multi-steps.text-center.custom-icons li:nth-of-type(4) a:hover::before, .cd-multi-steps.text-center.custom-icons li.current:nth-of-type(4) em::before {
    background-position: -60px -40px;
  }
}
/* -------------------------------- 

Multi steps indicator 

-------------------------------- */
@media only screen and (min-width: 768px) {
  .cd-multi-steps {
    /* reset style */
    background-color: transparent;
    padding: 0;
    text-align: center;
  }

  .cd-multi-steps li {
    position: relative;
    float: none;
    margin: 0.4em 40px 0.4em 0;
  }
  .cd-multi-steps li:last-of-type {
    margin-right: 0;
  }
  .cd-multi-steps li::after {
    /* this is the line connecting 2 adjacent items */
    position: absolute;
    content: \'\';
    height: 4px;
    background: #edeff0;
    /* reset style */
    margin: 0;
  }
  .cd-multi-steps li.visited::after {
    background-color: #96c03d;
  }
  .cd-multi-steps li > *, .cd-multi-steps li.current > * {
    position: relative;
    color: #2c3f4c;
  }

  .cd-multi-steps.custom-separator li::after {
    /* reset style */
    height: 4px;
    background: #edeff0;
  }

  .cd-multi-steps.text-center li::after {
    width: 100%;
    top: 50%;
    left: 100%;
    -webkit-transform: translateY(-50%) translateX(-1px);
    -moz-transform: translateY(-50%) translateX(-1px);
    -ms-transform: translateY(-50%) translateX(-1px);
    -o-transform: translateY(-50%) translateX(-1px);
    transform: translateY(-50%) translateX(-1px);
  }
  .cd-multi-steps.text-center li > * {
    z-index: 1;
    padding: .6em 1em;
    border-radius: .25em;
    background-color: #edeff0;
  }
  .no-touch .cd-multi-steps.text-center a:hover {
    background-color: #2c3f4c;
  }
  .cd-multi-steps.text-center li.current > *, .cd-multi-steps.text-center li.visited > * {
    color: #ffffff;
    background-color: #96c03d;
  }
  .cd-multi-steps.text-center.custom-icons li.visited a::before {
    /* change the custom icon for the visited item - check icon */
    background-position: 0 -60px;
  }

  .cd-multi-steps.text-top li, .cd-multi-steps.text-bottom li {
    width: 80px;
    text-align: center;
  }
  .cd-multi-steps.text-top li::after, .cd-multi-steps.text-bottom li::after {
    /* this is the line connecting 2 adjacent items */
    position: absolute;
    left: 50%;
    /* 40px is the <li> right margin value */
    width: calc(100% + 40px);
  }
  .cd-multi-steps.text-top li > *::before, .cd-multi-steps.text-bottom li > *::before {
    /* this is the spot indicator */
    content: \'\';
    position: absolute;
    z-index: 1;
    left: 50%;
    right: auto;
    -webkit-transform: translateX(-50%);
    -moz-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    -o-transform: translateX(-50%);
    transform: translateX(-50%);
    height: 12px;
    width: 12px;
    border-radius: 50%;
    background-color: #edeff0;
  }
  .cd-multi-steps.text-top li.visited > *::before,
  .cd-multi-steps.text-top li.current > *::before, .cd-multi-steps.text-bottom li.visited > *::before,
  .cd-multi-steps.text-bottom li.current > *::before {
    background-color: #96c03d;
  }
  .no-touch .cd-multi-steps.text-top a:hover, .no-touch .cd-multi-steps.text-bottom a:hover {
    color: #96c03d;
  }
  .no-touch .cd-multi-steps.text-top a:hover::before, .no-touch .cd-multi-steps.text-bottom a:hover::before {
    box-shadow: 0 0 0 3px rgba(150, 192, 61, 0.3);
  }

  .cd-multi-steps.text-top li::after {
    /* this is the line connecting 2 adjacent items */
    bottom: 4px;
  }
  .cd-multi-steps.text-top li > * {
    padding-bottom: 20px;
  }
  .cd-multi-steps.text-top li > *::before {
    /* this is the spot indicator */
    bottom: 0;
  }

  .cd-multi-steps.text-bottom li::after {
    /* this is the line connecting 2 adjacent items */
    top: 3px;
  }
  .cd-multi-steps.text-bottom li > * {
    padding-top: 20px;
  }
  .cd-multi-steps.text-bottom li > *::before {
    /* this is the spot indicator */
    top: 0;
  }
}
/* -------------------------------- 

Add a counter to the multi-steps indicator 

-------------------------------- */
.cd-multi-steps.count li {
  counter-increment: steps;
}

.cd-multi-steps.count li > *::before {
  content: counter(steps) " - ";
}

@media only screen and (min-width: 768px) {
  .cd-multi-steps.text-top.count li > *::before,
  .cd-multi-steps.text-bottom.count li > *::before {
    /* this is the spot indicator */
    content: counter(steps);
    height: 26px;
    width: 26px;
    line-height: 26px;
    font-size: 1.4rem;
    color: #ffffff;
  }

  .cd-multi-steps.text-top.count li:not(.current) em::before,
  .cd-multi-steps.text-bottom.count li:not(.current) em::before {
    /* steps not visited yet - counter color */
    color: #2c3f4c;
  }

  .cd-multi-steps.text-top.count li::after {
    bottom: 11px;
  }

  .cd-multi-steps.text-top.count li > * {
    padding-bottom: 34px;
  }

  .cd-multi-steps.text-bottom.count li::after {
    top: 11px;
  }

  .cd-multi-steps.text-bottom.count li > * {
    padding-top: 34px;
  }
}
label + input[type="radio"]:checked {
  background: #000;
}
</style>
	<script language="javascript">
$(document).ready(function(){
 $("dl").css(\'display\',\'none\');
 $("input[type=\'radio\'][value=\'YES\']:checked").parent(\'label\').css(\'background-color\', \'pink\');
 });

				    function login() {
				$.ajax({
				type: \'POST\',
                url: \'nurse_checkout.php\',
    
                data: \'hello\',
        
                success: function(response) {
alert(\'success\');
		
				}
        });

  
    }
	</script>
'; ?>

</head>
<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
" style="background-color:white"	>
<section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons" >
		<li id="ros" style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
"><a href="<?php echo $this->_tpl_vars['ROS_LINK']; ?>
"><i class="fa fa-note" style="margin-right: 8px;"></i>Medical Issues</a></li>
			
			<li id="vitals" style="<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
"><a href="<?php echo $this->_tpl_vars['VITALS_LINK']; ?>
"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<li class="current" style="<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
"></i><em>Review of systems</em></li>
			<li id="visit" style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a href="<?php echo $this->_tpl_vars['VISIT_LINK']; ?>
"><i class="fa fa-note" style="margin-right: 8px;"></i>Visit Notes</a></li>
			<li id="lab"  style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a href="<?php echo $this->_tpl_vars['LAB_LINK']; ?>
"><i class="fa fa-note" style="margin-right: 8px;"></i>Lab Tests</a></li>
			<li id="prescription"  style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a href="<?php echo $this->_tpl_vars['prescription_LINK']; ?>
"><i class="fa fa-note" style="margin-right: 8px;"></i>Prescription</a></li>
			<li id="plan"  style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a href="<?php echo $this->_tpl_vars['plan_LINK']; ?>
"><i class="fa fa-note" style="margin-right: 8px;"></i>Plan</a></li>
			<li id="referral"  style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a href="<?php echo $this->_tpl_vars['referral_LINK']; ?>
"><i class="fa fa-note" style="margin-right: 8px;"></i>Referral</a></li>
			<li id="admission"  style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a href="<?php echo $this->_tpl_vars['admission_LINK']; ?>
"><i class="fa fa-note" style="margin-right: 8px;"></i>Admission</a></li>
			<li id="summary"  style="<?php echo $this->_tpl_vars['DISPLAYNONE']; ?>
;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;"><a href="<?php echo $this->_tpl_vars['summary_LINK']; ?>
">Summary</a></li>
		<li>
			<a href="<?php echo $this->_tpl_vars['redirect_LINK']; ?>
"  style="background-color: #dd4b39 !important;
color: #fff;<?php echo $this->_tpl_vars['DISPLAYNONE1']; ?>
;">
check out</a></li>
		</ol>
	</nav>
</section>
<form name="ros" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/forms/ros/save.php"
 onsubmit="return top.restoreSession()">
<table><tr><td colspan='10'>
<p><span class="main_title"><?php echo smarty_function_xl(array('t' => 'Review Of Systems'), $this);?>
</span></p>
</td>

</tr>

<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Constitutional'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Weight Change'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'weight_change','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_weight_change(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Weakness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'weakness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_weakness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Fatigue'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'fatigue','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_fatigue(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Anorexia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'anorexia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_anorexia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Fever'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'fever','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_fever(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Chills'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'chills','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_chills(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Night Sweats'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'night_sweats','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_night_sweats(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Insomnia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'insomnia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_insomnia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Irritability'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'irritability','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_irritability(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Heat or Cold'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'heat_or_cold','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_heat_or_cold(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Intolerance'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'intolerance','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_intolerance(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Eyes'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Change in Vision'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'change_in_vision','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_weight_change(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Family History of Glaucoma'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'glaucoma_history','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_glaucoma_history(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Eye Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'eye_pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_eye_pain(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Irritation'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'irritation','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_irritation(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Redness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'redness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_redness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Excessive Tearing'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'excessive_tearing','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_excessive_tearing(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Double Vision'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'double_vision','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_double_vision(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Blind Spots'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'blind_spots','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_blind_spots(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Photophobia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'photophobia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_photophobia(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Ears'), $this);?>
, <?php echo smarty_function_xl(array('t' => 'Nose'), $this);?>
, <?php echo smarty_function_xl(array('t' => 'Mouth'), $this);?>
, <?php echo smarty_function_xl(array('t' => 'Throat'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hearing Loss'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hearing_loss','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hearing_loss(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Discharge'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'discharge','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_discharge(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_pain(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Vertigo'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'vertigo','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_vertigo(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Tinnitus'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'tinnitus','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_tinnitus(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Frequent Colds'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'frequent_colds','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_frequent_colds(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Sore Throat'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'sore_throat','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_sore_throat(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Sinus Problems'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'sinus_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_sinus_problems(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Post Nasal Drip'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'post_nasal_drip','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_post_nasal_drip(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Nosebleed'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'nosebleed','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_nosebleed(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Snoring'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'snoring','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_snoring(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Apnea'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'apnea','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_apnea(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Breast'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Breast Mass'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'breast_mass','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_breast_mass(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Discharge'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'breast_discharge','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_breast_discharge(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Biopsy'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'biopsy','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_biopsy(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Abnormal Mammogram'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'abnormal_mammogram','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_abnormal_mammogram(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Respiratory'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Cough'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'cough','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_cough(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Sputum'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'sputum','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_sputum(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Shortness of Breath'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'shortness_of_breath','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_shortness_of_breath(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Wheezing'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'wheezing','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_wheezing(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hemoptysis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hemoptsyis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hemoptsyis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Asthma'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'asthma','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_asthma(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'COPD'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'copd','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_copd(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Cardiovascular'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Chest Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'chest_pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_chest_pain(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Palpitation'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'palpitation','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_palpitation(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Syncope'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'syncope','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_syncope(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'PND'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'pnd','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_pnd(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'DOE'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'doe','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_doe(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Orthopnea'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'orthopnea','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_orthopnea(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Peripheral'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'peripheal','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_peripheal(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Edema'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'edema','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_edema(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => "Leg Pain/Cramping"), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'legpain_cramping','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_legpain_cramping(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'History of Heart Murmur'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'history_murmur','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_history_murmur(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Arrythmia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'arrythmia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_arrythmia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Heart Problem'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'heart_problem','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_heart_problem(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Gastrointestinal'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Dysphagia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'dysphagia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_dysphagia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Heartburn'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'heartburn','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_heartburn(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Bloating'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'bloating','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_bloating(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Belching'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'belching','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_belching(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Flatulence'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'flatulence','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_flatulence(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Nausea'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'nausea','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_nausea(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Vomiting'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'vomiting','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_vomiting(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hematemesis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hematemesis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hematemesis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'gastro_pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_gastro_pain(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Food Intolerance'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'food_intolerance','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_food_intolerance(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => "H/O Hepatitis"), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hepatitis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hepatitis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Jaundice'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'jaundice','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_jaundice(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hematochezia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hematochezia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hematochezia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Changed Bowel'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'changed_bowel','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_changed_bowel(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Diarrhea'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'diarrhea','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_diarrhea(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Constipation'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'constipation','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_constipation(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Genitourinary'), $this);?>
 <?php echo smarty_function_xl(array('t' => 'General'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Polyuria'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'polyuria','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_polyuria(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Polydypsia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'polydypsia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_polydypsia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Dysuria'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'dysuria','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_dysuria(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hematuria'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hematuria','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hematuria(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Frequency'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'frequency','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_frequency(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Urgency'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'urgency','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_urgency(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Incontinence'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'incontinence','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_incontinence(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Renal Stones'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'renal_stones','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_renal_stones(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'UTIs'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'utis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_utis(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Genitourinary'), $this);?>
 <?php echo smarty_function_xl(array('t' => 'Male'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Hesitancy'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hesitancy','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hesitancy(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Dribbling'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'dribbling','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_dribbling(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Stream'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'stream','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_stream(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Nocturia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'nocturia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_nocturia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Erections'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'erections','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_erections(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Ejaculations'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'ejaculations','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_ejaculations(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Genitourinary'), $this);?>
 <?php echo smarty_function_xl(array('t' => 'Female'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Female G'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'g','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_g(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Female P'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'p','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_p(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Female AP'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'ap','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_ap(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Female LC'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'lc','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_lc(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Menarche'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'mearche','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_mearche(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Menopause'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'menopause','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_menopause(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'LMP'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'lmp','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_lmp(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Frequency'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'f_frequency','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_f_frequency(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Flow'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'f_flow','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_f_flow(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Symptoms'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'f_symptoms','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_f_symptoms(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Abnormal Hair Growth'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'abnormal_hair_growth','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_abnormal_hair_growth(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => "F/H Female Hirsutism/Striae"), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'f_hirsutism','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_f_hirsutism(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>
	</td>
</tr></table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Musculoskeletal'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Chronic Joint Pain'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'joint_pain','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_joint_pain(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Swelling'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'swelling','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_swelling(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Redness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'm_redness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_m_redness(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Warm'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'm_warm','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_m_warm(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Stiffness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'm_stiffness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_m_stiffness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Muscle'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'muscle','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_muscle(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Aches'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'm_aches','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_m_aches(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'FMS'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'fms','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_fms(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Arthritis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'arthritis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_arthritis(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Neurologic'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'LOC'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'loc','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_loc(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Seizures'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'seizures','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_seizures(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Stroke'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'stroke','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_stroke(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'TIA'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'tia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_tia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Numbness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'n_numbness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_n_numbness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Weakness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'n_weakness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_n_weakness(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Paralysis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'paralysis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_paralysis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Intellectual Decline'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'intellectual_decline','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_intellectual_decline(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Memory Problems'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'memory_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_memory_problems(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Dementia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'dementia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_dementia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Headache'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'n_headache','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_n_headache(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Skin'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Cancer'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 's_cancer','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_s_cancer(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Psoriasis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'psoriasis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_psoriasis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Acne'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 's_acne','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_s_acne(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Other'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 's_other','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_s_other(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Disease'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 's_disease','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_s_disease(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Psychiatric'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Psychiatric Diagnosis'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'p_diagnosis','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_p_diagnosis(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Psychiatric Medication'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'p_medication','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_p_medication(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Depression'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'depression','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_depression(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Anxiety'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'anxiety','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_anxiety(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Social Difficulties'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'social_difficulties','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_social_difficulties(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Endocrine'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Thyroid Problems'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'thyroid_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_thyroid_problems(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Diabetes'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'diabetes','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_diabetes(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Abnormal Blood Test'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'abnormal_blood','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_abnormal_blood(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo smarty_function_xl(array('t' => 'Hematologic'), $this);?>
/<?php echo smarty_function_xl(array('t' => 'Allergic'), $this);?>
/<?php echo smarty_function_xl(array('t' => 'Immunologic'), $this);?>
</span><table>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Anemia'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'anemia','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_anemia(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => "F/H Blood Problems"), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'fh_blood_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_fh_blood_problems(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Bleeding Problems'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'bleeding_problems','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_bleeding_problems(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Allergies'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'allergies','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_allergies(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'Frequent Illness'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'frequent_illness','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_frequent_illness(),'separator' => ""), $this);?>
</td>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'HIV'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hiv','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hiv(),'separator' => ""), $this);?>
</td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo smarty_function_xl(array('t' => 'HAI Status'), $this);?>
:</td>
			<td class="response"><?php echo smarty_function_html_radios(array('name' => 'hai_status','options' => $this->_tpl_vars['form']->get_options(),'selected' => $this->_tpl_vars['form']->get_hai_status(),'separator' => ""), $this);?>
</td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>

<tr>
<td>
	<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['form']->get_id(); ?>
" />
	<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['form']->get_pid(); ?>
" />
	<input type="hidden" name="process" value="true" />
</td>
</tr>
<tr>
<td>
	<input type="submit" name="Submit" class="button" value=<?php  xl('Save','e','"','"');  ?>
</td>
<td>
	<a href="<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
" class="button4" onclick="top.restoreSession()"><?php  xl("Cancel","e");  ?></a>
</td>


</tr>

</table>

</body>

</html>