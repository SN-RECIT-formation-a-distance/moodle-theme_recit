<?php

namespace theme_recit2\local;
class Settings {

    public $theme_name = '';

    public function __construct($theme_name) {
        $this->theme_name = $theme_name;
    }

    public function settings(&$settings) {
        global $CFG;
        
        /*
        * ----------------------
        * General settings tab
        * ----------------------
        */
        $page = new \admin_settingpage('theme_'.$this->theme_name.'_general', get_string('generalsettings', 'theme_recit2'));

        // Logo file setting.
        $name = 'theme_'.$this->theme_name.'/logo';
        $title = get_string('logo', 'theme_recit2');
        $description = get_string('logodesc', 'theme_recit2');
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
        $setting = new \admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
        $page->add($setting);

        // Favicon setting.
        $name = 'theme_'.$this->theme_name.'/favicon';
        $title = get_string('favicon', 'theme_recit2');
        $description = get_string('favicondesc', 'theme_recit2');
        $opts = array('accepted_types' => array('.ico'), 'maxfiles' => 1);
        $setting = new \admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
        $page->add($setting);

        // Login page background image.
        $name = 'theme_'.$this->theme_name.'/loginbgimg';
        $title = get_string('loginbgimg', 'theme_recit2');
        $description = get_string('loginbgimg_desc', 'theme_recit2');
        $opts = array('accepted_types' => array('.png', '.jpg', '.svg'));
        $setting = new \admin_setting_configstoredfile($name, $title, $description, 'loginbgimg', 0, $opts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_'.$this->theme_name.'/coursebanner';
        $title = get_string('course-banner', 'theme_recit2');
        $description = get_string('course-banner-desc', 'theme_recit2');
        $opts = array('accepted_types' => array('.png', '.jpg', '.svg'));
        $setting = new \admin_setting_configstoredfile($name, $title, $description, 'coursebanner', 0, $opts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_'.$this->theme_name.'/showleavingsitewarning';
        $title = get_string('showleavingsitewarning', 'theme_recit2');
        $description = get_string('showleavingsitewarningdesc', 'theme_recit2');
        $setting = new \admin_setting_configcheckbox($name, $title, $description, 0);
        $page->add($setting);

        $name = 'theme_'.$this->theme_name.'/enablebreadcrumb';
        $title = get_string('enablebreadcrumb', 'theme_recit2');
        $description = get_string('enablebreadcrumbdesc', 'theme_recit2');
        $setting = new \admin_setting_configcheckbox($name, $title, $description, 0);
        $page->add($setting);

        $name = 'theme_'.$this->theme_name.'/enablebs4warning';
        $title = get_string('enablebs4warning', 'theme_recit2');
        $description = get_string('enablebs4warningdesc', 'theme_recit2');
        $setting = new \admin_setting_configcheckbox($name, $title, $description, 0);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_'.$this->theme_name.'/categoryliststyle';
        $title = get_string('categoryliststyle', 'theme_recit2');
        $description = get_string('categoryliststyledesc', 'theme_recit2');
        $default = 0;
        $options = array(0 => get_string('condensed', 'theme_recit2'), 1 => get_string('extended', 'theme_recit2'));
        $setting = new \admin_setting_configselect($name, $title, $description, $default, $options);
        $page->add($setting);

        // Must add the page after defining all the settings!
        $settings->add($page);

        /*
        * -----------------------
        * Frontpage settings tab
        * -----------------------
        */
        $page = new \admin_settingpage('theme_'.$this->theme_name.'_frontpage', get_string('frontpagesettings', 'theme_recit2'));

        // Headerimg file setting.
        $name = 'theme_'.$this->theme_name.'/headerimg';
        $title = get_string('headerimg', 'theme_recit2');
        $description = get_string('headerimgdesc', 'theme_recit2');
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
        $setting = new \admin_setting_configstoredfile($name, $title, $description, 'headerimg', 0, $opts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    
        $name = 'theme_'.$this->theme_name.'/featuredcourses';
        $title = get_string('featuredcourses', 'theme_recit2');
        $description = get_string('featuredcoursesdesc', 'theme_recit2');
        $default = '';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        // Enable or disable Slideshow settings.
        $name = 'theme_'.$this->theme_name.'/sliderenabled';
        $title = get_string('sliderenabled', 'theme_recit2');
        $description = get_string('sliderenableddesc', 'theme_recit2');
        $setting = new \admin_setting_configcheckbox($name, $title, $description, 0);
        $page->add($setting);

        $name = 'theme_'.$this->theme_name.'/slidercount';
        $title = get_string('slidercount', 'theme_recit2');
        $description = get_string('slidercountdesc', 'theme_recit2');
        $default = 1;
        $options = array();
        for ($i = 0; $i < 13; $i++) {
            $options[$i] = $i;
        }
        $setting = new \admin_setting_configselect($name, $title, $description, $default, $options);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // If we don't have an slide yet, default to the preset.
        $slidercount = get_config('theme_recit2', 'slidercount');

        if (!$slidercount) {
            $slidercount = 1;
        }

        for ($sliderindex = 1; $sliderindex <= $slidercount; $sliderindex++) {
            $fileid = 'sliderimage' . $sliderindex;
            $name = 'theme_'.$this->theme_name.'/sliderimage' . $sliderindex;
            $title = get_string('sliderimage', 'theme_recit2');
            $description = get_string('sliderimagedesc', 'theme_recit2');
            $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
            $setting = new \admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_'.$this->theme_name.'/slidertitle' . $sliderindex;
            $title = get_string('slidertitle', 'theme_recit2');
            $description = get_string('slidertitledesc', 'theme_recit2');
            $setting = new \admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
            $page->add($setting);

            $name = 'theme_'.$this->theme_name.'/slidercap' . $sliderindex;
            $title = get_string('slidercaption', 'theme_recit2');
            $description = get_string('slidercaptiondesc', 'theme_recit2');
            $default = '';
            $setting = new \admin_setting_confightmleditor($name, $title, $description, $default);
            $page->add($setting);
        }
    
        $settings->add($page);

        /*
        * --------------------
        * Footer settings tab
        * --------------------
        */
        $page = new \admin_settingpage('theme_'.$this->theme_name.'_footer', get_string('footersettings', 'theme_recit2'));

        // Footer logo
        $name = 'theme_'.$this->theme_name.'/footerlogo';
        $title = get_string('footerlogo', 'theme_recit2');
        $description = get_string('footerlogodesc', 'theme_recit2');
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
        $setting = new \admin_setting_configstoredfile($name, $title, $description, 'footerlogo', 0, $opts);
        $page->add($setting);

        /* Footer Content */
        $name = 'theme_'.$this->theme_name.'/footnote';
        $title = get_string('footnote', 'theme_recit2');
        $description = get_string('footnotedesc', 'theme_recit2');
        $default = "";
        $setting = new \admin_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);

        // INFO Link.
        $name = 'theme_'.$this->theme_name.'/infolink';
        $title = get_string('infolink', 'theme_recit2');
        $description = get_string('infolink_desc', 'theme_recit2');
        $default = get_string('infolinkdefault', 'theme_recit2');
        $setting = new \admin_setting_configtextarea($name, $title, $description, $default);
        $page->add($setting);

        // Copyright.
        $name = 'theme_'.$this->theme_name.'/copyright_footer';
        $title = get_string('copyright_footer', 'theme_recit2');
        $description = '';
        $default = get_string('copyright_default', 'theme_recit2');
        $setting = new \admin_setting_configtextarea($name, $title, $description, $default);
        $page->add($setting);

       /* $name = 'theme_'.$this->theme_name.'/navcolor';
        $title = get_string('navcolor', 'theme_recit2');
        $description = get_string('navcolor_desc', 'theme_recit2');
        $setting = new \admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);*/

        // Website.
        $name = 'theme_'.$this->theme_name.'/website';
        $title = get_string('website', 'theme_recit2');
        $description = get_string('websitedesc', 'theme_recit2');
        $default = 'https://recitfad.ca';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        // Mobile.
        $name = 'theme_'.$this->theme_name.'/mobile';
        $title = get_string('mobile', 'theme_recit2');
        $description = get_string('mobiledesc', 'theme_recit2');
        $default = '123-456-7890';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        // Mail.
        $name = 'theme_'.$this->theme_name.'/mail';
        $title = get_string('mail', 'theme_recit2');
        $description = get_string('maildesc', 'theme_recit2');
        $default = 'info@recitfad.ca';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        // Facebook url setting.
        $name = 'theme_'.$this->theme_name.'/facebook';
        $title = get_string('facebook', 'theme_recit2');
        $description = get_string('facebookdesc', 'theme_recit2');
        $default = '';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        // Twitter url setting.
        $name = 'theme_'.$this->theme_name.'/twitter';
        $title = get_string('twitter', 'theme_recit2');
        $description = get_string('twitterdesc', 'theme_recit2');
        $default = '';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        // Linkdin url setting.
        $name = 'theme_'.$this->theme_name.'/linkedin';
        $title = get_string('linkedin', 'theme_recit2');
        $description = get_string('linkedindesc', 'theme_recit2');
        $default = '';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        // Youtube url setting.
        $name = 'theme_'.$this->theme_name.'/youtube';
        $title = get_string('youtube', 'theme_recit2');
        $description = get_string('youtubedesc', 'theme_recit2');
        $default = '';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        // Instagram url setting.
        $name = 'theme_'.$this->theme_name.'/instagram';
        $title = get_string('instagram', 'theme_recit2');
        $description = get_string('instagramdesc', 'theme_recit2');
        $default = '';
        $setting = new \admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);

        $settings->add($page);

        // Advanced settings.
        $page = new \admin_settingpage('theme_'.$this->theme_name.'_advanced', get_string('advancedsettings', 'theme_recit2'));

        // Raw SCSS to include before the content.
        if (file_exists($CFG->dirroot . "/theme/{$this->theme_name}/scss/recit/_variables.scss")){
            $default = file_get_contents($CFG->dirroot . "/theme/{$this->theme_name}/scss/recit/_variables.scss");
        }else{
            $default = file_get_contents($CFG->dirroot . "/theme/recit2/scss/recit/_variables.scss");
        }
        $setting = new \admin_setting_configtextarea('theme_'.$this->theme_name.'/prescss', get_string('rawscsspre', 'theme_recit2'), get_string('rawscsspre_desc', 'theme_recit2'), $default, PARAM_RAW, 60, 25);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Raw SCSS to include after the content.
        $default = '';
        if (file_exists($CFG->dirroot . "/theme/{$this->theme_name}/scss/recit/_extrascss.scss")){
            $default = file_get_contents($CFG->dirroot . "/theme/{$this->theme_name}/scss/recit/_extrascss.scss");
        }
        $setting = new \admin_setting_configtextarea('theme_'.$this->theme_name.'/extrascss', get_string('rawscss', 'theme_recit2'), get_string('rawscss_desc', 'theme_recit2'), $default, PARAM_RAW, 60, 15);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $settings->add($page);
    }
}