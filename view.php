<?php  // $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
* This page prints a particular instance of diplome
*
* @author  Your Name <your@email.address>
* @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
* @package mod/diplome
*/

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/dblib.php');
require_once(dirname(__FILE__).'/db/access.php');


$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // diplome instance ID
$show = optional_param('show', 0, PARAM_INT); // 
$showstatus = optional_param('showstatus', 0, PARAM_INT);
$action = optional_param('action', 0, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);

global $DB;
global $USER;

if ($id) {
    $cm         = get_coursemodule_from_id('diplome', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $diplome  = $DB->get_record('diplome', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $diplome  = $DB->get_record('diplome', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $diplome->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('diplome', $diplome->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'diplome', 'view', "view.php?id={$cm->id}", $diplome->name, $cm->id);

/// Print the page header

//$strdiplomes = get_string('modulenameplural', 'diplome');
//$strdiplome  = get_string('modulename', 'diplome');

$PAGE->set_url('/mod/diplome/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($diplome->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

//$navigation = build_navigation($PAGE);

echo $OUTPUT->header();

build_tabs('view',$id,$n);

/*
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$mgmtcourse = $DB->get_record('course',array('idnumber'=>'2'));
//print_r($mgmtcourse);

echo '<link type="text/css" rel="stylesheet" href="diplome.css"/>';
echo '<script type="text/javascript" src="scripts.js"></script>';

if ($course->id == $mgmtcourse->id) {
	/// This is the management course 

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

	echo '<table class="choices"><tr><td>';
	$active = 'fpage';
	$inactive[] = $active;
	print_tabs($tabs, $active, $inactive);
	/// If action was submitted
	if ($action == 1 && !empty($_POST['actions'])) {
		$dids = $_POST['dids'];
		$dstatus = $_POST['dstatus'];
		$actions = $_POST['actions'];
		
		foreach ($actions as $ai) {
			/// Verify whether the diploma corresponds to this course and this USER
			
			$req = $DB->get_record('diplome_diploma', array('id'=>$dids[$ai]));
			if ($req !== false) {
				$user = $DB->get_record('user', array('id'=>$req->userid));
				if ($user === false) {
					echo '<p class="error"><b>'.$req->id.'</b> : '.get_string('invaliduseridindatabase', 'diplome').'</p>';
					// delete_records('diplome_diploma', 'id', $req->id);
					continue; 
				}
						
				$course2 = $DB->get_record('course', array('id'=>$req->courseid));
				if($course2 === false) {
					echo '<p class="error"><b>'.$req->id.'</b> : '.get_string('invalidcourseidindatabase', 'diplome').'</p>';
					// delete_records('diplome_diploma', 'id', $requesteddip->id);
					continue;
				}                      
		
				$filepath = 'user/d0/'.$req->userid.'/'.$course2->fullname.'.pdf';
				if (!file_exists($CFG->dataroot.'/'.$filepath)) {
					echo '<p class="error"><b>'.$req->userid.'/'.$course2->fullname.'.pdf'.'</b> : '.get_string('certificatefiledeleted', 'diplome').'</p>';
					// delete_records('diplome_diploma', 'id', $req->id);
					continue;
				}   

				$roleassign = get_valid_roleassign ($req->userid, $req->courseid);
																			
				if (!$roleassign) {
					echo '<p><b>'.$req->id.'</b> : '.get_string('invalidroleassignindatabase', 'diplome').'</p>'; 
					// delete_records('diplome_diploma', 'id', $req->id);   
					// unlink($CFG->dataroot.'/'.$filepath);
					continue;
				}
					
				if ($req->status == $dstatus[$ai]) {
					$req->status += 1;
					update_record('diplome_diploma', $req);
				} 
				
			}   
		}
	}       
	print_course_categories($id, $a, $show, $showstatus);
	if ($show != 0) {
		print_mgmt_table($id, $a, $show, $showstatus);
	}

		
} else {
	echo '<table class="choices"><tr><td>';
	/// In a regular course
	if (!has_capability('mod/diplome:uploaddiploma', $context)) {
		if (!has_capability('mod/diplome:viewdiploma', $context)) {
			echo '<p class="error">'.get_string('noaccess', 'diplome').'</p>';    
		} else {
		/// Print student print options for current diplomas
			echo '<h2>'.get_string('studentmoduleheader', 'diplome').'</h2>';

			/// TODO First check if there are submitted print requests
		
			if (!empty($_POST['actions'])) {
				$dipids = $_POST['dipids'];
				$actions = $_POST['actions'];
			// if ($actions !== false)  {  
					foreach ($actions as $act) {
						/// Verify whether the diploma corresponds to this course and this USER
						$requesteddip = $DB->get_record('diplome_diploma', 'id', $dipids[$act]);
						/// Only if requested dip exists
						if ($requesteddip !== false) {
							if ($requesteddip->userid != $USER->id) {
								echo '<p class="error">'.get_string('wrongcertificateowner', 'diplome').'</p>';
								continue;
							}

							$course2 = $DB->get_record('course', 'id', $requesteddip->courseid);
							if(!$course2) {
								echo '<p class="error"><b>'.$requesteddip->id.'</b> : '.get_string('invalidcourseidindatabase', 'diplome').'</p>';
								// delete_records('diplome_diploma', 'id', $requesteddip->id);
								continue;
							}
							
							$roleassign = get_valid_roleassign ($requesteddip->userid, $requesteddip->courseid);
								
							if (!$roleassign) {
								echo '<p class="error"><b>'.$course2->fullname.'</b> : '.get_string('studentviewinvalidroleassignindatabase', 'diplome').'</p>'; 
								// delete_records('diplome_diploma', 'id', $requesteddip->id);   
								// unlink($CFG->dataroot.'/'.$filepath);
								continue;
							}

							$filepath = 'user/d0/'.$requesteddip->userid.'/'.$course2->fullname.'.pdf';
							if (!file_exists($CFG->dataroot.'/'.    $filepath)) {
								echo '<p class="error"><b>'.$requesteddip->userid.'/'.$course2->fullname.'.pdf'.'</b> : '.get_string('certificatefiledeleted', 'diplome').'</p>';
								// delete_records('diplome_diploma', 'id', $requesteddip->id);
								continue;
							}
							
							if ($requesteddip->status>1) {
								echo '<p class="error">'.get_string('certificatefileprinted', 'diplome').'</p>';
							}
							if ($requesteddip->status == 0) {
								$requesteddip->status = 1;
								update_record('diplome_diploma', $requesteddip);
							}
						}
					}
						
			}  
			//print_action_select($id, $a, $action); 
			print_student_diplomas_table($USER, $param, $val);
		}

	} else {

		$options[] = new tabobject ('fpage', 
					'view.php?'.$param.'='.$val, 
					get_string('tabgenerate', 'diplome'),
					get_string('tabgeneratedesc', 'diplome'),
					true);

		$options[] = new tabobject ('upload',
					'view.php?'.$param.'='.$val.'&page=1',
					get_string('tabupload', 'diplome'),
					get_string('tabuploaddesc', 'diplome'),
					true);

		$tabs = array($options);

		if ($page == 0) {
			if ($action == 1 && !empty($_POST['generate'])) {
				$instructors = get_instructorsstring($course->id);
				if ($instructors !== false) {
					$uids = $_POST['userids'];
					$genactions = $_POST['generate'];

					/// Process the given date
					$year = $_POST['year'];
					$month = $_POST['month'];
					$day = $_POST['day'];
					if (($date = check_date($_POST['year'], $_POST['month'], $_POST['day'], $course->startdate)) === false) {
						echo '<p class="error">'.get_string('invalidtimestamp', 'diplome').'</p>';
					} else {
						$template = $CFG->dataroot.'/user/d0/templates/'.$_POST['template'];
						if (!file_exists($template)) {
							echo '<p class="error">'.get_string('nosuchtemplate', 'get_string').'</p>';
						} else {

							$academy = 'Academia Cisco UPB';
							$location = 'Facultatea de Automatica si Calculatoare, Universitatea Politehnica Bucuresti';
							
							foreach ($genactions as $ai) {
								/// Verify whether the diploma corresponds to this course and this USER
								$user = $DB->get_record('user', array('id'=>$uids[$ai]));
								if (!$user) {
										echo '<p class="error"><b>'.$uids[$ai].'</b> : '.get_string('nosuchuser', 'diplome').'</p>';
										continue; 
								}
													
								$roleassign = get_valid_roleassign ($uids[$ai], $course->id);
								if (!$roleassign) {
									echo '<p class="error"><b>'.$user->firstname.' '.$user->lastname.'</b> : '.get_string('invalidroleassign', 'diplome').' : <b>'.$course->fullname.'</b></p>'; 
									continue;
								}
								
								$diploma = $DB->get_record_sql(
										"SELECT d.id, d.userid, d.courseid, d.status FROM {$CFG->prefix}diplome_diploma d
										WHERE d.courseid = ".$course->id."
											AND d.userid = ".$user->id."
										");
								if ($diploma !== false) {
									if ($diploma->status > 1) {
										echo '<p class="error"><b>'.$user->firstname.' '.$user->lastname.'</b> : '.get_string('alreadyprinted', 'diplome').'</p>';
										continue;
									}
								} else {
									$diprecord = new object();
									$diprecord->userid = $user->id;
									$diprecord->courseid = $course->id;
								
								}    
								/// Now modify selected template
								$student = no_more_diacritics($user->firstname.' '.$user->lastname);
								$input = file_get_contents($template);
								$input = str_replace("__STUDENT__", $student, $input);
								$input = str_replace("__ACADEMY__", $academy, $input);
								$input = str_replace("__LOCATION__", $location, $input);
								$input = str_replace("__INSTRUCTOR1__", no_more_diacritics($instructors[0]), $input);
								$input = str_replace("__INSTRUCTOR2__", no_more_diacritics($instructors[1]), $input);
								$input = str_replace("__DATE__", $date, $input);
								$filepath = $CFG->dataroot.'/user/d0/'.($user->id).'/'.($course->fullname).'.pdf';
								$dir = '/user/d0/'.($user->id);
								if ( !is_dir($CFG->dataroot.'/'.$dir) && make_upload_directory($dir, false) != $CFG->dataroot.'/'.$dir) {
				
									echo '<p class="error"><b>'.$dir.'</b> : '.get_string('errorcannotcreatedir', 'diplome').'.</p>';
									break;
								}
								if (file_put_contents($filepath, $input) === false) {
									echo '<p class="error"><b>'.($user->id).'/'.($course->fullname).'.pdf</b> : '.get_string('pdffilewriteerror', 'diplome').'</p>';                          
								} else {
									if ($diploma === false) {
										insert_record('diplome_diploma', $diprecord);    
									}
								}                                                
							} 
						}  
					}
				}
			}
			$active = 'fpage';
			$inactive[] = $active;
			print_tabs($tabs, $active, $inactive);
			print_diploma_generator($course, $cm, $a, $id);
			
		} else {
			$active = 'upload';
			$inactive[] = $active;
			print_tabs($tabs, $active, $inactive);
			print_upload_table($course, $cm, $a, $id);
			//echo $OUTPUT->user_picture($user, array('courseid'=>$course->id));
		}
	
	}
}
*/
/// Finish the page
//echo '</td></tr></table>';
echo $OUTPUT->footer($course);

?>
