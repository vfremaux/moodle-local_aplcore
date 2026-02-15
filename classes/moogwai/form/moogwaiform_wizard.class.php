<?php
// This file is part of Moogwai - private project

namespace local_vflibs\moogwai\form;

defined('MOOGWAI_INTERNAL') || die();

use stdClass;
use local_vflibs\moogwai\moogwai_url;
use local_vflibs\moogwai\exceptions\CodingException;

/**
 * This is a modified version of the moogwaiform base form, 
 *
 * The purpose of this class is to add a wizard behaviour to the form, keeping in
 * user's session the intermediary results until the last step is validated.
 * Data will be kept in $SESSION global.
 * Uploaded files and editor files should be kept in user's draft area, all the files
 * will be moved to final fileareas on last step.
 * If the last step is cancelled, than all the collected data will be lost.
 *
 * @package   core_form
 */
abstract class moogwaiform_wizard extends moogwaiform {

    /**
     * this will identify the wizard instance.
     */
    protected $wizard;

    protected $step;

    protected $iscurrent;

    protected $islast;

    /**
     * Wizard form constructor
     * @param string $wizardname identifies the session storage of the wizard.
     * @param int $step
     * @param bool $islast
     * @param moogwai_url|string $url current submit url
     * @param array $customdata additional custom data
     */
    public function __construct($wizard, $step, $islast, moogwai_url $url, $customdata = []) {

        if (is_null($url)) {
            throw new CodingException("An explicit moogwai_url must be provided in wizard forms");
        }

        if (is_null($url->get_param('step'))) {
            throw new CodingException("Moogwai wizard form urls need having a \"step\" attribute");
        }

        if ($url->get_param('step') === 0) {
            throw new CodingException("Moogwai wizard step should start at 1");
        }

        $this->step = $step;
        $this->islast = $islast;
        $this->wizard = $wizard;
        $this->iscurrent = false;
        $this->canbackward = false;
        parent::__construct($url, $customdata);
    }

    /**
     * In a wizarded form, get_data() will add an intermediate capture of incoming
     * data in the session Stub. It will only output the current's form captured data.
     *
     * @param stdClass|array $default_values object or array of default values
     */
    public function get_data() {
        global $SESSION;

        // What comes from that step form.
        $data = parent::get_data();

        $uniqid = $this->get_uniqueid();

        // Put in session for next steps.
        if (!isset($SESSION->$uniqid)) {
            $SESSION->$uniqid = new StdClass;
        }

        foreach (($data ?? []) as $key => $value) {
            $SESSION->$uniqid->$key = $value;
        }

        return $data;
    }

    /**
     * In a wizarded form, get_all_data() will give back all data accumulated.
     * in wizard session.
     *
     * @param stdClass|array $default_values object or array of default values
     */
    public function get_all_data() {
        global $SESSION;

        // What comes from that step form.
        $data = parent::get_data() ?? new Stdclass;

        $uniqid = $this->get_uniqueid();

        // Put in session for next steps.
        if (!isset($SESSION->$uniqid)) {
            $SESSION->$uniqid = new StdClass;
        }

        foreach ($data as $key => $value) {
            $SESSION->$uniqid->$key = $value;
        }

        // Merge all session data in output.
        foreach ($SESSION->$uniqid as $key => $value) {
            if (!isset($data->$key)) {
                $data->$key = $value;
            }
        }

        return $data;
    }

    /**
     * In a wizarded form, the default_data will be completed by what is found in
     * session.
     *
     * @param stdClass|array $default_values object or array of default values
     */
    public function set_data($default_data) {
        global $SESSION;

        $default_data = (object) $default_data;

        $uniqid = $this->get_uniqueid();
        if (!empty($SESSION->$uniqid)) {
            foreach ($SESSION->$uniqid as $key => $value) {
                $default_data->$key = $value;
            }
        }

        return parent::set_data($default_data);
    }

    /**
     * Return true if a previous button has been pressed resulting in the form being submitted.
     *
     * @return bool true if a previous button has been pressed
     */
    function is_backdrawn() {
        if ($this->optional_param('previous', 0, PARAM_RAW)) {
            return true;
        }
        return false;
    }

    /**
     * Set the current state of the form.
     * @param bool $current
     */
    public function set_current($current) {
        $this->iscurrent = $current;
    }

    /**
     * Set the current state of the form.
     * @param bool $current
     */
    public function is_current() {
        return $this->iscurrent;
    }

    /**
     * Get one single accumulated data in wizard session.
     */
    public function get_session_data($key) {
        global $SESSION;

        $uniqid = $this->get_uniqueid();
        if (isset($SESSION->$uniqid->$key)) {
            return $SESSION->$uniqid->$key;
        }
        return null;
    }

    /**
     * Add action button will be modified to
     */
    public function add_action_buttons($cancel = true, $submitlabel = null, $canbackward = false) {
        if ($this->islast) {
            return parent::add_action_buttons($cancel, $submitlabel);
        } else {
            $buttons = [];
            if ($canbackward && $this->step > 1) {
                $buttons[] = 'previous';
            }
            if ($cancel) {
                $buttons[] = 'cancel';
            }
            if (!$this->islast) {
                $buttons[] = 'next';
            } else {
                $buttons[] = 'save';
            }

            if (is_null($submitlabel)) {
                $submitlabel = get_string('savechanges');
            }

            $mform =& $this->_form;
            if (count($buttons) > 1) {
                // When more than one element we need a group
                $buttonarray = [];
                if (in_array('previous', $buttons)) {
                    $buttonarray[] = &$mform->createElement('submit', 'previous', get_string('previousstep', 'local_vflibs'));
                }
                if (in_array('cancel', $buttons)) {
                    $buttonarray[] = &$mform->createElement('cancel');
                }
                if (in_array('next', $buttons)) {
                    $buttonarray[] = &$mform->createElement('submit', 'next', get_string('nextstep', 'local_vflibs'));
                }
                if (in_array('save', $buttons)) {
                    $buttonarray[] = &$mform->createElement('submit', 'submitbutton', $submitlabel);
                }

                $mform->addGroup($buttonarray, 'buttonar', '', [' '], false);
                $mform->closeHeaderBefore('buttonar');
            } else {
                // no group needed
                if (in_array('cancel', $buttons)) {
                    $mform->addElement('cancel');
                }
                if (in_array('next', $buttons)) {
                    $mform->addElement('submit', 'next', get_string('next', 'core_form'));
                }
                if (in_array('save', $buttons)) {
                    $mform->addElement('submit', 'submitbutton', $submitlabel);
                }
                $mform->closeHeaderBefore('submitbutton');
            }
        }
    }

    public function get_uniqueid() {
        return $this->wizard->get_name();
    }

    public function is_last() {
        return $this->islast;
    }
}
