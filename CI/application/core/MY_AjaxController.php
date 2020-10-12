<?php

class MY_AjaxController extends CI_Controller {

    public $data;
    public $isGuest = TRUE;
    public $isAdmin = FALSE;
    public $profile = NULL;
    public $userId = 0;
    public $userGroups = array();

    public function __construct() {
        parent::__construct();

        if (!$this->input->is_ajax_request() &&  ENVIRONMENT == 'production' ) {
               // show_404();
        }

        $this->data = new stdClass();
        $this->data->ajax = TRUE;
        $this->isGuest =
        $this->data->isGuest = !$this->ion_auth->logged_in();
        $this->data->isAdmin =
        $this->isAdmin = !$this->isGuest ? $this->ion_auth->is_admin() : FALSE;

        $this->load->model('user_model', 'User');

        $this->data->userData = $this->ion_auth->user()->row();
        $this->profile        =& $this->data->userData;
        $this->userId         = $this->profile->id;

        if (!$this->isGuest) {
            $this->data->userSettings = $this->User->getSettings($this->userId);

            $groups = $this->ion_auth->get_users_groups($this->profile->id)->result();
            foreach ($groups as $group)
                $this->userGroups[] = $group->name;
        }

        $this->load->driver('cache', array(
            'adapter' => CACHE_METHOD_PRIMARY,
            'backup'  => CACHE_METHOD_SECONDARY
        ));
    }

    public function index() {
        show_error('Invalid access.');
    }

    protected function requireLogon() {

        if ($this->isGuest) {
            echo json_encode(array('error' => 'Not logged in.'));
            exit;
        }

    }

    public function setting($setting, $val) {
        if($setting == 'lock_my_ip' && $val==1){
            $this->ion_auth->update($this->userId, array('ip_address' => $this->input->ip_address()));
        }

        $this->User->addSetting($this->userId, $setting, $val);
    }

    protected function loadPartialView($view, $data = NULL) {
        if ($data)
            return $this->load->view($view, $data, TRUE);
        else
            return $this->load->view($view, $this->data, TRUE);
    }

    /*============
   * callback used for form validation
   */
    function valid_url($param) {
        if (!preg_match('((http|https):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=‌​%&amp;/~\+#])?)', $param)) {
            $this->form_validation->set_message('valid_url', '* invalid');
            return FALSE;
        }

        return TRUE;
    }
}