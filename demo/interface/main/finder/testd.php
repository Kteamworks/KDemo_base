
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="generator" content="vBulletin 3.7.3" />

<meta name="keywords" content=" Split screen into 3 div containers, javascript, php, cgi, xml, css, asp, mysql help, 

database, webmaster, dhtml" />
<meta name="description" content=" Split screen into 3 div containers JavaScript programming" />

<link rel="shortcut icon" href="favicon.ico" />
<!-- jquery splitter -->

<script type='text/javascript' src='../../library/js/jquery-1.9.0.min.js'></script>
<script type='text/javascript' src='../../library/js/jquery.splitter-0.14.0.js'></script>
<link rel='stylesheet' type='text/css' href='../../library/css/jquery.splitter.css'>
<script>
jQuery(function($) {
   $('#widget').width(700).height(400).split({orientation:'vertical', limit:100, position:'70%'});
   
   $('#foo').split({orientation:'horizontal', limit:10});
   $('#a').split({orientation:'vertical', limit:10});
   $('#spliter2').css({width: 200, height: 300}).split({orientation: 'horizontal', limit: 20});
});
</script>

<!-- CSS Stylesheet -->

<style>

#spliter2 .a {
  background-color: #2d2d2d;
  
}
#spliter2 .b {
  background-color: #2d002d;
}
#foo {
  background-color: #E92727;
}
#x {
  background-color: #EFBD73;
}
#y {
  background-color: #e4e5e5;
}
#b {
  background-color: #73A4EF;
}
#bar {
  background-color: #BEE927;
}
</style>

<style type="text/css" id="vbulletin_css">


.red {
	
float:left;

height: 400px;
overflow:scroll;
background-color:  #696;
color:#fff;
margin-left: 40px;
border:0px solid #73A4EF;
width:1px;
word-wrap: break-word;
font-family: monospace;
}



.vertical-text {
    -ms-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);
    -ms-transform-origin: left top 0;
    -moz-transform-origin: left top 0;
    -webkit-transform-origin: left top 0;
    transform-origin: left top 0;
    background: #E23737;
    color: #fff;
    margin-left: 40px;
    padding: 10px;
    border: 1px solid #ccc;
    text-transform: uppercase;
    border: 1px solid #B52C2C;
    text-transform: 1px 1px 0px rgba(0, 0, 0, 0.5);
    box-shadow: 2px -2px 0px rgba(0, 0, 0, 0.1);
    float: left;
}

.red2 {
float:left;
width : 100px;
height: 400px;
overflow:show;
border:0x solid #ff0000;
display: -webkit-flex;
display: flex;
-webkit-flex-wrap: wrap;
flex-wrap: wrap;
-webkit-align-content: center;
align-content: center;
vertical-align: 50%;	
}
	
.black {
margin-left:100px;
height:100px;
overflow:scroll;
border:0px solid #000;
}


.black2 {
margin-left:100px;
height:400px;
overflow:scroll;
border:0px solid #000;
}

.blue {
margin-left:100px;
height:100px;
overflow:hidden;
border:0px solid #000;
}

#green {
clear:both;
border:0px solid #696;
}

label
{
	color: #FFFFFF;
}

#pt_table th
{
	color: #FFFFFF;
}

#navbar {position: relative;width:20%;height:100%;z-index: 100; float:left;padding-top: 0px; margin-top: -3px; border-top: none;background-color:#ccc;font-family: verdana,arial,sans-serif;font-weight:bold;}

#navbar a{display: block; text-decoration: none;width: 180px; height: 40px;background-color:#ccc;}

#navbar ul li a:hover {border: none; border-top: 1px solid white;background-color:#AB4500;}

#navbar li {background-image: none; padding: 0px;}

#navbar ul {list-style-type: none;padding:0;text-indent: 20px;display: block;border : 1px solid;border-width : 0 1px;text-decoration: none;background-color:#ccc;}


