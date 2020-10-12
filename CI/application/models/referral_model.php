<?php

class Referral_model extends MY_Model
{
    private $refList = NULL;

    public function __construct()
    {
        parent::__construct();
    }

    public function recordClick($userId, $destination) {

        $cameFrom = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown';

        $this->db->insert('reflink_clicks', array('user_id' => $userId,
                                        'destination' => $destination,
                                        'came_from' => $cameFrom,
                                        'date' => $this->now));

        return $this->db->insert_id();
    }

    public function updateClick($clickId, $userId, $destination) {

        $cameFrom = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown';

        $this->db->where('id', $clickId)
                 ->update('reflink_clicks', array('user_id'     => $userId,
                                                  'destination' => $destination,
                                                  'came_from'   => $cameFrom,
                                                  'date'        => $this->now));
    }

    public function getClick($id) {

        return $this->db->from('reflink_clicks')
                            ->where('id', $id)
                            ->get()->row();
    }

    public function countClicksByDay($id = NULL) {

        $where = ($id) ? " where user_id = $id " : "";

        return $this->db->query("select (date div (24*3600)) * (24*3600) day, count(*)  c from reflink_clicks
                                $where
                                group by day order by day")
                ->result();
    }

    public function countSignupsByDay($id = NULL) {
        $where = ($id) ? " where user_id = $id " : "";

        return $this->db->query("select (created div (24*3600)) * (24*3600) day, count(*)  c from referrals
                                $where
                                group by day order by day")
                        ->result();
    }


    public function registerClick($clickId, $userId) {

        $this->db->where('id', $clickId)
                 ->update('reflink_clicks', array('referral_user_id' => $userId));
    }

    public function getClickCountDate($userId = NULL, $begin = NULL, $end = NULL ) {

        if ($begin)
            $this->db->where('date >=', $begin);

        if ($end)
            $this->db->where('date <=', $end);

        if ($userId)
            $this->db->where('user_id', $userId);

        $results = $this->db->from('reflink_clicks')->count_all_results();

        return $results;
    }

    public function countReferrals($userId, $active = NULL, $upgraded = NULL, $begin = NULL, $end = NULL) {

        if ($begin)
            $this->db->where('created_on >=', $begin);

        if ($end)
            $this->db->where('created_on <=', $end);

        if ($active) {
            $this->db->where('active', 1);
            $this->db->where('deleted', 0);
            $this->db->where('locked', 0);
            $this->db->where('banned', 0);
        }
        if ($upgraded)
            $this->db->where('account_level >', 0);

        $results = $this->db->from('users')
                            ->where('referrer_id', $userId)
                           // ->where('active', 1)
                            ->count_all_results();

        return $results;
    }

    public function summary($userId, $active = NULL, $begin = NULL, $end = NULL) {

        if (($summary = $this->getCache("refsummary_{$userId}", NULL, CACHE_FIVE_MINUTES)) === FALSE) {

            if ($begin)
                $this->db->where('created_on >=', $begin);

            if ($end)
                $this->db->where('created_on <=', $end);

            if ($active)
                $this->db->where('account_level != ', 'Free');

            $results = $this->db->select('code, price')
                    ->from('purchase_item')
                    ->order_by('code')
                    ->get()->result();

            $price = array();
            foreach ($results as $r) $price[$r->code] = $r->price;

            $results = $this->db->select('level, count(*) referrals', FALSE)
                    ->from('referrals r')
                    ->where('r.user_id', $userId)
                    ->group_by('r.level')
                    ->order_by('r.level')
            ->get()->result();

            $refs = array();
            foreach ($results as $r) $refs[$r->level] = $r->referrals;

            $results = $this->db->select('i.code, sum(p.amount) total', FALSE)
                ->from('payment p')
                ->join('purchase_item i', 'i.id = p.upgrade_id')
                ->where('p.approved IS NOT NULL')
                ->where('p.payee_user_id', $userId)
               // ->where('p.deleted', 0)
                ->group_by('i.code')
                ->order_by('i.code')
            ->get()->result();

            $payments = array();
            foreach ($results as $r) $payments[$r->code] = $r->total;

            //echo $this->db->last_query();

            $summary = array();

            for ($i = 1; $i <= intval(CYCLER_DEPTH); $i++) {
                $summary[$i] = new stdClass();
                $summary[$i]->price = $price[$i];
                $summary[$i]->referrals = (isset($refs[$i])) ? $refs[$i] : 0;
                $summary[$i]->earning = (isset($payments[$i])) ? $payments[$i] : 0;
            }

            $this->saveCache($summary);
        }

        return $summary;
    }

    public function getPayments($refereeId) {
        return $this->db->from('referrals')
                ->where('referee_id', $refereeId)
                ->order_by('level', 'asc')
                ->get()->result();
    }

    public function getReferrerId($userId, $action = '')
    {
            return $this->db->from('users')
                    ->select('referrer_id')
                    ->where('id', $userId)
                    ->get()
                    ->row()
                    ->referrer_id;
    }

    public function getReferrerDetails($userId)
    {
        return $this->db->select('username, email, email_settings, account_level, avatar')
                ->from('users')
                ->where('id', $userId)
            ->get()
            ->row();
    }

    public function getCommission($action)
    {
        $r = $this->db->select('type, level1, level2')
            ->from('referral_commission')
            ->where('action', $action)
            ->get()
            ->row();

        return $r;
    }

    public function payReferrers($userId, $action, $actionId, $amount)
    {
        $commission = $this->getCommission($action);

        if ($userId > 0) {
            $referrerL1 = $this->getReferrerId($userId);
        } else {
            $referrerL1 = $this->getGuestReferrerId($action, $actionId);
        }

        if ($referrerL1)
        {
            if ($commission->type=='percent') {
                $bonus = roundDown($amount * $commission->level1 / 100, 5);
            } else { // dollar value
                $bonus = floatval($commission->level1);
            }

            $this->payReferrer($referrerL1, $userId, 1, $action, $actionId, $bonus);

            $referrerL2 = $this->getReferrerId($referrerL1);
            if ($referrerL2)
            {
                if ($commission->type=='percent') {
                    $bonus = roundDown($amount * $commission->level2 / 100, 5);
                } else { // dollar value
                    $bonus = floatval($commission->level2);
                }
                if ($bonus > 0)
                    $this->payReferrer($referrerL2, $userId, 2, $action, $actionId, $bonus);
            }
        }
        return TRUE;
    }

    public function payUserDirect($userId, $refereeId, $level, $action, $actionId, $amount) {

        $commission = $this->getCommission($action);

        if ($commission->type == 'percent') {
            $bonus = roundDown($amount*$commission->level1/100, 5);
        } else { // dollar value
            $bonus = floatval($commission->level1);
        }

        if ($userId == 0) {
            $userId = $this->getGuestReferrerId($action, $actionId);
        }

        if ($userId) $this->payReferrer($userId, $refereeId, $level, $action, $actionId, $bonus);
    }

    private function payReferrer($userId, $refereeId, $level, $action, $actionId, $amount)
    {
        $now = $this->now;

        // Some magic SQL here
        $sql = "INSERT INTO referrals (referee_id, user_id, level, earning, created, updated)
            VALUES ($refereeId, $userId, $level, $amount, $now, $now)
            ON DUPLICATE KEY UPDATE earning = earning + $amount, updated = $now";
        $this->db->query($sql);

        $balance      = $this->PaymentMethod->getBalance($userId, 'eb');
        $totalBalance = $this->ion_auth->user($userId)->row()->balance;

        $this->Cashier->increaseUserBalance($userId, 'eb', $amount);


        $historyData = array(
            'referee_id'    => $refereeId,
            'user_id'       => $userId,
            'level'         => $level,
            'foreign_key'   => $actionId,
            'type'          => $action,
            'amount'        => $amount,
            'balance'       => $balance+$amount,
            'total'         => $totalBalance+$amount,
            'date'          => $this->now
        );

        $this->History->add('referral_history', $historyData);

        return TRUE;
    }

    public function addEarnings($userId, $refereeId, $level, $amount) {
        $now = $this->now;

        // Some magic SQL here
        $sql = "INSERT INTO referrals (referee_id, user_id, level, earning, created, updated)
            VALUES ($refereeId, $userId, $level, $amount, $now, $now)
            ON DUPLICATE KEY UPDATE earning = earning + $amount, updated = $now";
        $this->db->query($sql);

        return TRUE;
    }

    public function addPayment($userId, $refereeId, $amount) {
        $now = $this->now;

        $sql = "UPDATE referrals SET earning = earning + $amount, updated = $now WHERE user_id = $userId AND referee_id = $refereeId";
        $this->db->query($sql);

        return TRUE;
    }

    public function UpdateStatus($userId, $refereeId) {

        $sql = "UPDATE referrals SET status = 0 WHERE user_id = $refereeId AND referee_id = $userId";
        $this->db->query($sql);

        return TRUE;
    }


    public function get($userId, $free = TRUE) {

        if (!$free) $this->db->where('u.account_level >', 0);

        if (is_array($userId)) {
            $this->db->where('u.id IN ('.implode(",", $userId).')');
        } else {
            $this->db->where('u.referrer_id', $userId);
        }

        return $this->db->select('u.*, count(u1.id) referrals', FALSE)
                ->from('users u')
                ->join('users u1', 'u1.referrer_id = u.id and u1.active=1 and u1.deleted=0', 'LEFT')
                ->where('u.active', 1)
                ->where('u.deleted', 0)
                ->where('u.locked', 0)
                ->where('u.banned', 0)
                ->where('u.soft_hide', 0)
                ->group_by('u.id')
                ->order_by('referrals', 'desc')
                ->get()->result();
    }

    public function getReferralTotals($userId) {
        return $this->db->select('COUNT(r.referee_id) count, SUM(r.earning) earnings', FALSE)
                        ->from('referrals r')
                        ->where('r.user_id', $userId)
                        ->get()
                        ->row();
    }

    public function getSearchList($userId) {
        if (($result = $this->getCache('ref_list'.$userId, NULL, CACHE_FIVE_MINUTES)) === FALSE) {
            $this->refList = array();
            $this->getList($userId, 1);
            $result = $this->refList;
            $this->saveCache($result);
        }
        return $result;
    }

    private function getList($userId, $level) {

        $users = $this->db->select('id, username, first_name, last_name')
                ->from('users')
                ->where('referrer_id', $userId)
                ->get()->result();

        foreach ($users as $u) {
            $this->refList[$u->id] = $u->first_name.' '.$u->last_name.' '.$u->username;
            if ($level < intval(MAX_REF_LEVELS)) $this->getList($u->id, $level + 1);
        }
    }

    public function getReferralList($userId, $level) {

            return $this->db->query("select
            u.id,
            u.username,
            u.first_name,
            u.last_name,
            r.earning,
            u.account_level,
            u.account_expires,
            u.created_on,
            u.email,
            u.avatar,
            u.salt,
            s.username upline,
            (select count(*) from users where referrer_id = u.id and active=1 and deleted=0) referrals
            FROM referrals r
            JOIN users u on r.referee_id = u.id
            JOIN users s on s.id = u.referrer_id
            WHERE r.user_id = $userId
            and r.level = $level
            ORDER BY s.username
            ")->result();

    }

    public function logAction()
    {
        // to be implemented ...
    }

    public function getCommissionTable($hideObsolete = TRUE)
    {
        if ($hideObsolete)
            $this->db->where('sorting > 0');

        $r = $this->db->from('referral_commission')
            ->order_by('sorting')
            ->get()
            ->result();

        if ($r) {
            foreach ($r as &$row) {
                $levels = (array)json_decode($row->levels);
                $i = 1;
                $row->levels = array();

                foreach ($levels as $l) {
                    $row->levels[$i++] = intval($l);
                }
            }

        }
        return $r;
    }

    public function getCount($userId, $level)
    {
        return $this->db->select('COUNT(r.referee_id) count, SUM(r.earning) earnings', FALSE)
            ->from('referrals r')
            ->join('users u', 'u.id = r.referee_id')
            ->where('r.level', $level)
            ->where('r.user_id', $userId)
            ->where('r.status', 1)
            ->where('u.active', 1)
            ->where('u.soft_hide', 0)
            ->get()
            ->row();
    }

    public function getCounts($userId) {
//    $ids = $this->db->query("SELECT id FROM users where active = 1 ")->result_array();
//        $my_array = array_column($ids, 'id');
//        foreach ($ids as $row) {
//            $newArray[] = $row['id']; //Add it to the new array
//        }
//        $mm =    implode(',',$newArray);
      $r =  $this->db->query("SELECT r.user_id,r.level,r.status, COUNT(r.referee_id) count, SUM(r.earning) earnings FROM referrals r
               LEFT JOIN users u ON r.referee_id = u.id  WHERE r.user_id=$userId AND (r.status = 1 AND u.active = 1) GROUP BY r.level ORDER BY r.level")->result();



//        $r = $this->db->select('r.user_id,r.level,r.status, COUNT(r.referee_id) count, SUM(r.earning) earnings',FALSE)
//                ->from('referrals r')
//                ->join('users u', 'r.referee_id = u.id ')
//                ->where('r.user_id', $userId)
//                ->where('r.status', 1)
//                ->where('u.active', 1)
//                ->where('u.soft_hide', 0)
//              //  ->where_in('r.user_id', array($mm))
//                ->group_by('r.level')
//                ->order_by('r.level')
//                ->get()
//                ->result();

        $result = array();
        foreach ($r as $row) $result[$row->level] = $row;

        return $result;

    }

    public function deleteEntry($userId, $refereeId, $level) {
        $this->db->where('user_id', $userId)
            ->where('referee_id', $refereeId)
            ->where('level', $level)
            ->delete('referrals');

        return TRUE;
    }

    public function deleteAllEntries($refereeId) {
        $this->db->where('referee_id', $refereeId)
                ->delete('referrals');

        return TRUE;
    }

    public function storeNewReferral($userId, $referrerId, $level)
    {
//        $data = array(
//            'referee_id' => $userId,
//            'user_id'    => $referrerId,
//            'level'      => $level,
//            'created'    => $this->now,
//            'updated'    => $this->now
//        );
//        $this->db->insert('referrals', $data);

        $sql = "INSERT INTO referrals (referee_id, user_id, level, earning, created, updated)
            VALUES ($userId, $referrerId, $level, 0, {$this->now}, {$this->now})
            ON DUPLICATE KEY UPDATE earning = earning";

        $this->db->query($sql);

        return ($this->db->affected_rows() == 1);
    }

    public function getPaid($payer, $payee) {
        return $this->db->select('sum(amount) total', FALSE)
                ->from('payment')
                ->where('payer_user_id', $payer)
                ->where('payee_user_id', $payee)
                ->where('approved IS NOT NULL')
                ->get()->row()->total;

    }

    public function getEarnings($userId) {
        $cacheKey = cacheKey("day_referrals_earning_{$userId}");

        $results = $this->cache->get($cacheKey);
        if (!$results) {
            $results = $this->db->select_sum('earning')
                                ->from('referrals')
                                ->where('user_id', $userId)
                                ->get()
                                ->row()
                    ->earning;

            $this->cache->save($cacheKey, $results, CACHE_THIRTY_SECONDS);
        }

        return $results;
    }
    public function getEarningsFromUser($userId, $refId) {
        $cacheKey = cacheKey("referrals_earning_{$userId}_{$refId}");

        $results = $this->cache->get($cacheKey);
        if (!$results) {
            $results = $this->db->select_sum('earning')
                                ->from('referrals')
                                ->where('referee_id', $refId)
                                ->where('user_id', $userId)
                                ->get()
                                ->row()
                    ->earning;

            $this->cache->save($cacheKey, $results, CACHE_THIRTY_SECONDS);
        }

        return $results;
    }

    public function getEarningsDay($userId, $date = NULL)
    {
        if (is_null($date))
            $endDate = strtotime(date('Y-m-d', $this->now));

        $cacheKey = cacheKey("day_referrals_earning_{$userId}_{$date}");

        $results = $this->cache->get($cacheKey);
        if (!$results)
        {
            $results = $this->db->select_sum('amount')
                ->from('referral_history')
                ->where('user_id', $userId)
                ->where('date <', $endDate)
                ->get()
                ->row()
                ->amount;

            $this->cache->save($cacheKey, $results, CACHE_THIRTY_SECONDS);
        }

        return $results;
    }

    public function getReferralsCountDate($referrerId, $date = NULL)
    {
        if (!$date)
            $date = strtotime(date('Y-m-d', $this->now));

        $cacheKey = cacheKey("day_referrals_count_{$referrerId}_{$date}");

        $results = $this->cache->get($cacheKey);
        if (!$results)
        {
            $endDate = $date + 24 * 3600;

            $results = $this->db->from('users')
                ->where('referrer_id', $referrerId)
                ->where('created_on >=', $date)
                ->where('created_on <', $endDate)
                ->where('active', 1)
                ->count_all_results();

            $this->cache->save($cacheKey, $results, CACHE_THIRTY_SECONDS);
        }

        return $results;
    }

    public function getInvites($userId) {
        return $this->db->from('invite')
                ->where("(user_id =  $userId OR sponsor_user_id = $userId)")
                ->where('referral_user_id IS NULL')
                ->where('date > ', $this->now - INVITE_EXPIRATION*CACHE_ONE_HOUR)
                ->get()->result();
    }

    public function getInvite($code="5fbf03ec0b") {
        return $this->db->from('invite i')
                ->where("i.referral_user_id IS NULL")
                ->where("i.activation_code", $code)
                ->where('i.date > ', $this->now - INVITE_EXPIRATION*CACHE_ONE_HOUR)
                ->get()->row();
    }

    public function checkInviteEmail($email) {
        return $this->db->from('invite')
                ->where('email', $email)
                ->get()->num_rows();
    }






}