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
 * General library.
 *
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com> (ActiveProLearn.com)
 * @copyright   Valery Fremaux <valery.fremaux@gmail.com>, Florence Labord <labord.florence@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

// phpcs:disable moodle.Commenting.ValidTags.Invalid
// Abusive PSR12 rule : adds useless spaces in string concatenation.
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceBefore
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceAfter

// Errors should be always traced when trace is on.
define('LOCAL_APLCORE_TRACE_ERRORS', 1);
// Notices are important notices in normal execution.
define('LOCAL_APLCORE_TRACE_NOTICE', 3);
// Debug are debug time notices that should be burried in debug_fine level when debug is ok.
define('LOCAL_APLCORE_TRACE_DEBUG', 5);
// Data level is when requiring to see data structures content.
define('LOCAL_APLCORE_TRACE_DATA', 8);
// Debug fine are control points we want to keep when code is refactored and debug needs to be reactivated.
define('LOCAL_APLCORE_TRACE_DEBUG_FINE', 10);

/**
 * Tells which features are supported against distribution.
 * @param string $feature
 * @param bool $getsupported
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function local_aplcore_supports_feature($feature = null, $getsupported = false) {
    static $supports;

    if ($getsupported) {
        return $supports;
    }

    if (empty($feature)) {
        // Now feature is required.
        throw new moodle_exception("<plugin>_supports_feature needs now be called with an explicit feature key");
    }

    if (!isset($supports)) {
        $supports = [
            'pro' => [
                'notify' => ['zabbix'],
            ],
            'community' => [
                'notify' => ['zabbix'],
            ],
        ];
    }

    if (array_key_exists($feat, $supports['community'])) {
        if (in_array($subfeat, $supports['community'][$feat])) {
            return 'community';
        }
    }

    if (array_key_exists($feat, $supports['pro'])) {
        if (in_array($subfeat, $supports['pro'][$feat])) {
            return 'pro';
        }
    }

    return false;
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
