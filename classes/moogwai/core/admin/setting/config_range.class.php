<?php
// This file is part of Moogwai - Private project

namespace local_aplcore\moogwai\core\admin\setting;

require_once($CFG->dirroot.'/lib/adminlib.php');

/**
 * The user enters value through a slider.
 *
 * This type of field should be used for config settings which are using
 * English words and are not localised (passwords, database name, list of values, ...).
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config_range extends \admin_setting {

    /** @var int default range min */
    public $min;

    /** @var int default range max */
    public $max;

    /** @var int default range step */
    public $step;

    /** @var string default range unit */
    public $unit;

    /** @var array additional atributes */
    public $attributes;

    /** @var array List of arbitrary data attributes */
    protected $datavalues = [];

    /**
     * Config text constructor
     *
     * @param string $name unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param string $defaultsetting
     * @param mixed $paramtype int means PARAM_XXX type, string is a allowed format in regex
     * @param int[float $min range min
     * @param int[float $max range max
     * @param int[float $step range step
     * @param string $unit range unit suffix for stored value.
     * @param array $attributes additional attributes: 'width', style width, 'novalue' : hides value monitor.
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, $paramtype = PARAM_INT, $min = 0, $max = 100, $step = 1, $unit = '', $attributes = []) {
        $this->paramtype = $paramtype;
        $this->min  = $min;
        $this->max  = $max;
        $this->step  = $step;
        $this->unit  = $unit;
        $this->attributes = $attributes;
        parent::__construct($name, $visiblename, $description, $defaultsetting);
    }

    /**
     * Get whether this should be displayed in LTR mode.
     *
     * Try to guess from the PARAM type unless specifically set.
     */
    public function get_force_ltr() {
        $forceltr = parent::get_force_ltr();
        if ($forceltr === null) {
            return !is_rtl_compatible($this->paramtype);
        }
        return $forceltr;
    }

    /**
     * Return the setting
     *
     * @return mixed returns config if successful else null
     */
    public function get_setting() {
        return $this->config_read($this->name);
    }

    public function write_setting($data) {
        if ($this->paramtype === PARAM_INT && $data === '') {
        // do not complain if '' used instead of 0
            $data = 0;
        }

        // $data is a string
        $validated = $this->validate($data);
        if ($validated !== true) {
            return $validated;
        }

        if ($data !== 0) {
            $data .= $this->unit;
        }

        return ($this->config_write($this->name, $data) ? '' : get_string('errorsetting', 'admin'));
    }

    /**
     * Validate data before storage
     * @param string data
     * @return mixed true if ok string if error found
     */
    public function validate($data) {
        // allow paramtype to be a custom regex if it is the form of /pattern/
        if (preg_match('#^/.*/$#', $this->paramtype)) {
            if (preg_match($this->paramtype, $data)) {
                return true;
            } else {
                return get_string('validateerror', 'admin');
            }

        } else if ($this->paramtype === PARAM_RAW) {
            return true;

        } else {
            try {
                $cleaned = clean_param($data, $this->paramtype);
            } catch (coding_exception $ex) {
                throw new coding_exception("Text param error for param {$this->name} : ".$ex->getMessage());
            }
            if ("$data" === "$cleaned") { // implicit conversion to string is needed to do exact comparison
                return true;
            } else {
                return get_string('validateerror', 'admin');
            }
        }
    }

    /**
     * Set arbitrary data attributes for template.
     *
     * @param string $key Attribute key for template.
     * @param string $value Attribute value for template.
     */
    public function set_data_attribute(string $key, string $value): void {
        $this->datavalues[] = [
            'key' => $key,
            'value' => $value,
        ];
    }

    /**
     * Return an XHTML string for the setting
     * @return string Returns an XHTML string
     */
    public function output_html($data, $query = '') {
        global $OUTPUT;

        $default = $this->get_defaultsetting();
        $context = (object) [
            'width' => $this->attributes['width'] ?? '',
            'novalue' => $this->attributes['novalue'] ?? false,
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'id' => $this->get_id(),
            'name' => $this->get_full_name(),
            'value' => str_replace($this->unit, '', $data),
            'forceltr' => $this->get_force_ltr(),
            'readonly' => $this->is_readonly(),
            'data' => $this->datavalues,
        ];
        $element = $OUTPUT->render_from_template('local_aplcore/moogwai/core/admin/setting/config_range', $context);

        return format_admin_setting($this, $this->visiblename, $element, $this->description, true, '', $default, $query);
    }
}
