<?php
// This file is part of Moogwai - private project

/**
 * Group of date and time input element
 *
 * Contains class for a group of elements used to input a time.
 *
 * @package   local_aplcore
 */
namespace local_aplcore\moogwai\form\elements;

defined('MOOGWAI_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/form/group.php');

use MoodleQuickform;
use MoodleQuickform_group;
use HTML_QuickForm_Element;
use HTML_QuickForm_Renderer_Default;
use local_vflibs\moogwai\form\MoogwaiForm;
use renderer_base;
use core_calendar\type_factory;
use html_writer;

/**
 * Element used to input a time, eventually linked to a day timestamp.
 *
 * Class for a group of elements used to input a time as relative seconds shift from 0 (day start)
 * or absolute Linux timestamp from a default time. (default time is NOT checked for being
 * a day start at 00:00 so the caller of this class should provide accurate default time for
 * his needs.
 *
 * @package   core_form
 * @category  form
 */
class time_selector extends MoodleQuickForm_group {

    /**
     * Options for the element.
     *
     * starthour => int start of range of hours that can be selected
     * stophour => int last hour that can be selected
     * defaulttime => default time value if the field is currently not set
     * timezone => current timezone for getting current time
     * step => step to increment minutes by
     * timestampfrom => if set, will return a timestamp shifted with H:m:s value
     * optional => if true, show a checkbox beside the time to turn it on (or off)
     * @var array
     */
    protected $_options = [];

    /**
     * @var array These complement separators, they are appended to the resultant HTML.
     */
    protected $_wrap = ['', ''];

    /**
     * @var null|bool Keeps track of whether the date selector was initialised using createElement
     *                or addElement. If true, createElement was used signifying the element has been
     *                added to a group.
     */
    protected $_usedcreateelement = true;

    /**
     * Class constructor
     *
     * @param string $elementName Element's name
     * @param mixed $elementLabel Label(s) for an element
     * @param array $options Options to control the element's display
     * @param mixed $attributes Either a typical HTML attribute string or an associative array
     */
    public function __construct($elementName = null, $elementLabel = null, $options = [], $attributes = null) {

        $this->_options = [
            'starthour' => 8,
            'stophour' => 18,
            'defaulttime' => 0,
            'step' => 15,
            'optional' => false,
        ];

        // TODO Replace with the call to parent::__construct().
        HTML_QuickForm_element::__construct($elementName, $elementLabel, $attributes);
        $this->_persistantFreeze = true;
        $this->_appendName = true;
        $this->_type = 'time_selector';
        $this->_separator = [' ', ' ', ' '];
        // set the options, do not bother setting bogus ones
        if (is_array($options)) {
            foreach ($options as $name => $value) {
                if (isset($this->_options[$name])) {
                    if (is_array($value) && is_array($this->_options[$name])) {
                        $this->_options[$name] = @array_merge($this->_options[$name], $value);
                    } else {
                        $this->_options[$name] = $value;
                    }
                }
            }
        }
    }

    /**
     * This will create date group element constisting of day, month and year.
     *
     * @access private
     */
    function _createElements() {
        global $OUTPUT;

        for ($i = 0; $i <= 23; $i++) {
            $hours[$i] = sprintf("%02d", $i);
        }
        for ($i = 0; $i < 60; $i += $this->_options['step']) {
            $minutes[$i] = sprintf("%02d", $i);
        }

        $this->_elements = [];
        // If optional we add a checkbox which the user can use to turn if on.
        $attributes = $this->getAttributesForFormElement();

        if ($this->_options['optional']) {
            $this->_elements[] = $this->createFormElement('advcheckbox', 'enabled', null,
                get_string('enable'), $attributes, true);
        }

        $selectattributes = array_merge($attributes, ['class' => 'custom-select']);

        if (right_to_left()) {   // Display time to the right of date, in RTL mode.
            $this->_elements[] = $this->createFormElement('select', 'minute', get_string('minute', 'core_form'),
                $minutes, $selectattributes, true);
            $this->_elements[] = $this->createFormElement('select', 'hour', get_string('hour', 'core_form'),
                $hours, $selectattributes, true);
        }

        if (!right_to_left()) {   // Display time to the left of date, in LTR mode.
            $this->_elements[] = $this->createFormElement('select', 'hour', get_string('hour', 'core_form'), $hours,
                $selectattributes, true);
            $this->_elements[] = $this->createFormElement('select', 'minute', get_string('minute', 'core_form'), $minutes,
                $selectattributes, true);
        }

        foreach ($this->_elements as $element){
            if (method_exists($element, 'setHiddenLabel')) {
                $element->setHiddenLabel(true);
            }
        }

    }

