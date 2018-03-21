<?php

require_once ($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once ($GLOBALS['fileroot'] . "/library/forms.inc");

require_once("FormROS.class.php");

class C_FormROS extends Controller {

	var $template_dir;
	
    function C_FormROS($template_mod = "general") {
    	parent::Controller();
		$vid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='vitals' order by form_id desc limit 1 ");
		$vid1=sqlFetchArray($vid);
		$vid2=$vid1['form_id'];
				$nvid=sqlStatement("SELECT id from form_encounter where encounter='".$_SESSION['encounter']."'");

  $nvid1=sqlFetchArray($nvid);

 $nvid2=$nvid1['id'];
    	$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
    	$this->template_mod = $template_mod;
    	$this->template_dir = dirname(__FILE__) . "/templates/ros/";
    	$this->assign("FORM_ACTION", $GLOBALS['web_root']);
		$res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
		if($res['newcrop_user_role'] != 'erxnurse' && $res['newcrop_user_role'] != 'erxdoctor' ){
    	$this->assign("DONT_SAVE_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/$returnurl");
		}else
		{
		$this->assign("DONT_SAVE_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=newpatient&id=". $nvid2);
		}
		$this->assign("NEXT_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=procedure_order");
    	$this->assign("BACK_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=vitals&id=$id2");
    	    $this->assign("VISIT_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=newpatient&id=". $nvid2);
		 if($vid2 == null) {
		    $this->assign("VITALS_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=vitals");
			} else { 
			 $this->assign("VITALS_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=vitals&id=".$vid2);
		} 
		
		    $this->assign("ROS_LINK",$GLOBALS['webroot'] . "/interface/patient_file/summary/stats_full.php");
			
		$this->assign("LAB_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=procedure_order");
		$this->assign("prescription_LINK",$GLOBALS['webroot'] . "/controller.php?prescription&edit&id=&pid=".$_SESSION['pid']);
		if($plid2 == null) {
			$this->assign("plan_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=dictation");
		} else {
			$this->assign("plan_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=formname=dictation&id=".$plid2);
		}
		$this->assign("referral_LINK",$GLOBALS['webroot'] . "/interface/patient_file/transaction/add_transaction.php");
		$this->assign("admission_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/admit_doctor_form.php");
		$this->assign("summary_LINK",$GLOBALS['webroot'] . "/interface/patient_file/summary/summary_print.php");
		$this->assign("redirect_LINK",$GLOBALS['webroot'] . "/interface/patient_file/summary/nurse_checkout.php");
		$this->assign("STYLE", $GLOBALS['style']);
		if($res['newcrop_user_role'] == 'erxnurse'){
		$this->assign("DISPLAYNONE", "display:none");
		}
		if($res['newcrop_user_role'] != 'erxnurse' && $res['newcrop_user_role'] != 'erxdoctor' ){
		$this->assign("DISPLAYNONE1", "display:none");
		}
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
