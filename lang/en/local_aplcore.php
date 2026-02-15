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
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2014 onwards Valery Fremaux (https://www.activeprolearn.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Privacy
$string['privacy:metadata'] = 'The Local VFLibs plugin does not store any personal data about any user.';

$string['pluginname'] = 'Extra core add-ons VF plugins';

$string['errornokeygenerated'] = 'Error : No key generated';
$string['erroremptypartnerkey'] = 'Distributor/partner key is empty';
$string['erroremptyprovider'] = 'Provider is empty';
$string['errornodistributorkey'] = 'No distributor key provided';
$string['errornooptions'] = 'Error : No activation options found.';
$string['options'] = 'Activation options';
$string['start'] = 'Distributor identification';
$string['continue'] = 'Continue';
$string['activate'] = 'Activate';
$string['chooseoption'] = 'Choose an option...';
$string['provider_help'] = 'Support provider ID. This ID identifies the support provider provinding level3 support and mid/long term continuity warranty.';
$string['partnerkey_help'] = 'The partner key has been given to the technical staff responsible of the plugin\'s installation and activation.';
$string['activationoption_help'] = 'This plugin may have several activation options such as license duration, renew product, etc. Choose the best fit to your situation.';
$string['activationoption'] = 'Activation option';
$string['emulatecommunity'] = '<a name="getsupportlicense"></a>Emulate the community version.';
$string['emulatecommunity_desc'] = 'Switches the code to the community version. The result will be more compatible, but some features will not be available anymore.';
$string['getlicensekey'] = 'Get support license key';
$string['getlicensekey_desc'] = 'In some case, integrators (or administrators) can self-register the support license of the pro part of this plugin.
<br><a href="{$a}">Goto register form</a>';
$string['licensestatus'] = 'Pro license status';
$string['licensekey'] = 'Pro license key';
$string['licensekey_desc'] = 'Input here the product license key you got from your provider';
$string['licenseprovider'] = 'Pro License provider';
$string['licenseprovider_desc'] = 'Input here your provider key';
$string['partnerkey'] = 'Distributor Partner Key';
$string['provider'] = 'Support provider';
$string['specificprosettings'] = 'Specific pro settings';
$string['errorjson'] = 'Error : Json response was empty or not parsable.';
$string['errorresponse'] = 'Error : Provider response is valid but remote error : {$a}';
$string['noproaccess'] = 'This is the "pro" zone. "Pro" zone is NOT activated.';

include(__DIR__.'/pro_additional_strings.php');