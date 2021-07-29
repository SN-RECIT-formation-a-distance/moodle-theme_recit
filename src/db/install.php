<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__)."/../lib.php");
 
function xmldb_theme_recit2_install() {
    theme_recit2_create_course_custom_fields();
 
    return true;
}