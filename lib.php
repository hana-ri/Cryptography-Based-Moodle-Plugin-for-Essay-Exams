<?php

defined('MOODLE_INTERNAL') || die();

/**
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know or string for the module purpose.
 */
function customequiz_supports($feature)
{
    switch ($feature) {
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_COMPLETION_HAS_RULES:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_CONTROLS_GRADE_VISIBILITY:
            return false;
        case FEATURE_USES_QUESTIONS:
            return false;
        case FEATURE_PLAGIARISM:
            return false;
        case FEATURE_MOD_PURPOSE:
            return MOD_PURPOSE_ASSESSMENT;

        default:
            return null;
    }
}


// Fungsi buat ngatur nama sama deskripsi waktu nambahin activity baru
function customequiz_add_instance($instanceData, $mform = null)
{
    global $DB, $CFG;

    $instanceData->timecreated = time();

    $id = $DB->insert_record('customequiz', $instanceData);

    return $id;
};

// Fungsi buat ngeupadate nama sama desckripsi activity yang udah dibaut sebelumnya
function customequiz_update_instance($instanceData, $mform = null)
{
    global $DB, $CFG;

    $instanceData->timemodified = time();
    $instanceData->id = $instanceData->instance;

    return $DB->update_record('customequiz', $instanceData);
};

// function customequiz_delete_instance($id) {
//     global $DB;
//     $result = true;
//     $exists = $DB->get_record('customequiz', array('id' => $id), '*', MUST_EXIST);

//     if (!$exists) {
//         return false;
//     }
//     $DB->delete_records('customequiz', array('id' => $id));

//     return $result;
// };


// Buat ngantur navigasi didalem activity yang udah dibuat.
function customequiz_extend_settings_navigation(settings_navigation $settings, navigation_node $customequiznode)
{
    // Dapatkan kunci node di mana tautan baru akan disisipkan (sebelum atau setelah).
    $keys = $customequiznode->get_children_key_list();
    $beforekey = null;
    $i = array_search('modedit', $keys);
    if ($i === false && array_key_exists(0, $keys)) {
        $beforekey = $keys[0];
    } else if (array_key_exists($i + 1, $keys)) {
        $beforekey = $keys[$i + 1];
    }

    if (has_any_capability(['mod/customequiz:manage'], $settings->get_page()->cm->context)) {

        // Tambahkan tautan pertama: "Questions".
        $urlNewQuestion = new moodle_url('/mod/customequiz/questionview.php', ['id' => $settings->get_page()->cm->id]);
        $nodeNewQuestion = navigation_node::create(
            'Questions',
            $urlNewQuestion,
            navigation_node::TYPE_SETTING,
            null,
            'customequiz-nav-questions'
        );
        $customequiznode->add_node($nodeNewQuestion, $beforekey);
        // Tambahkan tautan kedua: "Results".
        $urlStatistics = new moodle_url('/mod/customequiz/results.php', ['id' => $settings->get_page()->cm->id]);
        $nodeStatistics = navigation_node::create(
            'Results',
            $urlStatistics,
            navigation_node::TYPE_SETTING,
            null,
            'customequiz-nav-results'
        );
        $customequiznode->add_node($nodeStatistics, $beforekey);
    }
}
