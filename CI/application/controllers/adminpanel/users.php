<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('admin.php');
include(APPPATH.'libraries/supportTicket.php');

class Users extends Admin
{
    public function __construct()
    {
        parent::__construct(TRUE);

        $this->layoutData['title'] = 'Users';

        $this->load->model('user_model', 'User');
        $this->load->model('cashier_model', 'Cashier');
        $this->load->model('referral_model', 'Referral');
        $this->load->model('payment_method_model', 'PaymentMethod');
    }

    /**
     * When in Admin menu link 'User' is clicked, it load users table with the list
     * of users in the system
     *
     * @param string $filter String for looking for in user's email and name
     * @param type $page
     * @param type $perpage
     */
    public function index()
    {
        show_404();
    }

    public function setting($userId, $setting, $val) {

        $this->User->addSetting($userId, $setting, $val);
    }


    /**
     * When in Admin > Users click over a user link will triger this
     * view to show his profile and details, allowing to change user's data
     *
     * @param int $userId User id to show details
     * @return View View with edit zone of user
     * @author ?
     */
    public function detail($id, $tab = NULL) {
      

        $this->data->user = $this->User->getData($id);
        $vis = $this->User->getVisible($id);

        if ($this->ajax) {
            switch ($tab) {
                case 'payments': // balance

                    if ($vis->visible == "0") {
                        echo "Suspect details Reported to Admin";
                        exit;
                    }
                    $this->load->model('payment_model', 'Payment');
                    $this->data->payments = $this->Payment->userSummary($id);
                    break;
                case 'abuse': // ip abuse
                    if ($vis->visible == "0") {
                        echo "Suspect details Reported to Admin";
                        exit;
                    }
                    $this->data->foundLogIP = $this->User->find_same_login_ip_by_user($id);
                    $this->data->foundRegIP = $this->User->find_same_registration_ip_by_user($id);
                    break;
                case 'security': // security
                    if ($vis->visible == "0") {
                        echo "Suspect details Reported to Admin";
                        exit;
                    }
                    $this->data->currentUserSettings = $this->User->getSettings($id);
                    $this->data->logsIPs = $this->User->getLoginIPs($id);
                    break;
               case 'phgh': // ph and GH
                   if ($vis->visible == "0") {
                       echo "Suspect details Reported to Admin";
                       exit;
                   }
                        $this->load->model('ph_model', 'PH');
                        $this->load->model('gh_model', 'GH');
                        $this->data->userId = $this->userid;
                    $this->data->getAllPh = $this->PH->getAllPh($id);
                    $this->data->getAllGh = $this->GH->getAllGh($id);
                    break;
                case 'groups': // groups
                    if ($this->ion_auth->is_support()){
                        echo "You are not permitted to view this page";
                        exit;
                    }
                    foreach ($this->picklist->select_values('user_group_list') as $id => $group) {
                        $this->data->formGroups[] = site_url('admin/form/user_groups/' . $group);
                    }
                    break;
                default:
                    break;
            }
            echo $this->loadPartialView('admin/user/partial/detail_' . $tab);
        } else {
            $this->data->tabs = array(
                'details' => array(
                    'url'   => site_url('admin/form/user/'.$id),
                    'title' => 'Form'
                ),
//                'upgrade' => array(
//                    'url'   => site_url("adminpanel/users/upgrade/$id"),
//                    'title' => 'Manual Upgrade'
//                ),

                'wallet' => array(
                    'url'   => site_url("adminpanel/users/wallet/$id"),
                    'title' => 'Wallet'
                ),
                'payments'   => array(
                    'url' => site_url("adminpanel/users/detail/$id/payments"),
                    'title' => 'Payments'
                ),
                'phgh'   => array(
                    'url' => site_url("adminpanel/users/detail/$id/phgh"),
                    'title' => 'PH/GH'
                ),
                'abuse'     => array(
                    'url' => site_url("adminpanel/users/detail/$id/abuse"),
                    'title' => 'IP Abuse'
                ),
                'security' => array(
                    'url'   => site_url("adminpanel/users/detail/$id/security"),
                    'title' => 'Security'
                ),
                'groups' => array(
                    'url'   => site_url("adminpanel/users/detail/$id/groups"),
                    'title' => 'Groups'
                ),
                'referral' => array(
                    'url'   => site_url("ajax/referrals/get_list/$id/1"),
                    'title' => 'Referrals'
                ),
                'refSummary' => array(
                    'url'   => site_url("adminpanel/users/ref_summary/$id"),
                    'title' => 'Referral Summary'
                ),
                'upline' => array(
                    'url'   => site_url("adminpanel/users/upline/$id"),
                    'title' => 'Upline'
                ),
//                'textads' => array(
//                    'url'   => site_url("admin/getList/user_text_ads?user_id=$id"),
//                    'title' => 'Text Ads'
//                ),

            );
            $username = $this->session->userdata('username');
//            if ($vis->visible == "0") {
//                echo "Suspect details Reported to Admin";
//                exit;
//            }
            if ($this->ion_auth->is_support()){
                $this->data->heading = 'USER: ' . $this->data->user->username . ' [' . $id . '] ' ;
            }elseif ($this->ion_auth->is_support() && $vis->visible == "0" ){
                $this->data->heading = 'USER: ' . 'Suspect Reported to Admin' ;

            }
            else {
                $this->data->heading = 'USER: ' . $this->data->user->username . ' [' . $id . '] ' . anchor(SITE_ADDRESS . "adminpanel/users/delete/" . $id, 'Delete Account', 'class="btn btn-alt confirm"');
            }
            $this->data->page_title = 'User: ' . $this->data->user->username . ' (ID#' . $id . ')';

            //$this->layout = 'shell';
            $this->addJavascript(asset('scripts/listForm.js'));
            $this->addJavascript(asset('scripts/tabs.js'));
            $this->addJavascript(asset('scripts/replace.js'));
            $this->addStyleSheet('/layout/member/assets/css/referrals_b.css');
            $this->loadView('partial/tab_panel', 'Admin');
        }
    }

