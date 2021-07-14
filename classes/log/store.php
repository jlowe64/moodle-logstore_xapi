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

namespace logstore_xapi\log;
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../src/autoload.php');

use \tool_log\log\writer as log_writer;
use \tool_log\log\manager as log_manager;
use \tool_log\helper\store as helper_store;
use \tool_log\helper\reader as helper_reader;
use \tool_log\helper\buffered_writer as helper_writer;
use \core\event\base as event_base;
use \stdClass as php_obj;

/**
 * This class processes events and enables them to be sent to a logstore.
 *
 */
class store extends php_obj implements log_writer {
    use helper_store;
    use helper_reader;
    use helper_writer;

    /**
     * Constructs a new store.
     * @param log_manager $manager
     */
    public function __construct(log_manager $manager) {
        $this->helper_setup($manager);
    }

    /**
     * Should the event be ignored (not logged)? Overrides helper_writer.
     * @param event_base $event
     * @return bool
     *
     */
    protected function is_event_ignored(event_base $event) {
        $allowguestlogging = $this->get_config('logguests', 1);
        if ((!CLI_SCRIPT || PHPUNIT_TEST) && !$allowguestlogging && isguestuser()) {
            // Always log inside CLI scripts because we do not login there.
            return true;
        }

        $enabledevents = explode(',', $this->get_config('routes', ''));
        $isdisabledevent = !in_array($event->eventname, $enabledevents);
        return $isdisabledevent;
    }

    /**
     * Insert events in bulk to the database. Overrides helper_writer.
     * @param array $events raw event data
     */
    protected function insert_event_entries(array $events) {
        global $DB;
		
		//don't log any events without a definitive user id (an actor) GVM 10-02-21
		$events = array_filter($events,function($event) { return !empty($event['userid']); });

        // If in background mode, just save them in the database.
        if ($this->get_config('backgroundmode', false)) {
            $DB->insert_records('logstore_xapi_log', $events);
        } else {
            $this->process_events($events);
        }
    }

    public function get_max_batch_size() {
        return $this->get_config('maxbatchsize', 100);
    }

    public function process_events(array $events) {
        global $DB;
        global $CFG;
        require(__DIR__ . '/../../version.php');
        $logerror = function ($message = '') {
            debugging($message, DEBUG_NORMAL);
        };
        $loginfo = function ($message = '') {
            debugging($message, DEBUG_DEVELOPER);
        };

        $handlerconfig = [
            'log_error' => $logerror,
            'log_info' => $loginfo,
            'transformer' => [
                'source_lang' => 'en',
                'send_mbox' => $this->get_config('mbox', false),
				'hashmbox' => $this->get_config('hashmbox', false),
                'send_response_choices' => $this->get_config('sendresponsechoices', false),
                'send_short_course_id' => $this->get_config('shortcourseid', false),
                'send_course_and_module_idnumber' => $this->get_config('sendidnumber', false),
                'send_username' => $this->get_config('send_username', false),
				'send_name' => $this->get_config('send_name', false),
                'send_jisc_data' => $this->get_config('send_jisc_data', false),
                'session_id' => sesskey(),
                'plugin_url' => 'https://github.com/xAPI-vle/moodle-logstore_xapi',
                'plugin_version' => $plugin->release,
                'repo' => new \src\transformer\repos\MoodleRepository($DB),
                'app_url' => $CFG->wwwroot,
            ],
            'loader' => [
                'loader' => 'moodle_curl_lrs',
                'lrs_endpoint' => $this->get_config('endpoint', ''),
                'lrs_username' => $this->get_config('username', ''),
                'lrs_password' => $this->get_config('password', ''),
                'lrs_max_batch_size' => $this->get_max_batch_size(),
                'lrs_resend_failed_batches' => $this->get_config('resendfailedbatches', false),
            ],
        ];

        if (isset($CFG->totara_release)) {
            $source = [
                'source_url' => 'http://totaralearning.com',
                'source_name' => 'Totara Learn',
                'source_version' => $CFG->totara_version
            ];
        } else {
            $source = [
                'source_url' => 'http://moodle.org',
                'source_name' => 'Moodle',
                'source_version' => $CFG->release
            ];
        }

        $handlerconfig['transformer'] = array_merge($handlerconfig['transformer'], $source);

        $loadedevents = \src\handler($handlerconfig, $events);
        return $loadedevents;
    }

    /**
     * Determines if a connection exists to the store.
     * @return boolean
     */
    public function is_logging() {
        return true;
    }
}
