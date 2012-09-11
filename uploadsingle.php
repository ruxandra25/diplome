<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/dblib.php');
require_once($CFG->libdir.'/setuplib.php');

$id = $_POST['id'];
$a = $_POST['a'];
$archive = $_FILES['archivefile'];

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
/// And finally the file processing
if (!has_capability('mod/diplome:uploaddiploma', $context)) {
    echo '<table class="choices"><tr><td>';
    echo '<p class="error">'.get_string('noaccess', 'diplome').'</p>';    
    echo '</td></tr></table>';
} else {
    switch ($archive['error']) { 
        case UPLOAD_ERR_OK:
            
            $zip = zip_open($archive['tmp_name']);
                 
            if (is_resource($zip)) {
                while ($zipentry = zip_read($zip)) {
                    $name = zip_entry_name($zipentry);
                    if ($name[strlen($name)-1] == '/') {
                        continue;
                    }
	            
                    $path = explode('/', $name);
		            $file = end($path);
                    $noext = explode('.', $file);
					if (strtolower($noext[1]) !== 'pdf') {
						echo '<p class="error"><b>'.($file).'</b> : '.get_string('pdffiletypeerror', 'diplome').'.</p>';
						continue;
					}
                    list($lastn, $firstn) = explode(' ', $noext[0], 2);
                    $firstns = explode(' ', $firstn);
                    $nfirst= count($firstns);
                  
                    $users = get_studentsbyfullname($course->id, $lastn, $firstn);
                  
                    if($users === false) {
                        $users = get_studentsbysimilarfirstname($course->id, $lastn, $firstn);
                        if ($users === false && $nfirst > 1) {
                            $i = 0;
                            $users = array();                        
	                        while ($i < $nfirst) {
                                $users2 = get_studentsbysimilarfirstname ($course->id, $lastn, $firstns[$i]);
                                if ( $users2 ) {
                                    $users = array_merge((array)$users, (array)$users2);                                    
                                }                                
                                $i ++;
                            }
	                    } 
                    }
                    $nuser = mycount($users);
                    if ($nuser == 0) {
                        echo '<p class="error"><b>'.$noext[0].'</b> : '. get_string('usernotfound', 
                            'diplome') .'.</p>';  

                    } else if ($nuser == 1) {

                        $diploma = get_record('diplome_diploma', 'userid', $users[key($users)]->id, 'courseid', $course->id);

                        if ($diploma === false) {
                            $diprecord = new object();
                            $diprecord->userid = $users[key($users)]->id;
                            $diprecord->courseid = $course->id;
                           
                        } else {
                            if ($diploma->status == 2) {
                                echo '<p class=error><b>'.$noext[0].'</b> : '.get_string('alreadyprinted', 'diplome').'</p>';
                                continue;
                            }
                        }

                        $dir = "user/d0/".($users[key($users)]->id);

                        if (!is_dir($CFG->dataroot.'/'.$dir) && make_upload_directory($dir, false) != $CFG->dataroot.'/'.$dir) {
                            echo '<p class="error"><b>'.$dir.'</b> : '.get_string('errorcannotcreatedir', 'diplome').'.</p>';
                            continue;
                         
                        } 

                        $add = $CFG->dataroot.'/'.$dir.'/'.$course->fullname.'.pdf';
                        $fp = fopen($add, "w");
                        zip_entry_open($zip, $zipentry, "r"); 
                        $buf = zip_entry_read($zipentry, zip_entry_filesize($zipentry));
                        fwrite($fp, "$buf");
                        zip_entry_close($zipentry);
                        fclose($fp);
                        if ($diploma === false) {
                             insert_record ('diplome_diploma',$diprecord); 
                        }
                        echo '<p class="success"><b>'.zip_entry_name($zipentry).'</b> : '.
                                get_string('unzipsuccess', 'diplome').' <b>'. 
                                $users[key($users)]->firstname.' '.$users[key($users)]->lastname.'</b>.</p>';
                       
                                              
                    } else {
                        /// If more matching users are found in database
                        echo '<p class="error"><b>'.$noext[0].'</b> : '.get_string('matchingtoomanyusers', 'diplome').
                             ': </p><ul>';
                        foreach ($users as $i => $user) {     
                            echo '<li>'.$user->firstname.' '.$user->lastname.'</li>';
                        }
                        echo '</ul>';
                    }	
                
               
                 }
                 zip_close($zip);
            
            } else {
                echo '<p class="error"><b>'.$archive['name'].'</b> :  '.get_string('zipopenerror', 'diplome').'.</p>';
            }
            break;
        
        case UPLOAD_ERR_NO_FILE:
     
            break;
        
        default:
            echo '<p class="error"><b>'.$archive['name'].'</b> : '.get_string('uploaderror', 'diplome').'.</p>';

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
