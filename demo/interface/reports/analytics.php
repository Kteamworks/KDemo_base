<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

 // This module shows relative insurance usage by unique patients
 // that are seen within a given time period.  Each patient that had
 // a visit is counted only once, regardless of how many visits.

 include_once("../globals.php");
 include_once("../../library/patient.inc");
 include_once("../../library/acl.inc");
 require_once("../../library/formatting.inc.php");

 // Might want something different here.
 //
 // if (! acl_check('acct', 'rep')) die("Unauthorized access.");


if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=insurance_distribution.csv");
  header("Content-Description: File Transfer");
  // CSV headers:
  if (true) {
    echo '"Insurance",';
    echo '"Charges",';
    echo '"Visits",';
    echo '"Patients",';
    echo '"Pt Pct"' . "\n";
  }
}
else {
	if(1)
	{
		$address = "http://45.118.183.174:8080/kavaii/login.html?j_username=hpademo&j_password=hpademo&singlesingon=y";
			  echo"<script type='text/javascript'>top.restoreSession();window.location='$address';</script>";
	}
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Insurance Progress Report','e'); ?></title>
<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/calendar.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>
<script language="JavaScript">
 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';
</script>

<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<style type="text/css">

/* specifically include & exclude from printing */
@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results table {
       margin-top: 0px;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}

</style>
</head>

<body class="body_top">

<!-- Required for the popup date selectors -->
<div id="overDiv"></div>

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Analytics','e'); ?></span>

<form name='theform' method='post' action="analytics.php" id='theform'>

  </td>
  <td align='left' height="100%">
	<table>
		<tr>
			<td>
				<!-- <div>
				<a id="anchorTest" class='css_button'  href="http://192.168.1.100:8081/kavaii/login.html?j_username=hpademo&j_password=hpademo&singlesingon=y">
					
					<span>
						<?php xl('Submit','e'); ?>
					</span>
					</a>
				</div>-->
			</td> 
		</tr>
	</table>
  </td>
 </tr>
</table>

</form>
</div> <!-- end parameters -->

</body>

<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
$(document).ready(function () {
	$('#anchorTest').trigger('click');

            $('#anchorTest').click(function () {
             
                 
                //alert('clicked '+$(this).attr('href'));
                //window.open($(this).attr('href'));
                
           

                
            }); 

            
        });

</script>
</html>
<?php
} // end not export
?>
