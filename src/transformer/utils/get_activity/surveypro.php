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
 * @param [type] $cmid
 * @return void
 */
function surveypro(array $config, $cmid) {
    $lang = $config['source_lang'];
    $repo = $config['repo'];
    $xapitype = 'http://id.tincanapi.com/activitytype/survey';

    $coursemodule = $repo->read_record_by_id('course_modules', $cmid);
    $module = $repo->read_record_by_id('modules', $coursemodule->module);
    $instance = $repo->read_record_by_id($module->name, $coursemodule->instance);

    $coursemoduleurl = $config['app_url'].'/mod/'.$module->name.'/layout_items.php?id='.$cmid;
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
 * Returns the object element in the xAPI call.
 *
 * @param array $config
 * @param [type] $itemid
 * @param [type] $cmid
 * @return void
 */
function surveypro_item(array $config, $itemid, $cmid) {
    $lang = $config['source_lang'];
    $repo = $config['repo'];
    $xapitype = 'http://id.tincanapi.com/activitytype/survey';

    // If the item doesn't exist return parent object.
    try {
        $item = $repo->read_record_by_id('surveypro_item', $itemid);
    } catch (\Exception $e) {
        return surveypro($config, $cmid);
    }

    $itemtype = $item->type;
    $itemplugin = $item->plugin;

    return [
        'id' => $config['app_url'].
                '/mod/surveypro/layout_itemsetup.php?id='.$cmid.
                '&itemid='.$itemid.
                '&type='.$itemtype.
                '&plugin='.$itemplugin,
        'definition' => [
            'type' => $xapitype,
            'name' => [
                $lang => 'Survey item',
            ],
        ],
    ];
}

/**
 * Returns the object element in the xAPI call.
 *
 * @param array $config
 * @param [type] $itemid
 * @param [type] $cmid
 * @return void
 */
function surveypro_submission($config, $submissionid, $cmid) {
    $lang = $config['source_lang'];
    $repo = $config['repo'];
    $xapitype = 'http://activitystrea.ms/schema/1.0/comment';

    // If the submission doesn't exist return parent object.
    try {
        $submission = $repo->read_record_by_id('surveypro_submission', $submissionid);
    } catch (\Exception $e) {
        return surveypro($config, $cmid);
    }

    return [
        'id' => $config['app_url'].
                '/mod/surveypro/view_form.php?id='.$cmid.
                '&submissionid='.$submission->id.
                '&view=1',
        'definition' => [
            'type' => $xapitype,
            'name' => [
                $lang => 'Survey submission',
            ],
        ]
    ];
}

/**
 * Returns the object element in the xAPI call.
 *
 * @param array $config
 * @param [type] $itemid
 * @param [type] $cmid
 * @return void
 */
function surveypro_usertemplate($config, $submissionid, $cmid) {
    $object = surveypro($config, $cmid);
    $object['id'] = $config['app_url'].'/mod/surveypro/utemplate_manage.php?id='.$cmid;
    return $object;
}