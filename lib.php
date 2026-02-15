<?php
// This file is NOT part of Moodle - http://moodle.org/
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
 * General library.
 *
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com> (ActiveProLearn.com)
 * @copyright   Valery Fremaux <valery.fremaux@gmail.com>, Florence Labord <labord.florence@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

define('LOCAL_APLCORE_TRACE_ERRORS', 1); // Errors should be always traced when trace is on.
define('LOCAL_APLCORE_TRACE_NOTICE', 3); // Notices are important notices in normal execution.
define('LOCAL_APLCORE_TRACE_DEBUG', 5); // Debug are debug time notices that should be burried in debug_fine level when debug is ok.
define('LOCAL_APLCORE_TRACE_DATA', 8); // Data level is when requiring to see data structures content.
define('LOCAL_APLCORE_TRACE_DEBUG_FINE', 10); // Debug fine are control points we want to keep when code is refactored and debug needs to be reactivated.

/**
 * Tells which features are supported against distribution.
 * @param string $feature
 * @param bool $getsupported
 */
function local_aplcore_supports_feature($feature = null, $getsupported = false) {
    global $CFG;
    static $supports;

    if (!during_initial_install()) {
        $config = get_config('local_aplcore');
    }

    if (!isset($supports)) {
        $supports = [
            'pro' => [
                'notify' => ['zabbix'],
            ],
            'community' => [
            ],
        ];
    }

    if ($getsupported) {
        return $supports;
    }

    // Check existance of the 'pro' dir in plugin.
    if (is_dir(__DIR__.'/pro')) {
        if ($feature == 'emulate/community') {
            return 'pro';
        }
        if (empty($config->emulatecommunity)) {
            $versionkey = 'pro';
        } else {
            $versionkey = 'community';
        }
    } else {
        $versionkey = 'community';
    }

    if (empty($feature)) {
        // Just return version.
        return $versionkey;
    }

    list($feat, $subfeat) = explode('/', $feature);

    if (!array_key_exists($feat, $supports[$versionkey])) {
        return false;
    }

    if (!in_array($subfeat, $supports[$versionkey][$feat])) {
        return false;
    }

    return $versionkey;
}

/**
 * A wrapper to APL debug. Do not use trace constants here because they may be not installed.
 * @param string $msg
 * @param int $level
 * @param string $label
 * @param int $backtracelevel
 */
function local_aplcore_debug_trace($msg, $level = LOCAL_APLCORE_TRACE_NOTICE, $label = '', $backtracelevel = 1) {
    if (function_exists('debug_trace')) {
        debug_trace($msg, $level, $label, $backtracelevel + 1);
    }
}