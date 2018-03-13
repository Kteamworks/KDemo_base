<?php
// Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/report.inc");
$user =  $_SESSION['authUser'];
$list = sqlStatement("select p.pid,p.encounter,p.dtime,pd.fname,pd.phone_cell,pd.genericname1 from payments p join
                     patient_data pd on p.pid=pd.pid where p.user='$user' and p.stage='pharm' group by p.encounter order by dtime desc" );
   

?>


<html>

<head>


<link rel="stylesheet" href="dataTableCSS/bootstrap.min.css">
 <link rel="stylesheet" href="http://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
 

 
<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title></title>



<script type="text/javascript" src="../../library/dialog.js"></script>

<script src="PluginsDataTable/jquery-1.12.4.js"></script>
	<script src="PluginsDataTable/jquery.dataTables.min.js"></script>
	<script src="PluginsDataTable/dataTables.bootstrap.min.js"></script>
	
	<script>
	$(document).ready(function() {
    $('#example').DataTable();

     } );
   </script>



</head>

<body class="body_top">
<form method='post' action=''>
<h2 align='center' style="background-color:powderblue;">Sale History</h2>	
<table id="example"  class="table table-striped table-responsive" width="100%">
 <thead>
 <tr class='head'>
  
  <th style="text-align:left">
   Name
  </th>
   <th style="text-align:left">
   Patient ID
  </th>
  
  <th style="text-align:left">
   Visit ID
  </th>
  
  <th style="text-align:left">
   Date
  </th>
  
 </tr>
  </thead>
  <tbody>
  <?php  while ($patient_list = sqlFetchArray($list)) { 
  $dtime =  $patient_list['dtime'];
  $newDate = date("d-M-Y", strtotime($dtime));
  ?>
  <tr>
  <td> <a href="pharmacy_duplicate_bill.php?id=<?php echo $patient_list['pid']; ?>&visit=<?php echo $patient_list['encounter']; ?>"><?php echo $patient_list['fname'];   ?></a></td>
  <td><?php echo $patient_list['pid'];   ?></td>
  <td><?php echo $patient_list['encounter'];   ?></td>
  <td><?php echo $newDate;   ?></td>
  
  </tr>
  <?php   }  ?>

</tbody>

</table>


</form>
</body>
</html>
