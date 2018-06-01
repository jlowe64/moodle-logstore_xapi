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

namespace src\transformer\repos;

use \stdClass as PhpObj;

require_once(__DIR__.'/Repository.php');

class TestRepository extends Repository {
    private $test_data;

    public function __construct($test_data) {
        $this->test_data = $test_data;
    }

    /**
     * Reads an array of objects from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @return PhpArr
     */
    public function read_records($type, array $query) {
        $records = $this->test_data->$type;
        $matchingrecords = [];

        foreach ($records as $record) {
            foreach ($query as $key => $value) {
                if ($record->$key === $value) {
                    $matchingrecords[] = (object) $record;
                }
            }
        }

        return $matchingrecords;
    }
}
