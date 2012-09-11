<?php  

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/db/access.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/dblib.php');
require_once(dirname(__FILE__).'/locallib.php');
	
$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // diplome instance ID
$action = optional_param('action', '', PARAM_ALPHA);
$eventid = optional_param('eventid', 0, PARAM_INT);
if ($id) {
	global $DB;
	$param = 'id';
	$val = $id;
	if (! $cm = get_coursemodule_from_id('diplome', $id)) {
		error('Course Module ID was incorrect');
	}

	if (! $course = $DB->get_record('course', array('id'=>$cm->course))) {
		error('Course is misconfigured');
	}

	if (! $diplome = $DB->get_record('diplome', array('id'=>$cm->instance))) {
		error('Course module is incorrect');
	}

} else if ($a) {
	$param = 'a';
	$val = $a;
	if (! $diplome = $DB->get_record('diplome', array('id'=>$a))) {
		error('Course module is incorrect');
	}
	if (! $course = $DB->get_record('course', array('id'=>$diplome->course))) {
		error('Course is misconfigured');
	}
	if (! $cm = get_coursemodule_from_instance('diplome', $diplome->id, $course->id)) {
		error('Course Module ID was incorrect');
	}

} else {
	error('You must specify a course_module ID or an instance ID');
}

global $DB;

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

$strdiplomes = get_string('modulenameplural', 'diplome');
$strdiplome  = get_string('modulename', 'diplome');

