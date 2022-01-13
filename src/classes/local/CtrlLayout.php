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

namespace theme_recit2\local;

require_once("Utils.php");

use stdClass;
use context_course;
use theme_config;

/**
 * Define utils for Recit theme.
 * @author RECITFAD
 */
class CtrlLayout{
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
        global $CFG, $SITE, $COURSE, $PAGE, $USER;

        $result = [
            'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
            'output' => $output,
            'isloggedin' => isloggedin(),
            'isguest' => $USER->id == 1,
            'modeedition' => self::user_is_editing($page),
            'is_siteadmin' => is_siteadmin(),
            'wwwroot' => $CFG->wwwroot,
            'layoutOptions' => (object) $PAGE->layout_options
        ];

        $result['settingsmenu'] = self::get_context_header_settings_menu($page);
        $result['extra'] = self::get_extra_menu();

        if ($user != null) {
            $result['usermenu'] = self::get_user_menu($page, $user);
        }

        $result['message_and_notification'] = message_popup_render_navbar_output($output);
        $result['message_drawer'] = core_message_standard_after_main_region_html();

        $result['lang'] = new stdClass();
        $result['lang']->options = self::getLangMenu();
        $result['lang']->show = ($result['lang']->options != null);
        $result['lang']->current = strtoupper(current_language());

        $result['css_custom'] = null;
        
        $cssCustom = ThemeSettings::get_custom_field('css_custom');
        if(!empty($cssCustom)){
            $result['css_custom'] = strip_tags($cssCustom);
        }

        return $result;
    }

    public static function get_course_section_nav(){
        global $PAGE, $COURSE, $USER;

        $result = [
            'layoutOptions' => (object) $PAGE->layout_options
        ];

        $pageAdmin = strpos($_SERVER['SCRIPT_NAME'], 'admin.php');        
        $pageBadges = strpos($_SERVER['SCRIPT_NAME'], 'badges/view.php');   
        $pageContent = strpos($_SERVER['SCRIPT_NAME'], 'contentbank/index.php');   
        $pageUser = strpos($_SERVER['SCRIPT_NAME'], 'user/index.php');  
        $pageCompetencies = strpos($_SERVER['SCRIPT_NAME'], 'coursecompetencies.php');  
        $pages = $pageAdmin | $pageBadges | $pageContent | $pageUser | $pageCompetencies;

        if(($COURSE->id > 1) && (!$PAGE->user_is_editing()) && (!$pages) && ($USER->id >= 1)){            
            $result['section_bottom_nav'] = new stdClass();
            $result['section_bottom_nav']->prev_section = get_string('prev_section', 'theme_recit2');
            $result['section_bottom_nav']->next_section = get_string('next_section', 'theme_recit2');
        
            $showSectionBottomNav = ThemeSettings::get_custom_field('show_section_bottom_nav');
            $result['section_bottom_nav']->enable = ($showSectionBottomNav == 1);

            $result['section_top_nav'] = self::get_course_section_top_nav();
        }
        else{
            $result['layoutOptions']->showSectionTopNav = false;
            $result['layoutOptions']->showSectionBottomNav = false;
        }

        return $result;
    }

    public static function get_course_section_top_nav(){
        global $COURSE, $CFG;

        $modinfo = get_fast_modinfo($COURSE);
        $sectionslist = $modinfo->get_section_info_all();

        if(count($sectionslist) == 0){
            return null;
        }
        
        $result = new CourseSectionNav();
        $menuModalIndex = ThemeSettings::get_custom_field('menumodel') - 1;
        if($menuModalIndex >= 0){
            $result->isMenuM1 = (ThemeSettings::MENU_MODEL_LIST[$menuModalIndex] == "m1");
            $result->isMenuM2 = (ThemeSettings::MENU_MODEL_LIST[$menuModalIndex] == "m2");
            $result->isMenuM3 = (ThemeSettings::MENU_MODEL_LIST[$menuModalIndex] == "m3");
            $result->isMenuM5 = (ThemeSettings::MENU_MODEL_LIST[$menuModalIndex] == "m5");
        }
        else{
            return null;
        }
        
        $protohref = "{$CFG->wwwroot}/course/view.php?id={$COURSE->id}%s";

        //$result->addSection(1, "map", sprintf($protohref, "#map"), "<i class='fa fa-map'></i>", "Menu");
        $hideRestricted = ThemeSettings::get_custom_field('hide_restricted_section');

        foreach($sectionslist as $section){
            if( !$section->visible ){ continue; } 
            if( $hideRestricted == 1 && !$section->available ){ continue; } 

            $sectionDesc = (empty($section->name) ? get_string('section') . '' . $section->section : $section->name);
            
            $sectionlevel = 1;
            if(isset($section->sectionlevel)){
                $sectionlevel = $section->sectionlevel;
            }

            $sectionId = "#section-{$section->section}";
            $result->addSection($sectionlevel, $sectionId, sprintf($protohref, $sectionId), $sectionDesc);
        }

        return $result;
    }

    public static function getLangMenu() {
        global $PAGE, $CFG;

        if (empty($CFG->langmenu)) {
            return null;
        }

        if ($PAGE->course != SITEID and !empty($PAGE->course->lang)) {
            // do not show lang menu if language forced
            return null;
        }
       
        $langs = get_string_manager()->get_list_of_translations();

        if (count($langs) < 2) {
            return null;
        }

        $result = array(); 
        
        foreach($langs as $index => $lang){
            $item = new stdClass();
            $item->data = $index;
            $item->text = $lang;
            $item->url =  new \moodle_url($PAGE->url, array('lang' => $item->data));
            $result[] = $item;
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

            $roles = ThemeUtils::getUserRoles($COURSE->id, $USER->id);
            if(ThemeUtils::isAdminRole($roles)){
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

                $result['turneditingonoff'] = self::get_editing_mode_object($page);

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
                $result['turneditingonoff'] = self::get_editing_mode_object($page);
                 
            }
        }

        return $result;
    }

    public static function get_editing_mode_object($page){
        global $CFG, $COURSE, $PAGE;
        $item = new stdClass();

        $state = ($page->user_is_editing() ? 'off' : 'on');

       // if((isset($page->cm) && $page->cm->modname == 'book')){
            $item->url = sprintf("%s&sesskey=%s&edit=%s", $PAGE->url->out(), sesskey(), $state);
        /*} else {
            $item->url = sprintf("%s/course/view.php?id=%ld&sesskey=%s&edit=%s", $CFG->wwwroot, $COURSE->id, sesskey(), $state);
        }	*/
        
        $item->title = get_string('editmode', 'theme_recit2');
        $item->checked = (self::user_is_editing($page) == 1 ? 'checked' : '');
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
            $roles = ThemeUtils::getUserRoles($COURSE->id, $USER->id);
            if(ThemeUtils::isAdminRole($roles)){
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
           
            $roles = ThemeUtils::getUserRoles($COURSE->id, $USER->id);
            if(ThemeUtils::isAdminRole($roles)){
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
}