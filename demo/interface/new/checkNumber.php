<?php
require_once("../globals.php");
include_once("$srcdir/sql.inc");
$echeck="select phone_cell from patient_data where phone_cell=".$_POST['email'];
   $echk=sqlStatement($echeck);
   $ecount=sqlNumRows($echk);
  if($ecount != 0)
   {
      echo 1;
   }
   
   ?>