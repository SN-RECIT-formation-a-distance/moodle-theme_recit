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
user_preference_allow_ajax_update('drawer-open-index', PARAM_BOOL);
user_preference_allow_ajax_update('drawer-open-block', PARAM_BOOL);

$hasdrawertoggle = false;
$navdraweropen = false;
$draweropenright = false;

if (isloggedin()) {
    $hasdrawertoggle = true;
}

$blockshtml = $OUTPUT->blocks('side-pre');
$topblockshtml = $OUTPUT->blocks('side-post');

$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$hastopblocks = strpos($topblockshtml, 'data-block=') !== false;

$extraclasses = [];
/*if (CtrlLayout::is_nav_drawer_open()) {
    $extraclasses[] = 'drawer-open-left';
    $navdraweropen = true;
}*/

if (CtrlLayout::is_drawer_open_right() && $hasblocks) {
    $extraclasses[] = 'drawer-open-right';
    $draweropenright = true;
}
$extraclasses[] = ThemeSettings::get_subtheme_class();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}
$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'page' => $PAGE,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'sidetopblocks' => $topblockshtml,
    'hastopblocks' => $hastopblocks,
    'bodyattributes' => $bodyattributes,
    'overflow' => $overflow,
    'hasdrawertoggle' => $hasdrawertoggle,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'headercontent' => $headercontent,
    'navdraweropen' => $navdraweropen,
    'draweropenright' => $draweropenright,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu) && isset($_GET['categoryid']),
];

$showActivityNav = ThemeSettings::get_custom_field('show_activity_nav');
$templatecontext['show_activity_nav'] = ($showActivityNav == 1);

$templatecontext = array_merge($templatecontext, CtrlLayout::get_template_context_common($OUTPUT, $PAGE, $USER));
$templatecontext = array_merge($templatecontext, CtrlLayout::get_course_section_nav());

$themesettings = new ThemeSettings();

$templatecontext = array_merge($templatecontext, $themesettings->footer_items());

//Activity setting
if (isset($PAGE->cm->modname)) {
    $templatecontext['activitysettings'] = $OUTPUT->region_main_settings_menu();
}

if($PAGE->__get('pagelayout') == 'mydashboard'){    
    echo $OUTPUT->render_from_template('theme_recit2/recit/mydashboard', $templatecontext);
}
else{
    echo $OUTPUT->render_from_template('theme_recit2/recit/columns2', $templatecontext);
}