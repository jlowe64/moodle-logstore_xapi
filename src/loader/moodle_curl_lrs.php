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

namespace src\loader\moodle_curl_lrs;
defined('MOODLE_INTERNAL') || die();

global $CFG;
if (!isset($CFG)) {
    $CFG = (object) [ 'libdir' => 'utils' ];
}
require_once($CFG->libdir . '/filelib.php');

use src\loader\utils as utils;

function load(array $config, array $events) {
    $sendhttpstatements = function (array $config, array $statements) {
        $endpoint = $config['lrs_endpoint'];
        $username = $config['lrs_username'];
        $password = $config['lrs_password'];

        $url = utils\correct_endpoint($endpoint).'/statements';
        $auth = base64_encode($username.':'.$password);
		
		//don't send empty statement arrays GVM 25-01-21
        if(count($statements) > 0) {
			$postdata = json_encode($statements);

			if ($postdata === false) {
				throw new \Exception('JSON encode error: '.json_last_error_msg());
			}

			$request = new \curl();
			$responsetext = $request->post($url, $postdata, [
				'CURLOPT_HTTPHEADER' => [
					'Authorization: Basic '.$auth,
					'X-Experience-API-Version: 1.0.0',
					'Content-Type: application/json',
				],
			]);
			$responsecode = $request->info['http_code'];

			if ($responsecode !== 200) {
				throw new \Exception($responsetext, $responsecode);
			}
		}
    };
    return utils\load_in_batches($config, $events, $sendhttpstatements);
}