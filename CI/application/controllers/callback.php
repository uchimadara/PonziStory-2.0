<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Callback extends CI_Controller
{
    private $postData = NULL;

    public function __construct()
    {
        parent::__construct();

        // This is the main callback controller for the processing of the payment
        $this->load->database();
        $this->load->model('transaction_model',    'Transaction');
        $this->load->model('payment_method_model', 'PaymentMethod');
        $this->load->model('email_model', 'EmailQueue');
        $this->load->model('my_account_model', 'Account');
        $this->load->model('user_model', 'User');

        $this->load->helper('url');
        $this->load->helper('file');

        if ($post = $this->input->post())
        {
            $this->postData = json_encode($post);
        }

        $this->output->enable_profiler(FALSE);

        log_message('debug', '<<bjb>> CALLBACK CONTROLLER uri='.$this->uri->uri_string());
        log_message('debug', '<<bjb>> CALLBACK CONTROLLER host='.$_SERVER['HTTP_HOST']);
    }

    /******************
     * PAYZA
     */
    public function process_pz()
    {
        if (!$this->input->post() || $this->input->get())
            show_404();

        $code  = 'pz';
        $token = urlencode($this->input->post('token'));

        if (!$token)
            return $this->Transaction->failDeposit($code, 'No token specified', $this->postData);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://secure.payza.com/ipn2.ashx');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        curl_close($ch);

        if (strlen($response) == 0)
            return $this->Transaction->failDeposit($code, 'Empty response', $this->postData);

        if (urldecode($response) == "INVALID TOKEN")
            return $this->Transaction->failDeposit($code, 'Invalid token', $this->postData);

        //urldecode the received response from Payza's IPN V2
        $response = urldecode($response);
        $this->postData .= "\n" . $response;

        //split the response string by the delimeter "&"
        $aps = explode("&", $response);

        //define an array to put the IPN information
        $info = array();

        foreach ($aps as $ap)
        {
            //put the IPN information into an associative array $info
            $ele = explode("=", $ap);
            $info[$ele[0]] = $ele[1];
        }

        // Test Mode??
        if (1 == $info['ap_test'])
            return $this->Transaction->failDeposit($code, 'Test mode', $this->postData);

        // Did the transaction succeed
        if ('Success' != $info['ap_status'])
            return $this->Transaction->failDeposit($code, 'Not Success status', $this->postData);

        // Let's get the deposit by using the identifier
        $deposit = $this->Transaction->getByIdentifier($info['ap_itemcode']);

        if (!$deposit)
            return $this->Transaction->failDeposit($code, 'Unknown identifier', $this->postData);

        $accountDetails = $this->PaymentMethod->getAccountDetailsById($deposit->account_id);
        if (!$accountDetails)
            return $this->Transaction->failDeposit($code, 'Cannot find details for account #' . $deposit->account_id, $this->postData);

        // Load the user's account details
//        $userDetails = $this->PaymentMethod->getByUserId($deposit->user_id, $code);
//        if (!$userDetails)
//            return $this->Transaction->failDeposit($code, 'Cannot find AP details for user #' . $deposit->user_id, $this->postData);

        // Check that the batch number does not already exists in the database
        if ($this->Transaction->referenceExists($code, $info['ap_referencenumber']))
            return $this->Transaction->failDeposit($code, 'Reference number already processed', $this->postData);

        // Is the account being paid into the correct one?
        if ($accountDetails->details != $info['ap_merchant'])
            return $this->Transaction->failDeposit($code, 'Invalid recipient account', $this->postData);

        // Are we being paid in good ol' United States Dollar$?
        if ('USD' != $info['ap_currency'])
            return $this->Transaction->failDeposit($code, 'Invalid currency', $this->postData);

        // The sender account is not the one we have in the database for that user
//        if ($userDetails->account != $info['ap_custemailaddress'])
//            return $this->Transaction->failDeposit($code, 'Invalid sender account', $this->postData);

        if ($deposit->gross_amount != $info['ap_totalamount'])
            return $this->Transaction->failDeposit($code, 'Invalid amount', $this->postData);

        // Is it the correct amount
        if (1 != $info['ap_quantity'])
            return $this->Transaction->failDeposit($code, 'Invalid quantity', $this->postData);

        // If we are here, it means the transaction is legit and can be added to the user's profile
        $this->db->trans_begin();

        if ($this->Transaction->update($deposit->id, 'ok', array('reference' => $info['ap_referencenumber'],
                                                                 'system_account' => $info['ap_merchant'],
                                                                 'user_account' => $info['ap_custemailaddress'],
                                                                 'details'      => json_encode($info)))) {
            $this->db->trans_commit();
            $this->notify($deposit->id);
        } else {
            $this->db->trans_rollback();
        }
    }

    /******************
     * EgoPay
     */
    public function process_ep()
    {
        if (!$this->input->post() || $this->input->get())
            show_404();

        $code = 'ap';

        $acct   = $this->PaymentMethod->getAccountDetails('ap');
        $params = array(
            'store_id'       => $acct[0]->extra_field_1,
            'store_password' => $acct[0]->extra_field_2
        );

        $this->load->library('EgoPaySci', $params);

        $EgoPay = new EgoPaySciCallback($params);
        $info = $EgoPay->getResponse($_POST);

        $this->postData .= "\n" . var_export($info, TRUE);

        // Did the transaction succeed
        if ('Completed' != $info['sStatus'])
            return $this->Transaction->failDeposit($code, 'Not Completed status', $this->postData);

        // Let's get the deposit by using the identifier
        $deposit = $this->Transaction->getByIdentifier($info['cf_1']);

        if (!$deposit)
            return $this->Transaction->failDeposit($code, 'Unknown identifier', $this->postData);

        $accountDetails = $this->PaymentMethod->getAccountDetailsById($deposit->account_id);
        if (!$accountDetails)
            return $this->Transaction->failDeposit($code, 'Cannot find details for account #' . $deposit->account_id, $this->postData);

        // Load the user's account details
//        $userDetails = $this->PaymentMethod->getByUserId($deposit->user_id, $code);
//        if (!$userDetails)
//            return $this->Transaction->failDeposit($code, 'Cannot find EP details for user #' . $deposit->user_id, $this->postData);

        // Check that the batch number does not already exists in the database
        if ($this->Transaction->referenceExists($code, $info['sId']))
            return $this->Transaction->failDeposit($code, 'Reference number already processed', $this->postData);

        // Are we being paid in good ol' United States Dollar$?
        if ('USD' != $info['sCurrency'])
            return $this->Transaction->failDeposit($code, 'Invalid currency', $this->postData);

        // The sender account is not the one we have in the database for that user
//        if ($userDetails->account != $info['sEmail'])
//            return $this->Transaction->failDeposit($code, 'Invalid sender account', $this->postData);

        if ($deposit->gross_amount != $info['fAmount'])
            return $this->Transaction->failDeposit($code, 'Invalid amount', $this->postData);

        // If we are here, it means the transaction is legit and can be added to the user's profile
        $this->db->trans_begin();

        if ($this->Transaction->update($deposit->id, 'ok', array('reference' => $info['sId'],
                                                                 'system_account' => $accountDetails->details,
                                                                 'user_account' => $info['sEmail'],
                                                                 'details'      => json_encode($info)))){
            $this->db->trans_commit();
            $this->notify($deposit->id);
        } else {
            $this->db->trans_rollback();
        }
    }

    /******************
     * Solid Trust Pay
     */
    public function process_st()
    {
        log_message('debug', '<<bjb>> CALLBACK CONTROLLER :: process_st');

        if (!$this->input->post() || $this->input->get()) {
            log_message('debug', '<<bjb>> CALLBACK CONTROLLER :: process_st - 404');
            show_404();
        }

        $code  = 'st';
        $post  = $this->input->post();

        $order = isset($post['item_id']) ? trim($post['item_id']) : NULL;

        // Test Mode??
        if (strtolower($post['testmode']) == 'on')
            return $this->Transaction->failDeposit($code, 'Test mode', $this->postData);

        if (strtolower($post['status']) != 'complete')
            return $this->Transaction->failDeposit($code, 'Status is not complete', $this->postData);

        // Let's get the deposit by using the identifier
        $deposit = $this->Transaction->getByIdentifier($order);

        if (!$deposit)
            return $this->Transaction->failDeposit($code, 'Unknown identifier', $this->postData);

        // Now we have found the deposit exists in the table, we need to check a few more things
        $accountDetails = $this->PaymentMethod->getAccountDetailsById($deposit->account_id);
        if (!$accountDetails)
            return $this->Transaction->failDeposit($code, 'Cannot find details for account #' . $deposit->account_id, $this->postData);

        // Load the user's account details
//        $userDetails = $this->PaymentMethod->getByUserId($deposit->user_id, $code);
//        if (!$userDetails)
//            return $this->Transaction->failDeposit($code, 'Cannot find LR details for user #' . $deposit->user_id, $this->postData);

        // Check that the batch number does not already exists in the database
        if ($this->Transaction->referenceExists($code, $post['tr_id']))
            return $this->Transaction->failDeposit($code, 'Transaction ID already processed', $this->postData);

        // Is the account being paid into the correct one?
//        if ($userDetails->account != $post['payerAccount'])
//            return $this->Transaction->failDeposit($code, 'Invalid recipient account', $this->postData);

        // The sender account is not the one we have in the database for that user
        if ($accountDetails->details != $post['merchantAccount'])
            return $this->Transaction->failDeposit($code, 'Invalid sender account', $this->postData);

        // Is it the correct amount
        if ($deposit->gross_amount != $post['amount'])
            return $this->Transaction->failDeposit($code, 'Invalid amount', $this->postData);

        // Ok so far everything is good, but does that transaction really exists on the STP server
        $secondary_password = $accountDetails->extra_field_2;
        $secondary_password = md5($secondary_password . 's+E_a*');  //encryption for db
        $hash = md5($post['tr_id'] . ":" . md5($secondary_password) . ":" . $post['amount'] . ":" . $post['merchantAccount'] . ":" . $post['payerAccount']);

        if ($hash != $post['hash'])
            return $this->Transaction->failDeposit($code, 'Invalid hash [' . $hash . ']', $this->postData);

        // If we are here, it means the transaction is legit and can be added to the user's profile

        log_message('debug', '<<bjb>> CALLBACK CONTROLLER :: process_st - updating transaction');

        $this->db->trans_begin();

        if ($this->Transaction->update($deposit->id, 'ok', array(
            'reference' => $post['tr_id'],
            'system_account' => $post['merchantAccount'],
            'user_account' => $post['payerAccount'],
            'details'      => json_encode($post)))) {

            $this->db->trans_commit();
            log_message('debug', '<<bjb>> CALLBACK CONTROLLER :: process_st - transaction complete');
            $this->notify($deposit->id);
        }else {
            log_message('debug', '<<bjb>> CALLBACK CONTROLLER :: process_st - transaction failed');
            $this->db->trans_rollback();
        }
    }

    /******************
     * Perfect Money
     */
    public function process_pm()
    {
        if (!$this->input->post() || $this->input->get())
            show_404();

        $code  = 'pm';
        $post  = $this->input->post();

        $order = isset($post['PAYMENT_ID']) ? trim($post['PAYMENT_ID']) : NULL;

        // Let's get the deposit by using the identifier
        $deposit = $this->Transaction->getByIdentifier($order);

        if (!$deposit)
            return $this->Transaction->failDeposit($code, 'Unknown identifier', $this->postData);

        // Now we have found the deposit exists in the table, we need to check a few more things
        $accountDetails = $this->PaymentMethod->getAccountDetailsById($deposit->account_id);
        if (!$accountDetails)
            return $this->Transaction->failDeposit($code, 'Cannot find details for account #' . $deposit->account_id, $this->postData);

        // Load the user's account details
//        $userDetails = $this->PaymentMethod->getByUserId($deposit->user_id, $code);
//        if (!$userDetails)
//            return $this->Transaction->failDeposit($code, 'Cannot find PM details for user #' . $deposit->user_id, $this->postData);

        // Check that the batch number does not already exists in the database
        if ($this->Transaction->referenceExists($code, $post['PAYMENT_BATCH_NUM']))
            return $this->Transaction->failDeposit($code, 'Batch number already processed', $this->postData);

        // Is the account being paid into the correct one?
        if ($accountDetails->details != $post['PAYEE_ACCOUNT'])
            return $this->Transaction->failDeposit($code, 'Invalid recipient account', $this->postData);

        // Are we being paid in good ol' United States Dollar$?
        if ('USD' != $post['PAYMENT_UNITS'])
            return $this->Transaction->failDeposit($code, 'Invalid currency', $this->postData);

        // The sender account is not the one we have in the database for that user
       // if ($userDetails->account != $post['PAYER_ACCOUNT'])
       //     return $this->Transaction->failDeposit($code, 'Invalid sender account', $this->postData);

        // Is it the correct amount
        if ($deposit->gross_amount != $post['PAYMENT_AMOUNT'])
            return $this->Transaction->failDeposit($code, 'Invalid amount', $this->postData);

        // Let's calculate the hash and compare to the data received
        $pm_sci_secret = $accountDetails->extra_field_1;
        $str = $post['PAYMENT_ID'] . ':' . $post['PAYEE_ACCOUNT'] . ':' . $post['PAYMENT_AMOUNT'] . ':' . $post['PAYMENT_UNITS'] . ':' . $post['PAYMENT_BATCH_NUM'] . ':' . $post['PAYER_ACCOUNT'] . ':' . strtoupper(md5($pm_sci_secret)) . ':' . $post['TIMESTAMPGMT'];
        $hash = strtoupper (md5 ($str));

        if ($hash != $post['V2_HASH'])
            return $this->Transaction->failDeposit($code, 'Invalid hash [' . $hash . ']', $this->postData);

        // If we are here, it means the transaction is legit and can be added to the user's profile
        $this->db->trans_begin();

        if ($this->Transaction->update($deposit->id, 'ok', array(
            'reference' => $post['PAYMENT_BATCH_NUM'],
            'system_account' => $post['PAYEE_ACCOUNT'],
            'user_account' => $post['PAYER_ACCOUNT'],
            'details'      => json_encode($post)))) {

            $this->db->trans_commit();
            $this->notify($deposit->id);
        } else {
            $this->db->trans_rollback();
        }
    }

    /******************
     * PayPal
     */
    public function process_pp() {
        if (!$this->input->post() || $this->input->get())
            show_404();

        $code = 'pp';
        $post = $this->input->post();

        $production_url = 'https://www.paypal.com/cgi-bin/webscr?';
        //$sandbox_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';

        $verify_url = $production_url.'cmd=_notify-validate&'.http_build_query($_POST);

        $opts = array('http' => array('header' => 'Accept-Charset: UTF-8,*'));
        $context = stream_context_create($opts);

        $post['verified'] = file_get_contents($verify_url, FALSE, $context);

        $order = isset($post['item_number1']) ? trim($post['item_number1']) : FALSE;
        $deposit = ($order) ? $this->Transaction->getByIdentifier($order) : FALSE;
        $receiverId = isset($post['receiver_id']) ? trim($post['receiver_id']) : '';

        if (!$deposit) {
            return $this->Transaction->failDeposit($code, 'Order not found', $this->postData);
        }

        if ($receiverId != 'FT284D26VXHKW') {
            return $this->Transaction->failDeposit($code, 'Incorrect receiver id', $this->postData);
        }

        if (strstr($post['verified'], 'VERIFIED') === FALSE) { // Trouble with verification. If we get a valid order number, then proceed regardless.
            $this->Transaction->failDeposit($code, 'Paypal order verification failed', json_encode($post));
        }

        // Now we have found the deposit exists in the table, we need to check a few more things
        $accountDetails = $this->PaymentMethod->getAccountDetailsById($deposit->account_id);
        if (!$accountDetails)
            return $this->Transaction->failDeposit($code, 'Cannot find details for account #'.$deposit->account_id, $this->postData);

        // Load the user's account details
//        $userDetails = $this->PaymentMethod->getByUserId($deposit->user_id, $code);
//        if (!$userDetails)
//            return $this->Transaction->failDeposit($code, 'Cannot find PM details for user #' . $deposit->user_id, $this->postData);

        // Check that the batch number does not already exists in the database
        if ($this->Transaction->referenceExists($code, $post['txn_id']))
            return $this->Transaction->failDeposit($code, 'Batch number already processed', $this->postData);

        // Is the account being paid into the correct one?
        if ($post['receiver_email'] != $accountDetails->details)
            return $this->Transaction->failDeposit($code, 'Invalid recipient account', $this->postData);

        // Are we being paid in good ol' United States Dollar$?
        if ('USD' != $post['mc_currency'])
            return $this->Transaction->failDeposit($code, 'Invalid currency', $this->postData);

        // The sender account is not the one we have in the database for that user
        // if ($userDetails->account != $post['PAYER_ACCOUNT'])
        //     return $this->Transaction->failDeposit($code, 'Invalid sender account', $this->postData);

        // Is it the correct amount
        if ($deposit->gross_amount != $post['mc_gross'])
            return $this->Transaction->failDeposit($code, 'Invalid amount', $this->postData);

        // If we are here, it means the transaction is legit and can be added to the user's profile
        $this->db->trans_begin();

        if ($this->Transaction->update($deposit->id, 'ok', array(
            'reference'      => $post['txn_id'],
            'system_account' => $post['receiver_email'],
            'user_account'   => $post['payer_email'],
            'cost'           => $post['payment_fee'],
            'details' => json_encode($post)
        ))
        ) {
            $this->db->trans_commit();
            $this->notify($deposit->id);
        } else {
            $this->db->trans_rollback();
        }
    }

    /******************
     * NOTIFICATION
     */
    public function notify($id, $redirect = FALSE) {

        $this->data->transaction = $transaction = $this->Transaction->getById($id);
        $order = $this->Account->getOrder($transaction->reference_id);
        $user = $this->User->getData($transaction->user_id);
        $data = array(
            'username'    => $user->username,
            'transaction' => $transaction,
            'order'       => $order
        );

        $this->EmailQueue->store($user->email, 'Payment Received', 'emails/cashier/payment_received', $data, 6);

        /*********
         * Process the order
         */
        $this->load->model('referral_model', 'Referral');
        $this->load->model('cashier_model', 'Cashier');

        $this->Account->completeOrders($order->user_id);

        $referral = $user->username;
        $userData = array();

        switch ($order->category) {
            case 'membership':
                $userData['ad_credits'] = $user->ad_credits + $order->ad_credits;
                $userData['te_credits'] = $user->te_credits + $order->te_credits;
                $timeRemaining = 0;
                $now = now();
                if ($order->discount > 0 && $user->account_expires > $now) {
                    $timeRemaining = $user->account_expires - $now;
                }

                // time purchased must be > time remaining per controllers/member::confirm_upgrade
                $expire = CACHE_ONE_DAY*$order->duration*$order->qty - $timeRemaining;

                if ($order->qty >= 12) $expire += CACHE_ONE_DAY*$order->duration;

                if ($expire <= 0) {
                    //$userData['account_expires'] = NULL; // lifetime membership indicator needs changed. See below.
                 /*
                  * The way discounts are implemented expire could be zero and not mean lifetime membership.
                  */
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

                $comm    = $this->Referral->getCommissionTable();
                $refComm = array();
                foreach ($comm as $rc) $refComm[$rc->name] = $rc->levels;

                for ($i = 1; $i <= 5; $i++) { // Pay up 5 levels

                    if ($user->referrer_id == DEFAULT_USER_ID) break;

                    $l1 = $this->User->getData($user->referrer_id);
                    if ($l1) {

                        $amount = roundDown((($order->amount*$order->qty) - $order->discount)*($refComm[$l1->account_level][$i]/100), 2);

                        //send email to free members, inform of comission missed, or pay referral comission to member and email them
                        if ($i > 1 and $l1->account_level == 'Free') {
                            $data = array(
                                'username'       => $l1->username,
                                'referral'       => $referral,
                                'level'          => $i,
                                'amountNovice' => roundDown((($order->amount*$order->qty) - $order->discount)*($refComm['Novice'][$i]/100), 2),
                                'amountAdvanced' => roundDown((($order->amount*$order->qty) - $order->discount)*($refComm['Advanced'][$i]/100), 2),
                                'amountExpert' => roundDown((($order->amount*$order->qty) - $order->discount)*($refComm['Expert'][$i]/100), 2),
                                'description'    => $order->description,
                            );
                            $this->EmailQueue->store($l1->email, 'Referral commission NOT paid', 'emails/referral/rc_not_paid', $data);
                        } else {
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
                                $this->EmailQueue->store($l1->email, 'Referral commission paid', 'emails/referral/rc_paid', $data);
                            }
                        }
                        $user = $l1;
                    } else {
                        break; // this would indicate an error in the hierarchy.
                    }
                }
                break;


            case 'advertising':
                $userData['ad_credits']     = $user->ad_credits + ($order->ad_credits * $order->qty);
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

                if ($user->referrer_id > 0 && $user->referrer_id != DEFAULT_USER_ID) {

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
                        $user = $l1;
                    }
                }
                break;
                
                case 'vacation':
                
                $data = array(
                    'created'        => now(),
                    'username'       => $user->username,
                    'transaction_id' => '#'.str_pad($order->transaction_id, 4, '0', STR_PAD_LEFT),
                    'order'          => $order,
                    'user'           => &$userData,
                    'tokens'         => $order->qty
                );

                $this->EmailQueue->store($user->email, 'Order Completed', 'emails/cashier/order_complete', $data, 6);

                $this->db->insert('surf_vacation', 
                        array(
                            'user_id' => $order->user_id, 
                            'vacation_ends' => strtotime('+ '.$order->duration*$order->qty.' days'), 
                            'purchase_id' => $order->transaction_id
                        )
                        );
                break;
        }
        if ($redirect) $this->success();
    }

    /******************
     * RESULT URLs LAND HERE
     *
     *
     */
    public function success()
    {
        $userData = $this->ion_auth->user()->row();

        $orders = $this->Account->getOrders($userData->id, 'complete');

        $this->session->set_flashdata('success', 'Your purchase is completed. Thank you for your support.');
        redirect('back_office/order_success/'.$orders[0]->id.'');
    }

    public function fail()
    {
        $this->session->set_flashdata('error', 'Your purchase was not completed. Please contact support if you believe there is a problem.');
        redirect ('back_office/order_fail');
    }

    public function cancel() {
        $this->session->set_flashdata('error', 'Your purchase has been cancelled. Please contact support if you believe there is a problem.');
        redirect('back_office/order_fail');
    }
}