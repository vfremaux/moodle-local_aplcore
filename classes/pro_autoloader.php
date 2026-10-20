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
 * Define standard hooks.
 * @package    local_aplcore
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  Valery Fremaux (https://www.activeprolearn.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_aplcore;

/**
 * Autoloader class.
 */
class pro_autoloader {
    /** @var $registered idempotency */
    private static bool $registered = false;

    /**
     * Register the class loader.
     */
    public static function register(): void {
        if (self::$registered) {
            return;
        }
        spl_autoload_register([self::class, 'load'], true, false);
        self::$registered = true;
    }

    /**
     * Load a class. (callback for spl_autoload_register)
     *
     * Moodle globals ($CFG, $DB, $PAGE, $OUTPUT, $USER, $COURSE) are not in
     * scope inside a static method, so we must import them explicitly before
     * calling require_once().  Without this, any pro file that references
     * $CFG->dirroot at file level (e.g. to pull in a sibling library) will
     * see an undefined variable and produce a broken include path.
     *
     * @param string $classname Fully-qualified class name to load.
     */
    public static function load(string $classname): void {
        // Make standard Moodle globals available to the required file.
        global $CFG, $DB, $PAGE, $OUTPUT, $USER, $COURSE;

        /*
         * Convention : mod_myplugin\pro\myclass
         *           → mod/myplugin/pro/classes/myclass.php
         */
        $parts = explode('\\', $classname);
        if (count($parts) < 3 || $parts[1] !== 'pro') {
            return; // Not a pro class. Let go thru.
        }

        $component = $parts[0]; // Eg: mod_myplugin.
        $dir = \core_component::get_component_directory($component);
        if (!$dir) {
            return;
        }

        $relative = implode('/', array_slice($parts, 2)); // Without 'pro'.
        $filepath = "{$dir}/pro/classes/{$relative}.php";

        if (file_exists($filepath)) {
            require_once($filepath);
        }
    }

    /**
     * Needed by the hook API
     */
    public static function callback_register(\core\hook\after_config $hook): void {
        self::register();
    }
}
