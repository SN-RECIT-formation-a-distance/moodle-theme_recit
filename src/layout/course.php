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
 * @package   theme_recit
 * @copyright RÉCITFAD 2019
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("common.php");
require_once($CFG->libdir . '/behat/lib.php');

ThemeRecitUtils::set_user_preference_drawer();

/*if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
    $draweropenright = (get_user_preferences('sidepre-open', 'true') == 'true');
} else {
    $navdraweropen = false;
    $draweropenright = false;
}*/

$blockshtml = $OUTPUT->blocks('side-pre');
$topblockshtml = $OUTPUT->blocks('side-post');

$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$hastopblocks = strpos($topblockshtml, 'data-block=') !== false;

$extraclasses = [];
/*if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}*/

if (ThemeRecitUtils::is_drawer_open_right() && $hasblocks) {
    $extraclasses[] = 'drawer-open-right';
}

$coursepresentation = theme_recit_get_setting('coursepresentation');
if ($coursepresentation == 2) {
    $extraclasses[] = 'coursepresentation-cover';
}

$extraclasses[] = theme_recit_get_course_theme();

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'sidetopblocks' => $topblockshtml,
    'hastopblocks' => $hastopblocks,
    'bodyattributes' => $bodyattributes,
    'hasdrawertoggle' => true,
    'navdraweropen' => ThemeRecitUtils::is_nav_drawer_open(),
    'draweropenright' => ThemeRecitUtils::is_drawer_open_right(),
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
];

$templatecontext = array_merge($templatecontext, ThemeRecitUtils::get_template_context_common($OUTPUT, $PAGE, $USER));

$themesettings = new \theme_recit\util\theme_settings();

$templatecontext = array_merge($templatecontext, $themesettings->footer_items());

if (!$coursepresentation || $coursepresentation == 1) {
    echo $OUTPUT->render_from_template('theme_recit/columns2', $templatecontext);
} else if ($coursepresentation == 2) {
    echo $OUTPUT->render_from_template('theme_recit/course_cover', $templatecontext);
}
