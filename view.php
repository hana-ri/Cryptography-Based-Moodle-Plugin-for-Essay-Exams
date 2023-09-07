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

/**
 * Activity view page for the plugintype_pluginname plugin.
 *
 * @package   plugintype_pluginname
 * @copyright Year, You Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/customequiz/classes/Question.php');
require_once($CFG->dirroot . '/mod/customequiz/classes/AttempQuestion.php');


$id = required_param('id', PARAM_INT); // Course Module ID
[$course, $cm] = get_course_and_cm_from_cmid($id, 'customequiz');
$instanceCustomeQuiz = $DB->get_record('customequiz', ['id' => $cm->instance], '*', MUST_EXIST);
$instanceCustomeQuiz->cmid = $id;
$instanceCustomeQuiz->user_id = $USER->id;

require_course_login($course, true, $cm);

$PAGE->add_body_class('limitedwidth');

$PAGE->set_url('/mod/customequiz/view.php', array('id' => $id));
$PAGE->set_title(format_string($instanceCustomeQuiz->name));
$PAGE->set_heading(format_string($course->fullname));

$PAGE->requires->jquery();
$PAGE->requires->js('/mod/customequiz/js/local.js');
$PAGE->requires->js('/mod/customequiz/js/view.js');

$templateData = Question::get_questions($instanceCustomeQuiz->id, $instanceCustomeQuiz->cmid);
$templateData['btnText'] = 'Submit';

// Render template
echo $OUTPUT->header();

if (!AttempQuestion::user_attemp($instanceCustomeQuiz->user_id, $id)) {
    echo $OUTPUT->render_from_template('customequiz/attemp_questions', $templateData);
} else {
    echo $OUTPUT->render_from_template('customequiz/alert', $templateData);
}

echo $OUTPUT->footer();
