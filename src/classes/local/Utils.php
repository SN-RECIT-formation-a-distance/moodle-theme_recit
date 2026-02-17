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

require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/message/output/popup/lib.php');

use theme_config;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class CourseSectionNav{
    public $sections = array();
    public $isMenuM1 = false;
    public $isMenuM2 = false;
    public $isMenuM3 = false;
    public $isMenuM5 = false;

    public function addSection($level = 1,  $sectionId = '', $url = '', $sectionDesc = "", $title = "", $sId = ""){
        $maxNbChars = 25;
        
        $truncate = ThemeSettings::get_custom_field('truncatesections');

        $obj = new stdClass();
        $obj->id = $sId;
        $obj->sectionId = $sectionId;
        $obj->url = $url;
        $obj->title = (strlen($title) > 0 ? $title : $sectionDesc);
        $obj->desc = $truncate == 0 ? $sectionDesc : mb_strimwidth($sectionDesc, 0, $maxNbChars, "...");
        $obj->subSections = array();

        if($level > 1){
            $lastIndex = count($this->sections) - 1;
            $this->sections[$lastIndex]->subSections[] = $obj;
            $this->sections[$lastIndex]->hasSubSections = true;
        }
        else{
            $this->sections[] = $obj;
        }
    }
}

/**
 * Helper to load a theme configuration.
 *
 * @package    theme_recit2
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ThemeSettings {

    public const COURSE_CUSTOM_FIELDS_SECTION = 'Personnalisation du thème RÉCIT';  // hardcodé car il ne peut pas être modifié
    
    public const MENU_MODEL_LIST = array(1 => 'm1', 2 => 'm3', 3 => 'm2', 4 => 'm5', 5 => 'Aucun menu');
    
    public const MODULES_WITH_EMBED_BLOCKS = ['book', 'quiz'];

    public const SUBTHEME_LIST = array(1 => 'theme-recit-anglais', 2 => 'theme-recit-art', 3 => 'theme-recit-ecr', 4 => 'theme-recit-francais', 5 => 'theme-recit-histoire', 6 => 'theme-recit-math', 7=> 'theme-recit-science');

    public static function get_custom_field($name) {
        global $COURSE;

        if($COURSE->id > 1){
            $customFieldsRecit = theme_recit2_get_course_metadata($COURSE->id, self::COURSE_CUSTOM_FIELDS_SECTION);

            if(property_exists($customFieldsRecit, $name)){
                return $customFieldsRecit->$name;
            }
        }

        return null;
    }

    public static function get_subtheme_class(){
        global $PAGE;
        /*$theme = ThemeSettings::get_custom_field('subtheme');
        if (!$theme || !isset(ThemeSettings::SUBTHEME_LIST[$theme])){
            return 'theme-recit2';
        }
        return ThemeSettings::SUBTHEME_LIST[$theme];*/
        return 'theme-'.str_replace('_', '-', $PAGE->theme->name);
    }

    public static function get_theme_name(){
        global $PAGE,$CFG;
        if (file_exists($CFG->dirroot.'/theme/'.$PAGE->theme->name.'/settings.php')){
            return $PAGE->theme->name;
        }
        return "recit2";
    }

    /**
     * Get config theme footer items
     *
     * @return array
     */
    public function footer_items() {
        global $CFG, $PAGE, $OUTPUT;

        $theme_name = self::get_theme_name();
        $theme = theme_config::load($theme_name);

        $templatecontext = [];

        $footersettings = [
            'facebook', 'twitter', 'linkedin', 'youtube', 'instagram', 
            'website', 'mobile', 'mail', 'footnote', 'copyright_footer'
        ];

        foreach ($footersettings as $setting) {
            if (!empty($theme->settings->$setting)) {
                $templatecontext[$setting] = $theme->settings->$setting;
            }
        }

        $footerfilesettings = ['footerlogo'];
        foreach ($footerfilesettings as $setting) {
            $image = $theme->setting_file_url($setting, $setting);
            if (!empty($image)) {
                $templatecontext[$setting] = $image;
            }
        }

        $templatecontext['infolink'] = $this->getInfolink($theme->settings->infolink);
        $templatecontext['output'] = $OUTPUT;

        $templatecontext['s_sitepolicy'] = get_string('sitepolicy', 'core_admin');
        $templatecontext['sitepolicy'] = $CFG->sitepolicy;
        $templatecontext['s_sitepolicyguest'] = get_string('sitepolicyguest', 'core_admin');
        $templatecontext['sitepolicyguest'] = $CFG->sitepolicyguest;

        $templatecontext['s_page_doc_link'] = get_string('moodledocslink');

        $path = page_get_doc_link_path($PAGE);
        if($path){
            $templatecontext['page_doc_link'] = get_docs_url($path);
        }

        if($PAGE->pagetype != 'site-index'){
            $templatecontext['s_sitehome'] = get_string('sitehome');
            $templatecontext['sitehome'] = "{$CFG->wwwroot}/?redirect=0";
        }

        // A returned 0 means that the setting was set and disabled, false means that there is no value for the provided setting.
        $showsummary = get_config('tool_dataprivacy', 'showdataretentionsummary');
        // This means that no value is stored in db. We use the default value in this case.
        $showsummary = ($showsummary === false ? true : $showsummary) ;

        if ($showsummary) {
            $templatecontext['s_showdataretentionsummary'] = get_string('dataretentionsummary', 'tool_dataprivacy');
            $templatecontext['showdataretentionsummary'] = "{$CFG->wwwroot}/admin/tool/dataprivacy/summary.php";
        }
        
        $templatename = "theme_recit2/recit/footer";
        if (file_exists($CFG->dirroot . "/theme/{$theme_name}/templates/recit/footer.mustache")){
            $templatename = "theme_{$theme_name}/recit/footer";
        }

        $templatecontext['customfooter']  = get_config('theme_recit2', 'customfooter');

        return array('footer' => $OUTPUT->render_from_template($templatename, $templatecontext));
    }

    /**
     * Get the infolinks from settings page and display on the footer.
     * @return type|string
     */
    public function getInfolink($strInfoLink) {
        $content = "";
        $infosettings = explode("\n", $strInfoLink);
        foreach ($infosettings as $key => $settingval) {
            $expset = explode("|", $settingval);
            if (isset($expset[0]) && isset($expset[1]) ) {
                list($ltxt, $lurl) = $expset;
            } else {
                $ltxt = $expset[0];
                $lurl = "#";
            }
            $ltxt = trim($ltxt);
            $lurl = trim($lurl);

            if (empty($ltxt)) {
                continue;
            }
            $content .= '<li><a href="'.$lurl.'" target="_blank">'.$ltxt.'</a></li>';
        }

        return $content;
    }

    /**
     * Get config theme slideshow
     *
     * @return array
     */
    public function slideshow($theme) {
        global $OUTPUT;

        $templatecontext['enabled'] = (isset($theme->settings->sliderenabled) && $theme->settings->sliderenabled == 1);

        if (empty($templatecontext['enabled'])) {
            return $templatecontext;
        }

        $slidercount = $theme->settings->slidercount;

        for ($i = 1, $j = 0; $i <= $slidercount; $i++, $j++) {
            $sliderimage = "sliderimage{$i}";
            $slidertitle = "slidertitle{$i}";
            $slidercap = "slidercap{$i}";

            $templatecontext['slides'][$j]['key'] = $j;
            $templatecontext['slides'][$j]['active'] = false;

            $image = $theme->setting_file_url($sliderimage, $sliderimage);
            if (empty($image)) {
                $image = $OUTPUT->image_url("slide_default_img$i", 'theme');
            }
            $templatecontext['slides'][$j]['image'] = $image;
            $templatecontext['slides'][$j]['title'] = $theme->settings->$slidertitle;
            $templatecontext['slides'][$j]['caption'] = $theme->settings->$slidercap;

            if ($i === 1) {
                $templatecontext['slides'][$j]['active'] = true;
            }
        }

        return $templatecontext;
    }

    /**
     * Get the frontpage numbers
     *
     * @return array
     */
    public function numbers() {
        global $DB;

        $templatecontext['numberusers'] = $DB->count_records('user', array('deleted' => 0, 'suspended' => 0)) - 1;
        $templatecontext['numbercourses'] = $DB->count_records('course', array('visible' => 1)) - 1;
        $templatecontext['numberactivities'] = $DB->count_records('course_modules');

        return $templatecontext;
    }
}

