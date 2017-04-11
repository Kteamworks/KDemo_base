<?php
ini_set('max_execution_time', 0);
function send_message(){
 $user = 'success';
 $password = '654321';
 $sender_id = 'BSHSMS';//helloz welcom FAPcop abhiii'hiiiii
 $sender = '9731960662';//9673776599 9320491970
 $msg = 'From MedSMART App';
 //$priority = 'ndnd';
 $sms_type = 'normal';
 //$data = array('user'=>$user, 'pass'=>$password, 'sender'=>$sender_id, 'phone'=>$sender, 'text'=>$msg,  'stype'=>$sms_type);//'priority'=>$priority,
 $data='user='.$user.'&pass='.$password.'&sender='.$sender_id.'&phone='.$sender.'&text='.$msg.'&stype='.$sms_type.'&priority=ndnd'; 
 $ch = curl_init('http://bhashsms.com/api/sendmsg.php?'.$data);
    echo var_dump($data);
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 echo var_dump($ch);
 try {
  $response = curl_exec($ch);
  echo var_dump($ch);
  curl_close($ch);
  echo var_dump($response);
  echo 'Message has been sent.';
 }catch(Exception $e){
  echo 'Message: ' .$e->getMessage();
 }
}

send_message();

?>

