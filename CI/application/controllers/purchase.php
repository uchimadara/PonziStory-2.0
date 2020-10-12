<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Purchase extends MY_Controller {


    public function __construct() {
        parent::__construct();

        if ($this->isGuest) {
            $this->session->set_flashdata('error', 'You must be logged in to view that page.');
            if ($this->ajax) {
                echo 'You must be logged in to view this.';
                return;
            } else {
                redirect(SITE_ADDRESS.'login.html/?redirect='.$this->uri->uri_string());
            }
        }

        $this->load->model('payment_method_model', 'PaymentMethod');
        $this->load->model('cashier_model', 'Cashier');
        $this->load->model('my_account_model', 'Account');
        $this->load->model('campaign_model', 'Campaign');

        $this->data->menu = $this->loadPartialView('partial/menu');
        $this->layout        = 'layout/member/shell';

        $orders = $this->Account->getOrders($this->profile->id, 'Pending');

        if (!$this->ajax) {
            if (!empty($orders)) {
                $this->session->set_flashdata('warning', 'Pending order exists. Please confirm or cancel.');
                $salt = random_string();
                $this->session->set_flashdata('salt', $salt);

                redirect('confirm_order/'.$salt);
            }
        }
    }

    public function index() {
        $this->bootstrap();
    }

    /**********
     * @name bootstrap
     * @param string $page
     *
     * @puropse main entry point for back office pages
     */
    public function bootstrap($page = 'credits', $param = NULL) {

        $orders = $this->Account->getOrders($this->profile->id, 'Pending');

        if (!empty($orders)) {
            $this->session->set_flashdata('warning', 'Pending order exists. Please confirm or cancel.');
            $salt = random_string();
            $this->session->set_flashdata('salt', $salt);

            redirect('confirm_order/'.$salt);
        }

        if (!method_exists($this, $page)) {
            $page = 'dashboard';
        }

        $html = $this->$page($param);

        if ($this->ajax) {
            $state = array(
                'html'      => $html,
                'pageTitle' => $this->data->page_title,
                'url'       => SITE_ADDRESS.'back_office/'.$page.'.html'
            );
            echo json_encode($state);
            return;
        }

        $this->data->widgets[] = & $html;
        //$this->layout        = 'shell';
        $this->loadView('partial/widgets',' Back Office');
    }

    public function account_upgrade() {

        $this->addStyleSheet(asset('styles/upgrade.css'));
        $this->addJavascript(asset('scripts/upgrade.js'));

        $this->data->memberships = $this->Account->getMemberships();

        if (now() > strtotime('2014-12-31 23:59:59')) {
            unset($this->data->memberships['lifetime']);
        }

        return $this->loadPartialView('account/upgrade');
    }

    public function confirm_upgrade() {
        if (!$_POST || !$this->ajax) show_error('Invalid entry');

        $post = $this->input->post();

        if (!isset($post['agree']) || $post['agree'] != '1') {
            $result = array('error' => "Please read and agree to our Terms of Service.");
        } elseif ($post['qty'] == '0') {

            $result = array('error' => "Quantity not selected.");
        } elseif ($post['upgrade_type'] == 'lifetime' && now() > strtotime('2014-12-31 23:59:59')) {
            $result = array('error' => "Lifetime offer is expired.");
        } elseif ($post['upgrade_type'] == 'lifetime' && $post['qty'] != '1') {
            $result = array('error' => "Only 1 Lifetime Membership allowed.");
        } else {

            $this->load->model('cashier_model', 'Cashier');

            $item = $this->Account->getMembership($post['upgrade_type']);

            $qty            = intval($post['qty']);
            $total          = $qty*$item->price;
            $discount = NULL;

            // detertmine if discount applies
            if ($this->profile->account_expires > now()
                    && $this->profile->account_level != 'Free'
                    && $post['upgrade_type'] != strtolower($this->profile->account_level)
            ) {

                $current = $this->Account->getMembership($this->profile->account_level);

                if ($post['upgrade_type'] == 'novice' || ($post['upgrade_type'] == 'advanced' && $current->code == 'expert')) {
                    echo json_encode(array('error' => "Please wait until your current membership expires before downgrading."));
                    return;
                } else {
                    if (($timeRemaining = $this->profile->account_expires - now()) > CACHE_ONE_DAY*7) {

                        $timePurchase = $qty*$item->duration*CACHE_ONE_DAY;
                        if ($timePurchase < $timeRemaining) { // See how discount is used in controllers/callback::notify
                            $result = array('error' => "You can not purchase an upgrade for a shorter period than you have remaining on your existing membership.");
                        } else {

                            $days     = floor($timeRemaining/CACHE_ONE_DAY);
                            $perDiem  = floatval($current->price)/intval($current->duration);
                            $discount = roundDown($days*$perDiem, 2);

                            if ($discount > $total) {
                                $result = array('error' => "Discount of ".money($this->data->order['discount'])." can not exceed total purchase price.");
                            } else {
                                $total -= $discount;
                            }
                        }
                    }
                }
            }

            $order = array(
                'user_id'          => $this->profile->id,
                'amount'           => $item->price,
                'qty'              => $qty,
                'method' => $post['method'],
                'purchase_item_id' => $item->id,
                'description' => $qty.($item->code != 'lifetime' ? pluralise(' month', $qty) : '').' '.$item->title,
                'total'            => $total,
                'discount' => $discount
            );

            $this->createOrder($order);

            $salt = random_string();
            $this->session->set_flashdata('salt', $salt);

            $result = array(
                'success'  => 'Ok',
                'redirect' => array(
                    'url'  => SITE_ADDRESS.'confirm_order/'.$salt,
                    'hash' => ''
                )
            );
        }
        echo json_encode($result);
    }

    public function credits() {
            $this->data->packages     = $this->Account->getCreditPackages();
            $this->data->surfSettings = $this->Account->getSurfSettings();

            $this->data->page_title = 'Purchase Ad Credits';
            return $this->loadPartialView('purchase/credits');
    }

    public function loan($loanId) {
        $this->load->model('loan_model', 'Loan');
        $loan = $this->Loan->getData( $loanId );
        $this->data->loan = $loan;

        $this->data->packages     = $this->Account->getCreditPackages();

        $this->data->page_title = 'Purchase Loan Funds';
        return $this->loadPartialView('purchase/loan');
    }

    public function confirm_loan($loanId) {
        if (!$_POST || !$this->ajax) show_error('Invalid entry');

        $post = $this->input->post();

        $this->load->model('loan_model', 'Loan');
        $loan = $this->Loan->getData( $loanId );

        $lent = $this->Loan->getTotalInvested($loanId, TRUE, $this->userId);

        if (!isset($post['agree']) || $post['agree'] != '1') {
            $result = array('error' => "Please read and agree to our Terms of Service.");
        } elseif (empty($post['amount'])) {
            $result = array('error' => "No amount entered.");
        } elseif (!is_numeric($post['amount'])) {
            $result = array('error' => "Invalid amount.");
        } elseif (is_integer($post['amount']) && $post['amount'] > 0) {
            $result = array('error' => "Invalid amount.");
        } elseif ($post['amount'] < $loan->min_investment) {
            $result = array('error' => "Min investment: ".money($loan->min_investment));
        } elseif (($post['amount'] + $lent) > $loan->max_investment) {
            $result = array('error' => "Max total investment: ".money($loan->max_investment));
        } elseif ($post['amount'] > ($loan->amount - $loan->total_invested)) {
            $result = array('error' => "To complete: ".money($loan->amount - $loan->total_invested));

        } else {

            $item = $this->Account->getPurchaseItem('loan_funds');

            $amount            = intval($post['amount']);

            $order = array(
                'user_id'          => $this->profile->id,
                'amount'           => $amount,
                'qty'              => 1,
                'method' => $post['method'],
                'purchase_item_id' => $item->id,
                'reference_id'     => $loanId,
                'description'      => $item->title.' purchase, Loan #'.$loanId.' $'.$amount.'',
                'total'            => $amount,
            );

            $this->createOrder($order);

            $salt = random_string();
            $this->session->set_flashdata('salt', $salt);

            $result = array(
                'success'  => 'Ok',
                'redirect' => array(
                    'url'  => site_url('confirm_order/'.$salt.'/'.$loan->id),
                    'hash' => ''
                )
            );
        }
        echo json_encode($result);die;
    }

    public function confirm_credits() {
        if (!$_POST || !$this->ajax) show_error('Invalid entry');

        $post = $this->input->post();

        if (!isset($post['agree']) || $post['agree'] != '1') {
            $result = array('error' => "Please read and agree to our Terms of Service.");
        } elseif (empty($post['qty'])) {
            $result = array('error' => "No quantity entered.");
        } elseif (!is_numeric($post['qty'])) {
            $result = array('error' => "Invalid quantity.");
        } elseif ($post['qty'] % 1 > 0) {
            $result = array('error' => "Invalid quantity.");
        } else {

            $item = $this->Account->getPurchaseItem($post['credit_pkg']);

            $qty            = intval($post['qty']);
            $total          = $qty*$item->price;
            $order = array(
                'user_id'          => $this->profile->id,
                'amount'           => $item->price,
                'qty'              => $qty,
                'method' => $post['method'],
                'purchase_item_id' => $item->id,
                'description'      => $qty.' '.$item->title,
                'total'            => $total,
            );

            $this->createOrder($order);

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
        echo json_encode($result);
    }

    public function sc_banners($id) {

        if ($_POST) {
            $post = $this->input->post();
            if (!isset($post['agree']) || $post['agree'] != '1') {
                $result = array('error' => "Please read and agree to our Terms of Service.");
            } elseif (empty($post['fromDate'])) {
                $result = array('error' => "First day not entered.");
            } elseif (empty($post['qty']) || $post['qty'] <= 0) {
                $result = array('error' => "Number of days not selected.");
            } elseif (empty($post['dates'])) {
                $result = array('error' => "No dates.");
            } else {

                $item = $this->Account->getPurchaseItem($post['credit_pkg']);
                $qty = intval($post['qty']);

                $order = array(
                    'user_id'          => $this->profile->id,
                    'amount'           => $item->price,
                    'qty'              => $qty,
                    'method' => $post['method'],
                    'purchase_item_id' => $item->id,
                    'description'      => $qty.' '.$item->title,
                    'total'            => $qty*$item->price,
                );

                $orderId = $this->createOrder($order);

                $slot = array(
                    'order_id'    => $orderId,
                    'campaign_id' => $id,
                    'dates'       => $post['dates']
                );

                $this->Campaign->bookSlots($slot);
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

            echo json_encode($result);

        } else {

            $this->data->campaign = $this->Campaign->getAd($id, 'campaign');
            if ($this->data->campaign->status == 'pending') {

                $this->session->set_flashdata("warning", "Please wait until campaign is approved before purchasing.");
                redirect("back_office/ads.html#scAds");
                return;
            }
            $this->data->packages = $this->Account->getPackages('slots');

            $dates = array();
            foreach ($this->Campaign->getFullSlots() as $d) {
                $dates[] = date(DEFAULT_DATE_FORMAT, $d->time_slot);
            }
            $this->data->fullSlots = json_encode($dates);

            $this->addJavascript(asset('scripts/ads.js'));
            $this->data->page_title    = 'Purchase SC Banner Slots';
            echo $this->loadView('purchase/slots');
        }
    }

    public function login_ads($id) {

        $datePickStart = date(MYSQL_DATE_FORMAT, now() + CACHE_ONE_DAY + (15*60));

        if ($_POST) {
            $post = $this->input->post();
            if (!isset($post['agree']) || $post['agree'] != '1') {
                $result = array('error' => "Please read and agree to our Terms of Service.");
            } elseif ($this->form_validation->run('purchase/login_ads') === FALSE) {
                $result['errorElements'] = $this->form_validation->error_array();
            } else {
                $package = $this->Account->getPurchaseItem('login_ad_bid');
                $day = strtotime($this->input->post('fromDate'));
                $minAmount = ($this->input->post('bid')) ? $this->input->post('bid') : $package->price;
                if ($day < strtotime($datePickStart)) {
                    $result['error'] = 'First day is to early.';
                } elseif ($minAmount < $package->price) {
                    $result['error'] = "Custom bid too low.";
                } else {

                    $count   = intval($this->input->post('days'));
                    $slots   = array();
                    $price   = 0;

                    for ($i = 1; $i <= $count; $i++) {
                        $d = $this->Campaign->getLoginAd($day);

                        $slot = array(
                            'time_slot'   => $day,
                            'status'      => 'pending',
                            'login_ad_id' => $id
                        );

                        if ($d == NULL) {
                            $slot['amount'] = $minAmount;
                        } else {
                            $slot['amount'] = max($minAmount, (string)($d->amount + LOGIN_ADS_BID));
                        }

                        $price += $slot['amount'];
                        $day += CACHE_ONE_DAY;
                        $slots[] = $slot;
                    }

                    $order = array(
                        'user_id'          => $this->profile->id,
                        'amount'           => $price,
                        'qty'              => 1,
                        'method'           => $post['method'],
                        'purchase_item_id' => $package->id,
                        'description'      => $count.' Login Ad Days',
                        'total'            => $price,
                    );

                    $orderId = $this->createOrder($order);

                    foreach ($slots as $s) {
                        $s['purchase_id'] = $orderId;
                        $this->Campaign->add('login_ad', $s);
                    }

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
            }

            echo json_encode($result);

        } else {

            $this->data->campaign = $this->Campaign->getAd($id, 'login_ad');
            if ($this->data->campaign->status == 'pending') {

                $this->session->set_flashdata("warning", "Please wait until campaign is approved before purchasing.");
                redirect("back_office/ads.html#loginAds");
                return;
            }

            $js       = <<<JS
var datePickStart = "{$datePickStart}";
JS;
            $this->addJavascript($js);
            $this->addJavascript(asset('scripts/calc_prices.js'));

            $this->data->campaign  = $this->Campaign->getAd( $id,'login_ad');
            $this->data->package =$this->Account->getPurchaseItem('login_ad_bid');
            $this->data->page_title = 'Purchase Login Ads';
            echo $this->loadView('purchase/login_ads');
        }

    }

    public function leaderboard_ads($id) {

        $datePickStart = date(MYSQL_DATE_FORMAT, now() + CACHE_ONE_DAY + (15*60));

        if ($_POST) {
            $post = $this->input->post();
            if (!isset($post['agree']) || $post['agree'] != '1') {
                $result = array('error' => "Please read and agree to our Terms of Service.");
            } elseif ($this->form_validation->run('purchase/leaderboard_ads') === FALSE) {
                $result['errorElements'] = $this->form_validation->error_array();
            } else {
                $package = $this->Account->getPurchaseItem('leaderboard_bid');
                $day = strtotime($this->input->post('fromDate'));
                $minAmount = ($this->input->post('bid')) ? $this->input->post('bid') : $package->price;
                if ($day < strtotime($datePickStart)) {
                    $result['error'] = 'First day is to early.';
                } elseif ($minAmount < $package->price) {
                    $result['error'] = "Custom bid too low.";
                } else {

                    $count   = intval($this->input->post('days'));
                    $slots   = array();
                    $price   = 0;

                    for ($i = 1; $i <= $count; $i++) {
                        $d = $this->Campaign->getLeaderboardAd($day);

                        $slot = array(
                            'time_slot'   => $day,
                            'status'      => 'pending',
                            'leaderboard_id' => $id
                        );

                        if ($d == NULL) {
                            $slot['amount'] = $minAmount;
                        } else {
                            $slot['amount'] = max($minAmount, (string)($d->amount + LEADERBOARD_BID));
                        }

                        $price += $slot['amount'];
                        $day += CACHE_ONE_DAY;
                        $slots[] = $slot;
                    }

                    $order = array(
                        'user_id'          => $this->profile->id,
                        'amount'           => $price,
                        'qty'              => 1,
                        'method'           => $post['method'],
                        'purchase_item_id' => $package->id,
                        'description'      => $count.' Leaderboard Ad Days',
                        'total'            => $price,
                    );

                    $orderId = $this->createOrder($order);

                    foreach ($slots as $s) {
                        $s['purchase_id'] = $orderId;
                        $this->Campaign->add('leaderboard', $s);
                    }

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
            }

            echo json_encode($result);

        } else {

            $this->data->campaign = $this->Campaign->getAd($id, 'leaderboard');
            if ($this->data->campaign->status == 'pending') {

                $this->session->set_flashdata("warning", "Please wait until campaign is approved before purchasing.");
                redirect("back_office/ads.html#leaderboardAds");
                return;
            }

            $js       = <<<JS
var datePickStart = "{$datePickStart}";
JS;
            $this->addJavascript($js);
            $this->addJavascript(asset('scripts/calc_prices.js'));

            $this->data->campaign  = $this->Campaign->getAd( $id,'leaderboard');
            $this->data->package =$this->Account->getPurchaseItem('leaderboard_bid');
            $this->data->page_title = 'Purchase Leaderboard Ads';
            echo $this->loadView('purchase/leaderboard_ads');
        }

    }

    public function reflink($id = FALSE) {

        if ($id === FALSE) {
            if (($id = $this->input->get('site_id')) === FALSE) {
                $this->session->set_flashdata('error', 'No monitor program specified.');
                redirect('back_office/ads.html#reflinkAds');
            }
        }

        $this->data->site = $this->Monitor->getMonitorSite($id);

        if (empty($this->data->site)) {
            $this->session->set_flashdata('error', 'No program specified for reflink purchase.');
            redirect('back_office/ads.html');
        }

        $datePickStart = date(MYSQL_DATE_FORMAT, now() + CACHE_ONE_DAY + (15*60));

        if ($_POST) {
            $post = $this->input->post();
            if (!isset($post['agree']) || $post['agree'] != '1') {
                $result = array('error' => "Please read and agree to our Terms of Service.");
            } elseif ($this->form_validation->run('purchase/reflink') === FALSE) {
                $result['errorElements'] = $this->form_validation->error_array();
            } else {
                $package   = $this->Account->getPurchaseItem('reflink_bid');
                $day       = strtotime($this->input->post('fromDate'));
                $minAmount = ($this->input->post('bid')) ? $this->input->post('bid') : $package->price;
                if ($day < strtotime($datePickStart)) {
                    $result['error'] = 'First day is to early.';
                } elseif ($minAmount < $package->price) {
                    $result['error'] = "Custom bid too low.";
                } else {

                    $count = intval($this->input->post('days'));
                    $slots = array();
                    $price = 0;

                    for ($i = 1; $i <= $count; $i++) {
                        $d = $this->Campaign->getReflink($id, $day);

                        $slot = array(
                            'time_slot'   => $day,
                            'status'      => 'pending',
                            'monitor_site_id' => $id,
                            'target_url' => $post['reflink']
                        );

                        if ($d == NULL) {
                            $slot['amount'] = $minAmount;
                        } else {
                            $slot['amount'] = max($minAmount, (string)($d->amount + REFLINK_BID));
                        }

                        $price += $slot['amount'];
                        $day += CACHE_ONE_DAY;
                        $slots[] = $slot;
                    }

                    $order = array(
                        'user_id'          => $this->profile->id,
                        'amount'           => $price,
                        'qty'              => 1,
                        'method'           => $post['method'],
                        'purchase_item_id' => $package->id,
                        'description'      => $count.' Ref Link Days for '.$this->data->site->name,
                        'total'            => $price
                    );

                    $orderId = $this->createOrder($order);

                    foreach ($slots as $s) {
                        $s['purchase_id'] = $orderId;
                        $this->Campaign->add('reflink', $s);
                    }

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
            }

            echo json_encode($result);
        } else {


            $js = <<<JS
var datePickStart = "{$datePickStart}";
JS;
            $this->addJavascript($js);
            $this->addJavascript(asset('scripts/calc_prices.js'));
            //$this->addJavascript(asset('scripts/reflink_ads.js'));

            $this->data->reflink = $this->Monitor->getUserReflink($this->userId, $this->data->site->id);
            $this->data->package    = $this->Account->getPurchaseItem('reflink_bid');
            $this->data->page_title = 'Purchase Referral Link';
            echo $this->loadView('purchase/reflink');
        }
    }

    private function createOrder($order) {

        /*
       * applying balance
       */
        $appliedBalance = 0;
        $method         = $order['method'];
        $appliedBalanceMethod = NULL;

        $fees = $this->PaymentMethod->getBillDetails($method);
        $order['fee'] = $this->calcFee($order['total'], $fees['deposit']['fee']);

        if (isset($_POST['apply'])) {
            $balance              = $this->PaymentMethod->getBalance($this->userId, $_POST['apply']);
            $balance = roundDown($balance, 2);
            $appliedBalanceMethod = $_POST['apply'];
            if ($order['total'] > $balance) {

                $order['total'] -= $balance;
                $appliedBalance = $balance;

                $order['fee'] = $this->calcFee($order['total'], $fees['deposit']['fee']);
            } else {
                $order['method']         = $_POST['apply'];
                $appliedBalance = $order['total'];
                $order['fee']            = 0;
            }
        }

        $order['total'] += $order['fee'];

        if ($appliedBalance > 0) {
            $order['apply_balance']        = $appliedBalance;
            $order['apply_balance_method'] = $appliedBalanceMethod;
        }

        $order['created'] = now();

        return $this->Account->addOrder($order);
    }


    private function calcFee($amt, $fee) {

        return roundup((-$amt - $fee->fixed)/(($fee->percent/100) - 1), 2) - $amt;
    }

    public function check_loginads() {
        if (!$this->ajax) {
            show_404();
        } else {
            $data  = array();
            $day   = strtotime($this->input->post('fromDate'));
            $count = intval($this->input->post('days'));
            $package = $this->Account->getPurchaseItem('login_ad_bid');

            $minAmount = ($this->input->post('bid')) ? $this->input->post('bid') : $package->price;
            if ($minAmount < $package->price) {
                $data['error'] = "Custom bid too low.";
            } else {

                for ($i = 1; $i <= $count; $i++) {
                    $d = $this->Campaign->getLoginAd($day);
                    if ($d == NULL) {
                        $data[date(DEFAULT_DATE_FORMAT, $day)] = $minAmount;
                    } else {
                        $data[date(DEFAULT_DATE_FORMAT, $day)] = max($minAmount, (string)($d->amount + LOGIN_ADS_BID));
                    }
                    $day += CACHE_ONE_DAY;
                }
            }
            echo json_encode($data);
            return;
        }
    }

    public function check_leaderboardads() {
        if (!$this->ajax) {
            show_404();
        } else {
            $data  = array();
            $day   = strtotime($this->input->post('fromDate'));
            $count = intval($this->input->post('days'));
            $package = $this->Account->getPurchaseItem('leaderboard_bid');

            $minAmount = ($this->input->post('bid')) ? $this->input->post('bid') : $package->price;
            if ($minAmount < $package->price) {
                $data['error'] = "Custom bid too low.";
            } else {

                for ($i = 1; $i <= $count; $i++) {
                    $d = $this->Campaign->getLeaderboardAd($day);
                    if ($d) {
                        $data[date(DEFAULT_DATE_FORMAT, $day)] = max($minAmount, (string)($d->amount + LEADERBOARD_BID));
                    } else {
                        $data[date(DEFAULT_DATE_FORMAT, $day)] = $minAmount;
                    }
                    $day += CACHE_ONE_DAY;
                }
            }
            echo json_encode($data);
            return;
        }
    }

    public function check_reflink($siteId) {
        if (!$this->ajax) {
            show_404();
        } else {
            $data    = array();
            $day     = strtotime($this->input->post('fromDate'));
            $count   = intval($this->input->post('days'));
            $package = $this->Account->getPurchaseItem('reflink_bid');

            $minAmount = ($this->input->post('bid')) ? $this->input->post('bid') : $package->price;
            if ($minAmount < $package->price) {
                $data['error'] = "Custom bid too low.";
            } else {

                for ($i = 1; $i <= $count; $i++) {
                    $d = $this->Campaign->getReflink($siteId, $day);
                    if ($d == NULL) {
                        $data[date(DEFAULT_DATE_FORMAT, $day)] = $minAmount;
                    } else {
                        $data[date(DEFAULT_DATE_FORMAT, $day)] = max($minAmount, (string)($d->amount + REFLINK_BID));
                    }
                    $day += CACHE_ONE_DAY;
                }
            }
            echo json_encode($data);
            return;
        }
    }

    public function check_slot() {

        if (!$this->ajax) {
            show_404();
        } else {
            $data            = array();
            $data['days']    = $data['skip'] = 0;
            $data['skipped'] = '';

            $d   = strtotime($this->input->post('fromDate'));
            $qty = intval($this->input->post('user_qty'));

            while ($data['days'] < $qty) {
                if ($this->Campaign->checkSlotAvailable($d)) {
                    $data['date'][] = date(DEFAULT_DATE_FORMAT, $d);
                    $data['days']++;
                } else {
                    if ($data['skip']) $data['skip_date'] .= ', ';
                    $data['skip_date'] .= date(DEFAULT_DATE_FORMAT, $d);
                    $data['skip']++;
                }
                $d += CACHE_ONE_DAY;
            }
            if ($data['skip']) {
                $data['msg'] = $data['skip'].' dates already full. Your banner will not run on: '.$data['skip_date'];
            } else {
                $data['msg'] = $data['days'].' days available.';
            }
            echo json_encode($data);
            return;
        }
    }
}
