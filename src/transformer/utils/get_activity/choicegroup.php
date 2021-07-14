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

namespace src\transformer\utils\get_activity;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

/**
 * Return the object element in the xAPI call.
 *
 * @param array $config
 * @param int $cmid
 * @return array $object
 */
function choicegroup(array $config, $cmid) {
    $lang = $config['source_lang'];
    $repo = $config['repo'];
    $xapitype = 'http://activitystrea.ms/schema/1.0/question';

    $coursemodule = $repo->read_record_by_id('course_modules', $cmid);
    $module = $repo->read_record_by_id('modules', $coursemodule->module);
    $instance = $repo->read_record_by_id($module->name, $coursemodule->instance);

    $coursemoduleurl = $config['app_url'].'/mod/'.$module->name.'/view.php?id='.$cmid;
    $instancename = property_exists($instance, 'name') ? $instance->name : $module->name;

    $object = [
        'id' => $coursemoduleurl,
        'definition' => [
            'type' => $xapitype,
            'name' => [
                $lang => $instancename,
            ],
        ],
    ];

    if (utils\is_enabled_config($config, 'send_course_and_module_idnumber')) {
        $moduleidnumber = property_exists($coursemodule, 'idnumber') ? $coursemodule->idnumber : null;
        $object['definition']['extensions']['https://w3id.org/learning-analytics/learning-management-system/external-id'] =
            $moduleidnumber;
    }

    return $object;
}

/**
 * Return the object element in the xAPI call.
 *
 * @param array $config
 * @param int $courseid
 * @return array $object
 */
function choicegroup_instance_list(array $config, $courseid)
{
    $lang = $config['source_lang'];
    $xapitype = 'http://activitystrea.ms/schema/1.0/collection';

    $instancelisturl = $config['app_url'].'/mod/choicegroup/index.php?id='.$courseid;
    $instancename = 'choicegroup';

    return [
        'id' => $instancelisturl,
        'definition' => [
            'type' => $xapitype,
            'name' => [
                $lang => $instancename,
            ],
        ],
    ];
}

/**
 * Return the object element in the xAPI call.
 *
 * @param array $config
 * @param int $cmid
 * @return array $object
 */
function choicegroup_report(array $config, $cmid)
{
    $lang = $config['source_lang'];
    $repo = $config['repo'];
    $xapitype = 'https://w3id.org/xapi/acrossx/activities/page';

    $coursemodule = $repo->read_record_by_id('course_modules', $cmid);
    $module = $repo->read_record_by_id('modules', $coursemodule->module);
    $instance = $repo->read_record_by_id($module->name, $coursemodule->instance);

    $coursemoduleurl = $config['app_url'].'/mod/'.$module->name.'/report.php?id='.$cmid;
    $instancename = property_exists($instance, 'name') ? $instance->name : $module->name;

    $object = [
        'id' => $coursemoduleurl,
        'definition' => [
            'type' => $xapitype,
            'name' => [
                $lang => $instancename,
            ],
        ],
    ];

    if (utils\is_enabled_config($config, 'send_course_and_module_idnumber')) {
        $moduleidnumber = property_exists($coursemodule, 'idnumber') ? $coursemodule->idnumber : null;
        $object['definition']['extensions']['https://w3id.org/learning-analytics/learning-management-system/external-id'] =
            $moduleidnumber;
    }

    return $object;
}
