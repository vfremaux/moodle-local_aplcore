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
 * @package     pro_generic
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   Valery Fremaux <valery.fremaux@gmail.com> (ActiveProLearn.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class GetKeyStart_Form extends moodleform {

    public function definition() {
        global $OUTPUT;

        $mform =& $this->_form;

        $mform->addElement('html', $OUTPUT->heading(get_string('getlicensekey', 'local_aplcore')));

        $mform->addElement('header', get_string('start', 'local_aplcore'));

        $mform->addElement('hidden', 'step', 'options');
        $mform->setType('step', PARAM_ALPHA);

        // Optionnaly sent in case we have already an installed pro key.
        $mform->addElement('hidden', 'licensekey', $this->_customdata['licensekey']);
        $mform->setType('licensekey', PARAM_TEXT);

        $attributes = ['size' => 16, 'maxlength' => 16];

        $mform->addElement('text', 'provider', get_string('provider', 'local_aplcore'), $attributes);
        $mform->addRule('provider', null, 'required');
        $mform->addHelpButton('provider', 'provider', 'local_aplcore');
        $mform->setType('provider', PARAM_TEXT);

        $mform->addElement('text', 'partnerkey', get_string('partnerkey', 'local_aplcore'), $attributes);
        $mform->addHelpButton('partnerkey', 'partnerkey', 'local_aplcore');
        $mform->addRule('partnerkey', null, 'required');
        $mform->setType('partnerkey', PARAM_TEXT);

        $this->add_action_buttons(true, get_string('continue', 'local_aplcore'));
    }

    public function validation($data, $files = []) {
        global $DB;

        $errors = parent::validation($data, $files);

        if (empty($data['provider'])) {
            $errors['provider'] = get_string('erroremptyprovider', 'local_aplcore');
        }

        if (empty($data['partnerkey'])) {
            $errors['partnerkey'] = get_string('erroremptypartnerkey', 'local_aplcore');
        }

        return $errors;
    }
}

class GetKey_Form extends moodleform {

    public function definition() {
        global $OUTPUT;

        $mform =& $this->_form;

        $mform->addElement('html', $OUTPUT->heading(get_string('getlicensekey', 'local_aplcore')));

        $mform->addElement('header', get_string('options', 'local_aplcore'));

        $attributes = array('size' => 16, 'maxlength' => 16);

        $mform->addElement('hidden', 'what', 'confirm');
        $mform->setType('what', PARAM_ALPHA);

        $mform->addElement('hidden', 'provider');
        $mform->setType('provider', PARAM_ALPHA);

        $mform->addElement('hidden', 'partnerkey');
        $mform->setType('partnerkey', PARAM_TEXT);

        if (!empty($options = $this->_customdata['options'])) {

            $group = [];
            foreach ($options as $optcode => $optlabel) {
                $group[] = $mform->createElement('radio', 'activationoption', '', $optlabel, $optcode, ['class' => 'purchaseoption']);
            }
            $mform->addGroup($group, 'activationoptionarr', get_string('activationoption', 'local_aplcore'), [' '], false);
            $mform->addRule('activationoptionarr', null, 'required');
            $mform->addHelpButton('activationoptionarr', 'activationoption', 'local_aplcore');

            $this->add_action_buttons(true, get_string('activate', 'local_aplcore'));

        } else {
            $mess = $OUTPUT->notification(get_string('errornooptions', 'local_aplcore'));
            $mform->addElement('static', 'activationoption', get_string('activationoption', 'local_aplcore'), $mess);

            $mform->addElement('cancel');
        }
    }

    public function validation($data, $files = []) {
        global $DB;

        $errors = parent::validation($data, $files);

        if (empty($data['provider'])) {
            $errors['provider'] = get_string('erroremptyprovider', 'local_aplcore');
        }

        if (empty($data['partnerkey'])) {
            $errors['partnerkey'] = get_string('erroremptypartnerkey', 'local_aplcore');
        }

        if (empty($data['activationoption'])) {
            $data['activationoption'] = clean_param($_POST['activationoption'], PARAM_TEXT); // form multiple bounce not handling well.
            if (empty($data['activationoption'])) {
                $errors['activationoption'] = get_string('erroremptyactivationoption', 'local_aplcore');
            }
        }

        return $errors;
    }
}