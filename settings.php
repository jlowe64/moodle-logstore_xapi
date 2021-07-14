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

require_once(__DIR__ . '/src/autoload.php');

if ($hassiteconfig) {
    // Endpoint.
    $settings->add(new admin_setting_configtext('logstore_xapi/endpoint',
        get_string('endpoint', 'logstore_xapi'), '',
        '', PARAM_URL));
    // Username.
    $settings->add(new admin_setting_configtext('logstore_xapi/username',
        get_string('username', 'logstore_xapi'), '', '', PARAM_TEXT));
    // Key or password.
    $settings->add(new admin_setting_configtext('logstore_xapi/password',
        get_string('password', 'logstore_xapi'), '', '', PARAM_TEXT));

    // Switch background batch mode on.
    $settings->add(new admin_setting_configcheckbox('logstore_xapi/backgroundmode',
        get_string('backgroundmode', 'logstore_xapi'),
        get_string('backgroundmode_desc', 'logstore_xapi'), 1));

    $settings->add(new admin_setting_configtext('logstore_xapi/maxbatchsize',
        get_string('maxbatchsize', 'logstore_xapi'),
        get_string('maxbatchsize_desc', 'logstore_xapi'), 30, PARAM_INT));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/resendfailedbatches',
        get_string('resendfailedbatches', 'logstore_xapi'),
        get_string('resendfailedbatches_desc', 'logstore_xapi'), 0));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/mbox',
        get_string('mbox', 'logstore_xapi'),
        get_string('mbox_desc', 'logstore_xapi'), 0));
		
	// optional hashing of email address
    $settings->add(new admin_setting_configcheckbox('logstore_xapi/hashmbox',
        get_string('hashmbox', 'logstore_xapi'),
        get_string('hashmbox_desc', 'logstore_xapi'), 1));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/shortcourseid',
        get_string('shortcourseid', 'logstore_xapi'),
        get_string('shortcourseid_desc', 'logstore_xapi'), 0));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/sendidnumber',
        get_string('sendidnumber', 'logstore_xapi'),
        get_string('sendidnumber_desc', 'logstore_xapi'), 0));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/send_username',
        get_string('send_username', 'logstore_xapi'),
        get_string('send_username_desc', 'logstore_xapi'), 0));
		
	$settings->add(new admin_setting_configcheckbox('logstore_xapi/send_name',
        get_string('send_name', 'logstore_xapi'),
        get_string('send_name_desc', 'logstore_xapi'), 1));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/send_jisc_data',
        get_string('send_jisc_data', 'logstore_xapi'),
        get_string('send_jisc_data_desc', 'logstore_xapi'), 0));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/sendresponsechoices',
       get_string('send_response_choices', 'logstore_xapi'),
       get_string('send_response_choices_desc', 'logstore_xapi'), 0));

    // Filters.
    $settings->add(new admin_setting_heading('filters',
        get_string('filters', 'logstore_xapi'),
        get_string('filters_help', 'logstore_xapi')));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/logguests',
        get_string('logguests', 'logstore_xapi'), '', '0'));

    $menuroutes = [];
    $eventfunctionmap = \src\transformer\get_event_function_map();
    foreach (array_keys($eventfunctionmap) as $eventname) {
        $menuroutes[$eventname] = $eventname;
    }

    $settings->add(new admin_setting_configmulticheckbox('logstore_xapi/routes',
        get_string('routes', 'logstore_xapi'), '', $menuroutes, $menuroutes));

}
