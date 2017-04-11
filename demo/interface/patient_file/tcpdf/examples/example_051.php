<?php
//============================================================+
// File name   : example_051.php
// Begin       : 2009-04-16
// Last Update : 2013-05-14
//
// Description : Example 051 for TCPDF class
//               Full page background
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Full page background
 * @author Nicola Asuni
 * @since 2009-04-16
 */
$sanitize_all_escapes=true;
$fake_register_globals=false;

//



require_once("../../../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/classes/Address.class.php");
require_once("$srcdir/classes/InsuranceCompany.class.php");
require_once("$srcdir/classes/Document.class.php");
require_once("$srcdir/options.inc.php");
require_once("../../history/history.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/edi.inc");
require_once("$srcdir/invoice_summary.inc.php");
require_once("$srcdir/clinical_rules.php"); 
// Include the main TCPDF library (search for installation path).
require_once("tcpdf_include.php");

if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }

  $active_reminders = false;
  if ((!isset($_SESSION['alert_notify_pid']) || ($_SESSION['alert_notify_pid'] != $pid)) && isset($_GET['set_pid']) && acl_check('patients', 'med') && $GLOBALS['enable_cdr'] && $GLOBALS['enable_cdr_crp']) {
    // showing a new patient, so check for active reminders
    $active_reminders = active_alert_summary($pid,"reminders-due");
  }
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
		// set bacground image
		//$img_file = K_PATH_IMAGES.'image_demo.jpg';
		$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
		
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
	}
}
function pic_array($pid,$picture_directory) {
	global $pid, $web_root;
    $pics = array();
    $sql_query = "select documents.id,documents.url from documents join categories_to_documents " .
                 "on documents.id = categories_to_documents.document_id " .
                 "join categories on categories.id = categories_to_documents.category_id " .
                 "where categories.name like ? and documents.foreign_id = ?";
    if ($query = sqlStatement($sql_query, array($picture_directory,$pid))) {
      while( $results = sqlFetchArray($query) ) {
            array_push($pics,$results['id']);
        }
      }
    return ($pics);
}


function pic_url($pid,$picture_directory) {
	global $pid, $web_root;
    $pics = array();
    $sql_query = "select documents.id,documents.url from documents join categories_to_documents " .
                 "on documents.id = categories_to_documents.document_id " .
                 "join categories on categories.id = categories_to_documents.category_id " .
                 "where categories.name like ? and documents.foreign_id = ?";
    if ($query = sqlStatement($sql_query, array($picture_directory,$pid))) {
      while( $results = sqlFetchArray($query) ) {
            array_push($pics,$results['url']);
        }
      }
    return ($pics);
}

$pdf = new MYPDF('L', 'mm', array(65,110),true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Chandni Joshi');
$pdf->SetTitle('Id Card Example');
$pdf->SetSubject('Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);         
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
$pdf->SetFont('times', '', 18);
$pdf->AddPage();
$pdf->setPrintHeader(false);
$pdf->setJPEGQuality(100);
// get the current page break margin
$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
//$auto_page_break = $pdf->getAutoPageBreak(true);

// disable auto-page-break
$pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
// set bacground image






$img_file = K_PATH_IMAGES.'id2.jpg';
$pdf->Image($img_file, 0,0, 0, 0, '', '', '', false, 100, '', false, false, 0);
//$pid=2730;
$photos = pic_array($pid, $GLOBALS['patient_photo_category_name']);
foreach ($photos as $photo_doc_id) {
	$doc_id=$photo_doc_id;
}

$photosurl = pic_url($pid, $GLOBALS['patient_photo_category_name']);
foreach ($photosurl as $photo_url_id) {
	$url_id=$photo_url_id;
}
$docobj = new Document($doc_id);
        $image_file = $docobj->get_url_file();
        $extension = substr($image_file, strrpos($image_file,"."));	
$r1=getPatientData($pid);
		$fname=$r1['fname'];
		$mname=$r1['mname'];
		$lname=$r1['lname'];
		$bgrp=$r1['genericval1'];
		$s1=$r1['street'];
		$s2=$r1['city'];
		$s3=$r1['state'];
		$db=$r1['DOB'];
		
		
		//

	    

		$db1 = date("d-m-Y", strtotime($db));
		
		//$end=date('d-m-Y', strtotime(date("Y-m-d H:i:s", strtotime($db1)) . " +1 year"));
		$end = date('d-m-Y',strtotime("+1 years"));
		//$validdate = strtotime ( '+1 years' , strtotime ( $db ) ) ;
		//$end = date ( 'd-m-Y' , $validate );
		$id=$r1['genericname1'];
		$addr=$s1.' '.$s2.' '.$s3;
		$name=$fname.' '.$mname.' '.$lname;
		//$end = date('d-m-Y',strtotime("+1 years"));
$id = <<<EOT
    
       <span align="left" style="font-size: 60%; color: #ffffff;"><br/><b>$id</b></span>
    
EOT;
$cred = <<<EOT
    
       <span align="left" style="font-size: 60%; color: #ffffff;"><br/><b>Name: $name</b><br/><b>Blood Group: $bgrp</b><br/><b>DOB: $db1</b><br/><b>Valid Upto: $end</b><br/><b>Address: $addr</b></span>
    
EOT;
$extension=' .jpg';
$pdf->writeHTMLCell(90, 20, 3, 28.9, $cred);
$abc=<<<EOT
    
       <span align="left" style="font-size: 60%; color: #ffffff;"><br/><b><img src = $web_root" . "/controller.php?document&retrieve&patient_id=$pid&document_id=$doc_id" . " width=85 height=54 alt='$doc_catg:$image_file'></img></b></span>
    
EOT;
$pdf->writeHTMLCell(90, 20, 3, 1, $id);

$pdf->Image($url_id,85, 07, 25, 25, 'JPG', '', '', false, 300, '', false, false, 0, 'T', false, false);////right,above,,left
$pdf->AddPage();
$img_file = K_PATH_IMAGES.'id_back.jpg';
// print a line using Cell()
$pdf->Cell(0, 0, 'PAGE 3', 1, 1, 'C');
$pdf->Image($img_file, 0,0, 0, 0, '', '', '', false, 100, '', false, false, 0);

//$imgsrc="file://C:/xampp/htdocs/kavaii/sites/default/documents/2447/CCI07062015_0003.jpg";
//$pdf->Image($url_id,85, 07, 25, 25, 'JPG', '', '', false, 300, '', false, false, 0, 'T', false, false);////right,above,,left
$pdf->Output('ID_card.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
