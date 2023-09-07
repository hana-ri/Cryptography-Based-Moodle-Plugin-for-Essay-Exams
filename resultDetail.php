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
 * Edit page for the customequiz module.
 *
 * @package   mod_customequiz
 * @copyright Year, Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use LDAP\Result;

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/customequiz/classes/Results.php');


$id = required_param('id', PARAM_INT); // Mengambil nilai 'id' dari URL
$user_id = required_param('user_id', PARAM_INT);

[$course, $cm] = get_course_and_cm_from_cmid($id, 'customequiz');
$instanceCustomeQuiz = $DB->get_record('customequiz', ['id' => $cm->instance], '*', MUST_EXIST);
$instanceCustomeQuiz->cmid = $id;

require_course_login($course, true, $cm);

$PAGE->add_body_class('limitedwidth');
$PAGE->set_url('/mod/customequiz/resultDetail.php', ['id' => $id]);
$PAGE->set_title(format_string($instanceCustomeQuiz->name));

$PAGE->requires->jquery();
$PAGE->requires->js('/mod/customequiz/js/local.js');
$PAGE->requires->js('/mod/customequiz/js/resultDetail.js');

$templateData = Results::result_details($user_id, $instanceCustomeQuiz->cmid);
$templateData['user_fullname'] = $DB->get_field('user', 'CONCAT(firstname, " ", lastname)', ['id' => $user_id]);

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('customequiz/result_detail', $templateData);

echo $OUTPUT->footer();
