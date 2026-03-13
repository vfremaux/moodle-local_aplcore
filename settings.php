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
 * Global settings
 *
 * @package   local_aplcore
 * @author    Valery Fremaux (valery.fremaux@gmail.com)
 * @copyright 2026 Valery Fremaux (www.activeprolearn.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Needs this condition or there is error on login page.
    $settings = new admin_settingpage('local_aplcore', get_string('pluginname', 'local_aplcore'));
    $ADMIN->add('localplugins', $settings);

    $key = 'local_aplcore/docbaseurl';
    $label = get_string('configdocbaseurl', 'local_aplcore');
    $desc = get_string('configdocbaseurl_desc', 'local_aplcore');
    $settings->add(new admin_setting_configtext($key, $label, $desc, ''));

    $key = 'local_vflibs/doccustomerid';
    $label = get_string('configdoccustomerid', 'local_aplcore');
    $desc = get_string('configdoccustomerid_desc', 'local_aplcore');
    $settings->add(new admin_setting_configtext($key, $label, $desc, ''));

    $key = 'local_vflibs/doccustomerpublickey';
    $label = get_string('configdoccustomerpublickey', 'local_aplcore');
    $desc = get_string('configdoccustomerpublickey_desc', 'local_aplcore');
    $settings->add(new admin_setting_configtextarea($key, $label, $desc, ''));

    $key = 'local_aplcore/editorplugins';
    $label = get_string('configeditorplugins', 'local_aplcore');
    $desc = get_string('configeditorplugins_desc', 'local_aplcore');
    $defaultlist = "mod_sharedresource,local_sharedresources,repository_sharedresources,block_sharedresources,
mod_certificate,mod_learningtimecheck,report_learningtimecheck,block_use_stats,report_trainingsessions,block_dashboard,
format_page,mod_customlabel,#block_page_module,block_page_tracker,mod_pagemenu,
enrol_autoenrol,enrol_profilefield,enrol_delayedcohorts
local_technicalsignals,tool_backupmanager,tool_filecheck,tool_cronmonitor,tool_moodlescript
block_vmoodle,report_vmoodle,block_publishflow,block_user_mnet_hosts,
block_shop,block_shop_bills,block_shop_products,block_shop_total,auth_ticket,block_my_certificates,
mod_mplayer,mod_wowslider,#mod_richmedia,block_livedesk,
block_data_behaviour,block_quiz_behaviour,block_groupspecifichtml,block_rolespecifichtml,block_profilespecifichtml,
block_profileselectorhtml,block_o365_links,block_userquiz_monitor,block_userquiz_limits
";
    $settings->add(new admin_setting_configtextarea($key, $label, $desc, $defaultlist));
}
