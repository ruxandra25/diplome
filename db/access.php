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
