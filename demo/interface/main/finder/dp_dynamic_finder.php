<?php
// Copyright (C) 2012 Rod Roark <rod@sunsetsystems.com>
// Sponsored by David Eschelbacher, MD
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// Sanitize escapes and stop fake register globals.
//
$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");

$popup = empty($_REQUEST['popup']) ? 0 : 1;

// Generate some code based on the list of columns.
//
$colcount = 0;
$header0 = "";
$header  = "";
$coljson = "";
$res = sqlStatement("SELECT option_id, title FROM list_options WHERE " .
  "list_id = 'DoctorPatients' ORDER BY seq, title");
while ($row = sqlFetchArray($res)) {
  $colname = $row['option_id'];
  $title = xl_list_label($row['title']);
  $header .= "   <th>";
  $header .= text($title);
  $header .= "</th>\n";
  $header0 .= "   <td align='center'><input type='text' size='10' ";
  $header0 .= "value='' class='search_init' /></td>\n";
  if ($coljson) $coljson .= ", ";
  $coljson .= "{\"sName\": \"" . addcslashes($colname, "\t\r\n\"\\") . "\"}";
  ++$colcount;
}
?>
<html>
<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
<link rel="stylesheet" href="../../../library/css/bootstrap.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../../../library/dist/css/AdminLTE.css">
<link rel="stylesheet" href="../../../library/css/mycss.css">
<style type="text/css">
@import "../../../library/js/datatables/media/css/demo_page.css";
@import "../../../library/js/datatables/media/css/demo_table.css";
@import "dynamic_finder.css";
.mytopdiv { float: left; margin-right: 1em; }
.menu{
			width: 100px;
			background: #000;
			color: #fff;
			position:absolute;
			z-index: 999999;
			display: none;
			box-shadow: 0 0 10px #713C3C;
		}
		.menu ul{
			list-style: none;
			padding: 0;
			margin:0;
		}
		.menu ul a{
			text-decoration: none;
		}
		.menu ul li{
			width: 88%;
			padding: 6%;
			background-color: #C0C0C0;
			color: #fff;
		}
		.menu ul li:hover{
			background-color: #F7BA4B;
	    		color: #444343;
		}


</style>

<script type="text/javascript" src="../../../library/js/datatables/media/js/jquery.js"></script>
<script type="text/javascript" src="../../../library/js/datatables/media/js/jquery.dataTables.min.js"></script>
<!-- this is a 3rd party script -->
<script type="text/javascript" src="../../../library/js/datatables/extras/ColReorder/media/js/ColReorderWithResize.js"></script>

<script language="JavaScript">

