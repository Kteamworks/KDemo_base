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
	  if ($aColumns[$iSortCol] == 'provider') {
        $orderby .= "provider_id";
      }
      else 

      if ($aColumns[$iSortCol] == 'name') {
        $orderby .= "lname $sSortDir, fname $sSortDir, mname $sSortDir";
      }
      else {
        $orderby .= "`" . escape_sql_column_name($aColumns[$iSortCol],array('patient_data','form_encounter','users','openemr_postcalendar_categories')) . "` $sSortDir";
      }
		}
	}
}

// Global filtering.
//
$where = '';
if (isset($_GET['sSearch']) && $_GET['sSearch'] !== "") {
  $sSearch = add_escape_custom($_GET['sSearch']);
  foreach ($aColumns as $colname) {
    $where .= $where ? "OR " : "WHERE ( ";
    if ($colname == 'name') {
      $where .=
        "lname LIKE '$sSearch%' OR " .
        "fname LIKE '$sSearch%' OR " .
        "mname LIKE '$sSearch%' ";
    }
    else {
      $where .= "`" . escape_sql_column_name($colname,array('patient_data','form_encounter','users','openemr_postcalendar_categories')) . "` LIKE '$sSearch%' ";
    }
  }
  if ($where) $where .= ")";
}

// Column-specific filtering.
//
for ($i = 0; $i < count($aColumns); ++$i) {
  $colname = $aColumns[$i];
  if (isset($_GET["bSearchable_$i"]) && $_GET["bSearchable_$i"] == "true" && $_GET["sSearch_$i"] != '') {
    $where .= $where ? ' AND' : 'WHERE';
    $sSearch = add_escape_custom($_GET["sSearch_$i"]);
    if ($colname == 'name') {
      $where .= " ( " .
        "lname LIKE '$sSearch%' OR " .
        "fname LIKE '$sSearch%' OR " .
        "mname LIKE '$sSearch%' )";
    }
    else {
      $where .= " `" . escape_sql_column_name($colname,array('patient_data','form_encounter','users','openemr_postcalendar_categories')) . "` LIKE '$sSearch%'";
    }
  }
}

// Compute list of column names for SELECT clause.
// Always includes pid because we need it for row identification.
//
$sellist = 'a.pid,b.id';
foreach ($aColumns as $colname) {
  if ($colname == 'pid') continue;
  if ($colname == 'id') continue;

  $sellist .= ", ";
  if($colname== 'provider'){
    $sellist .="provider_id";
	
  }else if ($colname == 'name') {
    $sellist .= "lname, fname, mname";
  }else
  {
    $sellist .= "`" . escape_sql_column_name($colname,array('patient_data','form_encounter','users','openemr_postcalendar_categories')) . "`";
  }
}

$user=  $_SESSION["authUser"];
$row1 = sqlStatement("SELECT id,newcrop_user_role,specialty,facility_id from users where username='".$user."'");
$row2=  sqlFetchArray($row1);
$providerid=$row2['id'];
$specialty=$row2['specialty'];

$facilityid=$row2['facility_id'];
$i=0;
$new=sqlStatement("SELECT * from users where specialty='".$specialty."' and authorized=1");
$new2=sqlStatement("SELECT count(*) as c from users where specialty='".$specialty."' and authorized=1");
$co=sqlFetchArray($new2);
$co=$co['c'];
//$row3=sqlFetchArray($new);
$X=array();
 while($row3=sqlFetchArray($new))
{
  $X[]=$row3['id'];
	
}
$X = "'" . implode("', '", $X) . "'";

//$X='63,118';
$today = date('Y-m-d',strtotime("+0 days"));
$provider_name=sqlStatement("SELECT a.provider_id,b.username from form_encounter a,users b where a.provider_id=b.id");
$provider_name_1=  sqlFetchArray($provider_name);
$get_provider_name=$provider_name_1['username'];
// Get total number of rows in the table.
//
if($row2["newcrop_user_role"]=="erxnurse")
{
$row = sqlQuery("SELECT COUNT(b.id) AS count FROM form_encounter a,patient_data b where a.pid=b.pid  and date(a.date)='".$today."'");
}
else
{
$row = sqlQuery("SELECT COUNT(b.id) AS count FROM form_encounter a,patient_data b where a.pid=b.pid  and date(a.date)='".$today."'");
}

$iTotal = $row['count'];

// Get total number of rows in the table after filtering.
//$row = sqlQuery("SELECT COUNT(b.id) AS count FROM form_encounter a,patient_data b where a.pid=b.pid and a.provider_id='".$providerid."' and a.date='".$today."'");

$iFilteredTotal = $row['count'];

// Build the output data array.
//
$out = array(
  "sEcho"                => intval($_GET['sEcho']),
  "iTotalRecords"        => $iTotal,
  "iTotalDisplayRecords" => $iFilteredTotal,
  "aaData"               => array()
);
if($row2["newcrop_user_role"]=="erxnurse")
{
$query ="SELECT $sellist FROM patient_data a,form_encounter b ,openemr_postcalendar_categories c where a.pid=b.pid and c.pc_catid=b.pc_catid and date(b.date)='".$today."' order by encounter desc  $limit";
}
else 
{
$query = "SELECT $sellist FROM patient_data a,form_encounter b ,openemr_postcalendar_categories c where a.pid=b.pid and c.pc_catid=b.pc_catid and date(b.date)='".$today."' order by encounter desc  $limit";
}
$res = sqlStatement($query);
while ($row = sqlFetchArray($res)) {
	$pid = $row['pid'];
	$encounter = $row['encounter'];
  //$bill_qry = "SELECT * from billing WHERE pid=? AND encounter=?";
  //$billed_amount="select sum(fee) from billing where activity=1 and encounter=? and pid=?";
  $paid1 ="Select coalesce(sum(fee),0) fee,(select coalesce(sum(amount1+amount2),0) paid from payments where encounter=b.encounter and activity=1)paid
from billing b
where b.encounter=? and b.pid=?
 and b.activity=1 and b.code_type in ('Doctor Charges','Scans')";
  
 $billed = sqlStatement($paid1, array($encounter,$pid));
  // Each <tr> will have an ID identifying the patient.
//  $arow = array('DT_RowId' => 'pid_' . $row['pid']);
while($data = sqlFetchArray($billed)) {
    if($data['paid'] == 0 && $data['fee'] > 0) {
     $arow = array('DT_RowId' => 'pid_' . $row['pid'],'DT_RowClass' => 'PT_UNBIILLED');
  }
 
    else {
  $arow = array('DT_RowId' => 'pid_' . $row['pid']);
  }
}
  foreach ($aColumns as $colname) {
	   if($colname=='provider'){
	$providerid=$row['provider_id'];
	$rsq=sqlStatement("select username from users where id='".$providerid."'");
	  $prname=sqlFetchArray($rsq);
	  $prname=$prname['username'];
	    
		$provider=$prname;
     
	  //if ($provider && $row['out_time']) $provider .= '@';
      //if ($row['out_time']) $provider .= $row['out_time'];
	  
	$arow[] = $provider;
	}else
    if ($colname == 'name') {
      $name = $row['fname'];
      if ($name && $row['lname']) $name .= '  ';
      if ($row['lname']) $name .= $row['lname'];
      if ($row['mname']) $name .= ' ' . $row['mname'];
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
