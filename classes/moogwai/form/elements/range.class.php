<?php
// This file is part of Moogwai - private project

namespace local_aplcore\moogwai\form\elements;

defined('MOOGWAI_INTERNAL') || die();

require_once($CFG->dirroot.'/local/aplcore/pear/HTML/QuickForm/range.php');

use HTML_QuickForm_range;
use moogwai_backoffice\core\output\templatable;

/**
 * Text type form element
 *
 * HTML class for a text type element
 *
 * @package   core_form
 * @category  form
 */
class range extends HTML_QuickForm_range implements templatable {

    use templatable_form_element;

    /** @var string html for help button, if empty then no help */
    var $_helpbutton = '';

    /** @var bool if true label will be hidden */
    var $_hiddenLabel = false;

    /** @var bool Whether to force the display of this element to flow LTR. */
    protected $forceltr = false;

    /**
     * constructor
     *
     * @param string $elementName (optional) name of the text field
     * @param string $elementLabel (optional) text field label
     * @param string $attributes (optional) Either a typical HTML attribute string or an associative array
     */
    public function __construct($elementName = null, $elementLabel = null, $attributes = null) {
        parent::__construct($elementName, $elementLabel, $attributes);
    }

    /**
     * Sets label to be hidden
     *
     * @param bool $hiddenLabel sets if label should be hidden
     */
    function setHiddenLabel($hiddenLabel){
        $this->_hiddenLabel = $hiddenLabel;
    }

    /**
     * Freeze the element so that only its value is returned and set persistantfreeze to false
     *
     * @access    public
     * @return    void
     */
    function freeze()
    {
        $this->_flagFrozen = true;
        $this->setPersistantFreeze(false);
    }

    /**
     * Returns the html to be used when the element is frozen
     *
     * @return    string Frozen html
     */
    function getFrozenHtml()
    {
        $attributes = ['readonly' => 'readonly'];
        $this->updateAttributes($attributes);
        return $this->_getTabs() . '<input' . $this->_getAttrString($this->_attributes) . ' />' . $this->_getPersistantData();
    }

    /**
     * Returns HTML for this form element.
     *
     * @return string
     */
    public function toHtml() {

        // Add the class at the last minute.
        if ($this->get_force_ltr()) {
            if (!isset($this->_attributes['class'])) {
                $this->_attributes['class'] = 'text-ltr';
            } else {
                $this->_attributes['class'] .= ' text-ltr';
            }
        }

        $this->_generateId();
        if ($this->_flagFrozen) {
            return $this->getFrozenHtml();
        }
        $html = $this->_getTabs() . '<input' . $this->_getAttrString($this->_attributes) . ' />';
        $html .= ' <div id="val_'.$this->getAttribute('id').'</div>';

        if ($this->_hiddenLabel){
            return '<label class="accesshide" for="'.$this->getAttribute('id').'" >'.
                        $this->getLabel() . '</label>' . $html;
        } else {
             return $html;
        }
    }

    /**
     * get html for help button
     *
     * @return string html for help button
     */
    function getHelpButton(){
        return $this->_helpbutton;
    }

    /**
     * Get force LTR option.
     *
     * @return bool
     */
    public function get_force_ltr() {
        return $this->forceltr;
    }

    /**
     * Force the field to flow left-to-right.
     *
     * This is useful for fields such as URLs, passwords, settings, etc...
     *
     * @param bool $value The value to set the option to.
     */
    public function set_force_ltr($value) {
        $this->forceltr = (bool) $value;
    }
}
