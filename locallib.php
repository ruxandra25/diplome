<?php

require_once('../../config.php');
require_once($CFG->libdir.'/tablelib.php');
require_once(dirname(__FILE__).'/dblib.php');
require_once($CFG->dirroot.'/calendar/lib.php');

function build_tabs($active,$id='',$n=''){

global $CFG;

$options=array();
$inactive=array();
$activetwo=array();

$currenttab=$active;

if(isset($id)) {
	$options[]=new tabobject('view', 
		$CFG->wwwroot.'/mod/diplome/view.php?id='.$id, 
		get_string('view', 'diplome'), 
		get_string('rollview', 'diplome'),
		true);

	$options[]=new tabobject('generate', 
		$CFG->wwwroot.'/mod/diplome/generate.php?id='.$id, 
		get_string('generate', 'diplome'), 
		get_string('rollgenerate', 'diplome'),
		true);
		
	$options[]=new tabobject('upload', 
		$CFG->wwwroot.'/mod/diplome/upload.php?id='.$id, 
		get_string('upload', 'diplome'), 
		get_string('rollupload', 'diplome'),
		true);
	$options[]=new tabobject('print_status', 
		$CFG->wwwroot.'/mod/diplome/print_status.php?id='.$id, 
		get_string('printstatus', 'diplome'), 
		get_string('rollprintstatus', 'diplome'),
		true);
		
	$options[]=new tabobject('schedule', 
		$CFG->wwwroot.'/mod/diplome/schedule.php?id='.$id, 
		get_string('schedule', 'diplome'), 
		get_string('schedule', 'diplome'),
		true);
 
		}
else
		{
	$options[]=new tabobject('view', 
		$CFG->wwwroot.'/mod/diplome/view.php?n='.$n, 
		get_string('view', 'diplome'), 
		get_string('rollview', 'diplome'),
		true);

	$options[]=new tabobject('generate', 
		$CFG->wwwroot.'/mod/diplome/generate.php?n='.$n, 
		get_string('generate', 'diplome'), 
		get_string('rollgenerate', 'diplome'),
		true);
		
	$options[]=new tabobject('upload', 
		$CFG->wwwroot.'/mod/diplome/upload.php?n='.$n, 
		get_string('upload', 'diplome'), 
		get_string('rollupload', 'diplome'),
		true);
	$options[]=new tabobject('printstatus', 
		$CFG->wwwroot.'/mod/diplome/print_status.php?n='.$n, 
		get_string('printstatus', 'diplome'), 
		get_string('rollprintstatus', 'diplome'),
		true);
		
	$options[]=new tabobject('schedule', 
		$CFG->wwwroot.'/mod/diplome/schedule.php?n='.$n, 
		get_string('schedule', 'diplome'), 
		get_string('schedule', 'diplome'),
		true);
 
		}

$tabs = array($options);
print_tabs($tabs,$currenttab,$inactive,$activetwo);
}

function build_tabs_template($active){

global $CFG;

$options=array();
$inactive=array();
$activetwo=array();

$currenttab=$active;

	$options[]=new tabobject('add', 
		$CFG->wwwroot.'/mod/diplome/add.php', 
		get_string('add', 'diplome'), 
		get_string('rolladd', 'diplome'),
		true);

	$options[]=new tabobject('edit', 
		$CFG->wwwroot.'/mod/diplome/edit.php?view=all', 
		get_string('edit', 'diplome'), 
		get_string('rolledit', 'diplome'),
		true);

$tabs = array($options);
print_tabs($tabs,$currenttab,$inactive,$activetwo);
}

function build_option($id, $name, $selected) {
	$contents = '<option label="'.$name.'" value="'.$id.'"';
	if ($selected) {
		$contents .= ' selected="selected"';
	}
	$contents .= ' >'.$name.'</option>';  
	return $contents;  
}

