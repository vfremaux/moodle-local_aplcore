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

// Abusive rules for langage files. If fits for very simple plugins, does NOT fit for complex
// highly architectured plugins.
// Needed for separating string sections.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

$string['addelement'] = 'Add element';
$string['configdocbaseurl'] = 'Documentation base url';
$string['configdocbaseurl_desc'] = 'Base url for the remote documentation source';
$string['configdoccustomerid'] = 'Documentation customer id';
$string['configdoccustomerid_desc'] = 'Customer key for document access authentication';
$string['configdoccustomerpublickey'] = 'Documentation public key';
$string['configdoccustomerpublickey_desc'] = 'A PEM public key for encrypting the documentation access token. It is given by the documentation provider.';
$string['configeditorplugins'] = 'Editor plugins for extra documentation';
$string['configeditorplugins_desc'] = '';
$string['courseselectorautoselectunique'] = 'Auto select unique result';
$string['courseselectorpreserveselected'] = 'Preserve selection';
$string['courseselectorsearchanywhere'] = 'Search anywhere';
$string['dockeyfailure'] = 'Key for documentation is missing or is not a public key.';
$string['editname'] = 'Edit name';
$string['helponblock'] = 'Help on the block';
$string['helponmodule'] = 'Help on the course module';
$string['less'] = 'More...';
$string['more'] = 'Less...';
$string['nextstep'] = 'Next step';
$string['nomatchingcourses'] = 'No courses matching';
$string['pluginname'] = 'Extra core add-ons APL plugins';
$string['previouslyselectedcourses'] = 'Previously selected courses';
$string['previousstep'] = 'Previous step';
$string['privacy:metadata'] = 'The Local APLCore plugin does not store any personal data about any user.';

// Pro APL String section.
$string['activate'] = 'Activate';
$string['activationoption'] = 'Activation option';
$string['cachedef_pro'] = 'Caches some pro related options and data';
$string['chooseoption'] = 'Choose an option...';
$string['continue'] = 'Continue';
$string['emulatecommunity'] = '<a name="getsupportlicense"></a>Emulate the community version.';
$string['emulatecommunity_desc'] = 'Switches the code to the community version. The result will be more compatible, but some features will not be available anymore.';
$string['erroremptydistributorkey'] = 'Distributor key is empy';
$string['erroremptyprovider'] = 'Provider is empy';
$string['errorjson'] = 'Error : Json response was empty or not parsable.';
$string['errornodistributorkey'] = 'No distributor key provided';
$string['errornokeygenerated'] = 'Error : No key generated';
$string['errornooptions'] = 'Error : No activation options found.';
$string['errorresponse'] = 'Error : Provider response is valid but remote error : {$a}';
$string['getlicensekey'] = 'Get license key';
$string['licensekey'] = 'Pro license key';
$string['licensekey_desc'] = 'Input here the product license key you got from your provider';
$string['licenseprovider'] = 'Pro License provider';
$string['licenseprovider_desc'] = 'Input here your provider key';
$string['licensestatus'] = 'Pro license status';
$string['noproaccess'] = 'This is the "pro" zone. "Pro" zone is NOT activated.';
$string['options'] = 'Activation options';
$string['partnerkey'] = 'Distribution Partner key';
$string['plugindist'] = 'Plugin distribution';
$string['provider'] = 'Support provider';
$string['specificprosettings'] = 'Specific pro settings';
$string['start'] = 'Distributor identification';

$string['plugindist_desc'] = '
<p>This plugin is the community version and is published for anyone to use as is and check the plugin\'s
core application. A "pro" version of this plugin exists and is distributed under conditions to feed the life cycle, upgrade, documentation
and improvement effort.</p>
<p>Please contact one of our distributors to get "Pro" version support.</p>
<p><a href="http://www.mylearningfactory.com/index.php/documentation/Distributeurs?lang=en_utf8">MyLF Distributors</a></p>';

$string['getlicensekey_desc'] = '<a name="getsupportlicense"></a>Partner for distribution can generate a licence key directly by using the following link :
<br><a href="{$a}">Goto licence query form</a>';

$string['provider_help'] = 'Support provider ID. This ID identifies the support provider provinding level3 support and mid/long term continuity warranty.';
$string['partnerkey_help'] = 'The partner key has been given to the technical staff responsible of the plugin\'s installation and activation.';

$string['emptysupportlicensemessage'] = '<div class="licensing">-- Pro licensed version --<br/>This plugin is being used in "pro" version
without support license key for demonstration. It will have limited features.</div>';

$string['emulatecommunity_desc'] = 'If enabled, the plugin will behave as the public community version.
This will increase compatibility with other implementations but will loose features !';

$string['activationoption_help'] = 'This plugin may have several activation options such as license duration, renew product, etc. Choose the best fit to your situation.';
