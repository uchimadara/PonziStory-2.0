<?php
class Payment_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $this->db->insert('payment', $data);
        return $this->db->insert_id();
    }

    public function add($payer_user_id, $payee_user_id, $amount, $created,$expired,
                        $confirmations=0,$currency_amount=0,$approved=NULL,$rejected=NULL,$proof_img=NULL,$ug=NULL,$mid,$phid,$ghid) {

        $data = array(
            'payer_user_id' => $payer_user_id,
            'payee_user_id' => $payee_user_id,
            'amount' => $amount,
            'created' => $created,
            'expired' => $expired,
            'confirmations' => $confirmations,
            'currency_amount' => $currency_amount,
            'approved' => $approved,
            'rejected' => $rejected,
            'proof_img' => $proof_img,
            'upgrade_id' => $ug,
            'method_id' => $mid,
            'ph_id' => $phid,
            'gh_id' => $ghid,

        );
        $m = $this->db->insert('payment', $data);
        return $this->db->insert_id();
//        if ($m){
//            return $this->db->insert_id();
//        }

      //  return $uuid;
    }


    public function checkPaymentExist($payer,$payee,$approved,$rejected) {
        return $this->db->from('payment')
            ->where('payer_user_id', $payer)
            ->where('payee_user_id', $payee)
            ->where('approved', $approved)
            ->where('rejected', $rejected)
            ->get()->num_rows();
    }

    public function get_last(){
        return $this->db->select('id')
            ->order_by('id',"desc")
            ->limit(1)
            ->get('payment')->row();
    }

    public function get($id) {

        return $this->db->select('p.payee_user_id,p.payer_user_id,p.created,p.amount,p.id,p.approved,p.rejected,p.ph_id,p.gh_id,p.proof_img, i.title, i.price, i.code, m.method_name, m.account, u.username', FALSE)
                ->from('payment p')
                ->join('purchase_item i', 'i.id = p.upgrade_id')
                ->join('user_payment_method m', 'm.id = p.method_id')
                ->join('users u', 'u.id = p.payer_user_id')
                ->where('p.id', $id)
                ->get()->row(); // can only be 1 at a time of these
    }

    public function getLast($id) {
        return $this->db->select('payer_user_id,payee_user_id,amount,approved,rejected')
            ->from('payment')
            ->where('id', $id)
            ->order_by("id", "desc")
            ->limit(1)
            ->get()->row();
    }


    public function getFull($id) {

        return $this->db->select("p.*, i.title, i.price, i.code, m.method_name, m.account, u1.username payer, concat_ws(' ', u1.first_name, u1.last_name) payer_name,u1.email payer_email,u1.phone payer_phone, u2.username payee, concat_ws(' ', u2.first_name, u2.last_name) payee_name, u2.email payee_email, u2.phone payee_phone", FALSE)
                ->from('payment p')
                ->join('purchase_item i', 'i.id = p.upgrade_id')
                ->join('user_payment_method m', 'm.id = p.method_id')
                ->join('users u1', 'u1.id = p.payer_user_id')
                ->join('users u2', 'u2.id = p.payee_user_id')
                ->where('p.id', $id)
                ->get()->row(); // can only be 1 at a time of these
    }

    public function update($id, $data) {
        $this->db->where('id', $id)->update('payment', $data);
    }

    public function updateTimeUp($id, $data) {
      $n =   $this->db->where('payer_user_id', $id)->update('payment', $data);
      if ($n){
          return true;
      }

    }

    public function updateApprove($id, $data) {
        $n =   $this->db->where('id', $id)->update('payment', $data);
        if ($n){
            return true;
        }

    }

    public function updateTimeUp2($id, $data) {
        $n =   $this->db->where('payee_user_id', $id)->update('payment', $data);
        if ($n){
            return true;
        }
    }


    public function updatePay($id, $data) {
        $n =   $this->db->where('id', $id)->update('payment', $data);
        if ($n){
            return true;
        }
    }

    public function getPendingSingle($payerId) {
        return $this->db->select('payee_user_id,amount,created,expired')
            ->from('payment')
            ->where('payer_user_id', $payerId)
            ->where('approved IS NULL')
            ->where('rejected IS NULL')
            ->where('deleted', 0)
            ->get()->row();  // can only be 1 at a time of these
    }

    public function getPendingMulti($payerId) {
        return $this->db->select('id,payee_user_id,amount,created,expired')
            ->from('payment')
            ->where('payer_user_id', $payerId)
            ->where('approved IS NULL')
            ->where('rejected IS NULL')
            ->where('proof_img IS NULL')
            ->where('deleted', 0)
            ->get()->result();
    }

    public function getPendingSent($payerId) {
        return $this->db->select('p.*, i.title, i.price, m.method_name, m.account', FALSE)
                ->from('payment p')
                ->join('purchase_item i', 'i.id = p.upgrade_id')
                ->join('user_payment_method m', 'm.id = p.method_id')
                ->where('payer_user_id', $payerId)
                ->where('approved IS NULL')
                ->where('rejected IS NULL')
                ->where('p.deleted', 0)
                ->get()->row();  // can only be 1 at a time of these
    }

    public function getPendingSent2($payerId) {
        return $this->db->select('p.*, i.title, i.price, m.method_name, m.account', FALSE)
            ->from('payment p')
            ->join('purchase_item i', 'i.id = p.upgrade_id')
            ->join('user_payment_method m', 'm.id = p.method_id')
            ->where('payer_user_id', $payerId)
            ->where('approved IS NULL')
            ->where('rejected IS NULL')
            ->where('proof_img IS NOT NULL')
            ->where('p.deleted', 0)
            ->get()->row();  // can only be 1 at a time of these
    }

    public function getPendingSent22($payerId) {
        return $this->db->select('p.id,p.payer_user_id,p.payee_user_id,p.amount,p.method_id,p.proof_img,p.created,p.expired,p.approved,p.rejected,p.ph_id,p.gh_id, i.title, i.price, m.method_name, m.account,m.note,m.payment_code,u.first_name,u.last_name,u.phone,u.email,u.avatar,u.username', FALSE)
            ->from('payment p')
            ->join('purchase_item i', 'i.id = p.upgrade_id')
            ->join('user_payment_method m', 'm.id = p.method_id')
            ->join('users u', 'u.id = m.user_id')
            ->where('payer_user_id', $payerId)
            ->where('approved IS NULL')
            ->where('rejected IS NULL')
            ->where('proof_img IS NOT NULL')
            ->where('p.deleted', 0)
            ->order_by("p.id", "asc")
            ->get()->result();  // can only be 1 at a time of these
    }

    public function getPendingSent3($payerId) {
        return $this->db->select('p.*, i.title, i.price, m.method_name, m.account,m.note,m.payment_code', FALSE)
            ->from('payment p')
            ->join('purchase_item i', 'i.id = p.upgrade_id')
            ->join('user_payment_method m', 'm.id = p.method_id')
            ->where('payer_user_id', $payerId)
            ->where('approved IS NULL')
            ->where('rejected IS NULL')
            ->where('proof_img IS NULL')
            ->where('p.deleted', 0)
            ->get()->row();  // can only be 1 at a time of these
    }

    public function getPendingSent4($payerId) {
        return $this->db->select('p.id,p.payer_user_id,p.payee_user_id,p.amount,p.method_id,p.proof_img,p.created,p.expired,p.approved,p.rejected,p.ph_id,p.gh_id, i.title, i.price, m.method_name, m.account,m.note,m.payment_code,u.first_name,u.last_name,u.phone,u.email,u.avatar,u.username', FALSE)
            ->from('payment p')
            ->join('purchase_item i', 'i.id = p.upgrade_id')
            ->join('user_payment_method m', 'm.id = p.method_id')
            ->join('users u', 'u.id = m.user_id')
            ->where('payer_user_id', $payerId)
            ->where('approved IS NULL')
            ->where('rejected IS NULL')
            ->where('proof_img IS NULL')
            ->where('p.deleted', 0)
            ->get()->result();  // can only be 1 at a time of these
    }

    public function getPendingSent5($payerId) {
        return $this->db->select('payee_user_id', FALSE)
            ->from('payment')

            ->where('payer_user_id', $payerId)
            ->where('approved IS NULL')
            ->where('rejected IS NULL')
            ->where('proof_img IS NULL')
            ->where('deleted', 0)
            ->get()->result();  // can only be 1 at a time of these
    }

    public function isPending($payerId, $level) {
        return $this->db->select('p.*, i.title, i.price, m.method_name, m.account', FALSE)
                ->from('payment p')
                ->join('purchase_item i', 'i.id = p.upgrade_id')
                ->join('user_payment_method m', 'm.id = p.method_id')
                ->where('payer_user_id', $payerId)
                ->where('approved IS NULL')
                ->where('rejected IS NULL')
                ->where('p.deleted', 0)
                ->where('i.code', $level)
                ->get()->row(); // can only be 1 at a time of these
    }

    public function getPending() {
        return $this->db
                ->select("p.*, i.title, i.price, i.code, u1.account_level,
                u1.username payer, concat_ws(' ', u1.first_name, u1.last_name) payer_name, u1.email payer_email, u1.text_ad_credits,
                u2.username payee, concat_ws(' ', u2.first_name, u2.last_name) payee_name, u2.email payee_email", FALSE)
                ->from('payment p')
                ->join('purchase_item i', 'i.id = p.upgrade_id')
                ->join('users u1', 'u1.id = p.payer_user_id')
                ->join('users u2', 'u2.id = p.payee_user_id')
                ->where('p.approved IS NULL')
                ->where('p.rejected IS NULL')
                ->where('p.deleted', 0)
                ->get()->result();
    }

    public function getPendingReceived($payeeId) {
        return $this->db->select('p.*, i.title, i.price, m.method_name, m.account', FALSE)
                ->from('payment p')
                ->join('purchase_item i', 'i.id = p.upgrade_id')
                ->join('user_payment_method m', 'm.id = p.method_id')
                ->where('payee_user_id', $payeeId)
                ->where('approved IS NULL')
                ->where('rejected IS NULL')
                ->where('p.deleted', 0)
                ->order_by('p.created')
                ->get()->result();
    }

    public function getPendingRejected($payerId) {
        return $this->db->select('p.*, i.title, i.price, m.method_name, m.account', FALSE)
                ->from('payment p')
                ->join('purchase_item i', 'i.id = p.upgrade_id')
                ->join('user_payment_method m', 'm.id = p.method_id')
                ->where('payer_user_id', $payerId)
                ->where('approved IS NULL')
                ->where('rejected IS NOT NULL')
                ->where('p.deleted', 0)
                ->get()->row(); // can only be 1 at a time of these
    }

    public function getPendingRejected2() {
        return $this->db->select('id,deleted,gh_id,payer_user_id,expired', FALSE)
            ->from('payment')
            ->where('approved IS NULL')
            ->where('rejected IS NOT NULL')
            ->where('deleted', 0)
            ->get()->result();
    }

    public function checkUser($userId) {
        return $this->db->select('count(*) c', FALSE)
                ->from('payment')
                ->where("(payer_user_id = $userId OR payee_user_id = $userId)")
                ->where('deleted', 0)
                ->get()->row()->c > 0;
    }

    public function userSummary($userId) {
        $result = array();
        $result['sent'] = $this->db->select('sum(amount) total, count(*) c', FALSE)
                        ->from('payment')
                        ->where('payer_user_id', $userId)
                         ->where('deleted', 0)
                        ->where('approved IS NOT NULL')
                        ->get()->row();

        $result['received'] = $this->db->select('sum(amount) total, count(*) c', FALSE)
                ->from('payment')
                ->where('payee_user_id', $userId)
                ->where('approved IS NOT NULL')
                ->where('deleted', 0)
                ->get()->row();

        $result['pending'] = $this->db->select('count(*) c, sum(amount) total', FALSE)
                ->from('payment')
                ->where('payee_user_id', $userId)
                ->where('approved IS NULL')
                ->where('rejected IS NULL')
                ->where('deleted', 0)
                ->get()->row();
       // var_dump($result['sent']);
        return $result;
    }

    public function payment_audit() {
        $sql = "select u.id, u.username, u.first_name, u.last_name, u.account_level, max(i.code) 'max upgrade'
                from payment p
                join users u on u.id = p.payer_user_id
                join purchase_item i on i.id = p.upgrade_id
                where p.approved is not null
                group by p.payer_user_id
                having u.account_level != max(i.code)";
    }

    public function getWallet($userId) {
        return $this->db->select('id, account, method_name,payment_code,note')
                ->from('user_payment_method')
                ->where('user_id', $userId)
              //  ->where('method_name', 'bitcoin')
                ->get()->row();
    }


    public function getReason($userId) {
        return $this->db->select('rejected,reason')
            ->from('payment')
            ->where('payee_user_id', $userId)
            ->where('rejected', 1)
            ->where('deleted !=', 1)
           ->order_by("id", "desc")
            ->limit(1)
            ->get()->row();
    }

    public function addWalletChange($userId, $oldWallet, $newWallet) {
        $this->load->helper('guid');
        $uuid = create_guid();
        $data = array(
            'id' => $uuid,
            'user_id' => $userId,
            'old_wallet' => $oldWallet,
            'new_wallet' => $newWallet,
            'date' => $this->now,
            'status' => 'pending'
        );
        $this->db->insert('wallet_change', $data);

        return $uuid;
    }

    public function changeWallet($userId, $uuid, $methodName) {
        $r = $this->db->from('wallet_change')
                ->where('user_id', $userId)
                ->where('id', $uuid)
                ->where('status', 'pending')
                ->get()->row();

        if ($r) {
            $this->db->where('user_id', $userId)
                    ->update('user_payment_method', array('account' => $r->new_wallet, 'method_name' => $methodName));
            $this->db->where('id', $uuid)
                    ->update('wallet_change', array('status' => 'approved'));

            return TRUE;
        }

        return FALSE;
    }

    public function getWalletChanges($userId) {
        return $this->db->from('wallet_change')
                ->where('user_id', $userId)
                ->get()->result();

    }

    public function cancelChangeWallet($userId) {

        $this->db->where('user_id', $userId)
                 ->where('status', 'pending')
                 ->delete('wallet_change');

        return TRUE;
    }

    public function getUpgradeDate($userId, $level, $first=FALSE) {

        $sort = ($first) ? 'asc' : 'desc';

        if (($result = $this->getCache('upgrade_date_'.$userId.$level.$sort)) === FALSE) {

            $r = $this->db->select('p.approved')
                    ->from('payment p')
                    ->join('purchase_item i', 'i.id = p.upgrade_id')
                    ->where('payer_user_id', $userId)
                    ->where('p.approved IS NOT NULL')
                    ->where('i.code', $level)
                    ->order_by('p.approved', $sort)
                    ->limit(1)
                    ->get();

            if ($r->num_rows() > 0) {
                $result = $r->row()->approved;
                $this->saveCache($result);
            } else {
                $result = FALSE;
            }
        }

        return $result;

    }

    public function setExpiration($userId, $upgrade_id, $time) {

        $expires = $this->now + $time;

        $this->db->query("INSERT INTO expiration (user_id, upgrade_id, start, expires)
        VALUES ($userId, $upgrade_id, {$this->now}, {$expires})
        ON DUPLICATE KEY UPDATE expires = expires + $time");
    }

    public function getExpires($userId) {

        if (($result = $this->getCache('expires_'.$userId)) === FALSE) {

            $r = $this->db->select('e.start, e.expires, i.code level', FALSE)
                ->from('expiration e')
                ->join('purchase_item i', 'i.id = e.upgrade_id')
                ->where('e.user_id', $userId)
                ->order_by('i.code')
                ->get()->result();

            if ($r) {
                $result = array();
                foreach ($r as $row) {
                    $result[$row->level] = $row;
                }

                $this->saveCache($result);
            }
        }
        return $result;
    }

    public function getUpgradeExpiration($userId, $level) {

        if (($result = $this->getCache('upgrade_expires_'.$userId)) === FALSE) {

            $r = $this->db->select('e.expires')
                    ->from('expiration e')
                    ->join('purchase_item i', 'i.id = e.upgrade_id')
                    ->where('e.user_id', $userId)
                    ->where('i.code', $level)
                    ->get();


            if ($r->num_rows() == 1) {
                $result = $r->row()->expires;
                $this->saveCache($result);
            } else {
                $result = FALSE;
            }
        }
        return $result;
    }

    public function getExpiring($when) {

        $result = $this->db->query("
            select u.username, u.first_name, u.last_name, u.email, i.price, i.title level, start, expires
            from expiration e
            join users u on u.id = e.user_id
            join purchase_item i on i.id = e.upgrade_id
            where (expires - expires mod 3600) = $when
        ")->result();

        return $result;
    }

    public function getReferralsExpiring($userId) {

        return $this->db->query("
        select ref.id, ref.username, ref.first_name, ref.last_name, ref.email, ref.phone, e.expires, r.level, p.price from
        referrals r
        join users u on u.id = r.user_id
        join users ref on ref.id = r.referee_id
        join expiration e on e.user_id = r.referee_id
        join purchase_item p on p.id = e.upgrade_Id
        where r.user_id = $userId
        and r.level = p.code
        order by  e.expires")->result();
    }

    public function find_txid($txId) {
        return $this->db->from('payment')->where('transaction_id', $txId)->count_all_results() > 0;
    }

    public function getTotalPaid($userId) {
        return $this->db->select('sum(amount) total', FALSE)
                ->from('payment')
                ->where('payee_user_id', $userId)
                ->where('approved IS NOT NULL')
                ->where('deleted', 0)
                ->get()->row()->total;
    }

    public function getTotalPaid2()
    {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('payment')
            ->where('deleted', 0)
            ->get()->row()->total;
    }


    function totaltransaction(){
        return $this->db->select('COUNT(*) as count')
            ->from('payment')
            ->get()->row()->count;
}


    public function getLatest($count) {
        $count = (int) $count;
        return $this->db->select('u.username, u.avatar, p.approved, p.amount')
                ->from('payment p')
                ->join('users u', 'u.id = p.payee_user_id')
                ->where('approved IS NOT NULL')
                ->where('p.deleted', 0)
                ->where('p.visible', 1)
                ->where('u.visible', 1)
//                ->where('p.payee_user_id >', 1)

             //   ->order_by('p.upgrade_id', 'desc')
            ->order_by('p.updated', 'desc')
            //->order_by('p.upgrade_id', 'desc')
                ->limit(20)
                ->get()->result();
    }


    public function getLatestL1($count = 20) {
        $count = (int) $count;
        return $this->db->select('u.username, u.avatar, p.approved, p.amount,p.upgrade_id')
            ->from('payment p')
            ->join('users u', 'u.id = p.payee_user_id')
            ->where('approved IS NOT NULL')
            ->where('p.upgrade_id =', 1)
            ->where('p.deleted', 0)
            ->where('p.visible', 1)
            ->where('u.visible', 1)
//                ->where('p.payee_user_id >', 1)

            //   ->order_by('p.upgrade_id', 'desc')
            ->order_by('p.created', 'desc')
           // ->order_by('p.upgrade_id', 'desc')
            ->limit(5)
            ->get()->result();
    }

    public function getLatestL2($count =5) {
        $count = (int) $count;
        return $this->db->select('u.username, u.avatar, p.approved, p.amount,p.upgrade_id')
            ->from('payment p')
            ->join('users u', 'u.id = p.payee_user_id')
            ->where('approved IS NOT NULL')
            ->where('p.upgrade_id =', 2)
            ->where('p.deleted', 0)
            ->where('p.visible', 1)
            ->where('u.visible', 1)
//                ->where('p.payee_user_id >', 1)

            //   ->order_by('p.upgrade_id', 'desc')
            ->order_by('p.created', 'desc')
           // ->order_by('p.upgrade_id', 'desc')
            ->limit(5)
            ->get()->result();
    }

    public function getLatestL3($count = 5) {
        $count = (int) $count;
        return $this->db->select('u.username, u.avatar, p.approved, p.amount,p.upgrade_id')
            ->from('payment p')
            ->join('users u', 'u.id = p.payee_user_id')
            ->where('approved IS NOT NULL')
            ->where('p.upgrade_id =', 3)
            ->where('p.deleted', 0)
            ->where('p.visible', 1)
            ->where('u.visible', 1)
//                ->where('p.payee_user_id >', 1)

            //   ->order_by('p.upgrade_id', 'desc')
            ->order_by('p.created', 'desc')
          //  ->order_by('p.upgrade_id', 'desc')
            ->limit(5)
            ->get()->result();
    }


    public function getLatestL4($count = 5) {
        $count = (int) $count;
        return $this->db->select('u.username, u.avatar, p.approved, p.amount,p.upgrade_id')
            ->from('payment p')
            ->join('users u', 'u.id = p.payee_user_id')
            ->where('approved IS NOT NULL')
            ->where('p.upgrade_id =', 4)
            ->where('p.deleted', 0)
            ->where('p.visible', 1)
            ->where('u.visible', 1)
//                ->where('p.payee_user_id >', 1)

            //   ->order_by('p.upgrade_id', 'desc')
            ->order_by('p.created', 'desc')
            //->order_by('p.upgrade_id', 'desc')
            ->limit(5)
            ->get()->result();
    }

    public function getLatestL5($count = 5) {
        $count = (int) $count;
        return $this->db->select('u.username, u.avatar, p.approved, p.amount,p.upgrade_id')
            ->from('payment p')
            ->join('users u', 'u.id = p.payee_user_id')
            ->where('approved IS NOT NULL')
            ->where('p.upgrade_id =', 5)
            ->where('p.deleted', 0)
            ->where('p.visible', 1)
            ->where('u.visible', 1)
//                ->where('p.payee_user_id >', 1)

            //   ->order_by('p.upgrade_id', 'desc')
            ->order_by('p.created', 'desc')
          //  ->order_by('p.upgrade_id', 'desc')
            ->limit(5)
            ->get()->result();
    }

    public function getLatestL6($count = 5) {
        $count = (int) $count;
        return $this->db->select('u.username, u.avatar, p.approved, p.amount,p.upgrade_id')
            ->from('payment p')
            ->join('users u', 'u.id = p.payee_user_id')
            ->where('approved IS NOT NULL')
            ->where('p.upgrade_id =', 6)
            ->where('p.deleted', 0)
            ->where('p.visible', 1)
            ->where('u.visible', 1)
//                ->where('p.payee_user_id >', 1)

            //   ->order_by('p.upgrade_id', 'desc')
            ->order_by('p.created', 'desc')
           // ->order_by('p.upgrade_id', 'desc')
            ->limit(5)
            ->get()->result();
    }


    function topEarners()
    {
//        $this->db->distinct();
//        return $this->db->select('p.amount, u.username')
//            ->from('payment p')
//            ->join('users u', 'u.id = p.payer_user_id')
//            ->where('approved', 1)
//            ->where('p.visible', 1)
//            ->where('u.visible', 1)
//        ->order_by('p.amount', 'desc')
//        ->limit(10)
//        ->get()->result();

        return $this->db->query("SELECT users.username, SUM(payment.amount) AS pp
FROM users
JOIN payment ON users.id = payment.payee_user_id
WHERE users.visible = 1 AND payment.approved = 1 AND payment.visible = 1
GROUP BY users.id
ORDER BY pp DESC
LIMIT 10")->result();

//        return $this->db->select_max('amount')
//       ->from('payment p')
//        ->join('users u', 'u.id = p.payer_user_id')
//        ->where('approved', 1)
//            ->order_by('p.created', 'desc')
//
//            ->limit(10)
//         ->get()->result();
    }



    public function awaitingConfirmation($userId) {
        return $this->db->select('p.payer_user_id,p.payee_user_id,p.amount,p.proof_img,p.created,p.approved,p.rejected,u.username,u.phone')
                ->from('payment p')
               ->join('users u', 'u.id = p.payer_user_id')
                ->where('payer_user_id', $userId)
                ->where('approved', NULL)
                 ->where('rejected', NULL)
                ->get()->result();
    }

    public function awaitingConfSingle($userId) {
        return $this->db->select('p.payer_user_id,p.payee_user_id,p.created,u.username,u.email,u.phone,p.amount')
            ->from('payment p')
            ->join('users u', 'u.id = p.payee_user_id')
            ->where('payer_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', NULL)
            ->get()->row();
    }

    public function awaitingConfirmationSingle($userId) {
        return $this->db->select('payer_user_id,payee_user_id,amount,proof_img,created,expired,approved,rejected')
            ->from('payment')
            ->where('payee_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', NULL)
            ->get()->row();
    }


    public function awaitingConfirmationSingle2($userId) {
        return $this->db->select('p.payer_user_id,p.payee_user_id,p.created,u.username,u.email,u.phone,p.amount,p.expired')
            ->from('payment p')
            ->join('users u', 'u.id = p.payee_user_id')
            ->where('payer_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', 1)
            ->where('p.deleted', 1)
            ->where('punishment', 1)
            ->get()->row();
    }


    public function awaitingConfirmationSingle3($userId) {
        return $this->db->select('p.payer_user_id,p.payee_user_id,p.created,u.username,u.email,u.phone,p.amount,p.expired,p.id')
            ->from('payment p')
            ->join('users u', 'u.id = p.payer_user_id')
            ->where('payee_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', 1)
            ->where('p.deleted', 1)
            ->where('punishment', 1)
            ->get()->row();
    }

    public function awaitingConfirmationSingle4($userId) {
        return $this->db->select('p.payer_user_id,p.payee_user_id,p.created,u.username,u.email,u.phone,p.amount,p.expired,p.id')
            ->from('payment p')
            ->join('users u', 'u.id = p.payer_user_id')
            ->where('payee_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', 1)
            ->where('p.deleted', 1)
            ->where('punishment', 1)
            ->get()->result();
    }

    public function getCurrentPayee($userId){
        return $this->db->select('payee_user_id,created,id')
            ->from('payment')
            ->where('payer_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', NULL)
            ->limit(1)
            ->get()->row();
    }


    public function getCurrentPayee2($id){
        return $this->db->select('amount,created,id,payee_user_id')
            ->from('payment')
            ->where('payee_user_id', $id)
            ->where('approved', NULL)
            ->where('rejected', NULL)
            ->limit(1)
            ->get()->row();
    }


    public function getCurrentPayment($userId,$id){
        return $this->db->select('payee_user_id,payer_user_id,created,amount,id,approved,rejected,ph_id,gh_id,proof_img')
            ->from('payment')
            //->where('payer_user_id', $userId)
            ->where('id', $id)
            ->where('approved', NULL)
            ->where('rejected', NULL)
            ->limit(1)
            ->get()->row();
    }

    public function getCurrentPayment2($userId){
        return $this->db->select('payee_user_id,payer_user_id,created,amount,id,approved,rejected')
            ->from('payment')
            ->where('payee_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', NULL)
            ->get()->result();
    }

    public function awaitingConfirmation2($userId)
    {
        return $this->db->select('p.id,p.payer_user_id,p.payee_user_id,p.amount,p.proof_img,p.created,p.expired,p.approved,p.rejected,u.username,u.phone')
            ->from('payment p')
            ->join('users u', 'u.id = p.payer_user_id')
            ->where('payee_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', NULL)
            ->get()->result();

    }

    public function awaitingConfirmation3($userId)
    {
        return $this->db->select('p.id,p.payer_user_id,p.payee_user_id,p.amount,p.proof_img,p.created,p.expired,p.approved,p.rejected,u.username,u.phone')
            ->from('payment p')
            ->join('users u', 'u.id = p.payer_user_id')
            ->where('payee_user_id', $userId)
            ->where('approved', NULL)
            ->where('rejected', NULL)
            ->get()->result();

    }


    public function oneTimePayment($uid){
        $db =   $this->db->query("SELECT id,username,email, phone
  FROM users u
 WHERE EXISTS( SELECT COUNT(p.payee_user_id) 
                 FROM payment p
                WHERE p.payee_user_id  = $uid
        AND u.account_level = 4
               HAVING COUNT(p.payee_user_id) = 2 )")
            ->get()->row();

        return $db;


    }

}