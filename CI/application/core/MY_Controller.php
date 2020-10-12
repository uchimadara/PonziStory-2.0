<?php

include(APPPATH . 'libraries/rmsList.php');
include(APPPATH . 'libraries/rmsForm.php');

class MY_Controller extends CI_Controller {

    protected $layout = 'layout/member/shell';
    private $css = array();
    private $js = NULL;
    private $fonts = '';
    protected $layoutData = array();
    protected $isGuest = TRUE;
    public $isAdmin = FALSE;
    protected $profile = NULL;
    protected $isMobile = NULL;
    protected $userGroups = NULL;
    protected $_path = '';
    protected $ajax = FALSE;
    protected $userId = 0;
    protected $userPage = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('user_model', 'User');
        $this->load->model('settings_model', 'Settings');
        $this->load->model('transaction_model', 'Transaction');

        $this->data = new stdClass();
        $this->data->ajax = $this->ajax = $this->input->is_ajax_request();

        $this->isGuest = $this->data->isGuest = !$this->ion_auth->logged_in();

        if ($this->isGuest) {

            $this->data->isAdmin = $this->data->isActive = FALSE;

            $this->data->userData = $this->User->getData(0);
            $this->profile        = & $this->data->userData;
            $this->userId         = $this->profile->id;

            $this->setLayout('layout/frontend/shell');

        } else { // LOGGED IN

            if ($this->data->userData = $this->ion_auth->user()->row()) {

                $this->profile        = & $this->data->userData;
                $this->userId         = $this->profile->id;

                if (!$this->ajax) {
                    $this->load->model('support_model', 'Ticket');
                    $this->data->unreadTickets = $this->Ticket->countUnread($this->data->userData->id);
                }
                $this->data->userData =$this->User->getData($this->userId);

                $this->load->model('my_account_model', 'Account');

                $this->data->isAdmin = $this->isAdmin = $this->profile ? $this->ion_auth->is_admin() : FALSE;
                $this->data->isActive = empty($this->profile->account_expires) || $this->profile->account_expires > now();
                $this->data->userSettings = $this->User->getSettings($this->userId);

            } else {
                $this->session->set_flashdata('Something went wrong. Please log in again.');
                redirect('login');
            }
        }

        // Load the cache (Memcached is using Couchbase otherwise default to file)
        $this->load->driver('cache', array(
            'adapter' => CACHE_METHOD_PRIMARY,
            'backup' => CACHE_METHOD_SECONDARY
        ));

        if (!$this->ajax) {
            $this->data->footer = $this->load->view('partial/footer', $this->data, TRUE);
        }
        // Set some defaults for the Form Validation messages (can be overridden in the descendant class)
        $this->form_validation->set_error_delimiters('<span class="frm_error">', '</span>');

        if (!$this->isAdmin) {
            $this->form_validation->set_message('required', '* required');
            $this->form_validation->set_message('min_length', '<span style="display: none;">%s</span>* too short (min %s chars)');
            $this->form_validation->set_message('max_length', '<span style="display: none;">%s</span>* too long (max %s chars)');
            $this->form_validation->set_message('exact_length', '<span style="display: none;">%s</span>* should be %s chars long');
            $this->form_validation->set_message('matches', '* mismatch');
            $this->form_validation->set_message('valid_email', '* invalid');
            $this->form_validation->set_message('required', '* required');
            $this->form_validation->set_message('less_than', '* too high');
            $this->form_validation->set_message('greater_than', '* too low');
            $this->form_validation->set_message('is_numeric', '* invalid');
            $this->form_validation->set_message('is_natural_no_zero', '* invalid');
        }

        if (ENVIRONMENT == 'production') {
//            if (($response = curl_get('http://landwhale.net/license.php')) != 'ok') {
//                echo $response;
//                die ('Invalid license'.$response);
//            }
        }

        $this->userGroups = array();
        if (!$this->isGuest) {
            $groups = $this->ion_auth->get_users_groups($this->profile->id)->result();
            foreach ($groups as $group)
                $this->userGroups[] = $group->name;
        }
        $this->data->userGroups = & $this->userGroups;

