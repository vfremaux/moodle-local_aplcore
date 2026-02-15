<?php

namespace local_vflibs\moogwai\form;

use moodleform;

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * Wrapper class to Moodle.
 */
abstract class moogwaiform extends moodleform {

    protected $_action;

    /**
     * Wrapper constructor.
     */
    public function __construct($action = null, $customdata = null, $method = 'post', $target = '',
            $attributes = null, $editable = true, $ajaxformdata = null) {

        parent::__construct($action, $customdata, $method, $target, $attributes,
                $editable, $ajaxformdata);
        $this->_action = $action;
    }

    /**
     *
     */
     public function get_action() {
        return $this->_action;
     }
}