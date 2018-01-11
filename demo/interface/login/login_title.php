<?php
$ignoreAuth=true;
include_once("../globals.php");
?>
<html>
<head>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<link rel=stylesheet href="../themes/login.css" type="text/css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body class="body_title">

<span class="title_bar">
<div class="title_name"><?php echo "$openemr_name" ?>  Premier™ | Kavaii<a href="#" onclick="window.top.location.href = '<?php echo $GLOBALS['webroot'] ?>/patients'; " style="float:right;color:#ddd">Patient Portal <i class="fa fa-sign-in"></i></a></div>
</span><br>

</body>
</html>
