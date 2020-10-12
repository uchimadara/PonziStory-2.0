<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News extends MY_Controller {

    public function __construct() {
        parent::__construct();

//        if ($this->isGuest) {
//            $this->session->set_flashdata('error', 'You must be logged in to view that page.');
//            redirect('user/login');
//        }
        $this->load->model('news_model', 'News');
        $this->load->library('text_format');

        // load the permissions for the current user
        //$this->data->menu = $this->loadPartialView('partial/menu');

        $this->output->enable_profiler(PROFILER_SETTING);
    }

    public function index() {
        $this->data->page_title = 'News';

        $this->data->content = $this->loadPartialView('news/list');

            $this->addJavascript('/assets/scripts/getList.js');
            $this->addStyleSheet('/assets/bootstrap/css/member.css');

        if ($this->isGuest){
            $this->layout = 'layout/frontend/shell';
        }else{
            $this->layout = 'layout/member/shell';
        }

        $this->loadView('layout/default', $this->data->page_title);
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
