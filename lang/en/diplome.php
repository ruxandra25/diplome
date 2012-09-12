<?php

$string['incaseofunavailablecontactinstructor'] = 'In case of unavailable certificates, please contact your class instructor.';
$string['diplome'] = 'Cerificate Picker';

$string['modulename'] = 'Certificate Picker';
$string['modulenameplural'] = 'Certificate Pickers';

$string['diplomefieldset'] = 'Custom example fieldset';
$string['diplomename'] = 'Certificate Picker Name';


/// diplome_diploma database inconsistencies
/// The management course print action messages
$string['tabprintstatus'] = 'Print Status';
$string['tabprintstatusdesc'] = 'Manage certificates with requests for print';
$string['tabschedule'] = 'Schedule';
$string['tabscheduledesc'] = 'Manage schedule';
$string['invaliduseridindatabase'] = 'The certificate with this ID has invalid userid (user might have been deleted)';

$string['invalidcourseidindatabase'] = 'The certificate with this ID has invalid courseid (course might have been deleted)';

$string['invalidcourseidinselectedshow'] ='The certificate with this ID pertains to a different category';

$string['certificatefiledeleted'] = 'The file has been deleted from the server';
$string['certificatefileprinted'] = 'The certificate file has already been printed';

$string['invalidroleassignindatabase'] = 'The certificate with this ID has invalid assignment of student to course';
$string['studentviewinvalidroleassignindatabase'] = 'You have been unenrolled from this course';

/// locallib.php : print_course_select() messages
$string['nocategories'] = 'No course categories to display.';
$string['selectcategory'] = 'Course category';
$string['selectnocategory'] = ' ';
$string['showstatusall'] = 'All';
$string['showstatusrequested'] = 'Requested by students';
$string['showstatusprinted'] = 'Printed';
$string['showstatussigned'] = 'Signed by instructor';

/// locallip.php : print_mgmt_table() messages
$string ['nocoursesinthiscategory'] = 'There are no courses in this category';
$string['nocoursecertificateswithstatus'] = 'There are no current certificates with this status';
$string['confirmcertificateaction'] = 'Confirm action';

/// locallib.php : print_course_diplomas_table() messages
$string['headerstudent'] = 'Student';
$string['headerdownload'] = 'Available';
$string['headerrequested'] = 'Requested by student';
$string['headerprinted'] = 'Printed';
$string['headersigned'] = 'Signed by instructor';
$string['headerready'] = 'Ready';

$string['printed'] = 'Printed';
$string['novalidrequests'] = 'None of the requests for print in this course are valid!';

/// The regular course request print messages
$string['noaccess'] = 'You do not have the right to view this page';
$string['studentmoduleheader'] = 'Current certificates';
$string['selectaction'] = 'Select action:';
$string['selectnoaction'] = ' ';
$string['wrongcertificateowner'] = 'This certificate belongs to a different user.';
//$string['nouploadedcertificates'] = 'You have no uploaded certificates';
//$string['novaliduploadedcertificates'] = 'You have no valid uploaded certificates';
$string['statusnotrequested'] = 'Not requested';
$string['statusrequested'] = 'Waiting for print';
$string['headercourse'] = 'Course';
$string['headerstatus'] = 'Print status';
$string['printrequest'] = 'Request printing';
$string['printrequestundo'] = 'Undo request printing';
$string['submittorequestbutton'] = 'Request';
$string['submittoundorequestbutton'] = 'Undo Requests';

$string['tabgenerate'] = 'Generate';
$string['tabgeneratedesc'] = 'Manage student diplomas to be automatically generated';
$string['tabupload'] = 'Upload';
$string['tabuploaddesc'] = 'Manage student diplomas to upload';

$string['invalidtimestamp'] = 'The date you have chosen is not between course startdate and currentdate';
$string['nosuchtemplate'] = 'The template you have requested does no longer exist on the server';
$string['nosuchuser'] = 'The requested user des not exist';
$string['invalidroleassign'] = 'The specified user is not enrolled in this course';
$string['pdffilewriteerror'] = 'File write error';
$string['alreadyprinted'] = 'Certificate has already been printed.';

/// locallib.php : print_diploma_generator() messages
$string['nostudents'] = 'There are no students enrolled in this course';
$string['headeruserpic'] = '    ';
$string['headergenerate'] = 'Generate';
$string['generateallbutton'] = 'Generate selected certificates';

/// dblib.php : get_instructors()
$string['noteachersincourse'] = 'There are no teachers assigned to this course. Diplomas can not be generated';
$string['toomanyteachersincourse'] = 'There are more than two teachers assigned to this course. Template will allow only two';

/// locallib.php : print_template_select()
$string['selecttemplate'] = 'Select template: ';
$string['selectyear'] = 'Year';
$string['selectmonth'] = 'Month';
$string['selectday'] = 'Day';

$string['january'] = 'January';
$string['february'] = 'February';
$string['march'] = 'March';
$string['april'] = 'April';
$string['may'] = 'May';
$string['june'] = 'June';
$string['july'] = 'July';
$string['august'] = 'August';
$string['september'] = 'September';
$string['october'] = 'October';
$string['november'] = 'November';
$string['december'] = 'December';

/// locallib.php : print_date_select()
$string['leaveblankforcurrent'] = 'Leave blank for current date';

/// locallib.php : print_upload_table() messages

$string['singleuploadwelcome'] = 'Upload archive to unzip';

$string['multipleuploadwelcome'] = 'Upload individual certificates';
$string['headerupload'] = 'Upload';


$string['unavailable'] = 'Unavailable';

/// uploadsingle.php messages
$string['usernotfound'] = 'The specified username was not found';
$string['errorcannotcreatedir'] = 'Cannot create user directory';
$string['unzipsuccess'] = 'certificate has been successfully extracted to user';
$string['matchingtoomanyusers'] = 'File matches too many users';
$string['zipopenerror'] = 'Unzip error';
$string['uploaderror'] = 'Upload error';
$string['uploadsuccess'] = 'File has been successfully uploaded';

/// uploadmultiple.php messages
$string['pdffiletypeerror'] = 'File is not a pdf';
$string['filemoveerror'] = 'File cannot be moved to destination';
$string['filesizeerror'] = 'File exceeds maximum file size';

/// event.php
$string['noeventstodisplay'] = 'There are no scheduled certificate take-away events.';
$string['eventlocation'] = 'Location';
$string['addnewevent'] = 'Add new certificate take-away event';
$string['studenteventtableheader'] = 'Scheduled certificate take-away events';

$string['pluginadministration'] = 'Plugin Admin pentru Vasilica';
$string['view'] = 'Current Certificate';
$string['rollview'] = 'Current Certificate';
$string['generate'] = 'Generate';
$string['upload'] = 'Upload';
$string['printstatus'] = 'Print Status';
$string['schedule'] = 'Schedule';
$string['rollgenerate'] = 'Generate';
$string['rollupload'] = 'Upload';
$string['rollprintstatus'] = 'Print Status'; 

$string['dipl'] = 'Diplome pentru studenti'; 
$string['certificate_template'] = 'Certificate Template';
$string['name'] = 'Nume'; 
$string['description'] = 'Description'; 
$string['imagelabel'] = 'Choose Template'; 


$string['add'] = 'Add'; 
$string['rolladd'] = 'RollAdd'; 
$string['edit'] = 'Edit'; 
$string['rolledit'] = 'RollEdit'; 

$string['diploma_template_added'] = 'The Diploma Template has been Successfully added ';
$string['diplometemplatesadmintitle'] = 'Diplome Templates AdminTitle';


 
?>
