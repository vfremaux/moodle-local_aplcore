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
 * This is the local version of getKey, only suitable for local_aplcore
 * TODO : parametrize to the component to serve all other plugins. 
 *
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com> (ActiveProLearn.com)
 * @copyright   Valery Fremaux <valery.fremaux@gmail.com>, Florence Labord <labord.florence@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

include('../../../config.php');

$component = required_param('component', PARAM_TEXT);
[$type, $name] = \core_component::normalize_component($component);
$componentpath = \core_component::get_plugin_directory($type, $name);

if (!file_exists($componentpath.'/pro/prolib.php')) {
    throw new moodle_exception("Not a APLCore handled plugin");
}

require_once($componentpath.'/lib.php'); // We need "<plugin>_supports_feature"
require_once($componentpath.'/pro/prolib.php');
$managerclass = $component.'\\pro_manager';
$promanager = $managerclass::instance();

require_once($CFG->dirroot.'/local/aplcore/pro/forms/form_getkey.php');

$url = new moodle_url('/local/aplcore/pro/getoptions.php', ['component' => $component]);
$PAGE->set_url($url);
$context = context_system::instance();
$PAGE->set_context($context);

require_login();
require_capability('moodle/site:config', $context);
$promanager->require_pro();

$params = [
    'manager' => $promanager,
    'licensekey' => $promanager->get_local_license_key(),
];
$mform = new GetKeyStart_Form($url, $params);

if ($mform->is_cancelled()) {
    redirect($promanager->return_url());
}

$data = $mform->get_data();

if ($data) {
    $params = [
        'provider' => $data->provider,
        'partnerkey' => $data->partnerkey,
        'licensekey' => $data->licensekey ?? '',
        'component' => $component
    ];
    $formurl = new moodle_url('/local/aplcore/pro/getkey.php', $params);
    redirect($formurl);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
