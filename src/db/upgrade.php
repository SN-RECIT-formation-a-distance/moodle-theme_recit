<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__)."/../lib.php");
 
function xmldb_theme_recit2_upgrade($oldversion) {
    
    $newversion = 2023011904;
    if ($oldversion < $newversion) {
        theme_recit2_create_course_custom_fields();

        upgrade_plugin_savepoint(true, $newversion, 'theme', 'recit2');
    }

    $newversion = 2025091602;
    if ($oldversion < $newversion) {
        $category_name = \theme_recit2\local\ThemeSettings::COURSE_CUSTOM_FIELDS_SECTION;
        $prefix = \theme_recit2\local\ThemeSettings::COURSE_CUSTOM_FIELDS_PREFIX;
        $handler = \core_customfield\handler::get_handler('core_course', 'course');
        $datas = $handler->get_categories_with_fields();
        
        $result = new stdClass();    
        foreach ($datas as $data) {
            if($data->get('name') != $category_name){
                continue;
            }
    
            foreach ($data->get_fields() as $field) {
                $attr = $field->get('shortname');
                $field->set('shortname', $prefix.$attr);
                $field->save();
            }
        }

        upgrade_plugin_savepoint(true, $newversion, 'theme', 'recit2');
    }

    return true;
}