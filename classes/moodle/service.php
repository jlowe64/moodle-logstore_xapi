<?php namespace logstore_emitter\moodle;
use \stdClass as php_obj;

class service extends php_obj {
    protected $repo;

    /**
     * Constructs a new service.
     * @param repository $repo The LRS to be used to store statements.
     */
    public function __construct(repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Reads data for an event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    private function read_event(array $opts) {
        return [
            'user' => $this->repo->read_user($opts['userid']),
            'course' => $this->repo->read_course($opts['courseid']),
            'event' => $opts,
        ];
    }

    /**
     * Reads data for a course_viewed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_course_viewed_event(array $opts) {
        return $this->read_event($opts);
    }

    /**
     * Reads data for a module_viewed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_module_viewed_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'module' => $this->repo->read_module($opts['objectid'], $opts['objecttable']),
        ]);
    }

    /**
     * Reads data for a attempt_started event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_attempt_started_event(array $opts) {
        $attempt = $this->repo->read_attempt($opts['objectid']);
        return array_merge($this->read_event($opts), [
            'attempt' => $attempt,
            'module' => $this->repo->read_module($attempt->quiz, 'quiz'),
        ]);
    }

    /**
     * Reads data for a user_loggedin event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_user_loggedin_event(array $opts) {
        return $this->read_event($opts);
    }

    /**
     * Reads data for a user_loggedout event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_user_loggedout_event(array $opts) {
        return $this->read_event($opts);
    }

    /**
     * Reads data for a assignment_graded event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_assignment_graded_event(array $opts) {
        $grade = $this->repo->read_object($opts['objectid'], $opts['objecttable']);
        return array_merge($this->read_event($opts), [
            'grade' => $grade,
            'module' => $this->repo->read_module($grade->assignment, 'assign'),
        ]);
    }

    /**
     * Reads data for a assignment_submitted event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_assignment_submitted_event(array $opts) {
        $submission = $this->repo->read_object($opts['objectid'], $opts['objecttable']);
        return array_merge($this->read_event($opts), [
            'submission' => $submission,
            'module' => $this->repo->read_module($submission->assignment, 'assign'),
        ]);
    }
}
