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

namespace logstore_xapi\task;
defined('MOODLE_INTERNAL') || die();

use tool_log\log\manager;
use logstore_xapi\log\store;

class emit_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskemit', 'logstore_xapi');
    }

    private function get_failed_events($events) {
        $nonloadedevents = array_filter($events, function ($loadedevent) {
            return $loadedevent['loaded'] === false;
        });
        $failedevents = array_map(function ($nonloadedevent) {
            return $nonloadedevent['event'];
        }, $nonloadedevents);
        return $failedevents;
    }

    private function get_successful_events($events) {
        $loadedevents = array_filter($events, function ($loadedevent) {
            return $loadedevent['loaded'] === true;
        });
        $successfulevents = array_map(function ($loadedevent) {
            return $loadedevent['event'];
        }, $loadedevents);
        return $successfulevents;
    }

    private function get_event_ids($loadedevents) {
        return array_map(function ($loadedevent) {
            return $loadedevent['event']->id;
        }, $loadedevents);
    }

    private function extract_events($limitnum) {
        global $DB;
        $conditions = null;
        $sort = 'id DESC';
        $fields = '*';
        $limitfrom = 0;
        $extractedevents = $DB->get_records('logstore_xapi_log', $conditions, $sort, $fields, $limitfrom, $limitnum);
        return $extractedevents;
    }

    private function delete_processed_events($events) {
        global $DB;
        $eventids = $this->get_event_ids($events);
        $DB->delete_records_list('logstore_xapi_log', 'id', $eventids);
    }

    private function store_failed_events($events) {
        global $DB;
        $failedevents = $this->get_failed_events($events);
        $DB->insert_records('logstore_xapi_failed_log', $failedevents);
        mtrace(count($failedevents) . " " . get_string('failed_events', 'logstore_xapi'));
    }

    private function record_successful_events($events) {
        mtrace(count($this->get_successful_events($events)) . " " . get_string('successful_events', 'logstore_xapi'));
    }

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        $manager = get_log_manager();
        $store = new store($manager);

        $extractedevents = $this->extract_events($store->get_max_batch_size());
        $loadedevents = $store->process_events($extractedevents);
        $this->store_failed_events($loadedevents);
        $this->record_successful_events($loadedevents);
        $this->delete_processed_events($loadedevents);
    }
}
