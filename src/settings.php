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

    // Preset.
    /*$name = 'theme_recit/preset';
    $title = get_string('preset', 'theme_recit');
    $description = get_string('preset_desc', 'theme_recit');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_recit', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Preset files setting.
    /*$name = 'theme_recit/presetfiles';
    $title = get_string('presetfiles', 'theme_recit');
    $description = get_string('presetfiles_desc', 'theme_recit');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 20, 'accepted_types' => array('.scss')));
    $page->add($setting);*/

    // Login page background image.
    $name = 'theme_recit/loginbgimg';
    $title = get_string('loginbgimg', 'theme_recit');
    $description = get_string('loginbgimg_desc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbgimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $brand-color.
    // We use an empty default value because the default colour should come from the preset.
   /* $name = 'theme_recit/brandcolor';
    $title = get_string('brandcolor', 'theme_recit');
    $description = get_string('brandcolor_desc', 'theme_recit');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
*/
    // Variable $navbar-header-color.
    // We use an empty default value because the default colour should come from the preset.
    /*$name = 'theme_recit/navbarheadercolor';
    $title = get_string('navbarheadercolor', 'theme_recit');
    $description = get_string('navbarheadercolor_desc', 'theme_recit');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Course theme.
    /*$name = 'theme_recit/coursetheme';
    $title = get_string('coursetheme', 'theme_recit');
    $description = get_string('coursethemedesc', 'theme_recit');
    $options = [];
    $options['theme-recit-francais'] = get_string('themeFrancais', 'theme_recit');
    $options['theme-recit-histoire'] = get_string('themeHistoire', 'theme_recit');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    /* Course format option.
    $name = 'theme_recit/coursepresentation';
    $title = get_string('coursepresentation', 'theme_recit');
    $description = get_string('coursepresentationdesc', 'theme_recit');
    $options = [];
    $options[1] = get_string('coursedefault', 'theme_recit');
    $options[2] = get_string('coursecover', 'theme_recit');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_recit/courselistview';
    $title = get_string('courselistview', 'theme_recit');
    $description = get_string('courselistviewdesc', 'theme_recit');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $page->add($setting);*/

    $name = 'theme_recit/enablebreadcrumb';
    $title = get_string('enablebreadcrumb', 'theme_recit');
    $description = get_string('enablebreadcrumbdesc', 'theme_recit');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    //$setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    /*
    * ----------------------
    * Advanced settings tab
    * ----------------------
    */
    /*$page = new admin_settingpage('theme_recit_advanced', get_string('advancedsettings', 'theme_recit'));*/

    // Raw SCSS to include before the content.
    /*$setting = new admin_setting_scsscode('theme_recit/scsspre',
        get_string('rawscsspre', 'theme_recit'), get_string('rawscsspre_desc', 'theme_recit'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Raw SCSS to include after the content.
    /*$setting = new admin_setting_scsscode('theme_recit/scss', get_string('rawscss', 'theme_recit'),
        get_string('rawscss_desc', 'theme_recit'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Google analytics block.
    /*$name = 'theme_recit/googleanalytics';
    $title = get_string('googleanalytics', 'theme_recit');
    $description = get_string('googleanalyticsdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
*/
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

    // Bannerheading.
  /*  $name = 'theme_recit/bannerheading';
    $title = get_string('bannerheading', 'theme_recit');
    $description = get_string('bannerheadingdesc', 'theme_recit');
    $default = 'Perfect Learning System';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Bannercontent.
   /* $name = 'theme_recit/bannercontent';
    $title = get_string('bannercontent', 'theme_recit');
    $description = get_string('bannercontentdesc', 'theme_recit');
    $default = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_recit/displaymarketingbox';
    $title = get_string('displaymarketingbox', 'theme_recit');
    $description = get_string('displaymarketingboxdesc', 'theme_recit');
    $default = 0;
    $choices = array(0 => 'No', 1 => 'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);*/

    // Marketing1icon.
   /* $name = 'theme_recit/marketing1icon';
    $title = get_string('marketing1icon', 'theme_recit');
    $description = get_string('marketing1icondesc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'marketing1icon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing1heading.
   /* $name = 'theme_recit/marketing1heading';
    $title = get_string('marketing1heading', 'theme_recit');
    $description = get_string('marketing1headingdesc', 'theme_recit');
    $default = 'We host';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing1subheading.
  /*  $name = 'theme_recit/marketing1subheading';
    $title = get_string('marketing1subheading', 'theme_recit');
    $description = get_string('marketing1subheadingdesc', 'theme_recit');
    $default = 'your MOODLE';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing1content.
    /*$name = 'theme_recit/marketing1content';
    $title = get_string('marketing1content', 'theme_recit');
    $description = get_string('marketing1contentdesc', 'theme_recit');
    $default = 'Moodle hosting in a powerful cloud infrastructure';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing1url.
  /*  $name = 'theme_recit/marketing1url';
    $title = get_string('marketing1url', 'theme_recit');
    $description = get_string('marketing1urldesc', 'theme_recit');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing2icon.
 /*   $name = 'theme_recit/marketing2icon';
    $title = get_string('marketing2icon', 'theme_recit');
    $description = get_string('marketing2icondesc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'marketing2icon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing2heading.
   /* $name = 'theme_recit/marketing2heading';
    $title = get_string('marketing2heading', 'theme_recit');
    $description = get_string('marketing2headingdesc', 'theme_recit');
    $default = 'Consulting';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing2subheading.
    /*$name = 'theme_recit/marketing2subheading';
    $title = get_string('marketing2subheading', 'theme_recit');
    $description = get_string('marketing2subheadingdesc', 'theme_recit');
    $default = 'for your company';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing2content.
 /*   $name = 'theme_recit/marketing2content';
    $title = get_string('marketing2content', 'theme_recit');
    $description = get_string('marketing2contentdesc', 'theme_recit');
    $default = 'Moodle consulting and training for you';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing2url.
   /* $name = 'theme_recit/marketing2url';
    $title = get_string('marketing2url', 'theme_recit');
    $description = get_string('marketing2urldesc', 'theme_recit');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing3icon.
  /*  $name = 'theme_recit/marketing3icon';
    $title = get_string('marketing3icon', 'theme_recit');
    $description = get_string('marketing3icondesc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'marketing3icon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing3heading.
   /* $name = 'theme_recit/marketing3heading';
    $title = get_string('marketing3heading', 'theme_recit');
    $description = get_string('marketing3headingdesc', 'theme_recit');
    $default = 'Development';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing3subheading.
  /*  $name = 'theme_recit/marketing3subheading';
    $title = get_string('marketing3subheading', 'theme_recit');
    $description = get_string('marketing3subheadingdesc', 'theme_recit');
    $default = 'themes and plugins';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing3content.
   /* $name = 'theme_recit/marketing3content';
    $title = get_string('marketing3content', 'theme_recit');
    $description = get_string('marketing3contentdesc', 'theme_recit');
    $default = 'We develop themes and plugins as your desires';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing3url.
  /*  $name = 'theme_recit/marketing3url';
    $title = get_string('marketing3url', 'theme_recit');
    $description = get_string('marketing3urldesc', 'theme_recit');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing4icon.
   /* $name = 'theme_recit/marketing4icon';
    $title = get_string('marketing4icon', 'theme_recit');
    $description = get_string('marketing4icondesc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'marketing4icon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing4heading.
   /* $name = 'theme_recit/marketing4heading';
    $title = get_string('marketing4heading', 'theme_recit');
    $description = get_string('marketing4headingdesc', 'theme_recit');
    $default = 'Support';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing4subheading.
  /*  $name = 'theme_recit/marketing4subheading';
    $title = get_string('marketing4subheading', 'theme_recit');
    $description = get_string('marketing4subheadingdesc', 'theme_recit');
    $default = 'we give you answers';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing4content.
    /*$name = 'theme_recit/marketing4content';
    $title = get_string('marketing4content', 'theme_recit');
    $description = get_string('marketing4contentdesc', 'theme_recit');
    $default = 'MOODLE specialized support';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Marketing4url.
   /* $name = 'theme_recit/marketing4url';
    $title = get_string('marketing4url', 'theme_recit');
    $description = get_string('marketing4urldesc', 'theme_recit');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

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

    // Enable or disable Slideshow settings.
    /*$name = 'theme_recit/numbersfrontpage';
    $title = get_string('numbersfrontpage', 'theme_recit');
    $description = get_string('numbersfrontpagedesc', 'theme_recit');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);*/

    // Enable sponsors on frontpage guest page.
   /* $name = 'theme_recit/sponsorsfrontpage';
    $title = get_string('sponsorsfrontpage', 'theme_recit');
    $description = get_string('sponsorsfrontpagedesc', 'theme_recit');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_recit/sponsorstitle';
    $title = get_string('sponsorstitle', 'theme_recit');
    $description = get_string('sponsorstitledesc', 'theme_recit');
    $default = get_string('sponsorstitledefault', 'theme_recit');
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $page->add($setting);

    $name = 'theme_recit/sponsorssubtitle';
    $title = get_string('sponsorssubtitle', 'theme_recit');
    $description = get_string('sponsorssubtitledesc', 'theme_recit');
    $default = get_string('sponsorssubtitledefault', 'theme_recit');
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $page->add($setting);

    $name = 'theme_recit/sponsorscount';
    $title = get_string('sponsorscount', 'theme_recit');
    $description = get_string('sponsorscountdesc', 'theme_recit');
    $default = 1;
    $options = array();
    for ($i = 0; $i < 5; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // If we don't have an slide yet, default to the preset.
    $sponsorscount = get_config('theme_recit', 'sponsorscount');

    if (!$sponsorscount) {
        $sponsorscount = 1;
    }

    for ($sponsorsindex = 1; $sponsorsindex <= $sponsorscount; $sponsorsindex++) {
        $fileid = 'sponsorsimage' . $sponsorsindex;
        $name = 'theme_recit/sponsorsimage' . $sponsorsindex;
        $title = get_string('sponsorsimage', 'theme_recit');
        $description = get_string('sponsorsimagedesc', 'theme_recit');
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
        $setting = new admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_recit/sponsorsurl' . $sponsorsindex;
        $title = get_string('sponsorsurl', 'theme_recit');
        $description = get_string('sponsorsurldesc', 'theme_recit');
        $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
        $page->add($setting);
    }*/

    // Enable clients on frontpage guest page.
  /*  $name = 'theme_recit/clientsfrontpage';
    $title = get_string('clientsfrontpage', 'theme_recit');
    $description = get_string('clientsfrontpagedesc', 'theme_recit');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_recit/clientstitle';
    $title = get_string('clientstitle', 'theme_recit');
    $description = get_string('clientstitledesc', 'theme_recit');
    $default = get_string('clientstitledefault', 'theme_recit');
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $page->add($setting);

    $name = 'theme_recit/clientssubtitle';
    $title = get_string('clientssubtitle', 'theme_recit');
    $description = get_string('clientssubtitledesc', 'theme_recit');
    $default = get_string('clientssubtitledefault', 'theme_recit');
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $page->add($setting);

    $name = 'theme_recit/clientscount';
    $title = get_string('clientscount', 'theme_recit');
    $description = get_string('clientscountdesc', 'theme_recit');
    $default = 1;
    $options = array();
    for ($i = 0; $i < 5; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // If we don't have an slide yet, default to the preset.
    $clientscount = get_config('theme_recit', 'clientscount');

    if (!$clientscount) {
        $clientscount = 1;
    }

    for ($clientsindex = 1; $clientsindex <= $clientscount; $clientsindex++) {
        $fileid = 'clientsimage' . $clientsindex;
        $name = 'theme_recit/clientsimage' . $clientsindex;
        $title = get_string('clientsimage', 'theme_recit');
        $description = get_string('clientsimagedesc', 'theme_recit');
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
        $setting = new admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_recit/clientsurl' . $clientsindex;
        $title = get_string('clientsurl', 'theme_recit');
        $description = get_string('clientsurldesc', 'theme_recit');
        $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
        $page->add($setting);
    }*/

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
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Website.
    $name = 'theme_recit/website';
    $title = get_string('website', 'theme_recit');
    $description = get_string('websitedesc', 'theme_recit');
    $default = 'https://recitfad.ca';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mobile.
    $name = 'theme_recit/mobile';
    $title = get_string('mobile', 'theme_recit');
    $description = get_string('mobiledesc', 'theme_recit');
    $default = '418-228-5541';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mail.
    $name = 'theme_recit/mail';
    $title = get_string('mail', 'theme_recit');
    $description = get_string('maildesc', 'theme_recit');
    $default = 'info@recitfad.ca';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Facebook url setting.
    $name = 'theme_recit/facebook';
    $title = get_string('facebook', 'theme_recit');
    $description = get_string('facebookdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Twitter url setting.
    $name = 'theme_recit/twitter';
    $title = get_string('twitter', 'theme_recit');
    $description = get_string('twitterdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Googleplus url setting.
    $name = 'theme_recit/googleplus';
    $title = get_string('googleplus', 'theme_recit');
    $description = get_string('googleplusdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Linkdin url setting.
    $name = 'theme_recit/linkedin';
    $title = get_string('linkedin', 'theme_recit');
    $description = get_string('linkedindesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Youtube url setting.
    $name = 'theme_recit/youtube';
    $title = get_string('youtube', 'theme_recit');
    $description = get_string('youtubedesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Instagram url setting.
    $name = 'theme_recit/instagram';
    $title = get_string('instagram', 'theme_recit');
    $description = get_string('instagramdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Top footer background image.
  /* $name = 'theme_recit/topfooterimg';
    $title = get_string('topfooterimg', 'theme_recit');
    $description = get_string('topfooterimgdesc', 'theme_recit');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'topfooterimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);*/

    // Disable bottom footer.
    $name = 'theme_recit/disablebottomfooter';
    $title = get_string('disablebottomfooter', 'theme_recit');
    $description = get_string('disablebottomfooterdesc', 'theme_recit');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $page->add($setting);
    $setting->set_updatedcallback('theme_reset_all_caches');

    $settings->add($page);

  /*  // Forum page.
    $settingpage = new admin_settingpage('theme_recit_forum', get_string('forumsettings', 'theme_recit'));

    $settingpage->add(new admin_setting_heading('theme_recit_forumheading', null,
            format_text(get_string('forumsettingsdesc', 'theme_recit'), FORMAT_MARKDOWN)));

    // Enable custom template.
    $name = 'theme_recit/forumcustomtemplate';
    $title = get_string('forumcustomtemplate', 'theme_recit');
    $description = get_string('forumcustomtemplatedesc', 'theme_recit');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settingpage->add($setting);

    // Header setting.
    $name = 'theme_recit/forumhtmlemailheader';
    $title = get_string('forumhtmlemailheader', 'theme_recit');
    $description = get_string('forumhtmlemailheaderdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $settingpage->add($setting);

    // Footer setting.
    $name = 'theme_recit/forumhtmlemailfooter';
    $title = get_string('forumhtmlemailfooter', 'theme_recit');
    $description = get_string('forumhtmlemailfooterdesc', 'theme_recit');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $settingpage->add($setting);

    $settings->add($settingpage);*/    
}
