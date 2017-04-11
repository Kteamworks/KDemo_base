<?php
/************************************************************************
  			aptient.php - Copyright duhlman

/usr/share/apps/umbrello/headings/heading.php

This file was generated on %date% at %time%
The original location of this file is /home/duhlman/uml-generated-code/prescription.php
**************************************************************************/

require_once("ORDataObject.class.php");
require_once("Provider.class.php");
/**
 * class Patient
 *
 */

class Patient extends ORDataObject{
	var $genericname1;
	var $id;
	var $pubpid;
	var $lname;
	var $mname;
	var $fname;
	var $date_of_birth;
	var $provider;
    var $title;
	/**
	 * Constructor sets all Prescription attributes to their default value
	 */
	function Patient($id = "")	{
		$this->id = $id;
		$this->_table = "patient_data";
		$this->pubpid = "";
		$this->title = "";
		$this->genericname1 = "";
		$this->lname = "";
		$this->mname = "";
		$this->fname = "";
		$this->dob 	 = "";
		$this->provider = new Provider();
		$this->populate();


	}
	function populate() {
		if (!empty($this->id)) {
			$res = sqlQuery("SELECT providerID,fname,lname,mname ".
                                        ", DATE_FORMAT(DOB,'%m/%d/%Y') as date_of_birth ".
                                        ", pubpid ,genericname1,title".
                                        " from " . $this->_table ." where pid =". mysql_real_escape_string($this->id));
			if (is_array($res)) {
				$this->title = $res['title'];
				$this->pubpid = $res['pubpid'];
				$this->lname = $res['lname'];
				$this->mname = $res['mname'];
				$this->fname = $res['fname'];
				$this->genericname1=$res['genericname1'];
				$this->provider = new Provider($res['providerID']);
				$this->date_of_birth =text(date('d/M/y',strtotime($res['date_of_birth'])));
			}
		}
	}
	function get_id() { return $this->id; }
	function get_pubpid() { return $this->pubpid; }
	function get_genericname1() { return $this->genericname1; }
	function get_lname() { return $this->lname; }
	function get_name_display() { return $this->title." ".$this->fname . " " .$this->mname." ". $this->lname; }
	function get_provider_id() { return $this->provider->id; }
	function get_provider() { return $this->provider; }
	function get_dob () { return $this->date_of_birth; }

} // end of Patient
?>
