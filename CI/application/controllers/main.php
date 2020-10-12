<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        //$this->output->enable_profiler(PROFILER_SETTING);
    }

    public function index()
    {
        //$this->isGuest = $this->data->isGuest = !$this->ion_auth->logged_in();
        // $this->load->library('session');
//        $this->output->cache(60);
        $members     = $this->User->countMembers();
        $toy = $this->session->userdata('username');
        $this->load->helper('text');
        $this->load->model('testimonial_model', 'Testimonial');
        $this->load->model('payment_model', 'Payment');
        $this->load->model('referral_model', 'Referral');
        $this->load->model('news_model', 'News');
        $this->data->testimonials = '';
        $testimonial = $this->Testimonial->getRandom(10);
        foreach ($testimonial as $t) {
            $t->paid = $this->Payment->getTotalPaid($t->user_id);
            $this->data->testimonials .= $this->loadPartialView('layout/testimonial', $t);
        }

        $payments = $this->Payment->getLatest(20);
       // $payments = array_merge($this->Payment->getLatestL6(),$this->Payment->getLatestL5(),$this->Payment->getLatestL4(),$this->Payment->getLatestL3(),$this->Payment->getLatestL2(),$this->Payment->getLatestL1());
        $top = $this->Payment->topEarners();
        $totalP = $this->Payment->getTotalPaid2();
        $tt = $this->Payment->totaltransaction();
        $topR = $this->User->topRecruiter();
        $topD = $this->User->topRecruiter2();
        $topS = $this->User->topStates();
        $news = $this->News->getSome();

        

        $this->data->header = $this->loadPartialView('layout/header', compact('toy','members','payments','news'));
        $this->data->home = $this->loadPartialView('layout/home', compact('toy','members','payments','topS','topD','top','totalP','tt','testimonial','topR'));

        $this->data->latest_payments = $this->loadPartialView('layout/latest_payments', compact('payments'));


        $this->addStyleSheet('/layout/frontend/assets/js/revolution-slider/css/settings.css');
        $this->addStyleSheet('/layout/frontend/assets/js/revolution-slider/css/layers.css');
        $this->addStyleSheet('/layout/frontend/assets/js/revolution-slider/css/navigation.css');

        $this->addJavascript('/layout/frontend/assets/js/custom.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.actions.min.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.carousel.min.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.migration.min.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.navigation.min.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.parallax.min.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js');
        $this->addJavascript('/layout/frontend/assets/js/revolution-slider/js/extensions/revolution.extension.video.min.js');
        $this->setLayout('layout/frontend/shell');
        $this->loadView('layout/home');
    }


    public function blackpage() {


        $this->data->page_title = 'BLACKPAGE';
        $this->load->model('Teams_model', 'Teams');
        //  $this->data->content = $this->loadPartialView('news/list');
        $this->data->teams = $this->User->getOffenders();


        $this->addJavascript('/layout/frontend/assets/js/jquery.dataTables.min.js');
        $this->addStyleSheet('/layout/frontend/assets/css/jquery.dataTables.min.css');

        $this->layout = 'layout/frontend/shell';

        $this->loadView('blackpage', $this->data->page_title);
    }


    public function migration(){
//        var_dump($_POST);
        $this->load->model('ph_model', 'PH');
        $today = date('Y-m-d');
        $twoweeks = date('Y-m-d', strtotime($today . ' + 14 days'));


        $username =  $_POST['username'];
       $amount =  $_POST['amount'];
       $rec_amount =  $_POST['rec_amount'];
        //first check if such username exist and is active
       $user =  $this->User->getUsername2($username);
       $exist =  $this->PH->checkPHexistByUsername($username);
       if($user){
           if(!$exist){
               $data = array(
                   "user_id" => $user->id,
                   "first_bonus_id" => 4809,
                   "first_bonus_username" => "paul",
                   "first_bonus_status" => "1",
                   "second_bonus_id" => 4809,
                   "second_bonus_username" => "paul",
                   "second_bonus_status" => "1",
                   "username" => $user->username,
                   "amount" => $amount,
                   "rem_amount" => $amount,
                   "date_of_ph" => date('Y-m-d'),
                   "date_of_gh" => $twoweeks,
                   "status" => "4"
               );

               $data2 = array(
                   "user_id" => $user->id,
                   "first_bonus_id" => 4809,
                   "first_bonus_username" => "paul",
                   "first_bonus_status" => "1",
                   "second_bonus_id" => 4809,
                   "second_bonus_username" => "paul",
                   "second_bonus_status" => "1",
                   "username" => $user->username,
                   "amount" => $rec_amount,
                   "rem_amount" => $rec_amount,
                   "date_of_ph" => date('Y-m-d'),
                   "date_of_gh" => $twoweeks,
                   "status" => "4",
                   "recom" => 1,
               );

               $m = $this->PH->create($data);
               if($m) {
                   $this->PH->create($data2);
               }
               echo 200;
           }else{
               echo 300;
           }
       }else {
           echo 302;
       }
        //check if the person already exist
        //perform Insert twice (ph and Recom)
        //send response back

    }

    public function splash($username) {
        $this->load->model('referral_model', 'Referral');
        $refData = $this->ion_auth->where('username', $username)->limit(1, 0)->users()->row();

        $new_uri = 'splash';

        if ($cookie = get_cookie('ref')) {
//            if ($refData) {
//                $this->Referral->updateClick($cookie, $refData->id, $new_uri);
//            }
        } else {
            if ($refData) {
                $refId = $this->Referral->recordClick($refData->id, $new_uri);

                $cookie = array(
                    'name'   => 'ref',
                    'value'  => $refId,
                    'expire' => '86500'
                );

                $this->input->set_cookie($cookie);
            }
        }

        $this->load->view('splash', SITE_NAME);
    }

    public function referral($username)
    {
        if ($this->isGuest) {

            $user = $this->User->getBySalt($username, array('id'));

            if ($user) {
                $this->load->model('referral_model', 'Referral');

                $clickId = $this->Referral->recordClick($user->id, $this->uri->uri_string());

                $cookie = array(
                    'name'   => 'ref',
                    'value'  => $clickId,
                    'expire' => CACHE_ONE_DAY * 7
                );

                $this->input->set_cookie($cookie);
            } else{
                $user = $this->User->getByUsername($username, array('id'));
                $this->load->model('referral_model', 'Referral');

                $clickId = $this->Referral->recordClick($user->id, $this->uri->uri_string());

                $cookie = array(
                    'name'   => 'ref',
                    'value'  => $clickId,
                    'expire' => CACHE_ONE_DAY * 7
                );

                $this->input->set_cookie($cookie);
            }
        }

        redirect();
    }

    /** ************
     *
     * TESTIMONIALS
     *
     */

    public function testimonials() {
        $this->addJavascript('/layout/frontend/assets/js/jquery.dataTables.min.js');
        $this->addStyleSheet('/layout/frontend/assets/css/jquery.dataTables.min.css');

        $this->addStyleSheet('/layout/frontend/assets/css/testimonials.css');
        $this->load->model('testimonial_model', 'Testimonial');

        $this->data->testimonials = $this->Testimonial->getAll();
        $this->data->count = count($this->Testimonial->getAll());
        $this->data->page_title = 'Testimonials';
        $this->data->header = $this->loadPartialView('layout/header', compact('toy','members','payments'));

        $this->setLayout('layout/frontend/shell');
        $this->data->content = $this->loadPartialView('testimonials');
        $this->loadView('layout/default', 'Testimonials');
    }

    public function view_page($uri='home') {

        $this->setLayout('layout/frontend/shell');
        $this->load->model('Settings_model', 'Settings');

        $this->data->page_title = SITE_NAME;

        $this->data->activeUsers     = $this->User->countMembers();
        $this->data->upgradedMembers = $this->User->countUpgradedMembers();

        $page = $this->Settings->getPage($uri);
        if ($page) {

            $input = html_entity_decode($page->page_html);
            $this->data->content = $input;
            //$this->data->content = preg_replace_callback("/(&#[0-9]+;)/", function ($m) {
            //return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
            //}, $input);

            if (defined('LAUNCH_TIME') && LAUNCH_TIME > now()) $this->data->launchtime = LAUNCH_TIME - now();

            $this->loadView('layout/default', $page->title);
        } else {
            show_404();
            // $this->data->content = ''; //$this->loadPartialView('layout/under_construction');
            // $this->load->view('layout/default', wordify($uri));
        }
    }

    public function sblog($slug){
        // var_dump($slug);
        $this->load->model('blog_model', 'Blog');
        $this->load->model('payment_model', 'Payment');

//      //  $payments = array_merge($this->Payment->getLatestL6(),$this->Payment->getLatestL5(),$this->Payment->getLatestL4(),$this->Payment->getLatestL3(),$this->Payment->getLatestL2(),$this->Payment->getLatestL1());
//
//        foreach ($payments as $payment){
//            var_dump($payment);
//        }

        $this->data->blogs = $this->Blog->getAll();
        $this->data->page_title = 'Blog News';
        $this->setLayout('layout/frontend/shell');
        $this->loadView('layout/news');
    }

    public function article($slug){
//        $this->load->model('blog_model', 'Blog');
//
//        $this->data->blog = $this->Blog->by_slug($slug);
//        $this->data->page_title = 'Blog News';
        $this->setLayout('layout/frontend/shell');
        $this->data->content = $this->loadPartialView('article');
        $this->loadView('layout/default','Article');
    }



    public function orgchart() {
        $this->load->view('orgchart/simple-interactive');
    }
}
