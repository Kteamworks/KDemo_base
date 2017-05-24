<?php
require_once("../../interface/globals.php");
$drug_id = $_GET['id'];
$drug_qry = "SELECT * FROM drugs WHERE drug_id = ?";
$drug_name = sqlStatement($drug_qry, array($drug_id));
$drug_name1 = sqlFetchArray($drug_name);
$qry = "SELECT * FROM drug_dosage WHERE drug_id = ?";
$results = sqlStatement($qry, array($drug_id));
?>
<div class="modal-header">
    <h3>Prescribe <?php echo $drug_name1['name']; ?> </h3>
</div>
<form name="form.userForm" ng-submit="submitForm()" novalidate>
    <div class="modal-body">
        <!-- NAME -->
		        <div class="form-group">
            <label>Dosage Type</label>
			<select ng-init="dosagetype = options[0]" name="dosagetype" class="form-control" ng-model="dosagetype" required>
			<option value="">-- Choose Dosage Type --</option>
    <option value="1" selected="">Tablet</option>
    <option value="2">Syrup</option>
    <option value="3">Injection</option>
  </select>
    <p ng-show="form.userForm.dosagetype.$invalid && !form.userForm.dosagetype.$pristine" class="help-block">Dosage type is required.</p>
        </div>

            <input type="hidden" name="name" class="form-control" ng-model="name">
           
        <!-- USERNAME -->
        <div class="form-group">
            <label>Medicine Units</label>
			<select name="username" class="form-control" ng-model="user.username" required>
			<option value="">-- Choose Medicine Units --</option>
			<?php while ($drug_data = sqlFetchArray($results)) { $qtyz = str_replace(".00", "", (string)number_format ($drug_data['dosage_quantity'], 2, ".", ""));?>
    <option value="<?php echo $drug_data['dosage_quantity'];?>-<?php echo $drug_data['dosage_units']; ?>" selected=""><?php echo $qtyz; ?>&nbsp;<?php echo $drug_data['dosage_units']; ?></option>
			<?php } ?>
  </select>
<p ng-show="form.userForm.username.$invalid && !form.userForm.username.$pristine" class="help-block">Medicine Units is required.</p>
        </div>

        <!-- EMAIL -->
        <div class="form-group">
            <label>Take</label>
			<select name="take1" ng-model="take1"  style="width:45px" ng-required="false">
  <option value="0">0</option>
    <option value="1" selected="selected">1</option>
    <option value="2">2</option>
 </select>
 <select name="take2" style="width:45px" ng-model="take2"  ng-required="false">
    <option value="0" selected="selected">0</option>
    <option value="1">1</option>
    <option value="2">2</option>
 </select>
 <select name="take3" style="width:45px" ng-model="take3"  ng-required="false">
   <option value="0">0</option>
    <option value="1" selected="selected">1</option>
    <option value="2">2</option>
 </select> 
              <select name="name" class="form-control" ng-model="name"  style="width:150px" ng-required="false">
  <option value="BF">Before Food</option>
    <option value="AF"  selected="">After Food</option>
 </select>
         <input name="email" class="form-control" ng-model="email" type="hidden" ng-required="false">
           <p ng-show="form.userForm.email.$invalid && !form.userForm.email.$pristine" class="help-block">Take is required.</p> 
        </div>
				<div class="form-group">
            <label>Duration</label>
 <select name="duration" class="form-control" ng-model="duration"  ng-required="false">
<option value="">-- Choose Duration --</option>
   <option value="1">1 Week</option>
    <option value="2" selected="selected">2 Weeks</option>
    <option value="3">3 Weeks</option>
 </select>
	</div>
		<div class="form-group">
            <label>Notes</label>
	<textarea name="note" class="form-control" wrap="virtual" ng-model="note" ng-required="false"></textarea>
	</div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" ng-disabled="form.userForm.$invalid">OK</button>
        <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
    </div>
</form>