<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include('admin.php');

class Admin_menu extends Admin {

    public function __construct() {
        parent::__construct(TRUE);

        $this->layoutData['title'] = 'Menu';
        $this->load->model('Menu_model', 'Menu');
        $this->layout = 'layout/admin/shell';
    }

    public function index() {
        $this->data->tabs = array();
        $this->data->menus = array();

        if (ENVIRONMENT != 'local') {
            $modules = $this->Menu->getModules(array('admin'));
        } else {
            $modules = $this->Menu->getModules();
        }
        foreach ($modules as $m) {
            $this->data->tabs[] = $m;
            $this->data->menus[] = $this->Menu->getAll($m);
        }

        $this->data->page_title = "Menu";
        $this->data->content = $this->loadPartialView('admin/menu/index');
        $this->loadView('layout/default', 'Admin Menu');
    }

    public function add() {
        if ($this->ajax) {
            $data = NULL;
            if ($this->form_validation->run('admin/add_menu')) {
                $data = $_POST;

                if ($this->Menu->insert($data)) {
                    $data = array('success' => 'Your data has been successfully saved. Redirecting...',
                                  'redirect' => array(
                                      'url'     => site_url('adminpanel/admin_menu'),
                                      'timeout' => 2,
                                      'hash'    => ''
                                  )
                    );
                } else {
                    $data = array('error' => renderErrors(array('ERROR:' => 'Something wrong happened')));
                }
            } else {
                $data = array('error' => renderErrors($this->form_validation->error_array()));
            }

            echo json_encode($data);
        } else {
           // $this->data->menus = $this->Menu->getMenu($id);
            $this->data->page_title = "Admin Add Menu";
            $this->data->content = $this->loadPartialView('admin/menu/add');
            $this->loadView('layout/default', 'Admin Add Menu');
        }
    }

    public function update($id) {
        if ($this->ajax) {
            $data = NULL;
            if ($this->form_validation->run('admin/add_menu')) {
                $data = $_POST;

                if ($this->Menu->update($id, $data)) {
                    $data = array(
                        'success' => 'Your data has been successfully saved. Redirecting...',
                        'redirect' => array(
                            'url' => site_url('adminpanel/admin_menu'),
                            'timeout' => 2,
                            'hash' => ''
                        )
                    );
                } else {
                    $data = array('error' => renderErrors(array('ERROR:' => 'Something wrong happened')));
                }
            } else {
                $data = array('error' => renderErrors($this->form_validation->error_array()));
            }

            echo json_encode($data);
        } else {
            $this->data->menus = $this->Menu->getMenu($id);
            $this->data->page_title = "Admin Update Menu";
            $this->data->content = $this->loadPartialView('admin/menu/update');
            $this->loadView('layout/default', 'Admin Update Menu');
        }
    }

    public function sorting($place, $parentId=0) {
        $this->Menu->updatePosition($place,$parentId);
    }

    public function delete($id) {
        $this->Menu->delete($id);
    }

}
