<?php
class My_account_model extends MY_Model {
    private $adsLevels;

    public function __construct() {
        parent::__construct();
    }

    public function getMemberships() {

        if (($result = $this->getCache('memberships')) === FALSE) {
            $pkgs = $this->db->where('status', 1)
                    ->where('category', 'membership')
                    ->order_by('code')
                    ->get('purchase_item')
                    ->result();

            $result = array();
            foreach ($pkgs as $p) $result[$p->code] = $p;

            $this->saveCache($result);
        }

        return $result;
    }

    public function getMembershipSettings() {
        $pkgs = $this->db->from('memberships')
                ->get()
                ->result();

        $memberships = array();
        foreach ($pkgs as $p) $memberships[$p->code] = $p;
        return $memberships;
    }

    public function getMaxAds($level) {
        $p = $this->db->where('code', $level)->get('purchase_item')->row();

        return $p->max_ads;
    }

    public function getShareItems() {
        $blocks = $this->db->where('status', 1)
                ->where('category', 'shares')
                ->get('purchase_item')
                ->result();

        $items = array();
        foreach ($blocks as $p) $items[$p->code] = $p;
        return $items;
    }

    public function getCreditPackages() {
        $pkgs = $this->db->where('status', 1)
                ->where('category', 'advertising')
                ->get('purchase_item')
                ->result();

        $items = array();
        foreach ($pkgs as $p) $items[$p->code] = $p;
        return $items;
    }

    public function getVacationPackages() {
        $pkgs = $this->db->where('status', 1)
                ->where('category', 'vacation')
                ->get('purchase_item')
                ->result();

        $items = array();
        foreach ($pkgs as $p) $items[$p->code] = $p;
        return $items;
    }

    public function getSurfSettings() {
        return $this->db->get('surf_settings')
                ->result();
    }

    public function getSurfSetting($level) {
        return $this->db->where('account_level', $level)
                ->get('surf_settings')
                ->row();
    }

    public function getMembership($code) {
        return $this->db->where('code', $code)->get('purchase_item')->row();
    }

    public function getPurchaseItem($code) {
        return $this->db->where('code', $code)->get('purchase_item')->row();
    }

    public function addOrder($order) {
        $this->db->insert('purchase_order', $order);
    }

    public function getOrders($userId, $status) {
        return $this->db
                ->select('p.*, m.name payment_method, i.title, i.code, i.category, i.ad_credits, i.duration, i.te_credits, i.banner_credits')
                ->join('purchase_item i', 'i.id=p.purchase_item_id')
                ->join('payment_method m', 'm.code=p.method')
                ->where('user_id', $userId)
                ->where('p.status', $status)
                ->order_by('p.created', 'desc')
                ->get('purchase_order p')->result();
    }

    public function getOrder($id) {
        return $this->db
                ->select('p.*, m.name payment_method, i.title, i.code, i.category, i.ad_credits, i.duration, i.te_credits, i.banner_credits')
                ->join('purchase_item i', 'i.id=p.purchase_item_id')
                ->join('payment_method m', 'm.code=p.method')
                ->where('p.id', $id)
                ->get('purchase_order p')->row();
    }

    public function completeOrders($userId) {
        $this->db->set('status', 'complete')
                ->where('user_id', $userId)
                ->where("status", 'processing')
                ->update('purchase_order');
    }

    public function cancelOrders($userId) {
        $orders = $this->db->from('purchase_order')
                ->where('user_id', $userId)
                ->where("status IN ('pending', 'processing')", NULL, FALSE)
                ->get()->result();

        if ($orders) {
            $this->db->where('user_id', $userId)
                    ->where("status IN ('pending', 'processing')", NULL, FALSE)
                    ->update('purchase_order', array('status' => 'cancelled', 'updated' => $this->now));
        }

        return $orders;
    }

