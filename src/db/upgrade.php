<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__)."/../lib.php");
 
function xmldb_theme_recit2_upgrade($oldversion) {
    
    $newversion = 2022020902;
    if ($oldversion < $newversion) {
        theme_recit2_create_course_custom_fields();

        upgrade_plugin_savepoint(true, $newversion, 'theme', 'recit2');
    }

    return true;
}