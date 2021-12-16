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
 * A one column layout for the recit theme.
 *
 * @package   theme_recit2
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use theme_recit2\local\CtrlLayout;

$bodyattributes = $OUTPUT->body_attributes([]);

$templatecontext = [
    'page' => $PAGE,
    'bodyattributes' => $bodyattributes
];

$templatecontext = array_merge($templatecontext, CtrlLayout::get_template_context_common($OUTPUT, $PAGE, $USER));

if($PAGE->__get('pagelayout') == 'popup'){
    if ($PAGE->cm) $PAGE->set_title($PAGE->cm->name);
    echo $OUTPUT->render_from_template('theme_recit2/recit/popup', $templatecontext);
}
else{
    echo $OUTPUT->render_from_template('theme_recit2/recit/columns1', $templatecontext);
}