    /**
     * Called by HTML_QuickForm whenever form event is made on this element
     *
     * @param string $event Name of event
     * @param mixed $arg event arguments
     * @param object $caller calling object
     * @return bool
     */
    function onQuickFormEvent($event, $arg, &$caller) {

        $this->setMoogwaiForm($caller);

        switch ($event) {
            case 'updateValue':
                // Constant values override both default and submitted ones
                // default values are overriden by submitted.
                $value = $this->_findValue($caller->_constantValues);
                if (null === $value) {
                    // If no boxes were checked, then there is no value in the array
                    // yet we don't want to display default value in this case.
                    if ($caller->isSubmitted() && !$caller->is_new_repeat($this->getName())) {
                        $value = $this->_findValue($caller->_submitValues);
                    } else {
                        $value = $this->_findValue($caller->_defaultValues);
                    }
                }
                $requestvalue = $value;
                if ($value == 0 || $value === '') {
                    $value = $this->_options['defaulttime'];
                    if (!$value) {
                        $value = time();
                    }
                }
                if (!is_array($value)) {
                    $calendartype = type_factory::get_calendar_instance();
                    $currentdate = $calendartype->timestamp_to_date_array($value, $this->_options['timezone'] ?? 0);
                    // Round minutes to the previous multiple of step.
                    $currentdate['minutes'] -= $currentdate['minutes'] % $this->_options['step'];
                    $value = [
                        'minute' => $currentdate['minutes'],
                        'hour' => $currentdate['hours'],
                    ];
                    // If optional, default to off, unless a date was provided.
                    if ($this->_options['optional']) {
                        $value['enabled'] = $requestvalue != 0;
                    }
                } else {
                    $value['enabled'] = isset($value['enabled']);
                }
                if (null !== $value) {
                    $this->setValue($value);
                }
                break;

            case 'createElement':
                if (isset($arg[2]['optional']) && $arg[2]['optional']) {
                    // When using the function addElement, rather than createElement, we still
                    // enter this case, making this check necessary.
                    if ($this->_usedcreateelement) {
                        $caller->disabledIf($arg[0] . '[hour]', $arg[0] . '[enabled]');
                        $caller->disabledIf($arg[0] . '[minute]', $arg[0] . '[enabled]');
                    } else {
                        $caller->disabledIf($arg[0], $arg[0] . '[enabled]');
                    }
                }
                return parent::onQuickFormEvent($event, $arg, $caller);
                break;

            case 'addElement':
                $this->_usedcreateelement = false;
                return parent::onQuickFormEvent($event, $arg, $caller);
                break;

            default:
                return parent::onQuickFormEvent($event, $arg, $caller);
        }
    }

    /**
     * Returns HTML for advchecbox form element.
     *
     * @return string
     */
    function toHtml() {
        include_once('HTML/QuickForm/Renderer/Default.php');
        $renderer = new HTML_QuickForm_Renderer_Default();
        $renderer->setElementTemplate('{element}');
        parent::accept($renderer);

        $html = $this->_wrap[0];
        if ($this->_usedcreateelement) {
            $html .= html_writer::tag('span', $renderer->toHtml(), ['class' => 'ftime_selector mr-2']);
        } else {
            $html .= $renderer->toHtml();
        }
        $html .= $this->_wrap[1];

        return $html;
    }

    /**
     * Accepts a renderer
     *
     * @param HTML_QuickForm_Renderer $renderer An HTML_QuickForm_Renderer object
     * @param bool $required Whether a group is required
     * @param string $error An error message associated with a group
     */
    function accept(&$renderer, $required = false, $error = null) {
        $renderer->renderElement($this, $required, $error);
    }

    /**
     * Export for template
     *
     * @param renderer_base $output
     * @return array|stdClass
     */
    public function export_for_template(renderer_base $output) {
        return parent::export_for_template($output);
    }

    /**
     * Output a timestamp. Give it the name of the group.
     *
     * @param array $submitValues values submitted.
     * @param bool $assoc specifies if returned array is associative
     * @return array
     */
    function exportValue(&$submitValues, $assoc = false) {

        $valuearray = [];

        foreach ($this->_elements as $element) {
            $thisexport = $element->exportValue($submitValues[$this->getName()], true);
            if ($thisexport != null) {
                $valuearray += $thisexport;
            }
        }
        if (count($valuearray)) {
            if ($this->_options['optional']) {
                // If checkbox is on, the value is zero, so go no further
                if(empty($valuearray['enabled'])) {
                    return $this->_prepareValue(0, $assoc);
                }
            }
            $hoursecs = (int) $valuearray['hour'] * (int) HOURSECS;
            $minsecs = (int) $valuearray['minute'] * (int) MINSECS;
            $value = ($this->_options['timestampfrom'] ?? 0) + $minsecs + $hoursecs;

            return $this->_prepareValue($value, $assoc);
        } else {
            return null;
        }
    }

    /**
     * in Moodle we must add this.
     * Stores the form this element was added to
     * This object is later used by {@link MoogwaiQuickForm_group::createElement()}
     * @param null|MoogwaiQuickForm $mform
     */
    public function setMoogwaiForm($mform) {
        if ($mform && $mform instanceof MoogwaiQuickForm) {
            $this->_mform = $mform;
        }
    }
}

MoodleQuickForm::registerElementType('time_selector', $CFG->dirroot.'/local/aplcore/classes/moogwai/form/elements/time_selector.class.php', '\\local_aplcore\\moogwai\\form\\elements\\time_selector');
