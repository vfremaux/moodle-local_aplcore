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

require_once($CFG->dirroot.'/local/aplcore/pear/HTML/QuickForm/elementchooser.php');

defined('MOOGWAI_INTERNAL') || die();

use html_writer;
use html_table;
use core\output\templatable;
use core\output\renderer_base;
use HTML_QuickForm_elementchooser;
use MoodleQuickForm;

/**
 * HTML class for a grid of elements
 *
 * Overloaded {@link HTML_QuickForm_ElementChooser} to add help button
 *
 * @package   core_form
 * @category  form
 */
class elementchooser extends HTML_QuickForm_ElementChooser {

    /** @var string html for help button, if empty then no help */
    var $_helpbutton = '';

    /**
     * constructor
     *
     * @param string $elementName Select name attribute
     * @param mixed $elementLabel Label(s) for the select
     * @param mixed $component the component name
     * @param mixed $options Visual options
     */
    public function __construct($elementName = null, $component = null, $options = null) {
        parent::__construct($elementName, $component, $options);
    }

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

}

MoodleQuickForm::registerElementType('elementchooser', $CFG->dirroot.'/local/aplcore/classes/moogwai/form/elements/elementchooser.class.php', '\\local_aplcore\\moogwai\\form\\elements\\elementchooser');
