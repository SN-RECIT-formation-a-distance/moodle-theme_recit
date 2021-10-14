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
 * A two column layout for the recit theme.
 *
 * @package   theme_recit2
 * @copyright RÉCIT 2019
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("common.php");
require_once($CFG->libdir . '/behat/lib.php');

ThemeRecitUtils2::set_user_preference_drawer();

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

$extraclasses = [];

if (ThemeRecitUtils2::is_drawer_open_right() && $hasblocks) {
    $extraclasses[] = 'drawer-open-right';
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'page' => $PAGE,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'hasdrawertoggle' => true,
    'navdraweropen' => ThemeRecitUtils2::is_nav_drawer_open(),
    'draweropenright' => ThemeRecitUtils2::is_drawer_open_right(),
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
];

$themesettings = new \theme_recit2\util\theme_settings();

$templatecontext = array_merge($templatecontext, $themesettings->footer_items());

if (is_siteadmin()) {
    global $DB;

    // Get site total users.
    $totalactiveusers = $DB->count_records('user', array('deleted' => 0, 'suspended' => 0)) - 1;
    $totaldeletedusers = $DB->count_records('user', array('deleted' => 1));
    $totalsuspendedusers = $DB->count_records('user', array('deleted' => 0, 'suspended' => 1));

    // Get site total courses.
    $totalcourses = $DB->count_records('course') - 1;

    // Get the last online users in the past 5 minutes.
    $onlineusers = new \block_online_users\fetcher(null, time(), 300, null, CONTEXT_SYSTEM, null);
    $onlineusers = $onlineusers->count_users();

    // Get the disk usage.
    $cache = cache::make('theme_recit2', 'admininfos');
    $totalusagereadable = $cache->get('totalusagereadable');

    if (!$totalusagereadable) {
        $totalusage = get_directory_size($CFG->dataroot);
        $totalusagereadable = number_format(ceil($totalusage / 1048576));

        $cache->set('totalusagereadable', $totalusagereadable);
    }

    $usageunit = ' MB';
    if ($totalusagereadable > 1024) {
        $usageunit = ' GB';
    }

    $totalusagereadabletext = $totalusagereadable . $usageunit;

    $templatecontext['totalusage'] = $totalusagereadabletext;
    $templatecontext['totalactiveusers'] = $totalactiveusers;
    $templatecontext['totalsuspendedusers'] = $totalsuspendedusers;
    $templatecontext['totalcourses'] = $totalcourses;
    $templatecontext['onlineusers'] = $onlineusers;
}

$templatecontext = array_merge($templatecontext, ThemeRecitUtils2::get_template_context_common($OUTPUT, $PAGE, $USER));


echo $OUTPUT->render_from_template('theme_recit2/mydashboard', $templatecontext);
