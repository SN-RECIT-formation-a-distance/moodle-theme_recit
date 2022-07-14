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
 * @package    theme_recit2
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

use theme_recit2\local\AdminSettingsPageTabs;
use theme_recit2\local\Settings;

require_once($CFG->dirroot.'/theme/recit2/classes/local/AdminSettingsPageTabs.php');
require_once($CFG->dirroot.'/theme/recit2/classes/local/Settings.php');
require_once(__DIR__ ."/lib.php");

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    $childsettings = new Settings('recit2');
    $settings = new AdminSettingsPageTabs('themesetting'.$childsettings->theme_name, get_string('configtitle', 'theme_recit2'));
    $childsettings->settings($settings);

}
