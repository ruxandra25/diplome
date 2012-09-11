<?php

// based on /user/pix.php

require_once('../../config.php');
require_once(dirname(__FILE__).'/dblib.php');
require_once($CFG->libdir.'/filelib.php');

if (!empty($CFG->forcelogin) and !isloggedin()) {
// protect images if login required and not logged in;
// do not use require_login() because it is expensive and not suitable here anyway
    redirect('img/error.png');
}

// disable moodle specific debug messages
disable_debugging();

$relativepath = get_file_argument('download.php');
$args = explode('/', trim($relativepath, '/'));

if (count($args) == 2) {
    $pathname = $CFG->dataroot.'/user/d0'.$relativepath;
    $lifetime = 0;

    /// Verify role assignment of the student in the specified course
    $pos = strrpos($args[1], '.pdf');
    $coursename = substr($args[1], 0, $pos);

    $user = get_record('user', 'id', $args[0]);
    if ($user === false) {
        redirect ('img/error.png');
    }
    $course = get_record('course', 'fullname', $coursename);
    if ($course === false) {
        redirect ('img/error.png');
    }

    $validdip = get_record('diplome_diploma', 'userid', $user->id, 'courseid', $course->id);
    $validra = get_valid_roleassign((int)$args[0], $course->id);
    
    if ($validra !== false && $validdip != false && file_exists($pathname) && !is_dir($pathname)) {

        send_file($pathname, $user->lastname.'_'.$user->firstname.'_'.$args[1], $lifetime);
    }
}

/// When path does not corespond -> use default instead
redirect ('img/error.png');

?>
