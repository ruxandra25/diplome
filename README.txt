====================================
      Certificate Handler Module - Version 0.1.0 
    ====================================
    
  
 Author        : Lucia Rosculete
 Moodle version: 1.9.5 (untested with other versions)
    
    
    
 Table of contents
 =================
    1. Overview
    2. Features
        2a. The Student Interface
        2b. The Teacher Interface
        2c. The Management Course Teacher Interface
    3. Installation
    4. Usage instructions
    5. Bug reporting & contact
    6. Changelog
    7. Certificate Status in Database
    
    
 1. Overview
 ===========
 
 The Certificate Picker Module offers a way to handle course completion certificate generation, uploading and printing. 
 
 THIS MODULE IS PROVIDED AS-IS WITHOUT ANY WARRANTY.
 
 
 2. Features
 ===========

 This module offers three different interfaces. Their functionality is decribed below.

 2a. The Student Interface
 -----------
 
 - viewable by a user inside the courses this person has a role assignment of 'Student'

 - displays all the certificates of course completion of this student inside a table and offers the possibility to download 

 - offers the possibility to request printing or cancel previous requests for printing

 - the certificates that were already requested have a green cherry image displayed

 - the certificates that were not requested have a red cherry image displayed 

 2b. The Teacher Interface
 -----------
 
 - viewable by a teacher inside a course this person has a role assignment of 'Teacher'

 ----> The Generate tab:

    - displays a table with all the students enrolled in the course and offers the possibility  to automatically generate the certificates of course completion for a part or al of the students by checking the apropriate checkboxes

    - the teacher may select any template and a valid date to be used for generating the certificate

    - in a course without teachers, certificates cannot be generated

    - in a course with more than two teachers, certificate will contain only two of their names
    
 ----> The Upload tab

    - offers possibility to upload a zip archive with certificates that have a 'Lastname Firstname.pdf' naming scheme.  

    - offers the possibility to upload multiple files for different students at once

 2c. The Management Course Teacher Interface

    - displays tables of certificates that have active print requests
 
   - the teacher must select the course category and may select the print status of the requested certificates. The default is 'all', meaning all the certificates that were requested for print or that have already been printed are displayed.

    - in order to rule out the possibility of printing some certificate twice, the teacher must check the boxes of the certificates that were actually printed
  
    
 3. Installation
 ===============
 
 a. Extract the archive in the moodle/mod/ folder
 b. Click on the "Notifications" item in site administration.

 
 4. Usage
 ========
 4a. The Students Interface

    - select the desired action of Request or Undo Request 

    - check the apropriate checkboxes of the certificates you would like to (undo) request printing of

    - by default, the certificates are marked with a red cherry, meaning they have not been requested yet; the requested certificates are marked with a green cherry  

 4b. The Teachers Interface

    - you may generate diplomas by choosing a template and a date to be marked on the certificate

    - to upload , click the Upload tab and choose to upload a zip archive with several diplomas (the upper Submit button) at once or to upload several diplomas one by one (the lower Submit button). 
 
 4c. The management Course Teachers Interface

    - select the desired course category and certificate status (all/pendong print/done printing)
    
    - download certificates using the Download links

    - from the table, check the boxes matching the certificates that you have printed
 
 NOTE: Certificates that have already printed may not be reuploaded, regenerated or rerequested for print.   

 5. Bug reporting & contact
 ==========================
 
 Please send any bugs, suggestions, comments to lucia.rosculete@ccna.ro .
 
 
 6. Changelog
 ============
 
 nothing here yet :D
 
 7. Certificate Status in Database
 ============
 
 The database status field indicates steps in a certificate's life:
	- 0 : generated but not requested for print
	- 1 : requested for print
	- 2 : printed
	- 3 : signed
	- 4 : sealed