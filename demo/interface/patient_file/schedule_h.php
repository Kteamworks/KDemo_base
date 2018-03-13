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
$list = sqlStatement("select * from billing  where schedule_h=1" );
   

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
<h2 align='center' style="background-color:powderblue;">Schedule H Drugs Sale </h2>
<table id="example"  class="table table-striped  cellspacing="0" width="100%">
 <thead>
 <tr class='head'>
  
  <th style="text-align:left">
   Medicine 
  </th>
   <th style="text-align:left">
   Patient 
  </th>
  
  <th style="text-align:left">
   Date
  </th>
  
  <th style="text-align:left">
   Price
  </th>
  
 </tr>
  </thead>
  <tbody>
  <?php  while ($patient_list = sqlFetchArray($list)) { 
  $pat_id = $patient_list['pid'];
  $pname = sqlQuery("select fname from patient_data where pid ='$pat_id'  ");
  
  $dtime =  $patient_list['date'];
  $newDate = date("d-M-Y", strtotime($dtime));
  ?>
  <tr>
  <td> <?php echo$patient_list['code_text'];  ?></a></td>
  <td><?php echo $pname['fname'];   ?></td>
  <td><?php echo $newDate;   ?></td>
  <td><?php echo $patient_list['fee'];   ?></td>
  
  </tr>
  <?php   }  ?>

</tbody>

</table>


</form>
</body>
</html>
