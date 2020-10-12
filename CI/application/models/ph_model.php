<?php
class Ph_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $this->db->insert('ph', $data);
        return $this->db->insert_id();
    }

    public function checkPHexist($id) {
        return $this->db->from('ph')
            ->where('user_id', $id)
            ->where('status <', '5')
            ->where('recom', NULL)
            ->get()->num_rows();
    }

    public function getCurrentPlan($id) {
        return $this->db->select('amount,plan_id')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status <', 6)
            ->where('recom', NULL)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getLastRecom($id) {
        return $this->db->select('amount,plan_id')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status <', 6)
            ->where('recom', 1)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }


    public function check4Recom($id) {
        return $this->db->from('ph')
            ->where('user_id', $id)
            ->where('recom', NULL)
            ->where('status <', '4')
            ->get()->num_rows();
    }
    public function checkPHexistRecom($id) {
        return $this->db->from('ph')
            ->where('user_id', $id)
            ->where('status <', '5')
            ->where('recom', 1)
            ->get()->num_rows();
    }

    public function checkPHexistByUsername($username) {
        return $this->db->from('ph')
            ->where('username', $username)
            ->where('status', '4')
            ->get()->num_rows();
    }

    public function checkPHexist2($id) {
        return $this->db->from('ph')
            ->where('user_id', $id)
            ->where('status <', '5')
            ->where('amount <', '10000')
            ->get()->num_rows();
    }

//    public function checkPH4GH($id) {
//        return $this->db->from('ph')
//            ->where('user_id', $id)
//            ->where('status', '4')
//            ->get()->num_rows();
//    }


    public function getAllPHstats() {
        return $this->db->from('ph')
            ->where('status', 1)
            ->where('rem_amount >', 0)
            ->get()->num_rows();
    }

    public function getAllPHstatsToday($date) {
        return $this->db->from('ph')
            ->where('status', 1)
            ->where('rem_amount >', 0)
            ->where('date_of_ph', $date)
            ->get()->num_rows();
    }

    public function getAllSumPHstats() {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('ph')
            ->where('status', 1)
            ->where('rem_amount >', 0)
            ->get()->row()->total;
    }

    public function getAllSumPHstatsToday($date) {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('ph')
            ->where('status', 1)
            ->where('rem_amount >', 0)
            ->where('date_of_ph', $date)
            ->get()->row()->total;
    }

    public function upAll($id){
        // $cd = (int) $cd;

        $this->db->query("UPDATE ph SET system_bonus_status = 2 WHERE user_id = $id ");
        return TRUE;
    }


    public function getPlan2($id) {
        return $this->db->select('amount,plan_id')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status', 4)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getPlan($id) {
        return $this->db->select('amount')
            ->from('ph')
            ->where('id', $id)
            ->get()->row();
    }

    public function getStatus($id) {
        return $this->db->select('status,amount,rem_amount')
            ->from('ph')
            ->where('user_id', $id)
            ->where('recom', NULL)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }
    public function getStatusMulti($id) {
        return $this->db->select('status,amount,rem_amount')
            ->from('ph')
            ->where('user_id', $id)
            ->where('recom', NULL)
            ->order_by('status', 'ASC')
            ->order_by('id', 'DESC')
            ->limit(3)
            ->get()->result();
    }

    public function getStatusInfo($id) {
        return $this->db->select('status,amount,rem_amount')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status', 2)
            ->or_where('status', 3)
            ->where('recom', NULL)
            ->order_by('status', 'ASC')
            ->order_by('id', 'DESC')
            ->limit(3)
            ->get()->result();
    }

    public function getStatus2($id) {
        return $this->db->select('status,amount,rem_amount')
            ->from('ph')
            ->where('user_id', $id)
            ->where('recom', 1)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getZaPh($id) {
        return $this->db->select('id,status,rem_amount')
            ->from('ph')
            ->where('status', 1)
            ->where('user_id', $id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getAllPh($id) {
        return $this->db->select('id,user_id,status,amount,rem_amount,date_of_ph,date_of_gh,recom')
            ->from('ph')
            ->where('status >', 0)
            ->where('user_id', $id)
            ->order_by('id', 'DESC')
            ->get()->result();
    }

    public function getAllPh2($id) {
        return $this->db->select('id,user_id,status,amount,rem_amount,date_of_ph,date_of_gh,recom')
            ->from('ph')
            ->where('status >', 0)
            ->where('id', $id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getProblemPh($id) {
        return $this->db->select('id,status,rem_amount')
            ->from('ph')
            ->where('status', 6)
            ->where('user_id', $id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getPh4Merge($amt=0,$limit = 10,$recom=NULL) {
        if ($amt == 0){
        return $this->db->select('user_id,p.username,recom,re_ph,rem_amount,date_of_ph,date_of_gh,status')
            ->from('ph p')
            ->join('users u', 'u.id = p.user_id')
            ->where('p.status', 1)
            ->where('p.rem_amount >', 0)
            ->where('u.locked', 0)
            ->where('u.active', 1)
            ->where('u.deleted', 0)
            ->order_by('rem_amount', 'ASC')
            ->order_by('date_of_ph', 'ASC')
            ->order_by('p.id', 'ASC')
            ->limit($limit)
            ->get()->result();
    }
    elseif ($amt > 1 && $recom=="1"){
        return $this->db->select('user_id,p.username,recom,re_ph,rem_amount,date_of_ph,date_of_gh,status')
            ->from('ph p')
            ->join('users u', 'u.id = p.user_id')
            ->where('p.status', 1)
            ->where('p.recom', 1)
            ->where('p.rem_amount >', 0)
            ->where('p.rem_amount', $amt)
            ->where('u.locked', 0)
            ->where('u.active', 1)
            ->where('u.deleted', 0)
            ->order_by('rem_amount', 'DESC')
            ->order_by('date_of_ph', 'ASC')
            ->order_by('p.id', 'ASC')
            ->limit($limit)
            ->get()->result();
    }

        elseif ($amt > 1 && $recom == "0"){
            return $this->db->select('user_id,p.username,recom,re_ph,rem_amount,date_of_ph,date_of_gh,status')
                ->from('ph p')
                ->join('users u', 'u.id = p.user_id')
                ->where('p.status', 1)
                ->where('p.recom', NULL)
                ->where('p.rem_amount >', 0)
                ->where('p.rem_amount', $amt)
                ->where('u.locked', 0)
                ->where('u.active', 1)
                ->where('u.deleted', 0)
                ->order_by('rem_amount', 'DESC')
                ->order_by('date_of_ph', 'ASC')
                ->order_by('p.id', 'ASC')
                ->limit($limit)
                ->get()->result();
        }

    else{
            return $this->db->select('user_id,p.username,recom,re_ph,rem_amount,date_of_ph,date_of_gh,status')
                ->from('ph p')
                ->join('users u', 'u.id = p.user_id')
                ->where('p.status', 1)
                ->where('p.rem_amount >', 0)
                ->where('p.rem_amount', $amt)
                ->where('u.locked', 0)
                ->where('u.active', 1)
                ->where('u.deleted', 0)
                ->order_by('rem_amount', 'DESC')
                ->order_by('date_of_ph', 'ASC')
                ->order_by('p.id', 'ASC')
                ->limit($limit)
                ->get()->result();
        }
    }

    public function getPh4MergeSingle($id) {
        return $this->db->select('user_id,username,rem_amount,date_of_ph,date_of_gh,status')
            ->from('ph')
            ->where('status', 1)
            ->where('rem_amount >', 0)
            ->where('user_id', $id)
            ->order_by('rem_amount', 'DESC')
            ->order_by('date_of_ph', 'ASC')
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get()->row();
    }

    public function getPh4MergeSingle2($amount) {

            return $this->db->select('p.id,user_id,p.username,recom,rem_amount,date_of_ph,date_of_gh,status')
                ->from('ph p')
                ->join('users u', 'u.id = p.user_id')
                ->where('p.status', 1)
                ->where('p.rem_amount >', 0)
                ->where('p.rem_amount <=', $amount)
                ->where('u.locked', 0)
                ->where('u.active', 1)
                ->where('u.deleted', 0)
                ->order_by('rem_amount', 'DESC')
                ->order_by('date_of_ph', 'ASC')
                ->order_by('p.id', 'ASC')
                ->limit(1)
            ->get()->row();
    }

    public function getPh4MergeSingleBonus($id) {
        return $this->db->select('user_id,username,rem_amount,date_of_ph,date_of_gh,status')
            ->from('ph')
            ->where('status', 1)
            ->where('rem_amount >', 0)
            ->where('user_id', $id)
            ->order_by('rem_amount', 'DESC')
            ->order_by('date_of_ph', 'ASC')
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get()->row();
    }

    public function getPh4Gh() {
        return $this->db->select('user_id,username,rem_amount,date_of_gh,status')
            ->from('ph')
            ->where('status', 4)
            ->where('rem_amount >', 0)
            ->order_by('id', 'DESC')
            ->get()->result();
    }

    public function getLastRecom2($id) {
        return $this->db->select('id,amount,date_of_gh')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status', '5')
            ->where('recom', 1)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getLastRePH($id) {
        return $this->db->select('id,amount,date_of_gh,plan_id')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status', '5')
            ->where('recom', NULL)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getLastPh($id) {
        return $this->db->select('id,amount,date_of_gh')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status', '4')
            ->where('recom', NULL)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getLastPh2($id) {
        return $this->db->select('id,amount,date_of_gh')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status', '4')
            ->where('recom', 1)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function getLastPh3($id) {
        return $this->db->select('id,amount,date_of_gh')
            ->from('ph')
            ->where('user_id', $id)
            ->where('status <', '4')
            ->where('recom', 1)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function checkGhDate($id) {
        return $this->db->select('date_of_gh,amount')
            ->from('ph')
            ->where('user_id', $id)
            ->where('recom', NULL)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }


    public function getFirstLevelBonus($id,$limit=5) {
        return $this->db->select('user_id,username,first_bonus_status,first_bonus_id,amount,rem_amount,date_of_gh,status')
            ->from('ph')
            ->where('status <', 5)
            ->where('first_bonus_status', 1)
            ->where('first_bonus_id', $id)
            ->order_by('id', 'DESC')
            ->limit($limit)
            ->get()->result();
    }

    public function getFirstLevelTotal($id)
    {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('ph')
            ->where('status <', 5)
            ->where('first_bonus_status', 1)
            ->where('first_bonus_id', $id)
            ->get()->row()->total;
    }

    public function getSecondLevelBonus($id,$limit=5) {
        return $this->db->select('user_id,username,second_bonus_status,second_bonus_id,amount,rem_amount,date_of_gh,status')
            ->from('ph')
            ->where('status <', 5)
            ->where('second_bonus_status', 1)
            ->where('second_bonus_id', $id)
            ->order_by('id', 'DESC')
            ->limit($limit)
            ->get()->result();
    }

    public function getSecondLevelTotal($id)
    {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('ph')
            ->where('status <', 5)
            ->where('second_bonus_status', 1)
            ->where('second_bonus_id', $id)
            ->order_by('id', 'DESC')
            ->get()->row()->total;
    }

    public function countFbl($id) {
        return $this->db->select("count(*) c", FALSE)
            ->from('ph')
            ->where('status <', 5)
            ->where('first_bonus_status', 1)
            ->where('first_bonus_id', $id)
            ->order_by('id', 'DESC')
            ->get()->row()->c;
    }

    public function countSysBonus($id) {
        return $this->db->select("count(*) c", FALSE)
            ->from('ph')
             ->where('system_bonus_status ', 1)
            ->where('recom ', NULL)
            ->where('status > ', 3)
            ->where('status < ', 6)
            ->where('user_id', $id)
            ->order_by('id', 'ASC')
            ->get()->row()->c;
    }

    public function sysBonusTotal($id)
    {
        //get las 5
        return $this->db->select('sum(amount) total', FALSE)
            ->from('ph')
            ->where('system_bonus_status ', 1)
            ->where('recom ', NULL)
            ->where('status > ', 3)
            ->where('status < ', 6)
            ->where('user_id', $id)
            ->order_by('id', 'ASC')
            ->limit(4)
            ->get()->row()->total;
    }

    public function getSysBonus($id)
    {
        //get las 5
        return $this->db->select('amount', FALSE)
            ->from('ph')
            ->where('system_bonus_status ', 1)
            ->where('recom ', NULL)
            ->where('status > ', 3)
            ->where('status < ', 6)
            ->where('user_id', $id)
            ->order_by('id', 'ASC')
            ->limit(4)
            ->get()->row();
    }


    public function getAvailbleBonusSumFirst($id)
    {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('ph')
            ->where('status', 4)
            ->where('first_bonus_status', 1)
            ->where('first_bonus_id', $id)
            ->get()->row()->total;
    }

    public function getAvailbleBonusSumSecond($id)
    {
        return $this->db->select('sum(amount) total', FALSE)
            ->from('ph')
            ->where('status', 4)
            ->where('second_bonus_status', 1)
            ->where('second_bonus_id', $id)
            ->get()->row()->total;
    }

    public function getAvailableSumTotal($id){
         $f = $this->getAvailbleBonusSumFirst($id) * 0.05;
        $s =  $this->getAvailbleBonusSumSecond($id) * 0.025;
        return $f + $s;
    }



    public function getFirstAvailableBonus($id,$limit=5) {
        return $this->db->select('user_id,username,first_bonus_status,first_bonus_id,amount,rem_amount,date_of_gh,status')
            ->from('ph')
            ->where('status', 4)
            ->where('first_bonus_status', 1)
            ->where('first_bonus_id', $id)
            ->order_by('id', 'DESC')
            ->limit($limit)
            ->get()->result();
    }

    public function getSecondAvailableBonus($id,$limit=5) {
        return $this->db->select('user_id,username,second_bonus_status,second_bonus_id,amount,rem_amount,date_of_gh,status')
            ->from('ph')
            ->where('status', 4)
            ->where('second_bonus_status', 1)
            ->where('second_bonus_id', $id)
            ->order_by('id', 'DESC')
            ->limit($limit)
            ->get()->result();
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
       $n = $this->db->where('id', $id)->update('ph', $data);
        if ($n){
            return true;
        }

    }

    public function updateTimeUp($id, $data) {
        $n =   $this->db->where('payer_user_id', $id)->update('payment', $data);
        if ($n){
            return true;
        }

    }

    public function updatePHmerge($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('ph', $data);

        return TRUE;
    }

    public function updatePHupload($id, $data) {
        $n =   $this->db->where('id', $id)->update('ph', $data);
        if ($n){
            return true;
        }

    }

    public function updatePHapprove($id, $data) {
        $n =   $this->db->where('id', $id)->update('ph', $data);
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