    public function wallet($id) {
        $vis = $this->User->getVisible($id);

        if ($vis->visible == "0") {
            echo "Suspect details Reported to Admin";
            exit;
        }

        $this->load->model('payment_model', 'Payment');
        $this->data->wallet = $this->Payment->getWallet($id);
        $this->data->changes = $this->Payment->getWalletChanges($id);

        echo $this->loadPartialView('admin/user/wallet');
    }

    public function upgrade($id) {

        if ($_POST) {

            $post = $this->input->post();

            $this->load->model('payment_model', 'Payment');

            if (empty($post['transaction_id'])) {
                echo json_encode(array(
                    'errorElements' => array('transaction_id' => '* required.')
                ));
                return;
            }
            if ($this->Payment->find_txid($post['transaction_id'])) {
                echo json_encode(array(
                    'errorElements' => array('transaction_id' => '* already in use.')
                ));
                return;
            }

            $upgrades = $this->Account->getMemberships();

            foreach ($upgrades as $code => $u) {
                if ($u->id == $post['upgrade_id']) {
                    $price = $u->price;
                    $level = $code;
                    break;
                }
            }
            $payeeWallet = $this->Payment->getWallet($post['payee_user_id']);

            if ($payeeWallet) {

                $payment['transaction_id']  = $post['transaction_id'];
                $payment['payer_user_id']   = $id;
                $payment['payee_user_id']   = $post['payee_user_id'];
                $payment['method_id']       = $payeeWallet->id;
                $payment['upgrade_id']      = $post['upgrade_id'];
                $payment['amount']          = $price;
                $payment['created']         = now();
                $payment['approved']        = 1;
                $payment['confirmations']   = 4;
                $payment['currency_amount'] = 0;
                $payment['update_user_id'] = $this->userId;

                $pId = $this->Payment->create($payment);
                $this->Payment->setExpiration($id, $post['upgrade_id'], SUBSCRIPTION_DURATION*CACHE_ONE_DAY);

                if ($level > $this->User->getData($id, array('account_level'))->account_level) {
                    $this->User->update($id, array('account_level' => $level+1));
                }

                echo json_encode(array(
                    'success'  => 'Success! Payment created [id='.$pId.']',
                    'again' => 'true'
                ));
            } else {
                echo json_encode(array('error' => 'Payee has no wallet.'));
            }


        } else {
            $this->data->userId = $id;
            $this->data->upline = array();
            $uplineId = $this->User->getData($id, array('referrer_id'))->referrer_id;

            if ($uplineId < DEFAULT_USER_ID) $uplineId = DEFAULT_USER_ID;

            for ($i = 1; $i <= CYCLER_DEPTH; $i++) {
                $this->data->upline[$i] = $this->User->getData($uplineId);
                if ($uplineId > DEFAULT_USER_ID) {
                    $uplineId = $this->data->upline[$i]->referrer_id;
                }
            }

            $this->data->upgrades = $this->Account->getMemberships();

            echo $this->loadPartialView('admin/user/upgrade');
        }
    }

