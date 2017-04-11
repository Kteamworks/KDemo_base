<?php /* Smarty version 2.3.1, created on 2015-03-18 11:50:11
         compiled from default/views/global/fancy_navigation.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'pc_url', 'default/views/global/fancy_navigation.html', 269, false),)); ?><style>
a {
 text-decoration:none;
}
td {
 font-family: Arial, Helvetica, sans-serif;
}
.tacell {
 font-size:10pt;
 background-color:#e8eef7;
 text-align:right;
}
.tucell {
 font-size:10pt;
 background-color:#f6c6c6;
 text-align:right;
}
.eacell {
 font-size:10pt;
 background-color:#ffffff;
}
.eucell {
 font-size:10pt;
 background-color:#ebebeb;
}
.bordyy {
}
.bordyn {
 border-top-width:0px;
 padding-top:0px;
}
.bordny {
 border-bottom-width:0px;
 padding-bottom:0px;
}
.bordnn {
 border-top-width:0px;
 border-bottom-width:0px;
 padding-top:0px;
 padding-bottom:0px;
}
div.tiny { width:1px; height:1px; font-size:1px; }

/* (CHEMED) */

.selectors_container {
    position: absolute;
    top: 25px;
    left: 0px;
    z-index: 3;
    float: right;
    width: 600px;
    height: 180px;
    visibility: hidden;
    background-color: #B0C4DE;
    border-top: solid 2px #E8EEF7;
    border-left: solid 2px #E8EEF7;
    border-bottom: solid 2px #000000;
    border-right: solid 2px #000000;
}

.selectors_lists_container {
    float: left;
    padding: 5px;
}

.selectors_cal_container {
    float: left;
    padding: 5px;
}

.selector_buttons {
    position: absolute;
    top: 0px;
    left: 0px;
    height: 30;
}

.navigation_buttons {
    position: absolute;
    top: 0px;
    left: 40%;
    height: 30;
}

.service_buttons {
    position: absolute;
    top: 0px;
    right: 20px;
    height: 30;
}

.viewtype_selected {
 border: #000000 solid 1px;
}

.viewtype {
}

.apptstatus {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9pt;
	color: #000000;
	font-weight: bold;
    margin: 0px 2px 0px 2px;
}

.appttime {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9pt;
	margin-left: 2px;
    border-left: solid 1px #000000;
}

.apptname {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9pt;
	margin-right: 2px;
    border-right: solid 1px #000000;
}
.cal_date_range {
    padding: 0px;
    margin-top: 35px;
}


/*END (CHEMED) */

</style>

<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php  include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php");  ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>

<script language='JavaScript'>

 var mypcc = '<?php  echo $GLOBALS['phone_country_code']  ?>';

 // This is called from the event editor popup.
 function refreshme() {
  top.restoreSession();
  document.forms[0].submit();
 }

 function newEvt(startampm, starttimeh, starttimem, eventdate, providerid, catid) {
  dlgopen('add_edit_event.php?startampm=' + startampm +
   '&starttimeh=' + starttimeh + '&starttimem=' + starttimem +
   '&date=' + eventdate + '&userid=' + providerid + '&catid=' + catid,
   '_blank', 550, 310);
 }

 function oldEvt(eventdate, eventid) {
  dlgopen('add_edit_event.php?date='+eventdate+'&eid=' + eventid, '_blank', 550, 310);
 }

 function goPid(pid) {
  top.restoreSession();
    <?php 
     if ($GLOBALS['concurrent_layout'])
     {

	      echo "  top.RTop.location = '../../patient_file/summary/demographics.php' " .
	       "+ '?set_pid=' + pid;\n";	

     } else {
      echo "  top.location = '../../patient_file/patient_file.php' " .
       "+ '?set_pid=' + pid + '&pid=' + pid;\n";
     }
     ?>
 }

 function GoToToday(theForm){
  var todays_date = new Date();
  var theMonth = todays_date.getMonth() + 1;
  theMonth = theMonth < 10 ? "0" + theMonth : theMonth;
  theForm.jumpdate.value = todays_date.getFullYear() + "-" + theMonth + "-" + todays_date.getDate();
  top.restoreSession();
  theForm.submit();
 }

 //(CHEMED)
 function ShowFacility() {
    var sel = document.getElementById('all_users');
    if (sel) {sel.value = '__PC_ALL__';} //Show all the users in the facility when switching facilities
   document.getElementById('cal_navigator').submit();
 }

 function ChangeViewType(viewtype) {
    document.getElementById('viewtype').value = viewtype;
    document.getElementById('cal_navigator').submit();
 }
 //END (CHEMED)

