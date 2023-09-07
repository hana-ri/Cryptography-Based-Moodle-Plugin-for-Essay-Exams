<?php
defined('MOODLE_INTERNAL') || die();

class Question
{
    public static function create_question($instanceCustomeQuiz, $data)
    {
        global $DB;

        $object = new stdClass();

        $object->customequiz_id = $instanceCustomeQuiz->id;
        $object->bobotnilai = $data['bobot_nilai'];
        $object->question = $data['question_content'];
        $object->timecreated = time();
        $DB->insert_record('customequiz_quiz_questions', $object);

        $url = new moodle_url('/mod/customequiz/questionview.php', ['id' => $instanceCustomeQuiz->cmid]);
        return $url->out();
    }

    public static function get_questions($customequiz_id, $cmid)
    {
        global $DB;

        $data = array(
            'questions' => $DB->get_records('customequiz_quiz_questions', ['customequiz_id' => $customequiz_id])
        );

        // Manipulasi data supaya bisa di print pada list_question.mustache (cara kerjanya aku juga bingung)
        $questionsArray = array();
        $index = 1;

        foreach ($data['questions'] as $question) {
            $question->number = $index;

            $url = new moodle_url(
                '/mod/customequiz/questionedit.php',
                array(
                    'id' => $cmid,
                    'questionid' => $question->id
                )
            );

            $parameters['questionid'] = $question->id;

            $question->editUrl = html_entity_decode($url);
            // $question->question = base64_decode($question->question);
            $question->question = $question->question;

            $questionsArray[] = (array) $question;

            $index++;
        }

        $data['questions'] = $questionsArray;

        return $data;
    }

    public static function update_question($instanceCustomeQuiz, $data)
    {
        global $DB;

        $object = $DB->get_record('customequiz_quiz_questions', ['id' => $instanceCustomeQuiz->questionid]);
        if ($object) {
            $object->bobotnilai = $data['bobot_nilai'];
            $object->question = $data['question_content'];
            $DB->update_record('customequiz_quiz_questions', $object);

            $url = new moodle_url('/mod/customequiz/questionview.php', ['id' => $instanceCustomeQuiz->cmid]);
            return $url->out();
        }

        return false;
    }

    public static function delete_question($instanceCustomeQuiz, $questionsId)
    {
        global $DB;

        $DB->delete_records('customequiz_quiz_questions', array('id' => $questionsId));

        return redirect(new moodle_url('/mod/customequiz/questionview.php', ['id' => $instanceCustomeQuiz->cmid]));
    }
}
