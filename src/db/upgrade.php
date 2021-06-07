<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__)."/../lib.php");
 
function xmldb_theme_recit_upgrade($oldversion) {
    
    if ($oldversion < 2021060701) {
        theme_recit_create_course_custom_fields();

        upgrade_plugin_savepoint(true, 2021060701, 'theme', 'recit');
    }

    return true;
}