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
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   Valery Fremaux <valery.fremaux@gmail.com> (ActiveProLearn.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_aplcore;

require_once($CFG->libdir.'/filelib.php');
require_once($CFG->dirroot.'/local/aplcore/lib.php');

use StdClass;
use moodle_url;
use admin_setting_heading;
use admin_setting_configcheckbox;
use admin_setting_configtext;
use moodle_exception;
use lang_string;
use cache;
use curl;

/**
 * This class centralizes license management common primitives for all licensed plugins.
 * This class must NEVER be overriden.
 */
class license_manager {

    /** @var This is a confidential URL location that serves for license checking. Plugins will be hardwired to use it. */
    static $licenserouterurl = 'https://service4.mylearningfactory.com/providers/router.php';
    static $failoverlicenserouterurl = 'https://service3.mylearningfactory.com/providers/router.php';

    /**
     * Constructor.
     */
    protected function __construct() {
        // Void constructor to ensure singleton pattern.
        return;
    }

    /**
     * Singleton instance generation.
     */
    static public function instance() {
        static $instance;

        if (!isset($instance)) {
            $instance = new license_manager();
        }

        return $instance;
    }

    /**
     * Licensing security advice :
     * This method should never be called from public parts. Its use needs
     * to remain confidential for "pro" sections to route the licence check calls to 
     * a unique point.
     * The licence router service is located at a common, distributor agnostic URL, which will
     * re-route the calls to the appropriate distributor web site.
     */
    public function get_license_router_url() {
        return self::$licenserouterurl;
    }

    /**
     * Get cached license keys for a plugin
     * @param string $plugin
     */
    public function get_plugin_cached($plugin) {

        $procache = cache::make($plugin, 'pro');

        // Ask cache for data.
        $plugindata = new StdClass();
        $plugindata->status = $procache->get('licensestatus');
        $plugindata->checkdate = $procache->get('licensecheckdate');
        $plugindata->licensekey = get_config('licensekey', $plugin);
        $plugindata->licenseprovider = get_config('licenseprovider', $plugin);

        // License key state analysis.
        if (empty($plugindata->licensekey)) {
            $plugindata->licensestate = 0; // Not regsistered
            $plugindata->licensestatetext = 'Unregistered';
        }

        if (preg_match('/(CHECK|SET) FAILURE : ([\\d]+) (.*)/', $plugindata->licensekey ?? '')) {
            // Check or retreive failed.
            $plugindata->licensestate = $matches[2];
            $plugindata->licensestatetext = $matches[3];
        } else {
            // Everything is OK.
            $plugindata->licensestate = 1000;
            $plugindata->licensestatetext = $plugindata->status;
        }

        return $plugindata;
    }

    /**
     * When giving a feature, checks the pro license is active and the feature is registered.
     * When used with empty feature, just tests this is a pro equiped package.
     * @param string $feature the feature to check 
     * @return boolean true if pro check is asserted
     */
    public function require_pro($feature = false) {
        $func = static::$shortcomponent.'_supports_feature';
        if (!empty($feature)) {
            if ($func($feature)) {
                $test = self::set_and_check_license_key(null, null, true /* interactive */);
                $test = (preg_match('/(SET|CHECK) OK/', $test));
                return $test;
            }
        } else {
            return ('pro' == $func());
        }
    }

    /**
     * Adds additional settings to the component settings (generic part of the prolib system).
     * @param objectref &$admin
     * @param objectref &$settings
     */
    public function add_settings(&$admin, &$settings) {
        global $CFG, $PAGE;

        $PAGE->requires->js_call_amd(static::$component.'/pro', 'init');

        $settings->add(new admin_setting_heading('plugindisthdr', get_string('plugindist', static::$shortcomponent), ''));

        $key = static::$shortcomponent.'/emulatecommunity';
        $label = get_string('emulatecommunity', static::$shortcomponent);
        $desc = get_string('emulatecommunity_desc', static::$shortcomponent);
        $settings->add(new admin_setting_configcheckbox($key, $label, $desc, 0));

        $key = static::$shortcomponent.'/licenseprovider';
        $label = get_string('licenseprovider', static::$shortcomponent);
        $desc = get_string('licenseprovider_desc', static::$shortcomponent);
        $settings->add(new admin_setting_configtext($key, $label, $desc, ''));

        $key = static::$shortcomponent.'/licensekey';
        $label = get_string('licensekey', static::$shortcomponent);
        $desc = get_string('licensekey_desc', static::$shortcomponent);
        $settings->add(new admin_setting_configtext($key, $label, $desc, ''));

        $key = static::$shortcomponent.'/getkey';
        $selfregisterurl = new moodle_url('/local/aplcore/pro/getoptions.php', ['component' => static::$component]);
        $desc = new lang_string('getlicensekey_desc', 'local_aplcore', $selfregisterurl->out());
        $settings->add(new admin_setting_heading($key, false, $desc));

        // These are additional plugin context pro settings.
        if (file_exists($CFG->dirroot.'/'.static::$componentpath.'/pro/localprolib.php')) {
            include_once($CFG->dirroot.'/'.static::$componentpath.'/pro/localprolib.php');

            $key = static::$shortcomponent.'/specificprosettings';
            $label = get_string('specificprosettings', static::$shortcomponent);
            $desc = '';
            $settings->add(new admin_setting_heading($key, $label, $desc));

            $localpromanagerclass = "\\".static::$shortcomponent."\\local_pro_manager";

            $localpromanagerclass::add_settings($admin, $settings);
        }
    }

    /**
     * Sends an empty license using advice to registered provider.
     */
    public function send_empty_license_signal() {
        global $CFG;

        $config = get_config(static::$component);

        $func = static::$shortcomponent.'_supports_feature';
        if ($func() && empty($config->licensekey)) {
            if ($config->licensekeycheckdate < time() - 30 * (int) DAYSECS) {

                $url = static::$licenserouterurl;
                $furl = static::$failoverlicenserouterurl;

                // This is an hidden switch that help tests. DO NOT USE for production.
                if (!empty($CFG->preferfailoverlicenserouter)) {
                    /*
                     * Use only for tests. Timeouts are NOT exchanged
                     * by this switch.
                     */
                    $u = $furl;
                    $furl = $url;
                    $url = $u;
                }

                $qs = '?provider='.$config->licenseprovider;
                $qs .= '&service=tell';
                $qs .= '&component='.static::$component;
                $qs .= '&host='.urlencode($CFG->wwwroot);
                $qs .= '&lang='.current_language();

                $settings = [
                    'cache' => true,
                    'ignoresecurity' => true,
                ];

                $curl = new curl($settings);
                // Keep the standard timeout.
                $result = $curl->get($url.$qs);
                $curlerrno = $curl->get_errno();
                if (empty($result) || $curlerrno != 0) {
                    $curl = new curl($settings);
                    $result = $curl->get($furl.$qs);
                }

                set_config('licensekeycheckdate', time(), static::$shortcomponent);
            }
        }
    }

    /**
     * Where to return in settings.
     */
    public function return_url() {
        return new moodle_url('/admin/settings.php?section='.static::$componentsettings, [], 'getsupportlicense');
    }

    /**
     * Prints the empty license signal message on screen.
     */
    public function print_empty_license_message() {
        // Get a plugin's specific string if exists.
        $sm = get_string_manager();
        if ($sm->string_exists('emptysupportlicensemessage', static::$shortcomponent)) {
            $a = new StdClass();
            $settingsurl = $this->return_url();
            $a = $settingsurl->out();
            return get_string('emptysupportlicensemessage', static::$shortcomponent, $a);
        } else {
            return get_string('emptysupportlicensemessage', 'local_aplcore', $a);
        }
    }

    /**
     * checks the support license existance and validity and sends some site stats to the provider.
     * licensing status is cached for 10 days, after what it is checked again.
     * @param string $customerkey defaults to plugin configuration pro part. The customer validation key.
     * @param string $provider defaults to plugin configuration pro part. The license provider identity key.
     * @param string $interactive if true, prints error status to screen and stops immediately if license is NOT verified.
     */
    public function set_and_check_license_key($customerkey = null, $provider = null, $interactive = false) {
        global $CFG, $DB;

        local_aplcore_debug_trace('Set_end_check_license_key', LOCAL_APLCORE_TRACE_DEBUG);
        $procache = cache::make(static::$component, 'pro');

        // Ask cache for data.
        $status = $procache->get('licensestatus');
        $checkdate = $procache->get('licensecheckdate');

        if (preg_match('/(SET|CHECK) OK/', $status)) {
            if (time() < $checkdate + (10 * (int) DAYSECS)) {
                // If last check sooner than 10 days ago, do NOT ask again.
                local_aplcore_debug_trace("Got cache licence", LOCAL_APLCORE_TRACE_DEBUG);
                return $status;
            }
        }

        // Either, ask for new license check.

        $config = get_config(static::$shortcomponent);

        if (empty($customerkey)) {
            $customerkey = $config->licensekey ?? ''; // The end user license key.
        }

        if (empty($provider)) {
            $provider = $config->licenseprovider ?? '';
        }

        $regusers = $DB->count_records('user', ['deleted' => 0]);
        $courses = $DB->count_records('course');
        $coursecats = $DB->count_records('course_categories');

        $url = static::$licenserouterurl;
        $furl = static::$failoverlicenserouterurl;

        // This is an hidden switch that help tests. DO NOT USE for production.
        if (!empty($CFG->preferfailoverlicenserouter)) {
            /*
             * Use only for tests. Timeouts are NOT exchanged
             * by this switch.
             */
            $u = $furl;
            $furl = $url;
            $url = $u;
        }

        $qs = '?provider='.$provider;
        $qs .= '&service=check';
        $qs .= '&customerkey='.$customerkey;
        $qs .= '&component='.static::$component;
        $qs .= '&host='.urlencode($CFG->wwwroot);
        $qs .= '&lang='.current_language();
        $qs .= '&users='.$regusers;
        $qs .= '&courses='.$courses;
        $qs .= '&coursecats='.$coursecats;

        $settings = [
            'cache' => true,
            'ignoresecurity' => true,
        ];

        $curl = new curl($settings);
        local_aplcore_debug_trace($url.$qs, LOCAL_APLCORE_TRACE_DEBUG);
        $curl->setopt(['CURLOPT_CONNECTTIMEOUT' => 20]); // Take a quite short time for the main.
        $result = $curl->get($url.$qs);

        $curlerrno = $curl->get_errno();
        if (empty($result) || $curlerrno != 0) {
            // In case main router is failing. Use failover.
            $curl = new curl($settings);
            // Rather longer timeout. If this one fails. It's over...
            $curl->setopt(['CURLOPT_CONNECTTIMEOUT' => 200]);
            $result = $curl->get($furl.$qs);
        }

        // Get result content.
        if (!preg_match('/(SET|CHECK) OK/', $result)) {

            // Invalidate cache.
            // Do not invalidate license key, btw.
            $procache->delete('licensestatus');
            $procache->delete('licensecheckdate');

            if (!$interactive) {
                // If not interactive check. Do not go any further.
                local_aplcore_debug_trace($url.$qs, LOCAL_APLCORE_TRACE_ERRORS);
                die();
            }
            return $result;
        }

        // Cache result for keeping the response valid for a while.
        $procache->set('licensestatus', $result);
        $procache->set('licensecheckdate', time());

        // Give exact service result without change.
        return $result;
    }

    /**
     * Get the license key stored in component's config
     */
    public function get_local_license_key() {
        $config = get_config(static::$shortcomponent);
        return $config->licensekey ?? '';
    }

    /**
     * Allows a distributor having a distributor valid secret key to generate a support license key and register
     * the plugin instance into the provider shop. On success, settings of the plugin will be updated.
     * @param text $distributorkey The secret key of the distributor (Shop Partner secret key)
     * @param string $provider The provider's routing code.
     * @param string $option The purchase option from the remote provider shop.
     * @param string $error The purchase option from the remote provider shop.
     */
    public function get_license_key($distributorkey, $provider, $option, &$error) {
        global $CFG, $DB;

        if (empty($distributorkey)) {
            $error = get_string('errornodistributorkey', static::$shortcomponent);
            return false;
        }

        $config = get_config(static::$shortcomponent);
        if (empty($provider)) {
            $provider = $config->licenseprovider ?? '';
        }

        if (empty($provider)) {
            $error = get_string('errornoprovider', static::$shortcomponent);
            return false;
        }

        $regusers = $DB->count_records('user', ['deleted' => 0]);
        $courses = $DB->count_records('course');
        $coursecats = $DB->count_records('course_categories');

        $url = static::$licenserouterurl;
        $furl = static::$failoverlicenserouterurl;

        // This is an hidden switch that help tests. DO NOT USE for production.
        if (!empty($CFG->preferfailoverlicenserouter)) {
            /*
             * Use only for tests. Timeouts are NOT exchanged
             * by this switch.
             */
            $u = $furl;
            $furl = $url;
            $url = $u;
        }

        $qs = '';
        $postfields = [
            'provider' => $provider,
            'service' => 'get',
            'distributorkey' => $distributorkey,
            'customerkey' => $config->licensekey, // Eventual pre existing end customer key.
            'component' => static::$component,
            'host' => $CFG->wwwroot,
            'lang' => current_language(), // Language of the operator.
            'users' => $regusers,
            'courses' => $courses,
            'coursecats' => $coursecats,
            'option' => $option,
        ];
        $qs = '?'.http_build_query($postfields, '', '&');

        $settings = [
            'cache' => true,
            'ignoresecurity' => true,
        ];

        $curl = new curl($settings);
        // Rather short timeout for the first call. So if it fails, bounce as fast as possible...
        $curl->setopt(['CURLOPT_CONNECTTIMEOUT' => 20]);
        local_aplcore_debug_trace('local_aplcore get_license_key : '.$url.$qs, LOCAL_APLCORE_TRACE_DEBUG);
        $jsonresult = $curl->get($url.$qs);

        $curlerrno = $curl->get_errno();
        if (empty($jsonresult) || $curlerrno != 0) {
            // In case main router is failing. Use failover.
            $curl = new curl($settings);
            // Rather longer timeout. If this one fails. It's over...
            $curl->setopt(['CURLOPT_CONNECTTIMEOUT' => 200]);
            $jsonresult = $curl->get($furl.$qs);
        }

        $result = json_decode($jsonresult);

        if (empty($result)) {
            local_aplcore_debug_trace($url.$qs, LOCAL_APLCORE_TRACE_ERRORS);
            $error = get_string('errorjson', static::$shortcomponent);
            return false;
        }

        if ($result->status == 100) {
            local_aplcore_debug_trace($url.$qs, LOCAL_APLCORE_TRACE_ERRORS);
            $error = get_string('errorresponse', static::$shortcomponent, $result->error);
            return false;
        }

        // Get result content.
        if (!preg_match('/[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}/', $result->reference)) {
            $returnurl = new moodle_url('/'.static::$componentpath.'/pro/getkey.php');
            $error = get_string('errornokeygenerated', static::$shortcomponent);
            return false;
        }

        set_config('licensekey', $result->reference, static::$shortcomponent);
        set_config('licenseprovider', $provider, static::$shortcomponent);

        // Give exact service result without change.
        return true;
    }

    /**
     * Get the possible distribution options the distributor can activate for the plugin.
     * @param text $distributorkey
     * @param text $provider
     * @return an array of product descriptions giving activation options.
     */
    public function get_activation_options($distributorkey, $provider = null) {
        global $CFG, $DB;

        $config = get_config(static::$shortcomponent);
        if (empty($provider)) {
            $provider = $config->licenseprovider ?? '';
        }

        if (empty($provider)) {
            throw new moodle_exception(get_string('errornoprovider', static::$shortcomponent));
        }

        $url = static::$licenserouterurl;
        $furl = static::$failoverlicenserouterurl;

        // This is an hidden switch that help tests. DO NOT USE for production.
        if (!empty($CFG->preferfailoverlicenserouter)) {
            /*
             * Use only for tests. Timeouts are NOT exchanged
             * by this switch.
             */
            $u = $furl;
            $furl = $url;
            $url = $u;
        }

        $qs = '?provider='.$provider;
        $qs .= '&service=getproducts';
        $qs .= '&distributorkey='.$distributorkey;
        $qs .= '&customerkey='.$config->licensekey ?? '';
        $qs .= '&component='.static::$component;
        $qs .= '&host='.urlencode($CFG->wwwroot);
        $qs .= '&lang='.current_language();

        $settings = [
            'cache' => true,
            'ignoresecurity' => true,
        ];

        $curl = new curl($settings);
        local_aplcore_debug_trace($url.$qs, LOCAL_APLCORE_TRACE_DEBUG);
        $curl->setopt(['CURLOPT_CONNECTTIMEOUT' => 20]);
        $jsonresult = $curl->get($url.$qs);

        $curlerrno = $curl->get_errno();
        if (empty($jsonresult) || $curlerrno != 0) {
            // In case main router is failing. Use failover.
            $curl = new curl($settings);
            $curl->setopt(['CURLOPT_CONNECTTIMEOUT' => 200]);
            $jsonresult = $curl->get($furl.$qs);
        }

        $result = json_decode($jsonresult);

        if (empty($result)) {
            local_aplcore_debug_trace($url, LOCAL_APLCORE_TRACE_ERRORS);
            throw new moodle_exception(get_string('errorjson', static::$shortcomponent));
            die;
        }

        if ($result->status == 100) {
            local_aplcore_debug_trace($url, LOCAL_APLCORE_TRACE_ERRORS);
            throw new moodle_exception(get_string('errorresponse', static::$shortcomponent, $result->error));
            die;
        }

        // Give exact service result without change.
        return $result->choices;
    }
}
