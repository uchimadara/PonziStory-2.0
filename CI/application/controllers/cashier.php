<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cashier extends MY_Controller {
    public function __construct() {
        parent::__construct();

        if ($this->isGuest) redirect();

        $this->load->model('payment_method_model', 'PaymentMethod');
        $this->load->model('cashier_model', 'Cashier');
        //$this->layout        = 'layout/member/shell';
        $this->data->menu = $this->loadPartialView('partial/menu');
        $this->data->userSettings = $this->User->getSettings($this->userId);
    }

    function index() {
        show_404();
    }

    public function add_funds() {
            $userId           = $this->profile->id;
            $data['balances'] = $this->PaymentMethod->getBalancesList($userId);

            return $this->loadPartialView('cashier/add_funds', $data);
    }

    public function cashier_page_cashout() {
        if ($this->ajax) {
            $userId           = $this->profile->id;
            $data['balances'] = $this->PaymentMethod->getBalancesList($userId);

            echo $this->loadPartialView('cashier/cashouts', $data);
        } else show_404();
    }

    public function transaction($id) {
        if (!$this->ajax)
            show_404();

        $transaction = $this->Transaction->getDetails($id, $this->profile->id);

        if (!$transaction) {
            echo '<span class="error">No transaction found.</span>';
            return;
        }

        $accountDetails     = NULL;
        $mtvAccountDetails  = NULL;
        $transactionDetails = NULL;
        switch ($transaction->method) {
            case 'wu':
                $transactionDetails = new WesternUnionDetails($transaction->details);
                $userAccount        = new WesternUnion($transaction->user_account);
                $systemAccount      = new WesternUnion($transaction->system_account);

                break;

            case 'bw':
                $transactionDetails = new BankWireDetails($transaction->details);
                $userAccount        = new BankWire($transaction->user_account);
                $systemAccount      = new BankWire($transaction->system_account);

                break;

            default:
                $userAccount   = $transaction->user_account;
                $systemAccount = $transaction->system_account;
        }

        echo $this->loadPartialView('cashier/partial/transaction', compact('transaction', 'transactionDetails', 'userAccount', 'systemAccount'));
    }

    // This function allows people to cancel their pending transaction

    public function cancel_transaction($id) {
        $this->load->model('transaction_model', 'Transaction');
        $data['trans']           = (array)$this->Transaction->getById($id);
        $data['trans']['status'] = 'cancelled';

        unset($data['trans']['id']);

        $this->db->trans_begin();
        if ($this->Transaction->updateCashout($id, $data['trans'])) {
            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Transaction cancelled.');
            echo 'Transaction cancelled! Page will reload...<script>window.location.reload();</script>';

        } else {
            $this->db->trans_rollback();

            echo '<span class="error">Cannot update the transaction</span>';
        }
    }

    public function cancel_cashout($id) {
        if (!$this->ajax) show_error('Invalid access');

        $this->load->model('transaction_model', 'Transaction');
        $trans           = (array) $this->Transaction->getById($id);
        if ($trans['user_id'] != $this->profile->id) {
            echo json_encode(array(
                'replace' => array(
                    'cancelCashoutButton' => '<span class="error">error</span>',
                )
            ));
            return;
        }

        $trans['status'] = 'cancelled';

        $this->db->trans_begin();
        if ($this->Transaction->updateCashout($id, $trans)) {
            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Transaction cancelled.');

            $balance = $this->PaymentMethod->getBalance($this->profile->id, $trans['method']);
            $bTotal = $this->PaymentMethod->getTotalBalances($this->profile->id);

            echo json_encode(array(
                'replace' => array(
                    'balance'.$trans['method'] => money($balance),
                    'bTotal' => money($bTotal),
                    'cashoutDiv' => ''
                )
            ));

        } else {
            $this->db->trans_rollback();

            echo json_encode(array(
                'replace' => array(
                    'cancelCashoutButton' => '<span class="error">error</span>',
                )
            ));
        }
    }

    public function account($code, $transType) {
        if ($code == 'eb')
            show_error('Forbidden', 403); // failsafe

        $userId = $this->profile->id;

        $this->data->account = $this->PaymentMethod->getAccountForUser($userId, $code);
        $this->data->code = $code;

        if (!$this->data->account->enabled) {
            $this->session->set_flashdata('error', 'Sorry but <strong>'.$this->data->account->name.'</strong> '.$transType.' has not been enabled');
            redirect('back_office/account.html');
        }

        if ($this->data->account->account == NULL || $this->data->account->locked == 0) // Add or edit
        {
            if ($_POST) {
                if ($this->form_validation->run($code.'_account') === TRUE) {
                    $post = $this->input->post();

                    switch ($code) {
                        case 'wu':
                            $accountData = new WesternUnion($post);
                            $accountData = $accountData->__toString();
                            break;

                        case 'bw':
                            $accountData = new BankWire($post);
                            $accountData = $accountData->__toString();
                            break;

                        case 'pm':
                            $accountData = strtoupper($post['account']);
                            break;

                        case 'bc':
                            $accountData = $post['account'];
                            break;

                        case 'st':
                            $accountData = $post['account'];
                            break;

                        default:
                            $accountData = strtolower($post['account']);
                    }

                    if ($this->PaymentMethod->set($userId, $code, $accountData)) {
                        $this->session->set_flashdata('success', 'Successfully updated your <strong>'.$this->data->account->name.'</strong> account');

                        $data = array(
                            'success'  => 'success',
                            'redirect' => array(
                                'url' => site_url('cashier/'.$transType.'_method/'.$code)
                            )
                        );
                    } else {
                        $data = array(
                            'error' => 'An unknown error has occurred'
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }

                echo json_encode($data);
                return;
            } else {

                //$this->data->countries = $this->picklist->select_values('country_list');
                //$this->data->country   = $this->profile->country_id;

                $this->data->transType = $transType;
                echo $this->loadPartialView('cashier/account');
            }
        } else // All set and locked so go away
        {
            $this->session->set_flashdata('info', 'Your <strong>'.$this->data->account->name.'</strong> account is locked and cannot be changed');
            redirect('back_office/account.html');
        }
    }

    public function cancel_order() {
        $this->load->model('cashier_model', 'Cashier');

        $orders = $this->Account->cancelOrders($this->profile->id);
        $this->session->set_flashdata('success', 'Order cancelled.');
        redirect('back_office/account.html');
    }

    public function confirm_order($salt) {

        //if ($salt != $this->session->flashdata('salt')) show_error('Invalid entry.'); // fails sometimes due to session problems

        $userId             = $this->profile->id;
        $orders = $this->Account->getOrders($userId, 'Pending');
        if (empty($orders)) show_error('Order not found');
        $this->data->code   = $orders[0]->method; // should only ever be one order.
        $this->data->order = $orders[0];

        $this->data->item = $this->Account->getPurchaseItem($this->data->order->code);

        $this->addStyleSheet(asset('styles/orders.css'));

        $this->data->fee = $this->PaymentMethod->getBillDetails($this->data->code);

        if ($this->data->code == 'bc') {
            $this->data->exchange = file_get_contents("https://blockchain.info/tobtc?currency=USD&value=1");
        }
        //$this->data->countries = $this->picklist->select_values('country_list');
        //$this->addJavascript(asset('scripts/cashier.js'));
        $this->addStyleSheet(asset('styles/cashier.css'));
        $this->addStyleSheet(asset('styles/depositmethods.css'));

        $this->loadView('cashier/confirm_order', SITE_NAME.' Confirm Order');
    }

    public function complete_order() {

        if ($this->ajax) {

            $userId             = $this->profile->id;
            $this->data->orders = $this->Account->getOrders($userId, 'Pending');
            if (empty($this->data->orders)) {
                echo json_encode(array('error' => 'An unknown error has occurred. No pending order'));
                return;
            }

            $order = $this->data->orders[0];

            $this->data->code = $order->method;
            $account = $this->PaymentMethod->getAccountDetails($order->method, 'in');

            $post['currency'] = 'USD'; // Set the currency to USD by default

            $orderData = array(
                'user_id'      => $userId,
                'method'       => $this->data->code,
                'item_code' => $order->category,
                'reference_id' => $order->id,
                'account_id' => ($account) ? $account->id : NULL,
                'gross_amount' => $order->total,
                'amount' => $order->total,
                'type' => 'deposit',
                'status' => 'pending',
                'identifier'   => $this->Transaction->identifier()
            );

            if ($this->data->code == 'bc') {
                $orderData['btc_exchange'] = file_get_contents("https://blockchain.info/tobtc?currency=USD&value=1");

            }
            $this->db->trans_begin();

            if ($transactionId = $this->Transaction->add($orderData)) {

                $this->db->trans_commit();

                $this->Account->processOrders($userId, $transactionId);

                if ($order->apply_balance < (($order->qty * $order->amount) - $order->discount)) {
                    $data = array(
                        'depositData'    => &$orderData,
                        'depositAccount' => &$account,
                        'order'          => &$order,
                    );

//                    if ($this->data->code == 'bc')
//                        $data['nonce'] = $this->redis->incr('bitcoin:nonce');

                    $result = array(
                        'success' => 'success',
                        'html'    => $this->loadPartialView('cashier/buttons/' . $this->data->code, $data)
                    );
                } else {

                    $this->Transaction->update($transactionId, 'processing');

                    $this->session->set_flashdata('success', 'Your order is complete.');
                    $result = array(
                        'success' => 'success',
                        'redirect' => array ('url' => SITE_ADDRESS.'callback/notify/'.$transactionId.'/1')
                    );
                }
            } else {
                $this->db->trans_rollback();

                $result = array(
                    'error' => 'An unknown error has occurred'
                );
            }

            echo json_encode($result);
            return;

        } else {
            show_404();
        }

    }

    public function process_paypal_expresspay($orderId) {

        $order   = $this->Account->getOrder($orderId);
        $account = $this->PaymentMethod->getAccountDetails($order->method);

        $this->load->library('merchant');
        $this->merchant->load('paypal_express');

        $settings = array(
            'username'  => $account->details,
            'password'  => $account->extra_field_1,
            'signature' => $account->extra_field_2);

        $this->merchant->initialize($settings);

        $params = array(
            'amount'      => $order->total,
            'description' => $order->description,
            'quantity'    => 1,
            'currency'    => 'USD',
            'return_url'  => SITE_PROTO . '://'.SITE_DOMAIN.'/purchase_confirm.html',
            'cancel_url'  => SITE_PROTO . '://'.SITE_DOMAIN.'/purchase_cancel.html');

        $response = $this->merchant->purchase($params);

        echo print_r($response);
    }

    public function process_paypal($orderId) {

        $order = $this->Account->getOrder($orderId);
        $account = $this->PaymentMethod->getAccountDetails($order->method);

        $this->load->model('transaction_model', 'Transaction');

        $transaction = $this->Transaction->getTransaction($order->transaction_id);

        $config['business']           = 'admin@'.SITE_DOMAIN;
        $config['custom'] = $transaction->identifier;
        $config['cpp_header_image']   = ''; //Image header url [750 pixels wide by 90 pixels high]
        $config['return']             = SITE_PROTO . '://'.SITE_DOMAIN.'/purchase_confirm.html';
        $config['cancel_return']      = SITE_PROTO . '://'.SITE_DOMAIN.'/purchase_cancel.html';
        $config['notify_url']         = SITE_PROTO . '://'.SITE_DOMAIN.'/callback/process_pp'; //IPN Post
        $config['production']         = TRUE; //Its false by default and will use sandbox
        $this->load->library('paypal', $config);
        $this->paypal->add($order->description, $order->total, 1, $transaction->identifier); //First item
        $this->paypal->pay(); //Proccess the payment
    }

    public function process_order($salt) {

        //if ($salt != $this->session->flashdata('salt')) show_error('Invalid entry.');

        $userId             = $this->profile->id;

        $this->data->orders = $this->Account->getOrders($userId, 'Pending');
        $this->data->code   = $this->data->orders[0]->method;
        $this->data->balance = $this->PaymentMethod->getBalance($userId, $this->data->code);

            $this->addStyleSheet(asset('styles/depositmethods.css'));
            $this->addStyleSheet(asset('styles/orders.css'));

            // $depositMethodInfo = $this->loadPartialView('cashier/partial/deposit_method_info', compact('code', 'depositAccount', 'fees'));
            $this->loadView('cashier/confirm_order', 'Purchase Confirmation');
    }

    public function email_instructions($code, $accountId = 0) {
        if ($this->ajax) {
            if ($accountId > 0)
                $depositAccount = $this->PaymentMethod->getAccountDetailsById($accountId);
            else {
                $accounts       = $this->PaymentMethod->getAccountDetails($code);
                $depositAccount = $accounts[0];
            }

            $fees = $this->PaymentMethod->getFeeData($code, 'deposit');

            switch ($code) {
                case 'bw':
                    $emailSubject   = 'Bank Wire Deposit Instructions';
                    $depositDetails = new BankWire($depositAccount->details);
                    break;

                case 'wu':
                    $emailSubject   = 'Western Union Deposit Instructions';
                    $depositDetails = new WesternUnion($depositAccount->details);
                    break;

                default:
                    return; // Don't want to be here if not BW or WU
            }

            $this->EmailQueue->store($this->profile->email, $emailSubject, 'emails/cashier/deposit_instructions_'.$code, compact('depositAccount', 'depositDetails', 'fees'));
        }
    }

    public function purchase_loan($loanId) {

        $this->load->model('loan_model', 'Loan');
        $loan = $this->Loan->getData( $loanId );
        $this->data = $loan;
        //var_dump($loan);

        $this->addStyleSheet(asset('styles/upgrade.css'));
        $this->addJavascript(asset('scripts/upgrade.js'));
        $this->load->model('payment_method_model', 'PaymentMethod');

        $this->data->accounts       = $this->PaymentMethod->getAccountDetails(NULL, 'in');
        $this->data->fees           = $this->PaymentMethod->getFees('deposit');
        $this->data->paymentMethods = $this->PaymentMethod->enabled()->getList($this->profile->id);

        $this->data->transType = 'purchase_loan';
        $this->data->balances  = $this->PaymentMethod->getBalancesList($this->userId);
        $this->data->extra_id = $loanId;

        $this->addStyleSheet(asset('styles/cashier.css'));
        $this->addJavascript(asset('scripts/replace.js'));
        $this->data->accountSelect = $this->loadPartialView('cashier/account_select');

        $this->loadView('cashier/purchase_loan', 'Purchase loan funds');
    }

    public function purchase_loan_method($code, $loanId) {

        $orders = $this->Account->getOrders($this->profile->id, 'Pending');

        if (!empty($orders)) {
            $this->session->set_flashdata('warning', 'Pending order exists. Please confirm or cancel.');
            $salt = random_string();
            $this->session->set_flashdata('salt', $salt);

            redirect('confirm_order/'.$salt);
        }

        $this->data->fee = $this->PaymentMethod->getFeeData($code, 'deposit');

        $this->load->model('loan_model', 'Loan');
        $loan = $this->Loan->getData( $loanId );
        $this->data->loan = $loan;
        //var_dump($loan);

        if ($_POST) {
            if ($this->form_validation->run('purchase_loan')) {
                $post   = $this->input->post();
                $amount = intval($post['amount']);

                $this->load->model('payment_method_model', 'PaymentMethod');
                $account = $this->PaymentMethod->getAccountDetails($code, 'in');

                $total_invested = $this->Loan->getTotalInvested( $loan->id, TRUE );

                if (!isset($post['agree']) || $post['agree'] != '1') {
                    $result = array('error' => "Please read and agree to our Terms of Service.");
                } elseif ($amount < $account->min_deposit) {
                    $result = array(
                        'errorElements' => array('amount' => money($account->min_deposit).' minimum')
                    );
                } elseif ($amount > $account->max_deposit) {
                    $result = array(
                        'errorElements' => array('amount' => money($account->max_deposit).' maximum')
                    );
                } elseif ($amount < $loan->min_investment) {
                    $result = array(
                        'errorElements' => array('amount' => money($loan->min_investment).' minimum')
                    );
                } elseif ($amount > $loan->max_investment) {
                    $result = array(
                        'errorElements' => array('amount' => money($loan->max_investment).' maximum')
                    );
                } elseif ($amount+$total_invested > $loan->amount) {
                    $result = array(
                        'errorElements' => array('amount' => money( $loan->amount - $total_invested ).' to complete fund')
                    );
                } else {

                    $item = $this->Account->getPurchaseItem('loan_funds');

                    $qty            = $amount;
                    $total          = $qty*$item->price;
                    $appliedBalance = 0;
                    $fees            = $this->PaymentMethod->getBillDetails($code);
                    $fee            = roundup((-$total - $fees['deposit']['fee']->fixed)/(($fees['deposit']['fee']->percent/100) - 1), 2) - $total;
                    $total += $fee;
                    $discount = NULL;

                    $this->data->order = array(
                        'user_id'          => $this->profile->id,
                        'amount'           => $item->price,
                        'qty'              => $qty,
                        'fee'              => $fee,
                        'method'           => $code,
                        'purchase_item_id' => $item->id,
                        'created'          => now(),
                        'description'      => $qty.' '.$item->title,
                        'apply_balance'    => $appliedBalance,
                        'total'            => $total,
                        'discount'         => $discount,
                        'reference_id'     => $loan->id
                    );

                    $this->Account->addOrder($this->data->order);
                    $salt = random_string();
                    $this->session->set_flashdata('salt', $salt);

                    $result = array(
                        'success'  => 'Ok',
                        'redirect' => array(
                            'url'  => site_url('confirm_order/'.$salt),
                            'hash' => ''
                        )
                    );
                }
            } else {
                $result = array(
                    'errorElements' => $this->form_validation->error_array()
                );
            }

            echo json_encode($result);

        } else {
            if (empty($this->profile->secret_question)) {
                $this->session->set_flashdata('error', 'Please set up your secret question and answer before adding funds.');
                redirect('back_office/profile.html');
            } else {
                $this->addStyleSheet(asset('styles/depositmethods.css'));
                $this->addStyleSheet(asset('styles/cashier.css'));
                $this->addJavascript(asset('scripts/replace.js'));

                $this->data->code = $code;
                $this->data->account = $this->PaymentMethod->getAccountForUser($this->userId, $code);
                $this->data->transType = 'purchase_loan';
                $this->loadView('cashier/purchase_loan_form', 'Purchase Loan Funds');
            }
        }
    }

    public function deposit() {

        $this->addStyleSheet(asset('styles/upgrade.css'));
        $this->addJavascript(asset('scripts/upgrade.js'));
        $this->load->model('payment_method_model', 'PaymentMethod');

        $orders = $this->Account->getOrders($this->profile->id, 'Pending');

        if (!empty($orders)) {
            $this->session->set_flashdata('warning', 'Pending order exists. Please confirm or cancel.');
            $salt = random_string();
            $this->session->set_flashdata('salt', $salt);

            redirect('confirm_order/'.$salt);
        }

        $this->data->accounts       = $this->PaymentMethod->getAccountDetails(NULL, 'in');
        $this->data->fees           = $this->PaymentMethod->getFees('deposit');
        $this->data->paymentMethods = $this->PaymentMethod->enabled()->getList($this->profile->id);

        $this->data->transType = 'deposit';
        $this->data->balances  = $this->PaymentMethod->getBalancesList($this->userId);

        $this->addStyleSheet(asset('styles/cashier.css'));
        $this->addJavascript(asset('scripts/replace.js'));
        $this->data->accountSelect = $this->loadPartialView('cashier/account_select');

        $this->loadView('cashier/add_funds', 'Add Funds');
    }

    public function deposit_method($code) {

        $orders = $this->Account->getOrders($this->profile->id, 'Pending');

        if (!empty($orders)) {
            $this->session->set_flashdata('warning', 'Pending order exists. Please confirm or cancel.');
            $salt = random_string();
            $this->session->set_flashdata('salt', $salt);

            redirect('confirm_order/'.$salt);
        }

        $this->data->fee = $this->PaymentMethod->getFeeData($code, 'deposit');

        if ($_POST) {
            if ($this->form_validation->run('deposit')) {
                $post   = $this->input->post();
                $amount = intval($post['amount']);

                $this->load->model('payment_method_model', 'PaymentMethod');
                $account = $this->PaymentMethod->getAccountDetails($code, 'in');

                if (!isset($post['agree']) || $post['agree'] != '1') {
                    $result = array('error' => "Please read and agree to our Terms of Service.");
                } elseif ($amount < $account->min_deposit) {
                    $result = array(
                        'errorElements' => array('amount' => money($account->min_deposit).' minimum')
                    );
                } elseif ($amount > $account->max_deposit) {
                    $result = array(
                        'errorElements' => array('amount' => money($account->max_deposit).' maximum')
                    );
                } else {

                    $item = $this->Account->getPurchaseItem('account_funds');

                    $qty            = $amount;
                    $total          = $qty*$item->price;
                    $appliedBalance = 0;
                    $fees            = $this->PaymentMethod->getBillDetails($code);
                    $fee            = roundup((-$total - $fees['deposit']['fee']->fixed)/(($fees['deposit']['fee']->percent/100) - 1), 2) - $total;
                    $total += $fee;
                    $discount = NULL;

                    $this->data->order = array(
                        'user_id'          => $this->profile->id,
                        'amount'           => $item->price,
                        'qty'              => $qty,
                        'fee'              => $fee,
                        'method'           => $code,
                        'purchase_item_id' => $item->id,
                        'created'          => now(),
                        'description'      => $qty.' '.$item->title,
                        'apply_balance'    => $appliedBalance,
                        'total'            => $total,
                        'discount'         => $discount
                    );

                    $this->Account->addOrder($this->data->order);
                    $salt = random_string();
                    $this->session->set_flashdata('salt', $salt);

                    $result = array(
                        'success'  => 'Ok',
                        'redirect' => array(
                            'url'  => site_url('confirm_order/'.$salt),
                            'hash' => ''
                        )
                    );
                }
            } else {
                $result = array(
                    'errorElements' => $this->form_validation->error_array()
                );
            }

            echo json_encode($result);

        } else {
            if (empty($this->profile->secret_question)) {
                $this->session->set_flashdata('error', 'Please set up your secret question and answer before adding funds.');
                redirect('back_office/profile.html');
            } else {
                $this->addStyleSheet(asset('styles/depositmethods.css'));
                $this->addStyleSheet(asset('styles/cashier.css'));
                $this->addJavascript(asset('scripts/replace.js'));

                $this->data->code = $code;
                $this->data->account = $this->PaymentMethod->getAccountForUser($this->userId, $code);
                $this->data->transType = 'deposit';
                $this->loadView('cashier/deposit', 'Add Funds');
            }
        }
    }

    public function purchase($salt) {

        $userId             = $this->profile->id;
        $orders = $this->Account->getOrders($userId, 'Pending');
        $this->data->code   = $orders[0]->method; // should only ever be one order.
        $this->data->order = $orders[0];

        $this->load->model('user_model', 'User');

        $this->data->depositAccount                   = $this->PaymentMethod->getAccountDetails($this->data->code);

        if (!$this->data->depositAccount->enabled) {
            $this->session->set_flashdata('error', 'We are sorry but purchases with <strong>'.$this->data->depositAccount->name.'</strong> are currently disabled.');
            redirect('back_office/account.html');
        }

        if ($this->data->depositAccount->account) {

            if ($_POST) {
                $this->data->depositData = array(
                    'user_id'      => $userId,
                    'method'       => $this->data->order->method,
                    'account_id'   => $this->data->depositAccount->id,
                    'gross_amount' => $this->data->order->total,
                    'amount' => $this->data->order->total,
                    'identifier'   => $this->Transaction->identifier()
                );

                $this->data->depositDetails = NULL;

                $this->db->trans_begin();

                if ($this->data->transactionId = $this->Transaction->addDeposit($this->data->depositData)) {
                    $this->db->trans_commit();

                    // Little hack to update the missing details with the first ever deposit
                    $data = array(
                        'success' => 'success',
                        'html'    => $this->loadPartialView('cashier/deposit_finish')
                    );
                } else {
                    $this->db->trans_rollback();

                    $data = array(
                        'error' => 'An unknown error has occurred'
                    );
                }

                echo json_encode($data);
                return;
            } else {
                show_error('Invalid Entry');
            }
        } else // huho
        {
            $this->session->set_flashdata('error', 'The <strong>'.$this->data->depositAccount->name.'</strong> account has not been set up');
            redirect('back_office.html');
        }
    }

    public function cashout() {
        if (empty($this->profile->secret_question)) {
            $this->session->set_flashdata('error', 'Please set up your secret question and answer before requesting a cashout.');
            redirect('back_office/profile.html');
        } else {
            $this->addStyleSheet(asset('styles/cashier.css'));
            $this->data->balances = $this->PaymentMethod->getCashoutBalancesList($this->userId);

            $this->addJavascript(asset('scripts/replace.js'));
            $this->data->transType = 'cashout';
            $this->loadView('cashier/account_select', 'Cashout Earnings');
        }
    }

    public function cashout_method($code) {
        $userId           = $this->profile->id;
        $this->data->fees = $this->PaymentMethod->getFees('cashout');

        if ($this->Cashier->pendingCashout($this->userId)) {
            $this->session->set_flashdata('error', 'Pending cashout exists. Please wait until you are paid before requesting another.');
            redirect('back_office/account.html');
        }
        if ($_POST) {
            if ($this->form_validation->run('cashout')) {
                $post    = $this->input->post();
                $amount  = floatval($post['amount']);
                $account = $this->PaymentMethod->getAccountDetails($code);
                $userAccount = $this->PaymentMethod->getAccountForUser($this->userId, $code);

                if ($amount > $userAccount->balance) {
                    $data = array(
                        'errorElements' => array('amount' => '*Amount is greater than balance')
                    );
                } elseif ($amount < $account->min_cashout) {
                    $data = array(
                        'errorElements' => array('amount' => '*Amount is less than allowed minimum of '.money($account->min_cashout))
                    );
                } elseif ($amount > $account->max_cashout) {
                    $data = array(
                        'errorElements' => array('amount' => '*Amount greater than allowed maximum of '.money($account->max_cashout))
                    );
                } else {

                    $this->data->cashout = array(
                        'user_id'      => $userId,
                        'gross_amount' => $amount,
                        'method'       => $code,
                        'user_account' => $userAccount->account,
                        'identifier'   => $this->Transaction->identifier()
                    );
                    $this->db->trans_begin();

                    if ($transactionId = $this->Transaction->addCashout($this->data->cashout)) {

                        $this->db->trans_commit();
                        $this->Cashier->decreaseUserBalance($this->profile->id, $code, $amount);

                        $this->session->set_flashdata('success', 'Cashout request successfully submitted.');

                        $this->data->cashout['account'] = $account;
                        $this->data->cashout['id']      = $transactionId;

                        $trans = array (
                            'username' => $this->profile->username,
                            'identifier' => $this->data->cashout['identifier'],
                            'method' => $code,
                            'amount' => $amount,
                            'fee' => $this->data->cashout['fee'],
                            'date' => now()
                        );
                        $this->EmailQueue->store($this->profile->email, 'Cashout requested', 'emails/cashier/cashout_requested', compact('trans'));

                        $data                           = array(
                            'success' => 'success',
                            'html'    => $this->loadPartialView('cashier/cashout_finish')
                        );
                    } else {
                        $this->db->trans_rollback();

                        $data = array(
                            'error' => 'An unknown transaction error has occurred. Please submit a support ticket.'
                        );
                    }
                }
            } else {
                $data = array(
                    'errorElements' => $this->form_validation->error_array()
                );
            }

            echo json_encode($data);
        } else {
            $this->addStyleSheet(asset('styles/depositmethods.css'));
            $this->data->account = $this->PaymentMethod->getAccountForUser($this->userId,  $code);
            $this->data->code = $code;
            $this->data->fee = $this->PaymentMethod->getFeeData($code, 'cashout');
            $this->loadView('cashier/cashout', 'Cashout Earnings');
        }
    }

    public function account_details($id) {
        if ($this->ajax) {
            $account = $this->PaymentMethod->getAccountDetailsById($id);
            echo $this->loadPartialView('cashier/details', compact('account'));
        } else {
            show_404();
        }
    }

    public function transfer() {
        if (empty($this->profile->secret_question)) {
            $this->session->set_flashdata('error', 'Please set up your secret question and answer before transferring funds a cashout.');
            redirect('back_office/profile.html');
        } else {
            $this->addStyleSheet(asset('styles/cashier.css'));
            $this->data->balances = $this->PaymentMethod->getCashoutBalancesList($this->userId);

            $this->addJavascript(asset('scripts/replace.js'));
            $this->data->transType = 'transfer';
            $this->loadView('cashier/account_select', 'Transfer Earnings');
        }
    }


    public function transfer_method($code) {
        if ($_POST) {
            if ($this->form_validation->run('transfer')) {
                $post        = $this->input->post();
                $amount      = floatval($post['amount']);
                $ppAccount = $this->PaymentMethod->getAccountForUser($this->userId, $code);
                $ebAccount = $this->PaymentMethod->getAccountForUser($this->userId, 'eb');

                if ($amount > $ebAccount->balance) {
                    $data = array(
                        'errorElements' => array('amount' => '*Amount is greater than balance')
                    );
                }  else {

                    $orderData = array(
                        'user_id'      => $this->userId,
                        'method'       => $code,
                        'gross_amount' => $amount,
                        'amount' => $amount,
                        'type'         => 'transfer',
                        'status'       => 'ok',
                        'identifier'   => $this->Transaction->identifier()
                    );

                    $this->db->trans_begin();

                    if ($transactionId = $this->Transaction->add($orderData)) {

                        $this->db->trans_commit();
                        $this->Cashier->transferBalance($this->userId, 'eb', $code, $amount);

                        $this->session->set_flashdata('success', 'Funds transfer successful.');

                        $trans = array(
                            'username'   => $this->profile->username,
                            'identifier' => $transactionId,
                            'method'     => $code,
                            'amount'     => $amount,
                            'fee'        => 0,
                            'date'       => now()
                        );
                        $this->EmailQueue->store($this->profile->email, 'Balance transferred', 'emails/cashier/balance_transfer', compact('trans'));

                        $data = array(
                            'success' => 'success',
                            'redirect'    => array(
                                'url' => SITE_ADDRESS.'back_office/account.html'
                            )
                        );
                    } else {
                        $this->db->trans_rollback();

                        $data = array(
                            'error' => 'An unknown transaction error has occurred. Please submit a support ticket.'
                        );
                    }
                }
            } else {
                $data = array(
                    'errorElements' => $this->form_validation->error_array()
                );
            }

            echo json_encode($data);
        } else {
            $this->addStyleSheet(asset('styles/depositmethods.css'));
            $this->data->account = $this->PaymentMethod->getAccountForUser($this->userId, 'eb');
            $this->data->code    = $code;
            $this->loadView('cashier/transfer', 'Transfer Earnings');
        }
    }

    /**
     * Used to return a valid date of birth from Database. It needs to construct the
     * rule for year, month and day
     *
     * @return boolean
     */
    function valid_dob() {
        $dob = $this->input->post('year').'-'.$this->input->post('month').'-'.$this->input->post('day');
        if (strtotime($dob) != $this->profile->date_of_birth) {
            return FALSE;
        }
        return TRUE;
    }

    public function bc_account_check($param) {
        if ($this->PaymentMethod->checkAccountExists('bc', $param)) {
            $this->form_validation->set_message('bc_account_check', '* already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function st_account_check($param) {
        if ($this->PaymentMethod->checkAccountExists('st', $param)) {
            $this->form_validation->set_message('st_account_check', '* already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function pz_account_check($param) {
        if ($this->PaymentMethod->checkAccountExists('pz', $param)) {
            $this->form_validation->set_message('pz_account_check', '* already in use');
            return FALSE;
        }
        return TRUE;
    }

    public function pm_account_check($param) {
        if (!preg_match('/^u\d{2,8}$/i', $param)) {
            $this->form_validation->set_message('pm_account_check', '* incorrect - use Uxxxxxx');
            return FALSE;
        }

        if ($this->PaymentMethod->checkAccountExists('pm', $this->profile->id, $param)) {
            $this->form_validation->set_message('pm_account_check', '* already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function pp_account_check($param) {
        if ($this->PaymentMethod->checkAccountExists('pp', $param)) {
            $this->form_validation->set_message('pp_account_check', '* incorrect - already in use');
            return FALSE;
        }

        return TRUE;
    }

    function valid_earnings($param) {
        $userId   = $this->profile->id;
        $earnings = $this->PaymentMethod->getBalance($userId, 'eb');
        if ((float)$param < 0.00001 || (float)$param > (float)$earnings) {
            $this->form_validation->set_message('valid_earnings', '* incorrect');
            return FALSE;
        }
        return TRUE;
    }

    public function refresh_traffic_value() {
        if (!$this->isGuest && $this->ajax) {
            $this->load->model('user_model', 'User');
            $trafficValue = $this->User->refreshNetValue($this->profile->id);

            echo money($trafficValue);

            return;
        }

        show_404();
    }
}