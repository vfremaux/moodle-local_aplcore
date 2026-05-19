<?php
/**
 * This is a new element type for HTML_QuickForm which provides an "element to choose" description
 * with an outgoing link to add an instance of it in the context.
 *
 * @category HTML
 * @package  HTML_QuickForm_ElementChooser
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL
 * @author   Valery Fremaux <valery.fremaux@gmail.com>
 */

require_once 'HTML/QuickForm/element.php';

/**
 * An HTML_QuickForm element displaying a generic "element chooser" description
 * with an outgoing link to add it to context.
 */
class HTML_QuickForm_ElementChooser extends HTML_QuickForm_element {

    /**
     * An url for going out to instanciation circuit.
     * Will be computed with component info.
     *
     * @var url.
     */
    private $_outgoingurl = null;

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
     * Several addition control options
     */
    var $_options = [];

    /**
     * Constructor
     *
     * @param string $name for the element
     * @param string $component the component to add
     * @param array $options other options (pixurl, section, beforemod, pageid, sr, etc.)
     */
    public function __construct($name = null, $component = null, $options = null) {
        global $COURSE;

        parent::__construct($name, null);
        $this->_type = 'elementchooser';
        // $this->updateAttributes(['class' => 'elementChooser']);
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
        global $COURSE;

        switch ($event) {

            case 'createElement': {

                [$name, $component, $options, $unused1, $unused2, $unused3] = $arg;

                if (is_array($options)) {
                    $this->_options = array_merge($this->_options, $options);
                }

                // Compute outgoing url.
                $sm = get_string_manager();
                if (strpos($component, 'cm_') === 0) {
                    // This is an existing module instance.
                    $this->_outgoingurl = $options['outgoingurl'];
                    $this->_label = $options['instancename'];
                    $this->_description = '';
                } else if (strpos($component, 'block_') === 0) {
                    $params = [
                        'id' => $COURSE->id,
                        'sesskey' => sesskey(),
                        'bui_addblock' => str_replace('block_', '', $component),
                    ];

                    if (!empty($options['pageid'])) {
                        $params['page'] = $options['pageid'];
                    }

                    // Eventual section return.
                    if (!empty($options['sr'])) {
                        $params['sr'] = $options['sr'];
                    }
                    $this->_outgoingurl = new moodle_url('/course/view.php', $params);
                    $this->_label = get_string('pluginname', $component);
                    if ($sm->string_exists('pluginname_help', $component)) {
                        $this->_description = get_string('pluginname_help', $component);
                    } else if ($sm->string_exists('pluginname_desc', $component)) {
                        $this->_description = get_string('pluginname_desc', $component);
                    } else {
                        $this->_description = '';
                    }
                } else {
                    // This is a module.
                    $this->_label = get_string('pluginname', $component);
                    if ($sm->string_exists('modulename_help', $component)) {
                        $this->_description = get_string('modulename_help', $component);
                    } else {
                        $this->_description = '';
                    }
                    if (strpos($component, 'mod_') === 0) {
                        $component = str_replace('mod_', '', $component);
                    }
                    $params = [
                        'id' => $COURSE->id,
                        'sesskey' => sesskey(),
                        'add' => str_replace('mod_', '', $component),
                    ];
                    $this->_outgoingurl = new moodle_url('/course/mod.php', $params);
                }

                break;
            }

            default:
                parent::onQuickFormEvent($event, $arg, $caller);
        }
        return true;
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
     * Freezes the element
     */
    public function export_for_template() {

        $template = new StdClass;
        $template->name = $this->_name;
        $template->label = $this->_label;
        $template->outgoingurl = $this->_outgoingurl;
        $template->ismodinstance = $this->_options['ismodinstance'] ?? false;
        $template->ismodtype = $this->_options['ismodtype'] ?? false;
        $template->modtype = $this->_options['modtype'] ?? '';
        $template->instancename = $this->_options['instancename'] ?? '';
        $template->instanceurl = $this->_options['instanceurl'] ?? '';
        $template->shortname = $this->_options['shortname'] ?? '';
        $template->description = $this->_options['description'] ?? $this->_description ?? '';
        if (!empty($this->_options['idnumber'])) {
            $template->idnumber = $this->_options['idnumber'];
        }
        if (array_key_exists('pixicon', $this->_options)) {
            $template->pixicon = $this->_options['pixicon'];
        }
        if (!empty($this->_options['subtype'])) {
            $template->subtype = $this->_options['subtype'];
        }
        if (!empty($this->_options['titlelevel'])) {
            $template->titlelevel = $this->_options['titlelevel'];
        } else {
            $template->titlelevel = 2;
        }
        if (empty($this->_options['layout']) || ($this->_options['layout'] == 'card')) {
            $template->iscard = true;
        } else {
            $template->iscard = false;
        }
        return $template;

    }

    /**
     * Freezes the element
     */
    public function freeze() {
    }

    /**
     * Returns Html for the element
     *
     * @access      public
     * @return      string
     */
    public function toHtml() {
        global $OUTPUT;

        return $OUTPUT->render_from_template('local_aplcore/form/elements/element-elementchooser', $this->export_for_template());
    }
}

require_once('HTML/QuickForm.php');
