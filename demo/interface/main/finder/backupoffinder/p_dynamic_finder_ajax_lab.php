<?php
// Copyright (C) 2012 Rod Roark <rod@sunsetsystems.com>
// Sponsored by David Eschelbacher, MD
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// Sanitize escapes and disable fake globals registration.
//
$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/jsonwrapper/jsonwrapper.php");

$popup = empty($_REQUEST['popup']) ? 0 : 1;

// With the ColReorder or ColReorderWithResize plug-in, the expected column
// ordering may have been changed by the user.  So we cannot depend on
// list_options to provide that.
//
$aColumns = explode(',', $_GET['sColumns']);

// Paging parameters.  -1 means not applicable.
//
$iDisplayStart  = isset($_GET['iDisplayStart' ]) ? 0 + $_GET['iDisplayStart' ] : -1;
$iDisplayLength = isset($_GET['iDisplayLength']) ? 0 + $_GET['iDisplayLength'] : -1;
$limit = '';
if ($iDisplayStart >= 0 && $iDisplayLength >= 0) {
  $limit = "LIMIT " . escape_limit($iDisplayStart) . ", " . escape_limit($iDisplayLength);
}

// Column sorting parameters.
//
$orderby = '';
if (isset($_GET['iSortCol_0'])) {
	for ($i = 0; $i < intval($_GET['iSortingCols']); ++$i) {
    $iSortCol = intval($_GET["iSortCol_$i"]);
		if ($_GET["bSortable_$iSortCol"] == "true" ) {
      $sSortDir = escape_sort_order($_GET["sSortDir_$i"]); // ASC or DESC
      // We are to sort on column # $iSortCol in direction $sSortDir.
      $orderby .= $orderby ? ', ' : ' ';
      //
	 
      if ($aColumns[$iSortCol] == 'name') {
	   $orderby .= "status,genericname1";
	  
       
      }
      else {
        $orderby .= "`" . escape_sql_column_name($aColumns[$iSortCol],array('patient_data','form_encounter','procedure_order','procedure_order_code')) . "` $sSortDir";
      }
		}
	}
}

// Global filtering.
//
$where = '';
if (isset($_GET['sSearch']) && $_GET['sSearch'] !== "") {


  foreach ($aColumns as $colname) {
    $where .= $where ? "OR " : "WHERE ( ";
    if ($colname == 'name') {
      $where .=
        "fname LIKE '$sSearch%' OR " .
        "mname LIKE '$sSearch%' OR " .
        "lname LIKE '$sSearch%' ";
    }
    else {
      $where .= "`" . escape_sql_column_name($colname,array('patient_data','form_encounter','procedure_order','procedure_order_code')) . "` LIKE '$sSearch%' ";
    }
  }
  if ($where) $where .= ")";
}

// Column-specific filtering.
//
for ($i = 0; $i < count($aColumns); ++$i) {
  $colname = $aColumns[$i];
  $sSearch = add_escape_custom($_GET['sSearch']);
  if (isset($_GET["bSearchable_$i"]) && $_GET["bSearchable_$i"] == "true" && $_GET["sSearch_$i"] != '') {
    $where .= $where ? ' AND' : 'WHERE';
    $sSearch = add_escape_custom($_GET["sSearch_$i"]);
    if ($colname == 'name') {
      $where .= " ( " .
        "fname LIKE '$sSearch%' OR " .
        "mname LIKE '$sSearch%' OR " .
        "lname LIKE '$sSearch%' )";
    }
	
    else {
      $where .= " `" . escape_sql_column_name($colname,array('patient_data','form_encounter','procedure_order','procedure_order_code')) . "` LIKE '$sSearch%'";
    }
  }
}

// Compute list of column names for SELECT clause.
// Always includes pid because we need it for row identification.
//
$sellist = 'a.pid,a.encounter,c.order_status,c.procedure_order_id,count(d.procedure_order_id) total_tests,count(d.sample_collected) sample_collected';

foreach ($aColumns as $colname) {
  if ($colname == 'pid') continue;
  if($colname=='encounter') continue;
  if($colname=='order_status') continue;
   if($colname=='procedure_order_id') continue;
   if($colname=='total_tests') continue;
   if($colname=='sample_collected') continue;
   
  $sellist .= ", ";
  if ($colname == 'name') {
    $sellist .= "fname, mname, lname";
  }
 
  else {
    $sellist .= "`" . escape_sql_column_name($colname,array('patient_data','form_encounter','procedure_order','procedure_order_code')) . "`";
  }
}
// Get total number of rows in the table.
//

$row = sqlQuery("SELECT COUNT(distinct(c.procedure_order_id)) AS count FROM form_encounter a,patient_data b,procedure_order  c ,procedure_order_code d where a.pid=b.pid and a.encounter=c.encounter_id and c.procedure_order_id=d.procedure_order_id and  c.order_status IN ('pending','collected','received')");

$iTotal = $row['count'];

// Get total number of rows in the table after filtering.
//


$row = sqlQuery("SELECT COUNT(distinct(c.procedure_order_id)) AS count FROM form_encounter a,patient_data b,procedure_order  c ,procedure_order_code d where a.pid=b.pid and a.encounter=c.encounter_id and c.procedure_order_id=d.procedure_order_id and c.order_status IN ('pending','collected','received')");


$iFilteredTotal = $row['count'];

// Build the output data array.
//
$out = array(
  "sEcho"                => intval($_GET['sEcho']),
  "iTotalRecords"        => $iTotal,
  "iTotalDisplayRecords" => $iFilteredTotal,
  "aaData"               => array()
);

$query ="SELECT $sellist FROM form_encounter a,patient_data b,procedure_order c,procedure_order_code d $where where a.pid=b.pid and a.encounter=c.encounter_id and c.procedure_order_id=d.procedure_order_id and a.pid=b.pid and a.encounter=c.encounter_id and c.order_status IN ('pending','collected','received')  group by a.pid,a.encounter,c.procedure_order_id  order by $orderby, c.procedure_order_id,c.order_status ,a.encounter desc  $limit";

$res = sqlStatement($query);
while ($row = sqlFetchArray($res)) {
 
  // Each <tr> will have an ID identifying the patient.
  $arow = array('DT_RowId' => 'pid_' . $row['pid']);
 
  foreach ($aColumns as $colname) {
	  
    if ($colname == 'name') {
      $name = $row['fname'];
      if ($name && $row['mname']) $name .= ' ';
      if ($row['mname']) $name .= $row['mname'];
      if ($row['lname']) $name .= ' ' . $row['lname'];
      $arow[] = $name;
    }
   else if ($colname == 'DOB' || $colname == 'regdate' || $colname == 'ad_reviewed' || $colname == 'userdate1') {
      $arow[] = oeFormatShortDate($row[$colname]);
    }
    else {
      $arow[] = $row[$colname];
    }
  }
  $out['aaData'][] = $arow;
}

// error_log($query); // debugging

// Dump the output array as JSON.
//
echo json_encode($out);
?>
