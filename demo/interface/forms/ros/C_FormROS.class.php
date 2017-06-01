<?php

require_once ($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once ($GLOBALS['fileroot'] . "/library/forms.inc");
require_once("FormROS.class.php");

class C_FormROS extends Controller {

	var $template_dir;
	
    function C_FormROS($template_mod = "general") {
    	parent::Controller();
		$id=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='vitals' order by form_id desc limit 1 ");
		$id1=sqlFetchArray($id);
		$id2=$id1['form_id'];
    	$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
    	$this->template_mod = $template_mod;
    	$this->template_dir = dirname(__FILE__) . "/templates/ros/";
    	$this->assign("FORM_ACTION", $GLOBALS['web_root']);
    	$this->assign("DONT_SAVE_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/$returnurl");
		$this->assign("NEXT_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=procedure_order");
    	$this->assign("BACK_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=vitals&id=$id2");
    	
		$this->assign("STYLE", $GLOBALS['style']);
    }
    
    function default_action() {
    	$ros = new FormROS();
    	$this->assign("form",$ros);
		return $this->fetch($this->template_dir . $this->template_mod . "_new.html");
	}
	
	function view_action($form_id) {
		
		if (is_numeric($form_id)) {
    		$ros = new FormROS($form_id);
    	}
    	else {
    		$ros = new FormROS();
    	}
    	
    	$this->assign("form",$ros);
    	return $this->fetch($this->template_dir . $this->template_mod . "_new.html");

	}
	
	function default_action_process() {
		if ($_POST['process'] != "true"){
					
			return;
		}
		$this->ros = new FormROS($_POST['id']);
		
		parent::populate_object($this->ros);
		$this->ros->persist();
		
		if ($GLOBALS['encounter'] == "") {
			$GLOBALS['encounter'] = date("Ymd");
		}
		if(empty($_POST['id']))
		{
			addForm($GLOBALS['encounter'], "Review Of Systems", $this->ros->id, "ros", $GLOBALS['pid'], $_SESSION['userauthorized']);
			$_POST['process'] = "";
		}
		return;
	}
    
}



?>
