<?php
// This file is part of Moogwai - private project

/**
 * select type form element
 *
 * Contains HTML class for a select type element
 *
 * @package   local_aplcore
 */

namespace local_aplcore\moogwai\form\elements;

defined('MOOGWAI_INTERNAL') || die();

use stdClass;
use local_aplcore\moogwai\core\output\templatable;
use core\output\renderer_base;
use MoodleQuickForm;

/**
 * form element allowing selecting one or more items visually
 * from an icon grid.
 *
 * HTML class for a visualselect type element
 *
 * @package   local_aplcore
 */
class visualselect extends select implements templatable {

    use templatable_form_element {
        export_for_template as export_for_template_base;
    }

    /** @var array $options choice options **/
    public $visualoptions = [];

    /** @var string html for help button, if empty then no help */
    public $_helpbutton = '';

    /** @var bool if true label will be hidden */
    public $_hiddenLabel = false;

    /** @var int $_rowsize */
    public $_rowsize = 5;

    /** @var int $_hideafter how many options are visible at start. If 0, does not hide any choice */
    public $_hideafter = 0;

    /**
     * constructor
     *
     * @param string $elementName Select name attribute
     * @param mixed $elementLabel Label(s) for the select
     * @param mixed $visualoptions Data to be used to populate options
     * @param mixed $attributes Either a typical HTML attribute string or an associative array
     */
    public function __construct($elementName = null, $elementLabel = null, $visualoptions = null, $attributes = null) {

        $this->visualoptions = $visualoptions;

        if (isset($attributes['rowSize'])) {
            $this->_rowsize = $attributes['rowSize'];
            unset($attributes['rowSize']);
        }

        if (isset($attributes['hideAfter'])) {
            $this->_hideafter = $attributes['hideAfter'];
            unset($attributes['hideAfter']);
        }

        $textopts = [];
        foreach (($visualoptions ?? []) as $o) {
            $textopts[$o->value] = $o->text;
        }

        parent::__construct($elementName, $elementLabel, $textopts, $attributes);
    }

    /**
     * Sets label to be hidden
     *
     * @param bool $hiddenLabel sets if label should be hidden
     */
    function setHiddenLabel($hiddenLabel) {
        $this->_hiddenLabel = $hiddenLabel;
    }

    public function getType() {
        return 'visualselect';
    }

