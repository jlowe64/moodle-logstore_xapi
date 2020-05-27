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

require_once($CFG->dirroot . '/admin/tool/log/store/xapi/lib.php');
require_once($CFG->libdir . '/weblib.php');
require_once($CFG->libdir . '/classes/user.php');
require_once($CFG->libdir . '/messagelib.php');

use tool_log\log\manager;
use logstore_xapi\log\store;

class sendfailednotifications_task extends \core\task\scheduled_task {

    /**
     * Constants
     * Repurpose email_to_user() to send for users with just email addresses.
     */
    const DEFAULT_RECEIVER = -99;
    const DEFAULT_RECEIVER_NAME = "";
    const DEFAULT_SENDER = -99;
    const DEFAULT_SENDER_NAME = "";
    const DEFAULT_SENDER_EMAIL = "";

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('tasksendfailednotifications', 'logstore_xapi');
    }

    /**
     * Get failed rows.
     *
     * @return array
     */
    private function get_failed_rows() {
        global $DB;

        $sql = "SELECT x.id, x.errortype AS type, x.eventname, u.firstname, u.lastname, x.contextid, x.response, x.timecreated
                  FROM {logstore_xapi_failed_log} x
             LEFT JOIN {user} u ON u.id = x.userid";

        $results = $DB->get_records_sql($sql);
        return $results;
    }

    /**
     * Get failed email message.
     *
     * @return string email message in html
     */
    private function get_failed_email_message($results) {
        $emailmsg = "";

        // styles
        $emailmsg .= '<style type="text/css">.header {text-align:left;}</style>';

        // first line
        $emailmsg .= \html_writer::tag('p', get_string('failedtosend', 'logstore_xapi'));

        // summary info
        $endpointname = get_string('endpoint', 'logstore_xapi');
        $url = get_config('logstore_xapi', 'endpoint');
        $endpointurl = \html_writer::tag('a', $url, array('target' => '_blank', 'href' => $url));

        $errorlogpage = get_string('errorlogpage', 'logstore_xapi');
        $url = new \moodle_url("/admin/tool/log/store/xapi/report.php");
        $errorlogurl = \html_writer::tag('a', $url, array('target' => '_blank', 'href' => $url));

        // first table
        $table = new \html_table();

        // data
        $table->data[] = array($endpointname, $endpointurl);
        $table->data[] = array($errorlogpage, $errorlogurl);

        // add table to message
        $emailmsg .= \html_writer::table($table);

        // separator
        $emailmsg .= \html_writer::tag('h3', get_string('failurelog', 'logstore_xapi'));

        // second table
        $table = new \html_table();

        // header
        $heading1 = get_string('datetimegmt', 'logstore_xapi');
        $heading2 = get_string('eventname', 'logstore_xapi');
        $heading3 = get_string('response', 'logstore_xapi');
        $table->head = array($heading1, $heading2, $heading3);

        // data
        foreach ($results as $result) {
            $col1 = userdate($result->timecreated);
            $col2 = $result->eventname;
            $col3 = $result->response;
            $table->data[] = array($col1, $col2, $col3);
        }

        // add table to message
        $emailmsg .= \html_writer::table($table);

        return $emailmsg;
    }

    /**
     * Send email using email_to_user.
     *
     * @param array $failedrows an array of failed rows from logstore_xapi_failed_log
     * @param string $message email message
     * @param string $subject email subject
     * @param object $user user to receive email
     * @return int 1 = sent, 0 = not sent
     */
    private function sendmail($failedrows, $message, $subject, $user) {
        global $DB;

        // Check if we have an actual user, if not we need to setup a temp user
        if (empty($user->id)) {
            $email = $user->email;
            $user = \core_user::get_support_user();
            $user->email = $email;
            // Unset emailstop to ensure the message is sent. This may already be the case when getting the support user.
            $user->emailstop = 0;
        }

        $from = new \stdClass();
        $from->id = self::DEFAULT_SENDER;
        $from->username = self::DEFAULT_SENDER_NAME;
        $from->email = self::DEFAULT_SENDER_EMAIL;
        $from->deleted = 0;
        $from->mailformat = FORMAT_HTML;

        // if any rows haven't been sent before then set flag to send
        $rowstosend = false;
        foreach ($failedrows as $row) {
            $results = $DB->get_records("logstore_xapi_notif_sent_log", array("failedlogid" => $row->id, "email" => $user->email));
            if (empty($results)) {
                $rowstosend = true;
                break;
            }
        }

        if ($rowstosend == true) {
            $messagesent = email_to_user($user, $from, $subject, html_to_text($message), $message);
            if ($messagesent) {
                // log that these notifications have been sent
                $now = time();
                $rows = array();
                foreach ($failedrows as $row) {
                    $rows[] = array("failedlogid" => $row->id, "email" => $user->email, "timecreated" => $now);
                }

                $DB->insert_records("logstore_xapi_notif_sent_log", $rows);
                return $messagesent;
            }
        }
        return 0;
    }

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        $manager = get_log_manager();
        $store = new store($manager);

        echo get_string('insendfailednotificationstask', 'logstore_xapi') . PHP_EOL;

        $enablesendingnotifications = get_config('logstore_xapi', 'enablesendingnotifications');
        if (empty($enablesendingnotifications)) {
            echo get_string('notificationsnotenabled', 'logstore_xapi') . PHP_EOL;
            return;
        }

        $results = $this->get_failed_rows();
        if (count($results) == 0) {
            echo get_string('norows', 'logstore_xapi') . PHP_EOL;
            return;
        }

        $notificationtrigger = get_config('logstore_xapi', 'errornotificationtrigger');
        if (count($results) < $notificationtrigger) {
            echo get_string('notificationtriggerlimitnotreached', 'logstore_xapi') . PHP_EOL;
            return;
        }

        $subject = get_string('failedsubject', 'logstore_xapi');
        $message = $this->get_failed_email_message($results);

        $users = logstore_xapi_get_users_for_notifications();
        foreach ($users as $user) {
            $this->sendmail($results, $message, $subject, $user);
        }
    }
}
