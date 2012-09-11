<?php

function mycount($array) {
	if ($array == false)
		return 0;

	$i = 0;
	foreach($array as $j => $element) {
		$i++;
	}
	return $i;
	
}

function get_coursesofstudent($userid) {
	global $CFG, $DB;
	return $DB->get_records_sql(
		"SELECT c.id, c.fullname FROM {$CFG->prefix}user u
		INNER JOIN {$CFG->prefix}role_assignments ra
			ON u.id = $userid
			AND u.id = ra.userid
		INNER JOIN {$CFG->prefix}role r
			ON ra.roleid = r.id
			AND r.name = 'Student'
		INNER JOIN {$CFG->prefix}context cx
			ON ra.contextid = cx.id
			AND cx.contextlevel = ".CONTEXT_COURSE."
		INNER JOIN {$CFG->prefix}course c
			ON cx.instanceid = c.id");
}

function get_studentsincourse($cid) {
	global $CFG, $DB;
	return $DB->get_records_sql(
		"SELECT u.id, u.firstname, u.lastname, u.picture, u.imagealt
		FROM {$CFG->prefix}context cx
		INNER JOIN {$CFG->prefix}role_assignments ra 
			ON cx.id = ra.contextid
			AND cx.instanceid = $cid
			AND cx.contextlevel = ".CONTEXT_COURSE."
		INNER JOIN {$CFG->prefix}role r 
			ON ra.roleid = r.id 
			AND r.name = 'Student'
		INNER JOIN {$CFG->prefix}user u
			ON ra.userid = u.id
		ORDER BY u.lastname, u.firstname"
		);
}


function get_instructors($cid) {
	global $CFG, $DB;
	return $DB->get_records_sql(
		"SELECT u.id, u.firstname, u.lastname, u.email
		FROM {$CFG->prefix}context cx
		INNER JOIN {$CFG->prefix}role_assignments ra
			ON cx.id = ra.contextid
			AND cx.instanceid = $cid
			AND cx.contextlevel=".CONTEXT_COURSE."
		INNER JOIN {$CFG->prefix}role r
			ON ra.roleid = r.id
			AND r.id = 3
		INNER JOIN {$CFG->prefix}user u
			ON ra.userid = u.id
		ORDER BY u.firstaccess
	"
	);
}


function get_instructorsstring($cid) {
	global $CFG, $DB;
	$itables = $DB->get_records_sql(
		"SELECT u.id, u.firstname, u.lastname
		FROM {$CFG->prefix}context cx
		INNER JOIN {$CFG->prefix}role_assignments ra
			ON cx.id = ra.contextid
			AND cx.instanceid = $cid
			AND cx.contextlevel=".CONTEXT_COURSE."
		INNER JOIN {$CFG->prefix}role r
			ON ra.roleid = r.id
			AND r.id = 3
		INNER JOIN {$CFG->prefix}user u
			ON ra.userid = u.id
		ORDER BY u.firstaccess
	"
	);

	$instructors = array();
	
	if ($itables == false) {
		
		echo '<p class="error">'.get_string('noteachersincourse', 'diplome').'</p>';
		return false;

	} else {
		
		$count = 0;
		foreach ($itables as $instr) {
			$instructors[] = $instr->firstname.' '.$instr->lastname;
			$count ++;
			if ($count > 2) {
				echo '<p class="error">'.get_string('toomanyteachersincourse', 'diplome').'</p>';
				break;
			} 
		}
		if ($count == 1) {
			$instructors = array('', $instructors[0]);
		}
	}
	return $instructors;
}                     

function get_studentsbyfullname($cid, $lastname, $firstname) {
	global $CFG, $DB;
	return $DB->get_records_sql(
		"SELECT u.id, u.firstname, u.lastname
		FROM {$CFG->prefix}context cx
		INNER JOIN {$CFG->prefix}role_assignments ra 
			ON cx.id = ra.contextid
		AND cx.instanceid= $cid
			AND cx.contextlevel = ".CONTEXT_COURSE."
		INNER JOIN {$CFG->prefix}role r 
			ON ra.roleid = r.id 
			AND r.name = 'Student'
		INNER JOIN {$CFG->prefix}user u
			ON ra.userid = u.id
		WHERE u.lastname = '".$lastname."'
			AND u.firstname = '".$firstname."'"
		);
}

function get_studentsbysimilarfirstname($cid, $lastname, $firstname) {
	global $CFG, $DB;
	return $DB->get_records_sql(
		"SELECT u.id, u.firstname, u.lastname
		FROM {$CFG->prefix}context cx
		INNER JOIN {$CFG->prefix}role_assignments ra 
			ON cx.id = ra.contextid
		AND cx.instanceid = $cid
			AND cx.contextlevel = ".CONTEXT_COURSE."
		INNER JOIN {$CFG->prefix}role r 
			ON ra.roleid = r.id 
			AND r.name = 'Student'
		INNER JOIN {$CFG->prefix}user u
			ON ra.userid = u.id
		WHERE u.lastname = '".$lastname."'
			AND u.firstname LIKE '%{$firstname}%'"
		);
}

function get_valid_roleassign($userid, $courseid) {
	global $CFG, $DB;
	return $DB->get_records_sql( "SELECT ra.id
				FROM {$CFG->prefix}context cx
				INNER JOIN {$CFG->prefix}role_assignments ra 
					ON ra.userid = ".$userid."
					AND cx.id = ra.contextid
					AND cx.instanceid = ".$courseid."
					AND cx.contextlevel = ".CONTEXT_COURSE."
				INNER JOIN {$CFG->prefix}role r 
					ON ra.roleid = r.id 
					AND r.name = 'Student'
				"); 
	
}

?>
