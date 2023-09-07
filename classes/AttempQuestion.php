<?php

class AttempQuestion
{
    public static function attemp_question($instanceCustomeQuiz, $data)
    {
        global $DB;

        foreach ($data as $key => $value) {
            $questionId = self::get_id_question($key); // Mengambil nilai id question yang disembunyikan di attribut name.
            $record = $DB->get_records('customequiz_quiz_answers', ['customequiz_quiz_questions_id' => $questionId, 'user_id' => $instanceCustomeQuiz->id]);

            if (!empty($record)) {
                $url = new moodle_url('/mod/customequiz/view.php', ['id' => $instanceCustomeQuiz->cmid]);
                return $url->out();
            }

            $object = new stdClass;
            $object->customequiz_quiz_questions_id = $questionId;
            $object->courseid = $instanceCustomeQuiz->cmid;
            $object->user_id = $instanceCustomeQuiz->user_id;
            $object->answer = $value;
            $object->timecreated = time();

            $DB->insert_record('customequiz_quiz_answers', $object);
        }
        $url = new moodle_url('/mod/customequiz/view.php', ['id' => $instanceCustomeQuiz->cmid]);
        return $url->out();
    }

    public static function user_attemp($userId, $courseId)
    {
        global $DB;

        $record = $DB->get_records('customequiz_quiz_answers', ['user_id' => $userId, 'courseid' => $courseId]);

        if (!empty($record)) {
            return true;
        }

        return false;
    }

    public static function get_id_question($key)
    {
        preg_match('/\d+$/', $key, $matches); // Mengambil angka di akhir string
        $id = $matches[0];
        return (int)$id;
    }
}
