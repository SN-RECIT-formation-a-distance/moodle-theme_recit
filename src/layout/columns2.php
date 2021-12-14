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

require_once($CFG->libdir . '/behat/lib.php');
//require_once($CFG->dirroot . '/theme/recit2/classes/local/CtrlLayout.php');

use theme_recit2\local\CtrlLayout;
use theme_recit2\local\ThemeSettings;

CtrlLayout::set_user_preference_drawer();

$hasdrawertoggle = false;
// Code $navdraweropen = false;
// Code $draweropenright = false;.

if (isloggedin()) {
    $hasdrawertoggle = true;
    // $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');"
    // $draweropenright = (get_user_preferences('sidepre-open', 'true') == 'true');
}

$blockshtml = $OUTPUT->blocks('side-pre');
$topblockshtml = $OUTPUT->blocks('side-post');

$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$hastopblocks = strpos($topblockshtml, 'data-block=') !== false;

$extraclasses = [];
/*if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}*/

if (CtrlLayout::is_drawer_open_right() && $hasblocks) {
    $extraclasses[] = 'drawer-open-right';
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'page' => $PAGE,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'sidetopblocks' => $topblockshtml,
    'hastopblocks' => $hastopblocks,
    'bodyattributes' => $bodyattributes,
    'hasdrawertoggle' => $hasdrawertoggle,
    'navdraweropen' => CtrlLayout::is_nav_drawer_open(),
    'draweropenright' => CtrlLayout::is_drawer_open_right(),
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu) && isset($_GET['categoryid']),
];

$showActivityNav = ThemeSettings::get_custom_field('show_activity_nav');
$templatecontext['show_activity_nav'] = ($showActivityNav == 1);

$templatecontext = array_merge($templatecontext, CtrlLayout::get_template_context_common($OUTPUT, $PAGE, $USER));
$templatecontext = array_merge($templatecontext, CtrlLayout::get_course_section_nav());

$themesettings = new ThemeSettings();

$templatecontext = array_merge($templatecontext, $themesettings->footer_items());

echo $OUTPUT->render_from_template('theme_recit2/recit/columns2', $templatecontext);