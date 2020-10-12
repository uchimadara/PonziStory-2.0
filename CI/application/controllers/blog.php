<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        //$this->output->enable_profiler(PROFILER_SETTING);
    }


 public function index($slug){
//        $this->load->model('blog_model', 'Blog');
//
//        $this->data->blog = $this->Blog->by_slug($slug);
//        $this->data->page_title = 'Blog News';
     $this->setLayout('layout/frontend/shell');
     //$this->data->content = $this->loadPartialView('article');
     $this->view('article');

}
}
