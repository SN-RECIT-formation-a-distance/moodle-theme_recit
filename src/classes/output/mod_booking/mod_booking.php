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
 * Overriden theme récit mod booking renderer.
 *
 * @copyright  RÉCIT 2019
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
//namespace mod_booking;
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . "/mod/booking/classes/output/renderer.php");
require_once($CFG->dirroot . "/theme/recit/classes/output/core_renderer.php");
require_once($CFG->dirroot . "/theme/recit/lib.php");
require_once("../../config.php");
require_once($CFG->dirroot . "/mod/booking/lib.php");
require_once($CFG->dirroot . "/mod/booking/classes/booking_option.php");
require_once($CFG->dirroot . "/mod/booking/externallib.php");

//use mod_booking\mod_booking as booking;
//use mod_booking\mod_booking as mod_booking;
/*$blockDiagTagQuestion = $CFG->dirroot . "/blocks/recitdiagtagquestion/block_recitdiagtagquestion.php";
define('BLOCK_DIAG_TAG_QUESTION_EXIST', file_exists($blockDiagTagQuestion));
if(BLOCK_DIAG_TAG_QUESTION_EXIST){
    require_once($blockDiagTagQuestion);
}*/
class theme_recit_mod_booking_option {
   // use mod_booking\mod_booking ;
    function mod_booking_replace() {
        global $CFG, $COURSE,$OUTPUT,$cm;
        $mod_booking_external = new mod_booking_external;
        $mod_booking_external->bookings()->settings->name;
        //  namespace mod_booking;
        //foreach ($bookings as $booking) {
          //$booking_option = new booking_option;
          //$booking = new booking($cm->id);
          //$page = array([]);
          
          //define('$page',0);
         // $PAGE->set_url('/mod/booking/index.php', array('id' => $id));
         //$page= $OUTPUT->set_heading(format_string($COURSE->fullname));
         echo $OUTPUT->heading(format_string( $mod_booking_external), 2);
         echo  "etienne";
          
         print_object ($COURSE);
        // print_object ($cm);
    //}
         return /*$page*/;
        
      }
}