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

// jshint unused: true, undef:true

/**
 * Generic version of pro.js, able to handle any component needing
 * license-key registration, instead of being hardcoded to local_aplcore.
 *
 * init() can be called once per plugin (e.g. once from each plugin's
 * settings.php), and repeated calls for the same plugin are idempotent:
 * the change handler is only ever bound once per plugin, and the license
 * icon state is tracked independently for each plugin so several plugins
 * can be checked on the same page (e.g. an admin search results page)
 * without interfering with one another.
 */
define(['jquery', 'core/log', 'core/config'], function($, log, cfg) {

    // Registry of plugins already initialised on this page, keyed by
    // shortcomponent. Because an AMD module is a singleton cached by the
    // JS loader, this object survives across multiple init() calls for
    // the same page load, which is what guarantees the idempotency.
    var initialisedplugins = {};

    var licensecheck = {
        /**
         * Initialise the license key watcher for a given plugin.
         *
         * Safe to call more than once for the same plugin (e.g. if the
         * settings fragment is re-rendered): subsequent calls are no-ops.
         *
         * @param {String} component Frankenstyle name, e.g. 'local_aplcore'.
         * @param {String} shortcomponent Short name used in the form field
         *                                ids, e.g. 'aplcore'.
         * @param {String} componentpath Path fragment used to build the
         *                                ajax services.php URL, e.g.
         *                                'local/aplcore'.
         */
        init: function(component, shortcomponent, componentpath) {
            if (initialisedplugins[shortcomponent]) {
                log.debug('AMD Pro js already initialized for ' + component + ', skipping.');
                return;
            }
            initialisedplugins[shortcomponent] = true;

            var licensekeyid = '#id_s_' + shortcomponent + '_licensekey';

            // Namespaced event (".licensecheck"), so that if the same DOM
            // node were ever rebound outside of this registry, jQuery
            // would still only keep one handler of this kind per element.
            $(licensekeyid).off('change.licensecheck').on('change.licensecheck', function() {
                licensecheck.checkproductkey(component, shortcomponent, componentpath, $(this));
            });
            $(licensekeyid).trigger('change.licensecheck');

            log.debug('AMD Pro js initialized for ' + component + ' system');
        },

        /**
         * Validate the checksum of the entered key and, if valid, ask the
         * server to check/set the license, updating the icon next to the
         * field for this specific plugin only.
         *
         * @param {String} component
         * @param {String} shortcomponent
         * @param {String} componentpath
         * @param {jQuery} $field The license key input for this plugin.
         */
        checkproductkey: function(component, shortcomponent, componentpath, $field) {
            var licensekeyid = '#id_s_' + shortcomponent + '_licensekey';

            var productkey = $field.val().replace(/-/g, '');
            var payload = productkey.substr(0, 14);
            var crc = productkey.substr(14, 2);

            var calculated = licensecheck.checksum(payload);

            var validicon = ' <img class="icon" src="' + cfg.wwwroot + '/pix/i/valid.svg' + '">';
            var cautionicon = ' <img class="icon" src="' + cfg.wwwroot + '/pix/i/warning.svg' + '">';
            var invalidicon = ' <img class="icon" src="' + cfg.wwwroot + '/pix/i/invalid.svg' + '">';
            var waiticon = ' <img class="icon" src="' + cfg.wwwroot + '/pix/i/loading.svg' + '">';

            // Scoped to this plugin's own field, so concurrent checks for
            // other plugins on the same page never touch each other's icon.
            var setIcon = function(icon) {
                $(licensekeyid + ' + img').remove();
                $(licensekeyid).after(icon);
            };

            if (crc === calculated) {
                var url = cfg.wwwroot + '/local/aplcore/pro/ajax/services.php?';
                url += 'what=license';
                url += '&component=' + component;
                url += '&service=check';
                url += '&customerkey=' + $field.val();
                url += '&provider=' + $('#id_s_' + shortcomponent + '_licenseprovider').val();

                setIcon(waiticon);

                $.get(url, function(data) {
                    if (data.match(/(SET|CHECK) OK/)) {
                        if (data.match(/-\d+.*$/)) {
                            setIcon(cautionicon);
                        } else {
                            setIcon(validicon);
                        }
                    } else {
                        setIcon(invalidicon);
                    }
                }, 'html');
            } else {
                setIcon(cautionicon);
            }
        },

        /**
         * Calculates a checksum on 2 chars.
         * @param {string} keypayload
         */
        checksum: function(keypayload) {
            var crcrange = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var crcrangearr = crcrange.split('');
            var crccount = crcrangearr.length;
            var chars = keypayload.split('');
            var crc = 0;

            for (var ch in chars) {
                var ord = chars[ch].charCodeAt(0);
                crc += ord;
            }

            var crc2 = Math.floor(crc / crccount) % crccount;
            var crc1 = crc % crccount;
            return '' + crcrangearr[crc1] + crcrangearr[crc2];
        }
    };

    return licensecheck;
});