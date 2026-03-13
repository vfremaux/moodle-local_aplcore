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
 * Element grid form element
 *
 * Contains HTML class for a grid of other form elements
 *
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// This is because this file is a Pear/Quickform cross integration file.
// phpcs:disable moodle.NamingConventions.ValidFunctionName.LowercaseMethod

if (!class_exists('MoodleQuickForm_elementgrid')) {
    if (file_exists($CFG->libdir.'/pear/HTML/QuickForm/elementgrid.php')) {
        require_once("HTML/QuickForm/elementgrid.php");
    } else {
        require_once($CFG->dirroot."/local/aplcore/HTML/QuickForm/elementgrid.php");
    }

    /**
     * HTML class for a button type element
     *
     * Overloaded {@link HTML_QuickForm_button} to add help button
     *
     * @package     local_aplcore
     * @author      Valery Fremaux <valery.fremaux@gmail.com>
     * @copyright   2020 Valery Fremaux <valery.fremaux@gmail.com>
     * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class MoodleQuickForm_elementgrid extends HTML_QuickForm_elementgrid {

        /**
         * @var string html for help button, if empty then no help.
         */
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

            $table = new html_table();

            $col = 0;
            $header = [];
            if ($this->_columnNames) {
                foreach ($this->_columnNames as $key => $value) {
                    ++$col;
                    $header[] = $value;
                }
            }
            $table->head = $header;

            $data = [];
            foreach (array_keys($this->_rows) as $key) {
                $col = 0;
                $row = [];
                foreach (array_keys($this->_rows[$key]) as $key2) {
                    ++$col;
                    $row[] = $this->_rows[$key][$key2]->toHTML();
                }
                $data[] = $row;
            }
            $table->data = $data;

            return html_writer::table($table);

        }
    }

    if (file_exists($CFG->libdir.'/form/elementgrid.php')) {
        $file = "$CFG->libdir/form/elementgrid.php";
        MoodleQuickForm::registerElementType('elementgrid', $file, 'MoodleQuickForm_elementgrid');
    } else {
        $file = $CFG->dirroot.'/local/aplcore/form/elementgrid.php';
        MoodleQuickForm::registerElementType('elementgrid', $file, 'MoodleQuickForm_elementgrid');
    }
}
