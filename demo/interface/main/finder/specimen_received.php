<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
$encounter = $_GET["encounter"] ? $_GET["encounter"] : $_GET["encounter"];
$orderid = $_GET["orderid"] ? $_GET["orderid"] : $_GET["orderid"];
 include_once("$srcdir/pid.inc");
 if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }
 
 if ($_POST['submit']) {
	$value_select = $_POST['value_code'];
	if($value_select){
		foreach($value_select as $this_value){
	 $sample_date_collected=$_POST['sample_date_collected'];
	 $specimen=$_POST['specimen'];
	 $orderid=$_POST['orderid'];
	 $procedure_code=$_POST['procedure_code'];
	  $procedure_sample_id=$_POST['procedure_sample_id'];
	 $today = date('Y-m-d H:i:s',strtotime("+0 days"));
	 sqlStatement("UPDATE procedure_sample SET sample_received_by ='".$_SESSION["authUser"]."', received=1, sample_received='". $today ."' WHERE procedure_sample_id='".$_POST['procedure_sample_id']."'");
	 sqlStatement("UPDATE procedure_order_code SET sample_receive=1 where procedure_code='".$_POST["procedure_code"]."' and procedure_order_id='".$orderid."'");
		}
   /*$status="collected";
   sqlStatement("UPDATE procedure_order SET order_status='".$status."' WHERE procedure_order_id='".$orderid."'");*/
   
   $address = "{$GLOBALS['rootdir']}/main/finder/p_dynamic_finder_lab.php";
echo"<script type='text/javascript'>top.restoreSession();window.location='$address';</script>";
		
	}
 }
?>

<html>
<head>
<style>
table td {
    border: 0px solid #eee;
}
.level1 td:first-child {
    padding-left: 15px;
}
.level2 td:first-child {
    padding-left: 30px;
}


.toggle {
    height: 9px;
    width: 9px;
    display: inline-block;   
}

</style>
<?php html_header_show();?>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript">
$(function() {
    $('#mytable').on('click', '.toggle', function () {
        //Gets all <tr>'s  of greater depth
        //below element in the table
        var findChildren = function (tr) {
            var depth = tr.data('depth');
            return tr.nextUntil($('tr').filter(function () {
                return $(this).data('depth') <= depth;
            }));
        };

        var el = $(this);
        var tr = el.closest('tr'); //Get <tr> parent of toggle button
        var children = findChildren(tr);

        //Remove already collapsed nodes from children so that we don't
        //make them visible. 
        //(Confused? Remove this code and close Item 2, close Item 1 
        //then open Item 1 again, then you will understand)
        var subnodes = children.filter('.expand');
        subnodes.each(function () {
            var subnode = $(this);
            var subnodeChildren = findChildren(subnode);
            children = children.not(subnodeChildren);
        });

        //Change icon and hide/show children
        if (tr.hasClass('collapse')) {
            tr.removeClass('collapse').addClass('expand');
            children.hide();
        } else {
            tr.removeClass('expand').addClass('collapse');
            children.show();
        }
        return children;
    });
});
</script>
</head>
<body class="body_top">
 <center><h2 style='font-family:lucida calligraph'><?php echo xlt('Sample Received'); ?></h2></center>
</br>

<form method="post" action="<?php echo $GLOBALS['rootdir'] ?>/main/finder/specimen_received.php" name="my_form" id="my_form">
<table style="border-style: groove">
<?php
/*$order=sqlStatement("SELECT * from procedure_sample where procedure_order_id='".$orderid."'");
	  $order1=sqlFetchArray($order);
	 if($order1!=0)
		{
			  echo "The Sample is Already Collected";
	         exit;
			  
			 
		}else*/
?>
<tr>
<td align="left"><?php echo xlt('Patient Name' ); ?>:</td>
		<td>
			<label> <?php if (is_numeric($pid)) {
    
    $result = getPatientData($pid, "genericname1,fname,lname,squad");
   echo text($result['fname'])." ".text($result['lname']);
   }
   $patient_name=($result['fname'])." ".($result['lname']);
   ?>
   </label>
   <input type="hidden" name="client_name" value="<?php echo attr($patient_name);?>">
   <input type="hidden" name="orderid" id="orderid" value="<?php echo attr($orderid);?>">
		</td>
		</tr>
		<tr>
	<td align="left"><?php echo xlt('Patient ID' ); ?>:</td>
	 <td> <?php echo text($result['genericname1']) ?></td>
	</tr>
	<tr>
	<td align="left"><?php echo xlt('Order ID' ); ?>:</td>
	 <td> <?php echo attr($orderid) ?></td>
	</tr>	
</table><hr>
<table border="0" id="mytable">
<tr>
<td>Sample ID</td>	
<td>Test ID</td>		
<td>Test Name</td>	
<td>Specimen</td>	
<td>Collected Date</td>
<td>Received</td>
</tr>	
<?php 
        $order=sqlStatement("Select * from procedure_sample a,procedure_order_code b where a.procedure_code=b.procedure_code and a.procedure_order_id='".$orderid."' and a.collected=1 group by a.procedure_sample_id");
		$today = date('Y-m-d H:i:s',strtotime("+0 days"));
	    while ($order3 = sqlFetchArray($order))
		{
			$ordersample=sqlStatement("SELECT procedure_code from procedure_sample where procedure_order_id='".$orderid."'");
			$ordersample1=sqlFetchArray($ordersample);
			
		?>
		<input type="hidden" name="procedure_code" id="procedure_code" value="<?php echo text($order3['procedure_code']);?>">
			<input type="hidden" name="procedure_sample_id" id="procedure_sample_id" value="<?php echo text($order3['procedure_sample_id']);?>">
		 <tr data-depth="0" class="collapse level0">
		 <td> <span class="toggle"></span> <?php echo text($order3['procedure_sample_id'])?></td>
		 <td> <?php echo text($order3['procedure_code'])?></td>
		 <td> <?php echo text($order3['procedure_name'])?></td>
		 <td> <?php echo text($order3['specimen'])?></td>
		 <td> <?php echo text(date('d/M/y h:i:s A',strtotime($order3['sample_date_collected'])))?></td>
		 <td>
		<?php if($order3['received']==1)
		{
			 
			    echo text(date('d/M/y h:i:s A',strtotime($order3['sample_received'])));
		}else
        {
					
		echo "<input type='checkbox' name='value_code[]' value=" . attr($order3['procedure_code']) . " ";
		if($value_select){
			if (in_array($order3['procedure_code'], $value_select)){ echo "checked='checked' ";}
		}
		}?>
		</td>
			</tr> 
			
		<?php }?>
		</table>
		
		
	<p>
		
    <input type='submit'  id="submit" name="submit" value='<?php echo xlt('Save');?>' class="button-css">&nbsp;
	<input type='button' class="button-css" value='<?php echo xlt('Cancel');?>'
 onclick="top.restoreSession();location='<?php echo "{$GLOBALS['rootdir']}/main/finder/p_dynamic_finder_lab.php"?>'" />

 
</body>
</form>
</html>