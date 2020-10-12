<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('admin.php');

class Faq extends Admin
{
    public function __construct()
    {
        parent::__construct(true);

        $this->page_title = 'Frequenty Asked Questions';
        $this->layout = 'layout/admin/shell';

        $this->load->model('faq_model', 'FAQ');
    }

    public function index()
    {
        $this->data->categories = $this->FAQ->getCategories();
        $this->data->questions  = $this->FAQ->getQuestions();

        $this->addStyleSheet(asset('styles/faq.css'));

        $this->data->widgets = $this->loadPartialView('admin/faq/index');
        $this->loadView('member/shell', SITE_NAME.' FAQ Admin');

    }

    public function category($id = null)
    {
        $this->data->icons   = array();
        $this->data->message = null;

        $this->data->categoryData = null;
        if ($id)
            $this->data->categoryData = $this->FAQ->getCategory($id);

        if ($this->input->post())
        {
            $this->form_validation->set_rules('name',        'Name',        'required');
            $this->form_validation->set_rules('description', 'Description', 'required|max_length[100]');

            if ($this->form_validation->run() === TRUE)
            {
                $data = array(
                    'name'        => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'icon'        => $this->input->post('icon')
                );

                if ($this->FAQ->storeCategory($data, $id))
                    redirect('adminpanel/faq');

                $this->data->message = 'Could not store the category';
            }
            else
                $this->data->message = $this->form_validation->error_string('', '<br/>');
        }

        $this->data->widgets = $this->loadPartialView('admin/faq/category');
        $this->loadView('member/shell', SITE_NAME.' FAQ Admin');
    }

    public function question($id = null)
    {
        $this->data->categories = $this->FAQ->getCategories();
        $this->data->message    = null;

        $this->data->questionData = null;
        if ($id)
            $this->data->questionData = $this->FAQ->getQuestion($id);

        if ($this->input->post())
        {
            $this->form_validation->set_rules('question', 'Question', 'required');
            $this->form_validation->set_rules('answer',   'Answer',   'required');

            if ($this->form_validation->run() === TRUE)
            {
                $data = array(
                    'question'    => $this->input->post('question'),
                    'answer'      => $this->input->post('answer'),
                    'category_id' => $this->input->post('category_id')
                );

                if ($this->FAQ->storeQuestion($data, $id))
                    redirect('adminpanel/faq');

                $this->data->message = 'Could not store the question';
            }
            else
                $this->data->message = $this->form_validation->error_string('', '<br/>');
        }

        $this->data->categories = dropdown($this->data->categories);

        $this->data->widgets = $this->loadPartialView('admin/faq/question');
        $this->loadView('member/shell', SITE_NAME.' FAQ Admin');
    }

    public function delete_category($categoryId)
    {
        $this->FAQ->deleteCategory($categoryId);
        redirect('adminpanel/faq');
    }

    public function delete_question($questionId)
    {
        $this->FAQ->deleteQuestion($questionId);
        redirect('adminpanel/faq');
    }
}