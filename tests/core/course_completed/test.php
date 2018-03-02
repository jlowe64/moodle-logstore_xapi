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

namespace Tests;

defined('MOODLE_INTERNAL') || die();

class course_completed_test extends xapi_testcase {
    protected function get_event() {
        return [
            'userid' => '1',
            'relateduserid' => '1',
            'courseid' => '1',
            'timecreated' => 1433946701,
            'objecttable' => 'course_completed',
            'objectid' => 1,
            'eventname' => '\core\event\course_completed',
        ];
    }

    protected function get_expected_statements() {
        return file_get_contents(__DIR__.'/statements.json');
    }
}