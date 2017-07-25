<?php 
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");

?>
<html>
<head>
<title></title>
<meta charset="utf-8"/>
<link href="styles.css" rel="stylesheet"/>

<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" >
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		  <script type="text/javascript">
		 function ConfirmDelete(){

     return confirm('Are you sure want to delete this?');
		 }
 </script>   
</head>
<body>

<?php
/*$localhost = 'localhost';
$user = 'root';
$password = '';
$conn = mysql_connect($localhost, $user, $password);
if(! $conn ) {
die('Could not connect: ' . mysql_error());
}*/
$sql = 'SELECT a.id,a.doctorname,a.roomnum as room,u.username as name FROM allocation a join users u on a.doctorname=u.user_id';
//mysql_select_db('mangoo');

$retval = sqlStatement($sql);
/*if(! $retval ) {
die('Could not get data: ' . mysql_error());
}*/
?>
<div class= "wrapper">
<table  align="center" style="margin: 60px auto;" border="4" width="50%" style="margin-top:60px; margin-left:0px;  ">
 <link href="style.css" rel="stylesheet"/>
      <caption align="bottom">

<thead>
   <tr>
      <th>RoomNumber</th>
      <th>DoctorName</th>
    </tr>
    </thead>
    
    <a href="create.php"><button style="height:40px;width:80px; margin-left:auto;margin-right:auto">Add</button>  </a>
<?php
    if( mysql_num_rows( $retval )==0 ){
        echo '<tr><td colspan="4">No Rows Returned</td></tr>';
      }else{
while($row = sqlFetchArray($retval)) {       
    
echo "<td>".$row['room']."</td>";
echo "<td>".$row['name']."</td>";
echo "<td><a href='edit.php?edit=$row[id]'><i class='fa fa-pencil-square-o' aria-hidden='true' style= width: 50px;></a></td>";
echo "<td><a onclick='return ConfirmDelete()' href='delete.php?del=".$row['id']."'> <i class='fa fa-trash' aria-hidden='true'></i> </a></td><tr>";


}
}
?>
</tbody>
</table>
</div>
    </body>

