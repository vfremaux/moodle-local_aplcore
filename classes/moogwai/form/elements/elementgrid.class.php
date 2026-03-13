<?php
// This file is part of Moogwai - private project


/**
 * Button form element
 *
 * Contains HTML class for a button type element
 *
 * @package   core_form
 */

namespace local_aplcore\moogwai\form\elements;

require_once($CFG->dirroot.'/local/aplcore/pear/HTML/QuickForm/elementgrid.php');

defined('MOOGWAI_INTERNAL') || die();

use html_writer;
use html_table;
use core\output\templatable;
use core\output\renderer_base;
use HTML_QuickForm_elementgrid;
use MoodleQuickForm;

/**
 * HTML class for a grid of elements
 *
 * Overloaded {@link HTML_QuickForm_button} to add help button
 *
 * @package   core_form
 * @category  form
 */
class elementgrid extends HTML_QuickForm_elementgrid {

    /** @var string html for help button, if empty then no help */
    var $_helpbutton = '';

    /**
     * get html for help button
     *
     * @return string html for help button
     */
    function getHelpButton() {
        return $this->_helpbutton;
    }

    /**
     * Slightly different container template when frozen.
     *
     * @return string
     */
    function getElementTemplateType() {
        if ($this->_flagFrozen){
            return 'nodisplay';
        } else {
            return 'default';
        }
    }

    /**
     * Returns Html for the element
     *
     * @access      public
     * @return      string
     */
    function toHtml() {
        global $OUTPUT;

        $table = new html_table();
        // $table->updateAttributes($this->getAttributes());

        $hasrownames = !empty($this->_rowNames);

        $col = ($hasrownames) ? 1 : 0;
        $header = [];
        if ($this->_columnNames) {
            foreach ($this->_columnNames as $key => $value) {
                ++$col;
                $header[] = $value;
            }
        }
        $table->head = $header;

        $data = [];
        foreach (array_keys($this->_rows) as $key) {
            $col = 0;
            $row = [];
            if ($hasrownames) {
                $row[] = array_shift($this->_rowNames);
            }
            foreach (array_keys($this->_rows[$key]) as $key2) {
                ++$col;
                $row[] = $this->_rows[$key][$key2]->toHTML();
            }
            $data[] = $row;
        }
        $table->data = $data;

        $html = '';
        $label = $this->getLabel();
        if (!empty($this->options['showLabel']) && !empty($label)) {
            $tagname = 'H'.$this->options['titleLevel'] ?? 2;
            $html .= html_writer::tag($tagname, $label);
        }
        $html .= html_writer::table($table);

        return $html;
    }
}

MoodleQuickForm::registerElementType('elementgrid', $CFG->dirroot.'/local/vflibs/classes/moogwai/form/elements/elementgrid.class.php', '\\local_vflibs\\moogwai\\form\\elements\\elementgrid');
