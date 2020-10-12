<?php

class User_model extends MY_Model {

    private $limit24hours = 86400; // Limit to 24 hours

    public function __construct() {
        parent::__construct();
    }

    public function isChangeEmailLinkValid($hash, $user_id) {
        return $this->db->select('count(*) c', FALSE)
                                ->from('users')
                                ->where('email_change_code', $hash)
                                ->where('id', $user_id)
                                ->get()->row()->c;
    }

    public function getData($id, $d = array()) {
        foreach ($d as $field)
            $this->db->select($field);

        $this->db->where('users.id', $id);

        $row = $this->db->get('users')->row();

        if (empty($row))
            return FALSE;

        return $row;
    }

    public function fbUpdate($user_id, $fbname){
        // $cd = (int) $cd;

        $this->db->query("UPDATE users SET fbname = '$fbname' WHERE id = '$user_id' ");
        return TRUE;
    }

    public function ngupdate($user_id, $fbname){
        // $cd = (int) $cd;

        $this->db->query("UPDATE users SET ngnusername = '$fbname' WHERE id = '$user_id' ");
        return TRUE;
    }

    function fbNameCheck($userId){
        return $this->db->select('fbname')
            ->from('users')
            ->where('id', $userId)
            ->get()->row();
    }

    function ngNameCheck($userId){
        return $this->db->select('ngnusername')
            ->from('users')
            ->where('id', $userId)
            ->get()->row();
    }


