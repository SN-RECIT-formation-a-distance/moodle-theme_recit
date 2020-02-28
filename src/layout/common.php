<?php

use theme_recit\util;

require_once($CFG->dirroot . '/theme/recit/classes/util/icon_system.php');
require_once($CFG->dirroot . '/user/lib.php');

class ThemeRecitUtils{
    public static function isNavDrawerOpen(){
        //return (get_user_preferences('drawer-open-nav', 'true') == 'true');
        return false; // par default
    }

    public static function isDrawerOpenRight(){
        //return (get_user_preferences('sidepre-open', 'true') == 'true');
        return false; // par default
    }

    public static function userIsEditing($page){
        return $page->user_is_editing();
    }

    public static function setUserPreferenceDrawer(){
        user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
        user_preference_allow_ajax_update('sidepre-open', PARAM_ALPHA);
    }

    public static function getPurgeAllCachesNavItem(){
        global $CFG;

        $item = new stdClass();
        $item->url = sprintf("%s/%s?sesskey=%s&confirm=1", $CFG->wwwroot, "admin/purgecaches.php", sesskey());
        $item->pix = "fa-trash-alt";
        $item->title = get_string('purgecaches', 'admin');
        return $item;
    }

    public static function getPurgeThemeCacheNavItem(){
        global $CFG;

        $item = new stdClass();
        $item->url = sprintf("%s/%s?sesskey=%s&reset=1", $CFG->wwwroot, "theme/index.php", sesskey());
        $item->pix = "fa-trash-alt";
        $item->title = get_string('themeresetcaches', 'admin');
        return $item;
    }

    public static function getExtraMenu(){
        $result = array();

        $result['purgeallcaches'] = self::getPurgeAllCachesNavItem();
        $result['purgethemecache'] = self::getPurgeThemeCacheNavItem();

        return $result;
    }

    public static function getTemplateContextCommon($output, $page, $user = null){
        
        $result = [
            'output' => $output,
            'isloggedin' => isloggedin(),
            'modeedition' => ThemeRecitUtils::userIsEditing($page),
            'is_siteadmin' => is_siteadmin(),
        ];

        $result['settingsmenu'] = self::getContextHeaderSettingsMenu($page);
        $result['extra'] = self::getExtraMenu();
        
        if($user != null){
            $result['usermenu'] = ThemeRecitUtils::getUserMenu($page, $user);
        }

        return $result;
    }

    public static function getContextHeaderSettingsMenu($page){
        $result = array();

        //$settingsnode = $page->settingsnav->find('turneditingonoff', navigation_node::TYPE_COURSE);
        // frontpageloaded, currentcourse, currentcoursenotes, user2, useraccount, changepassword, preferredlanguage, coursepreferences, editsettings, turneditingonoff
        //$settingsnode = $page->settingsnav->find('useraccount', navigation_node::TYPE_CONTAINER);
        self::addNavItemFromSettingsNav($result, $page->settingsnav, navigation_node::TYPE_SETTING, "editsettings");
        self::addNavItemFromSettingsNav($result, $page->settingsnav, navigation_node::TYPE_SETTING, "turneditingonoff");
        self::addNavItemFromSettingsNav($result, $page->settingsnav, navigation_node::TYPE_SETTING, "questions");
        if(isset($result['questions'])){
            $result['questions']->pix = "fa-database";
        }
        
        
        /*echo "<pre>";
        print_r($result);
        die();*/

        return $result;
    }
    
    public static function setRecitDashboard(&$item){
        global $CFG;

        $pathRecitDashboard = '/local/recitdashboard/view.php';
        if(file_exists($CFG->dirroot . $pathRecitDashboard)){
            $item->url = $CFG->wwwroot.$pathRecitDashboard;
        }
    }

    public static function getUserMenu($page, $user){
        $result = array();

        if($user->id == 0){
            return $result;
        }

        if (during_initial_install()) {
            return $result;
        }

        /*$loginpage = $this->is_login_page();
        $loginurl = get_login_url();*/
        
       /* if (!isloggedin()) {
            $returnstr = '';
            if (!$loginpage) {
                $returnstr .= "<a class='btn btn-login-top d-lg-none' href=\"$loginurl\">" . get_string('login') . '</a>';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }*/

        /*if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }*/

        // Get some navigation opts.
        $navOptions = user_get_user_navigation_info($user, $page);

        /*echo "<pre>";
        print_r($navOptions);
        die();*/

        $iconMap = \theme_recit\util\icon_system::$iconMap;

        foreach($navOptions->navitems as $navItem){
            if($navItem->itemtype == "link"){
                $item = new stdClass();
                $item->url = $navItem->url->out();
                $item->pix = $iconMap["core:" . $navItem->pix];
                $item->title = $navItem->title;
                $navId = current(explode(",",$navItem->titleidentifier));

                if($navId == "mymoodle"){
                    ThemeRecitUtils::setRecitDashboard($item);
                }

                $result[$navId] = $item;
            }
        }

        $item = new stdClass();
        $item->url = $navOptions->metadata['userprofileurl']->out();
        $item->pix = $navOptions->metadata['useravatar'];
        $item->title = $navOptions->metadata['userfullname'];
        $item->extra = "";

        if(isset($navOptions->metadata['rolename'])){
            $item->role =  $navOptions->metadata['rolename'];
        }
        
        $result["user"] = $item;

        self::addNavItemFromFlatNav($result, $page->flatnav, "home");
        self::addNavItemFromFlatNav($result, $page->flatnav, "participants");
        self::addNavItemFromFlatNav($result, $page->flatnav, "badgesview");
        self::addNavItemFromFlatNav($result, $page->flatnav, "competencies");
        self::addNavItemFromFlatNav($result, $page->flatnav, "calendar");
        self::addNavItemFromFlatNav($result, $page->flatnav, "privatefiles");
        self::addNavItemFromFlatNav($result, $page->flatnav, "sitesettings");


        // number of unread messages
        $result["messages"]->extra = \core_message\api::count_unread_conversations($user);
        
        /*echo "<pre>";
        print_r($result);
        die();*/
        return $result;
    }

    public static function addNavItemFromFlatNav(&$navItems, $flatnav, $key){
        $flatNavItem = $flatnav->find($key);

        if(empty($flatNavItem) || empty($flatNavItem->action)){
            return;
        }

        $iconMap = \theme_recit\util\icon_system::$iconMap;

        $item = new stdClass();
        $item->url = $flatNavItem->action->out();
        $item->pix = $iconMap["core:" . $flatNavItem->icon->pix];
        $item->title = $flatNavItem->text;

        $navItems[$key] = $item;
    }

    public static function addNavItemFromSettingsNav(&$navItems, $settingsnav, $nodeType, $key){
        $settingsNavItem = $settingsnav->find($key, $nodeType);

        if(empty($settingsNavItem)){
            return;
        }

        $iconMap = \theme_recit\util\icon_system::$iconMap;

        $item = new stdClass();
        $item->url = $settingsNavItem->action->out(false);
        $item->pix = $iconMap["core:" . $settingsNavItem->icon->pix];
        $item->title = $settingsNavItem->text;

        $navItems[$key] = $item;
    }
}

