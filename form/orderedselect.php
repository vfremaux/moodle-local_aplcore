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

defined('MOODLE_INTERNAL') || die();

/**
 * OrderedSelect form element
 *
 * Contains HTML class for an ordered select type element
 *
 * @package   local_vflibs
 * @subpackage  form
 * @copyright 2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!class_exists('MoodleQuickForm_orderedselect')) {
    if (file_exists($CFG->libdir.'/pear/HTML/QuickForm/orderedselect.php')) {
        require_once ("HTML/QuickForm/orderedselect.php");
    } else {
        require_once ($CFG->dirroot."/local/vflibs/form/HTML/QuickForm/orderedselect.php");
    }

/**
 * HTML class for a orderedselect type element
 *
 * Overloaded {@link HTML_QuickForm_select} to add help button
 *
 * @package   local_vflibs
 * @category  form
 * @copyright 2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MoodleQuickForm_orderedselect extends HTML_QuickForm_OrderedSelect {

        /** @var string html for help button, if empty then no help */
        public $_helpbutton = '';

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
        function toHtml(){
            global $PAGE;

            $PAGE->requires->js_call_amd('local_vflibs/orderedselect', 'init');

            return parent::toHtml();
        }
    }

    if (file_exists($CFG->libdir.'/form/orderedselect.php')) {
        MoodleQuickForm::registerElementType('orderedselect', "$CFG->libdir/form/orderedselect.php", 'MoodleQuickForm_orderedselect');
    } else {
        MoodleQuickForm::registerElementType('orderedselect', $CFG->dirroot.'/local/vflibs/form/orderedselect.php', 'MoodleQuickForm_orderedselect');
    }
}