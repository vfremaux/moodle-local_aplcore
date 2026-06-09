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
 * APL Pro Manager.
 *
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   Valery Fremaux <valery.fremaux@gmail.com> (ActiveProLearn.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_aplcore;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/local/aplcore/pro/lib.php');

/**
 * controls pro section access.
 */
final class pro_manager extends license_manager {

    /** @var short component name */
    public static $shortcomponent = 'local_aplcore';

    /** @var full component name */
    public static $component = 'local_aplcore';

    /** @var component path */
    public static $componentpath = 'local/aplcore';

    /** @var component settings page */
    public static $componentsettings = 'local_aplcore_generals';

    protected function __construct() {
        assert(1);
    }

    /**
     * Singleton implementation. Why it is better than pure static class :
     * Allows manipulation of methods through a single instance that
     * DO NOT mention the class name, so more portable accross plugins.
     * The class name is used just once per script when calling to the singleton.
     */
    public static function instance() {
        static $manager;

        if (is_null($manager)) {
            $manager = new pro_manager();
        }

        return $manager;
    }
}
