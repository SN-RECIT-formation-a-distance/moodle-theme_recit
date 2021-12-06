<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__)."/../lib.php");
 
function xmldb_theme_recit2_upgrade($oldversion) {
    
    if ($oldversion < 2021120303) {
        theme_recit2_create_course_custom_fields();

        upgrade_plugin_savepoint(true, 2021120303, 'theme', 'recit2');
    }

    return true;
}