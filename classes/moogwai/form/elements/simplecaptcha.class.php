<?php
// This file is part of Moogwai - private project

namespace local_vflibs\moogwai\form\elements;

require_once("HTML/QuickForm/text.php");

use HTML_QuickForm_input;
use local_vflibs\core\output\templatable;
use core\output\renderer_base;
use MoodleQuickForm;

/**
 * simplecaptcha type form element
 *
 * HTML class for a simple captcha type element.
 * This element will NOT rely on Google's recaptcha.
 *
 * @package   core_form
 */
class simplecaptcha extends HTML_QuickForm_input implements templatable {

    use templatable_form_element {
        export_for_template as export_for_template_base;
    }

    /** @var string html for help button, if empty then no help */
    var $_helpbutton = '';

    /**
     * constructor
     *
     * @param string $elementName (optional) name of the simmple captcha element
     * @param string $elementLabel (optional) label for simple captcha element
     * @param mixed $attributes (optional) Either a typical HTML attribute string
     *              or an associative array
     */
    public function __construct($elementName = null, $elementLabel = null, $attributes = null) {
        parent::__construct($elementName, $elementLabel, $attributes);
        $this->_type = 'simplecaptcha';
    }

    /**
     * Returns the reCAPTCHA element in HTML
     *
     * @return string The HTML to render
     */
    public function toHtml() {
        global $CFG;
        require_once($CFG->dirroot.'/lib/simplecaptchalib.php');

        $size = $this->getAttribute('size') ?? $CFG->simplecaptchastrength ?? 5;

        $html = '<div class="d-flex">';
        $html .= '<div class="row  align-items-center">';
        $html .= '<div class="col" id="'.$this->getName().'-challenge">'.simplecaptcha_get_challenge_html().'</div>';
        $html .= '<div class="col"><input class="form-control" type="text" name="'.$this->getName().'" size="'.$size.'"></div>';
        $html .= '<div class="col"><button class="btn btn-secondary simplecaptcha-updater" id="'.$this->getName().'-reload">'.get_string('reload').'</button></div>';
        $html .= '</div>';
        $html .= '</div>';
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
     * Checks captcha response.
     *
     * @param string $responsestr
     * @return bool
     */
    public function verify($responsestr) {
        global $CFG;
        require_once($CFG->dirroot.'/local/vflibs/simplecaptchalib.php');

        $response = simplecaptcha_check_response($responsestr);
        if (!$response['isvalid']) {
            $attributes = $this->getAttributes();
            $attributes['error_message'] = $response['error'];
            $this->setAttributes($attributes);
            return $response['error'];
        }
        return true;
    }

    public function export_for_template(renderer_base $output) {
        $context = $this->export_for_template_base($output);
        $context['html'] = $this->toHtml();
        return $context;
    }

    /**
     * Get force LTR option.
     *
     * @return bool
     */
    public function get_force_ltr() {
        return true;
    }

}

MoodleQuickForm::registerElementType('simplecaptcha', $CFG->dirroot.'/local/vflibs/classes/moogwai/form/elements/simplecaptcha.class.php', '\\local_vflibs\\moogwai\\form\\elements\\simplecaptcha');