    public function referral_lock($refID){
        $m = $this->db->select('username')
            ->from('users')
            ->where('id', $refID)
            ->where('locked', 1)
            ->get()->row();
        if($m){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getVisible($userId) {
        return $this->db->select('visible')
            ->from('users')
            ->where('id', $userId)
            ->get()->row();
    }

    public function getUserGroup($userId) {
        return $this->db->select('group_id')
            ->from('users_groups')
            ->where('user_id', $userId)
            ->get()->row();
    }

    public function upAll(){
      // $cd = (int) $cd;

        $this->db->query("UPDATE users SET account_expires = 1525560896 WHERE account_level = 1 ");
        return TRUE;
    }

    public function getLevelExpire() {

        return $this->db->select('account_expires')
            ->where('account_level', 1)
            ->where('deleted', 0)
            ->get('users')->result();
    }


    public function MaxRef($ref){

        $m = $this->db->select('referrer_id')
            ->from('users')
            ->where('referrer_id', $ref)
            ->where('active', 1)
            ->where('account_level >', 0)
            ->get()->result_array();
        array_count_values($m);
        return count($m);


//        $m = $this->db->query("SELECT username, referrer_id, COUNT(referrer_id)
//FROM users
//WHERE referrer_id = $ref AND account_level > 7
//GROUP BY referrer_id
//HAVING COUNT(referrer_id) > 2");

    }

    public function getSponsor($id, $d = array()) {
        foreach ($d as $field)
            $this->db->select($field);

        $this->db->where('users.id', $id);
        $this->db->where('users.locked', 0);

        $row = $this->db->get('users')->row();

        if (empty($row))
            return FALSE;

        return $row;
    }



    public function getThem(){

    }

    public function getBunch($userId = array()) {
        return $this->db->select('username,email,first_name,last_name,phone,account_level')
            ->from('users')
            ->where_in('id', $userId)
            ->get()->result();
    }

    public function getSingle($userId) {
        return $this->db->select('recycle,username,email,first_name,last_name,phone,account_level')
            ->from('users')
            ->where('id', $userId)
            ->get()->row();
    }

    function getOffenders(){
        return $this->db->query("SELECT c.method_name,u.phone,u.username,u.created_on ,u.reason
        FROM user_payment_method c 
        JOIN users u on c.user_id = u.id
        WHERE u.locked = 1 and u.reason is not NULL
         ORDER BY u.id,u.reason DESC")->result();
    }

    function getCurrentPhphones(){
        return $this->db->query("SELECT p.user_id, u.phone,u.username,u.locked 
        FROM ph p 
        JOIN users u on p.user_id = u.id
        WHERE p.status = 1 
         ORDER BY u.id DESC")->result();
    }

    public function getRefsRef($refid) {
        return $this->db->select('referrer_id')
            ->from('users')
            ->where('id', $refid)
            ->get()->row();
    }

    public function getUsername($id){
        return $this->db->select('username')
            ->from('users')
            ->where('id', $id)
            ->get()->row();
    }

    public function getUsername2($username){
        return $this->db->select('id,username,active')
            ->from('users')
            ->where('username', $username)
            ->where('active', 1)
            ->get()->row();
    }

    public function getByUsername($username, $d = array()) {
        foreach ($d as $field)
            $this->db->select($field);

        $this->db->where('users.username', $username);

        $row = $this->db->get('users')->row();

        if (empty($row))
            return FALSE;

        return $row;
    }

    public function getBySalt($salt, $d = array()) {
        foreach ($d as $field)
            $this->db->select($field);

        $this->db->where('users.salt', $salt);

        $row = $this->db->get('users')->row();

        if (empty($row))
            return FALSE;

        return $row;
    }

    public function getCountryById($id) {

        return $this->db->from('country')->where('id', $id)->get()->row();
    }

    public function getCountry($ip = FALSE)
    {
        if( $ip === FALSE ) $ip = getIp();
        $ipl = ip2long($ip);

        return ''; //$this->db->query("select id from ip_2_country left join country on code=country_code where {$ipl} between start and end")->row()->id;
    }
    
    function testt(){
//    $ids = $this->db->query("SELECT id FROM users where active = 1 ")->result_array();
//        $my_array = array_column($ids, 'id');
//        foreach ($ids as $row) {
//
//            $newArray[] = $row['id']; //Add it to the new array
//
//        }
//      $mm =    implode(',',$newArray);
//    return array($mm);
//    $ids = $this->db->query("SELECT id FROM users where active = 1 ")->result_array();
//        $my_array = array_column($ids, 'id');
//        $r = $this->db->select(array(
//            't1.id as id',
//            't1.description',
//            't1.topicid',
//            't1.add_date'))
//            ->from('table1 AS t1')
//            ->where('t1.topicid',$topicid)
//            ->where('t1.status',0)
//            ->where('t1.id NOT IN (select otherid from table2)',NULL,FALSE)
//        return $r;

//      $r =  $this->db->query("SELECT user_id,referee_id
//FROM referrals AS t1
//LEFT OUTER JOIN users AS t2 ON t1.user_id = t2.id
//WHERE t1.earning = 0 AND t1.referee_id  NOT IN (SELECT id FROM users where active = 1)")->result_array();
//      //return $r;
 }

    public function update($id, $data) {
        $this->db->where('id', $id);
        $n = $this->db->update('users', $data);
        if ($n){
            return TRUE;
        }

    }

    public function updateAll($level,$data) {
        $this->db->where('account_level', $level);
        $this->db->update('users', $data);

        return TRUE;
    }

    public function storeUpgrade($userId, $payeeId) {

        $data = array(
            'user_id' => $userId,
            'payee_id' => $payeeId
        );
        $this->db->insert('user_upgrade', $data);
        return TRUE;
    }

    public function getUpgrade($userId) {
        $r = $this->db->from('user_upgrade')
                ->where('user_id', $userId)
                ->get();

        if ($r->num_rows() > 0) return $r->row()->payee_id;

        return false;
    }

    public function deleteUpgrade($userId) {
        $this->db->where('user_id', $userId)->delete('user_upgrade');

    }

    public function getSetting($userId, $setting, $default = 1) {
        $r = $this->db->select('value')
                ->from('users_settings')
                ->where('setting', $setting)
                ->where('user_id', $userId)
                ->get();

        if ($r->num_rows() == 0) {
            $this->addSetting($userId, $setting, $default);
            return $default;
        }

        return $r->row()->value;
    }

    public function getSettings($userId) {
        $r = $this->db->select('setting, value')
                        ->from('users_settings')
                        ->where('user_id', $userId)
                        ->get()->result();

        $result = new stdClass();
        foreach ($r as $row)
            $result->{$row->setting} = $row->value;

        return $result;
    }

    public function addSetting($userId, $setting, $value) {
        $this->db->query("INSERT INTO users_settings (user_id, setting, `value`)
                          VALUES('$userId', '$setting', '$value')
                          ON DUPLICATE KEY
                          UPDATE `value` = '" . $value . "'");
    }

    public function addCredits($id, $credits, $type = 'ad') {
        $this->db->query("UPDATE users SET {$type}_credits = {$type}_credits + $credits WHERE id = $id");
        return TRUE;
    }

    public function subtractCredits($id, $credits, $type = 'ad') {
        $this->db->query("UPDATE users SET {$type}_credits = {$type}_credits - $credits WHERE id = $id");
        return TRUE;
    }

    public function storeLogin() {
        // Get the last entry for the user then follow the rules:
        //  - if IP is different add a new row
        //  - otherwise update the timestamp

        $userId = $this->ion_auth->user()->row()->id;
        $ip = $this->input->ip_address();

        $currentIP = ip2long($this->input->ip_address());

        $lastIPRow = $this->db->select('id, ip_address')
                ->from('user_login')
                ->where('user_id', $userId)
                ->order_by('date', 'DESC')
                ->limit(1)
                ->get()
                ->row();

        if (!$lastIPRow || $lastIPRow->ip_address != $ip) {
            $data = array(
                'user_id' => $userId,
                'user_ip' => $currentIP,
                'ip_address' => $ip,
                'date' => $this->now
            );

            $this->db->insert('user_login', $data);
        } else {
            $this->db->where('id', $lastIPRow->id)
                    ->set('date', $this->now)
                    ->update('user_login');
        }

        return ($this->db->affected_rows() == 1);
    }

    public function getLoginIPs($userId) {
        return $this->db->select('user_ip, ip_address, date')
                        ->where('user_id', $userId)
                        ->order_by('date', 'DESC')
                        ->limit(5)
                        ->get('user_login')
                        ->result();
    }

    public function getCountryFromIp($ip) {
        $countryRow = $this->db->select('c.name')
                ->from('country c')
                ->join('ip_2_country i', 'c.code = i.code')
                ->where('i.start <= ', $ip)
                ->where('i.end >= ', $ip)
                ->get()
                ->row();

        return $countryRow ? $countryRow->name : 'unknown';
    }

    public function storeFieldChange($userId, $fieldName, $oldValue, $newValue) {
        $data = array(
            'user_id' => $userId,
            'field' => $fieldName,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'date' => $this->now,
            'user_ip' => ip2long($this->input->ip_address()),
            'changed_by' => $this->ion_auth->user()->row()->id
        );

        $this->db->insert('user_changes', $data);

        return $this->db->insert_id();
    }

    /*     * *
     * Total registered activated users
     */

    public function countMembers() {
        return $this->db->from('users')
                        ->where('active', 1)
                ->where('deleted', 0)
                ->where('banned', 0)
                ->where('id >', 1)
                ->where('account_level >', 0)
                        ->count_all_results();
    }

    /*     * *
     * Total registered activated users by account level
     */

    public function countMemberTypes() {
        $types = array();
        $result = $this->db->select("account_level, count(*) c", FALSE)
                        ->from('users')
                        ->where('active', 1)
                          ->where('deleted', 0)
                         ->where('banned', 0)
                        ->where('id >', 0)
                        ->group_by('account_level')
                        ->get()->result();
        foreach ($result as $row) {
            $types[$row->account_level] = $row->c;
        }
        return $types;
    }

    public function countRegistrationsByDay() {

        return $this->db->query("select (created_on div (24*3600)) * (24*3600) day, count(*)  c from users
                                where  active = 1 AND deleted = 0
                                group by day order by day")
                        ->result();
    }

    public function getActive() {

        return $this->db->select('id, referrer_id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->get('users')->result();
    }



    /*     * *
     * Total registered activated users by account level exclude free trials (Novice level expires in 7 days)
     */

    public function countActiveMembers() {
        $types = array();
        $result = $this->db->select("account_level, count(*) c", FALSE)
                        ->from('users')
                        ->where('active', 1)
                        ->where('deleted', 0)
                        ->where('banned', 0)
                        ->where('id >', 1)
                        ->group_by('account_level')
                        ->get()->result();

        $total = 0;
        foreach ($result as $row) {
            $types['Stage '.$row->account_level] = $row->c;
            $total += $row->c;
        }
        return array($total, $types);
    }

    public function countUpgradedMembers() {
        return $this->db->select("count(*) c", FALSE)
                        ->from('users')
                        ->where('active', 1)
                        ->where('id >', 1)
                        ->where('account_level >', 0)
                        ->get()->row()->c;
    }

    // Count number of active users. If username provided, count number of
    // active users with email or username similar to 'username'

    public function getActiveUsersCount($filter = 'all') {
        $this->db->where('active', 1);

        return $this->getUsersCount($filter);
    }

    public function getUsersCount($filter = 'all') {
        if ($filter != 'all') {
            $this->db->like('username', $filter)
                    ->or_like('email', $filter);
        }

        return $this->db->from('users')
                        ->where('id >', 0)
                ->where('active', 1)
                ->where('deleted', 0)
                ->where('banned', 0)

                ->count_all_results();
    }

    public function getUsers($filter = 'all', $page = 1, $perpage = 50) {
        $start = ($page - 1) * $perpage;

        if ($filter != 'all') {
            $this->db->like('username', $filter)
                    ->or_like('email', $filter);
        }

        return $this->db->from('users')
                        ->limit($perpage, $start)
                        ->order_by('created_on')
                        ->get()
                        ->result();
    }

    public function getNextShareholder($nextId) {
        $r = $this->db->from('users')
                ->where('shares >', 0)
                ->where('id >=', $nextId)
                ->limit(1)
                ->get()
                ->row();
        if (empty($r)) {
            $nextId = 1;

            $r = $this->db->from('users')
                    ->where('shares >', 0)
                    ->where('id >=', $nextId)
                    ->limit(1)
                    ->get()
                    ->row();
        }

        return $r;
    }

    public function getEmailList($email) {
        return $this->db->from('users')
                        ->where('email_settings & ' . $email, NULL, FALSE)
                        ->where('active', 1)
                        ->get()
                        ->result();
    }

    public function getBatch($start, $limit) {
        return $this->db->select('id, username, email, email_settings')
                ->from('users')
                ->where('id >', $start)
                ->where('active', 1)
                ->where('banned', 0)
                ->where('deleted', 0)
                ->limit($limit)
                ->get()
                ->result();
    }


    public function countUnusedTECredits() {
        return $this->db->select('sum(te_credits) total', FALSE)
                        ->from('users')
                        ->where('active', 1)
                        ->get()->row()->total;
    }

    public function remindUsers() {
        /*         * ****
         * Resend activation
         */
        $users = $this->db->select('u.id, u.email, u.username, u.activation_code')
                ->from('users u')
                ->join('reminder r', 'r.user_id = u.id', 'LEFT')
                ->where('u.active', 0)
                ->where('u.activation_code IS NOT NULL')
                ->where('u.created_on < ', $this->now - (7 * 24 * 3600))
                ->where('r.user_id IS NULL')
                ->get()
                ->result();

        foreach ($users as $user) {
            $data = array(
                'userId' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'activation' => $user->activation_code,
            );

            $this->EmailQueue->store($user->email, 'Account Activation Reminder', 'emails/auth/activate_reminder', $data);

            $this->db->set('user_id', $user->id)
                    ->set('date', $this->now)
                    ->insert('reminder');
        }


        foreach ($users as $user) {
            $data = array(
                'username' => $user->username,
                'account_expires' => $user->account_expires,
                'account_level' => $user->account_level
            );

            $this->EmailQueue->store($user->email, 'Account Expiring', 'emails/member/account_expiring', $data);
        }
        /*         * ***
         * Account Expired
         *
         */
        $users = $this->db->select('u.id, u.email, u.username, u.account_expires, u.account_level')
                ->from('users u')
                ->where('u.active', 1)
                ->where('u.account_expires <', $this->now)
                ->where('u.account_level != ', 'Free')
                ->get()
                ->result();

        foreach ($users as $user) {
            $data = array(
                'username' => $user->username,
                'account_expires' => $user->account_expires,
                'account_level' => $user->account_level
            );

            $this->EmailQueue->store($user->email, 'Account Expired', 'emails/member/account_expired', $data);
        }
    }

    public function addToGroup($userId, $group_id) {
        return $this->db->insert('users_groups', array('user_id'  => $userId,
                                                       'group_id' => $group_id
        ));
    }

    public function removeFromGroup($userId, $group_id) {
        return $this->db->delete('users_groups', array('user_id'  => $userId,
                                                       'group_id' => $group_id
        ));
    }

    public function getReferralIds($userId) {
        return $this->db->select('id')->from('users')->where('referrer_id', $userId)->get()->row();
    }

    public function getEmailById($userId) {
        return $this->db->select('email')->from('users')->where('id', $userId)->get()->result();
    }

    public function deleteBanned() {

        $sql = "delete FROM `user_payment_method` WHERE user_id in (select id from users where banned=1);
                update purcahse_order set status = 'rejected' where user_id in (select id from users where banned=1);
                update monitor_listing set status = 'Problem' where user_id in (select id from users where banned=1);
                update users u2 join users banned ON u2.referrer_id = banned.id
                set u2.referrer_id = banned.referrer_id WHERE banned.banned = 1;
                update users set balance = 0, ad_credits = 0, te_credits = 0, banner_credits=0, referrer_id = 0 where banned = 1;
";
    }

    public function deleteSocialNetwork($uid, $id) {
        $this->db->where('id', $id)->where('user_id', $uid)->delete('user_social_network');
        return TRUE;
    }

    public function deleteUsers() {
        $users = $this->db->select('r.user_id, u.activation_code')
                ->from('reminder r')
                ->join('users u', 'u.id = r.user_id AND u.active = 0 AND u.activation_code IS NOT NULL', 'LEFT')
                ->where('r.date < ', $this->now - (7 * 24 * 3600))
                ->get()
                ->result();

        foreach ($users as $user) {
            if ($user->activation_code) {
                // Delete the user only if he is still in inactive state
                $this->db->where('user_id', $user->user_id)->delete('users_groups');
                $this->db->where('referee_id', $user->user_id)->delete('referrals');
                $this->db->where('id', $user->user_id)->delete('users');
            }

            // Delete the reminder
            $this->db->where('user_id', $user->user_id)->delete('reminder');
        }
    }

    public function deleteFree() {

        $this->db->set('deleted', 1)
                ->where('account_expires <=', $this->now)
                ->where('account_level', 0)
                ->where('id NOT IN (select distinct payer_user_id from payment)', NULL, FALSE)
                ->update('users');

        $sql = "INSERT into leads (SELECT null, username, first_name, last_name, email FROM users WHERE deleted = 1 AND banned = 0)";

        $this->db->query($sql);

        $sql = "SELECT id, username, email FROM users WHERE deleted = 1 AND banned = 0";
        $users = $this->db->query($sql)->result();

        if ($users) {
            $ids = array();
            foreach ($users as $u) $ids[] = $u->id;

            $this->purgeUsers($ids);
        }

        return $users;
    }

    public function oneTimePayment($uid){
     $db =   $this->db->query("SELECT id,username,email, phone
  FROM users u
 WHERE EXISTS( SELECT COUNT(p.payee_user_id) 
                 FROM payment p
                WHERE p.payee_user_id  = $uid
        AND u.account_level = 4
               HAVING COUNT(p.payee_user_id) = 20 )
               ")
         ->result();

     if($db){
         return TRUE;
     }else{
         return FALSE;
     }


    }

    public function Users2Purge(){
        $ids = $this->db->query("SELECT id FROM users WHERE active = 1 and account_level= 0 and created_on < 1520710186")
            ->result();
        return $ids;

    }

    public function purgeThem($ids) {

        if (is_array($ids)) {
            $uIds = implode(",", $ids);
        } else {
            $uIds = $ids;
        }
        $this->db->query("DELETE FROM referrals WHERE referee_id IN ( $uIds ) OR user_id IN ( $uIds )");
        $this->db->query("DELETE FROM users_settings WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM user_payment_method WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM news_read WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM users_settings WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM users_groups WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM reflink_clicks WHERE referral_user_id IN ( $uIds )");
        $this->db->query("DELETE FROM support_ticket_message WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM support_ticket WHERE user_id IN ( $uIds )");
        $this->db->query("UPDATE users SET  deleted = 1 AND active = 0 WHERE id IN  ( $uIds )");


    }

    public function purgeUsers($ids) {

        if (is_array($ids)) {
            $uIds = implode(",", $ids);
        } else {
            $uIds = $ids;
        }

        $this->db->query("DELETE FROM referrals WHERE referee_id IN ( $uIds ) OR user_id IN ( $uIds )");
        $this->db->query("DELETE FROM users_settings WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM user_payment_method WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM news_read WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM users_settings WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM users_groups WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM reflink_clicks WHERE referral_user_id IN ( $uIds )");
        $this->db->query("DELETE FROM support_ticket_message WHERE user_id IN ( $uIds )");
        $this->db->query("DELETE FROM support_ticket WHERE user_id IN ( $uIds )");
      //  $this->db->query("DELETE FROM users WHERE id IN ( $uIds )");
        $this->db->query("UPDATE users SET  deleted = 1 AND active = 0 WHERE id IN  ( $uIds )");

    }
   /*****
    * Account Expiring this hour and in the next 24 hours
    *
    */

    public function getExpiring() {

        return $this->db->select('u.id, u.email, u.username, u.created_on, u.account_expires, "free" as account_level', FALSE)
                ->from('users u')
                ->where('u.account_level', 0)
                ->where('MOD(u.account_expires, 3600) = '. $this->now % 3600, NULL, FALSE)
                ->where('u.account_expires > ', $this->now - CACHE_ONE_DAY)
                ->get()
                ->result();
    }

    public function lockUsers($hours) {

        $startTime = $this->now - (intval(CACHE_ONE_HOUR)*$hours);

        $pendingPayments = $this->db->select('p.payee_user_id')
                ->from('payment p')
                ->join('users u', 'u.id = p.payee_user_id')
                ->where('p.approved IS NULL')
                ->where('p.rejected IS NULL')
                ->where('p.created <= ', $startTime)
                ->where('u.locked', 0)
                ->get()->result();

        $ids = array();

        foreach ($pendingPayments as $row) {
            $ids[] = $row->payee_user_id;
        }

        if (!empty($ids)) {
            $lockIds = '('.implode(",", $ids).')';

            $sql = 'UPDATE users SET  locked = 1 WHERE id IN '.$lockIds;

            $this->db->query($sql);
        }

        return $ids;
    }

    public function clearInvites() {

        $startTime = $this->now - (INVITE_EXPIRATION * CACHE_ONE_HOUR);

        $this->db->where('date <=', $startTime)
                ->where('referral_user_id IS NULL')
                ->delete('invite');
    }

    public function getLevels($type) {
        return $this->db->select('user_level')
                        ->from('users_value_level')
                        ->where('type', $type)
                        ->order_by('user_level')
                        ->get()
                        ->result();
    }

    public function cleanUserSessions() {
        $this->db->query("DELETE FROM user_sessions WHERE
        last_activity < " . ($this->now - CACHE_ONE_DAY) . " OR ip_address = '0.0.0.0'");

    }

    public function forceLogout($userId) {
        $this->db->like('user_data', 's:3:"' .$userId . '"')
                ->delete('user_sessions');
    }

    public function getSocialNetworks($uid) {
        return $this->db
                        ->where('user_id', $uid)
                        ->get('user_social_network')
                        ->result();
    }

    public function getRegisteredTodayCount() {
        return $this->db->where('created_on > ', ($this->now - $this->limit24hours))
                        ->from('users')
                        ->where('active', 1)
                        ->count_all_results();
    }

    public function getGuestsOnlineCount() {
        return $this->db->from('user_sessions')
                        ->where("user_data NOT LIKE '%user_id%'", NULL, FALSE)
                        ->where('user_agent !=', '0')
                        ->where('last_activity >', $this->now - 3500)
                        ->count_all_results();
    }

    public function getUsersOnlineCount() {
        return $this->db->from('user_sessions')
                        ->like("user_data", "user_id")
                        ->where('last_activity >', $this->now - 1 * 3600)
                        ->count_all_results();
    }

    public function getUsersOnlineSubset($page, $perpage) {
        $start = ($page - 1) * $perpage;

        $onlineUsers = $this->db->select('last_activity, user_data')
                ->from('user_sessions')
                ->where('user_data !=', '')
                ->where('last_activity >', $this->now - 3500)
                ->limit($perpage, $start)
                ->get()
                ->result();

        foreach ($onlineUsers as &$user) {
            $userData = unserialize($user->user_data);
            if (isset($userData['username']) && $userData['username']) {
                $user->username = $userData['username'];
            } else if (isset($userData['id'])) {
                $user->username = $this->ion_auth->select('username')->user($userData['id'])->row()->username;
            } else
                $user->username = '<em>guest</em>';
        }

        return $onlineUsers;
    }


    public function block_ip() {
        if ($this->db->insert('users_ip_blocked', array('ip' => $this->input->ip_address(), 'released' => strtotime('+5 minutes'))))
            return TRUE;
        return FALSE;
    }

    public function remove_blocked_ip() {
        if ($this->db->delete('users_ip_blocked', array('ip' => $this->input->ip_address())))
            return TRUE;
        return FALSE;
    }

    public function check_blocked_ip() {
        if ($this->db->where('ip', $this->input->ip_address())->where('released <', now())->get('users_ip_blocked')->num_rows() > 0)
            return TRUE;
        return FALSE;
    }

    public function check_username($id, $username) {
        if ($this->db->where('id <>', $id)->where('username', $username)->get('users')
                ->num_rows() > 0
        )
            return TRUE;
        return FALSE;
    }

    public function check_email($id, $email) {
        if ($this->db->where('id <>', $id)->where('email', $email)->get('users')
                ->num_rows() > 0
        )
            return TRUE;
        return FALSE;
    }



    public function check_used_ip($ip) {
        // if was registered
        if ($this->db->where('ip_address', $ip)->get('users')->num_rows() > 0)
            return TRUE;
        // if was logged
        if ($this->db->where('ip_address', $ip)->get('user_login')->num_rows() > 0)
            return TRUE;
        return FALSE;
    }

    public function check_lock_ip($ip, $username) {
        if ($user = $this->db->where('username', $username)->get('users')->row()) {
            $lock = $this->db->where('user_id', $user->id)->where('setting', 'lock_my_ip')->get('users_settings')
                    ->row();

            if ($lock && $lock->value == 1) {

                return ($ip == $user->ip_address);
            }
        }
        return TRUE;
    }

    public function find_same_registration_ip($page, $perPage) {

        // find who registered with the same IP

        $data = array();
        $data['count'] = $this->db->select('ip_address')
                ->like('ip_address', '.')
                ->group_by('ip_address')
                ->having('count(*) >= 2')
                ->get('users')
                ->num_rows();
        $i = 0;
        //SLOW !!! foreach($this->db->select('DISTINCT(ip_address)')->like('ip_address', '.')->get('users')->result() as $u){
        foreach ($this->db->select('ip_address')
                ->like('ip_address', '.')
                ->group_by('ip_address')
                ->having('count(*) >= 2')
                ->get('users')
                ->result() as $u) {

            $search = $this->db->where('ip_address', $u->ip_address)->get('users');
            // if more than one registered
            if ($search->num_rows() > 1) {
                $data['result'][$i]['ip_address'] = $u->ip_address;
                foreach ($search->result() as $r) {
                    $data['result'][$i]['usernames'] .= $r->username . ', ';
                }
            }
            $i++;
        }
        return $data;
    }

    public function find_same_login_ip() {
        $data = array();
        $data['count'] = $this->db->select('ip_address')
                ->like('ip_address', '.')
                ->group_by('ip_address')
                ->having('count(DISTINCT(user_id)) >= 2')
                ->order_by('ip_address', 'ASC')
                ->get('user_login')
                ->num_rows();
        $i = 0;

        // find who logged with the same IP on different accounts
        foreach ($this->db->select('ip_address')
                ->like('ip_address', '.')
                ->group_by('ip_address')
                ->having('count(DISTINCT(user_id)) >= 2')
                ->get('user_login')
                ->result() as $u) {

            $search = $this->db->select('distinct(user_id), username')
                    ->join('users', 'users.id = user_login.user_id', 'left')
                    ->where('user_login.ip_address', $u->ip_address)
                    ->get('user_login');

            // if more than one registered
            if ($search->num_rows() > 1) {
                $data['result'][$i]['ip_address'] = $u->ip_address;
                foreach ($search->result() as $r) {
                    $data['result'][$i]['usernames'] .= $r->username . ', ';
                }
            }
            $i++;
        }
        return $data;
    }

    public function inactiveUsers() {

        $users = $this->db->select('u.id, u.email, u.username, u.last_login')
                ->from('users u')
                ->join('reminder r', 'r.user_id = u.id', 'LEFT')
                ->where('u.active', 1)
                ->where('u.activation_code IS NULL')
                ->where('u.last_login < ', $this->now - (7 * 24 * 3600))
                ->where('r.user_id IS NULL OR r.date < u.last_login')
                ->get()
                ->result();
        return $users;
    }

    public function setReminder($user_id){
        return $this->db->set('user_id', $user_id)
                    ->set('date', $this->now)
                    ->insert('reminder');
    }
    public function find_same_login_ip_by_user($user_id) {
        $data = '';

        // find who logged with the same IP on different accounts
        foreach ($this->db->select('ip_address')
                ->where('user_id', $user_id)
                ->like('ip_address', '.')
                ->group_by('ip_address')
                ->get('user_login')
                ->result() as $u) {

            $search = $this->db->select('distinct(user_id), username')
                    ->join('users', 'users.id = user_login.user_id', 'right')
                    ->where('user_login.ip_address', $u->ip_address)
                    ->where('user_login.user_id !=', $user_id)
                    ->get('user_login');
            // if more than one logged
            if ($search->num_rows() > 1) {
                foreach ($search->result() as $r) {
                    $data .= $r->username . ', ';
                }
            }
        }

        return $data;
    }

    public function find_same_registration_ip_by_user($user_id) {

        // find who registered with the same IP

        $data = '';
        $user = $this->db->where('id', $user_id)->get('users')->row();

        foreach ($this->db->where('id !=', $user_id)->where('ip_address', $user->ip_address)->like('ip_address', '.')->get('users')->result() as $u) {

            $data .= $u->username . ', ';
        }
        return $data;
    }



    function topRecruiter()
    {
//        return $this->db->query("SELECT username, COUNT(sponsor_id) AS ct
//        FROM users WHERE account_level > 0 AND id !=1 AND visible =1 GROUP BY sponsor_id ORDER BY COUNT(sponsor_id) DESC LIMIT 10")->result();



        return $this->db->query("SELECT c.username,c.id, u.referrer_id, COUNT(u.referrer_id) AS ct
        FROM users u
        JOIN users c on c.id = u.referrer_id
        WHERE c.id is not NULL AND u.account_level > 0  AND u.visible =1
        GROUP BY u.referrer_id ORDER BY ct DESC LIMIT 10")->result();

    }

       function topRecruiter2()
       {
//           $thirty = now();// -  86400; //2592000; //30days in seconds
//          $real =  date('d', $thirty);
//          var_dump($real);
           return $this->db->query("SELECT username,referrer_id, COUNT(referrer_id) AS ct
        FROM users WHERE account_level > 0
        GROUP BY referrer_id ORDER BY ct DESC LIMIT 10")->result();

    }


    function topStates(){
        return $this->db->query("SELECT c.country_name,c.id, u.country_id, COUNT(u.country_id) AS ct
        FROM users u
        JOIN country c on c.id = u.country_id
        WHERE c.id is not NULL AND u.account_level > 0
        GROUP BY u.country_id ORDER BY ct DESC LIMIT 10")->result();


    }

    function dueUpgrade($userId, $level){
        $count = 0;
        if($level == 0){
            $count = -1;
        }
          if($level == 1){
              $count = 2;
          }
        if($level == 2){
            $count = 5;
        }
        if($level == 3){
            $count = 12;
        }
        if($level == 4){
            $count = 26;
        }
        if($level == 5){
            $count = 54;
        }
        return $this->db->query("SELECT id,username,email, phone
  FROM users u
 WHERE EXISTS( SELECT COUNT(p.payee_user_id) 
                 FROM payment p
                WHERE p.payee_user_id  = $userId
        AND u.account_level = $level
               HAVING COUNT(p.payee_user_id) >= $count )")->result();


    }



}
