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
 * @package    theme_recit
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
use \core_customfield\category_controller;
use \core_customfield\field_controller;

/**
 * Adds the cover to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_recit_set_headerimg($theme) {
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
function theme_recit_set_topfooterimg($theme) {
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
function theme_recit_set_loginbgimg($theme) {
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
function theme_recit_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $scss .= file_get_contents($CFG->dirroot . '/theme/recit/style/bootstrap.css'); 
    $scss .= file_get_contents($CFG->dirroot . '/theme/recit/style/moodle-base.css'); // loaded here because of [[pix:]]
    $scss .= file_get_contents($CFG->dirroot . '/theme/recit/style/moodle-base-3-9.css'); 
    $scss .= theme_recit_get_scss_variables($theme); // assign the custom variables coming from Moodle Theme interface
    //$scss .= file_get_contents($CFG->dirroot . "/theme/recit/style/recit.scss"); // scss from Theme RÉCIT
    $scss .= file_get_contents($CFG->dirroot . "/theme/recit/scss/recit.scss"); // scss from Theme RÉCIT

    return $scss;
}

function theme_recit_get_scss_variables($theme){
    global $CFG;

    $scss_variables = [
        'ttmenucolor1' => '$tt-menu-color1',
        'ttmenucolor2' => '$tt-menu-color2',
        'ttmenucolor3' => '$tt-menu-color3',
        'ttmenucolor4' => '$tt-menu-color4',
    ];

    $varFileContent = file_get_contents($CFG->dirroot . '/theme/recit/scss/recit/_variables.scss');
    
    // in case this function is called by a subtheme
    if(file_exists($CFG->dirroot . "/theme/{$theme->name}/scss/_variables.scss")){
        $varFileContent .= file_get_contents($CFG->dirroot . "/theme/{$theme->name}/scss/_variables.scss");
    }
    
    $varFileContent = explode(";", $varFileContent);
    $newVarFileContent = array();
    
    foreach($varFileContent as $item){
        // value = [\$|\'|\#|\,|\-\w\d\s\!]*
        // look for variables, for example: /$varname:/
        if(preg_match('/\$[a-zA-z0-9-_]*\b:/', $item, $matches) == 0){
            $newVarFileContent[] = $item;
            continue;
        }

        $added = false;
        foreach ($scss_variables as $propname => $varname) {
            $value = isset($theme->settings->{$propname}) ? $theme->settings->{$propname} : null;
            if (empty($value)) {
                continue;
            }

            if($varname.":" == current($matches)){
                $added = true;
                $newVarFileContent[] = $varname . ": " . $value;
            }
        }

        if(!$added){
            $newVarFileContent[] = $item;
        }
    }

    return implode(";", $newVarFileContent);
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_recit_get_extra_scss($theme) { 
    $scss = $theme->settings->scss;

    $scss .= theme_recit_set_headerimg($theme);
    $scss .= theme_recit_set_topfooterimg($theme);
    $scss .= theme_recit_set_loginbgimg($theme);
    //$scss .= theme_recit_set_course_banner_img($theme);

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
function theme_recit_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    $theme = theme_config::load('recit');

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
function theme_recit_get_setting($setting, $format = false) {
    $theme = theme_config::load('recit');

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
function theme_recit_get_course_theme() {
    global $COURSE;

    switch($COURSE->theme){
        case 'recit_art':
            return 'theme-recit-art';
		case 'recit_ecr':
            return 'theme-recit-ecr';
		case 'recit_mathematique':
            return 'theme-recit-mathematique';
		case 'recit_science':
            return 'theme-recit-science';
		case 'recit_campus':
            return 'theme-recit-campus';
        case 'recit_ena':
            return 'theme-recit-ena';
		case 'recit_fga':
            return 'theme-recit-fga';
		case 'recit_anglais':
            return 'theme-recit-anglais';
		case 'recit_francais':
            return 'theme-recit-francais';
        case 'recit_histoire':
            return 'theme-recit-histoire';
        case 'recit_ecolea':
            return 'theme-recit-ecolea';
        case 'recit_ecoleb':
            return 'theme-recit-ecoleb';
        case 'recit_ecolec':
            return 'theme-recit-ecolec';
        case 'recit_ecoled':
            return 'theme-recit-ecoled';
        case 'recit_ecolee':
            return 'theme-recit-ecolee';
        case 'recit_ecolef':
            return 'theme-recit-ecolef';
        case 'recit_ecoleg':
            return 'theme-recit-ecoleg';
        default: 
            return "theme-recit";
    }
}


function theme_recit_create_course_custom_fields(){
    $category_name = \theme_recit\util\theme_settings::COURSE_CUSTOM_FIELDS_SECTION;
    $field_to_add = array(
        array(
            'type' => 'checkbox',
            'name' => get_string('course-banner', 'theme_recit'),
            'shortname' => 'img_course_as_banner',
            'description' => get_string('course-banner-help', 'theme_recit'),
            'descriptionformat' => FORMAT_HTML,
            'configdata' => array('required' => 0, 'uniquevalues' => 0, 'locked' => 0, 'visibility' => 1, "checkbydefault" => 0)
        )
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

/**
 * Extend the Recit navigation
 *
 * @param flat_navigation $flatnav
 */
