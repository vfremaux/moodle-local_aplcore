<?php
// This file is part of Moogwai - private project.

/**
 * A Generic controller class for basic wizard operations.
 *
 * @package     moogwai_backoffice
 */

namespace local_aplcore\moogwai\form;

defined('MOOGWAI_INTERNAL') || die();

use stdClass;
use local_aplcore\moogwai\moogwai_url;
use local_aplcore\moogwai\controller_base;
use local_aplcore\moogwai\exceptions\CodingException;

class controller_wizard extends controller_base {

    /** @var wizard */
    protected $wizard;

    /** @var command */
    protected $cmd;

    /** @var controller input data */
    protected $data;

    /** @var form where files come from */
    protected $mform;

    /** @var Marks a received state */
    protected $received;

    /**
     * controller constructor. You need a wizard if you want to use wizard routes.
     */
    public function __construct($wizard = null) {
        $this->wizard = $wizard;
    }

    /**
     * Receive parameters and data
     * @param $cmd
     * @param $data Form data
     * @param $mform Moogwai form instance for files
     */
    public function receive($cmd, $data = null, $mform = null) {

        parent::receive($cmd, $data, $mform);

        if (!isset($this->data)) {
            $this->data = new StdClass;
        }

        if (optional_param('next', false, PARAM_TEXT)) {
            $this->cmd = 'next';
            $this->step = required_param('step', PARAM_INT);
            $this->received = true;
        }

        if (optional_param('previous', false, PARAM_TEXT)) {
            // Is handled by $mform->is_backdrawn() case.
            throw new CodingException("Previous action should have been previously handled by calling is_backdrawn() on form.");
        }

        if ($this->received) {
            return;
        }

    }

    /**
     * Proces command.
     * Note that the wizard form get_data() already
     * recorded inputs in session.
     */
    public function process() {
        global $ME;

        parent::process();

        switch ($this->cmd) {
            case 'next': {
                if (!is_null($this->wizard) && $this->wizard->has_routes()) {
                    $step = $this->wizard->get_route();
                } else {
                    $step = $this->step + 1;
                }
                if (!is_null($this->mform)) {
                    $formurl = $this->mform->get_action();
                    if ($formurl instanceof moogwai_url) {
                        $urltogo = new moogwai_url($formurl, ['step' => $step]);
                    } else {
                        $urltogo = new moogwai_url($ME, ['step' => $step]);
                    }
                } else {
                    $urltogo = new moogwai_url($ME, ['step' => $step]);
                }
                return $urltogo;
                break;
            }

            case 'previous': {
                // Is handled by $mform->is_backdrawn() case.
                break;
            }
        }

        // Here return to parent to let local commands to be processed.
    }
}