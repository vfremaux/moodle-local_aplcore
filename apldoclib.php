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
 * Library dedicated do documentation bridges with editor documentation
 *
 * @package local_aplcore
 * @author Valery Fremaux valery.fremaux@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @copyright   2020 Valery Fremaux (https://www.activeprolearn.com)
 */

// Abusive PSR12 rule : adds useless spaces in string concatenation.
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceBefore
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceAfter

/**
 * Make an encrypted ticket to access to documentation.
 */
function local_aplcore_doc_make_ticket() {

    $config = get_config('local_aplcore');

    $ticket = new StdClass();
    $ticket->clientid = $config->doccustomerid ?? 0;
    $ticket->date = time();

    if (!empty($config->doccustomerpublickey)) {
        $res = openssl_get_publickey($config->doccustomerpublickey);
        if (!$res) {
            echo get_string('dockeyfailure', 'local_aplcore');
        }

        $decrypted = json_encode($ticket);
        $res = openssl_public_encrypt($decrypted, $encrypted, $res);

        $encoded = urlencode(base64_encode($encrypted));

        return $encoded;
    }
}

/**
 * Computes a specific documentation links for a plugin.
 * @param string $pluginname
 */
function local_aplcore_make_doc_url($pluginname) {
    global $CFG;

    $config = get_config('local_aplcore');

    if (empty($config->docbaseurl)) {
        // No external doc configured.
        return null;
    }

    $editorplugins = [];
    if (!empty($config->editorplugins)) {
        $pluginlist = str_replace("\n", '', $config->editorplugins);
        $pluginlist = str_replace(' ', '', $pluginlist);
        $editorplugins = explode(',', $pluginlist);
    } else {
        if (!empty($CFG->integratorplugins)) {
            $pluginlist = str_replace("\n", '', $CFG->integratorplugins);
            $pluginlist = str_replace(' ', '', $pluginlist);
            $editorplugins = explode(',', $CFG->integratorplugins);
        }
    }

    if (strpos($pluginname, '_') === false) {
        // Normalize name.
        $pluginname = 'mod_'.$pluginname;
    }

    if (!in_array($pluginname, $editorplugins)) {
        return false;
    }

    $ticket = local_aplcore_doc_make_ticket();

    $docbaseurl = str_replace('{lang}', current_language(), $config->docbaseurl);

    // Process plugin name for dokuwikis.
    $pluginnamearr = explode('_', $pluginname);
    $first = array_shift($pluginnamearr);
    $pluginpath = $first.':'.implode('', $pluginnamearr);

    return $docbaseurl.$pluginpath.':userguide&cryptoken='.$ticket;
}
