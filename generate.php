<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/mod_form.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // diplome instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('diplome', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $diplome  = $DB->get_record('diplome', array('id' => $cm->instance), '*', MUST_EXIST);
	$param = 'id';
	$val = $id; 
} elseif ($n) {
    $diplome  = $DB->get_record('diplome', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $diplome->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('diplome', $diplome->id, $course->id, false, MUST_EXIST);
	$param = 'n';
	$val = $n; 
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'diplome', 'Generate', "generate.php?id={$cm->id}", $diplome->name, $cm->id);

$PAGE->set_url('/mod/diplome/generate.php', array('id' => $cm->id));
$PAGE->set_title(format_string($diplome->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

echo $OUTPUT->header();


build_tabs('generate', $id, $n);

if(has_capability('mod/diplome:generate', $context)) {
	$options[] = new tabobject ('fpage', 
					'view.php?'.$param.'='.$val, 
					get_string('tabgenerate', 'diplome'),
					get_string('tabgeneratedesc', 'diplome'),
					true);
					
	print_diploma_generator($course, $cm, $n, $id);
}

else {
	echo 'nu are capabilitati';
}



echo $OUTPUT->footer();