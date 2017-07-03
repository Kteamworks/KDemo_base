<?php
require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");

        // In the ajax, I told it to use $_POST, so change to
        // post instead of get
        if(isset($_POST['did'])) {
            $id=$_POST['did'];
            // Don't do this anymore.............vvv
           $newcrop_user_role=sqlStatement("select * from users where id='$id'"); 

							  while($newcrop_user_roles = sqlFetchArray($newcrop_user_role)){								  
                               if($newcrop_user_roles['status'] == 0) {
								   $status = 'Offline';
								   $icon_stat = 'muted';
							   }
							   elseif($newcrop_user_roles['status'] == 2) {
							   									   $status = 'Busy';
									   $icon_stat = 'danger';
							   }
								   else {
									   $status = 'Online';
									   $icon_stat = 'success';
								   }
								   $today = date('Y-m-d',strtotime("+0 days"));

$query_seen = "select a.*, b.*,round(((b.pending_patient/a.total_no_of_patients )*100),0)pending_percent,round((((a.total_no_of_patients-b.pending_patient)/a.total_no_of_patients)*100),0)seen_percent, (a.total_no_of_patients-b.pending_patient)no_of_examined_patients
from
(SELECT count(b.id) total_no_of_patients 
FROM patient_data a,form_encounter b 
where a.pid=b.pid and b.provider_id='".$newcrop_user_roles['id']."'  and date(b.date)=date('".$today."') )a,

(SELECT count(b.id)pending_patient
FROM patient_data a,form_encounter b 
where a.pid=b.pid and b.provider_id='".$newcrop_user_roles['id']."'  and date(b.date)=date('".$today."') 
and out_to is null and out_time is  null)b";
$res_seen = sqlStatement($query_seen);
$res_seen1 = sqlFetchArray($res_seen);
                ?>
				                                    <div class="team-member-info">
                                        <!--<div class="action-set">
                                            <a href="javascript:;" class="tooltips" data-original-title="Profile Info" data-toggle="tooltip" data-placement="top">
                                                <i class="fa fa-reorder"></i>
                                            </a>
                                        </div> -->
                                        <div class="team-title">
                                            <a href="javascript:;" class="m-name">
                                                <?php echo ucfirst(strtolower($newcrop_user_roles['fname'])).' '. ucfirst(strtolower($newcrop_user_roles['lname'])); ?>
                                            </a>
                                            <span class="sub-title"><?php echo $newcrop_user_roles['qualification']; ?></span>
                                        </div>

                                        <div class="call-info">
                                            <a href="../messages/messages.php?showall=no&sortby=users.lname&sortorder=asc&begin=0&task=addnew&form_active=1">
                                                <i class="fa fa-envelope-o"></i>
                                            </a>
                                            														<?php if($newcrop_user_roles['user_image']) { ?>
              <img src="../<?php echo $newcrop_user_roles['user_image']; ?>" class="circle" alt="User Image">
			<?php } elseif($newcrop_user_roles['newcrop_user_role'] == 'erxdoctor') { ?>
			<img src="../../../library/dist/img/doctor.png" class="img-circle" alt="User Image">
			<?php }?><a href="tel:<?php echo $newcrop_user_roles['phonecell']; ?>">
                                                <i class="fa fa-phone"></i>
                                            </a>
                                        </div>
										<div class="call-info">
										<a href="#"><i class="fa fa-circle text-<?php echo $icon_stat; ?>"></i> <?php echo $status; ?></a>
                                        </div><div class="status">
                                            <h5>specialized</h5>
                                            <span><?php echo $newcrop_user_roles['specialty'] ?></span>

                                    </div><br>
									<div class="status">
                                            <h5>Room No</h5>
                                            <span>5</span>

                                    </div><br>
									<div class="status">
                                            <h5>Patients Seen</h5>
                                            <span><?php  echo $res_seen1['no_of_examined_patients']; ?></span>

                                    </div><br>
									<div class="status">
                                            <h5>Paitients waiting</h5>
                                            <span><?php  echo $res_seen1['pending_patient']; ?></span>

                                    </div>
                                </div>
                   
                <?php 
                }
            }


?>
