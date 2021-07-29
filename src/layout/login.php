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
 * A login page layout for the recit theme.
 *
 * @package   theme_recit2
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$extraclasses[] = 'recit-login';
$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$templatecontext = [
    'bodyattributes' => $bodyattributes
];

$templatecontext = array_merge($templatecontext, ThemeRecitUtils2::get_template_context_common($OUTPUT, $PAGE, $USER));

echo $OUTPUT->render_from_template('theme_recit2/login', $templatecontext);
