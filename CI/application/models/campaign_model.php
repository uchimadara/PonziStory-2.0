<?php
class Campaign_model extends MY_Model
{
    private $placement = NULL;

    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $data['created'] = $this->now;
        $data['updated'] = $this->now;

        $this->db->insert('campaign', $data);

        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)
            ->update('campaign', $data);

        return TRUE;
    }

    public function updateTextAd($id, $data) {
        $this->db->where('id', $id)
                 ->update('text_ad', $data);

        return TRUE;
    }

    public function updateAd($id, $table, $data) {
        $data['updated'] = $this->now;
        $this->db->where('id', $id)
                 ->update($table, $data);

        return TRUE;
    }

    public function addCredits($id, $credits, $table) {
        $this->db->query("UPDATE $table SET credits = credits + $credits WHERE id = $id");
        return TRUE;
    }

    public function getTextAd($id) {
        return $this->db->from('text_ad')->where('id', $id)->get()->row();
    }

    public function getAd($id, $table) {
        return $this->db->from($table)->where('id', $id)->get()->row();
    }

    public function countAds($userId, $table) {
        return $this->db->from($table)
                ->where('user_id', $userId)
                ->where('status !=', 'rejected')
                ->count_all_results();
    }


    public function getTextAdStats($userId = NULL) {
        $where = ($userId) ? " AND user_id = $userId " : '';
        $r = $this->db->query("
            SELECT count(*) total_ads, sum(impressions) views, sum(clicks) clicks, sum(credits) credits
            FROM text_ad
            WHERE status = 'approved' $where
        ")->result();

        if ($r) return $r[0];

        return FALSE;

    }

    public function getAdStats($table, $userId = NULL) {
        $where = ($userId) ? " AND user_id = $userId " : '';
        $r     = $this->db->query("
            SELECT count(*) total_ads, sum(impressions) views, sum(clicks) clicks, sum(credits) credits
            FROM $table
            WHERE status = 'approved' $where
        ")->result();

        if ($r) return $r[0];

        return FALSE;
    }

    public function unallocatedCredits() {

        return $this->db->select_sum('ad_credits')
                    ->from('users')
                    ->where('active', 1)
                    ->where('deleted', 0)
                    ->get()
                    ->row()
                    ->ad_credits;

    }

    public function clickTextAd($id, $userId) {

        $this->db->query("UPDATE text_ad SET clicks=clicks+1 WHERE id = $id");
        $this->db->insert('text_ad_click', array(
            'user_id' => $userId,
            'text_ad_id' => $id,
            'date' => now()
        ));
        return $this->db->from('text_ad')->where('id', $id)->get()->row();
    }

    public function clickAd($table, $id, $userId) {

        $this->db->query("UPDATE $table SET clicks=clicks+1 WHERE id = $id");
        $this->db->insert($table.'_click', array(
            'user_id'    => $userId,
            $table.'_id' => $id,
            'date'       => now()
        ));
        return $this->db->from($table)->where('id', $id)->get()->row();
    }

    public function getPlacement($type, $group, $position, $size) {

        $r = $this->db->from('ad_placement')
                ->where('type', $type)
                ->where('group', $group)
                ->where('position', $position)
                ->where('size', $size)
                ->get();

        if ($r) $this->placement = $r->row();

        return $this->placement;
    }

    public function getPlacementAds($table, $group, $position, $size, $userId) {

        $ads = array();
        $bIndex = "$table-$group-$position-$size";
        $last = $this->db->from('settings')->where("name", $bIndex)->get()->row()->value;

        if (empty($last)) $last = -1;

        $r = $this->db->from("$table b")
                ->where('ad_placement_id', $this->placement->id)
                ->where('impressions < credits', NULL, FALSE)
                ->where("b.id > $last", NULL, FALSE)
                ->where('b.status', 'approved')
                ->order_by('b.id')
                ->limit($this->placement->ads_limit)
                ->get();

        if ($r) {
            $ads = $r->result();

            if (($c = count($ads)) < $this->placement->ads_limit) {
                $last = -1;
                $ads  = array_merge($ads,
                    $this->db->from("$table b")
                            ->where('impressions < credits', NULL, FALSE)
                            ->where('ad_placement_id', $this->placement->id)
                            ->where("b.id > $last", NULL, FALSE)
                            ->where('b.status', 'approved')
                            ->order_by('b.id')
                            ->limit($this->placement->ads_limit - $c)
                            ->get()->result()
                );
            }
        }

        if (!empty($ads)) {
            $view['user_id'] = $userId;
            $view['date'] = $this->now;
            foreach ($ads as $ad) {
                $view[$table.'_id'] = $last = $ad->id;
                $this->db->query("UPDATE $table SET impressions=impressions+1 WHERE id = {$ad->id}");
                $this->db->insert($table."_impression", $view);
            }
            $this->db->where("name", $bIndex)->update('settings', array('value' => $last));
        }
        return $ads;
    }

    public function getAds($table, $size, $count, $userId) {

        $ads    = array();
        $bIndex = "$table-$size";
        $r      = $this->db->from('settings')->where("name", $bIndex)->get();

        $last = ($r->num_rows() > 0) ? $r->row()->value : 0;

        if ($size != 'text') $this->db->where('size', $size);
        $a = $this->db->from("$table b")
                ->where('impressions < credits', NULL, FALSE)
                ->where("b.id > $last", NULL, FALSE)
                ->where("b.user_id !=", $userId)
                ->where('b.status', 'approved')
                ->order_by('b.id')
                ->limit($count)
                ->get();

        if ($a->num_rows() > 0) {
            $ads = $a->result();
        }

        if (($c = count($ads)) < $count) { //$this->placement->ads_limit) {
            $last = 0;
            if ($size != 'text') $this->db->where('size', $size);
            $ads = array_merge($ads,
                $this->db->from("$table b")
                        ->where('impressions < credits', NULL, FALSE)
                        ->where("b.id > $last", NULL, FALSE)
                        ->where("b.user_id !=", $userId)
                        ->where('b.status', 'approved')
                        ->order_by('b.id')
                        ->limit($count - $c)
                        ->get()->result()
            );
        }

        if (!empty($ads)) {
            $view['user_id'] = $userId;
            $view['date']    = $this->now;
            foreach ($ads as $ad) {
                $view[$table.'_id'] = $last = $ad->id;
                $this->db->query("UPDATE $table SET impressions=impressions+1 WHERE id = {$ad->id}");
                $this->db->insert($table."_impression", $view);
            }

            if ($ad) {
                if ($r->num_rows() > 0) {
                    $this->db->where("name", $bIndex)->update('settings', array('value' => $last));
                } else {
                    $this->db->insert('settings', array(
                        'module' => 'admin',
                        'label' => 'last '.$table.' '.$size,
                        'description' => 'id of last '.$table.' displayed',
                        'name' => $bIndex,
                        'value' => $last,
                        'format' => 'int',
                        'date' => $this->now
                    ));
                }
            }

        }
        return $ads;
    }

    public function getCampaignBanner() {
        $last       = 0;
        $r          = $this->db->select('value')
                ->from('settings')
                ->where('name', 'last_campaign')
                ->get();

        if ($r->num_rows() > 0) {
            $last = $r->row()->value;
        }

        $day = $this->now - ($this->now % CACHE_ONE_DAY);

        $ad = $this->db->select('s.id, c.image, c.target_url')
                ->from('campaign c')
                ->join('campaign_slot s', 's.campaign_id = c.id')
                ->where('s.time_slot', $day)
                ->where('s.id >', $last)
                ->where('c.status', 'approved')
                ->order_by('s.id')
                ->limit(1)
                ->get()
                ->row();

        if (!$ad) {
            $last = 0;
            $ad   = $this->db->select('s.id, c.image, c.target_url')
                    ->from('campaign c')
                    ->join('campaign_slot s', 's.campaign_id = c.id')
                    ->where('s.time_slot ', $day)
                    ->where('s.id >', $last)
                    ->where('c.status', 'approved')
                    ->order_by('id')
                    ->limit(1)
                    ->get()
                    ->row();
        }

        if ($ad) {
            $last = $ad->id;
            $this->db->query("UPDATE campaign_slot SET impressions=impressions+1 WHERE id = {$last}");
        }
        $this->db->set('value', $last)
                ->where('name', 'last_campaign')
                ->update('settings');

        return $ad;
    }

    public function getBanner() {
        
        $last = 0;
        $r = $this->db->select('value')
                    ->from('settings')
                    ->where('name', 'last_banner')
                    ->get();
        if ($r->num_rows() > 0) $last = $r->row()->value;

        $ad = $this->db->from('banner')
                ->where('impressions < credits', NULL, FALSE)
                ->where('id >', $last)
                ->where('status', 'approved')
                ->order_by('id')
                ->limit(1)
                ->get()
                ->row();

        if (!$ad) {
            $last = 0;
            $ad = $this->db->from('banner')
                    ->where('impressions < credits', NULL, FALSE)
                    ->where('id >', $last)
                    ->where('status', 'approved')
                    ->order_by('id')
                    ->limit(1)
                    ->get()
                    ->row();
        }

        if ($ad) {
            $last = $ad->id;
            $this->db->query("UPDATE banner SET impressions=impressions+1 WHERE id = $last");
        }
        $this->db->set('value', $last)
                ->where('name', 'last_banner')
                ->update('settings');

        return $ad;
    }


    public function deleteTextAd($id) {
        $this->db->where('id', $id)->delete('text_ad');
        $this->db->where('text_ad_id', $id)->delete('text_ad_click');
        $this->db->where('text_ad_id', $id)->delete('text_ad_impression');
    }

    public function deleteBannerAd($id) {
        $this->db->where('id', $id)->delete('banner');
        $this->db->where('banner_id', $id)->delete('banner_click');
        $this->db->where('banner_id', $id)->delete('banner_impression');
    }

    public function countTextAdsByDay($userId) {
        $where = ($userId) ? " AND user_id = $userId " : "";

        return $this->db->query("select (created div (24*3600)) * (24*3600) day, count(*)  c from text_ad
                                 WHERE status = 'approved' $where group by day order by day")
                        ->result();
    }

    public function countBannerByDay($userId) {
        $where = ($userId) ? " AND user_id = $userId " : "";

        return $this->db->query("select (created div (24*3600)) * (24*3600) day, count(*)  c from banner_ad
                                 WHERE status = 'approved' $where group by day order by day")
                        ->result();
    }

    public function countAdViewsByDay($adId, $table) {
        $where = " WHERE {$table}_id = $adId "; //($userId) ? " JOIN banner b ON b.id = bai.banner_ad_id WHERE b.user_id = $userId " : "";

        $r = $this->db->query("select (bai.`date` div (24*3600)) * (24*3600) day, count(bai.id)  c from {$table}_impression bai
                                 $where group by day order by day");

       // echo $this->db->last_query();
        if ($r) return $r->result();

        return FALSE;
    }

    public function countAdClicksByDay($adId, $table) {
        $where = " WHERE {$table}_id = $adId "; //($userId) ? " JOIN banner b ON b.id = bai.banner_ad_id WHERE b.user_id = $userId " : "";

        $r = $this->db->query("select (bai.`date` div (24*3600)) * (24*3600) day, count(bai.id)  c from {$table}_click bai
                                 $where group by day order by day");

        if ($r) return $r->result();

        return FALSE;
    }

    public function getCount($userId = '', $type='')
    {
        $this->db->from('campaign')->where('status', 'enabled');

        if ($userId != '') $this->db->where('user_id', $userId);
        if ($type   != '') $this->db->where('type', $type);

        return $this->db->count_all_results();
    }

    public function getSubset($userId, $type = '', $page = 1, $perPage = 10)
    {
        $start = ($page - 1) * $perPage;

        switch ($type)
        {
            case 'fixed':
                //$this->db->select('c.*, COUNT(cs.id) slots', false)
                //    ->join('campaign_slot cs', 'cs.campaign_id = c.id', 'left');
                //break;

            case 'auction':
                $this->db->select('c.*, COUNT(cb.id) bids', FALSE)
                    ->join('campaign_bid cb', 'cb.campaign_id = c.id', 'left');
        }

        $this->db->from('campaign c')
            ->where('c.user_id', $userId)
            ->where('c.status', 'enabled')
            ->group_by('c.id')
            ->order_by('c.id')
            ->limit($perPage, $start);

        if ($type != '') $this->db->where('c.type', $type);

        return  $this->db->get()->result();
    }

    public function get($id)
    {
        return $this->db->from('campaign')
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function getSlot($id)
    {
        return $this->db->select('cs.*, c.target_url')
            ->from('campaign_slot cs')
            ->join('campaign c', 'c.id = cs.campaign_id')
            ->where('cs.id', $id)
            ->get()
            ->row();
    }

    public function getBid($id)
    {
        return $this->db->select('cb.campaign_id, c.target_url')
            ->from('campaign_bid cb')
            ->join('campaign c', 'c.id = cb.campaign_id')
            ->where('cb.id', $id)
            ->get()
            ->row();
    }

    public function getFixedPurchaseLogCount($id)
    {
        return $this->db->from('campaign_slot')
            ->where('campaign_id', $id)
            ->count_all_results();
    }

    public function getFixedPurchaseLogSubset($id, $page = 1, $perPage = 10)
    {
        $start = ($page - 1) * $perPage;

        return $this->db->select('cs.*, cp.price')
            ->from('campaign_slot cs')
            ->join('campaign_purchase cp', 'cp.id = cs.purchase_id')
            ->where('cs.campaign_id', $id)
            ->order_by('cs.time_slot', 'DESC')
            ->limit($perPage, $start)
            ->get()
            ->result();
    }

    public function slotAvailability($slot)
    {
        $row = $this->db->from('campaign_slot')
            ->where('time_slot', $slot)
            ->get()
            ->row();

        if ($row)
            return $row->campaign_id;

        return -1;
    }

    public function savePurchase($data, $slots)
    {
        if (count($slots) == 0) return FALSE;

        $data['date']   = $this->now;

        // Save the slots if available
        $slotsData = array();
        foreach ($slots as $slot)
        {
            if ($this->slotAvailability($slot) == -1)
            {
                $slotsData[] = array(
                    'campaign_id' => $data['campaign_id'],
                    'time_slot'   => $slot,
                    'created'     => $this->now
                );
            }
        }

        // Huho - no slots available
        if (count($slotsData) == 0)
            return FALSE;

        $data['slots']  = count($slotsData);
        $data['amount'] = $data['price'] * count($slotsData);

        $userId = $data['user_id'];
        $method = $data['method'];
        $amount = $data['amount'];

        if (!$this->Cashier->checkUserHasEnoughMoney($userId, $method, $amount))
            return FALSE;

        $this->Cashier->decreaseUserBalance($userId, $method, $amount);

        // Add to the History trail
        $balance      = $this->PaymentMethod->getBalance($userId, $method);
        $totalBalance = $this->ion_auth->user($userId)->row()->balance;

        $data['balance'] = $balance;
        $data['total']   = $totalBalance;

        $this->db->insert('campaign_purchase', $data);

        $purchaseId = $this->db->insert_id();

        $this->Referral->payReferrers($userId, 'adverts_fixed', $purchaseId, $amount);

        // Now we can save the slots
        foreach ($slotsData as &$slot)
            $slot['purchase_id'] = $purchaseId;

        $this->db->insert_batch('campaign_slot', $slotsData);

        return TRUE;
    }

    public function getAvailableAdvert($type = 'fixed')
    {
        switch ($type)
        {
            case 'fixed':

                $day = date('Y-m-d H:00', $this->now);

                return $this->db->select('b.id, b.campaign_id, b.bid as price, c.name, c.target_url, c.image')
                    ->from('campaign_bid b')
                    ->join('campaign c', 'c.id = b.campaign_id')
                    ->where('b.date', $day)
                    ->where('b.position IS NOT NULL')
                    ->order_by('bid', 'DESC')
                    ->limit(1)
                    ->get()
                    ->row();

            case 'auction':
                $day = date('Y-m-d', $this-d>now);

                return $this->db->select('b.id, b.campaign_id, b.bid, c.name, c.target_url, c.image')
                    ->from('campaign_bid b')
                    ->join('campaign c', 'c.id = b.campaign_id')
                    ->where('b.date', $day)
                    ->where('b.position IS NOT NULL')
                    ->order_by('bid', 'DESC')
                    ->get()
                    ->result();
        }
    }

    public function getCampaigns()
    {
        return $this->db->select('id')
            ->from('campaign')
            ->get()
            ->result();
    }

    public function getCampaignSlots()
    {
        return $this->db->select('id, campaign_id')
            ->from('campaign_slot')
            ->where('time_slot < ', $this->now) // + 1hour and 10min
            ->where('time_slot + 4200 > ', $this->now) // + 1hour and 10min
            ->get()
            ->result();
    }

    public function getCampaignBids($timestamp)
    {
        return $this->db->select('id, campaign_id')
            ->from('campaign_bid')
            ->where('timestamp', $timestamp)
            ->get()
            ->result();
    }

    public function updateCampaignSlotCounters($campaignId, $slotId, $impressions, $clicks)
    {
        $this->db->where('id', $slotId)
            ->where('campaign_id', $campaignId)
            ->set('impressions', $impressions)
            ->set('clicks', $clicks)
            ->update('campaign_slot');
    }

    public function updateCampaignBidCounters($campaignId, $bidId, $impressions, $clicks)
    {
        $this->db->where('id', $bidId)
            ->where('campaign_id', $campaignId)
            ->set('impressions', $impressions)
            ->set('clicks', $clicks)
            ->update('campaign_bid');
    }

    public function updateCampaignCounters($campaignId, $impLevel, $clickLevel)
    {
        $sql = "INSERT INTO campaign_statistics (campaign_id,
                                                 impressions,
                                                 clicks)
                     VALUES ($campaignId,
                             " . $this->db->escape(serialize($impLevel)) . ",
                             " . $this->db->escape(serialize($clickLevel)) . ")
            ON DUPLICATE KEY
                      UPDATE impressions = " . $this->db->escape(serialize($impLevel)) . ",
                             clicks      = " . $this->db->escape(serialize($clickLevel));
        $this->db->query($sql);
    }

    public function updateDailyCampaignCounters($campaignId, $date, $impressions, $clicks)
    {
        if ($impressions == 0 && $clicks == 0)
            return;

        $sql = "INSERT INTO campaign_statistics_daily (campaign_id,
                                                       day,
                                                       impressions,
                                                       clicks)
                     VALUES ($campaignId,
                             " . $this->db->escape($date) . ",
                             $impressions,
                             $clicks)
            ON DUPLICATE KEY
                      UPDATE impressions = $impressions,
                             clicks      = $clicks";
        $this->db->query($sql);
    }

    public function getClicksViews($campaignId, $table= 'text_ad')
    {
        $r           = array();
        $r['clicks'] = $this->db->select('count(*) c')
                                ->from($table.'_click c')
                                ->where($table.'_id', $campaignId)
                                ->group_by($table.'_id')
                                ->get()->row()->c;
        $r['views']  = $this->db->select('count(*) c')
                                ->from($table.'_impression i')
                                ->where($table.'_id', $campaignId)
                                ->group_by($table.'_id')
                                ->get()->row()->c;

        return $r;
    }

    public function getPurchasesCount($type, $date)
    {
        $end = strtotime("+1 day", $date);

        return $this->db->from('campaign_purchase cp')
            ->join('campaign c', 'c.id = cp.campaign_id')
            ->where('c.type', $type)
            ->where("cp.date BETWEEN $date AND $end")
            ->count_all_results();
    }

    public function getPurchasesSubset($type, $date, $page = 1, $perPage = 20)
    {
        $start = ($page - 1) * $perPage;

        $end = strtotime("+1 day", $date);

        return $this->db->select('cp.campaign_id, cp.slots, cp.price, cp.amount, cp.date, u.username')
            ->from('campaign_purchase cp')
            ->join('campaign c', 'c.id = cp.campaign_id')
            ->join('users u', 'u.id = cp.user_id')
            ->where("cp.date BETWEEN $date AND $end")
            ->where('c.type', $type)
            ->order_by('cp.date', 'DESC')
            ->limit($perPage, $start)
            ->get()
            ->result();
    }

    public function getAllCampaigns( $page = '', $perPage = '')
    {
        $this->db->select('c.*, u.username')
            ->from('campaign c')
            ->join('users u', 'u.id = c.user_id')
            ->order_by('c.id');

        if ($page != '' && $perPage != '') {
            $start = ($page - 1) * $perPage;
            $this->db->limit($perPage, $start);
        }

        return $this->db->get()->result();
    }


    public function addPrice($type, $price)
    {
        $this->db->set('type', $type)
            ->set('price', $price)
            ->set('date', $this->now)
            ->insert('campaign_price');
    }

    public function addImpressionValues($type, $impressionValues)
    {
        $this->db->set('type', $type)
            ->set('impression_values', $impressionValues)
            ->set('date', $this->now)
            ->insert('campaign_price');
    }

    public function getPrice($type, $before = NULL)
    {
        if ($before)
            $this->db->where('date < ', $before);

        return $this->db->from('campaign_price')
            ->where('type', $type)
            ->where('price IS NOT NULL')
            ->order_by('date', 'DESC')
            ->limit(1)
            ->get()
            ->row()
            ->price;
    }

    public function getImpressionValues()
    {
        $result =  $this->db->from('users_value_level')
            ->order_by('user_level', 'ASC')
            ->get()
            ->result();

        $values = array();
        foreach ($result as $row) $values[$row->user_level] = $row->start;

        return $values;
    }

    public function getReferralCommission($type, $campaignId)
    {
        $result = array();
        for ($i = 1; $i < 3; $i++)
        {
            switch ($type)
            {
                case 'fixed':
                    //$row = $this->db->select('SUM(r.amount) commission, u.username')
                    //    ->from('referral_history r')
                    //    ->join('campaign_purchase cp', 'cp.id = r.foreign_key')
                    //    ->join('users u', 'u.id = r.user_id')
                    //    ->where('cp.campaign_id', $campaignId)
                    //    ->where('r.type', 'adverts_fixed')
                    //    ->where('r.level', $i)
                    //    ->get()
                    //    ->row();

                    $row = $this->db->select('SUM(r.amount) commission, username')
                        ->from('referral_history r')
                        ->join('campaign_bid cb', 'cb.id = r.foreign_key')
                        ->join('users u', 'u.id = r.user_id')
                        ->where('cb.campaign_id', $campaignId)
                        ->where('r.type', 'topbanner')
                        ->where('r.level', $i)
                        ->get()
                        ->row();
                    break;

                case 'auction':
                    $row = $this->db->select('SUM(r.amount) commission, username')
                        ->from('referral_history r')
                        ->join('campaign_bid cb', 'cb.id = r.foreign_key')
                        ->join('users u', 'u.id = r.user_id')
                        ->where('cb.campaign_id', $campaignId)
                        ->where('r.type', 'banner_auction')
                        ->where('r.level', $i)
                        ->get()
                        ->row();

                    break;

            }

            if (!$row)
            {
                $row = $this->db->select('0 AS commission, u.username', FALSE)
                    ->from('users u')
                    ->join('referrals r', 'r.user_id = u.id')
                    ->join('campaign c', 'c.user_id = r.referee_id')
                    ->where('c.id', $campaignId)
                    ->where('r.level', $i)
                    ->get()
                    ->row();
            }

            $result['L' . $i] = $row;
        }

        return $result;
    }

    public function checkName($userId, $campaignName)
    {
        return $this->db->from('campaign')
            ->where('user_id', $userId)
            ->where('name', trim($campaignName))
            ->count_all_results() == 0;
    }

    public function getAuctionDays($type)
    {
        $now = $this->now;
        $result = array();

        for ($i = 0; $i < 30; $i++)
        {
            $day = $now + $i * (24 * 3600);
            $key = mktime(0, 0, 0, date('n', $day), date('j', $day), date('Y', $day));

            $result[$key] = $this->db->select('b.campaign_id, b.bid, b.user_id, c.name')
                ->from('campaign_bid b')
                ->join('campaign c', 'c.id = b.campaign_id')
                ->where('date', date('Y-m-d', $day))
                ->where('c.type', $type)
                ->order_by('bid', 'DESC')
                ->limit(3)
                ->get()
                ->result();
        }

        return $result;
    }

    public function getAuctionHours($type, $day, $start=0, $end=23)
    {
        //$now = strtotime($day);
        $result = array();

        $day = $day + ($start * 3600);

        for ($i = $start; $i <= $end; $i++)
        {
            //echo '$i='.$i.' day = '.date('Y-m-d H:i',$day).'<br />';

            $key = mktime($i, 0, 0, date('n', $day), date('j', $day), date('Y', $day));

            $result[$key] = $this->db->select('b.campaign_id, b.bid, b.user_id, c.name')
                ->from('campaign_bid b')
                ->join('campaign c', 'c.id = b.campaign_id')
                ->where('date', date('Y-m-d H:i', $day))
                ->where('c.type', $type)
                ->order_by('bid', 'DESC')
                ->limit(3)
                ->get()
                ->result();

            $day = $day + 3600;
        }

        return $result;
    }

    public function getMinimumBid($date, $type)
    {
        $row = $this->db->select_max('bid')
            ->from('campaign_bid')
            ->join('campaign', 'campaign.id=campaign_bid.campaign_id')
            ->where('campaign_bid.date', $date)
            ->where('campaign.type', $type)
            ->get()
            ->row();

        if (!$row || empty($row->bid))
          if ($type=='auction') return  0.5;
          else                  return .1;

        if ($type=='auction')
          return $row->bid + max($row->bid * 0.1, 0.5);
        else
          return $row->bid + max($row->bid * 0.25, 0.25);

    }

    public function correctCampaign($userId, $campaignId, $type)
    {
        return $this->db->from('campaign')
            ->where('id', $campaignId)
            ->where('user_id', $userId)
            ->where('type', $type)
            ->count_all_results() == 1;
    }

    public function addBid($type, $data, &$error)
    {
        $userId = $data['user_id'];
        $method = $data['method'];
        $amount = $data['bid'];

        if (!$this->correctCampaign($userId, $data['campaign_id'], $type))
            $error['campaign'] = '* incorrect';
        $min = $this->getMinimumBid($data['date'], $type);
        if ($amount < $min)
            $error['bid'] = '*too low. You bid '.money($amount).'. Minimum is '.money($min).'.';

        // Remove the money from the user's balance
        if (!$this->Cashier->checkUserHasEnoughMoney($userId, $method, $amount))
            $error['bid'] = '* insufficient funds';

        if ($error)
            return FALSE;

        $this->Cashier->decreaseUserBalance($userId, $method, $amount);

        // All good - carry on
        if ($type == 'auction')
          $data['timestamp'] = strtotime($data['date']);
        else
          $data['timestamp'] = strtotime(date('Y-m-d'), strtotime($data['date'].':00'));

        $data['created']   = $this->now;
        $data['updated']   = $this->now;

        $this->db->insert('campaign_bid', $data);
        $bidId = $this->db->insert_id();

        $balance      = $this->PaymentMethod->getBalance($userId, $method);
        $totalBalance = $this->ion_auth->user($userId)->row()->balance;

        $historyData = array(
            'foreign_key' => $bidId,
            'user_id'     => $userId,
            'status'      => 'bid',
            'amount'      => $amount,
            'balance'     => $balance,
            'total'       => $totalBalance,
            'type'        => $type,
            'date'        => $this->now
        );

        $this->History->add('campaign_bid_history', $historyData);

        $this->emailBidders($data['date'], $type);
        $this->refund($bidId, $data['date'], $type);
        $this->reorderBids($data['date'], $type);

        // Needed as the refund my have given the user some money back
        $balance      = $this->PaymentMethod->getBalance($userId, $method);
        $totalBalance = $this->ion_auth->user($userId)->row()->balance;

        return array(
            'paymentBalance'    => money($balance),
            'balance'           => money($totalBalance),
            'paymentBalanceAmt' => $balance,
            'balanceAmt'        => $totalBalance
        );
    }

    public function emailBidders($date, $type)
    {
        $lim = ($type == 'auction') ? 3 : 1;
        $bids = $this->db->select('cb.position, cb.timestamp, u.username, u.email, u.email_settings')
            ->from('campaign_bid cb')
            ->join('users u', 'u.id = cb.user_id')
            ->where('cb.date', $date)
            ->where('cb.status', 'active')
            ->where('cb.position IS NOT NULL')
            ->order_by('cb.bid', 'DESC')
            ->limit($lim)
            ->get()
            ->result();

        foreach ($bids as $bid)
        {
            $username   = $bid->username;
            $bidDate    = date('jS M, Y', $bid->timestamp);
            $bidLink    = ($type=='auction') ? SITE_ADDRESS."campaign/bid/$date.html" : SITE_ADDRESS."campaign/view_bids/".date('Y-m-d-H', $bid->timestamp);
            $refunded   = $bid->position == $lim;

            $this->EmailQueue->store($bid->email, 'Banner Auction Outbid Notification', 'emails/campaign/outbid', compact('username', 'bidDate', 'bidLink', 'refunded'));
        }
    }

    public function refund($bidId, $date, $type)
    {
        $lim = ($type == 'auction') ? 4 : 2;

        $bids = $this->db->from('campaign_bid')
            ->where('date', $date)
            ->where('status', 'active')
            ->order_by('bid')
            ->limit($lim)
            ->get()
            ->result();

        if (count ($bids) == $lim)
        {
            // Ok we have a bid that needs refunding
            $bid    = $bids[0];
            $userId = $bid->user_id;
            $refund = roundDown($bid->bid);
            $method = $bid->method;

            if ($method == 'lr') $method = 'eb';

            $this->Cashier->increaseUserBalance($userId, $method, $refund);

            $balance      = $this->PaymentMethod->getBalance($userId, $method);
            $totalBalance = $this->ion_auth->user($userId)->row()->balance;

            $historyData = array(
                'foreign_key' => $bid->id,
                'user_id'     => $userId,
                'status'      => 'refund',
                'amount'      => $refund,
                'balance'     => $balance,
                'total'       => $totalBalance,
                'type'        => $type,
                'date'        => $this->now
            );

            $this->History->add('campaign_bid_history', $historyData);

            $this->db->where('id', $bid->id)
                ->set('status', 'refunded')
                ->set('updated', $this->now)
                ->update('campaign_bid');

            $this->db->where('id', $bidId)
                ->set('deduction', $refund)
                ->update('campaign_bid');
        }
    }

    private function reorderBids($date, $type)
    {
        $this->db->set('position', NULL)
            ->where('date', $date)
            ->update('campaign_bid');

        $limit = ($type == 'auction') ? 3 : 1;

        $bids = $this->db->select('id')
            ->from('campaign_bid')
            ->where('date', $date)
            ->order_by('bid', 'DESC')
            ->limit($limit)
            ->get()
            ->result();

        $position = 1;
        foreach ($bids as $bid)
        {
            $this->db->set('position', $position)
                ->where('id', $bid->id)
                ->update('campaign_bid');

            $position++;
        }
    }

    public function getBids($date, $type)
    {
        return $this->db->select('b.bid, b.campaign_id, b.status, c.name, c.target_url, c.image, u.username')
            ->from('campaign_bid b')
            ->join('campaign c', 'c.id = b.campaign_id')
            ->join('users u', 'u.id = c.user_id')
            ->where('b.date', $date)
            ->where('c.type', $type)
            ->where('c.status', 'enabled')
            ->order_by('bid', 'DESC')
            ->get()
            ->result();
    }

    public function getBidsCount($id)
    {
        return $this->db->from('campaign_bid')
            ->where('campaign_id', $id)
            ->count_all_results();
    }

    public function getBidsSubset($id, $page = 1, $perPage = 10)
    {
        $start = ($page - 1) * $perPage;

        return $this->db->from('campaign_bid')
            ->where('campaign_id', $id)
            ->limit($perPage, $start)
            ->order_by('created', 'DESC')
            ->get()
            ->result();
    }

    public function closeAuction($type)
    {
      // close previous day/hour auction because status must remain active while on display
      // so referral payments are made at end of display time.

        $day = ($type == 'banner_auction')
             ? date('Y-m-d', $this->now - (24*3600))
             : date('Y-m-d H:00', $this->now - 3600);

        $bids = $this->db->from('campaign_bid')
            ->where('date', $day)
            ->where('status', 'active')
            ->where('position IS NOT NULL')
            ->get()
            ->result();

        // Pay the referrers
        foreach ($bids as $bid)
        {
            $userId = $bid->user_id;
            $bidId  = $bid->id;
            $amount = $bid->bid;

            $this->Referral->payReferrers($userId, $type, $bidId, $amount);
        }

        $this->db->set('status', 'closed')
            ->where('date', $day)
            ->where('status', 'active')
            ->update('campaign_bid');
    }

    public function getBidsCountByDate($date, $type)
    {
        $end = strtotime("+1 day", $date);

        return $this->db->from('campaign_bid cb')
            ->join('campaign c', 'c.id = cb.campaign_id')
            ->where('c.type', $type)
            ->where("cb.created BETWEEN $date AND $end")
            ->count_all_results();
    }

    public function getBidsSubsetByDate($date, $type, $page = 1, $perPage = 20)
    {
        $start = ($page - 1) * $perPage;

        $end = strtotime("+1 day", $date);

        return $this->db->select('cb.campaign_id, cb.date, cb.bid, cb.deduction, cb.created, u.username')
            ->from('campaign_bid cb')
            ->join('campaign c', 'c.id = cb.campaign_id')
            ->join('users u', 'u.id = cb.user_id')
            ->where("cb.created BETWEEN $date AND $end")
            ->where('c.type', $type)
            ->order_by('cb.created', 'DESC')
            ->limit($perPage, $start)
            ->get()
            ->result();
    }

    public function getBidsReferralsByDate($date, $type, $page = 1, $perPage = 20)
    {
        $start = ($page - 1) * $perPage;
        $end = strtotime("+1 day", $date);

        $r_type = ($type == 'auction') ? 'banner_auction' : 'topbanner';
        return $this->db->select('r.date, u.username, r.amount AS ref_amount, r.level, r.date as created, cb.campaign_id, cb.bid, cb.date')
            ->from('referral_history r')
            ->join('users u', 'u.id = r.user_id')
            ->join("campaign_bid cb", "cb.id = r.foreign_key")
            ->where('r.type', $r_type)
            ->where("r.date BETWEEN $date AND $end")
            ->order_by('cb.date ASC, level  ASC')
            ->limit($perPage, $start)
            ->get()
            ->result();
    }

    public function getRefPaymentCount($date, $type)
    {
        $end = strtotime("+1 day", $date);

        $r_type = ($type == 'auction') ? 'banner_auction' : 'topbanner';
        return $this->db->select('r.date, u.username, r.amount AS ref_amount, r.level, r.date as created, cb.campaign_id, cb.bid, cb.date')
            ->from('referral_history r')
            ->join('users u', 'u.id = r.user_id')
            ->join("campaign_bid cb", "cb.id = r.foreign_key")
            ->where('r.type', $r_type)
            ->where("r.date BETWEEN $date AND $end")
            ->order_by('cb.date ASC, level  ASC')
            ->count_all_results();
    }

    public function getEarningsDate($userId, $date = NULL)
    {
        if (!$date)
            $date = strtotime(date('Y-m-d', $this->now));

        $cacheKey = cacheKey("day_advertisement_earnings_{$userId}_{$date}");

        $results = $this->cache->get($cacheKey);
        if (!$results)
        {
            $endDate = $date + 24 * 3600;

            $refunds = $this->db->select_sum('amount')
                ->from('campaign_bid_history')
                ->where('user_id', $userId)
                ->where('status', 'refund')
                ->where('date >=', $date)
                ->where('date <', $endDate)
                ->get()
                ->row()
                ->amount;

            $bids = $this->db->select_sum('amount')
                ->from('campaign_bid_history')
                ->where('user_id', $userId)
                ->where('status', 'bid')
                ->where('date >=', $date)
                ->where('date <', $endDate)
                ->get()
                ->row()
                ->amount;

            //$fixed = $this->db->select_sum('amount')
            //    ->from('campaign_purchase')
            //    ->where('user_id', $userId)
            //    ->where('date >=', $date)
            //    ->where('date <', $endDate)
            //    ->get()
            //    ->row()
            //    ->amount;

            $results = $refunds - ($bids); // + $fixed);

            $this->cache->save($cacheKey, $results, CACHE_THIRTY_SECONDS);
        }

        return $results;
    }
}