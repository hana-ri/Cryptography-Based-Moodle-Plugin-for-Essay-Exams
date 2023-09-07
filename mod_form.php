<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_customequiz_mod_form extends moodleform_mod
{

    /**
     * Define the form
     */
    protected function definition()
    {
        global $CFG, $DB, $OUTPUT;

        $mform = &$this->_form;

        // Basic settings section header
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // General section name
        $mform->addElement('text', 'name', get_string('customequizname', 'mod_customequiz'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        // General section description
        $this->standard_intro_elements('Description');

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
}