$(document).ready(function() {
	
 // Initializing the DataTable.
 //
 var oTable = $('#pt_table').dataTable( {
  "bProcessing": true,
  // next 2 lines invoke server side processing
  "bServerSide": true,
  "sAjaxSource": "dp_dynamic_finder_ajax.php",
  // sDom invokes ColReorderWithResize and allows inclusion of a custom div
  "sDom"       : 'Rlfrt<"mytopdiv">ip',
  // These column names come over as $_GET['sColumns'], a comma-separated list of the names.
  // See: http://datatables.net/usage/columns and
  // http://datatables.net/release-datatables/extras/ColReorder/server_side.html
  "aoColumns": [ <?php echo $coljson; ?> ],
  "aLengthMenu": [ 10, 25, 50, 100 ],
  "iDisplayLength": <?php echo empty($GLOBALS['gbl_pt_list_page_size']) ? '25' : '25'; ?>,
  // language strings are included so we can translate them$GLOBALS
  "oLanguage": {
   "sSearch"      : "<?php echo xla('Search all columns'); ?>:",
   "sLengthMenu"  : "<?php echo xla('Show') . ' _MENU_ ' . xla('entries'); ?>",
   "sZeroRecords" : "<?php echo xla('No matching records found'); ?>",
   "sInfo"        : "<?php echo xla('Showing') . ' _START_ ' . xla('to{{range}}') . ' _END_ ' . xla('of') . ' _TOTAL_ ' . xla('entries'); ?>",
   "sInfoEmpty"   : "<?php echo xla('Nothing to show'); ?>",
   "sInfoFiltered": "(<?php echo xla('filtered from') . ' _MAX_ ' . xla('total entries'); ?>)",
   "oPaginate": {
    "sFirst"   : "<?php echo xla('First'); ?>",
    "sPrevious": "<?php echo xla('Previous'); ?>",
    "sNext"    : "<?php echo xla('Next'); ?>",
    "sLast"    : "<?php echo xla('Last'); ?>"
   }
  }
 } );

 // This puts our custom HTML into the table header.
 $("div.mytopdiv").html("<form name='myform'><input type='checkbox' name='form_new_window' value='1'<?php
  if (!empty($GLOBALS['gbl_pt_list_new_window'])) echo ' checked'; ?> /><?php
  echo xlt('Open in New Window'); ?></form>");

 // This is to support column-specific search fields.
 // Borrowed from the multi_filter.html example.
 $("thead input").keyup(function () {
  // Filter on the column (the index) of this element
	oTable.fnFilter( this.value, $("thead input").index(this) );
 });

 // OnClick handler for the rows
 /*$('#pt_table tbody tr').live('click', function () {
  // ID of a row element is pid_{value}
  var newpid = this.id.substring(4);
  // If the pid is invalid, then don't attempt to set 
  // The row display for "No matching records found" has no valid ID, but is
  // otherwise clickable. (Matches this CSS selector).  This prevents an invalid
  // state for the PID to be set.
  if (newpid.length===0)
  {
      return;
  }
  if (document.myform.form_new_window.checked) {
   openNewTopWindow(newpid);
  }
  else {
   top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
   document.location.href = "../../patient_file/summary/demographics.php?set_pid=" + newpid;
<?php } else { ?>
   top.location.href = "../../patient_file/patient_file.php?set_pid=" + newpid;
<?php } ?>
  }
 } );
 */
 
  // OnClick handler for the rows
  $(document).delegate('#pt_table tbody tr', 'mousedown', function(e){
 //$('#pt_table tbody tr').live('click', function (e) {
  // ID of a row element is pid_{value}
  var newpid = this.id.substring(4);
  var enc=$(this).find('td').eq(2).html();
  //var encounter=enc.substring(4);
 //alert(enc);
  // If the pid is invalid, then don't attempt to set 
  // The row display for "No matching records found" has no valid ID, but is
  // otherwise clickable. (Matches this CSS selector).  This prevents an invalid
  // state for the PID to be set.
if( e.button == 2 ) {
	
      if (newpid.length===0)
      {
        return;
      }
      
		$("tr").on("contextmenu",function(e){
		       //prevent default context menu for right click
		       e.preventDefault();

		       var menu = $(".menu"); 

		       //hide menu if already shown
		       menu.hide(); 
		       
		       //get x and y values of the click event
		       var pageX = e.pageX;
		       var pageY = e.pageY;

		       //position menu div near mouse cliked area
		       menu.css({top: pageY , left: pageX});

		       var mwidth = menu.width();
		       var mheight = menu.height();
		       var screenWidth = $(window).width();
		       var screenHeight = $(window).height();

		       //if window is scrolled
		       var scrTop = $(window).scrollTop();

		       //if the menu is close to right edge of the window
		       if(pageX+mwidth > screenWidth){
		       	menu.css({left:pageX-mwidth});
		       }

		       //if the menu is close to bottom edge of the window
		       if(pageY+mheight > screenHeight+scrTop){
		       	menu.css({top:pageY-mheight});
		       }

		       //finally show the menu
		       menu.show();
		}); 
		
		$("html").on("click", function(){
			$(".menu").hide();
		});
	
         
       } else { 
         
        
  if (newpid.length===0)
  {
      return;
  }
  if (document.myform.form_new_window.checked) {
   openNewTopWindow(newpid);
  }
  else {
   top.restoreSession();

  }
	   }
 $(document).ready(function() {

    $('table#pt_table.display.datatable tr').live("click", function() {
		if($(this).hasClass('PT_INACTIVE')) {
        e.preventDefault();
		} else {
		var name=$(this).text();
		var formname='all';
		top.restoreSession();
		//alert(encounter);
		//document.location.href="../../patient_file/history/history.php?set_pid=" + newpid+'&encounter='+enc;
		<?php 
		  $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
		  if($newcrop_user_role['newcrop_user_role'] == 'erxnurse') { ?>
		document.location.href="../../patient_file/encounter/load_form.php?formname=vitals&set_pid=" +newpid+'&active='+formname+'&encounter='+enc;
		<?php } elseif($newcrop_user_role['newcrop_user_role'] == 'erxdoctor') { ?>
		//document.location.href="../../patient_file/summary/stats_full.php?set_pid=" +newpid+'&active='+formname+'&encounter='+enc;
		document.location.href = "../../patient_file/summary/demographics.php?set_pid=" + newpid;
		<?php } ?>
		/*if(name=="Transfer")
		{
		//alert(newpid);
		 top.restoreSession();
		 //alert(encounter);
		document.location.href="../../patient_file/encounter/transfer_form.php?set_pid=" + newpid+'&encounter='+encounter;
		}else if(name=="Discharge")
		{
		top.restoreSession();
		document.location.href="../../patient_file/encounter/discharge_form.php?set_pid=" + newpid+'&encounter='+encounter;
		}else if(name=="Delete")
		{
		top.restoreSession();
		document.location.href="../../forms/admit/delete.php?set_pid=" + newpid+'&encounter='+encounter+'&formname='+formname;
		}*/
		}
    });


});
 } );
 

});

function openNewTopWindow(pid) {
 document.fnew.patientID.value = pid;
 top.restoreSession();
 document.fnew.submit();
}

</script>

</head>
<body class="body_top">
<?php 
$user=  $_SESSION["authUser"];

$row1 = sqlStatement("SELECT id,newcrop_user_role,specialty,facility_id from users where username='".$user."'");
$row2=  sqlFetchArray($row1);
$providerid=$row2['id'];
$today = date('Y-m-d',strtotime("+0 days"));

$query_seen = "select a.*, b.*,round(((b.pending_patient/a.total_no_of_patients )*100),0)pending_percent,round((((a.total_no_of_patients-b.pending_patient)/a.total_no_of_patients)*100),0)seen_percent, (a.total_no_of_patients-b.pending_patient)no_of_examined_patients
from
(SELECT count(b.id) total_no_of_patients 
FROM patient_data a,form_encounter b 
where a.pid=b.pid and b.provider_id='".$providerid."'  )a,

(SELECT count(b.id)pending_patient
FROM patient_data a,form_encounter b 
where a.pid=b.pid and b.provider_id='".$providerid."'  
and out_to is null and out_time is  null)b";

//$query_seen = "SELECT  coalesce(count(b.id),0) total_no_of_patients FROM patient_data a,form_encounter b where a.pid=b.pid and b.provider_id='".$providerid."'  and date(b.date)='".$today."' and b.out_to='Examined By'";
$res_seen = sqlStatement($query_seen);
$res_seen1 = sqlFetchArray($res_seen);

?>
<div class="col-md-12">

<?php 
if (isset($_SESSION['LAST_ACTIVITY_nurse'])  && isset($_SESSION['nurseVisit']) && (time() - $_SESSION['LAST_ACTIVITY_nurse'] < 60)) {
?>
        <div class="alert alert-success alert-dismissable">
            <i class="fa  fa-check-circle"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <b>Success!</b> Patient data has been recorded under visit ID <?php echo $_SESSION['nurseVisit']; ?> 
        </div>
		<?php
}
?>
<div class="col-md-4">
          <!-- Info Boxes Style 2 -->
          <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="ion ion-ios-people-outline"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">All Patients</span>
			
              <span class="info-box-number"><?php  echo $res_seen1['total_no_of_patients'];  ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: <?php echo $res1['Total_no_of_patients']; ?>%"></div>
              </div>
                  <span class="progress-description">
                    <?php echo $res1['Total_no_of_patients']; ?>% Increase since yesterday
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
		  </div>
		  <div class="col-md-4">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="ion ion-ios-eye"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Patients Seen</span>
              <span class="info-box-number"><?php  echo $res_seen1['no_of_examined_patients']; ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: <?php echo $res_seen1['seen_percent']; ?>%"></div>
              </div>
                  <span class="progress-description">
                    <?php echo $res_seen1['seen_percent']; ?>% Complete
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
		  </div>
		  <div class="col-md-4">
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-compass"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Patients Pending</span>
              <span class="info-box-number"><?php  echo $res_seen1['pending_patient']; ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: <?php echo $res_seen1['pending_percent']; ?>%"></div>
              </div>
                  <span class="progress-description">
                    <?php echo $res_seen1['pending_percent']; ?>% Pending
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
		  </div>

</div>
        </div>
<div id="dynamic"><!-- TBD: id seems unused, is this div required? -->

<!-- Class "display" is defined in demo_table.css -->
<table cellpadding="0" cellspacing="0" border="0" class="display" id="pt_table">
 <thead>
  <tr>
<?php echo $header0; ?>
  </tr>
  <tr>
<?php echo $header; ?>
  </tr>
 </thead>
 <tbody>
  <tr onclick="">
   <!-- Class "dataTables_empty" is defined in jquery.dataTables.css -->
   <td colspan="<?php echo $colcount; ?>" class="dataTables_empty">...</td>
  </tr>
 </tbody>
</table>

</div>

<!-- form used to open a new top level window when a patient row is clicked -->
<form name='fnew' method='post' target='_blank' action='../main_screen.php?auth=login&site=<?php echo attr($_SESSION['site_id']); ?>'>
<input type='hidden' name='patientID'      value='0' />
</form>

</body>
</html>

