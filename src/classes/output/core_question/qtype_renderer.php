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
 * Defines the renderer base classes for question types.
 *
 * @copyright  2019 RÉCIT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/question/type/rendererbase.php");
require_once($CFG->dirroot . "/question/type/truefalse/renderer.php");
/*
abstract class theme_recit_qtype_renderer extends qtype_renderer{
    /**
     * Generate the display of the outcome part of the question. This is the
     * area that contains the various forms of feedback. This function generates
     * the content of this area belonging to the question type.
     *
     * Subclasses will normally want to override the more specific methods
     * {specific_feedback()}, {general_feedback()} and {correct_response()}
     * that this method calls.
     *
     * @param question_attempt $qa the question attempt to display.
     * @param question_display_options $options controls what should and should not be displayed.
     * @return string HTML fragment.
     */
    /*public function feedback(question_attempt $qa, question_display_options $options) {
        $output = '';
        $hint = null;

        if ($options->feedback) {
            $output .= html_writer::nonempty_tag('div', $this->specific_feedback($qa),
                    array('class' => 'specificfeedback'));
            $hint = $qa->get_applicable_hint();
        }

        if ($options->numpartscorrect) {
            $output .= html_writer::nonempty_tag('div', $this->num_parts_correct($qa),
                    array('class' => 'numpartscorrect'));
        }

        if ($hint) {
            $output .= $this->hint($qa, $hint);
        }

        if ($options->generalfeedback) {
            $output .= html_writer::nonempty_tag('div', $this->general_feedback($qa),
                    array('class' => 'generalfeedback'));
        }

        if ($options->rightanswer) {
            $output .= html_writer::nonempty_tag('div', "<strong>".$this->correct_response($qa) . "</strong>",
                    array('class' => 'rightanswer'));
        }

        return $output;
    }
}

class theme_recit_qtype_truefalse_renderer extends qtype_truefalse_renderer {
   
}*/