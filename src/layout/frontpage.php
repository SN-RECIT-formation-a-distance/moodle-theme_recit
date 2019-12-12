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
 * Frontpage layout for the recit theme.
 *
 * @package   theme_recit
 * @copyright RÉCIT 2019
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("common.php");
require_once($CFG->libdir . '/behat/lib.php');

ThemeRecitUtils::setUserPreferenceDrawer();

$extraclasses = [];

$themesettings = new \theme_recit\util\theme_settings();

if (isloggedin()) {
    $blockshtml = $OUTPUT->blocks('side-pre');
    $hasblocks = strpos($blockshtml, 'data-block=') !== false;

    if (ThemeRecitUtils::isDrawerOpenRight() && $hasblocks) {
        $extraclasses[] = 'drawer-open-right';
    }

    $extraclasses[] = theme_recit_get_course_theme();

    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
    $templatecontext = [
        'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
        'sidepreblocks' => $blockshtml,
        'hasblocks' => $hasblocks,
        'bodyattributes' => $bodyattributes,
        'hasdrawertoggle' => true,
        'navdraweropen' => ThemeRecitUtils::isNavDrawerOpen(),
        'draweropenright' => ThemeRecitUtils::isDrawerOpenRight(),
        'regionmainsettingsmenu' => $regionmainsettingsmenu,
        'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
    ];

    $templatecontext = array_merge($templatecontext, ThemeRecitUtils::getTemplateContextCommon($OUTPUT, $PAGE, $USER));


    $templatecontext = array_merge($templatecontext, $themesettings->footer_items(), $themesettings->slideshow());

    echo $OUTPUT->render_from_template('theme_recit/frontpage', $templatecontext);
} else {
    $sliderfrontpage = false;
    if ((theme_recit_get_setting('sliderenabled', true) == true) && (theme_recit_get_setting('sliderfrontpage', true) == true)) {
        $sliderfrontpage = true;
        $extraclasses[] = 'slideshow';
    }

    $numbersfrontpage = false;
    /*if (theme_recit_get_setting('numbersfrontpage', true) == true) {
        $numbersfrontpage = true;
    }*/

    $sponsorsfrontpage = false;
    /*if (theme_recit_get_setting('sponsorsfrontpage', true) == true) {
        $sponsorsfrontpage = true;
    }*/

    $clientsfrontpage = false;
    /*if (theme_recit_get_setting('clientsfrontpage', true) == true) {
        $clientsfrontpage = true;
    }*/

    $bannerheading = '';
    if (!empty($PAGE->theme->settings->bannerheading)) {
        $bannerheading = theme_recit_get_setting('bannerheading', true);
    }

    $bannercontent = '';
    if (!empty($PAGE->theme->settings->bannercontent)) {
        $bannercontent = theme_recit_get_setting('bannercontent', true);
    }

    $shoulddisplaymarketing = false;
    /*if (theme_recit_get_setting('displaymarketingbox', true) == true) {
        $shoulddisplaymarketing = true;
    }*/

    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

    $templatecontext = [
        'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
        'bodyattributes' => $bodyattributes,
        'hasdrawertoggle' => false,
        'cansignup' => $CFG->registerauth == 'email' || !empty($CFG->registerauth),
        'bannerheading' => $bannerheading,
        'bannercontent' => $bannercontent,
        'shoulddisplaymarketing' => $shoulddisplaymarketing,
        'sliderfrontpage' => $sliderfrontpage,
        'numbersfrontpage' => $numbersfrontpage,
        'sponsorsfrontpage' => $sponsorsfrontpage,
        'clientsfrontpage' => $clientsfrontpage,
        'logintoken' => \core\session\manager::get_login_token()
    ];

    $templatecontext = array_merge($templatecontext, ThemeRecitUtils::getTemplateContextCommon($OUTPUT, $PAGE, $USER));

    $templatecontext = array_merge($templatecontext, $themesettings->footer_items(), $themesettings->marketing_items());

    if ($sliderfrontpage) {
        $templatecontext = array_merge($templatecontext, $themesettings->slideshow());
    }

    if ($numbersfrontpage) {
        $templatecontext = array_merge($templatecontext, $themesettings->numbers());
    }

    if ($sponsorsfrontpage) {
        $templatecontext = array_merge($templatecontext, $themesettings->sponsors());
    }

    if ($clientsfrontpage) {
        $templatecontext = array_merge($templatecontext, $themesettings->clients());
    }

    echo $OUTPUT->render_from_template('theme_recit/frontpage_guest', $templatecontext);
}
