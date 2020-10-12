<?php

/**
* 
*/
class depositmodel extends CI_Model
{
	public function index($user_id)
	{
		$pack_balance = $this->db->select('ad_pack_balance, account')
						->from('user_payment_method')
						->where('user_id',$user_id)
						->get();

						return $pack_balance->result();
	}
	public function getcompletedata($userid)
	{
		$completedata = $this->db->select('*')
						->from('buyingandsellingadcredits')
						->where('user_id',$userid)
						->where('categeory','deposit')
						->get();

						return $completedata->result();
	}
	public function checkforpendingrequest($userid)
	{
		$pendingrequest = $this->db->select('*')
							->from('buyingandsellingadcredits')
							->where('user_id',$userid)
							->where('status','pending')
							->get();

							return $pendingrequest->result();
	}
	public function insertDepositData($insertDepositData)
	{
		$this->db->insert('buyingandsellingadcredits',$insertDepositData);
	}

}

?>