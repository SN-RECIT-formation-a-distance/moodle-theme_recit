<?php
// This file is part of Ranking block for Moodle - http://moodle.org/
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
 * Theme Recit block settings file
 *
 * @package    theme_recit
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ ."/classes/admin_settingspage_tabs.php");

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    $settings = new theme_recit_admin_settingspage_tabs('themesettingrecit', get_string('configtitle', 'theme_recit'));

    /*
    * ----------------------
    * General settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_recit_general', get_string('generalsettings', 'theme_recit'));

    // Logo file setting.
    $name = 'theme_recit/logo';
    $title = get_string('logo', 'theme_recit');
    $description = get_string('logodesc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Favicon setting.
    $name = 'theme_recit/favicon';
    $title = get_string('favicon', 'theme_recit');
    $description = get_string('favicondesc', 'theme_recit');
    $opts = array('accepted_types' => array('.ico'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login page background image.
    $name = 'theme_recit/loginbgimg';
    $title = get_string('loginbgimg', 'theme_recit');
    $description = get_string('loginbgimg_desc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbgimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_recit/enablebreadcrumb';
    $title = get_string('enablebreadcrumb', 'theme_recit');
    $description = get_string('enablebreadcrumbdesc', 'theme_recit');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    //$setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_recit/showleavingsitewarning';
    $title = get_string('showleavingsitewarning', 'theme_recit');
    $description = get_string('showleavingsitewarningdesc', 'theme_recit');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    //$setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after defining all the settings!
    $settings->add($page);

    /*
    * -----------------------
    * Frontpage settings tab
    * -----------------------
    */
    $page = new admin_settingpage('theme_recit_frontpage', get_string('frontpagesettings', 'theme_recit'));

    // Headerimg file setting.
    $name = 'theme_recit/headerimg';
    $title = get_string('headerimg', 'theme_recit');
    $description = get_string('headerimgdesc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
   
    // Enable or disable Slideshow settings.
    $name = 'theme_recit/sliderenabled';
    $title = get_string('sliderenabled', 'theme_recit');
    $description = get_string('sliderenableddesc', 'theme_recit');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $page->add($setting);

    // Enable slideshow on frontpage guest page.
    $name = 'theme_recit/sliderfrontpage';
    $title = get_string('sliderfrontpage', 'theme_recit');
    $description = get_string('sliderfrontpagedesc', 'theme_recit');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_recit/slidercount';
    $title = get_string('slidercount', 'theme_recit');
    $description = get_string('slidercountdesc', 'theme_recit');
    $default = 1;
    $options = array();
    for ($i = 0; $i < 13; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // If we don't have an slide yet, default to the preset.
    $slidercount = get_config('theme_recit', 'slidercount');

    if (!$slidercount) {
        $slidercount = 1;
    }

    for ($sliderindex = 1; $sliderindex <= $slidercount; $sliderindex++) {
        $fileid = 'sliderimage' . $sliderindex;
        $name = 'theme_recit/sliderimage' . $sliderindex;
        $title = get_string('sliderimage', 'theme_recit');
        $description = get_string('sliderimagedesc', 'theme_recit');
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
        $setting = new admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_recit/slidertitle' . $sliderindex;
        $title = get_string('slidertitle', 'theme_recit');
        $description = get_string('slidertitledesc', 'theme_recit');
        $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
        $page->add($setting);

        $name = 'theme_recit/slidercap' . $sliderindex;
        $title = get_string('slidercaption', 'theme_recit');
        $description = get_string('slidercaptiondesc', 'theme_recit');
        $default = '';
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);
    }
   
    $settings->add($page);

    /*
    * --------------------
    * Footer settings tab
    * --------------------
    */
    $page = new admin_settingpage('theme_recit_footer', get_string('footersettings', 'theme_recit'));

    $name = 'theme_recit/getintouchcontent';
    $title = get_string('getintouchcontent', 'theme_recit');
    $description = get_string('getintouchcontentdesc', 'theme_recit');
    $default = 'RecitFad';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
  //  $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer logo
    $name = 'theme_recit/footerlogo';
    $title = get_string('footerlogo', 'theme_recit');
    $description = get_string('footerlogodesc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'footerlogo', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Website.
    $name = 'theme_recit/website';
    $title = get_string('website', 'theme_recit');
    $description = get_string('websitedesc', 'theme_recit');
    $default = 'https://recitfad.ca';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
 //   $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Terms of usage
    $name = 'theme_recit/termsurl';
    $title = get_string('termsurl', 'theme_recit');
    $description = get_string('termsurldesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
 //   $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // policy privacy
    $name = 'theme_recit/policyurl';
    $title = get_string('policyurl', 'theme_recit');
    $description = get_string('policyurldesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
 //   $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mobile.
    $name = 'theme_recit/mobile';
    $title = get_string('mobile', 'theme_recit');
    $description = get_string('mobiledesc', 'theme_recit');
    $default = '418-228-5541';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
//    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mail.
    $name = 'theme_recit/mail';
    $title = get_string('mail', 'theme_recit');
    $description = get_string('maildesc', 'theme_recit');
    $default = 'info@recitfad.ca';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
 //   $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Facebook url setting.
    $name = 'theme_recit/facebook';
    $title = get_string('facebook', 'theme_recit');
    $description = get_string('facebookdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
 //   $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Twitter url setting.
    $name = 'theme_recit/twitter';
    $title = get_string('twitter', 'theme_recit');
    $description = get_string('twitterdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
//    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Googleplus url setting.
    $name = 'theme_recit/googleplus';
    $title = get_string('googleplus', 'theme_recit');
    $description = get_string('googleplusdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
  //  $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Linkdin url setting.
    $name = 'theme_recit/linkedin';
    $title = get_string('linkedin', 'theme_recit');
    $description = get_string('linkedindesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
   // $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Youtube url setting.
    $name = 'theme_recit/youtube';
    $title = get_string('youtube', 'theme_recit');
    $description = get_string('youtubedesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
  //  $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Instagram url setting.
    $name = 'theme_recit/instagram';
    $title = get_string('instagram', 'theme_recit');
    $description = get_string('instagramdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
   // $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Disable bottom footer.
    $name = 'theme_recit/disablebottomfooter';
    $title = get_string('disablebottomfooter', 'theme_recit');
    $description = get_string('disablebottomfooterdesc', 'theme_recit');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $page->add($setting);
   // $setting->set_updatedcallback('theme_reset_all_caches');

    $settings->add($page);

    /*
    * -----------------------
    * Tree Topics
    * -----------------------
    */
    $settings->createCommonSettings('theme_recit');
}