#navbar ul li {float : left;border : 1px solid;position : relative;list-style-type: none;background-image: none; padding: 0px; background-color:#ccc;}


#content {float:right;background-color:#ddd;width:70%;height:100%;}

#content a {color: #AB4500; padding-left: 0px; text-decoration:none;}
#content a:hover {text-decoration: underline; background-color: transparent; color: #AB4500; padding-left: 0px; }


/* CSS Document */ 

.offscreen { 
  position: absolute; 
  top: -30em; 
  left: -300em; 
} 

div#hmenu { 
   margin: 0; 
   padding: .3em 0 .3em 0; 
   background: #ddeebb; 
   width: 100%; 
   text-align: center; 
} 

div#hmenu ul { 
   list-style: none; 
   margin: 0; 
   padding: 0; 
} 

div#hmenu ul li { 
   margin: 0; 
   padding: 0; 
   display: inline; 
} 

div#hmenu ul a:link{ 
   margin: 0; 
   padding: .3em .4em .3em .4em; 
   text-decoration: none; 
   font-weight: bold; 
   font-size: medium; 
   color: #004415; 
} 

div#hmenu ul a:visited{ 
   margin: 0; 
   padding: .3em .4em .3em .4em; 
   text-decoration: none; 
   font-weight: bold; 
   font-size: medium; 
   color: #227755; 
} 

div#hmenu ul a:active{ 
   margin: 0; 
   padding: .3em .4em .3em .4em; 
   text-decoration: none; 
   font-weight: bold; 
   font-size: medium; 
   color: #227755; 
} 

div#hmenu ul a:hover{ 
   margin: 0; 
   padding: .3em .4em .3em .4em; 
   text-decoration: none; 
   font-weight: bold; 
   font-size: medium; 
   color: #f6f0cc; 
   background-color: #227755; 
}


</style>


</head>
<body>


<!--
<div id="hmenu"> 
<ul> 
  <li><a href="http://www.w3.org/Consortium/activities">W3C Activities</a></li> 
  <li><a href="http://www.w3.org/TR/">W3C Technical Reports</a></li> 
  <li><a href="http://www.w3.org/Consortium/siteindex">W3C Site Index</a></li> 
  <li><a href="http://www.w3.org/Consortium/new-to-w3c">New Visitors</a></li> 
  <li><a href="http://www.w3.org/Consortium/">About W3C</a></li> 
  <li><a href="http://www.w3.org/Consortium/join">Join W3C</a></li> 
  <li><a href="http://www.w3.org/Consortium/contact">Contact W3C</a></li> 
</ul>   
</div> 
-->
<div class='red1'> </div>
<div id="widget">
  <div id="foo">
     
     <div id="a">
        <div id="x"><div style="padding: 0.5em; color: White; text-align:justify"><B>Messages</B></div></div>
        <div id="y"><?php include("../../main/messages/minmessages.php"); ?></div>
       
     </div><!-- #a -->
     <div id="b"></div>
   </div> <!-- end of #foo -->
   <div id="bar"></div>
</div> <!-- end of #widget -->
<div class='red1'> </div>
<div id="x"><div style="padding: 0.5em; color: White; text-align:justify"></div><B>Patient Appointments</B></div>
<div id="debug"><?php include("patient_tracker.php");?></div>
<div id="spliter2">
  <div class="a">
  </div>
  <div class='red1'> </div>
  <div id="x"><div style="padding: 0.5em; color: White; text-align:justify"><B>InPatients</B></div></div>
  <div class="b">
  <?php require_once("p_dynamic_finder_ip.php"); ?></div>
</div>

<!--
<div class='red'> Messages</div>
<div class='blue'><?php //include("../../main/messages/messages.php"); ?></div>
<div class='red'>Patient Queue</div>
<div class='blue'><?php //include("patient_tracker.php");?></div>
<div class='red2'>Check In Patients</div>
<div class='black2'><?php //require_once("dynamic_finder.php"); ?></div>

<div id='green'>&nbsp;</div>
-->


</body>
</html>
