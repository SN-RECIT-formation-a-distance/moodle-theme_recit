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
 * Overriden theme récit core renderer.
 *
 * @package    theme_recit2
 * @copyright  RÉCIT 2019
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_recit2\output;

use html_writer;
use custom_menu_item;
use custom_menu;
use action_menu_filler;
use action_menu_link_secondary;
use stdClass;
use moodle_url;
use action_menu;
use pix_icon;
use theme_config;
use core_text;
use renderer_base;
use theme_recit2\local\ThemeSettings;

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . '/behat/lib.php');

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_recit2
 * @copyright  RÉCIT 2019
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \core_renderer {
    /** @var custom_menu_item language The language menu if created */
    protected $language = null;
    
    /**
     * Constructor
     *
     * @param moodle_page $page the page we are doing output for.
     * @param string $target one of rendering target constants
     */
    public function __construct(\moodle_page $page, $target){
        global $USER;
        parent::__construct($page, $target);

        $this->page->requires->string_for_js('msgleavingmoodle', 'theme_recit2');
        
        if (isset($_GET['recitedit'])){//When user clicks on editing mode toggle
            $USER->editing = $_GET['recitedit'] == 'on' ? true : false;
        }
    }

    /**
     * Outputs the opening section of a box.
     *
     * @param string $classes A space-separated list of CSS classes
     * @param string $id An optional ID
     * @param array $attributes An array of other attributes to give the box.
     * @return string the HTML to output.
     */
    public function box_start($classes = '', $id = null, $attributes = array()) {
        global $PAGE;

        if(is_string($classes)){
            $classes = explode(" ", $classes);
        }

        if($PAGE->pagelayout == 'frontpage'){
            $classes[] = 'activity-content';
        }
        else if(in_array('quizinfo', $classes)){
            $classes[] = 'alert alert-secondary';
        }
                        
        $classes = implode(" ", $classes);
        return parent::box_start($classes, $id, $attributes);
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        global $PAGE, $SITE, $USER, $COURSE, $OUTPUT, $CFG;

        $theme = theme_config::load(ThemeSettings::get_theme_name());

        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->showpageheadingbutton = ($this->page->cm != null && in_array($this->page->cm->modname, array('wiki')));
        $header->headeractions = $this->page->get_header_actions();
        $header->coursebanner = $this->get_course_custom_banner();
        $header->layoutOptions = (object) $PAGE->layout_options;
        $header->isloggedin = isloggedin();
        $header->isediting = $USER->editing;
        $header->isguest = $USER->id == 1;
        $header->incourse = $COURSE->id > 1;
        if ($header->incourse){
            $courseContext = \context_course::instance($COURSE->id);
            $header->isenrolled = is_enrolled($courseContext, $USER) || strstr($_SERVER['REQUEST_URI'], 'enrol');
            $header->canenrol = (is_enrolled($courseContext) === false) && ($USER->id > 1) && (!has_capability('moodle/course:update', $courseContext));
            $header->course_id = $COURSE->id;
            $header->course_name = $COURSE->fullname;
            $header->course_url = sprintf("%s/course/view.php?id=%d", $CFG->wwwroot, $COURSE->id);
            $header->breadcrumb = (ThemeSettings::get_custom_field('enablebreadcrumb') == 1 ? $this->render_from_template('core/navbar', $this->page->navbar) : null);
        }else{
            $header->breadcrumb = ((isset($theme->settings->enablebreadcrumb) && $theme->settings->enablebreadcrumb == 1) ? $this->render_from_template('core/navbar', $this->page->navbar) : null);
        }

        $header->siteSummary = (isset($header->layoutOptions->showSiteSummary) && $header->layoutOptions->showSiteSummary ? $SITE->summary : null);

        $themesettings = new ThemeSettings();
        $header->slider = $themesettings->slideshow($theme);
        
        //js_reset_all_caches();

        return $this->render_from_template('theme_recit2/recit/header', $header);
    }

    public function get_course_custom_banner(){
        global $COURSE, $OUTPUT;
        $theme = theme_config::load(ThemeSettings::get_theme_name());
        if ($COURSE->id == 1){//Homepage, load headerimg 

            $headerimg = $theme->setting_file_url('headerimg', 'headerimg');

            if (is_null($headerimg)) {
                $headerimg = $OUTPUT->image_url('notconnected', 'theme');
            }
            return "background-image: url('$headerimg'); background-position: center;";
        }else{

            $img_course_as_banner = ThemeSettings::get_custom_field('img_course_as_banner');

            if($img_course_as_banner == "1"){
                $courseImage = \core_course\external\course_summary_exporter::get_course_image($COURSE);

                if($courseImage){
                    return "background-image: url('$courseImage'); background-position: center;";
                }
            }
            $courseimgtheme = $theme->setting_file_url('coursebanner', 'coursebanner');
            if ($courseimgtheme){
                return "background-image: url('$courseimgtheme'); background-position: center;";                  
            }
        }
    
        return "";
    }    

     /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {
        global $PAGE, $CFG;
        $output = parent::standard_head_html();

        // Add google analytics code.
        $googleanalyticscode = "<script>
                                    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};
                                    ga.l=+new Date;ga('create', 'GOOGLE-ANALYTICS-CODE', 'auto');
                                    ga('send', 'pageview');
                                </script>
                                <script async src='https://www.google-analytics.com/analytics.js'></script>";

        $theme = theme_config::load(ThemeSettings::get_theme_name());

        if (!empty($theme->settings->googleanalytics)) {
            $output .= str_replace("GOOGLE-ANALYTICS-CODE", trim($theme->settings->googleanalytics), $googleanalyticscode);
        }

        
        $settings = array(
            'showleavingsitewarning' => (isset($theme->settings->showleavingsitewarning) && $theme->settings->showleavingsitewarning),
            'enablebs4warning' => (isset($theme->settings->enablebs4warning) && $theme->settings->enablebs4warning),
            'moodleversion' => $CFG->version
        );
        // force le chargement du fichier js du thème de base RÉCIT
        $PAGE->requires->js('/theme/recit2/js/theme-recit2-navsection.js');
        $PAGE->requires->js('/theme/recit2/js/theme-recit2.js');
        $PAGE->requires->js('/theme/recit2/js/theme-legacy.js');
        $PAGE->requires->js('/theme/recit2/js/theme-recit2-init-vars.js');
        $PAGE->requires->js_init_call('theme_recit2_init_vars', array($settings));


        return $output;
    }

    /**
     * Renders the custom menu
     *
     * @param custom_menu $menu
     * @return mixed
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG;
        if (!$menu->has_children()) {
            return '';
        }
        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }
        return $content;
    }  

    /**
     * Gets the logo to be rendered.
     *
     * The priority of get log is: 1st try to get the theme logo, 2st try to get the theme logo
     * If no logo was found return false
     *
     * @return mixed
     */
    public function get_logo() {
        if ($this->should_display_theme_logo()) {
            return $this->get_theme_logo_url();
        }

        $url = $this->get_logo_url();
        if ($url) {
            return $url->out(false);
        }

        return false;
    }

    /**
     * Outputs the pix url base
     *
     * @return string an URL.
     */
    public function get_pix_image_url_base() {
        global $CFG;

        return $CFG->wwwroot . "/theme/recit2/pix";
    }

    /**
     * Whether we should display the main theme logo in the navbar.
     *
     * @return bool
     */
    public function should_display_theme_logo() {
        $logo = $this->get_theme_logo_url();

        return !empty($logo);
    }

    /**
     * Outputs the favicon urlbase.
     *
     * @return string an url
     */
    public function favicon() {
        global $OUTPUT;

        $theme = theme_config::load(ThemeSettings::get_theme_name());

        $favicon = $theme->setting_file_url('favicon', 'favicon');

        if (!empty(($favicon))) {
            return $favicon;
        }

        return parent::favicon();
    }

    /**
     * Get the main logo URL.
     *
     * @return string
     */
    public function get_theme_logo_url() {
        $theme = theme_config::load(ThemeSettings::get_theme_name());

        return $theme->setting_file_url('logo', 'logo');
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = '';
            if (!$loginpage) {
                $returnstr .= "<a class='btn btn-login-top d-lg-none' href=\"$loginurl\">" . get_string('login') . '</a>';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }

        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = '';

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;

            // Adds username to the first item of usermanu.
            $userinfo = new stdClass();
            $userinfo->itemtype = 'text';
            $userinfo->title = $user->firstname . ' ' . $user->lastname;
            $userinfo->url = new moodle_url('/user/profile.php', array('id' => $user->id));
            $userinfo->pix = 'i/user';

            array_unshift($opts->navitems, $userinfo);

            foreach ($opts->navitems as $key => $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'text':
                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall')),
                            $value->title,
                            array('class' => 'text-username')
                        );

                        $am->add($al);
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }

                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::tag(
            'li',
            $this->render($am),
            array('class' => $usermenuclasses)
        );
    }

    /**
     * Try to return the first image on course summary files, otherwise returns a default image.
     *
     * @return string HTML fragment.
     */
    public function courseheaderimage() {
        global $CFG, $COURSE, $DB;

        $course = $DB->get_record('course', ['id' => $COURSE->id]);

        require_once($CFG->libdir. '/coursecatlib.php');

        $course = new \course_in_list($course);

        $courseimage = '';
        $imageindex = 1;
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();

            $url = new moodle_url("$CFG->wwwroot/pluginfile.php" . '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), ['forcedownload' => !$isimage]);

            if ($isimage) {
                $courseimage = $url;
            }

            if ($imageindex == 2) {
                break;
            }

            $imageindex++;
        }

        if (empty($courseimage)) {
            $courseimage = $this->get_pix_image_url_base() . "/default_coursesummary.jpg";
        }

        // Create html for header.
        $html = html_writer::start_div('headerbkg');

        $html .= html_writer::start_div('withimage', array(
            'style' => 'background-image: url("' . $courseimage . '"); background-size: cover; background-position:center;
            width: 100%; height: 100%;'
        ));
        $html .= html_writer::end_div(); // End withimage inline style div.

        $html .= html_writer::end_div();

        return $html;
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function mydashboard_admin_header() {
        global $PAGE;

        $html = html_writer::start_div();
        $html .= html_writer::start_div('col-xs-12 p-a-1');

        $pageheadingbutton = $this->page_heading_button();
        if (empty($PAGE->layout_options['nonavbar'])) {
            $html .= html_writer::start_div('clearfix w-100 pull-xs-left', array('id' => 'page-navbar'));
           // $html .= html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav'));
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button');
            $html .= html_writer::end_div();
        } else if ($pageheadingbutton) {
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button nonavbar pull-xs-right m-r-1');
        }

        $html .= html_writer::end_div(); // End .row.
        $html .= html_writer::end_div(); // End .col-xs-12.

        return $html;
    }

    /**
     * The standard tags (typically performance information and validation links,
     * if we are in developer debug mode) that should be output in the footer area
     * of the page. Designed to be called in theme layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_footer_html() {
        global $PAGE;

        if($PAGE->__get('pagelayout') == 'popup'){
            return null;
        }

        $output = null;

        //if (debugging(null, DEBUG_DEVELOPER) and has_capability('moodle/site:config', \context_system::instance())) {  // Only in developer mode
            $output = parent::standard_footer_html();
        //}

        return $output;
    }

    public function heading($text, $level = 2, $classes = null, $id = null) {
        global $PAGE, $OUTPUT;

        if($PAGE->__get('pagelayout') == 'popup'){
            return null;
        }

        $level = (integer) $level;
        $output = "";
        if ($level < 1 or $level > 6) {
            throw new coding_exception('Heading level must be an integer between 1 and 6.');
        }
        else{
            $output =  html_writer::tag('h' . $level, $text, array('id' => $id, 'class' =>  renderer_base::prepare_classes($classes)));
        }

        return $output;
    }
    /**
     * See if this is the first view of the current cm in the session if it has fake blocks.
     *
     * (We track up to 100 cms so as not to overflow the session.)
     * This is done for drawer regions containing fake blocks so we can show blocks automatically.
     *
     * @return boolean true if the page has fakeblocks and this is the first visit.
     */
    public function firstview_fakeblocks(): bool {
        global $SESSION;

        $firstview = false;
        if ($this->page->cm) {
            if (!$this->page->blocks->region_has_fakeblocks('side-pre')) {
                return false;
            }
            if (!property_exists($SESSION, 'firstview_fakeblocks')) {
                $SESSION->firstview_fakeblocks = [];
            }
            if (array_key_exists($this->page->cm->id, $SESSION->firstview_fakeblocks)) {
                $firstview = false;
            } else {
                $SESSION->firstview_fakeblocks[$this->page->cm->id] = true;
                $firstview = true;
                if (count($SESSION->firstview_fakeblocks) > 100) {
                    array_shift($SESSION->firstview_fakeblocks);
                }
            }
        }
        return $firstview;
    }
}

class core_renderer_cli extends core_renderer {
     /**
     * Returns a template fragment representing a Heading.
     *
     * @param string $text The text of the heading
     * @param int $level The level of importance of the heading
     * @param string $classes A space-separated list of CSS classes
     * @param string $id An optional ID
     * @return string A template fragment for a heading
     */
    public function heading($text, $level = 2, $classes = 'main', $id = null) {
        $text .= "\n";
        switch ($level) {
            case 1:
                return '=>' . $text;
            case 2:
                return '-->' . $text;
            default:
                return $text;
        }
    }
}
class core_renderer_ajax extends core_renderer {
    /**
    * No need for headers in an AJAX request... this should never happen.
    * @param string $text
    * @param int $level
    * @param string $classes
    * @param string $id
    */
   public function heading($text, $level = 2, $classes = 'main', $id = null) {}
   }



        
        