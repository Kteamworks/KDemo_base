<?php

require_once("../../globals.php");
require_once("$srcdir/lists.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");

// get various authorization levels
$auth_notes_a  = acl_check('encounters', 'notes_a');
$auth_notes    = acl_check('encounters', 'notes');
$auth_coding_a = acl_check('encounters', 'coding_a');
$auth_coding   = acl_check('encounters', 'coding');
$auth_relaxed  = acl_check('encounters', 'relaxed');
$auth_med      = acl_check('patients'  , 'med');
$auth_demo     = acl_check('patients'  , 'demo');

$cmsportal = false;
if ($GLOBALS['gbl_portal_cms_enable']) {
  $ptdata = getPatientData($pid, 'cmsportal_login');
  $cmsportal = $ptdata['cmsportal_login'] !== '';
}
?>
<html>
<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>

<!-- include jQuery support -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

<script language='JavaScript'>

function checkAll(check) {
 var f = document.forms['report_form'];
 for (var i = 0; i < f.elements.length; ++i) {
  if (f.elements[i].type == 'checkbox') f.elements[i].checked = check;
 }
 return false;
}

function show_date_fun(){
  if(document.getElementById('show_date').checked == true){
    document.getElementById('date_div').style.display = '';
  }else{
    document.getElementById('date_div').style.display = 'none';
  }
  return;
}

</script>

</head>

<body class="body_top">
<div id="patient_reports"> <!-- large outer DIV -->

<?php if ( $GLOBALS['activate_ccr_ccd_report'] ) { // show CCR/CCD reporting options ?>
<div id="ccr_report">

<form name='ccr_form' id='ccr_form' method='post' action='../../../ccr1/createCCR.php'>
<span class='title'><?php //xl('Continuity of Care Record (CCR)','e'); ?></span>&nbsp;&nbsp;
<br/>
<span class='text'><?php //xl('Pop ups need to be enabled to see these reports','e'); ?></span>
<br/>
<br/>
<input type='hidden' name='ccrAction'>
<input type='hidden' name='raw'>

<br>
<div id="date_div" style="display:none" >
  <br>
 
</div>
<br>

<!-- <input type="button" class="generateCCR_raw" value="<?php xl('Raw Report','e'); ?>" /> -->
<?php if ($GLOBALS['phimail_enable']==true && $GLOBALS['phimail_ccr_enable']==true) { ?>
<input type="button" class="viewCCR_send_dialog" value="<?php echo htmlspecialchars( xl('Transmit', ENT_QUOTES)); ?>" />
             <br>
             <div id="ccr_send_dialog" style="display:none" >
              <br>
              <table border="0" cellpadding="0" cellspacing="0" >
               <tr>
                <td>
                 <span class='bold'><?php echo htmlspecialchars( xl('Enter Recipient\'s Direct Address'), ENT_NOQUOTES);?>: </span>
                <input type="text" size="64" name="ccr_send_to" id="ccr_send_to" value="">
                <input type="hidden" name="ccr_sent_by" id="ccr_sent_by" value="user">
                <input type="button" class="viewCCR_transmit" value="<?php echo htmlspecialchars( xl('Send', ENT_QUOTES)); ?>" />
                <div id="ccr_send_result" style="display:none" >
                 <span class="text" id="ccr_send_message"></span>
                </div>
                </td>
              </tr>
              </table>
             </div>
<?php } ?>
<hr/>
<span class='title'><?php xl('Discharge Summary','e'); ?></span>&nbsp;&nbsp;
<br/>
<span class='text'>(<?php xl('Pop ups need to be enabled to see these reports','e'); ?>)</span>
<br/>
<br/>
<input type="button" class="viewCCD" value="<?php xl('View/Print','e'); ?>" />
<input type="button" class="viewCCD_download" value="<?php echo htmlspecialchars( xl('Download', ENT_QUOTES)); ?>" />
<!-- <input type="button" class="viewCCD_raw" value="<?php xl('Raw Report','e'); ?>" /> -->
<?php if ($GLOBALS['phimail_enable']==true && $GLOBALS['phimail_ccd_enable']==true) { ?>
<input type="button" class="viewCCD_send_dialog" value="<?php echo htmlspecialchars( xl('Transmit', ENT_QUOTES)); ?>" />
             <br>
             <div id="ccd_send_dialog" style="display:none" >
              <br>
              <table border="0" cellpadding="0" cellspacing="0" >
               <tr>
                <td>
                 <span class='bold'><?php echo htmlspecialchars( xl('Enter Recipient\'s Direct Address'), ENT_NOQUOTES);?>: </span>
                <input type="text" size="64" name="ccd_send_to" id="ccd_send_to" value="">
		<input type="hidden" name="ccd_sent_by" id="ccd_sent_by" value="user">
                <input type="button" class="viewCCD_transmit" value="<?php echo htmlspecialchars( xl('Send', ENT_QUOTES)); ?>" />
                <div id="ccd_send_result" style="display:none" >
                 <span class="text" id="ccd_send_message"></span>
                </div>
                </td>
              </tr>
              </table>
             </div>
<?php } ?>

</form>


</div>
<?php } // end CCR/CCD reporting options ?>


