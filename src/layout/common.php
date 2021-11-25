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
 * Common functions for the recit theme.
 *
 * @package   theme_recit2
 * @copyright RÉCITFAD 2019
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/recit2/classes/util/ThemeRecitUtils.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/message/output/popup/lib.php');

/**
 * Define utils for Recit theme.
 * @author RECITFAD
 */
class ThemeRecitUtils2{
    /**
     * Function for class ThemeRecitUtils2.
     * @return boolean
     */
    public static function is_nav_drawer_open() {
        // return (get_user_preferences('drawer-open-nav', 'true') == 'true');
        return false; // Par default.
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @return boolean
     */
    public static function is_drawer_open_right() {
        // return (get_user_preferences('sidepre-open', 'true') == 'true');
        return false; // Par default.
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @param unknown $page
     * @return unknown
     */
    public static function user_is_editing($page) {
        return $page->user_is_editing();
    }

    /**
     * Function for class ThemeRecitUtils2.
     */
    public static function set_user_preference_drawer() {
        user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
        user_preference_allow_ajax_update('sidepre-open', PARAM_ALPHA);
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @return stdClass
     */
    public static function get_purge_all_caches_nav_item() {
        global $CFG;

        $item = new stdClass();
        $item->url = sprintf("%s/%s?sesskey=%s&confirm=1", $CFG->wwwroot, "admin/purgecaches.php", sesskey());
        $item->pix = "fa-trash";
        $item->title = get_string('purgecaches', 'admin');
        return $item;
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @return stdClass
     */
    public static function get_purge_theme_cache_nav_item() {
        global $CFG;

        $item = new stdClass();
        $item->url = sprintf("%s/%s?sesskey=%s&reset=1", $CFG->wwwroot, "theme/index.php", sesskey());
        $item->pix = "fa-trash";
        $item->title = get_string('themeresetcaches', 'admin');
        return $item;
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @return array
     */
    public static function get_extra_menu() {
        global $CFG;

        $result = array();

        $result['purgeallcaches'] = self::get_purge_all_caches_nav_item();
        $result['purgethemecache'] = self::get_purge_theme_cache_nav_item();

        if (file_exists("{$CFG->dirroot}/local/recitgestioncontenu/version.php")) {
            $item = new stdClass();
            $item->url = sprintf("%s/local/recitgestioncontenu/view.php", $CFG->wwwroot);
            $item->pix = "fa";
            $item->title = get_string('pluginname', 'local_recitgestioncontenu');
            $result['contentmanagement'] = $item;
        }

        return $result;
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @param string $output
     * @param stdClass $page
     * @param stdClass $user
     * @return array
     */
    public static function get_template_context_common($output, $page, $user = null) {
        global $CFG, $SITE, $COURSE;

        $result = [
            'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
            'output' => $output,
            'isloggedin' => isloggedin(),
            'modeedition' => self::user_is_editing($page),
            'is_siteadmin' => is_siteadmin(),
            'wwwroot' => $CFG->wwwroot
        ];

        $result['settingsmenu'] = self::get_context_header_settings_menu($page);
        $result['extra'] = self::get_extra_menu();

        if ($user != null) {
            $result['usermenu'] = self::get_user_menu($page, $user);
        }

        $result['message_and_notification'] = message_popup_render_navbar_output($output);
        $result['message_drawer'] = core_message_standard_after_main_region_html();

        $result['css_custom'] = null;
        
        $cssCustom = theme_recit2\util\theme_settings::get_custom_field('css_custom');
        if(!empty($cssCustom)){
            $result['css_custom'] = strip_tags($cssCustom);
        }

        if($COURSE->id > 1){
            $result['section_bottom_nav'] = new stdClass();
            $result['section_bottom_nav']->prev_section = get_string('prev_section', 'format_treetopics');
            $result['section_bottom_nav']->next_section = get_string('next_section', 'format_treetopics');
            $result['section_bottom_nav']->enable = true;
        }
        
        return $result;
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @param stdClass $page
     * @return array
     */
    public static function get_context_header_settings_menu($page) {
        global $DB, $CFG, $COURSE, $USER;

        $result = array();


        // Le courseId = 1 est l'accueil du site donc on l'ignore ici.
        if ($COURSE->id > 1) {
            $item = new stdClass();
            $item->url = sprintf("%s/course/view.php?id=%ld", $CFG->wwwroot, $COURSE->id);
            $item->pix = 'fa-home';
            $item->title = get_string('coursehome', 'theme_recit2');
            $result['coursehome'] = $item;

            $roles = ThemRecitUtils2::getUserRoles($COURSE->id, $USER->id);
            if(ThemRecitUtils2::isAdminRole($roles)){
                $item = new stdClass();
                $item->url = sprintf("%s/course/admin.php?courseid=%ld", $CFG->wwwroot, $COURSE->id);
                $item->pix = 'fa-cog';
                $item->title = get_string('courseadministration');
                $result['courseadmin'] = $item;

                $item = new stdClass();
                $item->url = sprintf("%s/user/index.php?id=%ld", $CFG->wwwroot, $COURSE->id);
                $item->pix = 'fa-users';
                $item->title = get_string('participants');
                $result['users'] = $item;
            }

            // the user has  permission to access these shortcuts
            if ($page->user_allowed_editing()) {

                $result['turneditingonoff'] = ThemeRecitUtils2::get_editing_mode_object($page);

                /*$item = new stdClass();
                $item->url = sprintf("%s/group/index.php?id=%ld", $CFG->wwwroot, $COURSE->id);
                $item->pix = 'fa-users';
                $item->title = get_string('groups');
                $result['groups'] = $item;*/

                if (!empty($page->cm->id)) {
                    $item = new stdClass();
                    $item->url = sprintf("%s/course/modedit.php?update=%ld&return=1", $CFG->wwwroot, $page->cm->id);
                    $item->pix = 'fa-sliders';
                    $item->title = 'Paramètres activité';
                    $result['paramsact'] = $item;
                }

                /*$item = new stdClass();
                $item->url = sprintf("%s/grade/report/grader/?id=%ld", $CFG->wwwroot, $COURSE->id);
                $item->pix = 'fa-graduation-cap';
                $item->title =  get_string('grade', 'theme_recit2');
                $result['grade'] = $item;*/
            }
        }else{
            if ($page->user_allowed_editing() && $page->pagelayout == 'frontpage') {
                // editing mode
                $result['turneditingonoff'] = ThemeRecitUtils2::get_editing_mode_object($page);
                 
            }
        }

        return $result;
    }

    public static function get_editing_mode_object($page){
        global $CFG, $COURSE;
        $item = new stdClass();
        $urlEditingMode = "%s/course/view.php?id=%ld&sesskey=%s&edit=%s";
        if ($page->user_is_editing()) {
            $item->url = sprintf($urlEditingMode, $CFG->wwwroot, $COURSE->id, sesskey(), 'off');
            $item->title = get_string('turneditingoff');
        } else {
            $item->url = sprintf($urlEditingMode, $CFG->wwwroot, $COURSE->id, sesskey(), 'on');
            $item->title = get_string('turneditingon');
        }	
        
        $item->pix = 'fa-pencil';
        return $item;
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @param unknown $item
     */
    public static function set_recit_dashboard(&$item) {
        global $CFG, $COURSE, $USER;

        $pathrecitdashboard = '/local/recitdashboard/view.php';
        if (file_exists($CFG->dirroot . $pathrecitdashboard)) {
            $roles = ThemRecitUtils2::getUserRoles($COURSE->id, $USER->id);
            if(ThemRecitUtils2::isAdminRole($roles)){
                $item->url = sprintf("%s?courseId=%ld", $CFG->wwwroot.$pathrecitdashboard, $COURSE->id);
            }
        }
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @param stdClass $page
     * @param stdClass $user
     * @return array|stdClass[]
     */
    public static function get_user_menu($page, $user) {
        global $COURSE, $USER;

        $result = array();

        if ($user->id == 0) {
            return $result;
        }

        if (during_initial_install()) {
            return $result;
        }

        // Get some navigation opts.
        $navoptions = user_get_user_navigation_info($user, $page);

        $theme = theme_config::load('recit2');
        $instance = \core\output\icon_system_fontawesome::instance($theme->get_icon_system());
        $iconmap = $instance->get_icon_name_map();

        foreach ($navoptions->navitems as $navitem) {
            if ($navitem->itemtype == "link") {
                $item = new stdClass();
                $item->url = $navitem->url->out(false);

                if (isset($iconmap["core:" . $navitem->pix])) {
                    $item->pix = $iconmap["core:" . $navitem->pix];
                }

                $item->title = $navitem->title;
                $navid = current(explode(",", $navitem->titleidentifier));

                if ($navid == "mymoodle") {
                    self::set_recit_dashboard($item);
                }
                else if($navid == 'messages'){
                    continue;
                }

                $result[$navid] = $item;
            }
        }

        $item = new stdClass();
        $item->url = $navoptions->metadata['userprofileurl']->out();
        $item->pix = $navoptions->metadata['useravatar'];
        $item->title = $navoptions->metadata['userfullname'];
        $item->extra = "";

        if (isset($navoptions->metadata['rolename'])) {
            $item->role = $navoptions->metadata['rolename'];
        }

        $result["user"] = $item;

        self::add_nav_item_from_flat_nav($result, $page->flatnav, "home");
        self::add_nav_item_from_flat_nav($result, $page->flatnav, "calendar");
        self::add_nav_item_from_flat_nav($result, $page->flatnav, "privatefiles");
        self::add_nav_item_from_flat_nav($result, $page->flatnav, "sitesettings");

        if($COURSE->id > 1){
           
            $roles = ThemRecitUtils2::getUserRoles($COURSE->id, $USER->id);
            if(ThemRecitUtils2::isAdminRole($roles)){
                self::add_nav_item_from_flat_nav($result, $page->flatnav, "participants");
                self::add_nav_item_from_flat_nav($result, $page->flatnav, "contentbank");
            }
            self::add_nav_item_from_flat_nav($result, $page->flatnav, "badgesview");
            self::add_nav_item_from_flat_nav($result, $page->flatnav, "competencies");
            
        }

        return $result;
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @param unknown $navitems
     * @param unknown $flatnav
     * @param unknown $key
     */
    public static function add_nav_item_from_flat_nav(&$navitems, $flatnav, $key) {
        $flatnavitem = $flatnav->find($key);
        
        if (empty($flatnavitem) || empty($flatnavitem->action)) {
            return;
        }

        $theme = theme_config::load('recit2');
        $instance = \core\output\icon_system_fontawesome::instance($theme->get_icon_system());
        $iconmap = $instance->get_icon_name_map();

        $item = new stdClass();
        $item->url = $flatnavitem->action->out();
        $item->pix = $iconmap["core:" . $flatnavitem->icon->pix];
        $item->title = $flatnavitem->text;

        $navitems[$key] = $item;
    }

    /**
     * Function for class ThemeRecitUtils2.
     * @param unknown $navitems
     * @param unknown $settingsnav
     * @param unknown $nodetype
     * @param unknown $key
     */
    /*public static function add_nav_item_from_settings_nav(&$navitems, $settingsnav, $nodetype, $key) {
        $settingsnavitem = $settingsnav->find($key, $nodetype);
		
        if (empty($settingsnavitem)) {
            return;
        }

        $iconmap = \theme_recit2\util\icon_system::$iconmap;

        $item = new stdClass();
        $item->url = $settingsnavitem->action->out(false);
        $item->pix = $iconmap["core:" . $settingsnavitem->icon->pix];
        $item->title = $settingsnavitem->text;

        $navitems[$key] = $item;
    }*/
}

