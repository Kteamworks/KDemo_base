<?php
require_once($GLOBALS['fileroot'] . "/interface/globals.php");
require_once ($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once ($GLOBALS['fileroot'] . "/library/forms.inc");
require_once ($GLOBALS['fileroot'] . "/library/patient.inc");
require_once($GLOBALS['srcdir'].'/encounter.inc');
require_once("FormVitals.class.php");

class C_FormVitals extends Controller {

	var $template_dir;

    function C_FormVitals($template_mod = "general") {
    	parent::Controller();
		$id=sqlStatement("SELECT id from form_encounter where encounter='".$_GET['encounter']."'");

		$id1=sqlFetchArray($id);
	$id2=$id1['id'];
	$pid=$_GET['set_pid'];
	$rid=sqlStatement("SELECT form_id from forms where encounter='".$_GET['encounter']."' and formdir='ros' order by form_id desc limit 1 ");
		$rid1=sqlFetchArray($rid);
		$rid2=$rid1['form_id'];
$vid=sqlStatement("SELECT form_id from forms where encounter='".$_GET['encounter']."' and formdir='vitals' order by form_id desc limit 1 ");
		$vid1=sqlFetchArray($vid);
		$vid2=$vid1['form_id'];
		$plid=sqlStatement("SELECT form_id from forms where encounter='".$_GET['encounter']."' and formdir='dictation' order by form_id desc limit 1 ");
		$plid1=sqlFetchArray($plid);
		$plid2=$plid1['form_id'];
		$nvid=sqlStatement("SELECT id from form_encounter where encounter='".$_GET['encounter']."'");

  $nvid1=sqlFetchArray($nvid);

 $nvid2=$nvid1['id'];
    	$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
    	$this->template_mod = $template_mod;
    	$this->template_dir = dirname(__FILE__) . "/templates/vitals/";
    	$this->assign("FORM_ACTION", $GLOBALS['web_root']);
    	$this->assign("DONT_SAVE_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/$returnurl");
		$this->assign("NEXT_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=ros");
		$this->assign("BACK_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=newpatient&id=". $id2);
	    $this->assign("VISIT_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=newpatient&id=". $id2);
		    $this->assign("VITALS_LINK",$GLOBALS['webroot'] . "/interface/patient_file/summary/stats_full.php");
			
		if($rid2 == null) {
		    $this->assign("ROS_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=ros");
			} else { 
			 $this->assign("ROS_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=ros&id=".$rid2);
		}
		$this->assign("LAB_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=procedure_order");
		$this->assign("prescription_LINK",$GLOBALS['webroot'] . "/controller.php?prescription&edit&id=&pid=".$pid);
		if($plid2 == null) {
			$this->assign("plan_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/load_form.php?formname=dictation");
		}else{
			$this->assign("plan_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/view_form.php?formname=formname=dictation&id=".$plid2);
		}
		$this->assign("referral_LINK",$GLOBALS['webroot'] . "/interface/patient_file/transaction/add_transaction.php");
		$this->assign("admission_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/admit_doctor_form.php");
		$this->assign("summary_LINK",$GLOBALS['webroot'] . "/interface/patient_file/summary/summary_print.php");
    	$this->assign("STYLE", $GLOBALS['style']);
		$res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
		if($res['newcrop_user_role'] == 'erxnurse'){
  if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
	  $srcdir = $GLOBALS['srcdir'];
  include_once($GLOBALS['srcdir'].'/pid.inc');
  setpid($_GET['set_pid']);
 }
			$encounter=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
setencounter($encounter);
		$this->assign("DISPLAYNONE", "display:none");
				$this->assign("ENCOUNTER", $_SESSION['encounter']);
		}
		if($res['newcrop_user_role'] != 'erxnurse' && $res['newcrop_user_role'] != 'erxdoctor' ){
		$this->assign("DISPLAYNONE1", "display:none");
		}
      // Options for units of measurement and things to omit.
      $this->assign("units_of_measurement",$GLOBALS['units_of_measurement']);
      $this->assign("gbl_vitals_options",$GLOBALS['gbl_vitals_options']);
    }

    function default_action_old() {
    	//$vitals = array();
    	//array_push($vitals, new FormVitals());
    	$vitals = new FormVitals();
    	$this->assign("vitals",$vitals);
    	$this->assign("results", $results);
    	return $this->fetch($this->template_dir . $this->template_mod . "_new.html");
	}

    function default_action($form_id) {

        if (is_numeric($form_id)) {
    		$vitals = new FormVitals($form_id);
    	}
    	else {
    		$vitals = new FormVitals();
    	}

    	$dbconn = $GLOBALS['adodb']['db'];
    	//Combined query for retrieval of vital information which is not deleted
      $sql = "SELECT fv.*, fe.date AS encdate " .
        "FROM form_vitals AS fv, forms AS f, form_encounter AS fe WHERE " .
        "fv.id != $form_id and fv.pid = " . $GLOBALS['pid'] . " AND " .
        "f.formdir = 'vitals' AND f.deleted = 0 AND f.form_id = fv.id AND " .
        "fe.pid = f.pid AND fe.encounter = f.encounter " .
        "ORDER BY encdate DESC, fv.date DESC";
    	$result = $dbconn->Execute($sql);

        // get the patient's current age
    	$patient_data = getPatientData($GLOBALS['pid']);
        $patient_dob=$patient_data['DOB'];
        $patient_age = getPatientAge($patient_dob);
    	$this->assign("patient_age", $patient_age);
        $this->assign("patient_dob",$patient_dob);

    	$i = 1;
    	while($result && !$result->EOF)
    	{
    		$results[$i]['id'] = $result->fields['id'];
    		$results[$i]['encdate'] = substr($result->fields['encdate'], 0, 10);
        $results[$i]['date'] = $result->fields['date'];
    		$results[$i]['activity'] = $result->fields['activity'];
    		$results[$i]['bps'] = $result->fields['bps'];
    		$results[$i]['bpd'] = $result->fields['bpd'];
    		$results[$i]['weight'] = $result->fields['weight'];
    		$results[$i]['height'] = $result->fields['height'];
    		$results[$i]['temperature'] = $result->fields['temperature'];
    		$results[$i]['temp_method'] = $result->fields['temp_method'];
    		$results[$i]['pulse'] = $result->fields['pulse'];
    		$results[$i]['respiration'] = $result->fields['respiration'];
    		$results[$i]['BMI'] = $result->fields['BMI'];
    		$results[$i]['BMI_status'] = $result->fields['BMI_status'];
                $results[$i]['note'] = $result->fields['note'];
    		$results[$i]['waist_circ'] = $result->fields['waist_circ'];
    		$results[$i]['head_circ'] = $result->fields['head_circ'];
    		$results[$i++]['oxygen_saturation'] = $result->fields['oxygen_saturation'];
    		$result->MoveNext();
    	}

    	$this->assign("vitals",$vitals);
    	$this->assign("results", $results);

    	$this->assign("VIEW",true);
	return $this->fetch($this->template_dir . $this->template_mod . "_new.html");

    }
	
    function default_action_process() {
		if ($_POST['process'] != "true")
			return;

		$weight = $_POST["weight"];
		$height = $_POST["height"];
		if ($weight > 0 && $height > 0) {
			$_POST["BMI"] = ($weight/$height/$height)*703;
		}
		if     ( $_POST["BMI"] > 42 )   $_POST["BMI_status"] = 'Obesity III';
		elseif ( $_POST["BMI"] > 34 )   $_POST["BMI_status"] = 'Obesity II';
		elseif ( $_POST["BMI"] > 30 )   $_POST["BMI_status"] = 'Obesity I';
		elseif ( $_POST["BMI"] > 27 )   $_POST["BMI_status"] = 'Overweight';
		elseif ( $_POST["BMI"] > 25 )   $_POST["BMI_status"] = 'Normal BL';
		elseif ( $_POST["BMI"] > 18.5 ) $_POST["BMI_status"] = 'Normal';
		elseif ( $_POST["BMI"] > 10 )   $_POST["BMI_status"] = 'Underweight';
		$temperature = $_POST["temperature"];
		if ($temperature == '0' || $temperature == '') {
			$_POST["temp_method"] = "";
		}

		$this->vitals = new FormVitals($_POST['id']);
		
		parent::populate_object($this->vitals);
		
		$this->vitals->persist();
		if ($GLOBALS['encounter'] < 1) {
			$GLOBALS['encounter'] = date("Ymd");
		}
		if(empty($_POST['id']))
		{
			addForm($GLOBALS['encounter'], "Vitals", $this->vitals->id, "vitals", $GLOBALS['pid'], $_SESSION['userauthorized']);
			$_POST['process'] = "";
		}
		return;
    }

}

?>
