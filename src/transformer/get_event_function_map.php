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

namespace src\transformer;
defined('MOODLE_INTERNAL') || die();

function get_event_function_map() {
    $availableevents = [
        '\auth_oidc\event\authed' => 'auth_oidc\user_authed',
        '\auth_oidc\event\user_loggedin' => 'auth_oidc\user_loggedin',
        '\core\event\course_completed' => 'core\course_completed',
        '\core\event\course_viewed' => 'core\course_viewed',
        '\core\event\user_created' => 'core\user_created',
        '\core\event\user_enrolment_created' => 'core\user_enrolment_created',
        '\core\event\user_loggedin' => 'core\user_loggedin',
        '\core\event\user_loggedout' => 'core\user_loggedout',
        '\core\event\course_module_completion_updated' => 'core\course_module_completion_updated',
        '\mod_assign\event\assessable_submitted' => 'mod_assign\assignment_submitted',
        '\mod_assign\event\submission_graded' => 'mod_assign\assignment_graded',

        '\mod_bigbluebuttonbn\event\activity_created' => 'mod_bigbluebuttonbn\activity_created',
        '\mod_bigbluebuttonbn\event\activity_deleted' => 'mod_bigbluebuttonbn\activity_deleted',
        '\mod_bigbluebuttonbn\event\activity_updated' => 'mod_bigbluebuttonbn\activity_updated',
        '\mod_bigbluebuttonbn\event\activity_viewed' => 'mod_bigbluebuttonbn\activity_viewed',
        '\mod_bigbluebuttonbn\event\bigbluebuttonbn_activity_management_viewed' => 'mod_bigbluebuttonbn\bigbluebuttonbn_activity_management_viewed',
        '\mod_bigbluebuttonbn\event\live_session' => 'mod_bigbluebuttonbn\live_session',
        '\mod_bigbluebuttonbn\event\meeting_created' => 'mod_bigbluebuttonbn\meeting_created',
        '\mod_bigbluebuttonbn\event\meeting_ended' => 'mod_bigbluebuttonbn\meeting_ended',
        '\mod_bigbluebuttonbn\event\meeting_joined' => 'mod_bigbluebuttonbn\meeting_joined',
        '\mod_bigbluebuttonbn\event\meeting_left' => 'mod_bigbluebuttonbn\meeting_left',
        '\mod_bigbluebuttonbn\event\recording_deleted' => 'mod_bigbluebuttonbn\recording_deleted',
        '\mod_bigbluebuttonbn\event\recording_edited' => 'mod_bigbluebuttonbn\recording_edited',
        '\mod_bigbluebuttonbn\event\recording_imported' => 'mod_bigbluebuttonbn\recording_imported',
        '\mod_bigbluebuttonbn\event\recording_protected' => 'mod_bigbluebuttonbn\recording_protected',
        '\mod_bigbluebuttonbn\event\recording_published' => 'mod_bigbluebuttonbn\recording_published',
        '\mod_bigbluebuttonbn\event\recording_unprotected' => 'mod_bigbluebuttonbn\recording_unprotected',
        '\mod_bigbluebuttonbn\event\recording_unpublished' => 'mod_bigbluebuttonbn\recording_unpublished',
        '\mod_bigbluebuttonbn\event\recording_viewed' => 'mod_bigbluebuttonbn\recording_viewed',

        '\mod_book\event\course_module_viewed' => 'mod_book\course_module_viewed',
        '\mod_book\event\chapter_viewed' => 'mod_book\chapter_viewed',
        '\mod_chat\event\course_module_viewed' => 'mod_chat\course_module_viewed',
        '\mod_choice\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_choicegroup\event\choice_removed' => 'mod_choicegroup\choice_removed',
        '\mod_choicegroup\event\choice_updated' => 'mod_choicegroup\choice_updated',
        '\mod_choicegroup\event\course_module_instance_list_viewed' => 'mod_choicegroup\course_module_instance_list_viewed',
        '\mod_choicegroup\event\course_module_viewed' => 'mod_choicegroup\course_module_viewed',
        '\mod_choicegroup\event\report_viewed' => 'mod_choicegroup\report_viewed',
        '\mod_data\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_facetoface\event\cancel_booking' => 'mod_facetoface\cancel_booking',
        '\mod_facetoface\event\course_module_viewed' => 'mod_facetoface\course_module_viewed',
        '\mod_facetoface\event\signup_success' => 'mod_facetoface\signup_success',
        '\mod_facetoface\event\take_attendance' => 'mod_facetoface\take_attendance',
        '\mod_feedback\event\course_module_viewed' => 'mod_feedback\course_module_viewed',
        '\mod_feedback\event\response_submitted' => 'mod_feedback\response_submitted\handler',
        '\mod_folder\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_forum\event\course_module_viewed' => 'mod_forum\course_module_viewed',
        '\mod_forum\event\discussion_created' => 'mod_forum\discussion_created',
        '\mod_forum\event\discussion_viewed' => 'mod_forum\discussion_viewed',
        '\mod_forum\event\post_created' => 'mod_forum\post_created',
        '\mod_forum\event\user_report_viewed' => 'mod_forum\user_report_viewed',
        '\mod_glossary\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_hvp\event\attempt_submitted' => 'mod_hvp\attempt_submitted',
        '\mod_hvp\event\course_module_instance_list_viewed' => 'mod_hvp\course_module_instance_list_viewed',
        '\mod_hvp\event\course_module_viewed' => 'mod_hvp\course_module_viewed',
        '\mod_imscp\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_lesson\event\course_module_viewed' => 'mod_lesson\course_module_viewed',
        '\mod_lti\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_oublog\event\comment_approved' => 'mod_oublog\comment_approved',
        '\mod_oublog\event\comment_created' => 'mod_oublog\comment_created',
        '\mod_oublog\event\comment_deleted' => 'mod_oublog\comment_deleted',
        '\mod_oublog\event\course_module_instance_list_viewed' => 'mod_oublog\course_module_instance_list_viewed',
        '\mod_oublog\event\course_module_viewed' => 'mod_oublog\course_module_viewed',
        '\mod_oublog\event\participation_viewed' => 'mod_oublog\participation_viewed',
        '\mod_oublog\event\post_created' => 'mod_oublog\post_created',
        '\mod_oublog\event\post_deleted' => 'mod_oublog\post_deleted',
        '\mod_oublog\event\post_imported' => 'mod_oublog\post_imported',
        '\mod_oublog\event\post_updated' => 'mod_oublog\post_updated',
        '\mod_oublog\event\post_viewed' => 'mod_oublog\post_viewed',
        '\mod_oublog\event\site_entries_viewed' => 'mod_oublog\site_entries_viewed',
        '\mod_page\event\course_module_viewed' => 'mod_page\course_module_viewed',
        '\mod_quiz\event\course_module_viewed' => 'mod_quiz\course_module_viewed',
        '\mod_quiz\event\attempt_abandoned' => 'mod_quiz\attempt_submitted\handler',
        '\mod_quiz\event\attempt_started' => 'mod_quiz\attempt_started',
        '\mod_quiz\event\attempt_reviewed' => 'mod_quiz\attempt_reviewed',
        '\mod_quiz\event\attempt_submitted' => 'mod_quiz\attempt_submitted\handler',
        '\mod_quiz\event\attempt_viewed' => 'mod_quiz\attempt_viewed',
        '\mod_resource\event\course_module_viewed' => 'mod_resource\course_module_viewed',
        '\mod_scorm\event\course_module_viewed' => 'mod_scorm\course_module_viewed',
        '\mod_scorm\event\sco_launched' => 'mod_scorm\sco_launched',
        '\mod_scorm\event\scoreraw_submitted' => 'mod_scorm\scoreraw_submitted',
        '\mod_scorm\event\status_submitted' => 'mod_scorm\status_submitted',
        '\mod_spa\event\category_created' => 'mod_spa\category_created',
        '\mod_spa\event\category_deleted' => 'mod_spa\category_deleted',
        '\mod_spa\event\category_viewed' => 'mod_spa\category_viewed',
        '\mod_spa\event\category_updated' => 'mod_spa\category_updated',
        '\mod_spa\event\course_module_instance_list_viewed' => 'mod_spa\course_module_instance_list_viewed',
        '\mod_spa\event\course_module_viewed' => 'mod_spa\course_module_viewed',
        '\mod_spa\event\edit_page_viewed' => 'mod_spa\edit_page_viewed',
        '\mod_spa\event\emails_sent' => 'mod_spa\emails_sent',
        '\mod_spa\event\email_template_updated' => 'mod_spa\email_template_updated',
        '\mod_spa\event\formative_feedback_viewed' => 'mod_spa\formative_feedback_viewed',
        '\mod_spa\event\group_override_created' => 'mod_spa\group_override_created',
        '\mod_spa\event\group_override_updated' => 'mod_spa\group_override_updated',
        '\mod_spa\event\group_override_deleted' => 'mod_spa\group_override_deleted',
        '\mod_spa\event\question_created' => 'mod_spa\question_created',
        '\mod_spa\event\question_previewed' => 'mod_spa\question_previewed',
        '\mod_spa\event\question_updated' => 'mod_spa\question_updated',
        '\mod_spa\event\question_viewed' => 'mod_spa\question_viewed',
        '\mod_spa\event\report_viewed' => 'mod_spa\report_viewed',
        '\mod_spa\event\submission_created' => 'mod_spa\submission_created',
        '\mod_spa\event\submission_deleted' => 'mod_spa\submission_deleted',
        '\mod_spa\event\submission_reviewed' => 'mod_spa\submission_reviewed',
        '\mod_spa\event\submission_state_updated' => 'mod_spa\submission_state_updated',
        '\mod_spa\event\submission_viewed' => 'mod_spa\submission_viewed',
        '\mod_spa\event\teacher_feedback_created' => 'mod_spa\teacher_feedback_created',
        '\mod_spa\event\teacher_feedback_updated' => 'mod_spa\teacher_feedback_updated',
        '\mod_spa\event\teacher_feedback_viewed' => 'mod_spa\teacher_feedback_viewed',
        '\mod_spa\event\template_created' => 'mod_spa\template_created',
        '\mod_spa\event\template_deleted' => 'mod_spa\template_deleted',
        '\mod_spa\event\template_previewed' => 'mod_spa\template_previewed',
        '\mod_spa\event\template_updated' => 'mod_spa\template_updated',
        '\mod_spa\event\template_viewed' => 'mod_spa\template_viewed',
        '\mod_spa\event\template_questions_added' => 'mod_spa\template_questions_added',
        '\mod_spa\event\template_questions_moved' => 'mod_spa\template_questions_moved',
        '\mod_spa\event\template_questions_removed' => 'mod_spa\template_questions_removed',
        '\mod_spa\event\template_questions_viewed' => 'mod_spa\template_questions_viewed',
        '\mod_spa\event\user_graded' => 'mod_spa\user_graded',
        '\mod_spa\event\user_override_created' => 'mod_spa\user_override_created',
        '\mod_spa\event\user_override_deleted' => 'mod_spa\user_override_deleted',
        '\mod_spa\event\user_override_updated' => 'mod_spa\user_override_updated',
        '\mod_spa\event\user_rated' => 'mod_spa\user_rated',
        '\mod_survey\event\course_module_viewed' => 'mod_survey\course_module_viewed',
        '\mod_surveypro\event\all_items_viewed' => 'mod_surveypro\all_items_viewed',
        '\mod_surveypro\event\all_submissions_viewed' => 'mod_surveypro\all_submissions_viewed',
        '\mod_surveypro\event\all_usertemplates_viewed' => 'mod_surveypro\all_usertemplates_viewed',
        '\mod_surveypro\event\course_module_instance_list_viewed' => 'mod_surveypro\course_module_instance_list_viewed',
        '\mod_surveypro\event\course_module_viewed' => 'mod_surveypro\course_module_viewed',
        '\mod_surveypro\event\form_previewed' => 'mod_surveypro\form_previewed',
        '\mod_surveypro\event\item_created' => 'mod_surveypro\item_created',
        '\mod_surveypro\event\item_deleted' => 'mod_surveypro\item_deleted',
        '\mod_surveypro\event\item_modified' => 'mod_surveypro\item_modified',
        '\mod_surveypro\event\mastertemplate_applied' => 'mod_surveypro\mastertemplate_applied',
        '\mod_surveypro\event\submission_created' => 'mod_surveypro\submission_created',
        '\mod_surveypro\event\submission_deleted' => 'mod_surveypro\submission_deleted',
        '\mod_surveypro\event\submission_duplicated' => 'mod_surveypro\submission_duplicated',
        '\mod_surveypro\event\submission_modified' => 'mod_surveypro\submission_modified',
        '\mod_surveypro\event\submission_viewed' => 'mod_surveypro\submission_viewed',
        '\mod_surveypro\event\submissioninpdf_downloaded' => 'mod_surveypro\submissioninpdf_downloaded',
        '\mod_surveypro\event\usertemplate_exported' => 'mod_surveypro\usertemplate_exported',
        '\mod_surveypro\event\usertemplate_saved' => 'mod_surveypro\usertemplate_saved',
        '\mod_url\event\course_module_viewed' => 'mod_url\course_module_viewed',
        '\mod_wiki\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_workshop\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_zoom\event\course_module_instance_list_viewed' => 'mod_zoom\course_module_instance_list_viewed',
        '\mod_zoom\event\course_module_viewed' => 'mod_zoom\course_module_viewed',
        '\mod_zoom\event\join_meeting_button_clicked' => 'mod_zoom\join_meeting_button_clicked',
        '\totara_program\event\program_assigned' => 'totara_program\program_assigned'
    ];
    if(PHPUNIT_TEST) {
        /**
         * In unit test, if test_adminroot_cache_reset test is run before core_event_deprecated_testcase
         * The report_eventlist_list_generator will load course_module_instances_list_viewed abstract class
         * which will cause the core_event_deprecated_testcase to fail
         * (debugging already called and the debug mode is off - list_generator.php)
         **/
        $environmentevents = $availableevents;

    } else {
        $environmentevents = class_exists("report_eventlist_list_generator") ?
            array_keys(\report_eventlist_list_generator::get_all_events_list(false)) : array_keys($availableevents);
    }

    return array_filter($availableevents, function($k) use ($environmentevents) {
        return in_array($k, $environmentevents);
    }, ARRAY_FILTER_USE_KEY);
}