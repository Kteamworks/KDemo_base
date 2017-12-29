<?php

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

 require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/calendar.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/amc.php");
require_once $GLOBALS['srcdir'].'/ESign/Api.php';
require_once("$srcdir/../controllers/C_Document.class.php");

   if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }
 ?>
 <html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="../../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
<link rel="stylesheet" type="text/css" href="../../../library/css/widgetbox.css">
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="../../../library/js/common.js"></script>
<style>
#content {
    margin-left: 10%;
}
#partable {
	margin-left: 10%;
}
table {
	padding: 10px;
}
</style>
</head>

<body>
<div class="container">
<div class="row">
 <div id="content" class="site-content col-md-12">
 
    <div id="corewidgetbox">
        <div class="widgetrow text-center">
<?php $collection_chk = sqlQuery("select * from procedure_order_code where procedure_order_id=".$_GET['orderid']);

?>
            <span onclick="javascript: window.location.href='{{url('auth/register')}}';">
                <a href="../../main/finder/specimen_collected.php?set_pid=<?php echo $_GET['set_pid']; ?>&encounter=<?php echo $_GET['encounter']; ?>&orderid=<?php echo $_GET['orderid'] ?>"  class="widgetrowitem defaultwidget" style="background-image: URL('../../../images/register.png');<?php if($collection_chk['sample_collected'] == 1) {?>background-color:blanchedalmond<?php } ?>">
                    <span class="widgetitemtitle"> Sample Collection</span>
                </a>
            </span>

                    <span onclick="javascript: window.location.href='{!! URL::route('form') !!}';">
                        <a href="../../main/finder/specimen_received.php?set_pid=<?php echo $_GET['set_pid']; ?>&encounter=<?php echo $_GET['encounter']; ?>&orderid=<?php echo $_GET['orderid'] ?>&name=Sample Received" class="widgetrowitem defaultwidget" style="background-image: URL('../../../images/news.png');<?php if($collection_chk['sample_receive'] == 1) {?>background-color:blanchedalmond<?php } ?>">
                            <span class="widgetitemtitle">Sample Recieved</span>
                        </a>
                    </span>

            <span onclick="javascript: window.location.href='{{url('mytickets')}}';">
                <a href="../../orders/orders_results_right.php?set_pid=<?php echo $_GET['set_pid']; ?>&encounter=<?php echo $_GET['encounter']; ?>&orderid=<?php echo $_GET['orderid'] ?>&review=<?php echo $_GET['review'] ?>" class="widgetrowitem defaultwidget" style="background-image: URL('../../../images/submitticket.png');">
                    <span class="widgetitemtitle">Add Results</span>
                </a>
            </span>
            <span onclick="javascript: window.location.href='{{url('/knowledgebase')}}';">
                <a href="../../main/finder/update_pending.php?encounter=<?php echo $_GET['encounter']; ?>&orderid=<?php echo $_GET['orderid'] ?>&name=Pending" class="widgetrowitem defaultwidget" style="background-image: URL('../../../images/knowledgebase.png');">
                    <span class="widgetitemtitle">Pending Test List</span>
                </a>
            </span>
			            <span onclick="javascript: window.location.href='{{url('/knowledgebase')}}';">
                <a href="../../main/finder/update_pending.php?encounter=<?php echo $_GET['encounter']; ?>&orderid=<?php echo $_GET['orderid'] ?>&name=Cancelled" class="widgetrowitem defaultwidget" style="background-image: URL('../../../images/knowledgebase.png');" onclick="return confirm('Are you sure you want to mark this test as canceled？')">
                    <span class="widgetitemtitle">Mark Cancelled</span>
                </a>
            </span>
			            <span onclick="javascript: window.location.href='{{url('/knowledgebase')}}';">
                <a href="../../main/finder/update_pending.php?encounter=<?php echo $_GET['encounter']; ?>&orderid=<?php echo $_GET['orderid'] ?>&name=Complete" class="widgetrowitem defaultwidget" style="background-image: URL('../../../images/knowledgebase.png');" onclick="return confirm('Are you sure you want to mark this test as completed？')">
                    <span class="widgetitemtitle">Mark Completed</span>
                </a>
            </span>
						            <span onclick="javascript: window.location.href='{{url('/knowledgebase')}}';">
                <a href="../../main/finder/update_pending.php?encounter=<?php echo $_GET['encounter']; ?>&orderid=<?php echo $_GET['orderid'] ?>&name=Routed" class="widgetrowitem defaultwidget" style="background-image: URL('../../../images/knowledgebase.png');" onclick="return confirm('Are you sure you want to route this test？')">
                    <span class="widgetitemtitle">Mark Routed</span>
                </a>
            </span>
        </div>
    </div>
	</div>
	</div>
<div class="row">
<div class="col-md-12">
<?php

  if ($result = getFormByEncounter($pid, $_GET['encounter'], "id, date, form_id, form_name, formdir, user, deleted")) {
	  
    echo "<table class='table table-responsive table-striped' id='partable'>";

    foreach ($result as $iter) {

        $formdir = $iter['formdir'];

        // skip forms whose 'deleted' flag is set to 1
        if ($iter['deleted'] == 1) continue;

 

        $user = getNameFromUsername($iter['user']);

        $form_name = ($formdir == 'newpatient') ? xl('Patient Visit') : xl_form_title($iter['form_name']);


        // Use the form's report.php for display.  Forms with names starting with LBF
        // are list-based forms sharing a single collection of code.
        //
        if (substr($formdir,0,3) == 'LBF') {
          include_once($GLOBALS['incdir'] . "/forms/LBF/report.php");
          call_user_func("lbf_report", $pid, $encounter, 2, $iter['form_id'], $formdir);
        }
        else  {
          include_once($GLOBALS['incdir'] . "/forms/procedure_order/report.php");
          call_user_func($formdir . "_report", $pid, $encounter, 2, $iter['form_id']);
        }

    }
    echo "</table>";
}
?>
</div>
</div>


<script type="text/javascript"> $(function(){ $('.dialogerror, .dialoginfo, .dialogalert').fadeIn('slow');$("form").bind("submit", function(e){$(this).find("input:submit").attr("disabled", "disabled");});});</script>
<script type="text/javascript" >try {if (top.location.hostname != self.location.hostname) { throw 1; }} catch (e) { top.location.href = self.location.href; }</script>
</div>   
</body>
</html>