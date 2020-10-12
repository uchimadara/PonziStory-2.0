<?php
class Cashier_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function purgeTempDeposits($timeSpan)
    {
        // Remove the temp deposits made by the e-currencies when they have exceeded the $timeSpan
        $this->db->where_not_in('method', array('wu', 'bw'))
            ->where('type', 'deposit')
            ->where('created <', $this->now - $timeSpan)
            ->where('status', 'pending')
            ->delete('transaction');
    }

    public function capital() {
        $this->db->where('capital', 1);
        return $this;
    }

    public function noncapital() {
        $this->db->where('capital', 0);
        return $this;
    }

    public function getExpenses($begin, $end, $page, $perpage) {
        $start = ($page - 1)*$perpage;

        return $this->db->from('expense')
                ->where("apply_date BETWEEN $begin AND $end", NULL, FALSE)
                ->limit($start, $perpage)
                ->get()->result();

    }

    public function sumExpenses($begin = NULL, $end = NULL) {
        if ($begin) $this->db->where("apply_date >=", $begin);
        if ($end) $this->db->where("apply_date <=", $end);

        $r = $this->db->select('name category, sum(amount) total')
                        ->from('expense')
        ->join('expense_category c', 'c.id = expense.category_id')
                        ->group_by('name')
                        ->get()->result();

        $ret = array();
        foreach ($r as $row) $ret[$row->category] = $row->total;

        return $ret;
    }

    public function sumShareSales($begin = NULL, $end = NULL) {

        if (($result = $this->getCache('SumShareSales'.$begin.$end, NULL, CACHE_ONE_DAY)) === FALSE) {
            if ($begin) $this->db->where("apply_date >=", $begin);
            if ($end) $this->db->where("apply_date <=", $end);

            $row = $this->db->query("
                SELECT sum(p.qty*i.shares) count, sum(gross_amount-p.fee) amount
                FROM `transaction` t
                join purchase_order p on t.id = p.transaction_id
                join purchase_item i on i.id = p.purchase_item_id
                WHERE t.`item_code` = 'shares'
                and t.type='share_purchase'
                and t.status = 'ok'
                and p.status = 'complete'
                ")->row();

            $result = array($row->amount, $row->count);
            $this->saveCache($result);
        }
        return $result;
    }

    public function increaseUserBalance($userId, $method, $amount)
    {
        $now = $this->now;

        $userData = $this->db->from('user_payment_method')
            ->where('user_id', $userId)
            ->where('payment_code', $method)
            ->get()
            ->row();

        if ($userData)
        {
            $this->db->set('balance', 'balance + ' . $amount, FALSE)
                ->set('updated', $now)
                ->where('id', $userData->id)
                ->update('user_payment_method');
        }
        else
        {
            $data = array(
                'user_id'      => $userId,
                'payment_code' => $method,
                'balance'      => $amount,
                'created'      => $now,
                'updated'      => $now
            );

            $this->db->insert('user_payment_method', $data);
        }

        $this->db->set('balance', 'balance + ' . $amount, FALSE)
            ->where('id', $userId)
            ->update('users');

    }

    public function setUserMethodBalance($userId, $method, $amount, $account=NULL) {
        $now = $this->now;

        $userData = $this->db->from('user_payment_method')
                             ->where('user_id', $userId)
                             ->where('payment_code', $method)
                             ->get()
                             ->row();

        if ($userData) {
            $this->db->set('balance', $amount)
                     ->set('updated', $now)
                     ->where('id', $userData->id)
                     ->update('user_payment_method');
        } else {
            $data = array(
                'user_id'      => $userId,
                'payment_code' => $method,
                'balance'      => $amount,
                'created'      => $now,
                'updated'      => $now
            );
            if ($account) $data['account'] = $account;

            $this->db->insert('user_payment_method', $data);
        }

    }

    public function transferBalance($userId, $fromCode, $toCode, $amount) {
        $this->db->where('user_id', $userId)
                ->where('payment_code', $fromCode)
                ->set('balance', 'balance - '.$amount, FALSE)
                ->update('user_payment_method');

        $this->db->where('user_id', $userId)
                ->where('payment_code', $toCode)
                ->set('balance', 'balance + '.$amount, FALSE)
                ->update('user_payment_method');
    }

    public function modifyUserBalance($userId, $paymentMethodCode, $amount)
    {
        // Don't want to go negative on the balance
        if ($amount < 0)
            $this->db->where('balance >= ', $amount);

        $this->db->set('balance', 'balance + ' . $amount, FALSE)
            ->where('user_id', $userId)
            ->where('payment_code', $paymentMethodCode)
            ->update('user_payment_method');

        if ($this->db->affected_rows() == 0)
            return FALSE;

        // Don't want to go negative on the balance
        if ($amount < 0)
            $this->db->where('balance >= ', $amount);

        $this->db->set('balance', 'balance + ' . $amount, FALSE)
            ->where('id', $userId)
            ->update('users');

        return $this->db->affected_rows() > 0;
    }

    public function decreaseUserBalance($userId, $method, $amount)
    {
        $this->db->set('balance', 'balance - ' . $amount, FALSE)
            ->set('updated', $this->now)
            ->where('user_id', $userId)
            ->where('payment_code', $method)
            ->where('balance >= ', $amount)
            ->update('user_payment_method');

        if ($this->db->affected_rows() == 0)
            return FALSE;

        $this->db->set('balance', 'balance - ' . $amount, FALSE)
            ->where('id', $userId)
            ->where('balance >= ', $amount)
            ->update('users');

        return $this->db->affected_rows() > 0;
    }

    public function checkUserHasEnoughMoney($userId, $method, $amount)
    {
        $currentBalance = $this->db->from('user_payment_method')
            ->where('payment_code', $method)
            ->where('user_id', $userId)
            ->get()
            ->row()
            ->balance;

        return $currentBalance >= $amount;
    }

    // TODO: **ALEX** redo these 2 functions
    public function getPartialBalances()
    {
        return $this->db->select('name, payment_code code, sum(balance) balance')
            ->group_by('payment_code')
            ->join('payment_method','payment_code = code')
            ->order_by('name')
            ->get('user_payment_method')
            ->result();
    }

    public function pendingCashout($userId) {
        return $this->db->from('transaction t')
                ->where('t.user_id', $userId)
                ->where('t.type', 'cashout')
                ->where('t.status', 'pending')
                ->count_all_results() > 0;
    }

    public function getPendingCashout($userId) {
        $r = $this->db->from('transaction t')
                ->where('t.user_id', $userId)
                ->where('t.type', 'cashout')
                ->where('t.status', 'pending')
                ->get();

        if ($r->num_rows() > 0) {
            return $r->row();
        }

        return FALSE;
    }

    public function getUserBalance($userId) {
        return $this->db->select('balance')
                        ->where('id', $userId)
                        ->get('users')
                        ->row()
                ->balance;
    }

    public function getTotalBalance()
    {
        return $this->db->select_sum('balance')
            ->get('user_payment_method')
            ->row()
            ->balance;
    }

    public function getTotalBalances() {
        return $this->db->select_sum('balance')
                        ->get('users')
                        ->row()
                ->balance;
    }

    public function getUsersBalances($code, $page = 1, $perpage = 50)
    {
        $start = ($page - 1) * $perpage;
        return $this->db->query("
                            SELECT u.id, u.username, upm.balance
                              FROM user_payment_method upm
                        INNER JOIN users u
                                ON u.id = upm.user_id
                             WHERE upm.payment_code = '$code'
                          ORDER BY upm.balance DESC
                             LIMIT $start, $perpage
                                ")->result();

    }

    public function countGetUsersBalances($code)
    {
        return $this->db->from('user_payment_method')
            ->where('payment_code', $code)
            ->count_all_results();
    }

    public function adjustBalance($userId, $method, $amount, $message)
    {
        if ($amount > 0) {
            $worked = TRUE;
            $this->increaseUserBalance($userId, $method, $amount); // will create entry if none exists
        } else {
            $worked = $this->modifyUserBalance($userId, $method, $amount);
        }
        if ($worked) {
            $data = array(
                'user_id' => $userId,
                'method'  => $method,
                'amount'  => $amount,
                'message' => $message ? $message : NULL,
                'balance' => $this->PaymentMethod->getBalance($userId, $method),
                'total'   => $this->ion_auth->user($userId)->row()->balance,
                'date'    => $this->now
            );

            if ($this->db->insert('adjustment', $data))
                return TRUE;
        }

        return FALSE;
    }

    public function recalculateBalance($userId) {
        $bal = $this->db->select_sum('balance')->from('user_payment_method')->where('user_id', $userId)->get()->row()->balance;
        $this->db->query("UPDATE users SET balance = $bal WHERE id = $userId");
        return $bal;
    }

    /*** ALERT ALERT ALERT ***/
    // This function is not to be used - just been made to correct some errors from admin
    public function fixTransaction($identifier, $newAmount)
    {
        return;
        $transaction = $this->db->from('transaction')
            ->where('identifier', $identifier)
            ->where('gross_amount <>', $newAmount)
            ->where('type', 'deposit')
            ->get()
            ->row();

        if ($transaction)
        {
            $userId       = $transaction->user_id;
            $method       = $transaction->method;
            $tNetAmount   = $transaction->amount;

            // Calculate the new fees and net amount from the method
            $fee       = roundUp($this->PaymentMethod->calculateGross($newAmount, $method, 'fee', 'deposit'));
            $netAmount = $newAmount - $fee;

            // Let's do this
            $this->db->trans_start();

            // Update the transaction
            $this->db->set('gross_amount', $newAmount)
                ->set('fee', $fee)
                ->set('amount', $netAmount)
                ->where('id', $transaction->id)
                ->update('transaction');

            // Calculate the offset for the history and balances
            $offset = $netAmount - $tNetAmount;

            // So we need to add $offset to the balances
            // Update balance of payment_method
            $this->db->query("UPDATE user_payment_method
                            SET balance = balance + $offset
                          WHERE user_id = $userId AND payment_code = '$method'");

            // Update general balance in users
            $this->db->query("UPDATE users
                SET balance = balance + $offset WHERE id = $userId");

            // Make sure the transaction history is updated as well
            $history = $this->db->from('transaction_history')
                ->where('user_id', $userId)
                ->where('foreign_key >= ', $transaction->id)
                ->order_by('id')
                ->get()
                ->result();

            foreach ($history as $idx=>$h)
            {
                if ($idx == 0) // First entry update the balance
                {
                    $this->db->set('balance', $h->balance + $offset)
                        ->set('amount', $h->amount + $offset);
                }

                $this->db->set('total', $h->total + $offset)
                    ->where('id', $h->id)
                    ->update('transaction_history');
            }

            // Refresh the traffic value
            $this->load->model('user_model', 'User');
            $this->User->refreshNetValue($userId);

            // Press it!
            $this->db->trans_complete();
            if ($this->db->trans_status())
            {
                echo "all good";
                return TRUE;
            }

            echo "kaboom";
            return FALSE;
        }

        echo "Transaction not found or already processed";
        return FALSE;
    }
}