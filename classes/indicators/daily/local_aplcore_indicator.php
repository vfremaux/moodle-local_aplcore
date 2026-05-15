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
 * Zabbix indicators for APL Core
 *
 * @package     local_aplcore
 * @author      Valery Fremaux valery.fremaux@gmail.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @copyright   2020 Valery Fremaux (https://www.activeprolearn.com)
 */
namespace report_zabbix\indicators\daily;

// False positive.
// phpcs:disable PSR2.ControlStructures.SwitchDeclaration.WrongOpenercase

use moodle_exception;
use coding_exception;
use StdClass;
use report_zabbix\zabbix_indicator;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/aplcore/lib.php');

/**
 * Plugin's indicators.
 */
class aplcore_indicator extends zabbix_indicator {
    /** @var string $submodes */
    public static $submodes = '';

    /** @var $states a cache for licensed plugin states */
    protected $states;

    /** @var array $licenseproviders a cache for licensed providers */
    protected $licensedproviders;

    /** @var array $licenseplugins a cache for licensed plugins */
    protected $licensedplugins;

    /**
     * Constructor
     */
    public function __construct() {
        global $DB;

        parent::__construct();
        $this->key = 'moodle.aplcore';

        $this->licensedplugins = $DB->get_records('config_plugins', ['name' => 'licensekey'], '', 'plugin,value');
        $this->licenseproviders = $DB->get_records('config_plugins', ['name' => 'licenseprovider'], '', 'plugin,value');

        $teststates = [];
        foreach ($this->licensedplugins as $lp) {
            if (!in_array($lp->plugin, array_keys($this->licenseproviders))) {
                // May use a licensekey but not APLCore.
                continue;
            }
            $this->licencedplugins[$lp->plugin] = $lp; // Confirm in $this.
            $teststates[] = '[' . $lp->plugin . '.status]';
        }

        $licenseends = [];
        foreach ($this->licensedplugins as $lp) {
            if (!in_array($lp->plugin, array_keys($this->licenseproviders))) {
                // May use a licensekey but not APLCore.
                continue;
            }
            $licenseends[] = '[' . $lp->plugin . '.end]';
        }
        self::$submodes = implode(',', $teststates) . ',' . implode(',', $licenseends);

        $this->states = $this->load_states();
    }

    /**
     * Return all available submodes
     * return array of strings
     */
    public function get_submodes() {
        return explode(',', self::$submodes);
    }

    /**
     * The function that contains the logic to acquire the indicator instant value.
     * @param string $submode to target an aquisition to an explicit submode
     */
    public function acquire_submode($submode) {
        if (!is_object($this->value)) {
            $this->value = new Stdclass();
        }

        if (is_null($submode)) {
            $submode = $this->submode;
        }

        preg_match('#\\[([0-9a-z]+)\.(status|end)\\]#', $submode, $matches);
        $plugin = $matches[1];
        $subsubmode = $matches[2];

        if ($subsubmode == 'status') {
            // Send the test feedback result.
            $this->value->$submode = $this->states[$plugin]->licensestate;
        } else {
            // Send the test measurement.
            $this->value->$submode = $this->get_end($plugin);
        }
    }

    /**
     * Load licensed plugins status values.
     */
    protected function load_states() {
        global $CFG;

        $this->states = [];

        if (!local_aplcore_supports_feature('notify/zabbix')) {
            return;
        }

        include_once($CFG->dirroot . '/local/aplcore/pro/lib.php');

        $licensemanager = \local_aplcore\license_manager::instance();

        foreach ($this->licensedplugins as $lp) {
            $state = $licensemanager->get_plugin_cached($lp->plugin);
            $this->states[$lp->plugin] = $state;
        }
    }

    /**
     * Get some information about license ending horizon.
     * @param $plugin
     */
    protected function get_end($plugin) {
        $status = $this->states[$plugin]->licensestatus;
        $status = preg_replace('/(SET|CHECK) OK/', '', $status);
        $status = trim($status);

        switch ($status) {
            case '-30d': {
                break;
            }

            case '-15d': {
                break;
            }

            case '-5d': {
                break;
            }
        }
    }
}
