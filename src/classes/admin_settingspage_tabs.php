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
 * @package   theme_recit2
 * @copyright 2019 RECIT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * @package   theme_recit2
 * @copyright 2019 RECIT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_recit2_admin_settingspage_tabs extends admin_settingpage {

    /** @var The tabs */
    protected $tabs = array();
    protected $fontfamily = array(
        "" => "",
        "'Viga', sans-serif" => "Viga",
        "'Times New Roman', sans-serif" => "Times new roman"
    );

    public function createCommonSettings($themeName, $key = '', $tabName = ''){
        $name = get_string('variablessettings', 'theme_recit2');
        if (!empty($tabName)) $name = $tabName;
        $page = new admin_settingpage($themeName.'_colors'.$key, $name);

        $name = $themeName.'/ttmenucolor1'.$key;
        $title = get_string('ttmenucolorX', 'theme_recit2', "1");
        $description = get_string('ttmenucolorX_desc', 'theme_recit2', "1");
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/ttmenucolor2'.$key;
        $title = get_string('ttmenucolorX', 'theme_recit2', "2");
        $description = get_string('ttmenucolorX_desc', 'theme_recit2', "2");
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/ttmenucolor3'.$key;
        $title = get_string('ttmenucolorX', 'theme_recit2', "3");
        $description = get_string('ttmenucolorX_desc', 'theme_recit2', "3");
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/ttmenucolor4'.$key;
        $title = get_string('ttmenucolorX', 'theme_recit2', "4");
        $description = get_string('ttmenucolorX_desc', 'theme_recit2', "4");
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/primarycolor'.$key;
        $title = get_string('primarycolor', 'theme_recit2');
        $description = get_string('primarycolor_desc', 'theme_recit2');
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/primaryboldcolor'.$key;
        $title = get_string('primaryboldcolor', 'theme_recit2');
        $description = get_string('primaryboldcolor_desc', 'theme_recit2');
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/secondarycolor'.$key;
        $title = get_string('secondarycolor', 'theme_recit2');
        $description = get_string('secondarycolor_desc', 'theme_recit2');
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/navcolor'.$key;
        $title = get_string('navcolor', 'theme_recit2');
        $description = get_string('navcolor_desc', 'theme_recit2');
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/color1'.$key;
        $title = get_string('color1', 'theme_recit2');
        $description = get_string('color1_desc', 'theme_recit2');
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/color2'.$key;
        $title = get_string('color2', 'theme_recit2');
        $description = get_string('color2_desc', 'theme_recit2');
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/btnradius'.$key;
        $title = get_string('btnradius', 'theme_recit2');
        $description = get_string('btnradius_desc', 'theme_recit2');
        $setting = new admin_setting_configtext($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/fontsize'.$key;
        $title = get_string('fontsize', 'theme_recit2');
        $description = get_string('fontsize_desc', 'theme_recit2');
        $setting = new admin_setting_configtext($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/fontfamily'.$key;
        $title = get_string('fontfamily', 'theme_recit2');
        $description = get_string('fontfamily_desc', 'theme_recit2');
        $setting = new admin_setting_configselect($name, $title, $description, '', $this->fontfamily);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = $themeName.'/headingsfontfamily'.$key;
        $title = get_string('headingsfontfamily', 'theme_recit2');
        $description = get_string('headingsfontfamily_desc', 'theme_recit2');
        $setting = new admin_setting_configselect($name, $title, $description, '', $this->fontfamily);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $this->add($page);
    }
    /**
     * Add a tab.
     *
     * @param admin_settingpage $tab A tab.
     */
    public function add_tab(admin_settingpage $tab) {
        foreach ($tab->settings as $setting) {
            $this->settings->{$setting->name} = $setting;
        }
        $this->tabs[] = $tab;
        return true;
    }

    public function add($tab) {
        return $this->add_tab($tab);
    }

    /**
     * Get tabs.
     *
     * @return array
     */
    public function get_tabs() {
        return $this->tabs;
    }

    /**
     * Generate the HTML output.
     *
     * @return string
     */
    public function output_html() {
        global $OUTPUT;

        $activetab = optional_param('activetab', '', PARAM_TEXT);
        $context = array('tabs' => array());
        $havesetactive = false;

        foreach ($this->get_tabs() as $tab) {
            $active = false;

            // Default to first tab it not told otherwise.
            if (empty($activetab) && !$havesetactive) {
                $active = true;
                $havesetactive = true;
            } else if ($activetab === $tab->name) {
                $active = true;
            }

            $context['tabs'][] = array(
                'name' => $tab->name,
                'displayname' => $tab->visiblename,
                'html' => $tab->output_html(),
                'active' => $active,
            );
        }

        if (empty($context['tabs'])) {
            return '';
        }

        return $OUTPUT->render_from_template('theme_recit2/admin_setting_tabs', $context);
    }
}
