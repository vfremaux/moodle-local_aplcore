<?php
/**
 * This is a new element type for HTML_QuickForm which defines a boostrap container of QuickForm elements
 *
 * PHP Versions 4 and 5
 *
 * @category HTML
 * @package  HTML_QuickForm_ElementContainer
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL
 * @author   Valery Fremaux <valery.fremaux@gmail.com>
 * @version  $Id$
 */

require_once 'HTML/QuickForm/element.php';

/**
 * An HTML_QuickForm element which holds any number of other elements in a bootstrap fluid container.
 * Used in DB_DataObject_FormBuilder for tripleLinks and crossLinks when there are
 * crossLinkExtraFields. This element type makes these grids of elements behave the
 * same as normal elements in the form. i.e. they will freeze correctly and get
 * values (defaults) set correctly.
 */
class HTML_QuickForm_ElementContainer extends HTML_QuickForm_element {

    /**
     * Array of HTML_QuickForm elements
     *
     * @var array
     */
    var $_elements = [];

    /**
     * Holds this element's name
     *
     * @var string
     */
    var $_name;

    /**
     * Holds a reference to the form for use when adding elements
     *
     * @var HTML_QuickForm
     */
    var $_form;

    /**
     * Holds options
     *
     * @var array
     */
    var $_options = ['actAsGroup' => false];

    /**
     * Constructor
     *
     * @param string name for the element
     * @param string label for the element
     */
    public function __construct($name = null, $label = null, $options = null) {
        parent::__construct($name, $label);
        $this->updateAttributes(['class' => 'elementContainer']);
        if (is_array($options)) {
            $this->_options = array_merge($this->_options, $options);
        }
    }

    /**
     * Sets this element's name
     *
     * @param string name
     */
    public function setName($name) {
        $this->_name = $name;
    }

    /**
     * Gets this element's name
     *
     * @return string name
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Add an element to the container
     *
     * @param array array of HTML_QuickForm elements
     */
    public function addElement($element) {
        $this->_elements[] = $element;
    }

    /**
     * Sets an element's array in the container
     *
     * @param array array of HTML_QuickForm elements
     */
    public function setElements(array $elements) {
        $this->_elements = $elements;
    }

    /**
     * Freezes all elements in the grid
     */
    public function freeze() {
        parent::freeze();
        foreach ($this->_elements as $element) {
            $element->freeze();
        }
    }

    /**
     * Returns Html for the element
     * this method is fully overriden by the Moodle element grid Wrapper
     * as we cannot use pear HTML_Table class in Moodle.
     *
     * @access      public
     * @return      string
     */
    public function toHtml() {
        assert(1);
        // Let moodle front class do the job.
    }

    /**
     * Called by HTML_QuickForm whenever form event is made on this element
     *
     * @param     string  Name of event
     * @param     mixed   event arguments
     * @param     object  calling object
     * @access    public
     * @return    bool    true
     */
    public function onQuickFormEvent($event, $arg, &$caller) {
        switch ($event) {

            case 'createElement': {

                [$name, $component, $options, $unused1, $unused2, $unused3] = $arg;
                $this->_label = ''; // Do not use label.
                $this->_name = $name;
                $this->_component = $component;

                if (is_array($options)) {
                    $this->_options = array_merge($this->_options, $options);
                }
            }

            case 'updateValue': {
                //store form for use in addRow
                $this->_form =& $caller;

                foreach ($this->_elements as $element) {
                    $element->onQuickFormEvent('updateValue', null, $caller);
                }
                break;
            }

            default:
                parent::onQuickFormEvent($event, $arg, $caller);
        }
        return true;
    }

    /**
     * Returns a 'safe' element's value
     *
     * @param  array   array of submitted values to search
     * @param  bool    whether to return the value as associative array
     * @access public
     * @return mixed
     */
    public function exportValue(&$submitValues, $assoc = false) {
        if ($this->_options['actAsGroup']) {
            return parent::exportValue($submitValues, $assoc);
        }

        if ($assoc) {
            $values = [];
            foreach ($this->_elements as $element) {
                $value = $element->exportValue($submitValues, true);
                if (is_array($value)) {
                    $values = HTML_QuickForm::arrayMerge($values, $value);
                } else {
                    $values[element->getName()] = $value;
                }
            }
            return $values;
        } else {
            return null;
        }
    }

    /**
     * Returns the value of the form element
     *
     * @access    public
     * @return    mixed
     */
    public function getValue() {
        $values = [];
        foreach ($this->_elements as $element) {
            $values[$element->getName()] = $element->getValue();
        }
        return $values;
    }
}

require_once('HTML/QuickForm.php');
