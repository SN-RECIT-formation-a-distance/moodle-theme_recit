<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme functions.
 *
 * @package    theme_recit2
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/classes/local/Utils.php');

use core_customfield\category_controller;
use core_customfield\field_controller;
use theme_recit2\local\ThemeSettings;

/**
 * Load the Jquery and migration files
 * Load the our theme js file
 *
 * @param  moodle_page $page [description]
 */
function theme_recit2_page_init(moodle_page $page) {
    global $USER;
    theme_recit2_strings_for_js();

}

 /**
  * Initialise the strings required for JS.
  *
  * @return void
  */
 function theme_recit2_strings_for_js() {
     global $PAGE;
     // In order to prevent extra strings to be imported, comment/uncomment the characters
     // which are enabled in the JavaScript part of this plugin.
     
}

/**
 * Adds the cover to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_recit2_set_headerimg($theme) {
    global $OUTPUT;

    $headerimg = $theme->setting_file_url('headerimg', 'headerimg');

    if (is_null($headerimg)) {
        $headerimg = $OUTPUT->image_url('notconnected', 'theme');
    }

    $headercss = "#page-site-index.notloggedin #page-header {background-image: url('$headerimg');}";

    return $headercss;
}

/**
 * Adds the login page background image to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_recit2_set_loginbgimg($theme) {
    global $OUTPUT;

    $loginbgimg = $theme->setting_file_url('loginbgimg', 'loginbgimg');

    if (is_null($loginbgimg)) {
        $loginbgimg = $OUTPUT->image_url('login_bg', 'theme');
    }

    $headercss = "#page-login-index.recit-login #page-wrapper #page, #page-login-signup #page-wrapper #page {background-image: url('$loginbgimg');}";

    return $headercss;
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_recit2_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $scss .= file_get_contents($CFG->dirroot . "/theme/recit2/style/moodle-base.css"); // loaded here because of [[pix:]]

    // Prepend pre-scss.
    $scss .= file_get_contents($CFG->dirroot . "/theme/recit2/scss/recit/_variables.scss"); // Load variables in case current precss doesn't have all variables
    if (isset($theme->settings->prescss)) $scss .= $theme->settings->prescss;

    $scss .= file_get_contents($CFG->dirroot . "/theme/{$theme->name}/scss/recit.scss"); // scss from Theme RÉCIT

    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_recit2_get_extra_scss($theme) {
    $result = "";

    $result .= theme_recit2_set_headerimg($theme);
    $result .= theme_recit2_set_loginbgimg($theme);
    
    if(!empty($theme->settings->extrascss)){
        $result .= $theme->settings->extrascss;
    }

    return $result;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return mixed
 */