function print_course_categories($id, $a, $show, $showstatus) {
	global $CFG, $DB;
	$categories = $DB->get_records('course_categories');
	if (!$categories) {
		print_string('nocategories', 'diplome');
		return false;
	}
	$usefulcat = array();
	foreach ($categories as $cat) {
		if (intval(substr($cat->name, 0, 4)) != 0) {
			$usefulcat[] = $cat;
		}
	}
/*
	if (count($usefulcat) == 0) {
		print_string('nocategories', 'accounting');
		return false;
	}
*/
	echo '<label>'.print_string('selectcategory', 'diplome').'</label>';
	echo '<form method = "get" name="SelectCriteria" action="view.php">';
	if ($id!=0){
			$param='id';
			$val=$id;
	}else{
			$param='a';
			$val=$a;
	}
	echo '<input type="hidden" name="'.$param.'" value="'.$val.'" />';  
	echo '<select name="show" onChange="document.forms[\'SelectCriteria\'].submit()">';

	if ($show == 0) {
		echo '<option value="0" selected="selected">'.get_string('selectnocategory', 'diplome').'</option>';
	} else {
		echo '<option value="0">'.get_string('selectnocategory', 'diplome').'</option>';
	}
	/*
	foreach($usefulcat as $cat) {
		if ($cat->id == $show) {
			echo build_option($cat->id, $cat->name, true);
		} else {
			echo build_option($cat->id, $cat->name, false);
		}
	} */
	echo '</select>';
	print_status_select($showstatus);
	echo '</form><br/>';
	return true;   
}

function print_status_select($showstatus) {
	global $CFG;
		
	echo '<select name="showstatus" onChange="document.forms[\'SelectCriteria\'].submit()">';

	if ($showstatus == 0) {
		echo build_option(0, get_string('showstatusall', 'diplome'), true);
	} else {
		echo build_option(0, get_string('showstatusall', 'diplome'), false);
	}            
	
	if ($showstatus == 1) {
		echo build_option(1, get_string('showstatusrequested', 'diplome'), true);
	} else {
		echo build_option(1, get_string('showstatusrequested', 'diplome'), false);
	}

	if ($showstatus == 2) {
		echo build_option(2, get_string('showstatusprinted', 'diplome'), true);
	} else {
		echo build_option(2, get_string('showstatusprinted', 'diplome'), false);
	}  

	if ($showstatus == 3) {
		echo build_option(3, get_string('showstatussigned', 'diplome'), true);
	} else {
		echo build_option(3, get_string('showstatussigned', 'diplome'), false);
	} 
	
	echo '</select>';

}

// function to recursively get all the child categories from the parent $category
function get_child_course_categories($catid) {
	$catids = array($catid);
	$tosearch = array($catid);
	
	while(count($tosearch) != 0) {
		$newsearch = array();
		foreach($tosearch as $cat) {
			$newcats = get_records('course_categories', 'parent', $cat);
			if ($newcats != false) {
				foreach($newcats as $newcat) {
					$newsearch[] = $newcat->id;
					$catids[] = $newcat->id;
				}
			}
		}
		$tosearch = $newsearch;
	}
	return $catids;
}

