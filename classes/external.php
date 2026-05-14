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
 * APLCore external API
 *
 * @package     local_aplcore
 * @author      Valery Fremaux valery.fremaux@gmail.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @copyright   2020 Valery Fremaux (https://www.activeprolearn.com)
 */

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
use core_external\external_warnings;
use local_aplcore\course\selector\course_selector_base;

/**
 * Auth external functions
 *
 * @package    local_aplcore
 * @author      Valery Fremaux valery.fremaux@gmail.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @copyright   2020 Valery Fremaux (https://www.activeprolearn.com)
 */
class local_aplcore_external extends external_api {
    /**
     * Describes the parameters for confirm_user.
     *
     * @return external_function_parameters
     */
    public static function coursesearch_parameters() {
        return new external_function_parameters(
            [
                'selectorid' => new external_value(PARAM_ALPHANUM, 'Selectorid hash'),
                'search' => new external_value(PARAM_TEXT, 'Search string'),
                'searchanywhere' => new external_value(PARAM_BOOL, 'Search option'),
            ]
        );
    }

    /**
     * search courses.
     *
     * @param  string $selectorid Id of the search selector
     * @param  string $search the search string
     * @param  string $searchanywhere Search option
     * @return array warnings and success status (true if the user was confirmed, false if he was already confirmed)
     * @throws moodle_exception
     */
    public static function coursesearch($selectorid, $search, $searchanywhere) {
        global $USER, $CFG, $PAGE;

        self::validate_parameters(
            self::coursesearch_parameters(),
            [
                'selectorid' => $selectorid,
                'search' => $search,
                'searchanywhere' => $searchanywhere,
            ]
        );

        // Get the search parameter.

        $PAGE->set_context(context_system::instance());

        // Get and validate the selectorid parameter.
        if (!isset($USER->courseselectors[$selectorid])) {
            throw new moodle_exception('unknowncourseselector');
        }

        // Get the options.
        $options = $USER->courseselectors[$selectorid];

        // Create the appropriate courseselector.
        $classname = $options['class'];
        unset($options['class']);
        $name = $options['name'];
        unset($options['name']);
        if (isset($options['file'])) {
            require_once($CFG->dirroot . '/' . $options['file']);
            unset($options['file']);
        }
        $courseselector = new $classname($name, $options);

        // Do the search and output the results.
        $results = $courseselector->find_courses($search);

        $json = [];
        foreach ($results as $groupname => $courses) {
            $groupdata = ['name' => $groupname, 'courses' => []];
            foreach ($courses as $course) {
                $output = new stdClass();
                $output->id = $course->id;
                $output->name = $courseselector->output_course($course);
                if (!empty($course->disabled)) {
                    $output->disabled = true;
                }
                if (!empty($course->infobelow)) {
                    $output->infobelow = $course->infobelow;
                }
                $groupdata['courses'][] = $output;
            }
            $json[] = $groupdata;
        }

        return ['results' => $json];
    }

    /**
     * Describes the coursesearch return value.
     *
     * @return external_single_structure
     */
    public static function coursesearch_returns() {

        return new external_single_structure(
            [
                'results' => new external_value(PARAM_TEXT, 'A json encoded set of course results'),
            ]
        );
    }
}
