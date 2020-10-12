<?php

/**
* 
*/
class marketplacemodel extends CI_Model
{

	public function index($userid)
	{
		$data = $this->db->select('*')
			->from('user_payment_method')
			->where('user_id',$userid)
			->get();

			return $data->result();
	}
	public function sell_royalty_positions($datatoinsert)
	{	
		$this->db->insert('buyingandsellingroyaltypositions',$datatoinsert);
	}
	public function seller_info_toupdate($userid, $updateinfo)
	{
		$this->db->from('user_payment_method')
		->where('user_id',$userid)
		->set($updateinfo)
		->update();
	}
	public function Get_RP_For_Sale()
	{
		$data = $this->db->from('buyingandsellingroyaltypositions')
				->where('status','pending')
				->where('categeory','sell')
				->order_by('amount_per_rp','asc')
				->get();

				return $data->result();
	}
	public function updateBalanceOnBuy($userid, $updateBalanceOnBuy)
	{
		$this->db->from('user_payment_method')
		->where('user_id',$userid)
		->set($updateBalanceOnBuy)
		->update();
	}
	public function updateRPwhencompleted($id, $updateStatus)
	{
		$this->db->from('buyingandsellingroyaltypositions')
		->where('id',$id)
		->set($updateStatus)
		->update();
	}
	public function insertDataToBid($insertData)
	{
		$this->db->insert('buyingandsellingroyaltypositions',$insertData);
	}	
	public function getBidData()
	{
		$data = $this->db->select('*')
				->from('buyingandsellingroyaltypositions')
				->where('categeory','bid')
				->where('status','pending')
				->get();

				return $data->result();
	}
	public function getBidderrp($userid)
	{
		$data = $this->db->select('royalty_positions')
			->from('user_payment_method')
			->where('user_id',$userid)
			->get();

			return $data->result();
	}
	public function getPendingSales($userid)
	{
		$data = $this->db->select('*')
				->from('buyingandsellingroyaltypositions')
				->where('user_id',$userid)
				->where('status','pending')
				->where('categeory','sell')
				->get();

				return $data->result();
	}
	public function getActiveBids($userid)
	{
		$data = $this->db->select('*')
				->from('buyingandsellingroyaltypositions')
				->where('user_id',$userid)
				->Where('status','pending')
				->where('categeory','bid')
				->get();

				return $data->result();
	}
	public function getRpValueForBidder($id)
	{
		$data = $this->db->select('*')
			->from('buyingandsellingroyaltypositions')
			->where('id', $id)
			->get();

			return $data->result();
	}
	public function getAllDividendsPaid()
	{
		$data = $this->db->select('dividends_paid')
				->from('dividends')
				->get();
				return $data->result();
	}
	public function historydividends($username)
	{
		$data = $this->db->select('*')
			->from('dividends')
			->where('username', $username)
			->get();

			return $data->result();
	}
	public function deletependingsales($id)
	{
		$this->db->from('buyingandsellingroyaltypositions')
			->where('id', $id)
			->delete();
	}

}

?>