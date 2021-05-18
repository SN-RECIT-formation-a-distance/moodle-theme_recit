<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__)."/../lib.php");
 
function xmldb_theme_recit_install() {
    theme_recit_create_course_custom_fields();
 
    return true;
}