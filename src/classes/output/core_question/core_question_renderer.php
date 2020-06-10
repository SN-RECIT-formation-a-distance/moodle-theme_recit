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
 * Renderers for outputting parts of the question engine.
 *
 * @copyright  2019 RÉCIT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/question/engine/renderer.php");

/**
 * @copyright  2019 RÉCIT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_recit_core_question_renderer extends core_question_renderer {
     /**
     * Generate the display of a question in a particular state, and with certain
     * display options. Normally you do not call this method directly. Intsead
     * you call {@link question_usage_by_activity::render_question()} which will
     * call this method with appropriate arguments.
     *
     * @param question_attempt $qa the question attempt to display.
     * @param qbehaviour_renderer $behaviouroutput the renderer to output the behaviour
     *      specific parts.
     * @param qtype_renderer $qtoutput the renderer to output the question type
     *      specific parts.
     * @param question_display_options $options controls what should and should not be displayed.
     * @param string|null $number The question number to display. 'i' is a special
     *      value that gets displayed as Information. Null means no number is displayed.
     * @return string HTML representation of the question.
     */
    public function question(question_attempt $qa, qbehaviour_renderer $behaviouroutput,
            qtype_renderer $qtoutput, question_display_options $options, $number) {

        $output = '';
        $questionState = $qa->get_state_class($options->correctness && $qa->has_marks());
        $classes = array($qa->get_question()->qtype->name(), $qa->get_behaviour_name(), $questionState);
        
        /*if($questionState != 'notyetanswered'){
            $classes[] = 'card';
        }*/
        $classes[] = 'card';

        $classes = implode(' ', $classes);

        $output .= html_writer::start_tag('div', array(
            'id' => $this->bypass_get_outer_question_div_unique_id($qa), //$qa->get_outer_question_div_unique_id(),
            'class' => $classes,
            'style' => 'margin-bottom: 1rem')
        );

        //$this->info($qa, $behaviouroutput, $qtoutput, $options, $number),
        $title = sprintf("%s%s%s%s%s", $this->number($number), $this->status($qa, $behaviouroutput, $options), 
            $this->mark_summary($qa, $behaviouroutput, $options), $this->question_flag($qa, $options->flags), $this->edit_question_link($qa, $options));
        $output .= html_writer::tag('div', $title, array('class' => 'card-header', 'style' => 'display: flex; justify-content: space-between; align-items: center;'));

        $output .= html_writer::start_tag('div', array('class' => 'card-body'));

        $output .= html_writer::tag('div',
                $this->add_part_heading($qtoutput->formulation_heading(),
                    $this->formulation($qa, $behaviouroutput, $qtoutput, $options)),
                array('class' => 'formulation clearfix'));
        $output .= html_writer::nonempty_tag('div',
                $this->add_part_heading(get_string('feedback', 'question'),
                    $this->outcome($qa, $behaviouroutput, $qtoutput, $options)),
                array('class' => 'outcome clearfix'));
        $output .= html_writer::nonempty_tag('div',
                $this->add_part_heading(get_string('comments', 'question'),
                    $this->manual_comment($qa, $behaviouroutput, $qtoutput, $options)),
                array('class' => 'comment clearfix'));
        $output .= html_writer::nonempty_tag('div',
                $this->response_history($qa, $behaviouroutput, $qtoutput, $options),
                array('class' => 'history clearfix'));

        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('div');
        return $output;
    }

    /**
     * When the question is rendered, this unique id is added to the
     * outer div of the question. It can be used to uniquely reference
     * the question from JavaScript.
     *
     * Note, this is not truly unique. It will be changed in Moodle 3.7. See MDL-65029.
     *
     * @return string id added to the outer <div class="que ..."> when the question is rendered.
     */
    public function bypass_get_outer_question_div_unique_id(question_attempt $qa) {
        return 'q' . $qa->get_slot();
    }

    /**
     * Generate the information bit of the question display that contains the
     * metadata like the question number, current state, and mark.
     * @param question_attempt $qa the question attempt to display.
     * @param qbehaviour_renderer $behaviouroutput the renderer to output the behaviour
     *      specific parts.
     * @param qtype_renderer $qtoutput the renderer to output the question type
     *      specific parts.
     * @param question_display_options $options controls what should and should not be displayed.
     * @param string|null $number The question number to display. 'i' is a special
     *      value that gets displayed as Information. Null means no number is displayed.
     * @return HTML fragment.
     */
    /*protected function info(question_attempt $qa, qbehaviour_renderer $behaviouroutput,
            qtype_renderer $qtoutput, question_display_options $options, $number) {
        $output = '';
        $output .= $this->number($number);
        $output .= $this->status($qa, $behaviouroutput, $options);
        $output .= $this->mark_summary($qa, $behaviouroutput, $options);
        $output .= $this->question_flag($qa, $options->flags);
        $output .= $this->edit_question_link($qa, $options);
        return $output;
    }*/

     /**
     * Generate the display of the question number.
     * @param string|null $number The question number to display. 'i' is a special
     *      value that gets displayed as Information. Null means no number is displayed.
     * @return HTML fragment.
     */
    protected function number($number) {
        if (trim($number) === '') {
            return '';
        }
        $numbertext = '';
        if (trim($number) === 'i') {
            $numbertext = get_string('information', 'question');
        } else {
            $numbertext = get_string('questionx', 'question', $number);
        }
        return $numbertext;
    }

    protected function edit_question_link(question_attempt $qa,
            question_display_options $options) {
        global $CFG;

        if (empty($options->editquestionparams)) {
            return '';
        }

        $params = $options->editquestionparams;
        if ($params['returnurl'] instanceof moodle_url) {
            $params['returnurl'] = $params['returnurl']->out_as_local_url(false);
        }
        $params['id'] = $qa->get_question()->id;
        $editurl = new moodle_url('/question/question.php', $params);

        return html_writer::tag('div', html_writer::link(
                $editurl, $this->pix_icon('t/edit', get_string('edit'), '', array('class' => 'iconsmall')) .
                get_string('editquestion', 'question')));
    }

    /**
     * Generate the display of the outcome part of the question. This is the
     * area that contains the various forms of feedback.
     *
     * @param question_attempt $qa the question attempt to display.
     * @param qbehaviour_renderer $behaviouroutput the renderer to output the behaviour
     *      specific parts.
     * @param qtype_renderer $qtoutput the renderer to output the question type
     *      specific parts.
     * @param question_display_options $options controls what should and should not be displayed.
     * @return HTML fragment.
     */
    protected function outcome(question_attempt $qa, qbehaviour_renderer $behaviouroutput,
            qtype_renderer $qtoutput, question_display_options $options) {

        $answer = $qa->get_state_class($options->correctness && $qa->has_marks());
        $alertVariant = "";
        if($answer == 'correct'){
            $alertVariant = "alert-success";
        }
        else if($answer == "partiallycorrect"){
            $alertVariant = "alert-warning";
        }
        else{
            $alertVariant = "alert-danger";
        }

        $output = '';
        $output .= html_writer::nonempty_tag('div',
                $qtoutput->feedback($qa, $options), array('class' => "mt-2 alert $alertVariant"));
        $output .= html_writer::nonempty_tag('div',
                $behaviouroutput->feedback($qa, $options), array('class' => 'im-feedback'));
        $output .= html_writer::nonempty_tag('div',
                $options->extrainfocontent, array('class' => 'extra-feedback'));
        return $output;
    }
}