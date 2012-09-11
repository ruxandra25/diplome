<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/dblib.php');
require_once($CFG->libdir.'/setuplib.php');

$id = $_POST['id'];
$a = $_POST['a'];
$userid = $_POST['userids'];
$uploaded = $_FILES['userfiles'];

if ($id) {
    if (! $cm = get_coursemodule_from_id('diplome', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = get_record('course', 'id', $cm->course)) {
        error('Course is misconfigured');
    }

    if (! $diplome = get_record('diplome', 'id', $cm->instance)) {
        error('Course module is incorrect');
    }

} else if ($a) {
    if (! $diplome = get_record('diplome', 'id', $a)) {
        error('Course module is incorrect');
    }
    if (! $course = get_record('course', 'id', $diplome->course)) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('diplome', $diplome->id, $course->id)) {
        error('Course Module ID was incorrect');
    }

} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, "diplome", "view", "view.php?id=$cm->id", "$diplome->id");

/// Print the page header

$strdiplomes = get_string('modulenameplural', 'diplome');
$strdiplome  = get_string('modulename', 'diplome');

$navlinks = array();
$navlinks[] = array('name' => $strdiplomes, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($diplome->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($diplome->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strdiplome), navmenu($course, $cm));

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

echo '<link type="text/css" rel="stylesheet" href="diplome.css"/>';
echo '<table class="choices"><tr><td>';

if (!has_capability('mod/diplome:uploaddiploma', $context)) {
    echo '<table class="choices"><tr><td>';
    echo '<p class="error">'.get_string('noaccess', 'diplome').'</p>';    
    echo '</td></tr></table>';
} else {
    /// File processing

    $pdftype = 'application/pdf';
    $pdfext = 'pdf';

    $counter = 0;


    set_time_limit(5*count($userid));

    foreach($uploaded['error'] as $counter => $error) {

        switch ($error) {

            case UPLOAD_ERR_OK: 
                $filetype = $uploaded['type'][$counter];


                if (strcmp($filetype, $pdftype)!=0) {
	            echo '<p class="error"><b>'.($uploaded['name'][$counter]).'</b> : '.get_string('pdffiletypeerror', 'diplome').'.</p>';
                 	 break; 
                }
                
                $filesize = $uploaded['size'][$counter];
                if ($filesize > 4194304) {
                    echo '<p class="error"><b>'.$uploaded['name'][$counter].'</b> : '.get_string('filesizeerror', 'diplome').'</p>';
                    break; 
                }
                $uid = $userid[$counter];
                if (($user=get_record('user', 'id', $uid)) === false) {
                    echo '<p class="error">'.get_string('nosuchuser', 'diplome').'</p>';
                    break;
                }
                
                if (get_valid_roleassign($uid, $course->id) === false) {
                    echo '<p class="error"><b>'.$user->firstname.' '.$user->lastname.'</b> : '.get_string('invalidroleassign', 'diplome').'</p>';
                    break;
                }

                $diploma = get_record('diplome_diploma', 'userid', $uid, 'courseid', $course->id);

                if ($diploma === false) {
                    $diprecord = new object();
                    $diprecord->userid = $uid;
                    $diprecord->courseid = $course->id;
                   
                } else {
                    if ($diploma->status == 2) {
                        echo '<p class=error>'.$user->firstname.' '.$user->lastname.' '.get_string('alreadyprinted', 'diplome').'</p>';
                         break;
                    }
                }

                $dir = "user/d0/".($uid);
                if ( !is_dir($CFG->dataroot.'/'.$dir) && make_upload_directory($dir, false) != $CFG->dataroot.'/'.$dir) {
                   
                    echo '<p class="error"><b>'.$dir.'</b> : '.get_string('errorcannotcreatedir', 'diplome').'.</p>';
                    break;
                }
                $add = $CFG->dataroot.'/'.$dir.'/'.$course->fullname.'.'.$pdfext;
                if (move_uploaded_file($uploaded['tmp_name'][$counter], $add)) {                                                  
                    if ($diploma === false) {
                        insert_record ('diplome_diploma', $diprecord); 
                    }
                    echo '<p class="success"><b>'.$uploaded['name'][$counter].'</b> : '.get_string('uploadsuccess', 'diplome').'.</p>';   
                
                } else {
                    echo '<p class="error"><b>'.$uploaded['name'][$counter].'</b> : '.get_string('filemoveerror', 'diplome').'.</p>'; 
                }
                break;
                                                  
            case  UPLOAD_ERR_NO_FILE:
	            break;


            default:
                echo '<p class="error"><b>'.$uploaded['name'][$counter].'</b> : '.get_string('uploaderror', 'diplome').'.</p>';
        }
        $counter++;

    } 

    $backurl = $CFG->wwwroot.'/mod/diplome/view.php?id='.$cm->id.'&page=1';

    //redirect($backurl, $message, $delay);
    /// Finish the page
echo '<div><a href="'.$backurl.'">('.get_string('continue').')</a></div>';
}

echo '</td></tr></table>';
/// Finish the page
print_footer($course);

?>