$PAGE->set_url('/mod/diplome/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($diplome->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$navigation = build_navigation($PAGE);

print_header_simple(format_string($diplome->name), '', $navigation, '', '', true,
			update_module_button($cm->id, $course->id, $strdiplome), navmenu($course, $cm));


$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$mgmtcourse = $DB->get_record('course', array('idnumber'=>'1000'));
echo '<link type="text/css" rel="stylesheet" href="diplome.css"/>';
echo '<table class="events"><tr><td align="left">';

if ($course->id == $mgmtcourse->id) {
	/// This is the management course 
	global $DB;
    $options[] = new tabobject ('fpage', 
				'view.php?'.$param.'='.$val, 
				get_string('tabprintstatus', 'diplome'),
				get_string('tabprintstatusdesc', 'diplome'),
				true);

    $options[] = new tabobject ('schedule',
				'event.php?'.$param.'='.$val,
				get_string('tabschedule', 'diplome'),
				get_string('tabscheduledesc', 'diplome'),
				true);

	$tabs = array($options);
	
	$active = 'schedule';
	$inactive[] = $active;
	print_tabs($tabs, $active, $inactive);
		
	switch($action) {
		case 'new':
            
			$form = data_submitted();
			if(!empty($form)) {
				$form->location = clean_text(strip_tags($form->location), '<lang><span>');
				$form->timestart = make_timestamp($form->startyr, $form->startmon, $form->startday, $form->starthr, $form->startmin);
				if ($form->duration == 1) {
					$form->timeduration = make_timestamp($form->endyr, $form->endmon, $form->endday, $form->endhr, $form->endmin) - $form->timestart;
					if ($form->timeduration < 0) {
						$form->timeduration = 0;
					}
				} else if ($form->duration==2) {
					$form->timeduration = $form->minutes * MINSECS;
				} else {
					$form->timeduration = 0;
				}
				validate_form($form, $err);
				if (count($err) == 0) {
           
					$eventid = $DB->insert_record('diplome_event', $form, true);
                    
                    /// Use the event id as the repeatid to link repeat entries together
                    if ($form->repeat != 0) {
                        $form->repeatid = $form->id = $eventid;
                        update_record('diplome_event', $form);
                    }

                    if ($form->repeat) {
                        for($i = 1; $i < $form->repeats; $i++) {
                            // What's the DST offset for the previous repeat?
                            $dst_offset_prev = dst_offset_on($form->timestart);

                            $form->timestart += WEEKSECS;

                            // If the offset has changed in the meantime, update this repeat accordingly
                            $form->timestart += $dst_offset_prev - dst_offset_on($form->timestart);

                            /// Get the event id for the log record.
                            $DB->insert_record('diplome_event', $form, true);  
                        }
                    }
                }  else {
		            foreach ($err as $key => $value) {
		    		    $focus = 'form.'.$key;
		    	    }
			    }
		    }
		break;
		case 'edit':
            $title = get_string('editevent', 'calendar');
            $event = $DB->get_record('diplome_event', array('id'=>$eventid));
            $repeats = optional_param('repeats', 0, PARAM_INT);
						
            if($event === false) {
                error('Invalid event');
            }
          
            if($form = data_submitted()) {

				$form->location = clean_text(strip_tags($form->location), '<lang><span>');				
                $form->timestart = make_timestamp($form->startyr, $form->startmon, $form->startday, $form->starthr, $form->startmin);
                if($form->duration == 1) {
                    $form->timeduration = make_timestamp($form->endyr, $form->endmon, $form->endday, $form->endhr, $form->endmin) - $form->timestart;
                    if($form->timeduration < 0) {
                        $form->timeduration = 0;
                    }
                }
                else if($form->duration == 2) {
                    $form->timeduration = $form->minutes * MINSECS;
                }
                else {
                    $form->timeduration = 0;
                }

                validate_form($form, $err);

                if (count($err) == 0) {

                    if($event->repeatid && $repeats) {
                        // Update all
                        if($form->timestart >= $event->timestart) {
                            $timestartoffset = 'timestart + '.($form->timestart - $event->timestart);
                        }
                        else {
                            $timestartoffset = 'timestart - '.($event->timestart - $form->timestart);
                        }

                        execute_sql('UPDATE '.$CFG->prefix.'diplome_event SET '.
                            'location = \''.$form->location.'\','.
                            'timestart = '.$timestartoffset.','.
                            'timeduration = '.$form->timeduration.' '.
                            'WHERE repeatid = '.$event->repeatid);

                        }

                    else {
                        // Update this
                        execute_sql('UPDATE '.$CFG->prefix.'diplome_event SET '.
                            'location = \''.$form->location.'\','.
                            'timestart = '.$form->timestart.','.
                            'timeduration = '.$form->timeduration.' '.
                            'WHERE id = '.$eventid);						
				
                    }

                    // OK, now redirect to normal view
                    redirect('event.php?'.$param.'='.$val);
                }
                else {
                    foreach ($err as $key => $value) {
                        $focus = 'form.'.$key;
                    }
                }
            }
        break;
		case 'delete':
            $title = get_string('deleteevent', 'calendar');
            $event = $DB->get_record('diplome_event', array('id'=>$eventid));
            if($event === false) {
                error('Invalid event');
            }

        break;
		
	}
	
	switch($action) {
		case 'new':
			$form->timestart = time();
			$form->location = '';                    
            $form->timeduration = 0;
            $form->duration = 0;
            $form->repeat = 0;
            $form->repeats = '';
            $form->minutes = '';
            include('event_new.html');
			print_adminevent_table($param, $val);
		break;
		case 'edit':
            if(empty($form)) {
                $form->location = clean_text($event->location);
                $form->timestart = $event->timestart;
                $form->timeduration = $event->timeduration;
                $form->id = $event->id;
                if($event->timeduration > HOURSECS) {
                    $form->duration = 1;
                    $form->minutes = '';
                }
                else if($event->timeduration) {
                    // Up to one hour, "minutes" mode probably is better here
                    $form->duration = 2;
                    $form->minutes = $event->timeduration / MINSECS;
                }
                else {
                    // No duration
                    $form->duration = 0;
                    $form->minutes = '';
                }
            }

            if($event->repeatid) {
                $fetch = $DB->get_record_sql('SELECT 1, COUNT(id) AS repeatcount FROM '.$CFG->prefix.'diplome_event WHERE repeatid = '.$event->repeatid);
                $repeatcount = $fetch->repeatcount;
            }
            else {
                $repeatcount = 0;
            }

            echo '<div class="header">'.get_string('editevent', 'calendar').'</div>';
            include('event_edit.html');
			print_adminevent_table($param, $val);
        break;
		case 'delete':
            $confirm = optional_param('confirm', 0, PARAM_INT);
            $repeats = optional_param('repeats', 0, PARAM_INT);
            if($confirm) {
                // Kill it and redirect to event view
                if(($event = $DB->get_record('diplome_event', array('id'=>$eventid)) !== false)) {
					
                    if($event->repeatid && $repeats) {
                        delete_records('diplome_event', 'repeatid', $event->repeatid);
                    }
                    else {
                        delete_records('diplome_event', 'id', $eventid);
                    }
                }
				//echo '</td></tr></table>';
	            redirect('event.php?'.$param.'='.$val);

            }
            else {
                $eventtime = usergetdate($event->timestart);

                if($event->repeatid) {
                    $fetch = $DB->get_record_sql('SELECT 1, COUNT(id) AS repeatcount FROM '.$CFG->prefix.'diplome_event WHERE repeatid = '.$event->repeatid);
                    $repeatcount = $fetch->repeatcount;
                }
                else {
                    $repeatcount = 0;
                }

                // Display confirmation form
                echo '<div class="header">'.get_string('deleteevent', 'calendar').'</div>';
                echo '<h2>'.get_string('confirmeventdelete', 'calendar').'</h2>';
                if($repeatcount > 1) {
                    echo '<p>'.get_string('youcandeleteallrepeats', 'calendar', $repeatcount).'</p>';
                }
                echo '<div class="eventlist">';
                $event->time = calendar_format_event_time($event, time(), '', false);
				echo '<table class="generaltable" width="60%">';
                print_event($event, $param, $val, true);
				echo '</table><br/>';
                echo '</div>';
                include('event_delete.html');
            }
        break;

		default:
			print_adminevent_table($param, $val);
			echo '<tr><td align="center">';
			echo '<form action="event.php" method="get">';
			echo '<input type="hidden" name="'.$param.'" value="'.$val.'" />';
			echo '<input type="hidden" name="action" value="new" />';
			echo '<input class="singleb" type="submit" value="'.get_string('addnewevent', 'diplome').'" />';
			echo '</form>';
			echo '</td></tr>';
			
		break;
		
	}
    echo '</td></tr></table>';
    $OUTPUT->footer();
	
}

function validate_form(&$form, &$err) {

    if(!checkdate($form->startmon, $form->startday, $form->startyr)) {
        $err['timestart'] = get_string('errorinvaliddate', 'calendar');
    }
    if($form->duration == 2 and !checkdate($form->endmon, $form->endday, $form->endyr)) {
        $err['timeduration'] = get_string('errorinvaliddate', 'calendar');
    }
    if($form->duration == 2 and !($form->minutes > 0 and $form->minutes < 1000)) {
        $err['minutes'] = get_string('errorinvalidminutes', 'calendar');
    }
    if (!empty($form->repeat) and !($form->repeats > 1 and $form->repeats < 100)) {
        $err['repeats'] = get_string('errorinvalidrepeats', 'calendar');
    }
}

?>