    public function processOrders($userId, $transactionId) {
        $this->db->where('user_id', $userId)
                ->where("status", 'pending')
                ->update('purchase_order', array(
                    'status'         => 'processing',
                    'updated'        => $this->now,
                    'transaction_id' => $transactionId
                ));
    }

    public function countOrders($type) {
        return $this->db->from('purchase_order p')
                ->where("purchase_item_id IN (SELECT id FROM purchase_item WHERE code= '$type')", NULL, FALSE)
                ->join("transaction t", "t.id = p.transaction_id AND t.status = 'ok'")
                ->where('p.status', 'complete')
                ->count_all_results();
    }

    public function countUpgrades() {
        return $this->db->from('purchase_order p')
                ->join("transaction t", "t.id = p.transaction_id AND t.status = 'ok'")
                ->where("purchase_item_id IN (SELECT id FROM purchase_item WHERE category= 'membership')", NULL, FALSE)
                ->where('p.status', 'complete')
                ->count_all_results();
    }

    public function countUpgradesByDay() {

        return $this->db->query("SELECT (p.approved DIV (24*3600)) * (24*3600) day, count(*)  c FROM payment p
                                WHERE  p.approved IS NOT NULL
                                GROUP BY day ORDER BY day")
                ->result();
    }

    public function sumAdPurchases() {
        return $this->db->select("SUM(p.amount*p.qty) total", FALSE)
                ->from('purchase_order p')
                ->join("transaction t" , "t.id = p.transaction_id AND t.status = 'ok'")
                ->where("purchase_item_id IN (SELECT id FROM purchase_item WHERE category= 'advertising')", NULL, FALSE)
                ->where('p.status', 'complete')
                ->get()->row()->total;
    }

    public function sumAdPurchasesByDay() {

        return $this->db->query("SELECT (p.updated DIV (24*3600)) * (24*3600) day, sum(p.amount*p.qty) c FROM purchase_order p
                                JOIN transaction t ON t.id = p.transaction_id AND t.status = 'ok'
                                WHERE  p.status = 'complete'
                                AND p.purchase_item_id IN (SELECT id FROM purchase_item WHERE category = 'advertising')
                                GROUP BY day ORDER BY day")
                ->result();
    }


    public function accountSummary($userId) {
        $this->cacheOverride = TRUE;

        $cacheKey = cacheKey("account_summary_$userId");

        if (($results = $this->getCache($cacheKey)) === FALSE) {

            $results                        = array(); // = new stdClass();
            $results['Referral Commission'] = $this->db->select_sum('gross_amount')
                    ->from('transaction')
                    ->where('type', 'ref_comm')
                    ->where('status', 'ok')
                    ->where('user_id', $userId)
                    ->get()->row()->gross_amount;

            $results['Dividends Earned'] = $this->db->select_sum('gross_amount')
                    ->from('transaction')
                    ->where('type', 'dividend')
                    ->where('status', 'ok')
                    ->where('user_id', $userId)
                    ->get()->row()->gross_amount;

            $results['TE Earning']       = $this->db->select_sum('gross_amount')
                    ->from('transaction')
                    ->where('type', 'earning')
                    ->where("item_code LIKE 'surf%'", NULL, FALSE)
                    ->where('status', 'ok')
                    ->where('user_id', $userId)
                    ->get()->row()->gross_amount;

            $results['Withdrawals'] = $this->db->select_sum('gross_amount')
                    ->from('transaction')
                    ->where('type', 'cashout')
                    ->where('status', 'ok')
                    ->where('user_id', $userId)
                    ->get()->row()->gross_amount;

            $results['Upgrades'] = $this->db->select_sum('gross_amount')
                    ->from('transaction')
                    ->where('type', 'deposit')
                    ->where('item_code', 'membership')
                    ->where('status', 'ok')
                    ->where('user_id', $userId)
                    ->get()->row()->gross_amount;

            $results['Ad Purchases'] = $this->db->select_sum('gross_amount')
                    ->from('transaction')
                    ->where('type', 'deposit')
                    ->where('item_code', 'advertising')
                    ->where('status', 'ok')
                    ->where('user_id', $userId)
                    ->get()->row()->gross_amount;

            $results['Share Purchases'] = $this->db->select_sum('gross_amount')
                    ->from('transaction')
                    ->where('type', 'share_purchase')
                    ->where('status', 'ok')
                    ->where('user_id', $userId)
                    ->get()->row()->gross_amount;

            $results['Share Sales'] = $this->db->select_sum('gross_amount')
                    ->from('transaction')
                    ->where('type', 'share_sale')
                    ->where('status', 'ok')
                    ->where('user_id', $userId)
                    ->get()->row()->gross_amount;


            $this->saveCache($results);
        }
        return $results;
    }

    public function balanceSummary($userId) {
        $sql = "SELECT u.username, u.account_level, u.account_expires, t.type, t.item_code, count(*) c, sum(t.gross_amount) total
                FROM `transaction` t
                JOIN users u ON u.id = t.user_id
                WHERE user_id in (2159,2025,2876,2928)
                GROUP BY t.type
                ORDER BY u.username";
    }

    public function multipleUpgrades() {
        $sql = "SELECT u.username, p.description, p.amount, p.method, p.total, p.discount, p.apply_balance, from_unixtime(p.updated), from_unixtime(u.account_expires) FROM users u
                JOIN purchase_order p ON p.user_id = u.id
                JOIN (SELECT user_id  FROM transaction t
                where type='deposit' and item_code='membership' and status ='ok'
                and gross_amount > 6

                group by user_id
                having count(*) > 1) t ON t.user_id = u.id

                WHERE p.description like '%Membership%'
                and p.status = 'complete'
                ORDER BY u.id
                ";
    }

    public function tokenAudit() {

        $sql = "
            SELECT t.user_id,  u.username, count(t.id) actual_tokens, tr.tokens surf_tokens, ifnull(p.tokens, 0) purchase_tokens, count(t.id) - (ifnull(p.tokens, 0) + tr.tokens) diff

            FROM `token` t

            LEFT JOIN (SELECT user_id, count(id) DIV 25 tokens FROM
            transaction where  type='earning' group by user_id ) tr on tr.user_id = t.user_id

            LEFT JOIN (SELECT po.user_id, sum(po.total-po.fee) DIV 10 tokens  FROM  purchase_order po
            JOIN transaction t ON t.id = po.transaction_id where purchase_item_id = 16 AND t.status = 'ok' group by po.user_id) p on p.user_id = t.user_id

            JOIN users u on u.id = t.user_id

            group by t.`user_id`

            having (purchase_tokens + surf_tokens) != actual_tokens";

        return $this->db->query($sql)->result();

    }
    public function monitorSummary($userId) {
        $this->cacheOverride = TRUE;

        $cacheKey = cacheKey("monitor_summary_$userId");

        if (($results = $this->getCache($cacheKey)) === FALSE) {

            $results = new stdClass();

            $results->programCount = $this->db->select("count(distinct listing_id) num_programs", FALSE)
                    ->from('monitor_investment i')
                    ->where('i.user_id', $userId)
                    ->get()->row()->num_programs;

            $results->programActiveCount = $this->db->select("count(distinct listing_id) num_programs", FALSE)
                    ->from('monitor_investment i')
                    ->join('monitor_listing m', 'm.id = i.listing_id')
                    ->where('i.user_id', $userId)
                    ->where('i.status !=', 'Pending')
                    ->where('m.status', 'Active')
                    ->get()->row()->num_programs;


            $results->programClosedCount = $this->db->select("count(distinct listing_id) num_programs", FALSE)
                    ->from('monitor_investment i')
                    ->join('monitor_listing m', 'm.id = i.listing_id')
                    ->where('i.user_id', $userId)
                    ->where('i.status !=', 'Pending')
                    ->where('m.status', 'Closed')
                    ->get()->row()->num_programs;

            $results->programProblemCount = $this->db->select("count(distinct listing_id) num_programs", FALSE)
                    ->from('monitor_investment i')
                    ->join('monitor_listing m', 'm.id = i.listing_id')
                    ->where('i.user_id', $userId)
                    ->where('i.status !=', 'Pending')
                    ->where('m.status', 'Problem')
                    ->get()->row()->num_programs;

            $results->invest = $this->db->select("sum(i.amount) programTotal, m.name, m.id, m.status")
                    ->from('monitor_investment i')
                    ->join('monitor_listing m', 'm.id = i.listing_id')
                    ->where('i.user_id', $userId)
                    ->where('i.status', 'confirmed')
                    ->group_by('m.id')
                    ->get()->result();

            $results->cashout = $this->db->select("sum(i.amount) programTotal, m.name, m.id, m.status")
                    ->from('monitor_cashout i')
                    ->join('monitor_listing m', 'm.id = i.listing_id')
                    ->where('i.user_id', $userId)
                    ->where('i.status', 'confirmed')
                    ->group_by('m.id')
                    ->get()->result();

            $results->investStatusTotal  = array(
                'Active'  => 0,
                'Problem' => 0,
                'Closed'  => 0
            );
            $results->investStatusCount  = array(
                'Active'  => 0,
                'Problem' => 0,
                'Closed'  => 0
            );
            $results->cashoutStatusTotal = array(
                'Active'  => 0,
                'Problem' => 0,
                'Closed'  => 0
            );
            $results->cashoutStatusCount = array(
                'Active'  => 0,
                'Problem' => 0,
                'Closed'  => 0
            );
            $results->investTotal        = $results->cashoutTotal = 0;
            foreach ($results->invest as $invest) {
                $results->investStatusTotal[$invest->status] += $invest->programTotal;
                $results->investStatusCount[$invest->status]++;
                $results->investTotal += $invest->programTotal;
            }
            foreach ($results->cashout as $cashout) {
                $results->cashoutStatusTotal[$cashout->status] += $cashout->programTotal;
                $results->cashoutStatusCount[$cashout->status]++;
                $results->cashoutTotal += $cashout->programTotal;
            }

            $this->saveCache($results);
        }
        return $results;
    }

    //Profile data
    public function profile($userId) {
        //-------Personal Information-----
        $firstEmailChange  = $this->db->from('user_changes')
                ->where('user_id', $userId)
                ->where('field', 'email')
                ->order_by('id')
                ->limit(1)
                ->get()
                ->row();
        $data['reg_email'] = $firstEmailChange ? $firstEmailChange->old_value : '';

//        $country = $this->db->from('country')
//                ->where('id', $user->country_id)
//                ->limit(1)
//                ->get()
//                ->row();
//
//        $user->country = (!empty($country)) ? $country->country_name : '';
//        $old_country = $user->country;
//
//        $firstCountryChange = $this->db->from('user_changes')
//                ->where('user_id', $userId)
//                ->where('field', 'country_id')
//                ->order_by('id')
//                ->limit(1)
//                ->get()
//                ->row();
//        if($firstCountryChange) {
//            $country = $this->db->from('country')
//                    ->where('id', $firstCountryChange->old_value)
//                    ->limit(1)
//                    ->get()
//                    ->row();
//            $old_country = (!empty($country)) ? $country->country_name : '';
//        }
//
//        $data['reg_country'] = $old_country;
//

        //-------Account Security---
        // Written hidden because we want them to know we have their datas
        // but we don't want to show them, :D
        //Take the last 5 DIFFERENTS IPs from the user:

        $this->load->model('user_model', 'User');
        $data['logIPs'] = $this->User->getLoginIPs($userId);

        return $data;
    }
    public function surf_vacation($user_id) {
        return $this->db->select_max('vacation_ends')->where('user_id', $user_id)->get('surf_vacation',1)->row();

    }
}