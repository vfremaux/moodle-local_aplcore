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

require_once($CFG->dirroot.'/local/aplcore/pear/HTML/QuickForm/elementcontainer.php');

defined('MOOGWAI_INTERNAL') || die();

use StdClass;
use html_writer;
use html_table;
use core\output\templatable;
use core\output\renderer_base;
use HTML_QuickForm_ElementContainer;
use MoodleQuickForm;

/**
 * HTML class for a grid of elements
 *
 * Overloaded {@link HTML_QuickForm_button} to add help button
 *
 * @package   core_form
 * @category  form
 */
class elementcontainer extends HTML_QuickForm_ElementContainer {

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

        $id = 1;
        foreach ($this->_elements as $element) {
            $elementtpl = new StdClass;
            $elementtpl->html = $element->toHTML();
            $elementtpl->id = 'elm'.$id;
            $elements[] = $elementtpl;
            $id++;
        }

        $template = new StdClass;
        $name = $this->getName();
        $template->id = 'container'.uniqid();
        $template->elements = $elements;
        if (!array_key_exists('layout', $this->_options)) {
            $this->_options['layout'] = 'cards';
        }
        $template->hascards = $this->_options['layout'] == 'cards';
        $template->istable = $this->_options['layout'] == 'table';

        if (!empty($this->_options['showName']) && !empty($name)) {
            $template->titlelevel = $this->_options['titleLevel'] ?? 2;
            $template->name = $name;
        }
        return $OUTPUT->render_from_template('local_vflibs/form/elements/element-elementcontainer', $template);
    }
}

MoodleQuickForm::registerElementType('elementcontainer', $CFG->dirroot.'/local/vflibs/classes/moogwai/form/elements/elementcontainer.class.php', '\\local_vflibs\\moogwai\\form\\elements\\elementcontainer');
