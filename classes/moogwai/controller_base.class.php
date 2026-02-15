<?php
// This file is part of Moogwai - private project.

/**
 * A Generic controller interface for all MVC schemes.
 *
 * @package     moogwai_backoffice
 */

namespace local_vflibs\moogwai;

defined('MOOGWAI_INTERNAL') || die();
require_once($CFG->dirroot.'/lib/filelib.php');

use stdClass;
use local_vflibs\moogwai\exceptions\CodingException;

class controller_base implements controller {

    /** @var command */
    protected $cmd;

    /** @var controller input data */
    protected $data;

    /** @var form where files come from */
    protected $mform;

    /** @var Marks a received state. ensures receive() has been called before process() */
    protected $received;

    public function receive($cmd, $data = null, $mform = null) {

        $this->cmd = $cmd;
        $this->mform = $mform;

        if (!empty($data)) {
            // Data is fed from outside.
            $this->data = (object)$data;
            $this->received = true;
            return;
        } else {
            $this->data = new StdClass;
        }

        $this->received = true;
    }

    public function process() {
        if (empty($this->received)) {
            throw new CodingException('Data must be received in controller before operation. this is a programming error.');
        }
    }

    // Post process one single editor.
    public function postprocess_editor_files($field, $component, $context, $filearea, $itemid) {

        if (!$this->mform) {
            // We may not have forms, f.e. in unit tests.
            return;
        }

        $draftideditor = file_get_submitted_draft_itemid($field.'_editor');
        $options = $this->mform->get_editor_options();
        /*
        $this->data->$field = file_save_draft_area_files($draftideditor, $context->id, $component,
                                                              $filearea, $itemid,
                                                              $options, $this->data->$field['text']);
        */
        $this->data = file_postupdate_standard_editor($this->data, $field, $options, $context, $component,
                                                $filearea, $itemid);
        return $this->data->$field;
    }
}