</script>

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1;">&nbsp;</div>

<?php 

 // A_CATEGORY is an ordered array of associative-array categories.
 // Keys of interest are: id, name, color, desc, event_duration.
 //
 // echo "<!-- A_CATEGORY = "; print_r($this->_tpl_vars['A_CATEGORY']); echo " -->\n"; // debugging
 // echo "<!-- A_EVENTS = "; print_r($this->_tpl_vars['A_EVENTS']); echo " -->\n"; // debugging

 $A_CATEGORY  =& $this->_tpl_vars['A_CATEGORY'];

 $A_EVENTS  =& $this->_tpl_vars['A_EVENTS'];
 // $S_EVENTS  =& $this->_tpl_vars['S_EVENTS']; // Deleted by Rod
 $providers =& $this->_tpl_vars['providers'];
 $times     =& $this->_tpl_vars['times'];
 $interval  =  $this->_tpl_vars['interval'];
 $viewtype  =  $this->_tpl_vars['VIEW_TYPE'];
 $PREV_WEEK_URL = $this->_tpl_vars['PREV_WEEK_URL'];
 $NEXT_WEEK_URL = $this->_tpl_vars['NEXT_WEEK_URL'];
 $PREV_DAY_URL  = $this->_tpl_vars['PREV_DAY_URL'];
 $NEXT_DAY_URL  = $this->_tpl_vars['NEXT_DAY_URL'];

 $Date =  postcalendar_getDate();
 if (!isset($y)) $y = substr($Date, 0, 4);
 if (!isset($m)) $m = substr($Date, 4, 2);
 if (!isset($d)) $d = substr($Date, 6, 2);

 // echo "<!-- There are " . count($A_EVENTS) . " A_EVENTS days -->\n";

 // $MULTIDAY = count($A_EVENTS) > 1; // (CHEMED) not needed after week_view template changes

 $provinfo = getProviderInfo('%', true, $_SESSION['pc_facility']); //(CHEMED)

  ?>

 <form name='theform' id='cal_navigator' action='index.php?module=PostCalendar&func=view&tplview=default&pc_category=&pc_topic='
       method='post' onsubmit='return top.restoreSession()'>

    <div class='selector_buttons'>

        <img src='../../pic/show_calendar.gif' id='img_jumpdate' align='absbottom'
             width='24' height='22' border='0' alt='[?]' style='cursor:pointer'
             title='<?php  echo xl ("Click here to choose a date"); ?>'>

        <input type='text' size='10' name='jumpdate' id='jumpdate'
               value='<?php  echo "$y-$m-$d"; ?>' title='yyyy-mm-dd date to go to'
               onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />

        <input type='submit' name='bnsubmit' value='<?php  echo xl("Go");  ?>' />

        <input type='submit' name='bnsubmit' value='<?php  echo xl("Today");  ?>' onClick='GoToToday(theform);' />

    </div>

    <div class='navigation_buttons'>
        <input name='viewtype' id='viewtype' type='hidden' value='<?php  echo $viewtype ?>' />
        <?php 
            foreach ( array ('day' => xl("Day"), 'week' => xl("Week"), 'month' => xl("Month"), 'year' => xl("Year"))
                  as $key => $value)  {
                echo "    <input type='button' onClick='javascript:ChangeViewType(\"$key\");' class='viewtype' value='$value' />";
            }
         ?>
    </div>

    <div class='service_buttons'>
        <input type='button' value='<?php  echo xl("Add");  ?>' onclick='newEvt(1, 9, 00, <?php  echo $Date; ?>, 0, 0)' />
        <input type='button' value='<?php  echo xl("Search");  ?>' onclick='top.restoreSession();location="index.php?module=PostCalendar&func=search"' />

        <a href="<?php $this->_plugins['function']['pc_url'][0](array('action' => $this->_tpl_vars['VIEW_TYPE'],'print' => "true"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>"><?php  echo xl("Print");  ?></a>

    </div>

    <div class='selectors_container' id='selectors_container'>
        <div class='selectors_cal_container' id='selectors_cal_container'>
        </div>

        <div class='selectors_lists_container'>
            <input type='hidden' name='all_users' id='all_users' value='' />
            <select multiple size='10' name='pc_username[]' >
                <option value='__PC_ALL__'><?php  echo xl ("All Users");  ?></option>
            <?php 
                 foreach ($provinfo as $doc) {
                  $username = $doc['username'];
                  echo "    <option value='$username'";
                  foreach ($providers as $provider)
                   if ($provider['username'] == $username) echo " selected";
                  echo ">" . $doc['fname'] . " " . $doc['lname'] . "</option>\n";
                 }
             ?>
            </select>

            <?php 
                // ==============================
                // FACILITY FILTERING (lemonsoftware)(CHEMED)
                // $facilities = getFacilities(); // (CHEMED) commented this out
                // We only want to see facilities that are a 'service location',
                // as 'billing location' facilities should not have any patient appointments
                // TODO: Is the above statement true?
                $facilities = getServiceFacilities();
             ?>

            <select name='pc_facility' size='10' onchange='ShowFacility()'>
            <?php 
                 if ( !$_SESSION['pc_facility'] ) $selected = "selected='selected'";
                 echo "    <option value='0' $selected>"  .xl('All Facilities'). "</option>\n";
                 foreach ($facilities as $fa) {
                    $selected = ( $_SESSION['pc_facility'] == $fa['id']) ? "selected='selected'" : "" ;
                     echo "    <option value='" .$fa['id']. "' $selected>"  .$fa['name']. "</option>\n";
                 }
             ?>
            </select>

        </div>

    </div>

