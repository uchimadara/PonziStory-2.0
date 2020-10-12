<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('admin.php');

class Cashier extends Admin
{
    public function __construct()
    {
        parent::__construct(TRUE);

        $this->layoutData['title'] = 'Cashier';
        $this->data->menu = $this->loadPartialView('admin/menu');
        $this->load->model('payment_method_model', 'PaymentMethod');
        $this->load->model('transaction_model', 'Transaction');

    }

    public function index()
    {

        $this->data->lists = array();

        $this->data->alltimeStats = $this->Transaction->getSummary();

        $this->data->overhead = $this->Transaction->sumOverhead();
        $this->data->begin = strtotime(date("Y-m-d", now()));

        $this->data->tabs = array(
            'history' => array(
                'url'   => site_url('adminpanel/cashier/viewList/history'),
                'title' => 'History'
            ),
            'purchases' => array(
                'url'   => site_url('adminpanel/cashier/viewList/purchases'),
                'title' => 'Purchases'
            ),
            'withdrawals' => array(
                'url'   => site_url('adminpanel/cashier/viewList/withdrawals'),
                'title' => 'Withdrawals'
            ),
            'Earning' => array(
                'url'   => site_url('adminpanel/cashier/viewList/earning'),
                'title' => 'Earning'
            ),
            'commissions' => array(
                'url'   => site_url('adminpanel/cashier/viewList/commissions'),
                'title' => 'Ref. Comm.'
            ),
            'transfers' => array(
                'url'   => site_url('adminpanel/cashier/viewList/balance_transfers'),
                'title' => 'Transfers'
            ),
            'adjustments' => array(
                'url'   => site_url('adminpanel/cashier/viewList/adjustments'),
                'title' => 'Adjustments'
            ),
        );

        $this->addJavascript(asset('scripts/admin/admin.js'));
        $this->addJavascript(asset('scripts/tabs.js'));

        $this->layoutData['title'] = 'Cashier';

        $this->loadView('admin/cashier/index', 'Cashier', FALSE);
    }

    public function viewList($listName) {

        $this->_path     = 'admin/';
        echo parent::viewList($listName, TRUE);
    }

    public function summary($begin) {
        $end = $begin + CACHE_ONE_DAY - 1;
        $this->data->todayStats = $this->Transaction->getSummary($begin, $end);
        $this->data->begin = $begin;
        echo $this->loadPartialView('admin/cashier/partial/summary');
    }

    public function failed_deposit($id) {
        $this->data->deposit = $this->Transaction->getFailed($id);
        $this->data->deposit->fields = json_decode($this->data->deposit->data);

        $this->loadView('admin/cashier/failed_deposit', 'Failed Deposit');
    }
    public function approve_cashout($id, $refNo) {

       $data['trans'] = (array) $this->Transaction->getById($id);
        $data['trans']['status'] = 'ok';
        $data['trans']['reference'] = $refNo;

        unset($data['trans']['id']);

        if ($this->Transaction->updateCashout($id, $data['trans'])) {
            $data['user'] = (array) $this->ion_auth->select('username, email, email_settings')->user($data['trans']['user_id'])
                                           ->row();

            if ($this->User->getSetting($data['trans']['user_id'], 'email_cashout')) {
                $this->EmailQueue->store($data['user']['email'], 'Your withdrawal has been paid', 'emails/cashier/cashout_approved', $data);
            }
            if (($perPage = $this->session->userdata('perPage')) === FALSE) $perPage = DEFAULT_ITEMS_PER_PAGE;

            $list = new RMSList('admin_lists/', 'pending_cashouts', "admin/getList/pending_cashouts/");
            $list->getPartial(1, $perPage);
            echo $list->render();

        } else {
            echo '<span class="error">Error!</span>';
        }
    }

    public function reject_cashout($id) {

        $data['trans']              = (array)$this->Transaction->getById($id);
        $data['trans']['status']    = 'rejected';

        unset($data['trans']['id']);

        if ($this->Transaction->updateCashout($id, $data['trans'])) {
            $data['user'] = (array)$this->ion_auth->select('username, email, email_settings')
                                                  ->user($data['trans']['user_id'])
                                                  ->row();

            if ($this->User->getSetting($data['trans']['user_id'], 'email_cashout')) {
                $this->EmailQueue->store($data['user']['email'], 'Your withdrawal request is rejected', 'emails/cashier/cashout_rejected', $data);
            }
            if (($perPage = $this->session->userdata('perPage')) === FALSE) $perPage = DEFAULT_ITEMS_PER_PAGE;

            $list = new RMSList('admin_lists/', 'pending_cashouts', "admin/getList/pending_cashouts/");
            $list->getPartial(1, $perPage);
            echo $list->render();
        } else {
            echo '<span class="error">Error!</span>';
        }
    }

