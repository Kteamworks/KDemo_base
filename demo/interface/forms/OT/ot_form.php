<?php
require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
$result_patient = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
$result_visit="SELECT * from form_encounter where pid=? and encounter=?";
$result_visit1 = sqlStatement($result_visit, array($pid,$encounter)); 
$result_visit2=sqlFetchArray($result_visit1);
$pfname = $result_patient['fname'];
$plname = $result_patient['lname'];
$pmname = $result_patient['mname'];
$page = $result_patient['age'];
$pgender=$result_patient['sex'];
$pmob = $result_patient['phone_cell'];
?> 
<html>

<head>
<?php html_header_show();?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<script src="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="<?php echo $GLOBALS['webroot'] ?>/library/css/bootstrap-datetimepicker4.7.14.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot']; ?>/library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
  <link rel="stylesheet" href="../../../dist/css/AdminLTE.min.css">
  	<link rel="stylesheet" href="style.css"  />
		<link rel="stylesheet" href="../../../library/css/mycss.css"  />
	<style>
	.cd-breadcrumb li > *, .cd-multi-steps li > * {
    font-size: 1.4rem !important;
	}
	section {
		margin-left: 20px;
	}
	.cd-breadcrumb.triangle li > * {
    padding: 1em 0.3em 1em 1.4em !important;
	}
	</style>
<?php $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
 ?>
<body>
   <div class="container">
<div class="row">
<div class="col-md-12">
<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>OT form requirement</legend>

<!-- Text input-->




<div class="form-group">
  <label class="col-md-4 control-label" for="Name (Full name)">Name (Full name)</label>  
  <div class="col-md-4">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user">
        </i>
       </div>
       <input id="Name (Full name)" name="Name (Full name)" type="text" placeholder="Name (Full name)" class="form-control input-md" value="<?php echo $pfname. $pmname. $plname ?>" readonly>
      </div>

    
  </div>

  
</div>

<!-- File Button 
<div class="form-group">
  <label class="col-md-4 control-label" for="Upload photo">Upload photo</label>
  <div class="col-md-4">
    <input id="Upload photo" name="Upload photo" class="input-file" type="file">
  </div>
</div>--> 

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Date Of Birth">Age</label>  
  <div class="col-md-4">

  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-birthday-cake"></i>
        
       </div>
       <input id="Age" name="age" type="text" placeholder="Age" class="form-control input-md" value="<?php echo $age; ?>" readonly>
      </div>
  
    
  </div>
</div>

<!-- Multiple Radios (inline) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="Gender">Gender</label>
  <div class="col-md-4"> 
    <label class="radio-inline" for="Gender-0">
      <input type="radio" name="Gender" id="Gender-0" value="1" checked="<?php if($pgender == 'male') { echo "checked"; } ?>">
      Male
    </label> 
    <label class="radio-inline" for="Gender-1">
      <input type="radio" name="Gender" id="Gender-1" value="2" checked="<?php if($pgender == 'female') { echo "checked"; } ?>">
      Female
    </label> 
    <label class="radio-inline" for="Gender-2">
      <input type="radio" name="Gender" id="Gender-2" value="3" checked="<?php if($pgender == 'other') { echo "checked"; } ?>">
      Other
    </label>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Phone number ">Phone number </label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-phone"></i>
        
       </div>
    <input id="Phone number " name="Phone number " type="text" placeholder="Primary Phone number " class="form-control input-md" value="<?php echo $pmob; ?>" readonly>
    
      </div>

  
  </div>
</div>

                    <!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Working Experience (time period)">OT Appointment (time period)</label>  
  <div class="col-md-4">
  <div class="input-group date"  id='datetimepicker' >

      <input type='text' size='10' class='form-control' name='form_date' id='form_date' title='<?php echo xla('yyyy-mm-dd OT Appointment'); ?>' />
	   <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
				</div>
				<div class="col-md-4">
				<a href="#">Find available OT Slots</a>
				</div>
 </div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Working Experience (time period)">Estimated Operation Time (time period)</label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-clock-o"></i>
        
       </div>
    <input id="Working Experience (time period)" name="Working Experience" type="text" placeholder="Enter time period " class="form-control input-md">
    
    
      </div>
 
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="Overview (max 200 words)">Overview (max 200 words)</label>
  <div class="col-md-4">                     
    <textarea class="form-control" rows="10"  id="Overview (max 200 words)" name="Overview (max 200 words)">Overview</textarea>
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="Special (max 200 words)">Special Instructions </label>
  <div class="col-md-4">                     
    <textarea class="form-control" rows="10"  id="Special (max 200 words)" name="special_instruction">Special Instructions</textarea>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" ></label>  
  <div class="col-md-4">
  <a href="#" class="btn btn-success"><span class="glyphicon glyphicon-thumbs-up"></span> Submit</a>
  <a href="#" class="btn btn-danger" value=""><span class="glyphicon glyphicon-remove-sign"></span> Clear</a>
    
  </div>
</div>

</fieldset>
</form>
</div>
<!--<div class="col-md-2 hidden-xs">
<img src="http://websamplenow.com/30/userprofile/images/avatar.jpg" class="img-responsive img-thumbnail ">
  </div> -->


</div>
   </div>
   		<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

        <script type="text/javascript">
		var j = jQuery.noConflict();
            j(function () {
                j('#datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
				});
				</script>

		              <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/moment/moment.js" ></script>
                    <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/bootstrap-datetimepicker4.7.14.min.js" type="text/javascript"></script>
					                    <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
  </html>
  