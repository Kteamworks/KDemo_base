<?php
/**
 * Login screen.
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @author  Scott Wakefield <scott.wakefield@gmail.com>
 * @author  ViCarePlus <visolve_emr@visolve.com>
 * @author  Julia Longtin <julialongtin@diasp.org>
 * @author  cfapress
 * @author  markleeds
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

$ignoreAuth=true;
include_once("../globals.php");
include_once("$srcdir/sql.inc");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> MedSmart </title>
        <link href="../../images/logo.png"  rel="shortcut icon" >
        
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->
        <link href="../../library/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Admin LTE CSS -->
        <link href="../../library/css/AdminLTEsemi.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

        <link href="../../library/css/app.css" rel="stylesheet" type="text/css" />
        
       <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
		    <link href="../../library/css/widgetbox.css" rel="stylesheet" type="text/css" />
        <link href="../../library/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <link href="../../library/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        
        
<?php html_header_show();?>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<link rel=stylesheet href="../themes/login.css" type="text/css">

<script language='JavaScript' src="../../library/js/jquery-1.4.3.min.js"></script>
<script language='JavaScript'>
function transmit_form()
{
    document.forms[0].submit();
}
function imsubmitted() {
<?php if (!empty($GLOBALS['restore_sessions'])) { ?>
 // Delete the session cookie by setting its expiration date in the past.
 // This forces the server to create a new session ID.
 var olddate = new Date();
 olddate.setFullYear(olddate.getFullYear() - 1);
 document.cookie = '<?php echo session_name() . '=' . session_id() ?>; path=/; expires=' + olddate.toGMTString();
<?php } ?>
    return false; //Currently the submit action is handled by the encrypt_form(). 
}
</script>

</head>
<body onload="javascript:document.login_form.authUser.focus();" >
        <div id="page" class="hfeed site">
            <!-- Main content -->
            <div id="main" style="background:url('hd.jpg');    background-position: 75% 14%;
    min-height: 432px;">
                <div class="container">
                    <div class="content-area">
                        <div class="row">
						
						
 <div class="login-box" style=" width: 500px;
    height: 150px;"  valign = "center">
 <div style="padding: 20px">
 
<form method="POST"
 action="../main/main_screen.php?auth=login&site=<?php echo attr($_SESSION['site_id']); ?>"
 target="_top" name="login_form" onsubmit="return imsubmitted();">

<input type='hidden' name='new_login_session_management' value='1' />

<?php
// collect groups
$res = sqlStatement("select distinct name from groups");
for ($iter = 0;$row = sqlFetchArray($res);$iter++)
	$result[$iter] = $row;
if (count($result) == 1) {
	$resvalue = $result[0]{"name"};
	echo "<input type='hidden' name='authProvider' value='" . attr($resvalue) . "' />\n";
}
// collect default language id
$res2 = sqlStatement("select * from lang_languages where lang_description = ?",array($GLOBALS['language_default']));
for ($iter = 0;$row = sqlFetchArray($res2);$iter++)
          $result2[$iter] = $row;
if (count($result2) == 1) {
          $defaultLangID = $result2[0]{"lang_id"};
          $defaultLangName = $result2[0]{"lang_description"};
}
else {
          //default to english if any problems
          $defaultLangID = 1;
          $defaultLangName = "English";
}
// set session variable to default so login information appears in default language
$_SESSION['language_choice'] = $defaultLangID;
// collect languages if showing language menu
if ($GLOBALS['language_menu_login']) {
    
        // sorting order of language titles depends on language translation options.
        $mainLangID = empty($_SESSION['language_choice']) ? '1' : $_SESSION['language_choice'];
        if ($mainLangID == '1' && !empty($GLOBALS['skip_english_translation']))
        {
          $sql = "SELECT *,lang_description as trans_lang_description FROM lang_languages ORDER BY lang_description, lang_id";
	  $res3=SqlStatement($sql);
        }
        else {
          // Use and sort by the translated language name.
          $sql = "SELECT ll.lang_id, " .
            "IF(LENGTH(ld.definition),ld.definition,ll.lang_description) AS trans_lang_description, " .
	    "ll.lang_description " .
            "FROM lang_languages AS ll " .
            "LEFT JOIN lang_constants AS lc ON lc.constant_name = ll.lang_description " .
            "LEFT JOIN lang_definitions AS ld ON ld.cons_id = lc.cons_id AND " .
            "ld.lang_id = ? " .
            "ORDER BY IF(LENGTH(ld.definition),ld.definition,ll.lang_description), ll.lang_id";
          $res3=SqlStatement($sql, array($mainLangID));
	}
    
        for ($iter = 0;$row = sqlFetchArray($res3);$iter++)
               $result3[$iter] = $row;
        if (count($result3) == 1) {
	       //default to english if only return one language
               echo "<input type='hidden' name='languageChoice' value='1' />\n";
        }
}
else {
        echo "<input type='hidden' name='languageChoice' value='".attr($defaultLangID)."' />\n";   
}
?>

<?php if (count($result) != 1) { ?>
 <div class="col-xs-12">

<!-- Password -->
<div class="form-group has-feedback">
    <span class="text"><?php echo xlt('Group:'); ?></span>
<select name=authProvider>
<?php
	foreach ($result as $iter) {
		echo "<option value='".attr($iter{"name"})."'>".text($iter{"name"})."</option>\n";
	}
?>
</select>
</div>
</div>
<?php } ?>

<?php if (isset($_SESSION['loginfailure']) && ($_SESSION['loginfailure'] == 1)): ?>
<div class="alert alert-danger alert-dismissable">
    <i class="fa  fa-ban"> </i> <b> Alert </b>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    

<?php echo xlt('Invalid username or password'); ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['relogin']) && ($_SESSION['relogin'] == 1)): ?>
<div class="alert alert-danger alert-dismissable">
    <i class="fa  fa-ban"> </i> <b> Alert </b>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   
<b><?php echo xlt('Password security has recently been upgraded.'); ?><br>
<?php echo xlt('Please login again.'); ?></b>
<?php unset($_SESSION['relogin']); ?>
</div>
<?php endif; ?>
 <div class="col-xs-12">
<div class="form-group has-feedback">
    <input type='text'  name="authUser" placeholder='Enter Email/User ID' class='form-control'>
	<span class="glyphicon glyphicon-envelope form-control-feedback"></span>

</div>
</div>
 <div class="col-xs-12">

<!-- Password -->
<div class="form-group has-feedback">
      <input type="password" class='form-control' placeholder='Password' name="clearPass">  
   <span class="glyphicon glyphicon-lock form-control-feedback"></span>

</div>
</div>
        
        

<?php
if ($GLOBALS['language_menu_login']) {
if (count($result3) != 1) { ?>
<span class="text"><?php echo xlt('Language'); ?>:</span>
<select class="entryfield" name=languageChoice size="1">
<?php
        echo "<option selected='selected' value='" . attr($defaultLangID) . "'>" . xlt('Default') . " - " . xlt($defaultLangName) . "</option>\n";
        foreach ($result3 as $iter) {
	        if ($GLOBALS['language_menu_showall']) {
                    if ( !$GLOBALS['allow_debug_language'] && $iter[lang_description] == 'dummy') continue; // skip the dummy language
                    echo "<option value='".attr($iter['lang_id'])."'>".text($iter['trans_lang_description'])."</option>\n";
		}
	        else {
		    if (in_array($iter[lang_description], $GLOBALS['language_menu_show'])) {
                        if ( !$GLOBALS['allow_debug_language'] && $iter['lang_description'] == 'dummy') continue; // skip the dummy language
		        echo "<option value='".attr($iter['lang_id'])."'>" . text($iter['trans_lang_description']) . "</option>\n";
		    }
		}
        }
?>
</select>

<?php }} ?>

    <div class="col-xs-12">
	<input type="submit" onClick="transmit_form()"  class="btn btn-primary btn-block btn-flat" value="<?php echo xla('Login');?>">

       </div><!-- /.col -->


 <div class="col-xs-12">
<?php
$ip=$_SERVER['REMOTE_ADDR'];
?>
</div>
</form>

 
<div class="row">
        <div class="col-xs-12">

    <div class="col-xs-6">

            <label>
             <!--   <input type="checkbox" name="remember"> Remember me -->
            </label>
        </div>
    <!-- /.col -->

    <div class="col-xs-6">
 
<a href="../reset/password.php" class="text-danger bg-success pull-right" style="padding:10px">I forgot my password</a><br> 

</div>

<!-- /.login-page -->
  </div><!-- /.col -->
</div>
</div>
</div>

						</div>
						</div>
						</div>
						</div>
            <footer id="colophon" class="site-footer" role="contentinfo" style="
    padding: 0;
    padding-top: 15px;">
                <div class="container">

                    <div class="row">
                        <div class="site-info col-md-6">
                            <p class="text-muted">Copyright &copy; <?php echo date('Y') ?>  <a href="http://kavaii.com/index.html" target="_blank"><b>Kavaii</b></a>. All rights reserved. Powered by <a href="http://www.medsmart.co.in/"  target="_blank"><b>MedSmart</b></a></p>
                        </div>
                        <div class="site-social text-right col-md-6">

                            <ul class="list-inline hidden-print">

                                <li><a href="https://www.facebook.com/Kavaii-Inc-Passion-for-Data-103624693070885" class="btn btn-social btn-facebook" target="_blank"><i class="fa fa-facebook fa-fw"></i></a></li>

                                <li><a href="https://twitter.com/kavaiianalytics" class="btn btn-social btn-twitter" target="_blank"><i class="fa fa-twitter fa-fw"></i></a></li>

                            <!--    <li><a href="" class="btn btn-social btn-google-plus" target="_blank"><i class="fa fa-google-plus fa-fw"></i></a></li>  -->

                                <li><a href="https://www.linkedin.com/company/1030463/" class="btn btn-social btn-linkedin" target="_blank"><i class="fa fa-linkedin fa-fw"></i></a></li>

                            </ul>
                        </div>
                    </div>
            </footer><!-- #colophon -->
			</div>
            <script src="../../library/js/jquery2.1.1.min.js" type="text/javascript"></script>
            <!-- Bootstrap 3.3.2 JS -->
            <script src="../../library/js/bootstrap.min.js" type="text/javascript"></script>
            <!-- Slimscroll -->
            <script src="../../library/js/superfish.js" type="text/javascript"></script>
            
            <script src="../../library/js/mobilemenu.js" type="text/javascript"></script>
            
            <script src="../../library/js/know.js" type="text/javascript"></script>
            
            
            <script src="../../library/iCheck/icheck.min.js" type="text/javascript"></script>
            
            <script>
$(function () {
//Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
        var clicks = $(this).data('clicks');
        if (clicks) {
            //Uncheck all checkboxes
            $("input[type='checkbox']", ".mailbox-messages").iCheck("uncheck");
        } else {
            //Check all checkboxes
            $("input[type='checkbox']", ".mailbox-messages").iCheck("check");
        }
        $(this).data("clicks", !clicks);
    });
//Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
        e.preventDefault();
//detect type
        var $this = $(this).find("a > i");
        var glyph = $this.hasClass("glyphicon");
        var fa = $this.hasClass("fa");
//Switch states
        if (glyph) {
            $this.toggleClass("glyphicon-star");
            $this.toggleClass("glyphicon-star-empty");
        }
        if (fa) {
            $this.toggleClass("fa-star");
            $this.toggleClass("fa-star-o");
        }
    });
});
            </script>

</body>
</html>
