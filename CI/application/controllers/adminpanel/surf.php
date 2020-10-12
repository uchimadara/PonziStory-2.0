<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('admin.php');

class Surf extends Admin
{
    public function __construct()
    {
        parent::__construct(TRUE);

        $this->layoutData['title'] = 'Surf';
        $this->load->model('surf_model', 'Surf');
    }

    public function index()
    {
        parent::viewList('pending_surf_sites');
    }

    public function approve($id) {
        $site = $this->Surf->getSite($id);
        $this->Surf->updateSite($id, array('status' => 'active'));

        $user = $this->User->getData($site->user_id);
        $this->EmailQueue->store($user->email, 'Site Approved', "emails/surf/site_approval", array('name' => $site->name, 'username' => $user->username));

        echo json_encode(array('success' => 'success'));
    }

    public function reject($id) {
        $site = $this->Surf->getSite($id);
        $this->Surf->updateSite($id, array('status' => 'rejected'));
        $user = $this->User->getData($site->user_id);
        $this->EmailQueue->store($user->email, 'Site Rejected', "emails/surf/site_rejected", array('name' => $site->name, 'username' => $user->username));

        echo json_encode(array('success' => 'success'));
    }

    public function delete($id) {
        $site = $this->Surf->getSite($id);
        $this->Surf->updateSite($id, array('status' => 'deleted'));
        $user = $this->User->getData($site->user_id);
        $this->EmailQueue->store($user->email, 'Site Deleted', "emails/surf/site_deleted", array('name' => $site->name, 'username' => $user->username));

        echo json_encode(array('success' => 'success'));
    }
}