    public function edit_payment($id) {
        $this->load->model('payment_model', 'Payment');
        $this->load->model('payment_method_model', 'PaymentMethods');

        $this->data->payment = $this->Payment->getFull($id);
        $this->data->upgrades = $this->Account->getMemberships();

        if ($_POST && $this->ajax) {

            if ($this->form_validation->run('payment_proof') === FALSE) {

                $result['errorElements'] = $this->form_validation->error_array();

            } else {

                $payment = $this->input->post();

                $payment['update_user_id']    = $this->userId;
                $payment['updated']       = now();

                $this->Payment->update($id, $payment);

                $this->load->model('referral_model', 'Referral');

                if ($this->data->payment->amount != $payment['amount']) {
                    // update referrals table with corrected earning
                    $this->Referral->addPayment($this->data->payment->payee_user_id, $this->data->payment->payer_user_id, floatval($payment['amount']) - $this->data->payment->amount);
                }
//                $payee = $this->User->getData($payment['payee_user_id'], array('username', 'email'));
//
//                $this->load->model('email_model', 'EmailQueue');
//                $this->EmailQueue->store($payee->email, '['.SITE_NAME.'] Payment submitted for your approval', 'emails/cashier/payment_submitted', compact('payee', 'payment'));

                $result = array(
                    'success'  => 'Ok',
                    'redirect' => 'reload'
                );
            }
            echo json_encode($result);

        } else {

            $this->data->paymentMethods = $this->PaymentMethod->getUserMethods($this->data->payment->payee_user_id);

            echo $this->loadPartialView('admin/user/payment_form');
        }
    }


    public function edit_phgh($id,$p) {
        $this->load->model('ph_model', 'PH');
        $this->load->model('gh_model', 'GH');

        $this->data->userId = $this->userId;
        $this->data->getAllPh = $this->PH->getAllPh2($id);
        $this->data->getAllGh = $this->GH->getAllGh2($id);
        if ($_POST && $this->ajax && $p == 1) {
            

                $ph = $this->input->post();

                $this->PH->update($id, $ph);

            
//                $payee = $this->User->getData($payment['payee_user_id'], array('username', 'email'));
//
//                $this->load->model('email_model', 'EmailQueue');
//                $this->EmailQueue->store($payee->email, '['.SITE_NAME.'] Payment submitted for your approval', 'emails/cashier/payment_submitted', compact('payee', 'payment'));

                $result = array(
                    'success'  => 'Ok',
                    'redirect' => 'reload'
                );

            echo json_encode($result);

        } else {

            $this->data->paymentMethods = $this->PaymentMethod->getUserMethods($this->data->payment->payee_user_id);

            echo $this->loadPartialView('admin/user/phgh_form');
        }
    }


    public function edit_phgh2($id,$p) {
        $this->load->model('ph_model', 'PH');
        $this->load->model('gh_model', 'GH');

        $this->data->userId = $this->userId;
        $this->data->getAllPh = $this->PH->getAllPh2($id);
        $this->data->getAllGh = $this->GH->getAllGh2($id);
        if ($_POST && $this->ajax && $p == 2 ) {


            $ph = $this->input->post();

            $this->GH->update($id, $ph);


//                $payee = $this->User->getData($payment['payee_user_id'], array('username', 'email'));
//
//                $this->load->model('email_model', 'EmailQueue');
//                $this->EmailQueue->store($payee->email, '['.SITE_NAME.'] Payment submitted for your approval', 'emails/cashier/payment_submitted', compact('payee', 'payment'));

            $result = array(
                'success'  => 'Ok',
                'redirect' => 'reload'
            );

            echo json_encode($result);

        } else {

            $this->data->paymentMethods = $this->PaymentMethod->getUserMethods($this->data->payment->payee_user_id);

            echo $this->loadPartialView('admin/user/phgh_form2');
        }
    }

