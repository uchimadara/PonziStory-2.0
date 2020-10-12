<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Disclaimer extends MY_Controller {

    public function __construct() {
        parent::__construct();

//        if ($this->isGuest) {
//            $this->session->set_flashdata('error', 'You must be logged in to view that page.');
//            redirect('user/login');
//        }

        //$this->load->library('text_format');

        // load the permissions for the current user
        $this->data->menu = $this->loadPartialView('partial/menu');

        $this->output->enable_profiler(PROFILER_SETTING);
    }

    public function index() {


        $this->data->page_title = 'Disclaimer';
       
        $this->layout = 'layout/frontend/shell';

        $this->loadView('disclaimer', $this->data->page_title);
    }

    public function addTeam(){
        $this->data->page_title = 'ADD TEAM';

        $this->layout = 'layout/member/shell';

        $this->loadView('add_teams', $this->data->page_title);
    }

    function rolekey_exists()
    {
        $this->User->role_exists($this->profile->username);
    }

    public function addTeamPost(){
        $this->load->model('Teams_model', 'Teams');

        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('team_link', 'Team Link', 'trim|required|xss_clean');
        $this->form_validation->set_rules('location', 'Location', 'trim|required|xss_clean');

        if ($this->Teams->role_exists($this->profile->username)){
            $this->session->set_flashdata('error', 'You have Already Created a Team.');
            redirect('back_office/dashboard');
        }

        if ($this->form_validation->run() == TRUE) {

            //return true;
            $data = array(
                'name'    => $this->input->post('name'),
                'team_link'      => $this->remove_http($this->input->post('team_link')),
                'location'      => $this->input->post('location'),
                'team_leader' => $this->profile->username,
                'status' => 1,
                'created' => date('Y-m-d'),
            );

            if ($this->Teams->create($data)) {
                $this->session->set_flashdata('success', 'Team Successfully Created.');
                redirect('back_office/dashboard');
            }
            // $this->data->message = 'Could not store the question';

            else {
                $this->data->message = $this->form_validation->error_string('', '<br/>');
            }

        }
    }


    function remove_http($url) {
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
            if(strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }


    public function getList($listName, $sortCol = '', $sortDir = '', $page = 1, $perPage = '') {

        $this->_path = 'member/';
        parent::getList($listName, $sortCol, $sortDir, $page, $perPage);
    }

    public function article($slug) {

        $this->data->news = $this->News->by_slug($slug);
        $this->data->page_title = $this->data->news->title;

        $this->News->mark_read($this->data->news->id, $this->profile->id);
        if ($this->isGuest){
            $this->layout = 'layout/frontend/shell';
        }else{
            $this->layout = 'layout/member/shell';
        }

        $this->data->content = $this->loadPartialView('news/view');
        $this->loadView('layout/default', $this->data->page_title);
    }
}
