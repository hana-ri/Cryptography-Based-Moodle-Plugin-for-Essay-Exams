<?php

require_once("$CFG->libdir/phpspreadsheet/vendor/autoload.php");

class Results
{
    public static function total_attemps($cmid)
    {
        global $DB;

        // Menyusun query menggunakan Moodle's database class
        $select = 'd.user_id, u.username, u.firstname, u.lastname, COUNT(*) as total_attempts';
        $from = 'mdl_customequiz_quiz_answers AS d';
        $join = 'LEFT JOIN {user} u ON d.user_id = u.id';
        $where = 'd.courseid = ' . $cmid; // Kondisi WHERE baru
        $groupby = 'd.user_id, u.username, u.firstname, u.lastname';

        $data = $DB->get_records_sql("SELECT $select FROM {$from} $join WHERE $where GROUP BY $groupby");

        $data = array('user_attempts' => $data);

        // Manipulasi data supaya bisa di print pada list_question.mustache (cara kerjanya aku juga bingung)
        $dataArray = array();
        $index = 1;
        foreach ($data['user_attempts'] as $user_attempts) {
            $user_attempts->number = $index;

            $url = new moodle_url(
                '/mod/customequiz/resultDetail.php',
                array(
                    'id' => $cmid,
                    'user_id' => $user_attempts->user_id
                )
            );


            $user_attempts->detailURL = html_entity_decode($url);

            $dataArray[] = (array) $user_attempts;
            $index++;
        }

        $data['user_attempts'] = $dataArray;

        return $data;
    }

    public static function download_attempts($cmid)
    {
        global $DB;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $select = 'a.id AS answer_id, CONCAT(u.firstname, " ", u.lastname) AS fullname, q.bobotnilai AS bobot_nilai, q.question, a.answer';
        $from = 'mdl_customequiz_quiz_questions q';
        $join = 'JOIN mdl_customequiz_quiz_answers a ON q.id = a.customequiz_quiz_questions_id';
        $join .= ' JOIN mdl_user u ON a.user_id = u.id';
        $where = 'a.courseid = ' . $cmid;
        $order = 'u.username';

        $query = "SELECT $select FROM {$from} $join WHERE $where ORDER BY $order";

        $data = $DB->get_records_sql($query);

        // var_dump($data);
        // die;

        // Set headers for Excel columns
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Fullname');
        $sheet->setCellValue('C1', 'Bobot Nilai');
        $sheet->setCellValue('D1', 'Question');
        $sheet->setCellValue('E1', 'Answer');

        // Populate Excel rows with data
        $row = 2;
        $index = 1;
        foreach ($data as $record) {
            $sheet->setCellValue('A' . $row, $index);
            $sheet->setCellValue('B' . $row, $record->fullname);
            $sheet->setCellValue('C' . $row, $record->bobot_nilai);
            $sheet->setCellValue('D' . $row, base64_decode($record->question));
            $sheet->setCellValue('E' . $row, base64_decode($record->answer));
            $row++;
            $index++;
        }

        // Create Excel Writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set HTTP headers for file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="quiz_attempts.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet to browser
        $writer->save('php://output');
        exit;
    }

    public static function result_details($user_id, $cmid)
    {
        global $DB;

        $select = 'q.id AS question_id, q.bobotnilai AS bobot_nilai, q.question, a.answer';
        $from = 'mdl_customequiz_quiz_questions q';
        $join = 'JOIN mdl_customequiz_quiz_answers a ON q.id = a.customequiz_quiz_questions_id';
        $where = 'a.courseid = ' . $cmid . ' AND a.user_id = ' . $user_id;
        $order = 'q.id';

        $query = "SELECT $select FROM {$from} $join WHERE $where ORDER BY $order";

        $data = $DB->get_records_sql($query);

        $data = array('user_attempts' => $data);

        // Manipulasi data supaya bisa di print pada list_question.mustache (cara kerjanya aku juga bingung)
        $dataArray = array();
        $index = 1;
        foreach ($data['user_attempts'] as $user_attempts) {

            $user_attempts->number = $index;
            $user_attempts->question = $user_attempts->question;
            $user_attempts->answer = $user_attempts->answer;

            $dataArray[] = (array) $user_attempts;

            $index++;
        }

        $data['user_attempts'] = $dataArray;

        return $data;
    }
}