        $this->data->globalSiteStats = array(
            'total_members' => $this->User->countMembers()
        );
    }

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    protected function addJavascript($script) {
        if ((substr($script, 0, 4) == 'http') || ($script{0} == '/'))
            $this->js .= '<script type="text/javascript" src="' . $script . '"></script>';
        else
            $this->js .= '<script type="text/javascript">' . $script . '</script>';

    }

    // ieVersion can be any of the following:
    // IE, !IE, IE 6, IE 7, ..., IE lt 7, IE gt 7, ..., IE lte 7, IE gte 7, etc...
    protected function addStyleSheet($style, $ieVersion = NULL) {
        if ((substr($style, 0, 4) == 'http') || ($style{0} == '/'))
            $css = '<link rel="stylesheet" href="' . $style . '" type="text/css" media="all" />';
        else
            $css = '<style type="text/css" media="all">' . $style . '</style>';

        $this->css[$ieVersion ? $ieVersion : ''][] = $css;
    }

    protected function processStyleSheet() {
        $res = '';
        foreach ($this->css as $ver => $css) {
            $temp = implode("\n", $css);

            if ($ver)
                $temp = "\n<!--[if $ver]>\n" . $temp . "\n<![endif]-->";
            $res .= $temp . "\n";
        }
        return $res;
    }

    protected function loadView($view, $title = '', $return = FALSE) {

        $isGuest = $this->isGuest ? "true" : "false";
        $isActive = $this->isActive ? "true" : "false";
        $baseUrl = base_url();
        $launchTime = defined("LAUNCH_TIME") ? LAUNCH_TIME : '0';
        $currentDateTime = date('G');

        $js = <<<JS
var mim = {
   baseUrl: '{$baseUrl}',
   assetPath: '/assets/',
   isGuest: {$isGuest},
   isActive: {$isActive},
   launchtime: {$launchTime},
   alertInterval: 10000,
   alertCount: 0,
   teAlert: 'bell'
};
var currentDateTime = {$currentDateTime};
JS;
        $this->addJavascript($js);

        $this->_custom_scan();

        $layoutData['title'] = $title;
        $layoutData['page_title'] = SITE_NAME . ' ' . $title;
        $layoutData['fonts'] = $this->fonts;
        $layoutData['server_time'] = date(DEFAULT_DATETIME_FORMAT, now());
        $layoutData['year'] = date('Y', now());

        $layoutData['SITE_DESCRIPTION'] = SITE_DESCRIPTION;
        $layoutData['SITE_KEYWORDS'] = SITE_KEYWORDS;
        $layoutData['SITE_LOGO'] = SITE_LOGO;
        $layoutData['SITE_NAME'] = SITE_NAME;

        $layoutData['flash_message'] = $this->load->view('layout/flash_message', NULL, TRUE);


        if ($view != '') {
            $members     = $this->User->countMembers();
            $toy = $this->session->userdata('username');
            $this->load->model('payment_model', 'Payment');
            $this->data->testimonials = '';

           $payments = $this->Payment->getLatest(20);
             // $payments = array_merge($this->Payment->getLatestL6(),$this->Payment->getLatestL5(),$this->Payment->getLatestL4(),$this->Payment->getLatestL3(),$this->Payment->getLatestL2(),$this->Payment->getLatestL1());

            $layoutData['header'] = $this->load->view('layout/header',compact('toy','members','payments'), TRUE);
            $layoutData['content'] = $this->load->view($view, $this->data, TRUE);
            $layoutData['latest_payments'] = $this->load->view($view, $this->data, TRUE);
        } else {
            $this->addJavascript('/layout/frontend/assets/js/jquery.liMarquee.js');
            $this->addJavascript('/layout/frontend/assets/js/main.js');
            $this->addStylesheet('/layout/frontend/assets/css/liMarquee.css');
            $layoutData['content'] = $this->load->view($view, $this->data, TRUE);
            $layoutData['testimonials'] = $this->data->testimonials;
            $layoutData['latest_payments'] = $this->data->latest_payments;
            $layoutData['header'] = $this->data->header;
            $layoutData['home'] = $this->data->home;
            $layoutData['article'] = $this->data->article;
        }

        $widget = new Widget();

        $admin = strpos($this->layout, 'admin') !== FALSE;

        $menu = strpos($this->layout, 'frontend') !== FALSE ? 'guest' : ($admin ? 'admin' : 'member');

        $layoutData['menu'] = $widget->run('menu', $menu, $this->isGuest);
        $layoutData['sponsor'] = '';


        $this->load->library('parser');
        $layoutData['slider'] = '';

        if ($this->uri->uri_string() == '') {
            if (defined('SLIDER') && SLIDER != '') {
                $layoutData['slider'] = file_get_contents(FCPATH.SLIDER);
                //$layoutData['slider'] = file_get_contents(FCPATH.SLIDER);
            }

            $clickId = get_cookie('ref');

            if ($clickId) {
                $this->load->model('referral_model', 'Referral');
                $this->data->clickData = $this->Referral->getClick($clickId);
                $sponsor               = $this->User->getData($this->data->clickData->user_id, array('first_name', 'last_name', 'username'));
                if (empty($sponsor->first_name) && empty($sponsor->last_name)) $sponsor->first_name = $sponsor->username;
                if ($sponsor->locked == 0 || $sponsor->account_level > 0) {
                    $layoutData['sponsor'] = $this->parser->parse(FCPATH.'layout/frontend/sponsor', $sponsor, TRUE);
                }
            }
            if ($layoutData['sponsor'] == '') {
                if (!$this->isGuest) {
                    $sponsor = &$this->profile;
                } else {
                    $sponsor = $this->User->getData(DEFAULT_USER_ID, array('first_name', 'last_name'));
                }
                $layoutData['sponsor'] = $this->parser->parse(FCPATH.'layout/frontend/sponsor', $sponsor, TRUE);
            }
        }

        if (defined('FOOTER') && FOOTER != '') {
            $layoutData['footer'] = file_get_contents(FCPATH.FOOTER);
        } else {
            $layoutData['footer'] = '';
        }

        if (!$this->isGuest) {

            $layoutData['stats'] = $widget->run('stats', ($admin ? 'admin' : 'member'));
            $layoutData['username'] = $this->profile->username;

            if ($menu == 'member') {
                $layoutData['news_modal'] = isset($this->data->newsModal) ? $this->data->newsModal : '';
                $layoutData['text_ads'] = $widget->run('textAds', 5, $this->userId, $this->profile->account_level);
            }
        }

        $layoutData['js']  = $this->js;
        $layoutData['css'] = $this->processStyleSheet();

        $body = $this->parser->parse(FCPATH.$this->layout, $layoutData, TRUE); //passing True as the last parameter makes the parser return the string instead of passing it to the output class.

        if ($return) {
            return $body;
        }  else {
            echo $body;
        }
        return TRUE; // $this->load->view($this->layout, $layoutData, $return);
    }

    protected function loadPartialView($view, $data = NULL) {
        if ($data)
            return $this->load->view($view, $data, TRUE);
        else
            return $this->load->view($view, $this->data, TRUE);
    }

    protected function select_balance() {
        $userId = $this->profile->id;

        $exclusion = $this->input->post('exclude');
        if ($exclusion)
            $exclusion = explode(' ', $exclusion);
        $balances = $this->PaymentMethod->exclude($exclusion)->getBalancesList($userId, '');

        echo $this->loadPartialView('balance/list', compact('balances'));
    }

    /* ============
     * callback used for form validation
     */

    function valid_url($param) {
        if (!preg_match('((http|https):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=‌​%&amp;/~\+#])?)', $param)) {
            $this->form_validation->set_message('valid_url', '* invalid');
            return FALSE;
        }

        return TRUE;
    }

    function not_required_valid_url($param) {

        if ($param != '') {
            if (!preg_match('((http|https):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=‌​%&amp;/~\+#])?)', $param)) {
                $this->form_validation->set_message('valid_url', '* invalid');
                return FALSE;
            }
        }

        return TRUE;
    }

    function valid_secret($param) {
        if (empty($this->profile->secret_answer)) {
            $this->form_validation->set_message('valid_secret', '* not set. Set this up in your account profile.');
            return FALSE;
        }
        if ($param == $this->profile->secret_answer) {
            return TRUE;
        }
        $this->form_validation->set_message('valid_secret', '* invalid');
        return FALSE;
    }

    /*     * ****************
     * LIST METHODS
     */

    protected function viewList($listName, $ret = FALSE) {

        $this->data->page_title = wordify($listName);
        if (($perPage = $this->session->userdata('perPage')) === FALSE)
            $perPage = DEFAULT_ITEMS_PER_PAGE;

        if (file_exists(APPPATH . 'libraries/rmsLists/' . $listName . '.php')) {
            include(APPPATH . 'libraries/rmsLists/' . $listName . '.php');
            $this->data->list = new $listName(substr($this->_path, 0, -1) . '_lists/', $listName, $this->_path . "getList/$listName/");
        } else {
            $this->data->list = new RMSList(substr($this->_path, 0, -1) . '_lists/', $listName, $this->_path . "getList/$listName/");
        }
       // $this->data->list->getPartial(1, $perPage);

        if ($ret) {
            if (file_exists(APPPATH.'views/'.$this->_path.'partial/list.php')) {
                return $this->loadPartialView($this->_path.'partial/list');
            } else {
                return $this->loadPartialView('partial/list');
            }
        }

        $this->data->content = $this->loadPartialView($this->_path . 'list');
        $this->loadView('layout/default', $this->data->page_title);
    }

    protected function getList($listName, $sortCol = '', $sortDir = '', $page = 1, $perPage = '') {

        log_message('debug', "MY_Controller::getList sortCol=$sortCol, sortDir=$sortDir, page=$page, perPage=$perPage");
        if ($perPage == '') {
            if (($perPage = $this->session->userdata('perPage')) === FALSE) {
                $perPage = DEFAULT_ITEMS_PER_PAGE;
            }
        } else {
            $this->session->set_userdata('perPage', $perPage);
        }

        if (file_exists(APPPATH . 'libraries/rmsLists/' . $listName . '.php')) {
            include(APPPATH . 'libraries/rmsLists/' . $listName . '.php');
            $report = new $listName(substr($this->_path, 0, -1) . '_lists/', $listName, $this->_path . "getList/$listName/", $sortDir, $sortCol);
        } else {
            $report = new RMSList(substr($this->_path, 0, -1) . '_lists/', $listName, $this->_path . "getList/$listName/", $sortDir, $sortCol);
        }

        if (($params = $this->input->get_post()) !== FALSE) {
            $report->set_where($params);
        }

        $table = $report->getPartial($page, $perPage)->render();

        if ($this->input->is_ajax_request()) {
            echo $table;
        } else {
            return $table;
        }
    }

    protected function renderList($listName, $sortCol = '', $sortDir = '', $page = 1, $perPage = '') {

        log_message('debug', "MY_Controller::getList sortCol=$sortCol, sortDir=$sortDir, page=$page, perPage=$perPage");
        if ($perPage == '') {
            if (($perPage = $this->session->userdata('perPage')) === FALSE) {
                $perPage = DEFAULT_ITEMS_PER_PAGE;
            }
        } else {
            $this->session->set_userdata('perPage', $perPage);
        }

        if (file_exists(APPPATH . 'libraries/rmsLists/' . $listName . '.php')) {
            include(APPPATH . 'libraries/rmsLists/' . $listName . '.php');
            $report = new $listName(substr($this->_path, 0, -1) . '_lists/', $listName, $this->_path . "getList/$listName/", $sortDir, $sortCol);
        } else {
            $report = new RMSList(substr($this->_path, 0, -1) . '_lists/', $listName, $this->_path . "getList/$listName/", $sortDir, $sortCol);
        }

        if (($params = $this->input->get()) !== FALSE) {
            $report->set_where($params);
        }

        return $report->getPartial($page, $perPage)->render();
    }

    /*     * *********
     * @param $formName
     * @param string $id
     * @return array|string
     */

    public function doForm($formName, $id = '', $data = array()) {

        $this->load->model('db_form');
        $form = new RMSForm(substr($this->_path, 0, -1) . '_forms/', $formName);

        if ($_POST) {
            $post = $this->input->post();
            $result = $form->validate($post); // returns data['success'] or data['errorElements']

            if (isset($result['errorElements'])) {

                return $result;
            } else {

                $this->db_form->set_table($form->get_table());

                if ($id != '') {
                    if (isset($_POST['user_id']) && !$this->isAdmin) {
                        $row = $this->db_form->get_data($id, array('user_id'));
                        if ($row && $row['user_id'] != $this->userId) {
                            return array('error' => 'invalid access');
                        }
                    }

                    $this->db_form->update($id, $result['success']);

                } else {
                    $id = $this->db_form->create($result['success']);
                }

                return $id;
            }
        } else {
            if ($id != '') {
                $this->db_form->set_table($form->get_table());
                $data = $this->db_form->retrieve($id);
            }

            $this->data->form = $form->render($data);
            $this->data->formName = $formName;
            $this->data->buttons = $form->buttons();

// This would be the admin user's salt when it's admin posting the user form - bad idea.
            $this->data->salt = random_string();
            $this->session->set_userdata('salt', $this->data->salt);
//

            $title = $form->get_title();
            $title = ($id != '') ? $title . ' #' . $id : "New " . $title;

            if ($this->ajax) {
                $this->data->title = $title;
                echo $this->loadPartialView($this->_path . 'form');
            } else {

                $this->data->content = $this->loadPartialView($this->_path . 'form');
                $this->loadView('layout/default', $title);
            }
        }
    }

    public function _custom_scan() {
        $this->load->helper('directory');

        if ($css = directory_map(FCPATH.'custom/css')) {
            foreach ($css as $c) {
                $this->addStyleSheet(SITE_ADDRESS.'custom/css/'.$c);
            }
        }
        if ($js = directory_map(FCPATH.'custom/js')) {
            foreach ($js as $j) {
                $this->addJavascript(SITE_ADDRESS.'custom/js/'.$j);
            }
        }
    }
}
    function curl_get($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

