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
 * Recit config.
 *
 * @package   theme_recit2
 *  2017 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

$THEME->name = 'recit2';
//$THEME->sheets[] = "bootstrap";
//$THEME->sheets[] = "moodle-base";
//$THEME->sheets[] = "moodle-base-3-9-2";
//$THEME->sheets[] = "recit";
$THEME->editor_sheets = [];
$THEME->parents = [];
$THEME->enable_dock = false;
$THEME->yuicssmodules = array();
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_DEFAULT;
$THEME->scss = function($theme) {
    return theme_recit2_get_main_scss_content($theme);
};
//$THEME->csstreepostprocessor = 'theme_recit2_css_tree_post_processor';
$THEME->extrascsscallback = 'theme_recit2_get_extra_scss';
//$THEME->prescsscallback = 'theme_recit2_get_pre_scss';
//$THEME->prescsscallback = 'theme_recit2_get_pre_scss';
//$THEME->usefallback = true;
// Add a custom icon system to the theme.
//$THEME->iconsystem = '\\theme_recit2\\output\\icon_system_fontawesome';
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->layouts = [
    // Most backwards compatible layout without the blocks - this is the layout used by default.
    'base' => array(
        'file' => 'columns2.php',
        'regions' => array(),
        'options' => array('showCourseBanner' => true, 'showBreadcrumb' => true, 'showSectionTopNav' => false),
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre','side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('showSectionTopNav' => false),
    ),
    // Course page.
    'course' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre','side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('showCourseBanner' => true, 'showBreadcrumb' => true, 'showSectionBottomNav' => true, 'showSectionTopNav' => true),
    ),
    'coursecategory' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('showCourseBanner' => false, 'showBreadcrumb' => true, 'showSectionTopNav' => true),
    ),
    // Internal course modules page.
    'incourse' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre','side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('showCourseBanner' => true, 'showBreadcrumb' => true, 'showSectionBottomNav' => true, 'showSectionTopNav' => true),
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('showCourseBanner' => false, 'showSectionTopNav' => false),
    ),
    // Server administration scripts.
    'admin' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
		'options' => array('showBreadcrumb' => true, 'showSectionTopNav' => false),
    ),
    // My dashboard page.
    'mydashboard' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('showCourseBanner' => false, 'showSectionTopNav' => false),
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array(),
        'options' => array(),
    ),
	// Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array(),
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array(),
    ),
	 // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => array(
        'file' => 'embedded.php',
        'regions' => array()
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
    // Please be extremely careful if you are modifying this layout.
    'maintenance' => array(
        'file' => 'maintenance.php',
        'regions' => array(),
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array(),
    ),
    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'file' => 'secure.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    )
];

$THEME->rarrow = "";
$THEME->larrow = "";
$THEME->uarrow = "";
$THEME->darrow = "";
//$THEME->enablecourseajax = false;