<?php
$ignoreAuth=true;
include_once("../globals.php");
?>
<html>
<head>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<link rel=stylesheet href="../themes/login.css" type="text/css">
</head>
<body class="body_title">

<span class="title_bar">
<div class="title_name"><?php echo "$openemr_name" ?><a href="#" onclick="window.top.location.href = '<?php echo $GLOBALS['webroot'] ?>/patients'; " style="float:right;color:#ddd">Login as Patient</a></div>
</span><br>

</body>
</html>
