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
 * Course renderer.
 *
 * @package    theme_recit2
 * @copyright  2017 Willian Mano - conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_recit2\output\core;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/badgeslib.php');

use moodle_url;
use html_writer;
use coursecat;
use coursecat_helper;
use stdClass;
use core_course_list_element;

/**
 * Renderers to align recit2's course elements to what is expect
 *
 * @package    theme_recit2
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {

    private $listview = false;
    private $maxInstructors = 5;
 /**
     * Renders html to display a course search form.
     *
     * @param string $value default value to populate the search field
     * @param string $format display format - 'plain' (default), 'short' or 'navbar'
     * @return string
     */
    public function course_search_form($value = '', $format = 'plain') {
        static $count = 0;
        $formid = 'coursesearch';
        if ((++$count) > 1) {
            $formid .= $count;
        }
        
        $view = optional_param('view', null, PARAM_ALPHA);
        $isTileView = false;
        if (!$view || $view == 'tile') $isTileView = true;

        switch ($format) {
            case 'navbar' :
                $formid = 'coursesearchnavbar';
                $inputid = 'navsearchbox';
                $inputsize = 20;
                break;
            case 'short' :
                $inputid = 'shortsearchbox';
                $inputsize = 12;
                break;
            default :
                $inputid = 'coursesearchbox';
                $inputsize = 30;
        }

        $data = (object) [
            'searchurl' => (new moodle_url('/course/search.php'))->out(false),
            'id' => $formid,
            'inputid' => $inputid,
            'inputsize' => $inputsize,
            'value' => $value,
            'isTileView' => $isTileView
        ];

        return $this->render_from_template('theme_recit2/course_search_form', $data);
    }

    /**
     * Renders the list of courses
     *
     * This is internal function, please use {@link core_course_renderer::courses_list()} or another public
     * method from outside of the class
     *
     * If list of courses is specified in $courses; the argument $chelper is only used
     * to retrieve display options and attributes, only methods get_show_courses(),
     * get_courses_display_option() and get_and_erase_attributes() are called.
     *
     * @param coursecat_helper $chelper various display options
     * @param array $courses the list of courses to display
     * @param int|null $totalcount total number of courses (affects display mode if it is AUTO or pagination if applicable),
     *     defaulted to count($courses)
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $_totalcount = null) {
        global $CFG;


        if ($_totalcount === null) {
            $totalcount = count($courses);
        }else{
            $totalcount = $_totalcount;
        }

        if ($CFG->courseswithsummarieslimit < $totalcount){
            $this->listview = true;
            return parent::coursecat_courses($chelper, $courses, $_totalcount);
        }
        

        if (!$totalcount) {
            // Courses count is cached during courses retrieval.
            return '';
        }

        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO) {
            // In 'auto' course display mode we analyse if number of courses is more or less than $CFG->courseswithsummarieslimit.
            if ($totalcount <= $CFG->courseswithsummarieslimit) {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
            } else {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_COLLAPSED);
            }
        }

        // Prepare content of paging bar if it is needed.
        $paginationurl = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        if ($totalcount > count($courses)) {
            // There are more results that can fit on one page.
            if ($paginationurl) {
                // The option paginationurl was specified, display pagingbar.
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_courses_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                        $paginationurl->out(false, array('perpage' => $perpage)));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => 'all')),
                            get_string('showall', '', $totalcount)), array('class' => 'paging paging-showall'));
                }
            } else if ($viewmoreurl = $chelper->get_courses_display_option('viewmoreurl')) {
                // The option for 'View more' link was specified, display more link.
                $viewmoretext = $chelper->get_courses_display_option('viewmoretext', new \lang_string('viewmore'));
                $morelink = html_writer::tag('div', html_writer::link($viewmoreurl, $viewmoretext),
                        array('class' => 'paging paging-morelink'));
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            // There are more than one page of results and we are in 'view all' mode, suggest to go back to paginated view mode.
            $pagingbar = html_writer::tag(
                'div',
                html_writer::link(
                    $paginationurl->out(
                        false,
                        array('perpage' => $CFG->coursesperpage)
                    ),
                    get_string('showperpage', '', $CFG->coursesperpage)
                ),
                array('class' => 'paging paging-showperpage')
            );
        }

        // Display list of courses.
        $attributes = $chelper->get_and_erase_attributes('courses');
        $content = html_writer::start_tag('div', $attributes);

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        $coursecount = 1;
        $content .= html_writer::start_tag('div', array('class' => 'recit-course-list'));
        foreach ($courses as $course) {
            $content .= $this->coursecat_coursebox($chelper, $course);

            /*if ($coursecount % 4 == 0) {
                $content .= html_writer::end_tag('div');
                $content .= html_writer::start_tag('div', array('class' => 'recit-course-list'));
            }*/

            $coursecount ++;
        }

        $content .= html_writer::end_tag('div');

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div'); // End courses.
        return $content;
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function, to display an information about just one course
     * please use {@link core_course_renderer::course_info_box()}
     *
     * @param coursecat_helper $chelper various display options
     * @param course_in_list|stdClass $course
     * @param string $additionalclasses additional classes to add to the main <div> tag (usually
     *    depend on the course position in list - first/last/even/odd)
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $CFG;

        if ($this->listview) {
            return parent::coursecat_coursebox($chelper, $course, $additionalclasses);
        }

        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
           // require_once($CFG->libdir. '/coursecatlib.php');
            $course = new core_course_list_element($course);
        }

        $classes = trim('card');
        if ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $nametag = 'h3';
        } else {
            $classes .= ' collapsed';
            $nametag = 'div';
        }

        // End coursebox.
        $content = html_writer::start_tag('div', array(
            'class' => $classes,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
        ));

        $content .= $this->coursecat_coursebox_content($chelper, $course);

        $content .= html_writer::end_tag('div'); // End coursebox.

        return $content;
    }

    /**
     * Returns HTML to display course content (summary, course contacts and optionally category name)
     *
     * This method is called from coursecat_coursebox() and may be re-used in AJAX
     *
     * @param coursecat_helper $chelper various display options
     * @param stdClass|course_in_list $course
     * @return string
     */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        global $CFG, $DB;

        if ($this->listview) {
            return parent::coursecat_coursebox_content($chelper, $course);
        }
        

        if ($course instanceof stdClass) {
            if (file_exists($CFG->libdir. '/coursecatlib.php')) require_once($CFG->libdir. '/coursecatlib.php');
            $course = new core_course_list_element($course);
        }

        $data = new stdClass();

        // Course name.
        $data->coursename = $chelper->get_course_formatted_name($course);
        $data->courselink = new moodle_url('/course/view.php', array('id' => $course->id));

        $data->courseimage = $this->get_course_summary_image($course, $data->courselink);

        // Course instructors.
        if ($course->has_course_contacts()) {
            $data->coursecontacts = array();
            $data->hascontacts = true;

            $instructors = $course->get_course_contacts();
            $countInstructors = 0;
            foreach ($instructors as $key => $instructor) {
                $name = $instructor['username'];
                $url = $CFG->wwwroot.'/user/profile.php?id='.$key;
                $picture = $this->get_user_picture($DB->get_record('user', array('id' => $key)));
                $data->coursecontacts[] = array('name' => $name, 'url' => $url, 'picture' => $picture);

                $countInstructors++;

                if($countInstructors >= $this->maxInstructors){
                    $data->coursecontactsnum = count($instructors)-$countInstructors;
                    break;
                }
            }

        }

        // Display course summary.
        if ($course->has_summary()) {
            $data->coursesummary = $chelper->get_course_formatted_summary($course, array('overflowdiv' => true, 'noclean' => true, 'para' => false));
        }


        // Print enrolmenticons.
        if ($icons = enrol_get_course_info_icons($course)) {
            $data->courseicons = array();
            foreach ($icons as $pixicon) {
                $data->courseicons[] = $this->render($pixicon);
            }
        }
        
        $badges = \badges_get_badges(BADGE_TYPE_COURSE, $course->id, '', '', 0, 0, 0);
        $data->badges = array();
        $bcount = 0;
        foreach ($badges as $badge){
            if ($badge->status == BADGE_STATUS_ACTIVE || $badge->status == BADGE_STATUS_ACTIVE_LOCKED){
                $data->badges[] = array("name" => $badge->name, "image" => print_badge_image($badge, $badge->get_context()));
                $bcount++;
                if ($bcount > $this->maxInstructors){
                    break;
                }
            }
        }

        $data->tags = array();
        $course_tags = \core_tag_tag::get_item_tags_array('core', 'course', $course->id);
        foreach ($course_tags as $tag){
            $data->tags[] = array("url" => $CFG->wwwroot."/tag/index.php?tag=" .urlencode($tag), "name" => $tag);
        }

        // Display course category if necessary (for example in search results).
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_EXPANDED_WITH_CAT){
            if ($cat = \core_course_category::get($course->category, IGNORE_MISSING)) {
                $data->caturl = get_string('category').': '. html_writer::link(new moodle_url('/course/index.php', array('categoryid' => $cat->id)), $cat->get_formatted_name(), array('class' => $cat->visible ? '' : 'dimmed'));
            }
        }

        return $this->render_from_template('theme_recit2/recit/courselistcontent', $data);
    }

    
    /**
     * Returns HTML to display course contacts.
     *
     * @param core_course_list_element $course
     * @return string
     */
    protected function course_contacts(core_course_list_element $course) {
        $content = '';
        if ($course->has_course_contacts()) {
            $content .= html_writer::start_tag('ul', ['class' => 'teachers']);
            $countInstructors = 0;
            $instructors = $course->get_course_contacts();
            foreach ($instructors as $coursecontact) {
                $rolenames = array_map(function ($role) {
                    return $role->displayname;
                }, $coursecontact['roles']);
                $name = implode(", ", $rolenames).': '.
                    html_writer::link(new moodle_url('/user/view.php',
                        ['id' => $coursecontact['user']->id, 'course' => SITEID]),
                        $coursecontact['username']);
                $content .= html_writer::tag('li', $name);
                $countInstructors++;

                if($countInstructors >= $this->maxInstructors){
                    $content .= sprintf("<span class='badge badge-warning p-2'>+%d</span>", count($instructors)-$countInstructors);
                    break;
                }
            }
            $content .= html_writer::end_tag('ul');
        }
        return $content;
    }

    /**
     * Returns the first course's summary issue
     *
     * @param stdClass $course the course object
     * @return string
     */
    protected function get_course_summary_image($course, $courselink) {
        global $CFG;

        $contentimage = '';

        if ($this->listview) return $contentimage;

        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {
                $contentimage = html_writer::link($courselink, html_writer::empty_tag('img', array(
                    'src' => $url,
                    'alt' => $course->fullname,
                    'class' => 'recit-card-img-top')));
                break;
            }
        }

        if (empty($contentimage)) {
            $url = $CFG->wwwroot . "/theme/recit2/pix/default_course.jpg";

            $contentimage = html_writer::link($courselink, html_writer::empty_tag('img', array(
                'src' => $url,
                'alt' => $course->fullname,
                'class' => 'recit-card-img-top')));
        }

        return $contentimage;
    }

    /**
     * Get the user profile pic
     *
     * @param null $userobject
     * @param int $imgsize
     * @return moodle_url
     * @throws \coding_exception
     */
    protected function get_user_picture($userobject = null, $imgsize = 100) {
        global $USER, $PAGE;

        if (!$userobject) {
            $userobject = $USER;
        }

        $userimg = new \user_picture($userobject);

        $userimg->size = $imgsize;

        return $userimg->get_url($PAGE);
    }
    

    /**
     * Outputs contents for frontpage as configured in $CFG->frontpage or $CFG->frontpageloggedin
     *
     * @return string
     */
    public function frontpage() {
        global $CFG, $SITE;

        $output = '';
        $output .= $this->featuredcourses();
        $output .= parent::frontpage();

        return $output;
    }
    
    protected function featuredcourses(){
        $output = '';
        $theme = \theme_config::load('recit2');
        $courses = isset($theme->settings->featuredcourses) ? $theme->settings->featuredcourses : '';

        if (empty($courses)) {
            return $output;
        }

        $courses = explode(',', $courses);
        $chelper = new \coursecat_helper();

        $output .= "<h2>".get_string('featuredcourses', 'theme_recit2')."</h2>";
        $output .= "<div class='recit-course-list courses'>";

        foreach ($courses as $c) {
            if (is_numeric($c)){
                $course = new stdClass();
                $course->id = $c;
                $output .= $this->coursecat_coursebox($chelper, $course);
            }
        }
        $output .= "</div>";

        return $output;
    }

}
