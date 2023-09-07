<?php

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/customequiz/classes/AttempQuestion.php');
require_once($CFG->dirroot . '/mod/customequiz/classes/Question.php');


$id = required_param('id', PARAM_INT); // Course Module ID

require_login();

[$course, $cm] = get_course_and_cm_from_cmid($id, 'customequiz');
$instanceCustomeQuiz = $DB->get_record('customequiz', ['id' => $cm->instance], '*', MUST_EXIST);
$instanceCustomeQuiz->cmid = $id;
$instanceCustomeQuiz->user_id = $USER->id;

function ajaxResponse($url)
{
    $response = new stdClass();
    $response->status = 'success';
    $response->message = 'Data received and processed successfully!';
    $response->url = $url;

    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_SERVER['HTTP_REFERER'];
    // Mengurai URL untuk mendapatkan path
    $path = parse_url($url, PHP_URL_PATH);
    // Menggunakan pathinfo untuk mendapatkan nama file
    $filename = pathinfo($path, PATHINFO_BASENAME);

    if ($filename === 'view.php') {
        $results = AttempQuestion::attemp_question($instanceCustomeQuiz, $_POST);
        ajaxResponse($results);
    } elseif ($filename === 'questionview.php') {
        $data['bobot_nilai'] = $_POST['bobot_nilai'];
        $data['question_content'] = $_POST['question_content'];
        $results = Question::create_question($instanceCustomeQuiz, $data);
        ajaxResponse($results);
    } elseif ($filename === 'questionedit.php') {
        $questionid = optional_param('questionid', 0, PARAM_INT); // Mengambil nilai 'questionid' dari URL

        $data['bobot_nilai'] = $_POST['bobot_nilai'];
        $data['question_content'] = $_POST['question_content'];
        $instanceCustomeQuiz->questionid = $questionid;
        $results = Question::update_question($instanceCustomeQuiz, $data);
        ajaxResponse($results);
    }
}