function theme_recit2_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    $theme = theme_config::load('recit2');
    

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'logo') {
        return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'headerimg') {
        return $theme->setting_file_serve('headerimg', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing1icon') {
        return $theme->setting_file_serve('marketing1icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing2icon') {
        return $theme->setting_file_serve('marketing2icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing3icon') {
        return $theme->setting_file_serve('marketing3icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing4icon') {
        return $theme->setting_file_serve('marketing4icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'loginbgimg') {
        return $theme->setting_file_serve('loginbgimg', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'favicon') {
        return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^sliderimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Get theme setting
 *
 * @param string $setting
 * @param bool $format
 * @return string
 */
function theme_recit2_get_setting($setting, $format = false) {
    $theme = theme_config::load(ThemeSettings::get_theme_name());

    if (empty($theme->settings->$setting)) {
        return false;
    } else if (!$format) {
        return $theme->settings->$setting;
    } else if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    } else if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    } else {
        return format_string($theme->settings->$setting);
    }
}

function theme_recit2_get_course_metadata($courseid, $cat) {
    $handler = \core_customfield\handler::get_handler('core_course', 'course');
    // This is equivalent to the line above.
    //$handler = \core_course\customfield\course_handler::create();
    $datas = $handler->get_instance_data($courseid, true);
    
    $result = new stdClass();    
    foreach ($datas as $data) {
        if (empty($data->get_value())) {
            continue;
        }
        if($data->get_field()->get_category()->get('name') != $cat){
            continue;
        }

        $attr = $data->get_field()->get('shortname');
        $result->$attr = $data->get_value();
    }
    return $result;
}

function theme_recit2_create_course_custom_fields(){
    $category_name = ThemeSettings::COURSE_CUSTOM_FIELDS_SECTION;
    $field_to_add = array();
    $field_to_add[] = array(
            'type' => 'checkbox',
            'name' => get_string('course-banner', 'theme_recit2'),
            'shortname' => 'img_course_as_banner',
            'description' => get_string('course-banner-help', 'theme_recit2'),
            'descriptionformat' => FORMAT_HTML,
            'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "checkbydefault" => 0)
    );

    $field_to_add[] = array(
        'type' => 'checkbox',
        'name' => get_string('show-activity-nav', 'theme_recit2'),
        'shortname' => 'show_activity_nav',
        'description' => get_string('show-activity-nav-help', 'theme_recit2'),
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "checkbydefault" => 1)
    );

    $field_to_add[] = array(
        'type' => 'checkbox',
        'name' => get_string('show-section-bottom-nav', 'theme_recit2'),
        'shortname' => 'show_section_bottom_nav',
        'description' => get_string('show-section-bottom-nav-help', 'theme_recit2'),
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "checkbydefault" => 0)
    );

    $field_to_add[] = array(
        'type' => 'checkbox',
        'name' => get_string('hide_restricted_section', 'theme_recit2'),
        'shortname' => 'hide_restricted_section',
        'description' => get_string('hide_restricted_section_help', 'theme_recit2'),
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "checkbydefault" => 1)
    );

    $field_to_add[] = array(
        'type' => 'checkbox',
        'name' => get_string('enablebreadcrumb', 'theme_recit2'),
        'shortname' => 'enablebreadcrumb',
        'description' => get_string('enablebreadcrumbdesc', 'theme_recit2'),
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "checkbydefault" => 1)
    );

    $field_to_add[] = array(
        'type' => 'checkbox',
        'name' => get_string('truncatesections', 'theme_recit2'),
        'shortname' => 'truncatesections',
        'description' => get_string('truncatesectionsdesc', 'theme_recit2'),
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "checkbydefault" => 1)
    );

    $options = array();
    foreach(ThemeSettings::MENU_MODEL_LIST as $item){
        $str = "menu-$item";
        if (get_string_manager()->string_exists($str, 'theme_recit2')) {
            $options[] = get_string($str, 'theme_recit2');
        }else{
            $options[] = $item;
        }
    }
    
    $field_to_add[] = array(
        'type' => 'select',
        'name' => get_string('menu-model', 'theme_recit2'),
        'shortname' => 'menumodel',
        'description' => get_string('menu-model-help', 'theme_recit2'),
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "options" => implode("\r\n", $options), "defaultvalue" => get_string("menu-m1", 'theme_recit2'))
    );

    /*$field_to_add[] = array(
        'type' => 'select',
        'name' => get_string('course-subtheme', 'theme_recit2'),
        'shortname' => 'subtheme',
        'description' => get_string('course-subtheme-help', 'theme_recit2'),
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 2, "options" => implode("\r\n", ThemeSettings::SUBTHEME_LIST), "defaultvalue" => "")
    );*/

    $field_to_add[] = array(
        'type' => 'textarea',
        'name' => 'CSS Custom',
        'shortname' => 'css_custom',
        'description' => 'CSS Custom',
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "defaultvalue" => "", "defaultvalueformat" => "1")
    );

    $field_to_add[] = array(
        'type' => 'checkbox',
        'name' => get_string('navbuttonhome', 'theme_recit2'),
        'shortname' => 'themerecit2_navbuttonhome',
        'description' => get_string('navbuttonhomedesc', 'theme_recit2'),
        'descriptionformat' => FORMAT_HTML,
        'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 0, "checkbydefault" => 0)
    );

    $fields = array();
    $category = null;

    $handler = \core_customfield\handler::get_handler('core_course', 'course');
    $curcat = $handler->get_categories_with_fields();
    foreach($curcat as $cat){
        if ($cat->get('name') == $category_name){
            $category = $cat->get('id');
            
            foreach ($cat->get_fields() as $field) {
                $fields[] = $field->get('shortname');
            }
        }
    }

    if (!$category){
        $category = $handler->create_category($category_name);
    }

    $category = category_controller::create($category);
    $handler = $category->get_handler();
    foreach($field_to_add as $f){
        if (!in_array($f['shortname'], $fields)){
            $field = field_controller::create(0, (object)['type' => $f['type']], $category);
            $handler->save_field_configuration($field, (object)$f);
        }
    }
}