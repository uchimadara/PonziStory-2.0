<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define ("BATCH_SIZE", 68);

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!defined('CLI') && ENVIRONMENT == 'production') {
            show_404();
            exit(0);
        }
        // $this->load->library('email');

        $this->load->model('email_model', 'EmailQueue');
        $this->load->model('user_model',  'User');

        $this->load->driver('cache', array(
            'adapter' => CACHE_METHOD_PRIMARY,
            'backup'  => CACHE_METHOD_SECONDARY
        ));
        //$this->output->enable_profiler(PROFILER_SETTING);
    }

	public function emails()
	{
        $emails = $this->EmailQueue->subset(BATCH_SIZE);

        $fromName  = FROM_NAME;
        $fromEmail = FROM_EMAIL;

        foreach ($emails as $email) {
            if ($email->template) {
                $email_content = $this->load->view($email->template, json_decode($email->data), TRUE);
                $body          = $this->load->view("emails/basic_template", compact('email_content'), TRUE);
            } else {
                $body = $this->EmailQueue->skin($email->body);
            }

            $this->EmailQueue->send_smtp_email(
                $email->email,
                $fromName,
                $fromEmail,
                $email->subject,
                str_replace('&euro;', '€', $body)
            );

            $this->EmailQueue->delete($email->id);
        }

        $this->EmailQueue->updateCount(count($emails));
    }

    function blast() {

        $data = NULL;
        $userCount = $this->User->countMembers();
        $blast = $this->EmailQueue->getBlasterQueue($userCount);

        foreach($blast as $b) {

            $users = $this->User->getBatch($b->last_user_id, BATCH_SIZE);

            $countSent = 0;

            foreach ($users as $user) {
                if (TRUE) { // (intval($user->email_settings) & intval($b->setting)) {

//                    $find = array(
//                        '/\[USERNAME\]/',
//                        '/\[EMAIL\]/',
//                        '/\[REF_LINK\]/',
//                        '/\[BALANCE\]/',
//                        '/\[ID\]/',
//                        '/\[ACTIVATION_CODE\]/',
//
//                    );
//                    $replace = array(
//                        $user->username,
//                        $user->email,
//                        site_url('ref/'.$user->username),
//                        '\\$'.money($user->balance, ''),
//                        $user->id,
//                        $user->activation_code
//                    );

                    $template = (is_null($blast->template)) ? "emails/basic_template" : "emails/{$b->template}_template";
                    $subject = $b->subject; // preg_replace($find, $replace, $subject);

                    $email_content = html_entity_decode($b->body); // preg_replace($find, $replace, $b->body);
                    $body          = $this->load->view($template, compact('email_content'), TRUE);

                    $this->EmailQueue->send_smtp_email(
                        $user->email,
                        $b->from_name,
                        $b->from_email,
                        $subject,
                        $body
                    );

                    $countSent++;
                    $lastUserId = $user->id;
                }
            }
            $total = $b->sent + $countSent;
            $completed = ($total >= $userCount) ? now() : NULL;
            $this->EmailQueue->updateBlasterQueue($b->id, $total, $lastUserId, $completed);
        }
    }

    public function check_pending() {

        $this->load->model('payment_model', 'Payment');
        $this->load->model('referral_model', 'Referral');
        $this->load->model('my_account_model', 'Account');
       // $this->load->helper('bitcoin');

        $payments = $this->Payment->getPending();
        $memberships = $this->Account->getMemberships();

        foreach ($payments as $p) {
         //   $c = get_confirmations($p->transaction_id);
            if ($p->proof_img) {

                $data = array(
                    'confirmations' => 1,
                    'updated' => now(),
                    'update_user_id' => 1
                );

                if ($p->approved == 1) {

                    $p->approved = $data['approved'] = now();

                    $this->Referral->addPayment($p->payee_user_id, $p->payer_user_id, $p->amount);

                    $upgrade = $memberships[$p->code];

                    $userData = array(
                        'account_expires' => NULL,
                        'text_ad_credits' => $p->text_ad_credits + $upgrade->text_ad_credits
                    );

                    if ($p->account_level < $p->code) {
                        $userData['account_level'] = $p->code;
                    }
                    $this->User->update($p->payer_user_id, $userData);

                    $this->EmailQueue->store($p->payer_email, '['.SITE_NAME.'] Upgrade Approved', 'emails/cashier/payment_approved', $p);

                    // set expiration
                    $this->Payment->setExpiration($p->payer_user_id, $p->upgrade_id, SUBSCRIPTION_DURATION*CACHE_ONE_DAY);

                }

                $this->Payment->update($p->id, $data);
            }
        }
    }
    public function clean_user_sessions() {
        $this->User->cleanUserSessions();
    }

    public function reminders() {
        $this->load->model('payment_model', 'Payment');

        $now = now();
        $hour = $now - ($now % CACHE_ONE_HOUR) + CACHE_ONE_DAY;
        $expiring = $this->Payment->getExpiring($hour);

        foreach ($expiring as $user) {

            $this->EmailQueue->store($user->email, '['.SITE_NAME.'] Monthly Payment Due', 'emails/member/payment_due', compact('user'));
        }

        $hour = $now - ($now%CACHE_ONE_HOUR) - CACHE_ONE_HOUR;

        $expired = $this->Payment->getExpiring($hour);
        foreach ($expired as $user) {

            $this->EmailQueue->store($user->email, '['.SITE_NAME.'] Upgrade Expired', 'emails/member/upgrade_expired', compact('user'));
        }

    }

    public function cronjob_paymentconfirmationfordeposit()
    {
        $this->load->model('buyerandsellermodel','cronjob');

        $showalldata = $this->cronjob->selectingalldata();

        $SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

        $API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

        foreach ($showalldata as $alldata) {
                # code...
            $idfordeposit = $alldata->id;

            $useridfordeposit = $alldata->user_id;

            $creditpacksfordeposit = $alldata->credit_packs;

            $hashfordeposit = $alldata->hash;

        }

        try
        {
            $client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

            $transaction_information = $client->transaction($hashfordeposit);

            $confirmation = $transaction_information['confirmations'];

            if($confirmation > 0)
            {
                $statusupdate = array(
                        'status'=>'completed'
                    );

                $this->cronjob->updateSellerStatus($idfordeposit, $statusupdate);

                $checkallinfo = $this->cronjob->index($useridfordeposit);

                foreach ($checkallinfo as $callinfo) {
                    # code...
                    $currentadpackscredit = $callinfo->ad_pack_balance;

                }

                print_r($checkallinfo);

                $totaladcreditpackstoupdate = $currentadpackscredit + $creditpacksfordeposit;

                $balancetoupdate = array(
                        'ad_pack_balance'=>$totaladcreditpackstoupdate
                        );

                $this->cronjob->updateBuyerBalance($useridfordeposit, $balancetoupdate);



            }

        }
        catch(Exception $e)
        {
            $message = "Invalid Error";
        }

    }

    public function scrub() {
        $this->load->model('user_model', 'User');
        $this->load->model('email_model', 'EmailQueue');

        $users = $this->User->deleteFree();

        foreach ($users as $user) {
            $this->EmailQueue->store($user->email, '['.SITE_NAME.'] Account expired', 'emails/member/account_expired', compact('user'));
        }

        $users = $this->User->getExpiring();

        foreach ($users as $user) {
            $this->EmailQueue->store($user->email, '['.SITE_NAME.'] Free account expiring', 'emails/member/account_expiring', compact('user'));
        }

        $this->User->clearInvites();

        if (defined('LOCK_ACCOUNTS') && LOCK_ACCOUNTS != '0') { // lock_accounts = number of hours to allow member to approve/reject payments

            $users = $this->User->lockUsers(intval(LOCK_ACCOUNTS));

            foreach ($users as $id) {

                $user = $this->User->getData($id, array('username', 'email', 'account_level', 'account_expires'));
                $this->EmailQueue->store($user->email, '['.SITE_NAME.'] Account locked', 'emails/member/account_locked', compact('user'));
            }
        }
    }

    /****************
     * LOGGING METHODS
     *
     */
    function start_log($logFile = 'cron_log') {
        if (ENVIRONMENT == 'production') {
            return fopen(APPPATH."logs/".$logFile.'.log', "ab");
        }
        return 0;
    }

    function log($handle, $info) {
        if ($handle) {
            fwrite($handle, '['.date('Y-m-d H:i:s').'] '.$info.PHP_EOL);
        } else {
            echo $info.''.PHP_EOL;
        }
    }

    function close_log($handle) {
        if ($handle) fclose($handle);
    }
    
    public function inactive_users() {
        foreach($this->User->inactiveUsers() as $user){
            $this->EmailQueue->store($user->email, 'We miss you!', 'emails/auth/miss_you.php', compact('user'));
            $this->User->setReminder($user->id);
        }       
    }
}