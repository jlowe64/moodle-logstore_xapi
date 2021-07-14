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

$string['endpoint'] = 'Your LRS endpoint for the xAPI';
$string['settings'] = 'General Settings';
$string['xapifieldset'] = 'Custom example fieldset';
$string['xapi'] = 'xAPI';
$string['password'] = 'Your LRS basic auth secret/password for the xAPI';
$string['pluginadministration'] = 'Logstore xAPI administration';
$string['pluginname'] = 'Logstore xAPI';
$string['submit'] = 'Submit';
$string['username'] = 'Your LRS basic auth key/username for the xAPI';
$string['xapisettingstitle'] = 'Logstore xAPI Settings';
$string['backgroundmode'] = 'Send statements by scheduled task?';
$string['backgroundmode_desc'] = 'This will force Moodle to send the statements to the LRS in the background,
        via a cron task to avoid blocking page responses. This will make the process less close to real time, but will help to prevent unpredictable
        Moodle performance linked to the performance of the LRS.';
$string['maxbatchsize'] = 'Maximum batch size';
$string['maxbatchsize_desc'] = 'Statements are sent to the LRS in batches. This setting controls the maximum number of
        statements that will be sent in a single operation. Setting this to zero will cause all available statements to
        be sent at once, although this is not recommended.';
$string['taskemit'] = 'Emit records to LRS';
$string['routes'] = 'Include actions with these routes';
$string['failed_events'] = 'event(s) have failed to send to the LRS.';
$string['successful_events'] = 'event(s) have been successfully processed.';
$string['filters'] = 'Filter logs';
$string['logguests'] = 'Log guest actions';
$string['filters_help'] = 'Enable filters that INCLUDE some actions to be logged.';
$string['mbox'] = 'Identify users by email';
$string['hashmbox'] = 'Hash users email addresses';
$string['mbox_desc'] = 'Statements will identify users with their email (mbox) when this box is ticked.';
$string['hashmbox_desc'] = 'If identifying users by email has been selected, ticking this box will hash the email address (if users must still be identified a lookup table must be used).';
$string['send_username'] = 'Identify users by id';
$string['send_name'] = 'Identify users by name';
$string['send_username_desc'] = 'Statements will identify users with their username when this box is ticked.';
$string['send_name_desc'] = 'Statements will identify users with their full name when this box is ticked.';
$string['send_jisc_data'] = 'Adds JISC data to statements';
$string['send_jisc_data_desc'] = 'Statements will contain data required by JISC.';
$string['shortcourseid'] = 'Send short course name';
$string['shortcourseid_desc'] = 'Statements will contain the shortname for a course as a short course id extension';
$string['sendidnumber'] = 'Send course and activity ID number';
$string['sendidnumber_desc'] = 'Statements will include the ID number (admin defined) for courses and activities in the object extensions';
$string['send_response_choices'] = 'Send response choices';
$string['send_response_choices_desc'] = 'Statements for multiple choice and sequencing question answers will be sent to the LRS with the correct response and potential choices';
$string['resendfailedbatches'] = 'Resend failed batches';
$string['resendfailedbatches_desc'] = 'When processing events in batches, try re-sending events in smaller batches if a batch fails. If not selected, the whole batch will not be sent in the event of a failed event.';
$string['privacy:metadata:logstore_xapi_log'] = 'xAPI holding table for cron processing';
$string['privacy:metadata:logstore_xapi_log:userid'] = 'User Id of xAPI holding table for cron processing';
$string['privacy:metadata:logstore_xapi_failed_log'] = 'xAPI holding table for failed events';
$string['privacy:metadata:logstore_xapi_failed_log:userid'] = 'User Id of xAPI holding table for failed events';
