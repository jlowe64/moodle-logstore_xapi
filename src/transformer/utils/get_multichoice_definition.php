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
use src\transformer\utils as utils;
defined('MOODLE_INTERNAL') || die();

function get_multichoice_definition(array $config, \stdClass $questionattempt, \stdClass $question, $lang) {
    if ($config['send_response_choices']) {
        $repo = $config['repo'];
        $answers = $repo->read_records('question_answers', [
            'question' => $questionattempt->questionid
        ]);
        $choices = array_map(function ($answer) use ($lang) {
            return [
                "id" => "$answer->id",
                "description" => [
                    $lang => utils\get_string_html_removed($answer->answer)
                ]
            ];
        }, $answers);
        return [
            'type' => 'http://adlnet.gov/expapi/activities/cmi.interaction',
            'name' => [
                $lang => utils\get_string_html_removed($questionattempt->responsesummary),
            ],
            'interactionType' => 'choice',
            'correctResponsesPattern' => [
                utils\get_string_html_removed($questionattempt->rightanswer),
            ],
            // Need to pull out id's that are appended during array_map so json parses it correctly as an array.
            'choices' => array_values($choices)
        ];
    }

    return [
        'type' => 'http://adlnet.gov/expapi/activities/cmi.interaction',
        'name' => [
            $lang => utils\get_string_html_removed($questionattempt->responsesummary),
        ],
        'interactionType' => 'choice'
    ];
}
