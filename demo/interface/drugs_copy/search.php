<?php
    $key=$_GET['key'];
    $array = array();
    $con=mysqli_connect("localhost","root","","openemr");
   // $db=mysql_select_db("demos",$con);
    $query=mysqli_query($con,"select * from manufacturer where title LIKE '{$key}%'");
    while($row=mysqli_fetch_assoc($query))
    {
      $array[] = $row['title'];
    }
    echo json_encode($array);
?>