class ThemeUtils
{
    public static function getUserRoles($courseId, $userId){
        // get the course context (there are system context, module context, etc.)
        $context = \context_course::instance($courseId);

        return self::getUserRolesOnContext($context, $userId);
    }

    public static function getUserRolesOnContext($context, $userId){
        $userRoles1 = get_user_roles($context, $userId);

        $userRoles2 = array();
        foreach($userRoles1 as $item){
            $userRoles2[] = $item->shortname;
        }

        $ret = self::moodleRoles2RecitRoles($userRoles2);

        if(is_siteadmin($userId)){
            $ret[] = 'ad';
        }
        
        return $ret;
    }
    
    public static function moodleRoles2RecitRoles($userRoles){
        $ret = array();

        foreach($userRoles as $name){
            switch($name){
                case 'manager': $ret[] = 'mg'; break;
                case 'coursecreator': $ret[] = 'cc'; break;
                case 'editingteacher': $ret[] = 'et'; break;
                case 'teacher': $ret[] = 'tc'; break;
                case 'student': $ret[] = 'sd'; break;
                case 'guest': $ret[] = 'gu'; break;
                case 'frontpage': $ret[] = 'fp'; break;
            }
        }

        return $ret;
    }
    
    public static function isAdminRole(){
        global $PAGE;
        
        if ((is_a($PAGE->context, 'context_course') && has_capability('moodle/course:update', $PAGE->context)) ||
                (is_a($PAGE->context, 'context_category') && has_capability('moodle/category:manage', $PAGE->context))) {
            return true;
        }
        else{
            return false;
        }
    }
}