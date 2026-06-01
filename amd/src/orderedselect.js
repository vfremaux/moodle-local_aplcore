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

// jshint undef:false, unused:false, scripturl:true
/* eslint no-undef: "off", no-unused-vars: "off" */

/**
 * Javascript controller for orderedselect.
 *
 * @module     local_aplcore/orderedselect
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/log'], function($, log) {

    var orderedselect = {

        captured: null,

        selectedoptions: [],

        init: function(params) {

            $('.orderedselect-delete').bind('click', this.removeselecteditem);
            $('.orderedselect-select').bind('change', this.updatevalues);
            $('.orderedselect-option').bind('mousedown', this.captureselecteditem);
            $('.orderedselect-option').bind('mousedrag', this.drag);
            $('.orderedselect-option').bind('mouseup', this.releaseselecteditem);

            this.selectedoptions = params.options;

            log.debug("AMD AplCore orderedselect initialized");
        },

        /**
         * Select a new option in the list. Will append to the end of the ordrered list.
         * we assume the calling object is the option element.
         */
        toggleoption: function() {

            var that = $(this);
            var selectobj = that.nearest('select');
            var valueid = '#' + selectobj.attr('id') + '-value';
            var selectionid = '#' + selectobj.attr('id') + '-selection';

            if (iselected) {
                // Add selected div. ( create node)
                $newnode = $(selectionid).appendNode();

                // Add value to optionlist and register the html object.
                // Refresh the hidden input with keys in order.
                selectedoptions[that.attr('value')] = newnode;
                $(valueid).html(orderedselect.selectedoptions.join(','));

            } else {
                // Remove value from option array and hidden input.
                removedoptions = new Array();
                for (opt in selectedoptions) {
                    if (opt != that.attr('value')) {
                        removedoptions[opt] = selectedoptions[opt];
                    }
                }
                $(valueid).html(orderedselect.removedoptions.join(','));

                // Delete selected div. (delete draggable selection node)
                var optionid = selectobj.attr('id') + '-' + that.attr('value');
                $(selectionid).deleteNode(optionid);
            }
        },

        /**
         * Starts dragging
         * @param {Event} e
         */
        captureselecteditem: function(e) {

            e.preventDefault();
            e.stopPropagation();

            var that = $(this);

            orderedselect.captured = that;
            that.css('position', 'absolute');
            that.css('opacity', 0.5);
        },

        /**
         * Move dragged item on screen while it is held
         * @param {Event} e
         */
        dragselecteditem: function(e) {

            e.preventDefault();
            e.stopPropagation();

            var that = $(this);

            if (that.attr('id') === orderedselect.captured) {
                if (orderedselect.captured !== '') {
                    // Reposition.
                    // Test if need to be swapped.
                }
            }
        },

        /**
         * Updates the list and releases the held item.
         * @param {Event} e
         */
        releaseselecteditem: function(e) {

            e.preventDefault();
            e.stopPropagation();

            var that = $(this);

            orderedselect.captured = null;
            that.css('position', '');
            that.css('opacity', 1);
        },

        /**
         * Clicked on the delete icon of the item.
         */
        removeselecteditem: function() {

            var that = $(this);

            var option = that.nearest('orderedselect-option');
            // Delete node, remove value in hidden and unselect the related option in select.
        }
    };

    return orderedselect;

});