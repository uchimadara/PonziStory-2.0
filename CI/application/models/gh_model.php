<?php
class Gh_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $this->db->insert('gh', $data);
        return $this->db->insert_id();
    }

    public function checkGHexist($id) {
        return $this->db->from('gh')
            ->where('user_id', $id)
            ->where('status <', '4')
            ->get()->num_rows();
    }

    public function checkGHexist2($id) {
        return $this->db->from('gh')
            ->where('user_id', $id)
            ->where('status <', '6')
            ->get()->num_rows();
    }

    public function getAllGh($id) {
        return $this->db->select('id,user_id,status,amount,rem_amount,date_added,date_of_gh,type')
            ->from('gh')
            ->where('status >', 0)
            ->where('user_id', $id)
            ->order_by('id', 'DESC')
            ->get()->result();
    }
    public function getAllGh2($id) {
        return $this->db->select('id,user_id,status,amount,rem_amount,date_added,date_of_gh,type')
            ->from('gh')
            ->where('status >', 0)
            ->where('id', $id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function checkGHCollected($id) {
        return $this->db->from('gh')
            ->where('user_id', $id)
            ->where('status', '4')
            ->get()->num_rows();
    }

    public function checkGHexistBonus($id) {
        return $this->db->from('gh')
            ->where('user_id', $id)
            ->where('status <', '4')
            ->where('type', 'BONUS')
            ->get()->num_rows();
    }

    public function upAll($id){
        // $cd = (int) $cd;

        $this->db->query("UPDATE ph SET first_bonus_status = 2 WHERE first_bonus_id = $id ");
        $this->db->query("UPDATE ph SET second_bonus_status = 2 WHERE second_bonus_id = $id ");
        return TRUE;
    }

    public function getStatus($id) {
        return $this->db->select('status,amount,rem_amount')
            ->from('gh')
            ->where('user_id', $id)
            ->order_by('id', 'DESC')
            ->get()->row();
    }

    public function getMeMerge($id) {
        return $this->db->select('id,user_id,method_id,status,amount,rem_amount')
            ->from('gh')
            ->where('status', 1)
            ->where('type', 'GH')
            ->where('user_id', $id)
            ->order_by('id', 'DESC')
            ->get()->row();
    }



    public function getBonus4Merge() {
        return $this->db->select('user_id,g.username,rem_amount,date_of_gh,status,method_id')
            ->from('gh g')
            ->join('users u', 'u.id = g.user_id')
            ->where('status', 1)
            ->where('type', 'BONUS')
            ->where('rem_amount >', 0)
            ->where('u.locked', 0)
            ->where('u.active', 1)
            ->order_by('rem_amount', 'DESC')
            ->order_by('date_of_gh', 'ASC')
            ->order_by('g.id', 'ASC')
            ->get()->result();
    }

    public function getGh4Merge($amt=0,$limit=10) {

        if ($amt == 0){
        return $this->db->select('user_id,g.username,rem_amount,date_of_gh,status,method_id')
            ->from('gh g')
            ->join('users u', 'u.id = g.user_id')
            ->where('status', 1)
            ->where('type', 'GH')
            ->where('rem_amount >', 0)
            ->where('u.locked', 0)
            ->where('u.active', 1)
            ->order_by('rem_amount', 'DESC')
//            ->order_by('date_added', 'ASC')
            ->order_by('date_of_gh', 'ASC')
//            ->order_by('g.id', 'ASC')
            ->limit($limit)
            ->get()->result();
    } else{
            return $this->db->select('user_id,g.username,rem_amount,date_of_gh,status,method_id')
                ->from('gh g')
                ->join('users u', 'u.id = g.user_id')
                ->where('status', 1)
                ->where('type', 'GH')
                ->where('rem_amount >', 0)
                ->where('rem_amount', $amt)
                ->where('u.locked', 0)
                ->where('u.active', 1)
                ->order_by('rem_amount', 'DESC')
//                ->order_by('date_added', 'ASC')
                ->order_by('date_of_gh', 'ASC')
//                ->order_by('g.id', 'ASC')
                ->limit($limit)
                ->get()->result();
        }
    }

    public function getGh4MergeSingle($id) {
        return $this->db->select('user_id,username,rem_amount,date_of_gh,status,method_id')
            ->from('gh')
            ->where('status', 1)
            ->where('type', 'GH')
            ->where('rem_amount >', 0)
            ->where('user_id', $id)
            ->order_by('rem_amount', 'ASC')
            ->order_by('date_added', 'ASC')
            ->order_by('date_of_gh', 'ASC')
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get()->row();
    }

    public function getGh4MergeSingleBonus($id) {
        return $this->db->select('user_id,username,rem_amount,date_of_gh,status,method_id')
            ->from('gh')
            ->where('status', 1)
            ->where('type', 'BONUS')
            ->where('rem_amount >', 0)
            ->where('user_id', $id)
            ->order_by('rem_amount', 'ASC')
            ->order_by('date_of_gh', 'ASC')
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get()->row();
    }

    public function getAllBonusstats() {
        return $this->db->from('gh')
            ->where('status', 1)
            ->where('type', 'BONUS')
            ->where('rem_amount >', 0)
            ->get()->num_rows();
    }

    public function getUserGHstats($user) {
        return $this->db->from('gh')
            ->where('user_id', $user)
            ->where('status >', 0)
            ->where('type', 'GH')
            ->where('rem_amount', 0)
            ->get()->num_rows();
    }

    public function getGhRecord4Testi() {
        return $this->db->from('gh')
            ->where('status >', 3)
            ->where('type', 'GH')
            ->where('rem_amount', 0)
            ->get()->num_rows();
    }

    public function getRefRecord4Testi() {
        return $this->db->from('gh')
            ->where('status >', 3)
            ->where('type', 'BONUS')
            ->where('rem_amount', 0)
            ->get()->num_rows();
    }

    public function getSysRecord4Testi() {
        return $this->db->from('gh')
            ->where('status >', 3)
            ->where('type', 'SYSBONUS')
            ->where('rem_amount', 0)
            ->get()->num_rows();
    }

    public function getAllGHstats() {
        return $this->db->from('gh')
            ->where('status', 1)
            ->where('type', 'GH')
            ->where('rem_amount >', 0)
            ->get()->num_rows();
    }

        public function getTotalMergedToday($date) {
            return $this->db->select('sum(amount) total', FALSE)
                ->from('gh')
                ->where('status >', 0)
//                ->where('type', 'GH')
//                ->where('rem_amount >', 0)
                ->where('date_added', $date)
                ->get()->row()->total;
        }

    public function getAllGHstatsToday($date) {
        return $this->db->from('gh')
            ->where('status', 1)
            ->where('type', 'GH')
            ->where('rem_amount >', 0)
            ->where('date_of_gh', $date)
            ->get()->num_rows();
    }

    public function getAllSumGHstats() {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('gh')
            ->where('status', 1)
            ->where('type', 'GH')
            ->where('rem_amount >', 0)
            ->get()->row()->total;
    }

    public function getAllSumGHstatsToday($date) {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('gh')
            ->where('status', 1)
            ->where('type', 'GH')
            ->where('rem_amount >', 0)
            ->where('date_of_gh', $date)
            ->get()->row()->total;
    }


    public function getBonusGH($id) {
        return $this->db->select('user_id,username,amount,rem_amount,date_of_gh,date_added,status,method_id')
            ->from('gh')
            ->where('status > ', 0)
            ->where('user_id', $id)
            ->where('type', 'BONUS')
            ->order_by('id', 'DESC')
            ->get()->result();
    }


    public function getZaGh($id) {
        return $this->db->select('id,status,rem_amount')
            ->from('gh')
            ->where('status', 1)
            ->where('user_id', $id)
            ->where('type', 'GH')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getZaGhBonus($id) {
        return $this->db->select('id,status,rem_amount')
            ->from('gh')
            ->where('status', 1)
            ->where('user_id', $id)
            ->where('type', 'BONUS')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getProblemGh($id) {
        return $this->db->select('id,status,rem_amount')
            ->from('gh')
            ->where('status', 6)
            ->or_where('status',1)
            ->where('user_id', $id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }



    public function get($id) {

        return $this->db->select('p.*, i.title, i.price, i.code, m.method_name, m.account, u.username', FALSE)
            ->from('payment p')
            ->join('purchase_item i', 'i.id = p.upgrade_id')
            ->join('user_payment_method m', 'm.id = p.method_id')
            ->join('users u', 'u.id = p.payer_user_id')
            ->where('p.id', $id)
            ->get()->row(); // can only be 1 at a time of these
    }


    public function getAll(){
        return $this->db->select('id,name,team_link,team_leader,location')
            ->from('teams')
            ->where('status', 1)
            ->order_by('id', 'DESC')
            ->get()->result();
    }


    public function getFull($id) {

        return $this->db->select("p.*, i.title, i.price, i.code, m.method_name, m.account, u1.username payer, concat_ws(' ', u1.first_name, u1.last_name) payer_name, u2.username payee, concat_ws(' ', u2.first_name, u2.last_name) payee_name, u2.email payee_email", FALSE)
            ->from('payment p')
            ->join('purchase_item i', 'i.id = p.upgrade_id')
            ->join('user_payment_method m', 'm.id = p.method_id')
            ->join('users u1', 'u1.id = p.payer_user_id')
            ->join('users u2', 'u2.id = p.payee_user_id')
            ->where('p.id', $id)
            ->get()->row(); // can only be 1 at a time of these
    }

    public function update($id, $data) {
      $n =  $this->db->where('id', $id)->update('gh', $data);
        if ($n){
            return true;
        }

    }

    public function updateGHmerge($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('gh', $data);

        return TRUE;
    }


    public function updateTimeUp($id, $data) {
        $n =   $this->db->where('payer_user_id', $id)->update('payment', $data);
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

    public function updateGHupload($id, $data) {
        $n =   $this->db->where('id', $id)->update('gh', $data);
        if ($n){
            return true;
        }

    }


    public function updateGHapprove($id, $data) {
        $n =   $this->db->where('id', $id)->update('gh', $data);
        if ($n){
            return true;
        }

    }

    public function getPendingSingle($payerId) {
        return $this->db->select('created,expired')
            ->from('payment')
            ->where('payer_user_id', $payerId)
            ->where('approved IS NULL')
            ->where('rejected IS NULL')
            ->where('deleted', 0)
            ->get()->row();  // can only be 1 at a time of these
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


}