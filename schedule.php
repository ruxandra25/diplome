<?php

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

add_to_log($course->id, 'diplome', 'schedule', "schedule.php?id={$cm->id}", $diplome->name, $cm->id);

$PAGE->set_url('/mod/diplome/schedule.php', array('id' => $cm->id));
$PAGE->set_title(format_string($diplome->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

echo $OUTPUT->header();

build_tabs('schedule',$id,$n);

if(has_capability('mod/diplome:schedule', $context)) {
	echo 'are capabilitati';
}

else {
	echo 'nu are capabilitati';
}


echo $OUTPUT->footer($course);