    /**
     * Returns HTML for select form element.
     *
     * @return string
     */
    public function toHtml() {
        global $OUTPUT;

        $html = '';
        if ($this->getMultiple()) {
            // Adding an hidden field forces the browser to send an empty data even though the user did not
            // select any element. This value will be cleaned up in self::exportValue() as it will not be part
            // of the select options.
            $html .= '<input type="hidden" name="'.$this->getName().'" value="_qf__force_multiselect_submission">';
        }
        if ($this->_hiddenLabel){
            $this->_generateId();
            $html .= '<label class="accesshide" for="'.$this->getAttribute('id').'" >'.$this->getLabel().'</label>';
        }
        // $html .= parent::toHtml();

        $template = new StdClass;
        $elmtemplate = $this->export_for_template($OUTPUT);
        $template->element = $elmtemplate;
        $html .= $OUTPUT->render_from_template('local_vflibs/moogwai/form/element-visualselect', $template);

        return $html;
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
     * Removes an OPTION from the SELECT
     *
     * @param string $value Value for the OPTION to remove
     * @return void
     */
    function removeOption($value)
    {
        $key = array_search($value, $this->_values);
        if ($key !== false && $key !== null) {
            unset($this->_values[$key]);
        }
        foreach ($this->_options as $key => $option) {
            if ($option['attr']['value'] == $value) {
                unset($this->_options[$key]);
                // we must reindex the options because the ugly code in quickforms' select.php expects that keys are 0,1,2,3... !?!?
                $this->_options = array_merge($this->_options);
                return;
            }
        }
        if (array_key_exists($value, $this->visualoptions)) {
            unset($this->visualoptions[$value]);
        }
    }

    /**
     * Removes all OPTIONs from the SELECT
     */
    function removeOptions()
    {
        $this->_options = [];
    }

    /**
     * Slightly different container template when frozen. Don't want to use a label tag
     * with a for attribute in that case for the element label but instead use a div.
     * Templates are defined in renderer constructor.
     *
     * @return string
     */
    function getElementTemplateType() {
        if ($this->_flagFrozen) {
            return 'static';
        } else {
            return 'default';
        }
    }

   /**
    * We check the options and return only the values that _could_ have been
    * selected. We also return a scalar value if select is not "multiple"
    *
    * @param array $submitValues submitted values
    * @param bool $assoc if true the retured value is associated array
    * @return mixed
    */
    function exportValue(&$submitValues, $assoc = false)
    {
        $emptyvalue = $this->getMultiple() ? [] : null;
        if (empty($this->_options)) {
            return $this->_prepareValue($emptyvalue, $assoc);
        }

        $value = $this->_findValue($submitValues);
        if (is_null($value)) {
            $value = $this->getValue();
        }

        if ($this->getMultiple()) {
            if (!empty($value[0])) {
                $value = explode(',', $value[0]);
            } else {
                $value = [];
            }
        } else {
            $value = (array) $value;
        }

        $cleaned = [];
        foreach ($value as $v) {
            foreach ($this->_options as $option) {
                if ((string)$option['attr']['value'] === (string)$v) {
                    $cleaned[] = (string)$option['attr']['value'];
                    break;
                }
            }
        }

        if (empty($cleaned)) {
            return $this->_prepareValue($emptyvalue, $assoc);
        }
        if ($this->getMultiple()) {
            return $this->_prepareValue($cleaned, $assoc);
        } else {
            return $this->_prepareValue($cleaned[0], $assoc);
        }
    }

    public function export_for_template(renderer_base $output) {
        $context = $this->export_for_template_base($output);

        $rawoptions = [];
        // Standard option attributes.
        $standardoptionattributes = ['text', 'value', 'selected', 'disabled'];
        $ix = 0;
        if (!empty($this->visualoptions)) {
            foreach ($this->visualoptions as $option) {

                if (is_array($this->_values) && in_array( (string) $option->value, $this->_values)) {
                    $this->_updateAttrArray($option->attr, ['selected' => 'selected']);
                }

                // Set other attributes.
                $otheroptionattributes = [];
                foreach ($option->attr as $attr => $value) {
                    if (!in_array($attr, $standardoptionattributes) && $attr != 'class' && !is_object($value)) {
                        $otheroptionattributes[] = $attr . '="' . s($value) . '"';
                    }
                }

                // Add more/less
                if ($this->_hideafter > 0) {

                    if ($ix == $this->_hideafter) {
                        // insert a "more..." item.
                        $moreoption = new StdClass;
                        $moreoption->ismore = true;
                        $moreoption->value = get_string('more', 'local_vflibs');
                        $rawoptions[] = $moreoption;
                    }
                    if ($ix >= $this->_hideafter) {
                        $option->hidden = true;
                    }
                }
                $option->isopt = true;
                $option->optionattributes = implode(' ', $otheroptionattributes);
                $rawoptions[] = $option;
                $ix++;
            }
            if ($this->_hideafter > 0) {
                // insert a "less..." item.
                $lessoption = new StdClass;
                $lessoption->isless = true;
                $lessoption->value = get_string('less', 'local_vflibs');
                $rawoptions[] = $lessoption;
            }

            // Distribute raw options in rows.
            $optionrows = [];
            $options = [];
            foreach ($rawoptions as $o) {
                $options[] = $o;
                if (count($options) >= $this->_rowsize) {
                    $optionrowtpl = new stdClass;
                    $optionrowtpl->options = $options;
                    $optionrows[] = $optionrowtpl;
                    $options = []; // Reset options for next row.
                }
            }
            if ($numlastrow = count($options)) {
                // complete last row to _rowsize
                for ($i = $numlastrow; $i < $this->_rowsize; $i++) {
                    $options[] = new StdClass;
                }
                // Add last row if not empty.
                $optionrowtpl = new Stdclass;
                $optionrowtpl->options = $options;
                $optionrows[] = $optionrowtpl;
            }
        }
        if (!is_array($this->_values)) {
            $context['value'] = $this->_values;
        } else {
            $context['value'] = implode(',', $this->_values);
        }
        $context['nooptions'] = $output->notification(get_string('nooptions', 'local_vflibs'), 'warning');
        $context['optionrows'] = $optionrows;
        $context['hasoptions'] = !empty($optionrows);
        $context['nameraw'] = $this->getName();

        return $context;
    }

}

MoodleQuickForm::registerElementType('visualselect', $CFG->dirroot.'/local/aplcore/classes/moogwai/form/elements/visualselect.class.php', '\\local_aplcore\\moogwai\\form\\elements\\visualselect');
