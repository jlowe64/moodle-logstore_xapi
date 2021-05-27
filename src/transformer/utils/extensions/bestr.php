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

namespace src\transformer\utils\extensions;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function bestr(array $config, \stdClass $event, $course) {
    if (utils\is_enabled_config($config, 'send_bestr_data')) {
        $repo = $config['repo'];
        if (isset($event->relateduserid) && $event->relateduserid) {
			$user = $repo->read_record_by_id('user', $event->relateduserid);
		}
		else {
			$user = $repo->read_record_by_id('user', $event->userid);
		}
        return array(
			'http://lrs.bestr.it/lrs/define/context/extensions/actor' => array(
				"actor_name" => $user->firstname,
				"actor_surname" => $user->lastname
				)
			);
    }
    return [];
}
