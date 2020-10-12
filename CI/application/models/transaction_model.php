<?php
class Transaction_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Gets history summary of deposits cashouts
     *
     * @param string $data Array of payment method's code
     * @return array $result Array with all history summary data
     *
     * @author Adrian
     */
    public function history($data) {
        $today = mktime(0, 0, 0, date("n", $this->now), date("j", $this->now), date("Y", $this->now));

        $result = array();
        foreach ($data as $d) {
            // Total Deposit
            $result[$d]['deposit_ok_count']       = $this->countTransactions($d, 'deposit');
            $result[$d]['deposit_ok_count_today'] = $this->countTransactions($d, 'deposit', NULL, $today);
            $result[$d]['deposit_ok_amount']      = $this->getTransactionsAmount($d, 'deposit', 'ok');

            // Total Cashout
            $result[$d]['cashout_ok_count']       = $this->countTransactions($d, 'cashout');
            $result[$d]['cashout_ok_count_today'] = $this->countTransactions($d, 'cashout', NULL, $today);
            $result[$d]['cashout_ok_amount']      = $this->getTransactionsAmount($d, 'cashout', 'ok');

            // Deposit Pending
            $result[$d]['deposit_pending_count']       = $this->countTransactions($d, 'deposit', 'pending');
            $result[$d]['deposit_pending_count_today'] = $this->countTransactions($d, 'deposit', 'pending', NULL, $today);
            $result[$d]['deposit_pending_amount']      = $this->getTransactionsAmount($d, 'deposit', 'pending');

            // Cashout Pending
            $result[$d]['cashout_pending_count']       = $this->countTransactions($d, 'cashout', 'pending');
            $result[$d]['cashout_pending_count_today'] = $this->countTransactions($d, 'cashout', 'pending', NULL, $today);
            $result[$d]['cashout_pending_amount']      = $this->getTransactionsAmount($d, 'cashout', 'pending');
        }

        return $result;
    }

    public function getTotalUserCashouts($userId) {
        return $this->db->select_sum('gross_amount')
                ->from('transaction t')
                ->where('t.user_id', $userId)
                ->where('t.status', 'ok')
                ->where('t.type', 'cashout')
                ->get()
                ->row()
                ->gross_amount;
    }

    public function getSum($type = 'all', $userId = '') {

        if ($type != 'all') {
            if (is_array($type)) {
                $this->db->where_in('type', $type);
            } else {
                $this->db->where('type', $type);
            }
        }
        if ($userId != '') $this->db->where('user_id', $userId);

        return $this->db->select_sum('gross_amount')
                ->from('transaction t')
                ->where('t.status', 'ok')
                ->get()
                ->row()
                ->gross_amount;
    }

    public function sumUsers($type, $page = '1', $perpage = '20', $begin = '', $end = '') {

        $start = ($page - 1)*$perpage;
        if ($begin) $this->db->where('created >=', $begin);
        if ($end) $this->db->where('created <=', $end);

        return $this->db->select('username, user_id, account_level, sum(gross_amount) total')
                ->from('transaction t')
                ->join('users', 'users.id=t.user_id')
                ->where('users.active', 1)
                ->where('type', $type)
                ->where('t.status', 'ok')
                ->group_by('user_id')
                ->order_by('total', 'desc')
                ->limit($perpage, $start)
                ->get()
                ->result();
    }

    public function getSummary($begin = NULL, $end = NULL) {

        $ret = array('cycled'       => 0,
                     'cleared'      => 0,);

        if ($begin) $this->db->where('created >=', $begin);
        if ($end) $this->db->where('created <=', $end);
        $result = $this->db->select('type, sum(gross_amount) amount, count(*) c', FALSE)
                ->from('transaction t')
                ->where('t.status', 'ok')
                ->where('t.type !=', 'deposit')
                ->group_by('t.type')
                ->get()
                ->result();

        foreach ($result as $row) $ret[$row->type] = array('amount' => $row->amount, 'count' => $row->c);

        if ($begin) $this->db->where('created >=', $begin);
        if ($end) $this->db->where('created <=', $end);
        $result = $this->db->select('item_code, sum(gross_amount) amount, count(*) c', FALSE)
                ->from('transaction t')
                ->where('t.status', 'ok')
                ->where('t.type', 'deposit')
                ->group_by('t.item_code')
                ->order_by('t.item_code')
                ->get()
                ->result();

        foreach ($result as $row) {
            $ret[$row->item_code] = array('amount' => $row->amount, 'count' => $row->c);
        }


        return $ret;
    }

    public function sumOverhead($begin = NULL, $end = NULL) {

        if ($begin) $this->db->where('created >=', $begin);
        if ($end) $this->db->where('created <=', $end);
        $result = $this->db->select('sum(t.cost) costs, sum(p.fee) fees', FALSE)
                ->from('transaction t')
                ->join('purchase_order p', 'p.transaction_id = t.id')
                ->where('t.status', 'ok')
                ->get()
                ->row();

        return $result;
    }

    public function getTotalEarned($userId = '') {

        if ($userId != 'all') $this->db->where('user_id', $userId);

        return $this->db->select_sum('gross_amount')
                ->from('transaction t')
                ->where("type IN ('ref_comm', 'dividend', 'rcb')", NULL, FALSE)
                ->where('t.status', 'ok')
                ->get()
                ->row()
                ->gross_amount;
    }

    public function getTotalUserDeposits($userId) {
        return $this->db->select_sum('gross_amount')
                ->from('transaction t')
                ->where('t.user_id', $userId)
                ->where('t.status', 'ok')
                ->where('t.type', 'deposit')
                ->get()
                ->row()
                ->gross_amount;
    }

    public function getById($id) {
        return $this->db->select('t.*')
                ->from('transaction t')
                ->where('t.id', $id)
                ->get()
                ->row();
    }

    public function cancel($id) {
        $this->db->set('status', 'cancelled');

        return $this->__update($id);
    }

    public function reject($id) {
        $this->db->set('status', 'rejected');

        return $this->__update($id);
    }

    public function reset($id) {
        $this->db->set('status', 'pending');

        return $this->__update($id);
    }

    private function __update($id) {
        $this->db->set('updated', $this->now)
                ->where('id', $id)
                ->update('transaction');

        return $this->db->affected_rows() > 0;
    }

    public function add($orderData) {
        // Here we calculate the net amount + the fee and cost
        //$grossAmount = $orderData['gross_amount'];

        $data = array(
            'amount'  => $orderData['gross_amount'],
            'fee'     => 0,
            'created' => $this->now,
            // 'user_account'   => $this->PaymentMethod->getAccountForUser($orderData['user_id'], $orderData['method'])->account,
            // 'system_account' => $this->PaymentMethod->getAccountDetailsById($orderData['account_id'])->details
        );

        // If the cost has not been specified, calculate it
//        if (!isset ($depositData['cost']))
//            $data['cost'] = roundUp($this->PaymentMethod->calculateGross($grossAmount, $depositData['method'], 'cost', 'deposit'));

        // Get all the data into one array
        $data = array_merge($orderData, $data);

        // Insert the new Transaction
        if (!$this->db->insert('transaction', $data))
            return FALSE;

        $depositId = $this->db->insert_id();

        return $depositId;
    }

    public function addDeposit($depositData) {
        // Here we calculate the net amount + the fee and cost
        $grossAmount = $depositData['gross_amount'];
        $fee         = roundUp($this->PaymentMethod->calculateGross($grossAmount, $depositData['method'], 'fee', 'deposit'));
        $netAmount   = $grossAmount - $fee;

        $data = array(
            'type'           => 'deposit',
            'amount'         => $netAmount,
            'fee'            => $fee,
            'created'        => $this->now,
            'updated'        => $this->now,
            'user_account'   => $this->PaymentMethod->getAccountForUser($depositData['user_id'], $depositData['method'])->account,
            'system_account' => $this->PaymentMethod->getAccountDetailsById($depositData['account_id'])->details
        );

        // If the cost has not been specified, calculate it
        if (!isset ($depositData['cost']))
            $data['cost'] = roundUp($this->PaymentMethod->calculateGross($grossAmount, $depositData['method'], 'cost', 'deposit'));

        // Get all the data into one array
        $data = array_merge($depositData, $data);

        // Insert the new Transaction
        if (!$this->db->insert('transaction', $data))
            return FALSE;

        $depositId = $this->db->insert_id();

        return $depositId;
    }

    public function addCashout(&$cashoutData) {
        $method = $cashoutData['method'];

        // Here we calculate the net amount + the fee and cost
        $grossAmount = $cashoutData['gross_amount'];
        $fee         = roundUp($this->PaymentMethod->calculateGross($grossAmount, $method, 'fee', 'cashout'));
        $cost        = roundUp($this->PaymentMethod->calculateGross($grossAmount, $method, 'cost', 'cashout'));
        $netAmount   = $grossAmount - $fee;

        $data = array(
            'type'    => 'cashout',
            'amount'  => $netAmount,
            'fee'     => $fee,
            'cost'    => $cost,
            'created' => $this->now,
        );

        $cashoutData = array_merge($cashoutData, $data);

        // We now need to save the cashout and deduct the amount of money from the user's balance
        if (!$this->db->insert('transaction', $cashoutData))
            return FALSE;

        $cashoutId = $this->db->insert_id();

//        // Update the user's balance (passing it a negative amount will deduct rather than add)
//        if (!$this->__updateUserMethodBalance($userId, $method, $grossAmount, FALSE))
//            return FALSE;
//
//        // Update the user's master balance
//        if (!$this->__updateUserMasterBalance($userId, $grossAmount))
//            return FALSE;
//
//        // Add to the History trail
//        $balance      = $this->PaymentMethod->getBalance($userId, $method);
//        $totalBalance = $this->ion_auth->user($userId)->row()->balance;
//
//        $historyData = array(
//            'foreign_key' => $cashoutId,
//            'user_id'     => $userId,
//            'status'      => 'pending',
//            'amount'      => $grossAmount,
//            'balance'     => $balance,
//            'total'       => $totalBalance,
//            'date'        => $this->now
//        );
//
//        if (!$this->History->add('transaction_history', $historyData))
//            return FALSE;

        return $cashoutId;
    }

    public function update($id, $operation, $extraData = NULL) {
        $details = $this->getById($id);
        if (!$details)
            return FALSE;

        if ($details->type == 'deposit')
            return $this->__updateDeposit($details, $operation, $extraData);

        if ($details->type == 'cashout')
            return $this->__updateCashout($details, $operation, $extraData);

        // We should never ever EVER get here - but experience suggests that the weirdest things can happen ¬.¬
        return FALSE;
    }

    private function __updateDeposit($depositData, $operation, $extraData = NULL) {
        $depositId = $depositData->id;

        switch ($operation) {
            // Admin rejected the deposit for whatever reason
            case 'reject':
                return $this->reject($depositId);

            // the user cancels his deposit
            case 'cancel':
                return $this->cancel($depositId);

            case 'pending':
                return $this->reset($depositId);

            // Need to mark the deposit as received and update the balances
            case 'ok':
                // update the transaction accounts
                $data = array(
                    'status'  => 'ok',
                    'updated' => $this->now,
                );

                // If anything has been passed to the function then we need to add it to the
                // list of data to be updated (eg reference of the deposit)
                if ($extraData) {
                    $data = array_merge($data, $extraData);
                }

                $this->db->where('id', $depositId)
                        ->update('transaction', $data);

                if ($this->db->affected_rows() == 0)
                    return FALSE;

                return TRUE;
        }

        // if all else fails, return false
        return FALSE;
    }

    public function updateCashout($id, $data) {
        $data['updated'] = $this->now;
        $this->db->where('id', $id)
                ->update('transaction', $data);

        if ($this->db->affected_rows() == 0)
            return FALSE;

        if ($data['status'] != 'ok') {

            // Return the balance to user's account

            $this->db->set('balance', 'balance + '.abs($data['gross_amount']), FALSE)
                    ->where('user_id', $data['user_id'])
                    ->where('payment_code', $data['method'])
                    ->update('user_payment_method');

            $this->db->set('balance', 'balance + '.abs($data['gross_amount']), FALSE)
                    ->where('id', $data['user_id'])
                    ->update('users');
        }

        return TRUE;
    }

    private function __updateCashout($cashoutData, $operation, $extraData = NULL) {
        $userId    = $cashoutData->user_id;
        $method    = $cashoutData->method;
        $cashoutId = $cashoutData->id;

        switch ($operation) {
            // Need to mark the cashout as sent
            case 'ok':
                $data = array(
                    'status'  => 'ok',
                    'updated' => $this->now
                );

                // If anything has been passed to the function then we need to add it to the
                // list of data to be updated (eg reference of the deposit)
                if ($extraData)
                    $data = array_merge($data, $extraData);

                $this->db->where('id', $cashoutId)
                        ->update('transaction', $data);

                if ($this->db->affected_rows() == 0)
                    return FALSE;

                break;

            default:
                // *** Ugly but best way of doing it with more reuse of code
                if ($operation == 'reject') {
                    if (!$this->reject($cashoutId))
                        return FALSE;
                } else {
                    if (!$this->cancel($cashoutId))
                        return FALSE;
                }
                // Update the user's master balance
                if (!$this->__updateUserMasterBalance($userId, $cashoutData->gross_amount))
                    return FALSE;
        }
        return TRUE;
    }

    private function __updateUserMethodBalance($userId, $method, $amount, $lock = FALSE) {
        if ($lock)
            $this->db->set('locked', 1);

        // Be extra careful when negative amount is passed - we don't want the balance to go negative
        if ($amount < 0)
            $this->db->where('balance >= ', abs($amount));

        $this->db->set('balance', 'balance + '.$amount, FALSE)
                ->where('user_id', $userId)
                ->where('payment_code', $method)
                ->update('user_payment_method');

        return $this->db->affected_rows() > 0;
    }

    private function __updateUserMasterBalance($userId, $amount) {
        // Be extra careful when negative amount is passed - we don't want the balance to go negative
        if ($amount < 0)
            $this->db->where('balance >= ', abs($amount));

        // Update General Balance
        $this->db->set('balance', 'balance + '.$amount, FALSE)
                ->where('id', $userId)
                ->update('users');

        if ($this->db->affected_rows() == 0)
            return FALSE;

        return TRUE;
    }

    public function getByIdentifier($identifier) {
        // This will be used by the Callback and HAS to be pending and of type DEPOSIT
        return $this->db->select('t.*')
                ->from('transaction t')
                ->where('t.type', 'deposit')
                ->where('t.identifier', $identifier)
                ->where('t.status', 'pending')
                ->get()
                ->row();
    }

    public function referenceExists($code, $reference) {
        // no need to tie this with the `type` as we want to make sure the reference has never
        // been used throughout
        return $this->db->from('transaction')
                ->where('method', $code)
                ->where('reference', $reference)
                ->count_all_results() > 0;
    }

    public function getCountByUserId($userId, $status) {
        // This is a little hack to prevent deposits made with any e-currencies from showing
        // in the pending list
        if ($status == 'pending')
            $this->db->where_in('method', array('bw', 'wu'));

        $this->db->where('user_id', $userId);

        return $this->getCount($status);
    }

    public function countItems($code) {
        $this->db->where('item_code', $code);
        return $this->getCount('ok');
    }


    public function getSubsetByUserId($userId, $status, $page = 1, $perpage = 10) {
        // This is a little hack to prevent deposits made with any e-currencies from showing
        // in the pending list
        if ($status == 'pending') {
            $whereSql = "((t.method IN ('bw', 'wu') AND t.type = 'deposit') OR t.type = 'cashout')";
            $this->db->where($whereSql);
        }

        $this->db->where('t.user_id', $userId);

        return $this->getSubset($status, $page, $perpage);
    }

    public function getTransaction($id) {
        return $this->db->from('transaction')
                ->where('id', $id)
                ->get()->row();
    }

    public function getTransactionCountByUserId($userId, $type) {
        // This is a little hack to prevent deposits made with any e-currencies from showing
        // in the pending list

        return $this->db->from('transaction')
                ->where('user_id', $userId)
                ->where('status', 'ok')
                ->where('type', $type)
                ->count_all_results();
    }

    public function getLatestTransaction($userId, $type) {

        return $this->db->from('transaction')
                ->where('user_id', $userId)
                ->where('status', 'ok')
                ->where('type', $type)
                ->order_by('created', 'desc')
                ->limit(1)
                ->get()->row();
    }

    public function getTransactionSubsetByUserId($userId, $type, $page = 1, $perpage = 10) {
        // This is a little hack to prevent deposits made with any e-currencies from showing
        // in the pending list

        $start = ($page - 1)*$perpage;

        return $this->db
                ->select('t.id, t.type, t.method, t.details, t.gross_amount, t.amount, t.created, t.updated, pm.name')
                ->from('transaction t')
                ->join('payment_method pm', 'pm.code = t.method')
                ->where('t.status', 'ok')
                ->where('t.user_id', $userId)
                ->where('t.type', $type)
                ->order_by('t.updated', 'DESC')
                ->limit($perpage, $start)
                ->get()
                ->result();
    }

    // generic functions that are used by other functions within the same model
    public function getCount($status) {
        return $this->db->from('transaction')
                ->where('status', $status)
                ->count_all_results();
    }

    public function getSubset($status, $page = 1, $perpage = 10) {
        $start = ($page - 1)*$perpage;

        return $this->db
                ->select('t.id, t.status, t.type, t.method, t.details, t.gross_amount, t.amount, t.created, t.updated, pm.name')
                ->from('transaction t')
                ->join('payment_method pm', 'pm.code = t.method')
                ->where('t.status', $status)
                ->where('t.type <> ', 'transfer')
                ->order_by('t.updated', 'DESC')
                ->limit($perpage, $start)
                ->get()
                ->result();
    }

    public function getDetails($id, $userId = NULL) {
        if ($userId)
            $this->db->where('t.user_id', $userId);

        $r = $this->db->select('t.*, pm.name')
                ->from('transaction t')
                ->join('payment_method pm', 'pm.code = t.method')
                ->where('t.id', $id)
                ->get()
                ->row();

        return $r;
    }

    /**
     * Count number of transaction depending on filter
     *
     * @param string $code Payment method code ('lr', 'bw'...)
     * @param string $type Type of transaction: 'cashout' or 'deposit'
     * @param string $status
     * @param bool   $today Boolean to search only today data
     * @param date   $date  to search only for this date
     * @return type
     */
    public function countTransactions($code, $type = NULL, $status = NULL, $date = NULL) {
        if ($code != 'any')
            $this->db->where('method', $code);

        if ($type)
            $this->db->where('type', $type);

        if ($date) {
            $end = strtotime("+1 day", $date);
            $this->db->where("updated BETWEEN $date AND $end");
        }

        if ($status)
            $this->db->where('status', $status);

        return $this->db->from('transaction')
                ->count_all_results();
    }

    public function getTransactionsAmount($code, $type, $status = NULL) {
        if ($status)
            $this->db->where('status', $status);

        return $this->db->select_sum('gross_amount', 'amount')
                ->from('transaction')
                ->where('type', $type)
                ->where('method', $code)
                ->get()
                ->row()
                ->amount;
    }

    public function getDepositsSubset($code, $status, $page, $perpage = 30, $date = NULL) {
        $start = ($page - 1)*$perpage;

        if ($code != 'any')
            $this->db->where('t.method', $code);

        if ($date) {
            $end = strtotime("+1 day", $date);
            $this->db->where("t.updated BETWEEN $date AND $end");
        }

        return $this->db->from('transaction t')
                ->select('t.*, u.username, upm.account, pma.name deposit_account_name')
                ->join('users u', 'u.id = t.user_id')
                ->join('user_payment_method upm', "upm.user_id = u.id AND upm.payment_code = t.method")
                ->join('payment_method_account pma', "pma.id = t.account_id")
                ->where('t.type', 'deposit')
                ->where('t.status', $status)
                ->order_by('t.updated', 'DESC')
                ->limit($perpage, $start)
                ->get()
                ->result();
    }

    public function getCashoutsSubset($code, $status, $page, $perpage = 30, $date = NULL) {
        $start = ($page - 1)*$perpage;

        if ($code != 'any')
            $this->db->where('t.method', $code);

        if ($date) {
            $end = strtotime("+1 day", $date);
            $this->db->where("t.updated BETWEEN $date AND $end");
        }

        $r = $this->db->from('transaction t')
                ->select('t.*, u.username') //->select('t.*, u.username, upm.account')
                ->join('users u', 'u.id = t.user_id')
                ->join('user_payment_method upm', 'upm.user_id = u.id AND upm.payment_code = t.method')
                ->where('t.type', 'cashout')
                ->where('t.status', $status)
                ->order_by('t.updated', 'DESC')
                ->limit($perpage, $start)
                ->get()
                ->result();

        $sql = $this->db->last_query();

        return $r;
    }

    // Only used by the callback controller when an e-currency is used
    public function failDeposit($code, $reason, $data) {

        log_message('debug', '<<bjb>> TRANSACTION MODEL :: failDeposit');

        $data = array(
            'method'  => $code,
            'reason'  => $reason,
            'data'    => $data,
            'created' => $this->now,
            'ip'      => $this->input->ip_address()
        );

        $this->db->insert('deposit_fail', $data);
    }

    public function getFailed($id) {
        return $this->db->from('deposit_fail')->where('id', $id)->get()->row();
    }

    public function getCashoutDetails($id) {
        return $this->db->select('t.*, u.username, upm.account')
                ->from('transaction t')
                ->join('users u', 'u.id = t.user_id')
                ->join('user_payment_method upm', 'upm.user_id = u.id AND upm.payment_code = t.method')
                ->where('t.type', 'cashout')
                ->where('t.id', $id)
                ->get()
                ->row();
    }

    public function identifier() {
        return random_string('alnum', 12);
    }

    public function auditUser($userId) {

        $audit = array();

        $sql = "select sum(p.qty) amount
                FROM purchase_order p
                JOIN transaction t ON t.id = p.transaction_id
                where t.status = 'ok'
                and p.status = 'complete'
                and t.user_id = $userId
                and t.type = 'deposit' and t.item_code ='account_funds'";

        $audit['account funds'] = $this->db->query($sql)->row()->amount;

        $sql = "select sum(amount) amount
                FROM transaction
                where status = 'ok'
                and user_id = $userId
                and type = 'share_sale'";

        $audit['share sales'] = $this->db->query($sql)->row()->amount;

        $sql = "select sum(amount) amount
                FROM transaction
                where status = 'ok'
                and user_id = $userId
                and type = 'ref_comm'";

        $audit['ref comm'] = $this->db->query($sql)->row()->amount;

        $sql = "select sum(amount) amount
                FROM transaction
                where status = 'ok'
                and user_id = $userId
                and type = 'dividend'";

        $audit['dividends'] = $this->db->query($sql)->row()->amount;

        $sql = "select sum(amount) amount
                FROM transaction
                where status = 'ok'
                and user_id = $userId
                and type = 'earning'";

        $audit['earning'] = $this->db->query($sql)->row()->amount;

        $sql = "select sum(amount) amount
                FROM transaction
                where status = 'ok'
                and user_id = $userId
                and type = 'refund'";

        $audit['bid refunds'] = $this->db->query($sql)->row()->amount;

        $sql = "select sum(amount) amount
                FROM transaction
                where status = 'ok'
                and user_id = $userId
                and type = 'share_purchase'
                AND item_code != 'shares'";

        $audit['share purchases'] = -$this->db->query($sql)->row()->amount;

        $sql = "select sum(apply_balance) amount
                FROM purchase_order p
                JOIN transaction t ON t.id = p.transaction_id
                where t.status = 'ok'
                and p.status = 'complete'
                and t.user_id = $userId";

        $audit['balance applied'] = -$this->db->query($sql)->row()->amount;

        $sql = "select sum(gross_amount) amount
                FROM transaction
                where status = 'ok'
                and user_id = $userId
                and type = 'admin_adjustment'";

        $audit['adjustments'] = $this->db->query($sql)->row()->amount;

        $sql = "select sum(gross_amount) amount
                FROM transaction
                where status = 'ok'
                and user_id = $userId
                and type = 'cashout'";

        $audit['cashouts'] = -$this->db->query($sql)->row()->amount;



        return $audit;

    }
}