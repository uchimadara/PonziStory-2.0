<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tools extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->enable_profiler(FALSE);
        $this->load->helper('inflector');
        $this->load->helper('file');
    }

    public function generateRMS($table = '') {

        if ($table != '') {
            if ($_POST) {
                $field = $this->input->post('field');
                if ($this->input->post('rmslist') == 1)
                    $this->_rmsList($table, $field);
                if ($this->input->post('rmsform') == 1)
                    $this->_rmsForm($table, $field);
                $this->_list();
                exit;
            }
            echo form_open();
            echo 'Folder prefix' . form_input('folder', 'cms_') . '<br />';
            $fields = $this->db->list_fields($table);
            foreach ($fields as $field) {
                echo form_checkbox('field[]', $field, TRUE) . $field . '<br />';
            }
            echo '<br />Options:<br />';
            echo form_checkbox('rmslist', 1, TRUE) . 'RMS List' . '<br />';
            echo form_checkbox('rmsform', 1, FALSE) . 'RMS Form' . '<br /><br />';
            echo form_submit('submit', 'Generate!');
            echo form_close();
        } else {
            $this->_list();
        }
    }

    public function _list() {
        $tables = $this->db->list_tables();
        foreach ($tables as $table) {
            echo anchor('tools/generateRMS/' . $table, $table) . '<br />';
        }
    }

    public function _rmsList($table, $field) {

        $file_output = '<?php  if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');' . "\n\n";
        $file_output .= '$config[\'list_' . $table . '\'][\'table\'] = \'' . $table . '\';' . "\n";
        $file_output .= '$config[\'list_' . $table . '\'][\'order\'] = \'' . $field[0] . '\';' . "\n";
        $file_output .= '$config[\'list_' . $table . '\'][\'table_class\'] = \'table\';' . "\n";
        $file_output .= '$config[\'list_' . $table . '\'][\'fields\'] = array(' . "\n";
        foreach ($field as $f => $val) {
            $file_output .= 'array(\'label\'      => \'' . humanize($val) . '\',
                                            \'width\'      => \'' . (strlen($val) * 2) . '%\',
                                            \'field_name\' => \'' . $val . '\',
                                      ),' . "\n";
        }
        $file_output .= ');' . "\n";
        $file_output .= '?>' . "\n";


        $file_path = APPPATH . 'config/' . $this->input->post('folder') . 'lists/';
        if (!file_exists($file_path)) {
            mkdir($file_path, 0755, true);
        }
        if (!file_exists($file_path . $table . '.php')) {
            if (!write_file($file_path . $table . '.php', $file_output)) {
                echo 'Unable to write the file<br />';
            } else {
                echo 'File (' . $file_path . $table . '.php) written!<br />';
            }
        } else {
            echo 'Unable to write the file - Delete this file first!<br />';
        }
    }

    public function _rmsForm($table, $field) {
        $fields = $this->db->field_data($table);
        $fieldsArr = array();
        foreach ($fields as $f) {
            $fieldsArr[$f->name] = $f;
        }

        $file_output = '<?php  if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');' . "\n\n";
        $file_output .= '$config[\'form_' . $table . '\'][\'table\'] = \'' . $table . '\';' . "\n";
        $file_output .= '$config[\'form_' . $table . '\'][\'fields\'] = array(' . "\n";
        foreach ($fieldsArr as $f) {
            $file_output .= 'array(\'label\'      => \'' . humanize($f->name) . '\',
                                            \'field_name\' => \'' . $f->name . '\',
                                            \'type\' => \'text\',
                                            \'value\' => \'\',
                                            \'maxlength\' => \'' . $f->max_length . '\',
                                            \'rule\' => \'trim|xss_clean|required\',
                                      ),' . "\n";
        }
        $file_output .= ');' . "\n";
        $file_output .= '?>' . "\n";

        $file_path = APPPATH . 'config/' . $this->input->post('folder') . 'forms/';
        if (!file_exists($file_path)) {
            mkdir($file_path, 0755, true);
        }
        if (!file_exists($file_path . $table . '.php')) {
            if (!write_file($file_path . $table . '.php', $file_output)) {
                echo 'Unable to write the file<br />';
            } else {
                echo 'File (' . $file_path . $table . '.php) written!<br />';
            }
        } else {
            echo 'Unable to write the file - Delete this file first!<br />';
        }
    }

    public function dbmigration($filename = '') {

        if ($filename != '') {
            $file_path = APPPATH . '../../database/';
            if (file_exists($file_path . $filename . '.sql')) {
                $file = file_get_contents($file_path . $filename . '.sql');
                $queries = explode(';', $file);
                foreach ($queries as $q) {
                    if (strlen($q) > 10) {
                        if ($this->db->query($q)) {
                            echo 'OK' . PHP_EOL;
                        } else {
                            echo 'SQL ERROR' . PHP_EOL;
                        }
                    }
                }
            } else {
                echo 'FILE MISSING' . PHP_EOL;
            }
        } else {
            redirect();
        }
    }

}