</form>

<p align="center" class='cal_date_range'>
<?php 
 // Show the date/range and its previous- and next-day/week selectors.
 $atmp = array_keys($A_EVENTS);

if ($MULTIDAY) {
  echo "<a href='$PREV_WEEK_URL' onclick='top.restoreSession()'>&lt;&lt;</a>&nbsp;\n";
  echo dateformat(strtotime($atmp[0]));
  echo " - ";
  echo dateformat(strtotime($atmp[count($atmp)-1]));
  echo "&nbsp;<a href='$NEXT_WEEK_URL' onclick='top.restoreSession()'>&gt;&gt;</a>\n";
 } else {
  echo "<a href='$PREV_DAY_URL' onclick='top.restoreSession()'>&lt;&lt;</a>&nbsp;\n";
  echo dateformat(strtotime($atmp[0]), true);
  echo "&nbsp;<a href='$NEXT_DAY_URL' onclick='top.restoreSession()'>&gt;&gt;</a>\n";
 }

 ?>
</p>

<script language='JavaScript'>

//(CHEMED) popup calendar
function dateChanged(calendar) {
    // Beware that this function is called even if the end-user only
    // changed the month/year.  In order to determine if a date was
    // clicked you can use the dateClicked property of the calendar:
    if (calendar.dateClicked) {
          // OK, a date was clicked, redirect to /yyyy/mm/dd/index.php
          var y = calendar.date.getFullYear();
          var m = calendar.date.getMonth() + 1; // integer, 1..12
          if (m < 10) {m = '0'+m;}
          var d = calendar.date.getDate();      // integer, 1..31
          if (d < 10) {d = '0'+d;}
          // redirect...
          document.getElementById("jumpdate").value = y + "-" + m + "-" + d;
    }
};

var cal = Calendar.setup({flat: "selectors_cal_container", flatCallback: dateChanged});
var trig = document.getElementById("img_jumpdate");
document.getElementById("selectors_container").style.visibility = 'hidden';
cal.hide();
trig.onmouseover = function() {
    if (document.getElementById("selectors_container").style.visibility == 'hidden') {
        cal.show();
        document.getElementById("selectors_container").style.visibility = 'visible';
    } else {
        document.getElementById("selectors_container").style.visibility = 'hidden';
        cal.hide();
    }
}
//END (CHEMED)
</script>