function print_mgmt_table($id, $a, $show, $showstatus) {
	global $CFG;
	$courses = array();
	$categories = get_child_course_categories($show);
	
	foreach($categories as $cat) {
		if ($showstatus > 0) {
			$cat_courses = $DB->get_records_sql(
					"SELECT c.id, c.fullname FROM {$CFG->prefix}course c
					INNER JOIN {$CFG->prefix}diplome_diploma d
						ON c.id = d.courseid
						AND c.category = '".$cat."'
						AND d.status >= '".$showstatus."'
					ORDER BY c.fullname
					");
		} else {
			$cat_courses = get_records('course', 'category', $cat);
		}
		if($cat_courses != false) {
			foreach($cat_courses as $cc) {
				$courses[] = $cc;
			}
		}
	}
	if(!$courses) {
			echo '<p class="error">'.get_string('nocoursesinthiscategory', 'diplome').'</p>';
	}
	else {
		$validinfofound = false;
		if ($id) {
			echo '<form action="view.php?id='.$id.'&show='.$show.'&showstatus='.$showstatus.'" method="post">';
		} else if ($a) {
			echo '<form action="view.php?a='.$a.'&show='.$show.'&showstatus='.$showstatus.'" method="post">';
		}
		$counter = 0;
		foreach ($courses as $course) {
			echo '<h2><a href="'.$CFG->wwwroot.'/course/view.php?id='.$course->id.'">'.$course->fullname.'</a></h2>';
			$instructors = get_instructors($course->id);
			if ($instructors != false) {
				echo '<p>';
				$first = true;
				foreach($instructors as $instruct) {
					if ($first) {
						$first = false;
					} else {
						echo ' ; ';
					}
					echo '<a href="mailto:'.$instruct->email.'">'.$instruct->firstname.' '.$instruct->lastname.'</a>';
				}
				echo '</p>';
			}
			$newc = print_course_diplomas_table($showstatus, $course->id, $counter);
				
			if ($newc > $counter) {
				
				$validinfofound = true;
				$counter = $newc;                    
			}
		}
		
		if ($validinfofound) {
			echo '<input type="hidden" name="action" value="1" />';
			echo '<input type="submit" name="submit" value="'.get_string('confirmcertificateaction', 'diplome').'" />';
		}
		echo '</form>';
		
	}
}

function print_course_diplomas_table($showstatus, $cid, $i) {
	global $CFG, $DB;

	/// Print students table with the available diplomas
	if ($showstatus == 0) {
		$studentds = $DB->$DB->get_records_sql(
			"SELECT d.id, d.courseid, d.userid, d.status, u.firstname, u.lastname, c.fullname
			FROM {$CFG->prefix}course c
			INNER JOIN {$CFG->prefix}diplome_diploma d
				ON c.id = '".$cid."'
				AND c.id = d.courseid 
			INNER JOIN {$CFG->prefix}user u
				ON d.userid = u.id
			ORDER BY u.lastname, u.firstname;
			");
	} else {
		$studentds = $DB->get_records_sql(
			"SELECT d.id, d.courseid, d.userid, d.status, u.firstname, u.lastname, c.fullname
			FROM {$CFG->prefix}course c
			INNER JOIN {$CFG->prefix}diplome_diploma d
				ON c.id = '".$cid."'
				AND c.id = d.courseid
				AND d.status >= '".$showstatus."'
			INNER JOIN {$CFG->prefix}user u
				ON d.userid = u.id
			ORDER BY u.lastname, u.firstname;
			");
	}
	
	if (!$studentds) {
		echo '<p class="error">'.get_string('nocoursecertificateswithstatus', 'diplome').'</p>';
	} else {
		
		$table = new html_table();
		$table->width = '60%';
		$table->class = 'generaltable overviewtable';
		$table->data = array();      
		$table->head = array(get_string('headerstudent', 'diplome'), 
							get_string('headerdownload', 'diplome'),
							get_string('headerrequested', 'diplome'),
							get_string('headerprinted', 'diplome'),
							get_string('headersigned', 'diplome'),
							get_string('headerready', 'diplome'));
		$table->align = array('center', 'center', 'left', 'left', 'left', 'left');
		$table->size = array('','','','','','');

		foreach ($studentds as $studentd) {
			
			$roleassign = get_valid_roleassign($studentd->userid, $studentd->courseid);
			$filepath = $studentd->userid.'/'.$studentd->fullname.'.pdf';
			if ($roleassign === false) {
				echo '<p class="error"><b>'.$studentd->id.'</b> : '.get_string('invalidroleassignindatabase', 'diplome').'</p>';
				// delete_record('diplome_diploma', 'id', $student->id);
				// unlink($CFG->dataroot.'/'.$filepath);
				continue;           
			}
			
			if (!file_exists($CFG->dataroot.'/user/d0/'.$filepath)) {
				echo '<b>'.$studentd->userid.'/'.$studentd->fullname.'.pdf'.'</b> : ';
				echo '<p class="error"><b>'.$filepath.'</b> : '.get_string('certificatefiledeleted', 'diplome').'</p>';
				// delete_records('diplome_diploma', 'id', $student->id);
				continue;
			}
			else {
			echo 'vasilica';
				$row = array();
				$row[] = $studentd->firstname.' '.$studentd->lastname;
				$row[] = '<a href="'.$CFG->wwwroot.'/mod/diplome/download.php/'.$filepath.'">Download</a>';
				if ($studentd->status == 0){	
					$row[] = '<img src="img/redcherry.png" alt="'.get_string('no', 'diplome').'"/>';
				} else {
					$row[] = '<img src="img/greencherry.png" alt="'.get_string('yes', 'diplome').'"/>';
				}
				$column = 1;
				do {
					$inputs = '';
					if ($studentd->status == $column){
						$inputs = '<input type="checkbox" name="actions[]" value="'.$i.'"/>';
						$inputs .= '<input type="hidden" name="dids[]" value="'.$studentd->id.'"/>';
						$inputs .= '<input type="hidden" name="dstatus[]" value="'.$studentd->status.'"/>';
						$i++;
						
					} else if ($studentd->status > $column) {
						$inputs = '<img src="img/green.gif" alt="'.get_string('yes', 'diplome').'"/>';
					}
					$column++;
					$row[] = $inputs;
				} while($column < 4);
				$table->data[] = $row;
			}
		}
		echo '<br/>';
		if (!$table->data) {
			echo '<p class="error">'.get_string('novalidrequests', 'diplome').'</p>';
			return $i;
		}
	
		echo html_writer::table($table);
		echo '<br/>';
		return $i;
	}
}

function print_action_select($id, $a, $action) {
	echo '<label>'.print_string('selectaction', 'diplome').'</label>';
	echo '<form method = "get" name="SelectAction" action="view.php">';
	if ($id!=0){
			$param='id';
			$val=$id;
	}else{
			$param='a';
			$val=$a;
	}
	echo '<input type="hidden" name="'.$param.'" value="'.$val.'" />';  
	echo '<select name="action" onChange="document.forms[\'SelectAction\'].submit()">';

	if ($action == 0) {
		echo '<option value="0" selected="selected">'.get_string('printrequest', 'diplome').'</option>';
	} else {
		echo '<option value="0">'.get_string('printrequest', 'diplome').'</option>';
	}

	if ($action == 1) {
		echo '<option value="1" selected="selected">'.get_string('printrequestundo', 'diplome').'</option>';
	} else {
		echo '<option value="1">'.get_string('printrequestundo', 'diplome').'</option>';
	}

	echo '</select>';
	echo '</form><br/>';

}

function print_student_diplomas_table($user, $param, $val) {

	global $CFG, $DB, $OUTPUT;

	
	$courses = get_coursesofstudent($user->id);

	if ($courses!= false) {
		$table = new html_table();
		$table->width = '60%';
		$table->class = 'generaltable overviewtable';
		$table->data = array();       

		$table->head = array(get_string('headercourse', 'diplome'), 
							get_string('headerdownload', 'diplome'), 
							get_string('headerrequested', 'diplome'),
							get_string('headerprinted', 'diplome'),
							get_string('headersigned', 'diplome'),
							get_string('headerready', 'diplome'));
		$table->size = array('', '', '', '', '', '');
		$table->align = array('center', 'center', 'left', 'left', 'left', 'left');
		
		$i = 0;
		$validaction = false;
		$readycount = false;
		$unavailable = false;
		foreach($courses as $course) {
			
			$coursename = $course->fullname;          
			$row = array();
			$row[] = '<a href="'.$CFG->wwwroot.'/course/view.php?id='.$course->id.'">'.$coursename.'</a>';

			$dip = $DB->get_record('diplome_diploma', array('courseid'=> $course->id, 'userid'=>$user->id));
			if ($dip == false) {
				$row[] = get_string('unavailable', 'diplome');
				$row[] = ''; $row[] = ''; $row[] = ''; $row[] = '';
				$unavailable = true;
			} else {
				$filepath = ($user->id).'/'.($coursename).'.pdf';
				if (!file_exists($CFG->dataroot.'/user/d0/'.$filepath)) {
					/// Database entry but no file
					echo '<p class="error"><b>'.($user->id).'/'.($coursename).'.pdf'.'</b> : '.get_string('certificatefiledeleted', 'diplome').'</p>';
					// delete_records('diplome_diploma', 'id', $dip->id);            
					$row[] = get_string('unavailable', 'diplome');
					$row[] = ''; $row[] = ''; $row[] = ''; $row[] = '';
				} else {
					$row[] = '<a href="'.$CFG->wwwroot.'/mod/diplome/download.php/'.$filepath.'">Download</a>';
					
					// can this diploma be requested?
					if ($dip->status < 1) {
						$inputs ='<input type="checkbox" name="actions[]" value="'.$i.'"/>';
						$inputs .= '<input type="hidden" name=dipids[]" value="'.$dip->id.'"/>';
						$validaction = true;
						$i++;
					} else {
						$inputs = '<img src="img/green.gif" alt="'.get_string('yes', 'diplome').'"/>';
					}
					$row[] = $inputs;
					
					// check the printed, singned and ready statuses to fill the last 3 columns of the table
					$column = 2; // start with 2 , status 2 means printed
					do {
						echo $dip->status;
						if ($dip->status < $column) {
							$row[] = '<img src="img/redcherry.png" alt="'.get_string('no', 'diplome').'"/>';
						} else {
							$row[] = '<img src="img/greencherry.png" alt="'.get_string('yes', 'diplome').'"/>';
							if ($dip->status == 4) {
								$readycount = true;
							}
						}
						$column ++;
					} while ($column < 5);
				}
			} 
			$table->data[] = $row;
		}
		
		if ($validaction) {
			echo '<form action="view.php?'.$param.'='.$val.'" method="post">';
			echo html_writer::table($table);
			echo '<br/>';
			//if ($action == 0) {
			echo '<input type="submit" name="submit" value="'.get_string('submittorequestbutton', 'diplome').'" />';
			/* } else if ($action == 1){
				echo '<input type="submit" name="submit" value="'.get_string('submittoundorequestbutton', 'diplome').'" />';
			} */
			echo '</form>';
		} else {
			
			echo html_writer::table($table);
		}
		
		if ($unavailable) {
			echo '<p>'.get_string('incaseofunavailablecontactinstructor', 'diplome').'</p>';
		}
		
		if ($readycount) {
			echo '<h2>'.get_string('studenteventtableheader', 'diplome').'</h2>';
			print_studentevent_table($param, $val);
		}
		
	} else {
		print("No courses");
	}
	return true;

}

function print_upload_table($course, $cm, $a, $id) {
	global $CFG, $DB, $OUTPUT;
	$users = get_studentsincourse($course->id);

	if (!$users) {
		echo '<p>'.get_string('nostudents','diplome');
		return NULL;
	}

	echo '<h2>'.get_string('singleuploadwelcome', 'diplome').'</h2>';
	echo '<div class="uploadform"><form enctype="multipart/form-data" action="uploadsingle.php" method="post" name="uploadsingle">';

	$hiddenctx = '<input type="hidden" name="id" value="'.$id.'"/>'.
		'<input type="hidden" name="a" value="'.$a.'"/>';
	$submitbutton = '<input type="submit" name="submit" value="Unzip"/>';
	$fileform = '<p><input type="file" name="archivefile"/></p>';

	echo $hiddenctx.$fileform.$submitbutton;
	echo '</form></div>';
	//echo '</td></tr><tr><td>';

	$table_headers = array(get_string('headeruserpic', 'diplome'), get_string('headerstudent','diplome'), get_string('headerdownload','diplome'), get_string('headerupload','diplome'));

	$table = new html_table();
	$table->class = 'generaltable overviewtbale';
	$table->head = $table_headers;
	$table->data = array();
	$table->size = array('', '', '', '');
	$table->align = array('center', 'center', 'center', 'left');

	foreach($users as $user) {

		$profilelink = '<strong><a href="'.$CFG->wwwroot.'/user/view.php?id='.
				$user->id.'&amp;course='.$course->id.'">'.$user->firstname.
				' '.$user->lastname.'</a></strong>';
		
		/// Check if diploma already in database
		$filexists = get_string('unavailable', 'diplome');
		$fileform = '<input type="hidden" name="userids[]" value="'.$user->id.'"/>
				<input type="file" name="userfiles[]"/>';

		if (($diploma = $DB->get_record('diplome_diploma', array('courseid'=>$course->id), 'userid', $user->id)) !== false) {
			$filepath = ($user->id).'/'.
				($course->fullname).'.pdf';
			
			if (file_exists($CFG->dataroot.'/user/d0/'.$filepath)) {
				$filexists = '<a href="'.$CFG->wwwroot.'/mod/diplome/download.php/'.$filepath.'">Download</a>';
			} else {
				///Inconsistent data base; File deleted
				echo '<p class="error"><b>'.$filepath.'</b> : '.get_string('certificatefiledeleted', 'diplome').'</p>';
				// delete_records('diplome_diploma', 'id', $diploma->id);
			}
			/// Allow reupload only if not already printed
			if ($diploma->status == 2) {
				$fileform = get_string('certificatefileprinted', 'diplome');
			}
		}
		
		$data = array ($OUTPUT->user_picture($user, array('courseid'=>$course->id)),fullname($user));
		$table->data[] = $data;   
			//echo $OUTPUT->user_picture($user, array('courseid'=>$courseid));
	}


	echo '<h2>'.get_string('multipleuploadwelcome', 'diplome').'</h2>';
	echo '<div class="uploadform"><form enctype="multipart/form-data" action="uploadmultiple.php" method="post" name="uploadmultiple">';

	$hiddenctx = '<input type="hidden" name="id" value="'.$id.'"/>'.
		'<input type="hidden" name="a" value="'.$a.'"/>';
	$submitbutton = '<input type="submit" name="submit" value="Upload"/>';
	
	echo $hiddenctx;
	echo html_writer::table($table);

	echo $submitbutton;
	echo '</form></div>';
	return NULL;
}

function print_diploma_generator ($course, $cm, $a, $id) {
	global $CFG, $DB, $OUTPUT;
	$students = get_studentsincourse($course->id); 
	
	if (!$students) {
		echo '<p>'.get_string('nostudents','diplome');
		return NULL;
	}

	$table = new html_table();
	$table->width = '60%';
	$table->class = 'generaltable overviewtbale';
	$table->data = array();
	$table->head = array(get_string('headeruserpic', 'diplome'), get_string('headerstudent', 'diplome'), get_string('headerdownload', 'diplome'), get_string('headergenerate', 'diplome'));
	$table->align = array('center', 'center', 'center', 'left');
	$table->size = array('','','', '');

	$i=0;
	foreach($students as $user) {
		$row = array();
		$row[] = $OUTPUT->user_picture($user, array('courseid'=>$course->id));
		$row[] = '<strong><a href="'.$CFG->wwwroot.'/user/view.php?id='.
				$user->id.'&amp;course='.$course->id.'">'.$user->firstname.
				' '.$user->lastname.'</a></strong>';
	
		/// Check if diploma already in database

		/// Set the default values for the file exists and checkbox fields
		$filexists = get_string('unavailable', 'diplome');
		$checkbox = '<input type="checkbox" name="generate[]" value="'.$i.'"/>
				<input type="hidden" name="userids[]" value="'.$user->id.'"/>';
		$i++;

		if (($diploma = $DB->get_record('diplome_diploma', array('courseid'=>$course->id), 'userid', $user->id)) !== false) {
			$filepath = ($user->id).'/'.
				($course->fullname).'.pdf';
			
			if (file_exists($CFG->dataroot.'/user/d0/'.$filepath)) {
				$filexists = '<a href="'.$CFG->wwwroot.'/mod/diplome/download.php/'.$filepath.'">Download</a>';
				
			} else {
				///Inconsistent data base; File deleted
				echo '<p class="error">'.$filepath.' : '.get_string('certificatefiledeleted', 'diplome').'</p>';
				// delete_records('diplome_diploma', 'id', $diploma->id);
			}
			
			/// Allow reupload only if not already printed
			if ($diploma->status == 2) {
				$checkbox = get_string('alreadyprinted', 'diplome');
				$i--;
			}
		}

		$row[] = $filexists;
		$row[] = $checkbox;
		$table->data[] = $row;
	}

	if ($id) {
		echo '<form action="view.php?id='.$id.'&page=0" method="post">';
	} else {
		echo '<form action="view.php?a='.$a.'&page=0" method="post">';
	}

	/// Print the date selector
	print_date_select();
	/// Print the template selector
	print_template_select();
	
	echo html_writer::table($table);
	echo '<input type="hidden" name="action" value="1"/>';
	echo '<input type="submit" name="submit" value="'.get_string('generateallbutton', 'diplome').'"/>';

	echo '</form>';

}

function print_template_select () {
	global $CFG, $DB;
	echo '<h3>'.get_string('selecttemplate', 'diplome').'</h3>';
	echo '<select name="template">';


	$files = scandir($CFG->dataroot.'/user/d0/templates');
	foreach ($files as $file) {
		if ($file != "." && $file != "..") {
			echo '<option value="'.$file.'">'.$file.'</option>';
		}
	}
	echo '</select><br/><br/>'; 
	return NULL;
}

function print_date_select() {
	$months = array ('january', 'february', 'march', 'april', 'may', 'june', 
			'july', 'august', 'september', 'october', 'november', 'december');

	echo '<p>'.get_string('leaveblankforcurrent', 'diplome').'</p>';
	echo '<label for="year">'.get_string('selectyear', 'diplome').': </label>
			<select name="year" id="year" size="1" onChange="setDay()">
			<option value=" " selected="selected"> </option>';

	$crtyear = (int)date('Y');
	for ($j=0; $j<5; $j++) {
		$year = $crtyear - $j;
		echo '<option value="'.$year.'">'.$year.'</option>';
	}

	echo '</select>
			<label for="month">'.get_string('selectmonth', 'diplome').': </label>
			<select name="month" id="month" size="1" onChange="setDay()">
			<option value=" " selected="selected"> </option>';

		for ($j=1; $j<13; $j++) {
			echo '<option value="'.$j.'">'.get_string($months[$j-1], 'diplome').'</option>';
		}
		echo '</select>
			<label for="day">'.get_string('selectday', 'diplome').': </label>
			<select name="day" id="day" size="1">
			<option value=" " selected="selected"> </option>';
	for ($j=1; $j<=31; $j++) {
		echo '<option value="'.$j.'">'.$j.'</option>';
	}
	echo '</select>';
}

function check_date($year, $month, $day, $startdate) {
	if ($year==' ' || $month==' ' || $day==' ') {
		$date = date('F j, Y');
	} else {
		$reqtime = mktime(0,0,0, $month, $day, $year);
		if ($reqtime > time() || $reqtime < $startdate) {
			return false;
		}
		$date = date('F j, Y', $reqtime);
	}
	return $date;
}

// converts UTF-8 name characters into ASCII to comply with pdf ANSI Encoding
function no_more_diacritics ($string) {
	setlocale(LC_ALL, 'en_US.UTF8');
	return iconv('UTF-8', 'ASCII//TRANSLIT', $string);
}   

function print_studentevent_table($param, $val) {
	global $CFG, $DB;
	
	$eventrecs = $DB->$DB->get_records_sql("SELECT * FROM {$CFG->prefix}diplome_event WHERE timestart > ".time()." ORDER BY timestart LIMIT 5");
	if ($eventrecs) {
		echo '<table class="generaltable" cellpadding="5px">';
		foreach($eventrecs as $ev) {
			print_event($ev, $param, $val, false);
		}	
		echo '</table><br/>';  
	} else {		
		echo '<p>'.get_string('noeventstodisplay', 'diplome').'</p><br/>';
	}
}	

function print_adminevent_table($param, $val) {
	global $CFG, $DB;
	$eventrecs = $DB->$DB->get_records_sql("SELECT * FROM {$CFG->prefix}diplome_event ORDER BY timestart");
	if ($eventrecs) {
		echo '<table class="generaltable" cellpadding="5px">';
		foreach($eventrecs as $ev) {
			print_event($ev, $param, $val, true);
		}	
		echo '</table><br/>';  
	} else {		
		echo '<p>'.get_string('noeventstodisplay', 'diplome').'</p><br/>';
	}
}	

function print_event ($ev, $param, $val, $allowededit) {
	echo '<tr>';
	if ($allowededit) {
		$imgedit = '<a href="event.php?'.$param.'='.$val.'&action=edit&eventid='.$ev->id.'"><img src="img/edit.gif" alt="edit" /></a>';
		$imgdel = '<a href="event.php?'.$param.'='.$val.'&action=delete&eventid='.$ev->id.'"><img src="img/delete.gif" alt="delete" /></a>';
	} else {
		$imgedit = $imgdel = '';
	}
	$time = usergetdate($ev->timestart);
	$timeend = usergetdate($ev->timestart+$ev->timeduration);
	echo '<td style="text-align: left;">'.$imgedit.$imgdel;
	$ev->time = calendar_format_event_time($ev, time(), '', false);
	if (!empty($ev->time)) {
		echo '<span class="date">'.$ev->time.'</span>';
	} else {
		echo '<span class="date">'.calendar_time_representation($ev->timestart).'</span>';
	}
	echo '</td><td>'.$ev->location.'</td>';
	echo '</tr>';

}