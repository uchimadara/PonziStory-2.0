<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
    public function __construct() {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            show_error('Not logged in. '.anchor(site_url('login'), 'Login'));
        } elseif ($this->ion_auth->is_admin() || ($this->ion_auth->is_support())) {

            $this->addStyleSheet(asset('styles/admin/style.css'));
            $this->addJavascript(asset('scripts/admin/admin.js'));
            $this->addJavascript(asset('scripts/searchList.js'));

            $this->load->model('support_model', 'Support');

            $this->output->enable_profiler(PROFILER_SETTING);

            $this->data->openTickets = $this->Support->countOpenTickets();

            $this->layout             = 'layout/admin/shell';
        }
        else {
            show_error('Unauthorized access.');

        }

    }

    public function bootstrap($page = 'dashboard') {

        if (!method_exists($this, $page)) {
            $page = 'dashboard';
        }

        $this->data->page_title = $page;

        $html = $this->$page();

        if ($this->ajax) {
            $state = array(
                'html'      => $html,
                'pageTitle' => $this->data->page_title,
                'url'       => SITE_ADDRESS.'back_office/'.$page.''
            );
            echo json_encode($state);
            exit(0);
        }

        $this->data->content = & $html;
        $this->layout        = 'layout/admin/shell';
        $this->loadView('layout/default', $this->data->page_title);
    }

    public function dashboard() {
       // var_dump($this->ion_auth->is_admin());
        $this->data->page_title = 'Admin Dashboard';

        $this->data->activeUsers   = $this->User->countMembers();
        $this->data->members       = $this->User->countMemberTypes();
        //$this->data->activeMembers = $this->User->countUpgradedMembers();

        $this->data->totalUpgrades = 0;
        if ($this->data->members) {
            foreach ($this->data->members as $a => $t) if ($a != 'Free') $this->data->totalUpgrades += $t;
        }

        $this->data->userStats  = array(
            'Signups'  => array(
                'url'   => 'stats/member_registrations',
                'count' => $this->data->activeUsers
            ),
            'Upgrades' => array(
                'url'   => 'stats/member_upgrades',
                'count' => $this->data->totalUpgrades
            ),
        );
        if ($this->data->totalUpgrades) {
            $this->data->userCharts = array();
            foreach ($this->data->members as $level => $c) {
                $this->data->userCharts[$level] = number_format($c/$this->data->totalUpgrades*100);
            }
        }
        $this->data->userCharts['Free'] = number_format(($this->data->activeUsers - $this->data->totalUpgrades)/($this->data->totalUpgrades?$this->data->totalUpgrades:1)*100);

        $this->data->userCharts['Members Online'] = number_format($this->User->getUsersOnlineCount()/$this->data->activeUsers*100);


//        $this->data->textAdStats        = $this->Campaign->getTextAdStats();
//        $this->data->unallocatedCredits = $this->Campaign->unallocatedCredits();
//        $this->addJavascript(asset('member/js/jquery.js'));
//        $this->addJavascript(asset('bootstrap/js/jquery.flot.pie.min.js'));
        return $this->loadPartialView('admin/index');
    }
    public function getMemberTypes() {

        $activeUsers   = $this->User->countMembers();
        $activeMembers = $this->User->countActiveMembers();

        $totalUpgrades = 0;
        foreach ($activeMembers as $a => $t) $totalUpgrades += $t;

        $activeMembers['Free Trial'] = $activeUsers - $totalUpgrades;


//        $out["cols"] = array(
//            array(
//                "id"      => "",
//                "label"   => "Level",
//                "pattern" => "",
//                "type"    => "string"),
//            array(
//                "id"      => "",
//                "label"   => "Total",
//                "pattern" => "",
//                "type"    => "number"),
//        );
//
//        $out["rows"] = array();
//        foreach ($activeMembers as $t => $d) {
//            $out["rows"][] = array("c" => array(
//                array('v' => $t.' ('.$d.')', 'f' => NULL),
//                array('v' => $d, 'f' => NULL)
//            ));
//        }

        echo json_encode($activeMembers);
    }

    /*******************
     * Member Hierarchy
     *
     */
    public function userTree() {
        $this->load->model('referral_model', 'Referral');

        $this->data->locate_id = '';
        if (!empty($_GET['locate'])) $this->data->locate_id = $_GET['locate'];

        $tree[] = $this->Referral->getReferralTree(1);

        $this->data->totalRefCount = 0;
        $this->data->refTree       = $this->drawTree($tree); //.'</ul>';

        $this->data->page_title = 'Referrals';
        echo $this->loadView('admin/userTree', 'User Tree');
    }

    function drawTree(&$tree, $level = 0) { // should be moved to a view, but for now...

        $s = '';
        $this->data->totalRefCount += count($tree);
        for ($i = 0; $i < count($tree); $i++) {

            if (intval($tree[$i]['id']) == intval($this->data->locate_id))
                $hilite = 'hilite';
            else
                $hilite = '';

            $s .= "<li><span class='folder'><span class='refBox username $hilite'>";
            if ($tree[$i]['account_level'] != 'Free') {

                $s .= img(array(
                            'src'   => asset('frontend/img/about/'.strtolower($tree[$i]['account_level']).'.jpg'),
                            'style' => "position:relative; top:-2px; left:-2px; width:16px;",
                            'alt'   => '',

                        ), FALSE).'&nbsp;';
            }
            $s .= anchor(SITE_ADDRESS.'admin/form/user/'.$tree[$i]['id'], $tree[$i]['name'], 'id="'.$tree[$i]['id'].'"')."</span>";
            if ($level > 0) {
                $s .= ' <span class="refBox">referrals: '.count($tree[$i]['users']).'</span>';
                $s .= ' <span class="refBox">earned: '.money($this->Referral->getEarnings($tree[$i]['id'])).'</span>';
                $s .= ' <span class="refbox"><a href="'.SITE_ADDRESS.'admin/email_user/'.$tree[$i]['id'].'">'.$tree[$i]['email'].'</a></span>';
            }
            $s .= "</span>";

            if (!empty($tree[$i]['users'])) {
                $s .= '<ul>';
                $s .= $this->drawTree($tree[$i]['users'], $level + 1);
                $s .= '</ul>';
            }
            $s .= '</li>';
        }
        return $s;
    }

    /*******************
     * SUPPORT
     *
     */
    public function support() {

        $status           = 'open';
        $this->page_title = 'Support Tickets';

        $this->data->ticketcount       = $this->Support->countTickets();
        $this->data->guestTicketCount  = $this->Support->getCount(NULL, TRUE, $status);
        $this->data->memberTicketCount = $this->Support->getCount(NULL, FALSE, $status);
        $this->data->Supcount = $this->Support->countSupportWOrks($this->profile->id);

//        $this->data->guestTickets       = $this->Support->getSummary(NULL, true, $status);
//        $this->data->memberTickets = $this->Support->getSummary(NULL, false, $status);
        return $this->loadPartialView('admin/support/tickets');
    }

    /*************
     * Following overrides is to secure access as admin
     * @param $listName
     */
    public function viewList($listName, $ret = FALSE) {

        $buttons     = array(
            'page'           => array(
                array(
                    'uri'   => 'admin/form/page',
                    'title' => 'New Page',
                    'extra' => ''
                )
            ),
            'purchase_items' => array(
                array(
                    'uri'   => 'admin/form/purchase_item',
                    'title' => 'New Item',
                    'extra' => ''
                )
            ),
            'surf_settings' => array(
                array(
                    'uri'   => 'admin/form/surf_settings',
                    'title' => 'Add Surf Settings',
                    'extra' => ''
                )
            ),
            'currency' => array(
                array(
                    'uri'   => 'admin/form/currency',
                    'title' => 'Add New Currency',
                    'extra' => ''
                )
            ),
            'cms_menu'           => array(
                array(
                    'uri'   => 'admin/form/cms_menu',
                    'title' => 'New menu item',
                    'extra' => ''
                )
            ),
            'news'           => array(
                array(
                    'uri'   => 'admin/form/news',
                    'title' => 'Add news',
                    'extra' => ''
                )
            ),
            'ad_placement'           => array(
                array(
                    'uri'   => 'admin/form/ad_placement',
                    'title' => 'Add new place',
                    'extra' => ''
                )
            ),
            'products' => array(
                array(
                    'uri'   => 'admin/form/product',
                    'title' => 'Add new product',
                    'extra' => ''
                )
            ),



        );
        $this->_path = 'admin/';
        if (array_key_exists($listName, $buttons)) $this->data->buttons = $buttons[$listName];

        return parent::viewList($listName, $ret);
    }

    public function getList($listName, $sortCol = '', $sortDir = '', $page = 1, $perPage = '') {
        $this->_path = 'admin/';
        parent::getList($listName, $sortCol, $sortDir, $page, $perPage);
    }

    public function delete_item($table, $id) {
        $this->load->model('db_form');

        $this->db_form->set_table($table);
        $this->db_form->delete(array($id));

        echo json_encode(array('success' => 'success'));
        return;
    }

    public function payment_update($id, $approve,$allow = NULL) {

        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model', 'PH');
        $this->load->model('gh_model', 'GH');
        $this->load->model('email_model', 'EmailQueue');

        $payment = $this->Payment->get($id);

        if ($payment->approved || $payment->rejected && $allow == NULL) {
            echo json_encode(array('error' => 'Donation already processed.'));
            return;
        } else {

            $data = array('update_user_id' => $this->userId);
            if ($approve == '1') {
                $this->load->model('referral_model', 'Referral');

                $payment->approved = $data['approved'] = 1;
                $payment->rejected = $data['rejected'] = NULL;

                $this->data->message = money($payment->price).' '.$payment->title.' donation from '.$payment->username.' approved.';

                $this->Referral->addPayment($payment->payee_user_id, $payment->payer_user_id, $payment->amount);

                $tpay = $this->User->getSingle($payment->payer_user_id);
                $recycle = (int)$tpay->recycle;
                $this->User->update($payment->payer_user_id, array(
                    'recycle' => $recycle + 1,
                    'plan' => $payment->amount,

                ));

                $this->PH->updatePHapprove($payment->ph_id, array(
                    'status' => 4,
                ));
                $this->GH->updateGHapprove($payment->gh_id, array(
                    'status' => 4,
                ));

                $tpay = $this->User->getSingle($payment->payer_user_id);
                $level = (int)$tpay->account_level;

                if($level == 0){
                    $tm = now() + 1209600; // 2 weeks
                }
                if($level == 1){
                    $tm = now() + 2592000; // 1 month
                }

                if($level == 2){
                    $tm = now() + 5184000; // 2 month
                }
                if($level == 3){
                    $tm = now() + 10368000; // 4 month
                }
                if($level == 4){
                    $tm = now() + 20736000; // 8 month
                }


               // $this->User->update($payment->payer_user_id, array('account_level' => $payment->code, 'account_expires' => $tm,'soft_hide' => 0));

                $payer = $this->User->getData($payment->payer_user_id, array('username', 'email'));

                $this->EmailQueue->store($payer->email, '['.SITE_NAME.'] Payment Approved', 'emails/cashier/payment_approved', compact('payer', 'payment'));
            }
            else {
                $payment->approved = $data['approved'] = NULL;
                $payment->rejected = $data['rejected'] = 1;

                $this->PH->updatePHapprove($payment->ph_id, array(
                    'status' => 6,
                ));
                $this->GH->updateGHapprove($payment->gh_id, array(
                    'status' => 6,
                ));
                $m = $this->Payment->updateTimeup($id, array(
                    'rejected' => 1,
                    'updated' => now()
                ));
//                if ($m && empty($payment->proof_img)) {
//                    $this->User->update($payment->payee_user_id, array('locked' => '1'));
//                }else {
//                    $this->User->update($payment->payer_user_id,array('locked'=>'1', 'reason' => "False ".$paint['reason']. " Accusation"));
//                }



                $payee = $this->User->getData($payment->payee_user_id, array('username', 'email'));
                $payer = $this->User->getData($payment->payer_user_id, array('username', 'email'));

                $this->EmailQueue->store($payee->email, '['.SITE_NAME.'] Payment Rejected', 'emails/cashier/payment_rejected', compact('payer', 'payee', 'payment'));
            }

            $this->Payment->update($id, $data);
        }
        echo json_encode(array('success' => TRUE));
    }

    public function requeue($id,$approve, $allow = NULL){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model', 'PH');
        $this->load->model('gh_model', 'GH');
        $this->load->model('email_model', 'EmailQueue');

        $payment = $this->Payment->get($id);

        if ($payment->deleted) {
            echo json_encode(array('error' => 'Donation already processed.'));
            return;
        } else {

           // $data = array('update_user_id' => $this->userId);

            if ($approve == '1') {

               // $tpay = $this->User->getSingle($payment->payer_user_id);
                //$recycle = (int)$tpay->recycle;
                $zaph = $this->PH->getProblemPh($payment->ph_id);
                $rph = (int)$zaph->rem_amount;
                $cph = (int)$payment->amount;
                $this->User->update($payment->payee_user_id, array(
                    'locked' =>  1,
                    'reason' => "Defaulter/Fake POP/ scammer",

                ));

                $this->PH->updatePHapprove($payment->ph_id, array(
                    'status' => 1,
                    'rem_amount' => $rph + $cph
                ));

                $this->Payment->update($id, array(
                    'deleted' => 1,
                ));

               

                // $this->User->update($payment->payer_user_id, array('account_level' => $payment->code, 'account_expires' => $tm,'soft_hide' => 0));

              //  $payer = $this->User->getData($payment->payer_user_id, array('username', 'email'));

               // $this->EmailQueue->store($payer->email, '['.SITE_NAME.'] Payment Approved', 'emails/cashier/payment_approved', compact('payer', 'payment'));
            }
            else {
                $zagh = $this->GH->getProblemGh($payment->gh_id);
                $rgh = (int)$zagh->rem_amount;
                $cgh = (int)$payment->amount;
                $this->User->update($payment->payer_user_id, array(
                    'locked' =>  1,
                    'reason' => "Defaulter/Fake POP/ Scammer",

                ));

                $this->GH->updateGHapprove($payment->gh_id, array(
                    'status' => 1,
                    'rem_amount' => $rgh + $cgh,
                    'date_added' => date('Y-m-d'),
                ));
                $this->Payment->update($id, array(
                    'deleted' => 1,
                    'punishment' => 1,
                    'expired' => (int)$payment->expired + 432000,
                ));



//                $payee = $this->User->getData($payment->payee_user_id, array('username', 'email'));
//                $payer = $this->User->getData($payment->payer_user_id, array('username', 'email'));
//
//                $this->EmailQueue->store($payee->email, '['.SITE_NAME.'] Payment Rejected', 'emails/cashier/payment_rejected', compact('payer', 'payee', 'payment'));
            }

           // $this->Payment->update($id, $data);
        }
        echo json_encode(array('success' => TRUE));
    }

    public function requeueBunch($approve=1){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model', 'PH');
        $this->load->model('gh_model', 'GH');
        $this->load->model('email_model', 'EmailQueue');

        $paym = $this->Payment->getPendingRejected2();

        foreach ($paym as $payment){

        if ($payment->deleted) {
           echo "No";
        } else {

            // $data = array('update_user_id' => $this->userId);

            if ($approve == '1') {

                // $tpay = $this->User->getSingle($payment->payer_user_id);
                //$recycle = (int)$tpay->recycle;
                $zagh = $this->GH->getProblemGh($payment->gh_id);
                $rgh = (int)$zagh->rem_amount;
                $cgh = (int)$payment->amount;
                $this->User->update($payment->payer_user_id, array(
                    'locked' =>  1,
                    'reason' => "Defaulter/Fake POP/ Scammer",

                ));

                $this->GH->updateGHapprove($payment->gh_id, array(
                    'status' => 1,
                    'rem_amount' => $rgh + $cgh
                ));
                $this->Payment->update($payment->id, array(
                    'deleted' => 1,
                    'punishment' => 1,
                    'expired' => (int)$payment->expired + 172800,
                ));



                // $this->User->update($payment->payer_user_id, array('account_level' => $payment->code, 'account_expires' => $tm,'soft_hide' => 0));

                //  $payer = $this->User->getData($payment->payer_user_id, array('username', 'email'));

                // $this->EmailQueue->store($payer->email, '['.SITE_NAME.'] Payment Approved', 'emails/cashier/payment_approved', compact('payer', 'payment'));
            }
            echo "Successful2";
    }
    echo "Successful";
    }
    }

    public function remergethis($id,$approve){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model', 'PH');
        $this->load->model('gh_model', 'GH');
        $this->load->model('email_model', 'EmailQueue');

        $payment = $this->Payment->get($id);

        if ($payment->approved == NULL && $payment->rejected == NULL) {
            echo json_encode(array('error' => 'Donation already processed.'));
            return;
        } else {

            // $data = array('update_user_id' => $this->userId);

            if ($approve == '1') {

                // $tpay = $this->User->getSingle($payment->payer_user_id);
                //$recycle = (int)$tpay->recycle;
                $zaph = $this->PH->getProblemPh($payment->ph_id);
                $rph = (int)$zaph->rem_amount;
                $cph = (int)$payment->amount;
                $this->User->update($payment->payer_user_id, array(
                    'locked' =>  0,
                ));

               $m =  $this->PH->updatePHapprove($payment->ph_id, array(
                    'status' => 2,
                    'rem_amount' => $cph
                ));
               if($m) {
                   $this->GH->updateGHapprove($payment->gh_id, array(
                       'status' => 2,
                       'rem_amount' => $cph
                   ));


                   $this->Payment->update($id, array(
                       'approved' => NULL,
                       'rejected' => NULL,
                       'expired' => (int)$payment->expired + 86400,
                   ));
               }



                // $this->User->update($payment->payer_user_id, array('account_level' => $payment->code, 'account_expires' => $tm,'soft_hide' => 0));

                //  $payer = $this->User->getData($payment->payer_user_id, array('username', 'email'));

                // $this->EmailQueue->store($payer->email, '['.SITE_NAME.'] Payment Approved', 'emails/cashier/payment_approved', compact('payer', 'payment'));
            }
            else {
                echo json_encode(array('error' => 'There is a problem here'));
                return;

//                $payee = $this->User->getData($payment->payee_user_id, array('username', 'email'));
//                $payer = $this->User->getData($payment->payer_user_id, array('username', 'email'));
//
//                $this->EmailQueue->store($payee->email, '['.SITE_NAME.'] Payment Rejected', 'emails/cashier/payment_rejected', compact('payer', 'payee', 'payment'));
            }

            // $this->Payment->update($id, $data);
        }
        echo json_encode(array('success' => TRUE));
    }

    public function testimonial_update($id, $approve) {

        $this->load->model('testimonial_model', 'Testimonial');
        $this->load->model('email_model', 'EmailQueue');

        $testimonial = $this->Testimonial->get($id);
        $user = $this->User->getData($testimonial->user_id, array('email', 'username'));

        $data['username'] = $user->username;

        if ($approve == '0') {

            $data['reason'] = $this->input->post('message');
            $this->EmailQueue->store($user->email, 'Testimonial Rejected', "emails/member/testimonial_rejected", $data);

            $this->Testimonial->update($id, array('status' => 'rejected'));

            @unlink(FCPATH.'uploads/'.$testimonial->screenshot);

        } else {

            $this->EmailQueue->store($user->email, 'Testimonial Approved', "emails/member/testimonial_approved", $data);

            $this->Testimonial->update($id, array('status' => 'approved'));
        }

        echo json_encode(array('success' => TRUE));
    }

    /*******************
     * Form Wrapper
     * @param $formName
     *
     */
    public function viewForm($formName, $id = '') {
        $vis = $this->User->getVisible($id);

//        if ($vis->visible == "0") {
//            echo "Suspect details Reported to Admin";
//            exit;
//        }

        $this->data->formURL = site_url("adminpanel/admin/form/$formName");
        $this->data->codeURL = site_url("admin/form/$formName/$id");
        if ($id != '') $this->data->formURL .= "/$id";
        if ($this->ajax) {
            $this->form($formName, $id);
        } else {

            $this->data->page_title = wordify($formName);
            $this->data->content    = $this->loadPartialView('partial/form');
            $this->layout           = 'layout/admin/shell';
            $this->loadView('layout/default', SITE_NAME.' Admin');
        }
    }

    /*****
     * Ajax Entry for forms.
     * @param $formName
     * @param string $id
     *
     */
    public function form($formName, $id = '') {

        $this->_path = 'admin/';
        if ($_POST) {
            if ($formName == 'expense') {
                $_POST['user_id'] = $this->userId;
            }
            if ($formName == 'user') { // preserve NULL value for lifetime members.
                
                $_POST['user_id_check'] = $id;
                unset($_POST['salt']);
                $this->load->model('user_model', 'User');
                if ($_POST['account_expires'] == '') {
                    unset($_POST['account_expires']);
                } else {
                    //  $_POST['account_expires'] = strtotime($_POST['account_expires']);
                }
                if ($_POST['banned'] == '1') {
                    $this->User->forceLogout($id);
                    $_POST['active']  = 0;
                }
            }
            if ($formName == 'mass_email') {
                $send_time = $this->input->post('send_date').' ';
                if ($_POST['send_meridian'] == '12' && intval($_POST['send_hour']) < 12) {
                    $send_time .= 12 + intval($_POST['send_hour']);
                } else {
                    $send_time .= ($_POST['send_hour'] == 12) ? '00' : $_POST['send_hour'];
                }

                $_POST['time_to_send'] = strtotime($send_time.':00:00');
            }
        }

        $result      = $this->doForm($formName, $id);

        if ($_POST) {
            if (!is_array($result)) {
                if ($formName == 'mass_email') {
                    $this->session->set_flashdata('success', 'Email queued to send at '.$send_time.':00');
                    $result = array(
                        'success'  => 'success',
                        'redirect' => array(
                            'url' => SITE_ADDRESS.'admin'
                        )
                    );
                } elseif ($formName == 'expense') {
                        if (($screenshot = $this->input->post('screenshot')) !== FALSE) {
                            $this->create_proofs(FCPATH."proofs/".$screenshot, 'expense/'.$result);
                        }
                    $result = array(
                        'success'  => 'Ok',
                        'redirect' => 'reload'
                    );
                } elseif ($formName == 'news' && $id == '') {
                    $this->load->model('news_model');
                    $this->news_model->slug($result);
                    $result = array(
                        'success'  => 'Ok',
                        'redirect' => array(
                            'url' => '/admin/viewList/news'
                        )
                    );
                } else {
                    $result = array(
                        'success'  => 'Ok',
                        'redirect' => 'reload'
                    );
                }
            }
            echo json_encode($result);
        }
    }

    public function upload_proof() {
        $config = array(
            'upload_path'   => FCPATH."proofs/",
            'allowed_types' => 'gif|jpg|png',
            'max_size'      => 1024*2,
            'encrypt_name'  => TRUE
        );

        $config['max_width']  = 1800;
        $config['max_height'] = 1200;

        $this->load->library('upload', $config);

        $data = NULL;
        if (!$this->upload->do_upload('screenshot')) {
            $data = array(
                'error' => $this->upload->display_errors('', '')
            );
            @unlink($_FILES['screenshot']['tmp_name']);
        } else {
            $image = $this->upload->data();

            $banner = base_url().'proofs/'.$image['file_name'];

            // Don't need to Make thumbnail - just css it.
            $thumb = ($image['image_width'] > 300);

            if (!$data) {
                $data = array(
                    'success' => 'success',
                    'file'    => $image['file_name'],
                    'screenshot'  => $banner,
                    'thumb'   => $thumb
                );

                $data = $data + $image;
            } else @unlink($image['full_path']);
        }

        echo json_encode($data);
    }

    function create_proofs($src_image_path, $filename) {

        if (empty($src_image_path)) return TRUE;

        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($src_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($src_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($src_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($src_image_path);
                break;
        }
        $source_aspect_ratio    = $source_image_width/$source_image_height;
        $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH/THUMBNAIL_IMAGE_MAX_HEIGHT;
        if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
            $thumbnail_image_width  = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width  = (int)(THUMBNAIL_IMAGE_MAX_HEIGHT*$source_aspect_ratio);
            $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
        } else {
            $thumbnail_image_width  = THUMBNAIL_IMAGE_MAX_WIDTH;
            $thumbnail_image_height = (int)(THUMBNAIL_IMAGE_MAX_WIDTH/$source_aspect_ratio);
        }
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);

        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
        imagejpeg($thumbnail_gd_image, FCPATH.'thumbnails/'.$filename.'.jpg', 90);
        imagejpeg($source_gd_image, FCPATH.'proofs/'.$filename.'.jpg', 90);
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        return TRUE;
    }

    /**
     * Callback functions used in config/form_validation for the update user bit :/
     * It checks username to be updated/registered in 'users' table
     *
     * @param string $param Username to be checked
     * @return boolean
     * @author Alex
     */
    public function user_check($param) {
        $userId = $_POST['user_id_check'];

        if ($this->User->check_username($userId, $param)) {
            $this->form_validation->set_message('user_check', '* Username already registered');
            return FALSE;
        } else if (!preg_match('/^[\w\-]+$/i', $param)) {
            $this->form_validation->set_message('user_check', 'Only alpha-numerical characters allowed');
            return FALSE;
        }

        return TRUE;
    }
    /**
     * Callback functions used in config/form_validation
     * It checks user's email to be updated/registered in 'users' table
     *
     * @param string $param Email to be checked
     * @return boolean
     * @author Alex
     */
    public function email_check($param) {
        $userId = $_POST['user_id_check'];

        if ($this->User->check_email($userId, $param)) {
            $this->form_validation->set_message('email_check', '* already in use');
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Callback functions used in config/form_validation for checking valid dates
     * It checks dates of birth for a user to be updated/registered
     * in 'users' table
     *
     * @param string $param Username to be checked
     * @return boolean
     * @author Alex
     */
    function valid_date() {
        if (!checkdate($this->input->post('month'), $this->input->post('day'), $this->input->post('year'))) {
            $this->form_validation->set_message('valid_date', '* invalid');
            return FALSE;
        }
        return TRUE;
    }
}