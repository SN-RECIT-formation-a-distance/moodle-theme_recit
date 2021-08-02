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
use \core_customfield\category_controller;
use \core_customfield\field_controller;


/**
 * Load the Jquery and migration files
 * Load the our theme js file
 *
 * @param  moodle_page $page [description]
 */
function theme_recit2_page_init(moodle_page $page) {
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
     $PAGE->requires->strings_for_js(array('last_navigated'), 'theme_recit2');
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
 * Adds the footer image to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_recit2_set_topfooterimg($theme) {
    global $OUTPUT;

    $topfooterimg = $theme->setting_file_url('topfooterimg', 'topfooterimg');

    if (is_null($topfooterimg)) {
        $topfooterimg = $OUTPUT->image_url('footer-bg', 'theme');
    }

    $headercss = "#top-footer {background-image: url('$topfooterimg');}";

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

    $headercss = "#page-login-index.recit-login #page-wrapper #page {background-image: url('$loginbgimg');}";

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
    $scss .= file_get_contents($CFG->dirroot . "/theme/{$theme->name}/style/moodle-base.css"); // loaded here because of [[pix:]]
    $scss .= theme_recit2_get_scss_variables($theme); // assign the custom variables coming from Moodle Theme interface
    $scss .= file_get_contents($CFG->dirroot . "/theme/{$theme->name}/scss/recit.scss"); // scss from Theme RÉCIT
    $scss .= theme_recit2_get_subthemes_scss();

    return $scss;
}

function theme_recit2_get_scss_variables($theme, $key = ''){
    global $CFG;

    $scss_variables = [
        'color1' => '$color1',
        'color2' => '$color2',
        'ttmenucolor1' => '$tt-menu-color1',
        'ttmenucolor2' => '$tt-menu-color2',
        'ttmenucolor3' => '$tt-menu-color3',
        'ttmenucolor4' => '$tt-menu-color4',
        'navcolor' => '$nav-color',
        'primarycolor' => '$primary',
        'primaryboldcolor' => '$primaryBold',
        'secondarycolor' => '$secondary',
        'fontfamily' => '$font-family-text',
        'fontsize' => '$font-family-text-size',
        'headingsfontfamily' => '$headings-font-family',
        'btnradius' => '$btn-radius',
        'headerheight' => '$header-height',
    ];

    $varFileContent = file_get_contents($CFG->dirroot . "/theme/{$theme->name}/scss/recit/_variables.scss");
    
    $varFileContent = explode(";", $varFileContent);
    $newVarFileContent = array();
    $modified = false;

    foreach($varFileContent as $item){
        // value = [\$|\'|\#|\,|\-\w\d\s\!]*
        // look for variables, for example: /$varname:/
        if(preg_match('/\$[a-zA-z0-9-_]*\b:/', $item, $matches) == 0){
            $newVarFileContent[] = $item;
            continue;
        }

        $added = false;
        foreach ($scss_variables as $k => $varname) {
            $propname = $k.$key;
            $value = isset($theme->settings->{$propname}) ? $theme->settings->{$propname} : null;
            if (empty($value)) {
                continue;
            }

            if($varname.":" == current($matches)){
                $added = true;
                $modified = true;
                $newVarFileContent[] = $varname . ": " . $value;
            }
        }

        if(!$added){
            $modified = true;
            $newVarFileContent[] = $item;
        }
    }

    if (!empty($key) && !$modified) return false; //Subtheme doesn't have any var set so return nothing
    return implode(";", $newVarFileContent);
}

function theme_recit2_get_subthemes_scss(){
    global $CFG;
    $scss = "";
    foreach (theme_recit2_get_subthemes() as $sub){
        $vars = theme_recit2_get_scss_variables(theme_config::load('recit2'), $sub['key']);
        if ($vars){
            $scss .= ".".$sub['cssclass']." {";
            $scss .= $vars;
            $scss .= file_get_contents($CFG->dirroot . "/theme/recit2/scss/recit/_sous-theme-vars.scss");
            $scss .= file_get_contents($CFG->dirroot . "/theme/recit2/scss/recit/_treetopics.scss");
            $scss .= "}";
        }
    }
    
    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_recit2_get_extra_scss($theme) { 
    $scss = $theme->settings->scss;

    $scss .= theme_recit2_set_headerimg($theme);
    $scss .= theme_recit2_set_topfooterimg($theme);
    $scss .= theme_recit2_set_loginbgimg($theme);
    //$scss .= theme_recit2_set_course_banner_img($theme);

    return $scss;
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
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'topfooterimg') {
        return $theme->setting_file_serve('topfooterimg', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'loginbgimg') {
        return $theme->setting_file_serve('loginbgimg', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'favicon') {
        return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^sliderimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^sponsorsimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^clientsimage[1-9][0-9]?$/", $filearea) !== false) {
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
    $theme = theme_config::load('recit2');

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

/**
 * Get course theme name
 *
 * @param string $setting
 * @param bool $format
 * @return string
 */
function theme_recit2_get_course_theme($data = false) {
    global $COURSE;
    
    $theme = 'theme-recit';

    if($COURSE->id > 1){
        $customFieldsRecit = theme_recit2_get_course_metadata($COURSE->id, \theme_recit2\util\theme_settings::COURSE_CUSTOM_FIELDS_SECTION);
        if(property_exists($customFieldsRecit, 'subtheme')){
            $sub = customfield_select\field_controller::get_options_array($customFieldsRecit->subtheme->get_field())[$customFieldsRecit->subtheme->get_value()];
            if (!empty($sub)){
                $theme = $sub;
            }
        }
    }

    $subtheme = theme_recit2_get_subthemes($theme);
    if ($subtheme) $theme = $subtheme['cssclass'];
    if ($data) return $subtheme;
    return $theme;
}


function theme_recit2_get_course_metadata($courseid, $cat) {
    $handler = \core_customfield\handler::get_handler('core_course', 'course');
    // This is equivalent to the line above.
    //$handler = \core_course\customfield\course_handler::create();
    $datas = $handler->get_instance_data($courseid);
    
    $result = new stdClass();
    foreach ($datas as $data) {
        if (empty($data->get_value())) {
            continue;
        }
        if($data->get_field()->get_category()->get('name') != $cat){
            continue;
        }

        $attr = $data->get_field()->get('shortname');
        $result->$attr = $data;
    }
    return $result;
}

function theme_recit2_create_course_custom_fields(){
    $category_name = \theme_recit2\util\theme_settings::COURSE_CUSTOM_FIELDS_SECTION;
    $field_to_add = array();
    $field_to_add[] = array(
            'type' => 'checkbox',
            'name' => get_string('course-banner', 'theme_recit2'),
            'shortname' => 'img_course_as_banner',
            'description' => get_string('course-banner-help', 'theme_recit2'),
            'descriptionformat' => FORMAT_HTML,
            'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 1, "checkbydefault" => 0)
    );


    $options = '';
    foreach (theme_recit2_get_subthemes() as $sub){
        $options .= "\r\n".$sub['key'];
    }
    $field_to_add[] = array(
            'type' => 'select',
            'name' => get_string('course-subtheme', 'theme_recit2'),
            'shortname' => 'subtheme',
            'description' => get_string('course-subtheme-help', 'theme_recit2'),
            'descriptionformat' => FORMAT_HTML,
            'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 2, "options" => $options, "defaultvalue" => "")
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

function theme_recit2_get_subthemes($key = ''){
    $subthemes = array(
        array('name' => get_string('course-french', 'theme_recit2'), 'cssclass' => 'theme-recit-francais', 'key' => 'francais'),
        array('name' => get_string('course-math', 'theme_recit2'), 'cssclass' => 'theme-recit-math', 'key' => 'math'),
        array('name' => get_string('course-english', 'theme_recit2'), 'cssclass' => 'theme-recit-anglais', 'key' => 'anglais'),
        array('name' => get_string('course-ecr', 'theme_recit2'), 'cssclass' => 'theme-recit-ecr', 'key' => 'ecr'),
        array('name' => get_string('course-art', 'theme_recit2'), 'cssclass' => 'theme-recit-art', 'key' => 'art'),
        array('name' => get_string('course-history', 'theme_recit2'), 'cssclass' => 'theme-recit-histoire', 'key' => 'histoire'),
    );

    if (!empty($key)){
        foreach ($subthemes as $sub){
            if ($sub['key'] == $key) return $sub;
        }
        return false;
    }

    return $subthemes;
}