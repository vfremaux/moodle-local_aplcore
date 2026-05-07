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
 * Strings for local_aplcore
 *
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2014 onwards Valery Fremaux (https://www.activeprolearn.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['activate'] = 'Activate';
$string['activationoption'] = 'Activation option';
$string['activationoption_help'] = 'This plugin may have several activation options such as license duration, renew product, etc. Choose the best fit to your situation.';
$string['chooseoption'] = 'Choose an option...';
$string['configdocbaseurl'] = 'Documentation base url';
$string['configdocbaseurl_desc'] = 'Base url for the remote documentation source';
$string['configdoccustomerid'] = 'Documentation customer id';
$string['configdoccustomerid_desc'] = 'Customer key for document access authentication';
$string['configdoccustomerpublickey'] = 'Documentation public key';
$string['configdoccustomerpublickey_desc'] = 'A PEM public key for encrypting the documentation access token. It is given by the documentation provider.';
$string['configeditorplugins'] = 'Editor plugins for extra documentation';
$string['configeditorplugins_desc'] = '';
$string['continue'] = 'Continue';
$string['editname'] = 'Edit name';
$string['emulatecommunity'] = '<a name="getsupportlicense"></a>Emulate the community version.';
$string['emulatecommunity_desc'] = 'Switches the code to the community version. The result will be more compatible, but some features will not be available anymore.';
$string['erroremptypartnerkey'] = 'Distributor/partner key is empty';
$string['erroremptyprovider'] = 'Provider is empty';
$string['errorjson'] = 'Error : Json response was empty or not parsable.';
$string['errornodistributorkey'] = 'No distributor key provided';
$string['errornokeygenerated'] = 'Error : No key generated';
$string['errornooptions'] = 'Error : No activation options found.';
$string['errorresponse'] = 'Error : Provider response is valid but remote error : {$a}';
$string['getlicensekey'] = 'Get support license key';
$string['getlicensekey_desc'] = 'In some case, integrators (or administrators) can self-register the support license of the pro part of this plugin. <br><a href="{$a}">Goto register form</a>';
$string['helponblock'] = 'Help on the block';
$string['helponmodule'] = 'Help on the course module';
$string['licensekey'] = 'Pro license key';
$string['licensekey_desc'] = 'Input here the product license key you got from your provider';
$string['licenseprovider'] = 'Pro License provider';
$string['licenseprovider_desc'] = 'Input here your provider key';
$string['licensestatus'] = 'Pro license status';
$string['nextstep'] = 'Next step';
$string['noproaccess'] = 'This is the "pro" zone. "Pro" zone is NOT activated.';
$string['options'] = 'Activation options';
$string['partnerkey'] = 'Distributor Partner Key';
$string['partnerkey_help'] = 'The partner key has been given to the technical staff responsible of the plugin\'s installation and activation.';
$string['pluginname'] = 'Extra core add-ons APL plugins';
$string['previousstep'] = 'Previous step';
$string['privacy:metadata'] = 'The Local APLCore plugin does not store any personal data about any user.';
$string['provider'] = 'Support provider';
$string['provider_help'] = 'Support provider ID. This ID identifies the support provider provinding level3 support and mid/long term continuity warranty.';
$string['specificprosettings'] = 'Specific pro settings';
$string['start'] = 'Distributor identification';

require(__DIR__.'/pro_additional_strings.php');
