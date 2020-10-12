<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include('admin.php');

class Admin_Settings extends Admin {

    public function __construct() {
        parent::__construct(TRUE);

        $this->layoutData['title'] = 'Settings';
        $this->load->model('Settings_model', 'Settings');
        $this->layout = 'layout/admin/shell';
    }

    public function index() {
//        $username = $this->session->userdata('username');
//
        if ($this->ion_auth->is_support()){
            echo "You are not permitted to view this page";
            exit;
        }
        $this->data->tabs = array();
        $this->data->settings = array();
        if (ENVIRONMENT != 'local') {
            $modules = $this->Settings->exclude_module(array('admin','notify'))->getModules();
        } else {
            $modules = $this->Settings->getModules();
        }
        foreach ($modules as $m) {
            $this->data->tabs[] = $m->module;
            $this->data->settings[] = $this->Settings->getAll($m->module);
        }

        $this->data->page_title = "Settings";
        $this->data->content = $this->loadPartialView('admin/settings/index');

        $this->addJavascript(asset('bootstrap/js/datetimepicker.min.js'));
        $this->addStyleSheet(asset('bootstrap/css/datetimepicker.min.css'));

        $this->loadView('layout/default', 'Admin Settings');
    }

    public function add() {
        if ($this->ajax) {
            $data = NULL;
            if ($this->form_validation->run('admin/add_settings')) {
                $data = array(
                    'name' => $this->input->post('name'),
                    'module' => $this->input->post('module'),
                    'description' => $this->input->post('description'),
                    'format' => $this->input->post('format'),
                    'label' => $this->input->post('label'),
                    'value' => $this->input->post('value')
                );

                if ($this->Settings->add($data)) {
                    $data = array(
                        'html' => '<div align="center"><h2>Your data has been successfully saved.</h2></div>'
                    );
                } else {
                    $data = array(
                        'error' => renderErrors(array(
                            'ERROR:' => 'Something wrong happened'
                        ))
                    );
                }
            } else {
                $data = array(
                    'error' => renderErrors($this->form_validation->error_array())
                );
            }

            echo json_encode($data);
        } else {
            $this->data->page_title = "Admin Add Setting";
            $this->data->widgets = $this->loadPartialView('admin/settings/add');
            $this->loadView('member/shell', 'Admin Add Setting');
        }
    }

    public function update($id) {
        if ($this->ajax) {
            $data = NULL;

            $setting = $this->Settings->getSetting($id);
            switch ($setting->format) {
                case 'date':
                case 'datetime':
                    $val = strtotime($this->input->post('value'));
                    break;

                case 'yes_no_int':
                    $val = $this->input->post('value') ? '1' : '0';
                    break;

                default:
                    $val = $this->input->post('value');
            }
            $data = array('value' => $val);

            if ($this->Settings->update($id, $data)) {
                $data = array('success' => 'Your data has been successfully saved.<script>$(function() {
                    setTimeout(function() {
                        $("div.alert").hide(\'blind\', {}, 500)
                    }, 3000);
                });</script>');
            } else {
                $data = array('error' => renderErrors(array('ERROR:' => 'Something wrong happened')));
            }
            echo json_encode($data);
        } else {
            $this->data->setting = $this->Settings->getSetting($id);
            $this->data->page_title = "Admin Add Setting";
            $this->data->content = $this->loadPartialView('admin/settings/update');
            $this->loadView('layout/default', 'Admin Update Setting');
        }
    }

    public function ref_comm() {

        $this->load->model('referral_model', 'Referral');
        $this->data->maxLevels = 5;

        if ($_POST) {
            $post = $this->input->post();
            foreach ($post['id'] as $id) {
                $data = array(
                    'sorting' => $post['sorting'][$id],
                );
                $levels = array();
                for ($i = 1; $i <= $this->data->maxLevels; $i++) {
                    $levels[$i] = $post["level$i"][$id];
                }
                $data['levels'] = json_encode($levels);
                $this->Referral->updateCommission($id, $data);
            }
        }

        $this->data->refComm = $this->Referral->getCommissionTable();


        $this->layoutData['title'] = 'Settings - Referral Commissions';

        $this->data->page_title = "Admin Ref Comm";
        $this->data->widgets = $this->loadPartialView('admin/settings/ref_comm');
        $this->loadView('member/shell', 'Admin Ref Comm');
    }

    public function add_ref_comm() {

        $this->load->model('referral_model', 'Referral');
        $data = array('error' => renderErrors(array('ERROR:' => 'Something wrong happened')));
        if ($_POST) {
            $post = $this->input->post();
            $this->Referral->addCommission($post);

            $data = array('html' => '<div align="center"><h2>Your data has been successfully saved.</h2></div>');
        }

        echo json_encode($data);
    }

}
