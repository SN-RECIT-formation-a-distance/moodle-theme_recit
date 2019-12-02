<?php

use theme_recit\util;

require_once($CFG->dirroot . '/theme/recit/classes/util/icon_system.php');
require_once($CFG->dirroot . '/user/lib.php');

class ThemeRecitUtils{
    public static function userIsEditing($page){
        return $page->user_is_editing();
    }
    
    public static function getUrlEditionMode($page){
        $obj = parse_url($page->url);

        $separator = (isset($obj['query']) ? "&" : "?");
        $editParam = (is_siteadmin() ? "adminedit" : "edit");
        if($editParam == "adminedit"){
            $editValue = (self::userIsEditing($page) ? '0' : '1');
        }
        else{
            $editValue = (self::userIsEditing($page) ? 'off' : 'on');
        }
        
        return sprintf("%s%ssesskey=%s&%s=%s", $page->url->out(), $separator, sesskey(), $editParam, $editValue);
    }

    public static function getTemplateContextCommon($output, $page, $user = null){
        $result = [
            'output' => $output,
            'isloggedin' => isloggedin(),
            'modeedition' => ThemeRecitUtils::userIsEditing($page),
            'is_siteadmin' => is_siteadmin()
        ];

        $result['settingsmenu'] = self::getContextHeaderSettingsMenu($page);

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
                $result[$navId] = $item;
            }
        }

        $item = new stdClass();
        $item->url = $navOptions->metadata['userprofileurl']->out();
        $item->pix = $navOptions->metadata['useravatar'];
        $item->title = $navOptions->metadata['userfullname'];
        $result["user"] = $item;

        self::addNavItemFromFlatNav($result, $page->flatnav, "home");
        self::addNavItemFromFlatNav($result, $page->flatnav, "participants");
        self::addNavItemFromFlatNav($result, $page->flatnav, "badgesview");
        self::addNavItemFromFlatNav($result, $page->flatnav, "competencies");
        self::addNavItemFromFlatNav($result, $page->flatnav, "calendar");
        self::addNavItemFromFlatNav($result, $page->flatnav, "privatefiles");
        self::addNavItemFromFlatNav($result, $page->flatnav, "sitesettings");

        /*echo "<pre>";
        print_r($result);
        die();*/
        return $result;
    }

    public static function addNavItemFromFlatNav(&$navItems, $flatnav, $key){
        $flatNavItem = $flatnav->find($key);

        if(empty($flatNavItem)){
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

