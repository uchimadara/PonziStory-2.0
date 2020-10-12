<?php
class Teams_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $this->db->insert('teams', $data);
        return $this->db->insert_id();
    }

    function role_exists($key)
    {
        $this->db->where('team_leader',$key);
        $query = $this->db->get('teams');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }


    public function add($payer_user_id, $payee_user_id, $amount, $created,$expired,
                        $confirmations=0,$currency_amount=0,$approved=NULL,$rejected=NULL,$proof_img,$ug,$mid) {

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

        );
        return $this->db->insert('payment', $data);

        //  return $uuid;
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
        $this->db->where('id', $id)->update('payment', $data);
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