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

namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_user(array $config, \stdClass $user) {
    $fullname = get_full_name($user);
    // The following email validation matches that in Learning Locker
    $hasvalidemail = mb_ereg_match("[A-Z0-9\\.\\`\\'_%+-]+@[A-Z0-9.-]+\\.[A-Z]{1,63}$", $user->email, "i");

    if (array_key_exists('send_mbox', $config) && $config['send_mbox'] == true && $hasvalidemail) {

		$toReturn = array();
		
		if(array_key_exists('hashmbox', $config) && $config['hashmbox'] == true) {
			$toReturn['mbox_sha1sum'] = sha1('mailto:' . $user->email);
		} else {
			$toReturn['mbox'] = 'mailto:' . $user->email;
		}
		
		if(array_key_exists('send_name', $config) && $config['send_name'] == true) {
			$toReturn['name'] = $fullname;
		}
		
        return $toReturn;

    }

    if (array_key_exists('send_username', $config) && $config['send_username'] === true) {
        $toReturn =  [
            'account' => [
                'homePage' => $config['app_url'],
                'name' => $user->username,
            ],
        ];
		
		if(array_key_exists('send_name', $config) && $config['send_name'] == true) {
			$toReturn['name'] = $fullname;
		}
		
        return $toReturn;		
		
    }

    $toReturn =  [
        'account' => [
            'homePage' => $config['app_url'],
            'name' => strval($user->id),
        ],
    ];
	
	if(array_key_exists('send_name', $config) && $config['send_name'] == true) {
		$toReturn['name'] = $fullname;
	}
	
	return $toReturn;		
}
