<?php /* Smarty version 2.3.1, created on 2015-03-18 11:50:11
         compiled from default/views/day/fancy_template.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'fetch', 'default/views/day/fancy_template.html', 23, false),
array('function', 'eval', 'default/views/day/fancy_template.html', 24, false),
array('function', 'assign', 'default/views/day/fancy_template.html', 29, false),
array('function', 'pc_sort_events', 'default/views/day/fancy_template.html', 34, false),
array('modifier', 'date_format', 'default/views/day/fancy_template.html', 29, false),
array('modifier', 'string_format', 'default/views/day/fancy_template.html', 30, false),
array('modifier', 'default', 'default/views/day/fancy_template.html', 415, false),
array('modifier', 'escape', 'default/views/day/fancy_template.html', 418, false),)); ?>










<?php if ($this->_tpl_vars['PRINT_VIEW'] == 1): ?>
    
    
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/day/orig_default.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>

<?php $this->_config_load("default.conf", null, 'local'); ?>

<?php $this->_config_load("lang.$USER_LANG", null, 'local'); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/header.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->_plugins['function']['fetch'][0](array('file' => "$TPL_STYLE_PATH/day.css",'assign' => "css"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php $this->_plugins['function']['eval'][0](array('var' => $this->_tpl_vars['css']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/global/navigation.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->_plugins['function']['assign'][0](array('var' => "dayname",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['DATE'], "%w")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php $this->_plugins['function']['assign'][0](array('var' => "day",'value' => $this->_run_mod_handler('string_format', true, $this->_run_mod_handler('date_format', true, $this->_tpl_vars['DATE'], "%d"), "%1d")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php $this->_plugins['function']['assign'][0](array('var' => "month",'value' => $this->_run_mod_handler('string_format', true, $this->_run_mod_handler('date_format', true, $this->_tpl_vars['DATE'], "%m"), "%1d")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php $this->_plugins['function']['assign'][0](array('var' => "year",'value' => $this->_run_mod_handler('string_format', true, $this->_run_mod_handler('date_format', true, $this->_tpl_vars['DATE'], "%Y"), "%4d")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>

<?php $this->_plugins['function']['pc_sort_events'][0](array('var' => "S_EVENTS",'sort' => "time",'order' => "asc",'value' => $this->_tpl_vars['A_EVENTS']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>

<div class='cal_labels'>                                  
    <?php if (count((array)$this->_tpl_vars['providers'])):
    foreach ((array)$this->_tpl_vars['providers'] as $this->_tpl_vars['provider']):
?>
    <div class='cal_column_line_label'></div>
    <div class='cal_column_label'><?php echo $this->_tpl_vars['provider']['lname']; ?>
</div>
    <?php endforeach; endif; ?>
</div>                                                    


<div class='cal_container'>                               

<?php 

//Used for sorting the event later
function compareEvents($a,$b) {
    $start_a = $a['start_slot'];
    $start_b = $b['start_slot'];
    $end_a = $a['end_slot'];
    $end_b = $b['end_slot'];
    $dur_a = $a['data']['duration'];
    $dur_b = $b['data']['duration'];
    $cat_a = $a['data']['catid'];
    $cat_b = $b['data']['catid'];

    //IN-OUT events are always the lowest in the list and show up right-most in the UI
    if (($cat_a == '2') || ($cat_a == '3') || ($cat_a == '4') || ($cat_a == '8') || ($cat_a == '11')) {
        return 1;
    }
    if (($cat_b == '2') || ($cat_b == '3') || ($cat_b == '4') || ($cat_b == '8') || ($cat_b == '11')) {
        return -1;
    }

    if (($start_a == $start_b) &&
       ($end_a == $end_b) &&
       ($dur_a == $dur_b)) {return 0;} //Events are alike

    if ($start_a == $start_b) { //Events start together
        if ($dur_a != $dur_b) { //Simply sort by duration
            return ($dur_a > $dur_b) ? -1 : 1;
        } else {
            return 0;
        }
    }

    if ($start_a < $start_b) {    //a starts earlier than b
        if ($end_a >= $end_b) { //events share slots
            if ($dur_a != $dur_b) { //Simply sort by duration
                return ($dur_a > $dur_b) ? -1 : 1;
            } else {
                return 0;
            }
        } else {                  //events do not share slots and $a is earlier than $b
        return -1;
        }
    }

    if ($start_b < $start_a) {    //b starts earlier than a
        if ($end_b >= $end_a) { //events share slots
            if ($dur_a != $dur_b) { //Simply sort by duration
                return ($dur_a > $dur_b) ? -1 : 1;
            } else {
                return 0;
            }
        } else {                  //events do not share slots and $b is earlier than $a
        return 1;
        }
    }
}

    //Configuration

    /*
    ------------------------ <= $calendar_width => -------------------------------------------
    |<= $line_label_width => | <= $column_width => ||...         |...                        |
    ------------------------------------------------------------------------------------------
    |...                     | ...                 ||...         |...                        |
    ------------------------------------------------------------------------------------------

    In a couple of places I am adding +1 to sizes. This is to account for extra 1px wide border around elements.
    */

    $day_start_y        = 30; //Used for multiday display

    $start_hour         = $GLOBALS['schedule_start'];
    $end_hour           = $GLOBALS['schedule_end'];

    $slot_minutes       = $GLOBALS['calendar_interval'];

    if ($PRINT_VIEW != 1) {
        $container_width    = $_SESSION['pc_framewidth'] - 20;
    } else {
        $container_width    = 900; //Make it fit to a landscape letter-sized paper
    }

    $line_height        = 80;

    $line_label_width   = 40;
    $scrollbar_width    = 30;

    $calendar_width     = $container_width - $scrollbar_width;

    $columns            = count($providers);
    $column_width       = ($calendar_width / $columns) - $line_label_width;

    $hour_slots         = 60 / $slot_minutes;
    $hour_height        = $hour_slots * $slot_height;

    $slots_count = ($end_hour - $start_hour) * $hour_slots + 1; //How many slots will we have per day
    $calendar_height = $line_height * ($slots_count + 1) + 20;

    //Generate columns (each column is a provider)
    // ['count']        how many columns we have altogether
    // ['pid']['index'] which column is it counting from the left (0 based)
    // ['pid']['x']     how far from the left edge the column starts (in pixels)
    $columns = array('count' => 0);
    foreach ($providers as $provider) {
        $pid = $provider['id'];
        $columns[$pid]['index'] = $columns['count']++;
        $columns[$pid]['x'] = (($columns[$pid]['index'] + 1) * ($line_label_width + 1)) + ($column_width * $columns[$pid]['index']);
    }

    //Re-Index categories array to make it more usefull
    $categories = array();
    foreach ($A_CATEGORY as $category) {
        $categories[$category['id']] = $category;
    }

    //For each day...
    foreach ($A_EVENTS as $date => $event_list) {

        //Generate labels and positions for all the calendar lines
        $lines = array();           //This will hold metadata for each visual calendar line
        $hour = $start_hour;
        $minutes = 0;

        for ($i = 1; $i <= $slots_count; $i++) {
            $lines[$i] = array();
            $lines[$i]['index'] = $i;
            $lines[$i]['ampm'] = $hour >= 12 ? '2' : '1';             //For the links
            $lines[$i]['ampm_char'] = $hour >= 12 ? 'pm' : 'am';      //Visual representation
            $lines[$i]['hour'] = $hour > 12 ? $hour - 12 : $hour;
            $lines[$i]['min'] = $minutes;
            $lines[$i]['date'] = $date;
            $lines[$i]['label'] = $lines[$i]['hour'].":".str_pad($minutes, 2, '0', STR_PAD_LEFT).'&nbsp;<br />'.$lines[$i]['ampm_char'].'&nbsp;';
            $lines[$i]['y'] = ($i-1) * ($line_height + 1) + $day_start_y; //Where to absolutely position the line

            //Go to the next slot
            $minutes += $slot_minutes;
            if (($i % $hour_slots) == 0) { //New hour starts
                $hour++;
                $minutes = 0;
            }
        }

        $arr_events = $S_EVENTS[$date];

        $events = array();      //Temporary array for enumerating the events
        $slots = array();       //This is the map of slots showing how many events share the slot and the CSS style for IN-OUT events
        $provstat = array();    //Used to gray out the slots for which the provider is not available (out of office)

        //Initialize the slot map with defaul values
        // ['adj']      Used for adjusting the chip's width and position from the left column edge (see further below)
        // ['n']        How many events share the same slot
        // ['provstat'] CSS style name as a visual cue that provider is available or not
        foreach($providers as $provider) {
            $slots[$provider['id']] = array();
            for ($i = 1; $i <= $slots_count; $i++) {
                $slots[$provider['id']][$i] = array('adj' => 0, 'n' => 0, 'provstat' => 'cal_slot_out');
            }
        }

        //Go through all the events for the day
        list($slotkey, $slotevent) = each($arr_events);
        for (; isset($slotkey); list($slotkey, $slotevent) = each($arr_events)) {
            $starth = substr($slotevent['startTime'], 0, 2);
            $startm = substr($slotevent['startTime'], 3, 2);
            $providerid = $slotevent['aid'];

            $start_slot = ($starth - $start_hour) * $hour_slots + floor($startm / $slot_minutes) + 1;
            $durminutes = ceil($slotevent['duration'] / 60); //Convert from seconds to minutes

            //$durslots is the amount of slots the chip touches, even if it does not fill a complete slot
            $durslots = ceil((($startm % $slot_minutes) + $durminutes) / $slot_minutes);
            if ($durslots == 0) {                                //Event should take up at least one slot
                $durslots = 1;
                $durminutes = $slot_minutes;
            }

            if (($start_slot + $durslots) > $slots_count) {      //Event should not be longer than visible calendar
                $durslots = $slots_count - $start_slot + 1;
                $durminutes = $durslots * $slot_minutes;
            }

            $end_slot = $start_slot + $durslots;

            //Events such as IN, OUT etc. require special handling (setting the CSS class name in the $slots map)
            if ($slotevent['catid'] == 2) {
                for ($i = $start_slot; $i <= $slots_count; $i++) {
                    $slots[$providerid][$i]['provstat'] = 'cal_slot_in';
                }
            }

            if ($slotevent['catid'] == 3) {
                for ($i = $start_slot; $i <= $slots_count; $i++) {
                    $slots[$providerid][$i]['provstat'] = 'cal_slot_out';
                }
            }

            if ($slotevent['catid'] == 4) {
                for ($i = $start_slot; $i < $start_slot + $durslots; $i++) {
                    $slots[$providerid][$i]['provstat'] = 'cal_slot_out';
                }
            }

            if ($slotevent['catid'] == 8) {
                for ($i = $start_slot; $i < $start_slot + $durslots; $i++) {
                    $slots[$providerid][$i]['provstat'] = 'cal_slot_out';
                }
            }
            //END special events handling

            //Mark slots as taken and count the chips in each slot
            for ($i = $start_slot; $i < $end_slot; $i++) {
                $slots[$providerid][$i]['n']++;
            }

            //Calculate event chip dimensions
            $x = $columns[$providerid]['x'];
            $y = $lines[$start_slot]['y'];
            $w = $column_width;
            $h = $durslots * ($line_height + 1) - 2;

            $slotevent['duration'] = $durminutes; //Convert from seconds to minutes

            //Add event to the list for rendering
            //attaching the category and event data from the database
            if ($slotevent['eid'] != '') { //For some reason empty $slotevent sometimes exist. TODO: Figure out why
                $events[$providerid][] = array(
                    'left'       => $x,
                    'top'        => $y,
                    'width'      => $w,
                    'height'     => $h,
                    'start_slot' => $start_slot,
                    'end_slot'   => $end_slot,
                    'category'   => $categories[$slotevent['catid']],
                    'data'       => $slotevent
                );
            }

        }

        $ready_events = array(); //This array will hold events with fully adjusted position


        list($providerid, $events_list) = each($events);
        for (; isset($providerid); list($providerid, $events_list) = each($events)) {

/* Sorting debug:           foreach ($events_list as $ev) {
                print('ID: '.$ev['data']['eid'].'&nbsp;');
                print('Start: '.$ev['start_slot'].'&nbsp;End:'.$ev['end_slot'].'&nbsp;');
                print('Duration: '.$ev['data']['duration'].'&nbsp;');
                print('Top: '.$ev['top'].'&nbsp;Left:'.$ev['left'].'&nbsp;');
                print('---------------<br />');
            }
            print "#########################################################################Sorting...<br />";*/

            //Sort events so that they as much as possible do NOT overlap
            usort($events_list, "compareEvents");

/* Sorting debug:            foreach ($events_list as $ev) {
                print('ID: '.$ev['data']['eid'].'&nbsp;');
                print('Start: '.$ev['start_slot'].'&nbsp;End:'.$ev['end_slot'].'&nbsp;');
                print('Duration: '.$ev['data']['duration'].'&nbsp;');
                print('Top: '.$ev['top'].'&nbsp;Left:'.$ev['left'].'&nbsp;');
                print('---------------<br />');
            }
            exit;*/

            if (!isset($ready_events[$providerid])) {$ready_events[$providerid] = array();}
            foreach ($events_list as $event) {
                $neighbors = 1;
                //Find the max neccesary width divisor (how thin to make this chip)
                //by going over each slot the chip overlays and seeing how many others are there
                for ($i = $event['start_slot']; $i < $event['end_slot']; $i++) {
                    if ($slots[$providerid][$i]['n'] > $neighbors) {$neighbors = $slots[$providerid][$i]['n'];}
                    $slots[$providerid][$i]['adj']++;
                }
                //Adjust chip position and width (the magic number 3 adjusts for chip borders and the space between chips)
                $event['width'] = floor(($column_width - ($neighbors * 3)) / $neighbors);
                $event['left']  = $event['left'] + (($event['width'] + 3) * ($slots[$providerid][$event['start_slot']]['adj'] - 1));
                $ready_events[$providerid][] = $event;
            }
          //usort($ready_events[$providerid], "compareEvents");
        }


        //Marshall some variables to Smarty engine
        $this->assign('container_width', $container_width);
        $this->assign('calendar_width', $calendar_width);
        $this->assign('date', $date);
        $this->assign_by_ref('lines', $lines);
        $this->assign('line_height', $line_height);
        $this->assign('hour_height', $hour_height);
        $this->assign('line_label_width', $line_label_width);
        $this->assign('column_width', $column_width);
        $this->assign_by_ref('events', $ready_events);
        $this->assign_by_ref('slots', $slots);
        $this->assign('date_label_y', $day_start_y - 30);
        $this->assign('calendar_height', $calendar_height);

 ?>


    <div class='cal_underlay'>
        <div class='cal_date_label' style='top: <?php echo $this->_tpl_vars['date_label_y']; ?>
'><?php echo dateformat(strtotime($date),true);  ?></div>
        <?php if (count((array)$this->_tpl_vars['lines'])):
    foreach ((array)$this->_tpl_vars['lines'] as $this->_tpl_vars['line']):
?>                       
        <div class='cal_line' style='top: <?php echo $this->_tpl_vars['line']['y']; ?>
px;'>
            <?php if (count((array)$this->_tpl_vars['providers'])):
    foreach ((array)$this->_tpl_vars['providers'] as $this->_tpl_vars['provider']):
?>              
            <div class='cal_line_label' onClick="javascript:newEvt(<?php echo $this->_tpl_vars['line']['ampm']; ?>
, <?php echo $this->_tpl_vars['line']['hour']; ?>
, <?php echo $this->_tpl_vars['line']['min']; ?>
, <?php echo $this->_run_mod_handler('date_format', true, $this->_tpl_vars['line']['date'], '%Y%m%d'); ?>
, <?php echo $this->_tpl_vars['provider']['id']; ?>
, 0)">
                
                <?php echo $this->_tpl_vars['line']['label']; ?>