    public function accounts($code, $id = NULL)
    {
        $methodsMenu = $this->__methodsMenu($code, site_url('adminpanel/cashier/accounts'));

        $accounts = $this->PaymentMethod->getAccountDetails($code);

        if ($this->ajax)
        {
            $data    = $this->PaymentMethod->getAccountDetailsById($id);
            $account = NULL;
            switch($code)
            {
                case 'lr':
                case 'ap':
                case 'st':
                case 'pm':
                case 'hd':
                    $account = $data->details;
                    break;

                case 'wu':
                    $account = new WesternUnion($data->details);
                    break;

                case 'bw':
                    $account = new BankWire($data->details);
                    break;
            }

            echo $this->loadPartialView('admin/cashier/partial/account_details', compact('code', 'account'));
        }
        else
        {
            $this->layoutData['title'] = 'Cashier Accounts';
            $this->loadView('admin/cashier/accounts', '', compact('methodsMenu', 'accounts'));
        }
    }

    public function payment_methods() {
        if ($this->ajax) {
        } else {
            $this->data->paymentMethods = $this->PaymentMethod->getList();

            $this->data->page_title = 'Payment Methods';
            $this->loadView('admin/cashier/payment_methods', $this->data->page_title );
        }
    }

    // This will be used to either edit an account or adding a new one
    public function account($code, $accountId = NULL)
    {
        $account = NULL;
        if ($accountId)
            $account = $this->PaymentMethod->getAccountDetailsById($accountId);

        if ($this->ajax)
        {
            $post = $this->input->post();

            if ($this->form_validation->run($code . '_account') === TRUE)
            {
                switch ($code)
                {
                    case 'bw':
                        $data = new BankWire($post);
                        $details = $data->__toString();
                        break;

                    case 'wu':
                        $data = new WesternUnion($post);
                        $details = $data->__toString();
                        break;

                    default:
                        $details = $post['account'];
                }

                $data = array(
                    'payment_code'     => $code,
                    'name'             => $post['name'],
                    'details'          => $details,
                    'extra_field_1'    => $post['extra_field_1'],
                    'extra_field_2'    => $post['extra_field_2'],
                    'restrict_to'      => $post['restrict_to'],
                    'minimum'          => $post['minimum'],
                    'maximum'          => $post['maximum'],
                    'maximum_duration' => $post['maximum_duration']
                );

                if ($account)
                    $res = $this->PaymentMethod->updateAccount($accountId, $data);
                else $res = $this->PaymentMethod->addAccount($data);

                if ($res)
                {
                    $this->session->set_flashdata('success', 'Account updated');
                    $data = array(
                        'success' => 'hurray!',
                        'redirect' => array (
                            'url' => site_url('adminpanel/cashier/accounts/' . $code)
                        )
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
        else
        {
            $listCountries = $this->User->getCountries();
            $countries     = dropdown($listCountries, 'name');
            $methodsMenu   = $this->__methodsMenu($code, site_url('adminpanel/cashier/accounts'));

            $this->layoutData['title'] = 'Cashier Accounts';
            $this->loadView('admin/cashier/account', '', compact('methodsMenu', 'code', 'account', 'countries'));
        }
    }

    public function billing($code)
    {
        $methodsMenu = $this->__methodsMenu($code, site_url('adminpanel/cashier/billing'));

        $this->layoutData['title'] = 'Cashier Billing';
        $this->loadView('admin/cashier/index', '', compact('methodsMenu'));
    }

    public function deposits($code = 'any', $status = 'pending', $page = 1, $perpage = 30)
    {
        // pending/completed/add new
        $methodsMenu = $this->__methodsMenu($code, site_url('adminpanel/cashier/deposits'));

        $count = $this->Transaction->countTransactions($code, 'deposit', $status);

        if ($count)
        {
            $data     = $this->Transaction->getDepositsSubset($code, $status, $page, $perpage);
            $paging   = generatePagination(site_url('adminpanel/cashier/deposits/' . $code . '/' . $status), $count, $page, $perpage, TRUE);
            $hasPages = $count > $perpage;

            $deposits = $this->loadPartialView('admin/cashier/partial/deposits', compact ('data', 'paging', 'hasPages', 'status'));
        }
        else $deposits = "No $status deposits found";

        if ($this->ajax)
        {
            echo $deposits;
        }
        else
        {
            $codeUrl = site_url('adminpanel/cashier/deposits/' . $code);

            $this->layoutData['title'] = 'Cashier Deposits';
            $this->loadView('admin/cashier/deposits', '', compact('methodsMenu', 'deposits', 'codeUrl'));
        }
    }

    public function deposit_details($id)
    {
        if ($this->ajax)
        {
            $deposit = $this->Transaction->getDetails($id);
            $userAccount = $this->PaymentMethod->getAccountForUser($deposit->user_id, $deposit->method);

            echo $this->loadPartialView('admin/cashier/partial/deposit_details', compact('deposit', 'userAccount'));
        }
        else
        {
            show_404();
        }
    }

    public function cashout_details($id)
    {
        if ($this->ajax)
        {
            $cashout = $this->Transaction->getDetails($id);
            $audit = $this->Transaction->auditUser($cashout->user_id);

            $userAccount = $this->PaymentMethod->getAccountForUser($cashout->user_id, $cashout->method);
            $userData = $this->User->getData($cashout->user_id);
            echo $this->loadPartialView('admin/cashier/partial/cashout_details', compact('cashout', 'userAccount', 'audit', 'userData'));
        }
        else
        {
            show_404();
        }
    }

    private function __methodsMenu($code = NULL, $url = NULL) {
        $methods = $this->PaymentMethod->enabled()->getAll();
        return $this->loadPartialView('admin/cashier/partial/methods', compact('methods', 'code', 'url'));
    }


    public function cashouts($code='any', $status = 'pending',$page = 1, $perpage = 30)
    {
        // pending/completed/add new
        $this->data->methodsMenu = $this->__methodsMenu($code, site_url('adminpanel/cashier/cashouts'));

        $count = $this->Transaction->countTransactions($code, 'cashout', $status);

        if ($count)
        {
            $data['status'] = $status;
            $data['cashouts']     = $this->Transaction->getCashoutsSubset($code, $status, $page, $perpage);
            $data['paging']   = generatePagination(site_url('adminpanel/cashier/cashouts/' . $code . '/' . $status), $count, $page, $perpage, TRUE);
            $data['hasPages'] = $count > $perpage;

            $this->data->cashouts = $this->loadPartialView('admin/cashier/partial/cashouts', $data);
        }

        if ($this->ajax)
        {
            echo (isset($this->data->cashouts)) ? $this->data->cashouts : '<div class="p-10">No Cashouts</div>';
        }
        else
        {
            $this->data->codeUrl = site_url('adminpanel/cashier/cashouts/' . $code);
            $this->data->status = $status;
            $this->layoutData['title'] = 'Cashier Cashouts';
            $this->loadView('admin/cashier/cashouts');
        }
    }

    public function process_cashouts($code)
    {
        // Find the account(s) to use for the cashout
        $this->data->code = $code;
        $this->data->cashoutIds = $this->input->post('cashout');
        $this->data->references = $this->input->post('reference');
        $this->data->commit     = $this->input->post('commit');

        // Specific fields
        $this->data->costs      = $this->input->post('cost');
        $this->data->infos      = $this->input->post('info');
        $this->data->mtcns      = $this->input->post('mtcn');
        $this->data->accounts   = $this->input->post('account');
        $this->data->amounts    = $this->input->post('pickup_amount');
        $this->data->currencies = $this->input->post('pickup_currency');

        $this->data->cashouts = array();

        // let's do the loop - oh yeah!
        $errors = array();

        foreach ($this->data->cashoutIds as $cashoutId)
        {
            $cashout = $this->Transaction->getCashoutDetails($cashoutId);

            if ($cashout->status != 'pending')
                continue; // if the page times out or is reloaded then cashouts may be sent again - instead we should skip

            $sendFrom = $this->PaymentMethod->getAccountDetails($cashout->method, 'out');
            $reference = isset($this->data->references[$cashoutId]) ? $this->data->references[$cashoutId] : NULL;
            $cost      = isset($this->data->costs[$cashoutId]) ? $this->data->costs[$cashoutId] : NULL;
            $amount    = isset($this->data->amounts[$cashoutId]) ? $this->data->amounts[$cashoutId] : NULL;
            $currency  = isset($this->data->currencies[$cashoutId]) ? $this->data->currencies[$cashoutId] : NULL;

            $data = NULL;

            // if we actually pressed the button to send the funds
            if ($this->data->commit)
            {
                switch ($cashout->method)
                {
                    case 'bw':
                        $info = isset($infos[$cashoutId]) ? $infos[$cashoutId] : NULL;

                        if ($cost && $reference && $info)
                        {
                            $details = new BankWireDetails();
                            $details->info     = $info;
                            $details->amount   = $amount;
                            $details->currency = $currency;

                            $data = array(
                                'account_id' => $sendFrom->id,
                                'details'    => $details->__toString(),
                                'cost'       => $cost,
                                'reference'  => $reference
                            );
                        }

                        break;

                    case 'wu':
                        $mtcn = isset($mtcns[$cashoutId]) ? $mtcns[$cashoutId] : NULL;

                        if ($mtcn && $reference && $amount)
                        {
                            $details = new WesternUnionDetails($cashout->details);
                            $details->mtcn     = $mtcn;
                            $details->amount   = $amount;
                            $details->currency = $currency;

                            $data = array(
                                'account_id' => $sendFrom->id,
                                'details'    => $details->__toString(),
                                'cost'       => 0,
                                'reference'  => $reference
                            );
                        }

                        break;

                    case 'pm':
                        if ($reference) {
                            $data = array(
                                'account_id' => $sendFrom->id,
                                'reference'  => $reference
                            );
                        } else {
                            // trying to open URL to process PerfectMoney Spend request
                            $request = array(
                                'AccountID' => $sendFrom->details,
                                'PassPhrase' => $sendFrom->extra_field1,
                                'Payer_Account' => $sendFrom->details,
                                'Payee_Account' => $cashout->user_account,
                                'Amount' => $cashout->amount,
                                'PAY_IN' => 1,
                                'PAYMENT_ID' => $cashoutId
                            );
                            $f = fopen('https://perfectmoney.is/acct/confirm.asp?'.http_build_query($request), 'rb');

                            if ($f === FALSE) {
                                $errors[$cashoutId] = 'error openning url';
                                continue;
                            }

                            // getting data
                            $out = "";
                            while (!feof($f)) $out .= fgets($f);

                            fclose($f);

                            // searching for hidden fields
                            if (!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $response, PREG_SET_ORDER)) {
                                $errors[$cashoutId] = 'Invalid response';
                                continue;
                            }

                            $params = array();
                            foreach ($response as $item) {
                                $params[$item[1]] = $item[2];
                            }

                            if (array_key_exists('ERROR', $params)) {
                                $errors[$cashoutId] = $params['ERROR'];
                                continue;
                            }

                            $data = array(
                                'account_id' => $sendFrom->id,
                                'reference'  => $params['PAYMENT_BATCH_NUM']
                            );
                        }

                        break;

                    case 'st':
                        if ($reference) {
                            $data = array(
                                'account_id' => $sendFrom->id,
                                'reference'  => $reference
                            );
                        } else {
                            $testmode   = 1;
                            $urladdress = "https://solidtrustpay.com/accapi/process.php"; //https://solidtrustpay.com/accapi/process.php;

                            $request = array(
                                'user'        => $cashout->user_account,
                                'testmode'    => $testmode,
                                'api_id'      => $sendFrom->extra_field1,
                                'api_pwd'     => md5($sendFrom->extra_field2.'s+E_a*'),
                                'paycurrency' => 'USD',
                                'comments'    => '',
                                'fee'         => '',
                                'udf1'        => $cashoutId,
                                'udf2'        => ''
                            );

                            // Call STP API

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, "$urladdress");
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_HEADER, 0); //use this to suppress output
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // tell cURL to graciously accept an SSL certificate
                            $response = curl_exec($ch); // or die(curl_error($ch));
                            if ($err = curl_error($ch)) {
                                $errors[$cashoutId] = $err;
                            } elseif ($response != 'ACCEPTED') {
                                $errors[$cashoutId] = $response;
                            } else {
                                $data = array(
                                    'account_id' => $sendFrom->id,
                                    'reference'  => $response['tr_id']
                                );
                            }
                            curl_close($ch);
                        }
                        break;

                    // all other methods are (should be) automatic
                    default:
                        // manual reference entered so just use it instead of processing the payment
                        if ($reference)
                        {
                            $data = array(
                                'account_id' => $sendFrom->id,
                                'reference' => $reference
                            );
                        }
                        else
                        {
                            //TODO: Process automatically (add to an array?)
                        }
                }
                // If we have data we can try to update the cashout
                if ($data && $this->Transaction->update($cashoutId, 'ok', $data)) {
                    // Refresh the data to grab all the necessary new information
                    $cashout = $this->Transaction->getCashoutDetails($cashoutId);

                    $userId = $cashout->user_id;
                    $email  = $this->ion_auth->select('email')->user($userId)->row()->email;

                    $fromAccount = &$sendFrom;
                    $userAccount = $this->PaymentMethod->getByUserId($userId, $code);

                    // The cashout has been stored properly so send an email
                    switch ($code) {
                        case 'bw':
                            $this->EmailQueue->store($email, 'Bank Wire Cashout Sent', 'emails/cashier/cashout_sent_bw', compact('cashout', 'userAccount'));
                            break;

                        case 'wu':
                            $this->EmailQueue->store($email, 'Western Union Cashout Sent', 'emails/cashier/cashout_sent_wu', compact('cashout', 'fromAccount', 'userAccount'));
                            break;
                    }
                }
            } else { // add to process array
                $this->data->cashouts[$cashoutId] = $cashout;
            }
        }

        if (count ($this->data->cashouts))
        {
           // $this->data->fromAccounts = dropdown($this->data->sendFrom);

            $this->layoutData['title'] = 'Process Cashouts';
            $this->loadView('admin/cashier/preview_cashouts', 'Preview Cashouts');
        }
        else{
            if ($errors) {
                $errMsg = '';
                foreach ($errors as $eID => $e) $errMsg .= 'Cashout ID '.$eID.' Failed: '.$e.'<br/>';
                $this->session->set_flashdata('error', $errMsg);
            }
            redirect('adminpanel/cashier/cashouts/'.$code);
        }
    }

    public function reject($id)
    {
        $transactionData = $this->Transaction->getDetails($id);
        $type            = $transactionData->type;
        $userId          = $transactionData->user_id;
        $email           = $this->ion_auth->select('email')->user($userId)->row()->email;

        switch ($type)
        {
            case 'deposit':
                $this->EmailQueue->store($email, 'Deposit Request Cancelled', 'emails/cashier/deposit_rejected', compact('transactionData'));

                break;

            case 'cashout':
                $this->EmailQueue->store($email, 'Cashout Request Cancelled', 'emails/cashier/cashout_rejected', compact('transactionData'));

                break;
        }

        $this->Transaction->update($id, 'reject');

        return;
    }

    public function reset($id)
    {
        $this->Transaction->update($id, 'pending');

        return;
    }

    public function deposit($depositId, $userId = NULL, $code = NULL)
    {
        $details = NULL;
        if ($depositId > 0)
        {
            $details = $this->Transaction->getDetails($depositId);
            if (!$details || $details->status != 'pending')
                redirect('adminpanel/cashier');

            $code   = $details->method;
            $userId = $details->user_id;
        }

        $username    = $this->ion_auth->select('username')->user($userId)->row()->username;
        $accounts    = dropdown($this->PaymentMethod->getAccountDetails($code, 'in'));
        $userAccount = $this->PaymentMethod->getAccountForUser($userId, $code);

        if (!$userAccount)
        {
            // User does not have an account for this payment method
            $this->session->set_flashdata('error', "$username does not have a $code account");
            redirect('adminpanel/cashier');
        }

        if ($this->ajax)
        {
            if ($this->form_validation->run('admin/cashier/deposit') !== FALSE)
            {
                $post = $this->input->post();

                // Ok so we know we need to set the reference number and that it exists
                $newData = array(
                    'reference' => $post['reference']
                );

                $depositDetails = NULL;
                switch ($code)
                {
                    case 'bw':
                        $depositDetails = new BankWireDetails($details ? $details->details : NULL);
                        if ($depositDetails->info != $post['info'])
                            $depositDetails->info = $post['info'];

                        if ($depositDetails->memo != $post['memo'])
                            $depositDetails->memo = $post['memo'];

                        break;

                    case 'wu':
                        $depositDetails = new WesternUnionDetails($details ? $details->details : NULL);
                        if ($depositDetails->city != $post['city'])
                            $depositDetails->city = $post['city'];

                        if ($depositDetails->region != $post['region'])
                            $depositDetails->region = $post['region'];

                        if ($depositDetails->zip != $post['zip'])
                            $depositDetails->zip = $post['zip'];

                        if ($depositDetails->country != $post['country'])
                            $depositDetails->country = $post['country'];

                        if ($depositDetails->mtcn != $post['mtcn'])
                            $depositDetails->mtcn = $post['mtcn'];

                        if ($depositDetails->transfer_date != $post['transfer_date'])
                            $depositDetails->transfer_date = $post['transfer_date'];

                        break;
                }

                if ($details)
                {
                    // Editing a deposit
                    if ($depositDetails && $depositDetails->__toString() != $details->details)
                    {
                        // Some of the details given by the user had to be changed for whatever reason
                        $newData = array_merge ($newData, array(
                            'details' => $depositDetails->__toString()
                        ));
                    }

                    if ($post['gross_amount'] != $details->gross_amount)
                    {
                        // If the amount received has changed one then we need to adjust the fee/cost
                        $grossAmount = $post['gross_amount'];
                        $fee         = roundUp($this->PaymentMethod->calculateGross($grossAmount, $code, 'fee', 'deposit'));
                        $netAmount   = $grossAmount - $fee;

                        $newData = array_merge ($newData, array(
                            'gross_amount' => $grossAmount,
                            'amount'       => $netAmount,
                            'fee'          => $fee
                        ));
                    }

                    if ($post['cost'] != $details->cost)
                    {
                        $newData = array_merge ($newData, array(
                            'cost' => $post['cost']
                        ));
                    }

                    // if the account has changed (mainly for Bank Wires)
                    if ($post['account_id'] != $details->account_id)
                    {
                        $newData = array_merge ($newData, array(
                            'account_id' => $post['account_id']
                        ));
                    }
                }
                else
                {
                    // Adding a deposit
                    $depositData = array(
                        'user_id'      => $userId,
                        'method'       => $code,
                        'account_id'   => $post['account_id'],
                        'gross_amount' => $post['gross_amount'],
                        'cost'         => isset($post['cost']) ? $post['cost'] : NULL,
                        'identifier'   => $this->Transaction->identifier()
                    );
                    $depositData['fee'] = roundUp($this->PaymentMethod->calculateGross($depositData['grossAmount'], $code, 'fee', 'deposit'));
                    $depositData['amount'] = $depositData['grossAmount'] - $depositData['fee'];

                    if ($depositDetails)
                        $depositData['details'] = $depositDetails->__toString();

                    $depositId = $this->Transaction->addDeposit($depositData);
                }

                $this->db->trans_begin();

                if ($this->Transaction->update($depositId, 'ok', $newData))
                {
                    $this->db->trans_commit();

                    // Email the user when we got the deposit from Western Union or Bank Wire
                    if ($code == 'bw' || $code == 'wu')
                    {
                        $email = $this->ion_auth->select('email')->user($userId)->row()->email;
                        $newDepositData = $this->Transaction->getById($depositId);
                        $this->EmailQueue->store($email, 'Deposit Received', 'emails/cashier/deposit_received_' . $code, compact('newDepositData', 'depositDetails', 'userAccount'));
                    }

                    $this->session->set_flashdata('success', 'Successfully added deposit');
                    $data = array(
                        'success'  => 'success',
                        'redirect' => array(
                            'url' => site_url('adminpanel/cashier'),
                        )
                    );
                }
                else
                {
                    $this->db->trans_rollback();

                    $data = array(
                        'error' => 'Problem with the deposit data'
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

        $listCountries = $this->User->getCountries();
        $countries     = dropdown($listCountries, 'name');

        $this->layoutData['title'] = 'Manage Deposit';
        $this->loadView('admin/cashier/deposit', '', compact('userId', 'username', 'code', 'details', 'userAccount', 'accounts', 'countries'));
    }

    public function method_status() {
        if ($this->ajax) {
            $accountId = $this->input->post('account_id');
            $enabled   = $this->input->post('enabled');

            $this->PaymentMethod->methodStatus($accountId, $enabled ? 1 : 0);
        }
    }

    public function account_status()
    {
        if ($this->ajax)
        {
            $accountId = $this->input->post('account_id');
            $enabled   = $this->input->post('enabled');

            $this->PaymentMethod->accountStatus($accountId, $enabled ? 1 : 0);
        }
    }

    public function settings($code = NULL)
    {
        if ($this->ajax)
        {
            $post = $this->input->post();
            $data = array(
                'percent' => isset($post['percent']) ? $post['percent'] : NULL,
                'fixed'   => isset($post['fixed']) ? $post['fixed'] : NULL,
                'max'     => $post['max'] ? $post['max'] : NULL
            );

            if ($this->PaymentMethod->updateMethodBill($code, $post['type'], $post['operation'], $data))
            {
                $data = array(
                    'success' => 'success'
                );
            }
            else
            {
                $data = array(
                    'error' => 'Error Updating Data!'
                );
            }

            echo json_encode($data);
        }
        else
        {
            // We need to get the deposit and cashout fees + costs all the time
            $billing  = array(
                'deposit' => array(
                    'fee'  => $this->PaymentMethod->getFeeData($code, 'deposit', 'fee'),
                    'cost' => $this->PaymentMethod->getFeeData($code, 'deposit', 'cost')
                ),
                'cashout' => array(
                    'fee'  => $this->PaymentMethod->getFeeData($code, 'cashout', 'fee'),
                    'cost' => $this->PaymentMethod->getFeeData($code, 'cashout', 'cost')
                )
            );

            $this->layoutData['title'] = 'Cashier - ' . strtoupper($code) . ' Settings';
            $this->loadView('admin/cashier/settings', '', compact('billing', 'code'));
        }
    }

    public function user_account_details($userId, $code)
    {
        $data    = $this->PaymentMethod->getAccountForUser($userId, $code);
        $account = NULL;
        switch($code)
        {
            case 'lr':
            case 'ap':
            case 'st':
            case 'pm':
            case 'hd':
                $account = $data->account;
                break;

            case 'wu':
                $account = new WesternUnion($data->account);
                break;

            case 'bw':
                $account = new BankWire($data->account);
                break;
        }

        echo $this->loadPartialView('admin/cashier/partial/account_details', compact('code', 'account'));
    }

    public function users($code, $page = 1, $perpage = 50)
    {
        $balances         = $this->Cashier->getUsersBalances($code, $page, $perpage);
        $count            = $this->Cashier->countGetUsersBalances($code);
        $balance_paging   = generatePagination(site_url("adminpanel/cashier/users/$code"), $count, $page, $perpage, TRUE);

        $balance_hasPages = $count > $perpage;
        $balanceUserTable = $this->loadPartialView('admin/cashier/partial/user_balances_table',compact('balances', 'balance_paging', 'balance_hasPages', 'count', 'code'));

        if ($this->ajax)
        {
            echo $balanceUserTable;
        }
        else
        {
            $methodsMenu = $this->__methodsMenu($code, site_url('adminpanel/cashier/accounts'));

            $this->layoutData['title'] = 'Balances';
            $this->loadView('admin/cashier/user_balances', ' Admin', compact('balanceUserTable','methodsMenu','balance_paging','balances_hasPages'));
        }
    }

    // Temporary function
    public function fixTransaction($ref, $amount)
    {
        $this->Cashier->fixTransaction($ref, $amount);
    }

    public function remove_lr()
    {
        $this->load->model('earning_transfer_model', 'Transfer');

        echo "Cancelling all LR Shares Marketplace ... ";
        $this->Transfer->doMarketplaceShares();
        echo "done!<br/>";

        echo "User shares ... ";
        $this->Transfer->doUserShares();
        echo "done!<br/>";

        echo "Transferring all balances ... ";
        $this->Transfer->doTransfers();
        echo "done!<br/>";
    }
}