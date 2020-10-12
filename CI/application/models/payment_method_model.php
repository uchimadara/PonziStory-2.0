<?php
class Payment_method_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        return $this->db->from('payment_method')
            ->where('code <>', 'eb')
            ->get()
            ->result();
    }

    public function getList()
    {
        return $this->db->from('payment_method')
            ->where('code <>', 'eb')
            ->get()
            ->result();
    }

    public function enabled(){
        $this->db->where('enabled', 1);
        return $this;
    }
    public function exclude($code)
    {
        if ($code) { // to deal with null or false
            if (is_array($code))
                $this->db->where_not_in('code', $code);
            else $this->db->where('code <>', $code);
        }

        return $this;
    }

    public function getByUserId($userId, $code = NULL)
    {
        $this->db->select('pm.name, upm.*')
            ->from('user_payment_method upm')
            ->join('payment_method pm', 'pm.code = upm.payment_code')
            ->where('upm.user_id', $userId);

        // If we specify a method we want to return 1 row instead of an array
        if ($code)
        {
            return $this->db->where('pm.code', $code)
                ->get()
                ->row();
        }

        return $this->db->get()
            ->result();
    }

    public function getUserBalances($userId, $all = FALSE) {
        if (!$all) $this->db->where('upm.balance >', 0);

        return $this->db->select('upm.id, upm.method_name, upm.note, pm.name, pm.code, upm.balance, upm.account')
                ->from('user_payment_method upm')
                ->join('payment_method pm', 'pm.code = upm.payment_code')
                ->where('upm.user_id', $userId)
                ->get()
                ->result();
    }

    private function userMethod($userId, $method)
    {
        return $this->db->from('user_payment_method')
            ->where('user_id', $userId)
            ->where('payment_code', $method)
            ->get()
            ->row();
    }

    public function set($userId, $code, $account)
    {
        $now = $this->now;

        if ($userMethod = $this->userMethod($userId, $code))
        {
            $this->db->set('account', $account)
                ->set('updated', $now)
                ->where('id', $userMethod->id)
                ->update('user_payment_method');
        }
        else
        {
            $data = array(
                'user_id'      => $userId,
                'payment_code' => $code,
                'account'      => $account,
                'created'      => $now,
                'updated'      => $now
            );

            $this->db->insert('user_payment_method', $data);
        }

        return $this->db->affected_rows() > 0;
    }

    public function setBalance($userId, $code, $balance)
    {
        $now = $this->now;

        if ($userMethod = $this->userMethod($userId, $code))
        {
            // Be extra careful when negative amount is passed - we don't want the balance to go negative
            if ($balance < 0)
                $this->db->where('balance >= ', abs($balance));

            $this->db->set('balance', 'balance + ' . $balance, FALSE)
                ->set('updated', $now)
                ->where('id', $userMethod->id)
                ->update('user_payment_method');
        }
        else
        {
            $data = array(
                'user_id'      => $userId,
                'payment_code' => $code,
                'balance'      => $balance,
                'created'      => $now,
                'updated'      => $now
            );

            $this->db->insert('user_payment_method', $data);
        }

        return $this->db->affected_rows() > 0;
    }

    public function getBalancesList($userId, $union = 'left', $excluded = array())
    {
        if (count($excluded))
            $this->db->where_not_in('pm.code', $excluded);

        $r = $this->db->select('pm.id, pm.name, pm.code, pm.enabled, upm.account, IFNULL(upm.balance, 0) balance, upm.locked', FALSE)
            ->from('payment_method pm')
            ->join('user_payment_method upm', "upm.payment_code = pm.code AND upm.user_id = $userId", $union)
            ->where('pm.enabled', 1)
                ->order_by('pm.sorting')
            ->get()
            ->result();

        return $r;
    }

    public function getCashoutBalancesList($userId) {
        return $this->db
                ->select('pm.id, pm.name, pm.code, pm.enabled, upm.account, IFNULL(upm.balance, 0) balance, upm.locked', FALSE)
                ->from('payment_method pm')
                ->join('user_payment_method upm', "upm.payment_code = pm.code AND upm.user_id = $userId", 'LEFT')
                ->where('pm.enabled', 1)
                ->where('pm.cashout_enabled', 1)
                ->order_by('pm.sorting')
                ->get()
                ->result();
    }

    public function getAccountsForUser($userId)
    {
        $result = $this->db->select('pm.id, pm.name, pm.code, upm.account')
            ->from('payment_method pm')
            ->join('user_payment_method upm', 'upm.payment_code = pm.code')
            ->where('upm.user_id', $userId)
            ->get()
            ->result();

        $accounts = array();
        foreach ($result as $row) $accounts[$row->code] = $row->account;
    }

    public function getAccountForUser($userId, $code)
    {
        return $this->db->select('pm.id, pm.name, upm.id upm_id, upm.note, upm.method_name, upm.balance, pm.enabled, upm.account, upm.locked, upm.created')
            ->from('payment_method pm')
            ->join('user_payment_method upm', "upm.payment_code = pm.code and upm.user_id = $userId", 'left')
            ->where('pm.code', $code)
            ->get()
            ->row();
    }

    public function getBalance($userId, $method)
    {
        return $this->db->select('upm.balance')
            ->from('payment_method pm')
            ->join('user_payment_method upm', "upm.payment_code = pm.code and upm.user_id = $userId", 'left')
            ->where('pm.code', $method)
            ->get()
            ->row()
            ->balance;
    }

    public function getTotalBalances($userId)
    {
        return $this->db->select_sum('upm.balance')
            ->from('payment_method pm')
            ->join('user_payment_method upm', "upm.payment_code = pm.code and upm.user_id = $userId", 'left')
            ->get()
            ->row()
            ->balance;
    }

    public function checkAccountExists($code, $account)
    {
        return $this->db->from('user_payment_method')
            ->where('account', $account)
            ->where('payment_code', $code)
            ->count_all_results() > 0;
    }

    public function calculateGross($amount, $code, $type, $operation)
    {
        $taxData = $this->db->from('payment_method_bill')
            ->where('payment_code', $code)
            ->where('type', $type)
            ->where('operation', $operation)
            ->order_by('date', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if ($taxData)
        {
            $tax = $amount * $taxData->percent / 100 + $taxData->fixed;
            if ($taxData->max)
                $tax = min($tax, $taxData->max);

            return $tax;
        }

        return 0;
    }

    public function getFeeData($code, $operation, $type='fee')
    {
        if (($result = $this->getCache('payment_fees'.$code.$operation.$type, '', CACHE_FIVE_MINUTES)) == FALSE) {
            $result = $this->db->from('payment_method_bill')
            ->where('payment_code', $code)
            ->where('operation', $operation)
            ->where('type', $type)
            ->order_by('date', 'DESC')
            ->limit(1)
            ->get()
            ->row();

            $this->saveCache($result);
        }

        return $result;
    }

    public function getFees($operation) {

        if (($result = $this->getCache('payment_fees'.$operation, '', CACHE_FIVE_MINUTES)) == FALSE) {
            $sql = "SELECT *
                      FROM payment_method_bill
                     WHERE operation = '$operation'
                     AND type='fee'";

            $data = $this->db->query($sql)->result();

            $result = array();
            foreach ($data as $d) {
                $obj          = new stdClass();
                $obj->percent = $d->percent;
                $obj->fixed   = $d->fixed;
                $obj->max     = $d->max;

                $result[$d->payment_code] = $obj;
            }
            $this->saveCache($result);
        }

        return $result;
    }

    public function getBillDetails($method)
    {
        $sql = "SELECT *
                      FROM payment_method_bill
                     WHERE payment_code = '$method'
                  ORDER BY date DESC";

        $data = $this->db->query($sql)->result();

        $result = array();
        foreach ($data as $d)
        {
            $operation = $d->operation;
            $type      = $d->type;

            $obj = new stdClass();
            $obj->percent = $d->percent;
            $obj->fixed   = $d->fixed;
            $obj->max     = $d->max;

            $result[$operation][$type] = $obj;
        }

        return $result;
    }

    // Not sure yet
    public function addAccount($data)
    {
        return $this->db->insert('payment_method_account', $data);
    }

    public function updateAccount($accountId, $data)
    {
        $this->db->where('id', $accountId)
            ->update('payment_method_account', $data);

        return $this->db->affected_rows() > 0;
    }

    public function updateMethodBill($code, $type, $operation, $data)
    {
        $data['payment_code'] = $code;
        $data['type']         = $type;
        $data['operation']    = $operation;
        $data['date']         = $this->now;

        return $this->db->insert('payment_method_bill', $data);
    }

    // EXCLUDED AS NOT IN USE UNLESS OTHERWISE STATED
    /*
    public function UpdateAccount($userAccId, $data)
    {
        $this->db->where('id', $userAccId);
        return  $this->db->update('user_payment_method',$data);
    }
    */

    public function methodStatus($id, $enabled) {
        return $this->db->where('id', $id)
                ->set('enabled', $enabled)
                ->update('payment_method');
    }

    public function accountStatus($accountId, $enabled)
    {
        return $this->db->where('id', $accountId)
            ->set('enabled', $enabled)
            ->update('payment_method_account');
    }

    public function getAccountDetails($code = NULL, $direction = 'all') {
        if (($result = $this->getCache('payment_account_details'.$code.$direction, '', CACHE_FIVE_MINUTES)) == FALSE) {
            if ($direction != 'all') {
                $this->db->where("(restrict_to = '$direction' OR restrict_to IS NULL OR restrict_to = '')");
            }
            if ($code) {
                $this->db->where('payment_code', $code);
            }

            $result = $this->db->from('payment_method_account')
                    ->where('enabled', 1)
                    ->get()
                    ->result();
            if ($code) $result = $result[0];
            $this->saveCache($result);
        }
        return $result;
    }

    public function getAccountDetailsById($accountId)
    {
        return $this->db->select('pma.*, pm.name method_name')
            ->from('payment_method_account pma')
            ->join('payment_method pm', 'pm.code = pma.payment_code')
            ->where('pma.id', $accountId)
            ->get()
            ->row();
    }

    public function getNameFromCode($code)
    {
        return $this->db->select('name')
            ->from('payment_method')
            ->where('code', $code)
            ->get()
            ->row()
            ->name;
    }

    /****
     * M2M functions
     */
    public function getPaymentMethod($id) {
        return $this->db->from('user_payment_method upm')
                ->where('id', $id)
                ->get()
                ->row();
    }
    public function addPaymentMethod($data) {
        $this->db->insert('user_payment_method', $data);
    }

    public function updatePaymentMethod($id, $data) {
        $this->db->where('id', $id)->update('user_payment_method', $data);
    }

    public function deletePaymentMethod($id, $userId) {

        $this->db->where('id', $id)
                ->where('user_id', $userId)->update('user_payment_method', array('deleted' => 1));
    }

    public function getUserMethods($userId) {
        return $this->db->from('user_payment_method upm')
                ->where('upm.user_id', $userId)
                ->where('upm.deleted', 0)
                ->get()
                ->result();
    }
}