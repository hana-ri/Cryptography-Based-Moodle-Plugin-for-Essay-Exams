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

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/customequiz/classes/Results.php');


$id = required_param('id', PARAM_INT); // Mengambil nilai 'id' dari URL

[$course, $cm] = get_course_and_cm_from_cmid($id, 'customequiz');
$instanceCustomeQuiz = $DB->get_record('customequiz', ['id' => $cm->instance], '*', MUST_EXIST);
$instanceCustomeQuiz->cmid = $id;

require_course_login($course, true, $cm);

$PAGE->add_body_class('limitedwidth');
$PAGE->set_url('/mod/customequiz/results.php', ['id' => $id]);
$PAGE->set_title(format_string($instanceCustomeQuiz->name));
$PAGE->requires->jquery();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Results::download_attempts($instanceCustomeQuiz->cmid);
}

$templateData = Results::total_attemps($instanceCustomeQuiz->cmid);

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('customequiz/results', $templateData);

echo $OUTPUT->footer();
