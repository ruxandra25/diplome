<?php  // $Id: lib.php,v 1.7.2.5 2009/04/22 21:30:57 skodak Exp $

/**
 * Library of functions and constants for module diplome
 * This file should have two well differenced parts:
 *   - All the core Moodle functions, neeeded to allow
 *     the module to work integrated in Moodle.
 *   - All the diplome specific functions, needed
 *     to implement all the module logic. Please, note
 *     that, if the module become complex and this lib
 *     grows a lot, it's HIGHLY recommended to move all
 *     these module specific functions to a new php file,
 *     called "locallib.php" (see forum, quiz...). This will
 *     help to save some memory when Moodle is performing
 *     actions across all modules.
 */

/// (replace diplome with the name of your module and delete this line)

$diplome_EXAMPLE_CONSTANT = 42;     /// for example


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $diplome An object from the form in mod_form.php
 * @return int The id of the newly inserted diplome record
 */
function diplome_add_instance($diplome) {
	global $DB;
    $diplome->timecreated = time();

    # You may have to add extra stuff in here #

    return $DB->insert_record('diplome', $diplome);
}


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $diplome An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function diplome_update_instance($diplome) {

    $diplome->timemodified = time();
    $diplome->id = $diplome->instance;

    # You may have to add extra stuff in here #

    return update_record('diplome', $diplome);
}


/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function diplome_delete_instance($id) {

  /*  if (! $diplome = get_record('diplome', 'id', $id)) {
        return false;
    }

    $result = true;

    # Delete any dependent records here #

    if (! delete_records('diplome', 'id', $diplome->id)) {
        $result = false;
    }*/

    return true;
}


/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 */
function diplome_user_outline($course, $user, $mod, $diplome) {
    $return = new object();
    $return->time='';
    $return->info=get_string('outlinedescription','diplome');
    return $return;
}


/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function diplome_user_complete($course, $user, $mod, $diplome) {
    return true;
}


/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in diplome activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function diplome_print_recent_activity($course, $isteacher, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}


/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function diplome_cron () {
    return true;
}


/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of diplome. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $diplomeid ID of an instance of this module
 * @return mixed boolean/array of students
 */
function diplome_get_participants($diplomeid) {
    return false;
}


/**
 * This function returns if a scale is being used by one diplome
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $diplomeid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 */
function diplome_scale_used($diplomeid, $scaleid) {
    $return = false;

    //$rec = get_record("diplome","id","$diplomeid","scale","-$scaleid");
    //
    //if (!empty($rec) && !empty($scaleid)) {
    //    $return = true;
    //}

    return $return;
}


/**
 * Checks if scale is being used by any instance of diplome.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any diplome
 */
function diplome_scale_used_anywhere($scaleid) {
    if ($scaleid and record_exists('diplome', 'grade', -$scaleid)) {
        return true;
    } else {
        return false;
    }
}


/**
 * Execute post-install custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function diplome_install() {
    return true;
}


/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function diplome_uninstall() {
    return true;
}


function diplome_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;
	
	if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }
	
	$fs = get_file_storage();
	$file = $DB->get_record_sql('SELECT * FROM {files} WHERE itemid = ? AND contextid = ? AND filearea = ? AND component = ? AND filesize > 0', array($args[0], $context->id, 'attachments', 'diplome'));
	
	if(!$file) {
		send_file_not_found();
	}
	else {
		$storedFile = $fs->get_file_by_id($file->id);
		
		if(!$storedFile) {
			send_file_not_found();
		}
		send_stored_file($storedFile, 60*60, 0, true);  
	}
}

//////////////////////////////////////////////////////////////////////////////////////
/// Any other diplome functions go here.  Each of them must have a name that
/// starts with diplome_
/// Remember (see note in first lines) that, if this section grows, it's HIGHLY
/// recommended to move all funcions below to a new "localib.php" file.



?>