/*function theme_recit_extend_flat_navigation(\flat_navigation $flatnav) {
    theme_recit_rebuildcoursesections($flatnav);
    theme_recit_delete_menuitems($flatnav);
    theme_recit_add_user_menu($flatnav);   
}*/

/*function theme_recit_add_user_menu(\flat_navigation $flatnav) {
    global $USER, $PAGE;
    $opts = user_get_user_navigation_info($USER, $PAGE);
    
    $options = [
            'text' => get_string('usermenu', 'theme_recit'),
            'shorttext' => get_string('usermenu', 'theme_recit'),
            'icon' => new pix_icon('t/viewdetails', ''),
            'type' => \navigation_node::COURSE_CURRENT,
            'key' => 'user_menu',
            'parent' => null
        ];
        
   $nav_node = new \flat_navigation_node($options, $flatnav);
        
   $flatnav->add($nav_node, null);
}*/

/**
 * Remove items from navigation
 *
 * @param flat_navigation $flatnav
 */
/*function theme_recit_delete_menuitems(\flat_navigation $flatnav) {

    $itemstodelete = [
        'coursehome'
    ];

    foreach ($flatnav as $item) {
        if (in_array($item->key, $itemstodelete)) {
            $flatnav->remove($item->key);

            continue;
        }

        if (isset($item->parent->key) && $item->parent->key == 'mycourses' &&
            isset($item->type) && $item->type == \navigation_node::TYPE_COURSE) {

            $flatnav->remove($item->key);

            continue;
        }
        
        if(isset($item->key) && ($item->key == 'mycourses' || $item->key == "course-sections"))
        {
            $flatnav->remove($item->key);
            continue;
        }
    }
}*/

/**
 * Improve flat navigation menu
 *
 * @param flat_navigation $flatnav
 */
/*function theme_recit_rebuildcoursesections(\flat_navigation $flatnav) {
    global $PAGE;

    $participantsitem = $flatnav->find('participants', \navigation_node::TYPE_CONTAINER);

    if (!$participantsitem) {
        return;
    }

    if ($PAGE->course->format != 'singleactivity') {
        $coursesectionsoptions = [
            'text' => get_string('coursesections', 'theme_recit'),
            'shorttext' => get_string('coursesections', 'theme_recit'),
            'icon' => new pix_icon('t/viewdetails', ''),
            'type' => \navigation_node::COURSE_CURRENT,
            'key' => 'course-sections',
            'parent' => $participantsitem->parent
        ];

        $coursesections = new \flat_navigation_node($coursesectionsoptions, 0);

        foreach ($flatnav as $item) {
            if ($item->type == \navigation_node::TYPE_SECTION) {
                $coursesections->add_node(new \navigation_node([
                    'text' => $item->text,
                    'shorttext' => $item->shorttext,
                    'icon' => $item->icon,
                    'type' => $item->type,
                    'key' => $item->key,
                    'parent' => $coursesections,
                    'action' => $item->action
                ]));
            }
        }

        $flatnav->add($coursesections, $participantsitem->key);
    }

    $mycourses = $flatnav->find('mycourses', \navigation_node::NODETYPE_LEAF);

    if ($mycourses) {
        $flatnav->remove($mycourses->key);

        $flatnav->add($mycourses, 'privatefiles');
    }
}*/
