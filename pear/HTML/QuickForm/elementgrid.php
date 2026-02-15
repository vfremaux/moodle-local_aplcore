<?php
/**
 * This is a new element type for HTML_QuickForm which defines a grid of QuickForm elements
 *
 * PHP Versions 4 and 5
 *
 * @category HTML
 * @package  HTML_QuickForm_ElementGrid
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL
 * @author   Justin Patrin <papercrane@reversefold.com>
 * @version  $Id$
 */

require_once 'HTML/QuickForm/element.php';

/**
 * An HTML_QuickForm element which holds any number of other elements in a grid.
 * Used in DB_DataObject_FormBuilder for tripleLinks and crossLinks when there are
 * crossLinkExtraFields. This element type makes these grids of elements behave the
 * same as normal elements in the form. i.e. they will freeze correctly and get
 * values (defaults) set correctly.
 */
class HTML_QuickForm_ElementGrid extends HTML_QuickForm_element {

    /**
     * Array of arrays of HTML_QuickForm elements
     *
     * @var array
     */
    var $_rows = [];

    /**
     * Array of column names (strings)
     *
     * @var array
     */
    var $_columnNames = [];

    /**
     * Array of row names (strings)
     *
     * @var array
     */
    var $_rowNames = [];

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
        $this->updateAttributes(['class' => 'elementGrid']);
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
     * Sets the column names
     *
     * @param array array of column names (strings)
     */
    public function setColumnNames($columnNames) {
        $this->_columnNames = $columnNames;
    }

    /**
     * Adds a column name
     *
     * @param string name of the column
     */
    public function addColumnName($columnName) {
        $this->_columnNames[] = $columnName;
    }

    /**
     * Set the row names
     *
     * @param array array of row names (strings)
     */
    public function setRowNames($rowNames) {
        $this->_rowNames = $rowNames;
    }

    /**
     * Sets the rows
     *
     * @param array array of HTML_QuickForm elements
     */
    public function setRows($rows) {
        foreach (array_keys($rows) as $key) {
            $this->addRow($rows[$key]);
        }
    }

    /**
     * Adds a row to the grid
     *
     * @param array array of HTML_QuickForm elements
     * @param string name of the row
     */
    public function addRow(&$row, $rowName = null) {
        $key = sizeof($this->_rows);
        $this->_rows[$key] = $row;

        //if updateValue has been called make sure to update the values of each added element
        foreach (array_keys($this->_rows[$key]) as $key2) {
            if (isset($this->_form)) {
                $this->_rows[$key][$key2]->onQuickFormEvent('updateValue', null, $this->_form);
            }
            if ($this->isFrozen()) {
                $this->_rows[$key][$key2]->freeze();
            }
        }
        if ($rowName !== null) {
            $this->addRowName($rowName);
        }
    }

    /**
     * Adds a row name
     *
     * @param string name of the row
     */
    public function addRowName($rowName) {
        $this->_rowNames[] = $rowName;
    }

    /**
     * Freezes all elements in the grid
     */
    public function freeze() {
        parent::freeze();
        foreach (array_keys($this->_rows) as $key) {
            foreach (array_keys($this->_rows[$key]) as $key2) {
                $this->_rows[$key][$key2]->freeze();
            }
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
            case 'updateValue': {
                //store form for use in addRow
                $this->_form =& $caller;

                foreach (array_keys($this->_rows) as $key) {
                    foreach (array_keys($this->_rows[$key]) as $key2) {
                        $this->_rows[$key][$key2]->onQuickFormEvent('updateValue', null, $caller);
                    }
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
            $values = array();
            foreach (array_keys($this->_rows) as $key) {
                foreach (array_keys($this->_rows[$key]) as $key2) {
                    $value = $this->_rows[$key][$key2]->exportValue($submitValues, true);
                    if (is_array($value)) {
                        $values = HTML_QuickForm::arrayMerge($values, $value);
                    } else {
                        $values[$this->_rows[$key][$key2]->getName()] = $value;
                    }
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
     * @since     1.0
     * @access    public
     * @return    mixed
     */
    public function getValue() {
        $values = array();
        foreach (array_keys($this->_rows) as $key) {
            foreach (array_keys($this->_rows[$key]) as $key2) {
                $values[$this->_rows[$key][$key2]->getName()] = $this->_rows[$key][$key2]->getValue();
            }
        }
        return $values;
    }
}

require_once('HTML/QuickForm.php');