    public function currency_check($param) {
        if ($this->input->post('currency') != 'USD') {
            if (empty($param)) {

                $this->form_validation->set_message('currency_check', '* required when currency is not USD');
                return FALSE;
            }
            if (!is_numeric($param)) {
                $this->form_validation->set_message('currency_check', '* invalid number');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function ref_summary($userId) {
        $vis = $this->User->getVisible($userId);

        if ($vis->visible == "0") {
            echo "Suspect details Reported to Admin";
            exit;
        }
        $summary = $this->Referral->summary($userId);
        echo $this->loadPartialView('member/referral_summary', compact('summary'));
    }


    public function upline($userId) {
        $vis = $this->User->getVisible($userId);

        if ($vis->visible == "0") {
            echo "Suspect details Reported to Admin";
            exit;
        }
        $upline = array();

        do {
            $user = $this->User->getData($userId);
            $upline[] = $user;
            $userId = $user->referrer_id;
        }
        while ($userId >= 1);

        $this->data->upline = array_reverse($upline);

        echo $this->loadPartialView('admin/user/partial/upline');
    }

    public function reassign_upline() {

        if ($_POST && $this->ajax) {
            $user = $this->User->getByUsername($this->input->post('upline'), array('id', 'referrer_id'));
            if ($user && $user->id > 0) {

                $this->load->model('referral_model', 'Referral');

                $refId = $this->input->post('user_id');

                // ensure the new referrer is not in the downline
                if ($this->check_downline($refId, $user->id)) {
                    echo json_encode(array(
                        'errorElements' => array(
                            'upline' => 'Circular reference error: new upline member exists in downline.'
                        )
                    ));
                    return;
                }
                $this->User->update($refId, array('referrer_id' => $user->id));

                $refs = $this->Referral->getPayments($refId); // previous upline

                // delete old upline where no earnings; keep ones with earning to maintain counts
                $i = 1;
                foreach ($refs as $u) {
                    if ($u->earning == 0) {
                        $this->Referral->deleteEntry($u->user_id, $u->referee_id, $u->level);
                    }
                    $oldUpline[$i++] = $u->user_id;
                }

                // get the new upline
                $upline = array();
                $i=1;
                $userId = $user->id;
                do {
                    $user     = $this->User->getData($userId, array('id', 'referrer_id'));
                    $upline[$i++] = $user->id;
                    $userId   = $user->referrer_id;

                } while ($userId >= 1 && $i <= MAX_REF_LEVELS);


                // add new referrals
                foreach($upline as $level => $userId) {

                    $this->Referral->storeNewReferral($refId, $userId, $level);
                }

                $upline = array();
                $userId = $refId;
                do {
                    $user     = $this->User->getData($userId);
                    $upline[] = $user;
                    $userId   = $user->referrer_id;
                } while ($userId >= 1);

                $this->data->upline = array_reverse($upline);

                echo json_encode(array(
                        'success' => TRUE,
                        'replace' => array(
                            'uplineDiv' => $this->loadPartialView('admin/user/partial/upline')
                        )
                    )
                );

            } else {
                echo json_encode(array(
                    'errorElements' => array(
                        'upline' => 'User not found'
                    )
                ));
            }
            return;
        } else {
            show_404();
        }
    }

    private function check_downline($userId, $newUplineId) {

        $result = FALSE;
        if ($userId == $newUplineId) return TRUE;
        $users = $this->User->getReferralIds($userId);
        foreach ($users as $u) {
            if ($result = $this->check_downline($u->id, $newUplineId)) break;
        }
        return $result;
    }

    public function referrals($userId)
    {
        $user = $this->ion_auth->user($userId)->row();
        if (!$user)
            show_404();

        $referrals = $this->Referral->getReferrals($userId);

        $this->layoutData['title'] = anchor('adminpanel/users', 'Users') . ' - ' . anchor('adminpanel/users/detail/' . $userId, $user->username) . ' - Referrals';
        $this->loadView('admin/user/referrals', NULL, compact('user', 'referrals'));
    }

    public function change_password($userId) {
        if ($this->ajax && $_POST) {
            $newPassword = $this->input->post('password');
            if (strlen($newPassword) >= 6) {
                if ($this->ion_auth->update($userId, array('password' => $newPassword)) === TRUE) {
                    $data = array(
                        'success' => 'User Information successfully updated'
                    );
                    if ($this->input->post('send_email')) {

                        $user      = $this->User->getData($userId);
                        $emailData = array(
                            'username' => $user->username,
                            'password' => $newPassword,
                        );

                        $this->EmailQueue->store($user->email, 'Your password has been reset', 'emails/auth/admin_new_password', $emailData, 6);
                    }
                } else {
                    $data = array(
                        'error' => "There was a problem updating the user's data"
                    );
                }
            } else {
                $data = array(
                    'error' => "Must be at least 6 characters."
                );
            }
        } else {
            show_error('invalid access');
            exit();
        }

        echo json_encode($data);
    }

    public function delete_invite($id) {
        $this->load->model('referral_model', 'Referral');
        $this->Referral->delete_row('invite', $id);

        echo json_encode(array('success' => 'success'));
    }

    public function delete($id) {
        $this->load->model('payment_model', 'Payments');
        if ($this->Payments->checkUser($id)) {
            $this->session->set_flashdata('error', 'Unable to delete account with payments.');
            redirect('admin/user/'.$id);
        } else {
            $this->User->purgeUsers($id);
            $this->session->set_flashdata('success', 'User account deleted.');
            redirect('admin/viewList/users');
        }
    }

    public function confirm_order($orderId) {
        $this->load->model('my_account_model', 'Account');
        $this->load->model('transaction_model', 'Transaction');

        $order = $this->Account->getOrder($orderId);
        $this->Account->completeOrders($order->user_id);

        $user     = $this->User->getData($order->user_id);
        $referral = $user->username;
        $userData = array();

        switch ($order->category) {
            case 'membership':
                $userData['ad_credits'] = $user->ad_credits + $order->ad_credits;
                $userData['te_credits'] = $user->te_credits + $order->te_credits;
                $expire                 = CACHE_ONE_DAY*$order->duration*$order->qty;

                if ($order->qty >= 12) $expire += CACHE_ONE_DAY*$order->duration;

                if ($expire == 0) {
                    $userData['account_expires'] = NULL;
                } elseif ($user->account_expires < now()) {
                    $userData['account_expires'] = now() + $expire;
                } else {
                    $userData['account_expires'] = $user->account_expires + $expire;
                }
                $parts                     = explode(' ', $order->title);
                $userData['account_level'] = ($parts[0] == 'Lifetime') ? 'Expert' : $parts[0];
                $this->User->update($user->id, $userData);

                $data = array(
                    'created'        => now(),
                    'username'       => $user->username,
                    'transaction_id' => '#'.str_pad($order->transaction_id, 4, '0', STR_PAD_LEFT),
                    'order'          => $order,
                    'user'           => &$userData
                );

                $this->EmailQueue->store($user->email, 'Upgrade Completed', 'emails/cashier/upgrade_complete', $data, 6);

                //****
                // PAY COMMISSION
                //****
                $this->load->model('referral_model', 'Referral');

                $comm    = $this->Referral->getCommissionTable();
                $refComm = array();
                foreach ($comm as $rc) $refComm[$rc->name] = $rc->levels;

                for ($i = 1; $i <= 5; $i++) { // Pay up 5 levels

                    if ($user->referrer_id == DEFAULT_USER_ID) break;

                    $l1 = $this->User->getData($user->referrer_id);
                    if ($l1) {
                        $amount = roundDown((($order->amount*$order->qty) - $order->discount)*($refComm[$l1->account_level][$i]/100), 2);

                        $this->Cashier->increaseUserBalance($l1->id, 'eb', $amount);

                        $orderData = array(
                            'user_id'      => $l1->id,
                            'item_code'    => 'level '.$i,
                            'method'       => 'eb',
                            'reference_id' => $order->id,
                            'gross_amount' => $amount,
                            'type'         => 'ref_comm',
                            'status'       => 'ok',
                            'identifier'   => $this->Transaction->identifier()
                        );

                        $transactionId = $this->Transaction->add($orderData);
                        $this->Referral->addEarnings($l1->id, $order->user_id, $i, $amount);

                        if (intval($l1->email_settings) & intval(EMAIL_REFCOMM)) {
                            $data = array(
                                'username'       => $l1->username,
                                'referral'       => $referral,
                                'level'          => $i,
                                'amount'         => $amount,
                                'transaction_id' => $transactionId,
                                'description'    => $order->description,
                            );
                            $this->EmailQueue->store($l1->email, 'Referral commission paid', 'emails/referral/rc_paid', $data, 3);
                        }
                        $user = $l1;
                    } else {
                        break; // this would indicate an error in the hierarchy.
                    }
                }
                break;

            case 'shares':
                // add shares to account
                $this->load->model('shares_model', 'Shares');
                $parts = explode('_', $order->code);

                $shares = intval($parts[0])*$order->qty;

                $this->Shares->decreaseUserShares(1, 'eb', $shares);
                $this->Shares->increaseUserShares($order->user_id, $order->method, $shares);

                $this->Transaction->update($order->transaction_id, 'ok', array('type' => 'share_purchase'));

                $data = array(
                    'created'        => now(),
                    'username'       => $user->username,
                    'transaction_id' => '#'.str_pad($order->transaction_id, 4, '0', STR_PAD_LEFT),
                    'order'          => $order,
                    'user'           => &$this->profile,
                    'shares'         => $shares
                );
                $this->EmailQueue->store($user->email, 'Share Purchase Completed', 'emails/cashier/share_purchase_complete', $data, 6);
                break;

            case 'advertising':
                $userData['ad_credits']     = $user->ad_credits + ($order->ad_credits*$order->qty);
                $userData['te_credits']     = $user->te_credits + ($order->te_credits*$order->qty);
                $userData['banner_credits'] = $user->banner_credits + ($order->banner_credits*$order->qty);

                $this->User->update($user->id, $userData);
                $this->User->addTokens($user->id, $order->qty);

                $data = array(
                    'created'        => now(),
                    'username'       => $user->username,
                    'transaction_id' => '#'.str_pad($order->transaction_id, 4, '0', STR_PAD_LEFT),
                    'order'          => $order,
                    'user'           => &$userData,
                    'tokens'         => $order->qty
                );

                $this->EmailQueue->store($user->email, 'Order Completed', 'emails/cashier/order_complete', $data, 6);

                //****
                // PAY COMMISSION
                //****
                $this->load->model('referral_model', 'Referral');

                if ($user->referrer_id != DEFAULT_USER_ID) {

                    $l1 = $this->User->getData($user->referrer_id);
                    if ($l1) {
                        $amount = roundDown(($order->amount*$order->qty)*.10, 2);

                        $this->Cashier->increaseUserBalance($l1->id, 'eb', $amount);

                        $orderData = array(
                            'user_id'      => $l1->id,
                            'item_code'    => 'level 1 ad purchase',
                            'method'       => 'eb',
                            'reference_id' => $order->id,
                            'gross_amount' => $amount,
                            'type'         => 'ref_comm',
                            'status'       => 'ok',
                            'identifier'   => $this->Transaction->identifier()
                        );

                        $transactionId = $this->Transaction->add($orderData);
                        $this->Referral->addEarnings($l1->id, $order->user_id, 1, $amount);

                        if (intval($l1->email_settings) & intval(EMAIL_REFCOMM)) {
                            $data = array(
                                'username'       => $l1->username,
                                'referral'       => $referral,
                                'level'          => 1,
                                'amount'         => $amount,
                                'transaction_id' => $transactionId,
                                'description'    => $order->description,
                            );
                            $this->EmailQueue->store($l1->email, 'Referral commission paid', 'emails/referral/rc_paid', $data, 3);
                        }
                    }
                }
                break;
        }
        echo json_encode(array('success' => 'success'));
    }

    /**
     * We access here through Admin > Users > User, page for editing user preferences.
     * When click in Payment Mehtod -> edit link, it triggers this function, showing
     * a page where edit details of accounts of the selected user.
     *
     * @param int $userId
     * @param string $code
     * @return View View for editing user's account details
     * @author ?
     */
    public function payment($userId, $code = NULL)
    {
        $user = $this->ion_auth->user($userId)->row();
        if (!$user)
            show_404();

        if ($this->ajax)
        {
            $post = $this->input->post();

            if ($this->form_validation->run('admin_' . $code . '_account') === TRUE)
            {
                switch ($code)
                {
                    case 'bw':
                        unset($post['user_id']);

                        $data = new BankWire($post);
                        $account = $data->__toString();
                        break;

                    case 'wu':
                        unset($post['user_id']);

                        $data = new WesternUnion($post);
                        $account = $data->__toString();
                        break;

                    default:
                        $account = $post['account'];
                }

                if ($this->PaymentMethod->set($userId, $code, $account))
                {
                    $data = array(
                        'success' => 'hurray!'
                    );
                }
                else
                {
                    $data = array(
                        'error' => 'crying face :('
                    );
                }
            }
            else
            {
                $data = array(
                    'error' => renderErrors($this->form_validation->error_array())
                );
            }

            echo json_encode($data);
            return;
        }

        $options = $this->PaymentMethod->getAll();
        foreach ($options as $option)
        {
            $data = $this->PaymentMethod->getByUserId($userId, $option->code);
            if ($data)
            {
                switch ($option->code)
                {
                    case 'bw':
                        $account = new BankWire($data->account);
                        break;

                    case 'wu':
                        $account = new WesternUnion($data->account);
                        break;

                    default:
                        $account = $data->account;
                }

                $userPayment[$option->code] = $account;
            }
        }

        $listCountries = $this->User->getCountries();
        $countries     = dropdown($listCountries, 'name');
        // Adding the -- option when user have not set their country
        $countries = array_merge(array('unknown' => '--'), $countries);

        $this->layoutData['title'] = '<a href="' . site_url('adminpanel/users') . '">Users</a> - Edit Payment Methods for <a href="' . site_url('adminpanel/users/detail/' . $userId) . '">' . $user->username . '</a>';
        $this->loadView('admin/user/payment_options', NULL, compact('user', 'options', 'userPayment', 'countries'));
    }

    public function history($userId, $method, $page = 1, $perPage = 100)
    {
        $user = $this->ion_auth->user($userId)->row();
        if (!$user)
            show_404();

        $count    = $this->History->getCount($userId, $method);
        $data     = $this->History->getSubset($userId, $method, NULL, $page, $perPage);
        $paging   = generatePagination(site_url('adminpanel/users/history/' . $userId . '/' . $method), $count, $page, $perPage, TRUE);
        $hasPages = $count > $perPage;

        $history  = $this->loadPartialView('admin/user/partial/history', compact('data', 'paging', 'hasPages', 'method'));

        if ($this->ajax)
            echo $history;
        else
        {
            $this->layoutData['title'] = 'User History';
            $this->loadView('admin/user/history', NULL, compact('user', 'history'));
        }
    }

    public function summary($userId, $page = 1, $perpage = 30) {
        $user = $this->ion_auth->user($userId)->row();
        if (!$user)
            show_404();


        $data['totalCashouts'] = $this->Transaction->getTotalUserCashouts($userId);
        $data['totalDeposits'] = $this->Transaction->getTotalUserDeposits($userId);

        $L1 = $this->Referral->getCount($userId, 1);
        $L2 = $this->Referral->getCount($userId, 2);

        $data['refComm'] = $L1->earnings + $L2->earnings;

        $data['deposits'] = $this->page_summary($userId, 'deposit', $page, $perpage, TRUE);
        $data['cashouts']   = $this->page_summary($userId, 'cashout', $page, $perpage, TRUE);
        $data['transfers'] = $this->page_summary($userId, 'transfer', $page, $perpage, TRUE);
        $data['lr_transfers'] = $this->page_summary($userId, 'lr_transfer', $page, $perpage, TRUE);

        $this->layoutData['title'] = 'User Summary';
        $this->loadView('admin/user/summary', NULL, compact('user', 'data'));
    }

    public function page_summary($userId, $type, $page, $perpage, $ret = FALSE) { // this one is for paging interface

        $this->load->model('earning_transfer_model', 'EarningTransfer');

        $view = 'default';
        switch ($type) {
            case 'transfer':
                $count = $this->EarningTransfer->countUserTransfers($userId, 'any', 'eb');
                $data =$this->EarningTransfer->getUserTransfersSubset($userId, 'any', 'eb', $page, $perpage);
                $view = 'transfer';
                break;
            case 'lr_transfer':
                $count = $this->EarningTransfer->countUserTransfers($userId, 'any', 'lr');
                $data =$this->EarningTransfer->getUserTransfersSubset($userId, 'any', 'lr', $page, $perpage);
                $view = 'transfer';
                break;
            default:
                $count = $this->Transaction->getTransactionCountByUserId($userId, $type);
                $data = $this->Transaction->getTransactionSubsetByUserId($userId, $type, $page, $perpage);
        }

        $paging   = generatePagination(site_url('adminpanel/users/page_summary/'.$userId.'/'.$type), $count, $page, $perpage, TRUE);
        $hasPages = $count > $perpage;
        $out      = $this->loadPartialView("admin/user/partial/summary_".$view, compact('paging', 'hasPages', 'count', 'data'));

        if ($ret) {
            return $out;
        }
        echo $out;
        return TRUE;
    }


    public function campaigns($userId, $page = 1, $perPage = 20)
    {
        $user = $this->ion_auth->user($userId)->row();
        if (!$user)
            show_404();

        $banner_count    = $this->Campaign->getCount($userId, 'auction');
        $topbanner_count = $this->Campaign->getCount($userId, 'fixed');

        $count = $banner_count + $topbanner_count;

        $data     = $this->Campaign->getSubset($userId, '', $page, $perPage);

        $paging   = generatePagination(site_url('adminpanel/users/campaigns/' . $userId), $count, $page, $perPage, TRUE);
        $hasPages = $count > $perPage;

        $campaigns  = $this->loadPartialView('admin/user/partial/campaigns', compact('data', 'paging', 'hasPages', 'topbanner_count', 'banner_count'));

        if ($this->ajax)
            echo $campaigns;
        else
        {
            $this->layoutData['title'] = 'User Campaigns';
            $this->loadView('admin/user/campaigns', NULL, compact('user', 'campaigns'));
        }
    }

    public function viewList($listName, $userId, $sortCol=NULL, $sortDir='asc', $page=1, $perPage=NULL) {
        if (is_null($perPage)) {
            if (($perPage = $this->session->userdata('perPage')) === FALSE) $perPage = DEFAULT_ITEMS_PER_PAGE;
        }

        $this->session->set_userdata('perPage', $perPage);

        $report = new SupportTicket('list_user_tickets', "adminpanel/users/viewList/$listName/$userId/", $sortDir, $sortCol);
        //$this->data->list->set_where(array('user_id', $userId));
        $report->getPartial(1, $perPage, $userId);

        echo $report->render();
    }

    public function adjust($userId, $code)
    {
        $user = $this->ion_auth->user($userId)->row();
        if (!$user)
            show_404();

        $method = $this->PaymentMethod->getByUserId($userId, $code);

        $error = NULL;

        if ($this->input->post())
        {
            $this->form_validation->set_rules('amount',  'Amount',  'required|numeric');
            $this->form_validation->set_rules('message', 'Message', 'max_length[100]');

            if ($this->form_validation->run() === TRUE)
            {
                $amount  = $this->input->post('amount');
                $message = $this->input->post('message');

                if ($amount < 0)
                    $amount = min (abs($amount), $method->balance) * -1;

                $this->db->trans_begin();

                if ($this->Cashier->adjustBalance($userId, $code, $amount, $message))
                {
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success', 'Balance adjusted');
                    redirect('/adminpanel/users/detail/' . $userId);
                }
                else
                {
                    $this->db->trans_rollback();

                    $error = 'Cannot update the balance';
                }
            }
            else $error = $this->form_validation->error_string();
        }

        $this->layoutData['title'] = 'User Balance Adjust';
        $this->loadView('admin/user/adjust', NULL, compact('user', 'method', 'error'));
    }

    public function ap_account_check($param)
    {
        $userId = $this->input->post('user_id');
        if ($this->PaymentMethod->checkAccountExists('ap', $userId, $param))
        {
            $this->form_validation->set_message('ap_account_check', '* incorrect - already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function st_account_check($param)
    {
        $userId = $this->input->post('user_id');
        if ($this->PaymentMethod->checkAccountExists('st', $userId, $param))
        {
            $this->form_validation->set_message('st_account_check', '* incorrect - already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function pm_account_check($param)
    {
        if (!preg_match('/^u\d{2,8}$/i', $param))
        {
            $this->form_validation->set_message('pm_account_check', '* incorrect - use Uxxxxxx');
            return FALSE;
        }

        $userId = $this->input->post('user_id');
        if ($this->PaymentMethod->checkAccountExists('pm', $userId, $param))
        {
            $this->form_validation->set_message('pm_account_check', '* incorrect - already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function change_group($userId) {
        if ($this->ajax && $this->isAdmin) {
            if ($this->input->post('status') == 1) {
                $this->User->addToGroup($userId, $this->input->post('group_id'));
            } else {
                $this->User->removeFromGroup($userId, $this->input->post('group_id'));
            }
        } else {
            show_404();
        }
    }
}