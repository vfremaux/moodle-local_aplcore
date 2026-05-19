<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * ColourPicker form element
 *
 * Contains HTML class for a colourpicker type element
 *
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Abusive PSR12 rule : adds useless spaces in string concatenation.
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceBefore
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceAfter
// phpcs:disable PSR12.Classes.OpeningBraceSpace.Found

defined('MOODLE_INTERNAL') || die();

// These are because this file is a Pear/Quickform cross integration file.
// phpcs:disable moodle.NamingConventions.ValidFunctionName.LowercaseMethod
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

require_once($CFG->dirroot.'/lib/pear/HTML/QuickForm.php');

if (!class_exists('MoodleQuickForm_colourpicker')) {
    require_once($CFG->dirroot."/local/aplcore/form/HTML/QuickForm/colourpicker.php");

    /**
     * HTML class for a colourpicker type element
     *
     * Overloaded to add help button
     *
     * @package     local_aplcore
     * @author      Valery Fremaux <valery.fremaux@gmail.com>
     * @copyright   2020 Valery Fremaux <valery.fremaux@gmail.com>
     * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class MoodleQuickForm_colourpicker extends HTML_QuickForm_ColourPicker {
        /** @var string html for help button, if empty then no help */
        public $_helpbutton = '';

        /**
         * get html for help button
         *
         * @return string html for help button
         */
        public function getHelpButton() {
            return $this->_helpbutton;
        }

        /**
         * Slightly different container template when frozen.
         *
         * @return string
         */
        public function getElementTemplateType() {
            if ($this->_flagFrozen) {
                return 'nodisplay';
            } else {
                return 'default';
            }
        }

        /**
         * Returns Html for the element
         *
         * @return      string
         */
        public function toHtml() {
            global $PAGE, $OUTPUT;

            $str = '<div class="form-colourpicker defaultsnext '.$this->getAttribute('class').'">';
            $str .= '    <div class="admin_colourpicker clearfix">';
            $str .= $OUTPUT->pix_icon('i/loading', get_string('loading', 'admin'), 'moodle', ['class' => 'loadingicon']);
            $str .= '    </div>';
            $attrs = $this->_getAttrString($this->_attributes);
            $str .= '    <input name="'.$this->_name.'" type="text" '.$attrs.' size="12" class="text-ltr">';
            $str .= '</div>';

            $PAGE->requires->js_init_call('M.util.init_colour_picker', [$this->getAttribute('id'), null]);

            return $str;
        }
    }

    $file = $CFG->dirroot.'/local/aplcore/form/colourpicker.php';
    MoodleQuickForm::registerElementType('colourpicker', $file, 'MoodleQuickForm_colourpicker');
}
