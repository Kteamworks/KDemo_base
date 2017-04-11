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
	     sqlInsert("INSERT into procedure_sample SET
	 collected =1,
    collected_by = '" . $_SESSION["authUser"] . "', 
	 procedure_code         = '" . add_escape_custom($_POST["procedure_code"]) . "',
    sample_date_collected         = '" . add_escape_custom($_POST["sample_date_collected"]) . "',
  specimen         = '" . add_escape_custom($_POST["specimen"]) . "',
   procedure_order_id          = '" . add_escape_custom($orderid) . "'");
   sqlStatement("UPDATE procedure_order_code SET sample_collected=1 where procedure_code='".$_POST["procedure_code"]."' and procedure_order_id='".$orderid."'");
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
 <center><h2 style='font-family:lucida calligraph'><?php echo xlt('Sample Collection'); ?></h2></center>
</br>

<form method="post" action="<?php echo $GLOBALS['rootdir'] ?>/main/finder/specimen_collected.php" name="my_form" id="my_form">
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
<td>Test ID</td>		
<td>Test Name</td>	
<td>Specimen</td>	
<td>Collected Date</td>
<td>Collected</td>
</tr>	
<?php 
        $order=sqlStatement("Select * from procedure_order_code  a, procedure_type b where a.procedure_code=b.procedure_code and a.procedure_order_id='".$orderid."' group by a.procedure_order_seq");
		$today = date('Y-m-d H:i:s',strtotime("+0 days"));
	    while ($order3 = sqlFetchArray($order))
		{
			$ordersample=sqlStatement("SELECT * from procedure_sample where procedure_code='".$order3['procedure_code']."' and procedure_order_id='".$orderid."'");
			$ordersample1=sqlFetchArray($ordersample);
			
		?>
		 <tr data-depth="0" class="collapse level0">
		 
		 <td id="procedure_code"> <span class="toggle"></span> <?php echo text($order3['procedure_code']) ;?>
		 <input type="hidden" name="procedure_code" id="procedure_code" value="<?php echo text($order3['procedure_code']);?>">
		 </td>
		 <td> <?php echo text($order3['procedure_name'])?></td>
		
		<td><textarea name="specimen" rows="2" cols="20" wrap="virtual name"><?php echo $order3['specimen'] ;?></textarea></td>
		<td class="forms">
		<?php if($ordersample1!=0)
		{
			    echo text(date('d/M/y h:i:s A',strtotime($ordersample1['sample_date_collected'])));
		}else
        {?>
			   <input type='text' size='10' name='sample_date_collected' id='sample_date_collected' <?php echo attr ($disabled)?>;
       value='<?php echo attr($today); ?>'
       title='<?php echo xla('yyyy-mm-dd Date of Collected'); ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
        <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22'
        id='img_transfer_date' border='0' alt='[?]' style='cursor:pointer;cursor:hand'
        title='<?php echo xla('Click here to choose a date'); ?>'>
		</td>
		
		<script language="javascript">
/* required for popup calendar */
Calendar.setup({inputField:"sample_date_collected", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_transfer_date",showsTime:'true'});

</script>
<?php }?>
		<td>
		<?php 
		if($ordersample1!=0)
		{
			    echo text($ordersample1['collected_by']);
		}else
        {
		echo "<input type='checkbox' name='value_code[]' value=" . attr($order3['procedure_code']) . " ";
		if($value_select){
			if (in_array($order3['procedure_code'], $value_select)){ echo "checked='checked' ";}
		}}?>
		</td>
			</tr> 
			<?php  }?>
			<!--
			<?php //if($order3['procedure_name']=="PPBS"){$specimen1="Urine";?>
			<tr data-depth="1" class="collapse level1">
			<td> <input type="hidden" name="procedure_code1" id="procedure_code1" value="<?php //echo text($order3['procedure_code'])?>"></td>
			<td></td>
		<td><span class="toggle"></span><textarea name="specimen1" rows="2" cols="20" wrap="virtual name"><?php //echo $specimen1 ;?></textarea></td>
    </tr>-->
		
		</table>
		
		
	<p>
		
    <input type='submit'  id="submit" name="submit" value='<?php echo xlt('Save');?>' class="button-css">&nbsp;
	<input type='button' class="button-css" value='<?php echo xlt('Cancel');?>'
 onclick="top.restoreSession();location='<?php echo "{$GLOBALS['rootdir']}/main/finder/p_dynamic_finder_lab.php"?>'" />

 
</body>
</form>
</html>