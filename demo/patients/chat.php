<?php
  $patient  = $_GET['name'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Untitled Page</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   
<script type="text/javascript">

var t = setInterval(function(){get_chat_msg()},5000);


//
// General Ajax Call
//
      
var oxmlHttp;
var oxmlHttpSend;
      
function get_chat_msg()
{

    if(typeof XMLHttpRequest != "undefined")
    {
        oxmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
       oxmlHttp = new ActiveXObject("Microsoft.XMLHttp");
    }
    if(oxmlHttp == null)
    {
        alert("Browser does not support XML Http Request");
       return;
    }
    
	if (document.getElementById("txtname") != null)
    {
        strname = document.getElementById("txtname").value;
        document.getElementById("txtname").readOnly=true;
    }
    oxmlHttp.onreadystatechange = get_chat_msg_result;
    oxmlHttp.open("GET","chat_recv_ajax.php?name=" + strname,true);
    oxmlHttp.send(null);
}
     
function get_chat_msg_result()
{
    if(oxmlHttp.readyState==4 || oxmlHttp.readyState=="complete")
    {
        if (document.getElementById("DIV_CHAT") != null)
        {
            document.getElementById("DIV_CHAT").innerHTML =  oxmlHttp.responseText;
            oxmlHttp = null;
        }
        var scrollDiv = document.getElementById("DIV_CHAT");
        scrollDiv.scrollTop = scrollDiv.scrollHeight;
    }
}

      
function set_chat_msg()
{

    if(typeof XMLHttpRequest != "undefined")
    {
        oxmlHttpSend = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
       oxmlHttpSend = new ActiveXObject("Microsoft.XMLHttp");
    }
    if(oxmlHttpSend == null)
    {
       alert("Browser does not support XML Http Request");
       return;
    }
    
    var url = "chat_send_ajax.php";
    var strname="noname";
    var strmsg="";
    if (document.getElementById("txtname") != null)
    {
        strname = document.getElementById("txtname").value;
        document.getElementById("txtname").readOnly=true;
    }
    if (document.getElementById("txtmsg") != null)
    {
        strmsg = document.getElementById("txtmsg").value;
        document.getElementById("txtmsg").value = "";
    }
    
    url += "?name=" + strname + "&msg=" + strmsg;
    oxmlHttpSend.open("GET",url,true);
    oxmlHttpSend.send(null);
}

</script>

</head>
<body>
    &nbsp;
    <div  class="table-responsive col-sm-12">
       
       <h2>Chat</h2>

	   <table class="table">
            
            
                          
         <input id="txtname" style="width: 150px" type="hidden" name="name" value='<?php echo $patient;  ?>' maxlength="15"/>
                       
            <tr>
                <td colspan="2">
                    <div id="DIV_CHAT">
                    </div>
                </td>
            </tr>
            <tr>
                <td class='col-sm-2 align-right'>
                    <input id="txtmsg" type="text" name="msg" /></td>
                <td>
                    <input id="Submit2" type="button" value="Send" onclick="set_chat_msg()"/></td>
            </tr>
            <tr>
                <td colspan="1" >
                    </td>
                <td colspan="1">
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
