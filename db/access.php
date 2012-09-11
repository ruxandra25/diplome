<?php
$capabilities = array(
	
    'mod/diplome:currentcertificate' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
			'student' => CAP_ALLOW
        )
    ),

    'mod/diplome:generate' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW
        )
    ),
	
	'mod/diplome:upload' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW
        )
    ),
	
	'mod/diplome:print_status' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
<<<<<<< HEAD
=======
            'manager' => CAP_ALLOW
        )
    ),
	
	'mod/diplome:schedule' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
>>>>>>> 7ca7a867065cefed6cbbf9d055e719fc2c207200
            'manager' => CAP_ALLOW
        )
    ),
	
	'mod/diplome:schedule' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'manager' => CAP_ALLOW
        )
    ),
	
);

?>
