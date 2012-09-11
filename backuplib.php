<?php
    
function diplome_backup_mods($bf,$preferences) {
        global $CFG;

        $status = true; 

        ////Iterate over label table
        if ($diplomes = get_records ("diplome","course", $preferences->backup_course,"id")) {
            foreach ($diplomes as $diplome) {
                if (backup_mod_selected($preferences,'label',$diplome->id)) {
                    $status = label_backup_one_mod($bf,$preferences,$diplome);
                }
            }
        }
        return $status;
}

function diplome_backup_one_mod($bf,$preferences,$diplome) {

        global $CFG;
        
        if (is_numeric($diplome)){
            $diplome = get_record('diplome','id',$diplome);
        }

        $status = true;
        fwrite($bf,start_tag("MOD",3,true));
        fwrite($bf,full_tag("ID",4,false,$diplome->id));
        fwrite($bf,full_tag("MODTYPE",4,false,"diplome"));
        fwrite($bf,full_tag("NAME",4,false,$diplome->name));
        fwrite($bf,full_tag("INTRO",4,false,$diplome->intro));
        fwrite($bf,full_tag("INTROFORMAT",4,false,$diplome->introformat));
        fwrite($bf,full_tag("TIMECREATED",4,false,$diplome->timecreated));
        fwrite($bf,full_tag("TIMEMODIFIED",4,false,$diplome->timemodified));
        
        $status = fwrite($bf, end_tag("MOD",3,true));
        
        return $status;
}


function diplome_check_backup_mods($course,$user_data=false,$backup_unique_code,$instances=null) {

         if (!empty($instances) && is_array($instances) && count($instances)) {
            $info = array();
            foreach ($instances as $id => $instance) {
                $info += label_check_backup_mods_instances($instance,$backup_unique_code);
            }
            return $info;
        }
        
         //First the course data
         $info[0][0] = get_string("modulenameplural","diplome");
         $info[0][1] = count_records("diplome", "course", "$course");
         return $info;

}

function diplome_check_backup_mods_instances($instance,$backup_unique_code) {
        $info[$instance->id.'0'][0] = '<b>'.$instance->name.'</b>';
        $info[$instance->id.'0'][1] = '';
        return $info;
}
?>