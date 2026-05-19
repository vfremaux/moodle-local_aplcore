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

// jshint undef:false, unused:false, scripturl:true, camelcase:false

/**
 * JavaScript for the course selectors.
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {call as fetchMany} from 'core/ajax';

const searchForCourses = (
    selectorid,
    search,
    searchanywhere,
) => fetchMany([{
    methodname: 'local_aplcore_coursesearch',
    args: {
        selectorid,
        search,
        searchanywhere,
    },
}])[0];

define(['jquery', 'core/str'], function($, corestr) {

    /**
     * Retrieves an instantiated course selector or null if there isn't one by the requested name.
     * @param {string} name The name of the selector to retrieve
     * @return {object|null}
     */
    var course_selectors = [];

    var get_course_selector = function(name) {
        return course_selectors[name] || null;
    };

    /**
     * Initialise a new course selector.
     *
     * @param {string} name the control name/id.
     * @param {string} hash the hash that identifies this selector in the course's session.
     * @param {array} extrafields extra fields we are displaying for each course in addition to fullname.
     * @param {string} lastsearch The last search that took place
     * @return {object} the course_selector object
     */
    var init_course_selector = function(name, hash, extrafields, lastsearch) {

        // Creates a new course_selector object.
        var course_selector = {

            /** This id/name used for this control in the HTML. */
            name: name,

            /** Array of fields to display for each course, in addition to fullname. */
            extrafields: extrafields,

            /** Number of seconds to delay before submitting a query request */
            querydelay: 0.5,

            /** The input element that contains the search term. */
            searchfield: $('#' + name + '_searchtext'),

            /** The clear button. */
            clearbutton: null,

            /** The select element that contains the list of courses. */
            listbox: $('#' + name),

            /** Used to hold the timeout id of the timeout that waits before doing a search. */
            timeoutid: null,

            /** Stores any in-progress remote requests. */
            iotransactions: {},

            /** The last string that we searched for, so we can avoid unnecessary repeat searches. */
            lastsearch: lastsearch,

            /**
             * Whether any options where selected last time we checked. Used by
             *  handle_selection_change to track when this status changes.
             */
            selectionempty: true,

            /**
             * Custom event callbacks (replaces Y.EventTarget).
             */
            _eventCallbacks: {},

            /**
             * Initialises the course selector object
             * @constructor
             */
            init: function() {
                var self = this;

                // Hide the search button and replace it with a label.
                var searchbutton = $('#' + this.name + '_searchbutton');
                var label = $('<label for="' + this.name + '_searchtext">' + searchbutton.val() + '</label>');
                this.searchfield.before(label);
                searchbutton.remove();

                // Hook up the event handler for when the search text changes.
                this.searchfield.on('keyup', function(e) {
                    self.handle_keyup(e);
                });

                // Hook up the event handler for when the selection changes.
                this.listbox.on('keyup', function() {
                    self.handle_selection_change();
                });
                this.listbox.on('click', function() {
                    self.handle_selection_change();
                });
                this.listbox.on('change', function() {
                    self.handle_selection_change();
                });

                // And when the search any substring preference changes. Do an immediate re-search.
                $('#courseselector_searchanywhereid').on('click', function() {
                    self.handle_searchanywhere_change();
                });

                // Define our custom event.
                this.selectionempty = this.is_selection_empty();

                // Replace the Clear submit button with a clone that is not a submit button.
                var clearbtn = $('#' + this.name + '_clearbutton');
                this.clearbutton = $('<input type="button" value="' + clearbtn.val() + '" />');
                this.clearbutton.attr('id', this.name + '_clearbutton');
                clearbtn.replaceWith(this.clearbutton);
                this.clearbutton.on('click', function() { self.handle_clear(); });
                this.clearbutton.prop('disabled', (this.get_search_text() === ''));

                this.send_query(false);
            },

            /**
             * Registers a custom event handler (replaces Y.EventTarget).
             * @param {string} eventName
             * @param {function} callback
             */
            on: function(eventName, callback) {
                if (!this._eventCallbacks[eventName]) {
                    this._eventCallbacks[eventName] = [];
                }
                this._eventCallbacks[eventName].push(callback);
            },

            /**
             * Fires a custom event (replaces Y.EventTarget).
             * @param {string} eventName
             */
            fire: function(eventName) {
                var args = Array.prototype.slice.call(arguments, 1);
                var callbacks = this._eventCallbacks[eventName] || [];
                for (var i = 0; i < callbacks.length; i++) {
                    callbacks[i].apply(this, args);
                }
            },

            /**
             * Key up hander for the search text box.
             * @param {jQuery.Event} e the keyup event.
             */
            handle_keyup: function(e) {
                var self = this;
                // Trigger an ajax search after a delay.
                this.cancel_timeout();
                this.timeoutid = setTimeout(function() { self.send_query(false); }, self.querydelay * 1000);

                // Enable or disable the clear button.
                this.clearbutton.prop('disabled', (this.get_search_text() === ''));

                // If enter was pressed, prevent a form submission from happening.
                if (e.which === 13 || e.keyCode === 13) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            },

            /**
             * Handles when the selection has changed. If the selection has changed from
             * empty to not-empty, or vice versa, then fire the event handlers.
             */
            handle_selection_change: function() {
                var isselectionempty = this.is_selection_empty();
                if (isselectionempty !== this.selectionempty) {
                    this.fire('course_selector:selectionchanged', isselectionempty);
                }
                this.selectionempty = isselectionempty;
            },

            /**
             * Trigger a re-search when the 'search any substring' option is changed.
             */
            handle_searchanywhere_change: function() {
                if (this.lastsearch !== '' && this.get_search_text() !== '') {
                    this.send_query(true);
                }
            },

            /**
             * Click handler for the clear button.
             */
            handle_clear: function() {
                this.searchfield.val('');
                this.clearbutton.prop('disabled', true);
                this.send_query(false);
            },

            /**
             * Fires off the ajax search request.
             */
            send_query: function(forceresearch) {
                var self = this;
                // Cancel any pending timeout.
                this.cancel_timeout();

                var value = this.get_search_text();
                this.searchfield.removeClass().addClass('');
                if (this.lastsearch == value && !forceresearch) {
                    return;
                }

                // Try to cancel existing transactions.
                $.each(this.iotransactions, function(id, xhr) {
                    xhr.abort();
                });
                this.iotransactions = {};

                var xhr = async() => {
                    const response = await searchForCourses(selectorid, search, this.get_option('searchanywhere') ? 1 : 0);
                    try {
                        this.listbox.css('background', '');
                        var data = JSON.parse(response.responseText);
                        if (data.error) {
                            this.searchfield.addClass('error');
                            return new M.core.ajaxException(data);
                        }
                        this.output_options(data);
                    } catch (e) {
                        this.searchfield.addClass('error');
                        return new M.core.exception(e);
                    };
                };
                // Store all asyncs so we can clear pending when searching again.
                this.iotransactions[xhr.id] = xhr;

                this.lastsearch = value;
                this.listbox.css('background', 'url(' + M.util.image_url('i/loading', 'moodle') + ') no-repeat center center');
            },

            /**
             * This method should do the same sort of thing as the PHP method
             * course_selector_base::output_options.
             * @param {object} data the list of courses to populate the list box with.
             */
            output_options: function(data) {
                // Clear out the existing options, keeping any ones that are already selected.
                var selectedcourses = {};
                this.listbox.find('optgroup').each(function() {
                    $(this).find('option').each(function() {
                        if ($(this).prop('selected')) {
                            selectedcourses[$(this).val()] = {
                                id : $(this).val(),
                                name : $(this).text(),
                                disabled: $(this).prop('disabled')
                            };
                        }
                        $(this).remove();
                    });
                    $(this).remove();
                });

                // Output each optgroup.
                var count = 0;
                var searchstr;
                for (var key in data.results) {
                    var groupdata = data.results[key];
                    this.output_group(groupdata.name, groupdata.courses, selectedcourses, true);
                    count++;
                }
                if (!count) {
                    if (this.lastsearch !== '') {
                        corestr.get_string('nomatchingcourses', 'local_aplcore').then(function(str) {
                            searchstr = this.insert_search_into_str(str, this.lastsearch);
                        });
                    } else {
                        searchstr = await corestr.get_string('none', 'local_aplcore');
                    }
                    this.output_group(searchstr, {}, selectedcourses, true);
                }

                // If there were previously selected courses who do not match the search, show them too.
                if (this.get_option('preserveselected') && selectedcourses) {
                    corestr.get_string('previouslyselectedcourses', 'local_aplcore').then(function(str) {
                        var searchstr = this.insert_search_into_str(str, this.lastsearch);
                        this.output_group(searchstr, selectedcourses, true, false);
                    });
                }
                this.handle_selection_change();
            },

            /**
             * This method should do the same sort of thing as the PHP method
             * course_selector_base::output_optgroup.
             *
             * @param {string} groupname the label for this optgroup.
             * @param {object} courses the courses to put in this optgroup.
             * @param {boolean|object} selectedcourses if true, select the courses in this group.
             * @param {boolean} processsingle
             */
            output_group: function(groupname, courses, selectedcourses, processsingle) {
                var optgroup = $('<optgroup></optgroup>');
                var count = 0;
                var option;
                for (var key in courses) {
                    var course = courses[key];
                    option = $('<option value="' + course.id + '">' + course.name + '</option>');
                    if (course.disabled) {
                        option.prop('disabled', true);
                    } else if (selectedcourses === true || selectedcourses[course.id]) {
                        option.prop('selected', true);
                        delete selectedcourses[course.id];
                    } else {
                        option.prop('selected', false);
                    }
                    optgroup.append(option);
                    if (course.infobelow) {
                        var extraoption = $('<option disabled="disabled" class="courseselector-infobelow"></option>');
                        extraoption.append(document.createTextNode(course.infobelow));
                        optgroup.append(extraoption);
                    }
                    count++;
                }

                if (count > 0) {
                    optgroup.attr('label', groupname + ' (' + count + ')');
                    if (processsingle && count === 1 && this.get_option('autoselectunique') && !option.prop('disabled')) {
                        option.prop('selected', true);
                    }
                } else {
                    optgroup.attr('label', groupname);
                    optgroup.append($('<option disabled="disabled">\u00A0</option>'));
                }
                this.listbox.append(optgroup);
            },

            /**
             * Replace a search term placeholder in a string.
             * @param {string} str
             * @param {string} search The search term
             * @return {string}
             */
            insert_search_into_str: function(str, search) {
                return str.replace("%%SEARCHTERM%%", search);
            },

            /**
             * Gets the search text
             * @return {string} the value to search for, with leading and trailing whitespace trimmed.
             */
            get_search_text: function() {
                return this.searchfield.val().toString().replace(/^ +| +$/, '');
            },

            /**
             * Returns true if the selection is empty (nothing is selected)
             * @return {boolean} check all the options and return whether any are selected.
             */
            is_selection_empty: function() {
                return this.listbox.find('option:selected').length === 0;
            },

            /**
             * Cancel the search delay timeout, if there is one.
             */
            cancel_timeout: function() {
                if (this.timeoutid) {
                    clearTimeout(this.timeoutid);
                    this.timeoutid = null;
                }
            },

            /**
             * @param {string} name The name of the option to retrieve
             * @return the value of one of the option checkboxes.
             */
            get_option: function(name) {
                var checkbox = $('#courseselector_' + name + 'id');
                if (checkbox.length) {
                    return checkbox.prop('checked');
                } else {
                    return false;
                }
            }
        };

        // Initialise the course selector.
        course_selector.init();
        // Store the course selector so that it can be retrieved.
        course_selectors[name] = course_selector;
        // Return the course selector.
        return course_selector;
    };

    /**
     * Initialise a class that updates the course's preferences when they change one of
     * the options checkboxes.
     * @constructor
     * @return {object} Tracker object
     */
    var init_course_selector_options_tracker = function() {
        // Create a course selector options tracker.
        var course_selector_options_tracker = {
            /**
             * Initialises the option tracker and gets everything going.
             * @constructor
             */
            init: function() {
                var self = this;
                var settings = [
                    'courseselector_preserveselected',
                    'courseselector_autoselectunique',
                    'courseselector_searchanywhere'
                ];
                for (var s in settings) {
                    var setting = settings[s];
                    (function(settingName) {
                        $('#' + settingName + 'id').on('click', function(e) {
                            self.set_course_preference(e, settingName);
                        });
                    })(setting);
                }
            },

            /**
             * Sets a course preference for the options tracker
             * @param {jQuery.Event|null} e
             * @param {string} name The name of the preference to set
             */
            set_course_preference: function(e, name) {
                M.util.set_course_preference(name, $('#' + name + 'id').prop('checked'));
            }
        };
        // Initialise the options tracker.
        course_selector_options_tracker.init();
        // Return it just in case it is ever wanted.
        return course_selector_options_tracker;
    };

    return {
        course_selectors: course_selectors,
        get_course_selector: get_course_selector,
        init_course_selector: init_course_selector,
        init_course_selector_options_tracker: init_course_selector_options_tracker
    };

});