<!--<input type="button" class="genreport" value="<?php xl('Generate Report','e'); ?>" />&nbsp;
<input type="button" class="genpdfrep" value="<?php xl('Download PDF','e'); ?>" />&nbsp; -->
<?php if ($cmsportal) { ?>
<input type="button" class="genportal" value="<?php xl('Send to Portal','e'); ?>" />
<?php } ?>

</div>  <!-- close patient_reports DIV -->
</body>

<script language="javascript">

// jQuery stuff to make the page a little easier to use
$(document).ready(function(){
    $(".genreport").click(function() { top.restoreSession(); document.report_form.pdf.value = 0; $("#report_form").submit(); });
    $(".genpdfrep").click(function() { top.restoreSession(); document.report_form.pdf.value = 1; $("#report_form").submit(); });
    $(".genportal").click(function() { top.restoreSession(); document.report_form.pdf.value = 2; $("#report_form").submit(); });
    $("#genfullreport").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl";?>'; });
    //$("#printform").click(function() { PrintForm(); });
    $(".issuecheckbox").click(function() { issueClick(this); });

    // check/uncheck all Forms of an encounter
    $(".encounter").click(function() { SelectForms($(this)); });

	$(".generateCCR").click(
        function() {
                if(document.getElementById('show_date').checked == true){
                        if(document.getElementById('Start').value == '' || document.getElementById('End').value == ''){
                                alert('<?php echo addslashes( xl('Please select a start date and end date')) ?>');
                                return false;
                        }
                }
		var ccrAction = document.getElementsByName('ccrAction');
		ccrAction[0].value = 'generate';
                var raw = document.getElementsByName('raw');
                raw[0].value = 'no';
		top.restoreSession();
		ccr_form.setAttribute("target", "_blank");
		$("#ccr_form").submit();
                ccr_form.setAttribute("target", "");
	});
        $(".generateCCR_raw").click(
        function() {
                var ccrAction = document.getElementsByName('ccrAction');
                ccrAction[0].value = 'generate';
                var raw = document.getElementsByName('raw');
                raw[0].value = 'yes';
                top.restoreSession();
                ccr_form.setAttribute("target", "_blank");
                $("#ccr_form").submit();
                ccr_form.setAttribute("target", "");
        });
        $(".generateCCR_download_h").click(
        function() {
                var ccrAction = document.getElementsByName('ccrAction');
                ccrAction[0].value = 'generate';
                var raw = document.getElementsByName('raw');
                raw[0].value = 'hybrid';
                top.restoreSession();
                $("#ccr_form").submit();
        });
        $(".generateCCR_download_p").click(
        function() {
                if(document.getElementById('show_date').checked == true){
                        if(document.getElementById('Start').value == '' || document.getElementById('End').value == ''){
                                alert('<?php echo addslashes( xl('Please select a start date and end date')) ?>');
                                return false;
                        }
                }
                var ccrAction = document.getElementsByName('ccrAction');
                ccrAction[0].value = 'generate';
                var raw = document.getElementsByName('raw');
                raw[0].value = 'pure';
                top.restoreSession();
                $("#ccr_form").submit();
        });
	$(".viewCCD").click(
	function() { 
		var ccrAction = document.getElementsByName('ccrAction');
		ccrAction[0].value = 'viewccd';
                var raw = document.getElementsByName('raw');
                raw[0].value = 'no';
		top.restoreSession();
                ccr_form.setAttribute("target", "_blank"); 
		$("#ccr_form").submit();
                ccr_form.setAttribute("target", "");
	});
        $(".viewCCD_raw").click(
        function() {
                var ccrAction = document.getElementsByName('ccrAction');
                ccrAction[0].value = 'viewccd';
                var raw = document.getElementsByName('raw');
                raw[0].value = 'yes';
                top.restoreSession();
                ccr_form.setAttribute("target", "_blank");
                $("#ccr_form").submit();
                ccr_form.setAttribute("target", "");
        });
        $(".viewCCD_download").click(
        function() {
                var ccrAction = document.getElementsByName('ccrAction');
                ccrAction[0].value = 'viewccd';
                var raw = document.getElementsByName('raw');
                raw[0].value = 'pure';
                $("#ccr_form").submit();
        });
<?php if ($GLOBALS['phimail_enable']==true && $GLOBALS['phimail_ccr_enable']==true) { ?>
        $(".viewCCR_send_dialog").click(
        function() {
                $("#ccr_send_dialog").toggle();
        });
        $(".viewCCR_transmit").click(
        function() {
                $(".viewCCR_transmit").attr('disabled','disabled');
                var ccrAction = document.getElementsByName('ccrAction');
                ccrAction[0].value = 'generate';
                var ccrRecipient = $("#ccr_send_to").val();
                var raw = document.getElementsByName('raw');
                raw[0].value = 'send '+ccrRecipient;
                if(ccrRecipient=="") {
                  $("#ccr_send_message").html("<?php
       echo htmlspecialchars(xl('Please enter a valid Direct Address above.'), ENT_QUOTES);?>");
                  $("#ccr_send_result").show();
                } else {
                  $(".viewCCR_transmit").attr('disabled','disabled');
                  $("#ccr_send_message").html("<?php
       echo htmlspecialchars(xl('Working... this may take a minute.'), ENT_QUOTES);?>");
                  $("#ccr_send_result").show();
                  var action=$("#ccr_form").attr('action');
                  $.post(action, {ccrAction:'generate',raw:'send '+ccrRecipient,requested_by:'user'},
                     function(data) {
                       if(data=="SUCCESS") {
                         $("#ccr_send_message").html("<?php
       echo htmlspecialchars(xl('Your message was submitted for delivery to'), ENT_QUOTES);
                           ?> "+ccrRecipient);
                         $("#ccr_send_to").val("");
                       } else {
                         $("#ccr_send_message").html(data);
                       }
                       $(".viewCCR_transmit").removeAttr('disabled');
                  });
                }
        });
<?php }
      if ($GLOBALS['phimail_enable']==true && $GLOBALS['phimail_ccd_enable']==true) { ?>
        $(".viewCCD_send_dialog").click(
        function() {
                $("#ccd_send_dialog").toggle();
        });
        $(".viewCCD_transmit").click(
        function() {
                $(".viewCCD_transmit").attr('disabled','disabled');
                var ccrAction = document.getElementsByName('ccrAction');
                ccrAction[0].value = 'viewccd';
                var ccdRecipient = $("#ccd_send_to").val();
                var raw = document.getElementsByName('raw');
                raw[0].value = 'send '+ccdRecipient;
                if(ccdRecipient=="") {
                  $("#ccd_send_message").html("<?php
       echo htmlspecialchars(xl('Please enter a valid Direct Address above.'), ENT_QUOTES);?>");
                  $("#ccd_send_result").show();
                } else {
                  $(".viewCCD_transmit").attr('disabled','disabled');
                  $("#ccd_send_message").html("<?php
       echo htmlspecialchars(xl('Working... this may take a minute.'), ENT_QUOTES);?>");
                  $("#ccd_send_result").show();
                  var action=$("#ccr_form").attr('action');
                  $.post(action, {ccrAction:'viewccd',raw:'send '+ccdRecipient,requested_by:'user'},
                     function(data) {
                       if(data=="SUCCESS") {
                         $("#ccd_send_message").html("<?php
       echo htmlspecialchars(xl('Your message was submitted for delivery to'), ENT_QUOTES);
                           ?> "+ccdRecipient);
                         $("#ccd_send_to").val("");
                       } else {
                         $("#ccd_send_message").html(data);
                       }
                       $(".viewCCD_transmit").removeAttr('disabled');
                  });
                }
        });
<?php } ?>

});

// select/deselect the Forms related to the selected Encounter
// (it ain't pretty code folks)
var SelectForms = function (selectedEncounter) {
    if ($(selectedEncounter).attr("checked")) {
        $(selectedEncounter).parent().children().each(function(i, obj) {
            $(this).children().each(function(i, obj) {
                $(this).attr("checked", "checked");
            });
        });
    }
    else {
        $(selectedEncounter).parent().children().each(function(i, obj) {
            $(this).children().each(function(i, obj) {
                $(this).removeAttr("checked");
            });
        });
    }
}

// When an issue is checked, auto-check all the related encounters and forms
function issueClick(issue) {
    // do nothing when unchecked
    if (! $(issue).attr("checked")) return;

    $("#report_form :checkbox").each(function(i, obj) {
        if ($(issue).val().indexOf('/' + $(this).val() + '/') >= 0) {
            $(this).attr("checked", "checked");
        }
            
    });
}

</script>

</html>