&nbsp;
                
            </div>      

            <?php $this->_plugins['function']['assign'][0](array('var' => "pid",'value' => $this->_tpl_vars['provider']['id']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
            <?php $this->_plugins['function']['assign'][0](array('var' => "lineindex",'value' => $this->_tpl_vars['line']['index']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
            <div class='cal_slot <?php echo $this->_tpl_vars['slots'][$this->_tpl_vars['pid']][$this->_tpl_vars['lineindex']]['provstat']; ?>
'>&nbsp;</div>
            
            <?php endforeach; endif; ?>                                           
        </div>
        <?php endforeach; endif; ?>                                            
    </div>

    <div class='cal_overlay'>                                
    
    <?php if (count((array)$this->_tpl_vars['providers'])):
    foreach ((array)$this->_tpl_vars['providers'] as $this->_tpl_vars['provider']):
?>                  
        <?php $this->_plugins['function']['assign'][0](array('var' => "pid",'value' => $this->_tpl_vars['provider']['id']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
        <?php $this->_plugins['function']['assign'][0](array('var' => "column",'value' => $this->_tpl_vars['events'][$this->_tpl_vars['pid']]), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
        <?php if (count((array)$this->_tpl_vars['column'])):
    foreach ((array)$this->_tpl_vars['column'] as $this->_tpl_vars['event']):
?>                      
        <?php if ($this->_tpl_vars['event']['data']['catid'] == 2 || $this->_tpl_vars['event']['data']['catid'] == 3 || $this->_tpl_vars['event']['data']['catid'] == 4 || $this->_tpl_vars['event']['data']['catid'] == 8 || $this->_tpl_vars['event']['data']['catid'] == 11): ?>
            <div class='cal_event_na' style='top:    <?php echo $this->_tpl_vars['event']['top']; ?>
px;
                                             left:   <?php echo $this->_tpl_vars['event']['left']; ?>
px;
                                             width:  <?php echo $this->_tpl_vars['event']['width']; ?>
px;
                                             height: <?php echo $this->_tpl_vars['event']['height']; ?>
px;'>
            <a href='javascript:oldEvt(<?php echo $this->_run_mod_handler('date_format', true, $this->_tpl_vars['date'], "%Y%m%d"); ?>
, <?php echo $this->_tpl_vars['event']['data']['eid']; ?>
)'>
            <?php if ($this->_tpl_vars['event']['data']['catid'] == 2): ?>
            <?php  xl('IN','e');  ?>
            <?php elseif ($this->_tpl_vars['event']['data']['catid'] == 3): ?>
            <?php  xl('OUT','e');  ?>
            <?php elseif ($this->_tpl_vars['event']['data']['catid'] == 4): ?>
            <?php  xl('VACATION','e');  ?>
            <?php elseif ($this->_tpl_vars['event']['data']['catid'] == 8): ?>
            <?php  xl('LUNCH','e');  ?>
            <?php elseif ($this->_tpl_vars['event']['data']['catid'] == 11): ?>
            <?php  xl('RESERVED','e');  ?>
            <?php else: ?>
            <?php  xl('Not Available','e');  ?>
            <?php endif; ?>
            </a>
            </div>
        <?php else: ?>
            <div class='cal_event' style='top:    <?php echo $this->_tpl_vars['event']['top']; ?>
px;
                                          left:   <?php echo $this->_tpl_vars['event']['left']; ?>
px;
                                          width:  <?php echo $this->_tpl_vars['event']['width']; ?>
px;
                                          height: <?php echo $this->_tpl_vars['event']['height']; ?>
px;
                                          background-color: <?php echo $this->_tpl_vars['event']['category']['color']; ?>
;'>
           <p>
              <span class='cal_event_status'><?php echo $this->_tpl_vars['event']['data']['apptstatus']; ?>
</span>
              <a href='javascript:oldEvt(<?php echo $this->_run_mod_handler('date_format', true, $this->_tpl_vars['date'], "%Y%m%d"); ?>
, <?php echo $this->_tpl_vars['event']['data']['eid']; ?>
)'>
               <?php echo $this->_run_mod_handler('date_format', true, $this->_tpl_vars['event']['data']['startTime'], "%I:%M %p"); ?>

              </a>
           </p>

           <p><font color="<?php echo $this->_tpl_vars['event']['data']['facility']['color']; ?>
">
            <?php echo $this->_tpl_vars['event']['data']['facility']['name']; ?>

               </font></p>

            <p><?php echo $this->_tpl_vars['event']['data']['duration']; ?>
<?php  xl('min','e');  ?></p>

            <?php if ($this->_tpl_vars['event']['data']['pid'] > 0): ?>
              <p><a href='javascript:goPid(<?php echo $this->_tpl_vars['event']['data']['pid']; ?>
)'><?php echo $this->_run_mod_handler('default', true, $this->_tpl_vars['event']['data']['patient_name'], 'No name'); ?>
</a></p>
              <p><?php if ($this->_tpl_vars['event']['data']['patient_dob'] != ''): ?><?php  xl('Age','e');  ?>: <?php echo $this->_tpl_vars['event']['data']['patient_age']; ?>
<?php endif; ?></p>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['event']['data']['title'] != ''): ?><p><?php echo $this->_run_mod_handler('escape', true, $this->_tpl_vars['event']['data']['title']); ?>
</p><?php endif; ?>
            <?php if ($this->_tpl_vars['event']['data']['hometext'] != ''): ?><p>(<?php echo $this->_run_mod_handler('escape', true, $this->_tpl_vars['event']['data']['hometext']); ?>
)</p><?php endif; ?>

            </div>
        <?php endif; ?>
        <?php endforeach; endif; ?>                                             
    <?php endforeach; endif; ?>                                                
    </div>                                                    


<?php 
        //For multiday display we have to make the calendar larger
        $day_start_y += ($line_height * ($slots_count+1)) + 50;
        $calendar_height += $calendar_height;
    }

 ?>
</div>                                                    
<style>
    p {
        margin: 0px 0px 0px 2px;
        padding: 0px;
    }

    .cal_container {
        <?php if ($this->_tpl_vars['PRINT_VIEW'] != 1): ?>
            position: absolute;
            top: 80px;
        <?php else: ?>
            position: relative;
            top: 60px;
        <?php endif; ?>
        left: 2px;
        width: <?php echo $this->_tpl_vars['container_width']; ?>
px;
        <?php if ($this->_tpl_vars['PRINT_VIEW'] != 1): ?>
            height: 82%; /* Unfortunately there is no way to reliably get a viewport height. */
            overflow-y: scroll;
        <?php else: ?>
            height: <?php echo $this->_tpl_vars['calendar_height']; ?>
px;
            overflow: visible;
        <?php endif; ?>
        border: solid 1px #A2BBDD;
        padding: 0px;
        margin: 0px;
    }

    .cal_underlay {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0px;
        left: 0px;
        padding: 0px;
        margin: 0px;
    }

    .cal_overlay {
        position: absolute;
        top: 0px;
        left: 0px;
        padding: 0px;
        margin: 0px;
    }


    .cal_line {
        position: absolute;
        width: 100%;
        height: <?php echo $this->_tpl_vars['line_height']; ?>
px;
        border-bottom: solid 1px #CCCCCC;
        padding: 0px;
        margin: 0px;
    }

    .cal_slot {
        width: <?php echo $this->_tpl_vars['column_width']; ?>
px;
        height: 100%;
        float: left;
        border-left: solid 1px #CCCCCC;
        padding: 0px;
        margin: 0px;
    }

    .cal_slot_in {
        background-color: #FFFFFF;
    }
    .cal_slot_out {
        background-color: #EEEEEE;
    }

    .cal_line_label {
        width: <?php echo $this->_tpl_vars['line_label_width']; ?>
px;
        height: <?php echo $this->_tpl_vars['line_height']; ?>
px;
        float: left;
        background-color: #E8EEF7;
        color: #333399;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        font-weight: normal;
        text-align: right;
        vertical-align: top;
        padding: 0px 0px 0px 0px;
        margin: 0px;
    }

    .cal_column_line_label {
        width: <?php echo $this->_tpl_vars['line_label_width']; ?>
px;
        height: 100%;
        float: left;
        padding: 0px;
        margin: 0px;
        background-color: #E8EEF7;
        color: #333399;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        font-weight: normal;
        text-align: right;
        vertical-align: top;
        padding: 0px 0px 0px 0px;
        margin: 0px;

    }

    .cal_labels {
        position: absolute;
        width: <?php echo $this->_tpl_vars['container_width']; ?>
px;
        height: 20px;
        border: solid 1px #CCCCCC;
        padding: 0px;
        margin: 0px;
        <?php if ($this->_tpl_vars['PRINT_VIEW'] != 1): ?>
            top: 57px;
        <?php else: ?>
            top: 20px;
        <?php endif; ?>
    }

    .cal_column_label {
        top: 80px;
        left: 0px;
        width: <?php echo $this->_tpl_vars['column_width']; ?>
px;
        height: 100%;
        float: left;
        border-left: solid 1px #CCCCCC;
        text-align: center;
        font-weight: bold;
        padding: 0px;
        margin: 0px;
    }

    .cal_date_label {
        position: absolute;
        left: 0px;
        width: 100%;
        height: 20;
        text-align: center;
        font-weight: bold;
        padding: 0px;
        margin: 0px;
        background-color: #B0C4DE;
    }

    .cal_event {
        position: absolute;
        font-size: 9px;
        border-top: solid 1px #EEEEEE;
        border-bottom: solid 1px #000000;
        border-left: solid 1px #EEEEEE;
        border-right: solid 1px #000000;
        padding: 0px;
        margin: 0px;
        z-index: 1;
        background-color: #FFFFFF;
        font-family: Arial, Helvetica, sans-serif;
        font-style: italic;
        font-weight: normal;
        font-size: 11px;
        overflow: hidden;
    }

    .cal_event_na {
        position: absolute;
        font-size: 9px;
        padding: 2px;
        z-index: 1;
        margin: 0px;
        font-family: Arial, Helvetica, sans-serif;
        font-style: italic;
        font-weight: bold;
        font-size: 14px;
    }

    .cal_event_status {
        background-color:  #E8EEF7;
        color: #000000;
        font-family: Courier, mono;
        font-size: 14px;
        font-style: normal;
        font-weight: bold;
        float: right;
        border-left: solid 1px #CCCCCC;
        border-bottom: solid 1px #CCCCCC;
        margin: 0px;
        padding: 0px 1px 0px 2px;
    }
</style>

</body>
</html>
<?php endif; ?>