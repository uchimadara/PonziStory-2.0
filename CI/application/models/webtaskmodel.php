<?php

/**
* 
*/
class webtaskmodel extends CI_Model
{
	public function plan_details($plan_name)
	{
		$data = $this->db->select('*')
				->from('Webtaskplandetails')
				->where('plan_name',$plan_name)
				->get();

				return $data->result();
	}
	public function EditDetails($PlanName, $EditDetails)
	{
		$this->db->from('Webtaskplandetails')
		->where('plan_name', $PlanName)
		->set($EditDetails)
		->update();
	}
	public function getBalanceDetails($userid)
	{
		$data = $this->db->select('amount_balance, bronze_shares, silver_shares, gold_shares')
				->from('user_payment_method')
				->where('user_id', $userid)
				->get();

				return $data->result();
	}
	public function GetBronzeSharePrice()
	{
		$data = $this->db->select('*')
				->from('Webtaskplandetails')
				->where('plan_name','Bronze')
				->get();

				return $data->result();
	}
	public function GetSilverSharePrice()
	{
		$data = $this->db->select('*')
				->from('Webtaskplandetails')
				->where('plan_name','Silver')
				->get();

				return $data->result();
	}
	public function GetGoldSharePrice()
	{
		$data = $this->db->select('*')
				->from('Webtaskplandetails')
				->where('plan_name','Gold')
				->get();

				return $data->result();
	}
	public function updateInfo($userid, $updateInfo)
	{
		$this->db->from('user_payment_method')
			->where('user_id', $userid)
			->set($updateInfo)
			->update();
	}
	public function insertTask($taskDetails)
	{
		$this->db->insert('webtasks_admin',$taskDetails);
	}
	public function getWebTasks()
	{
		$data = $this->db->select('*')
				->from('webtasks_admin')
				->get();
				return $data->result();
	}
	public function getDetailsForTasks($id)
	{
		$data = $this->db->select('*')
				->from('webtasks_admin')
				->where('id',$id)
				->get();

				return $data->result();
	}
	public function insertforapproval($infotoinsert)
	{
		$this->db->insert('web_tasks_for_approval',$infotoinsert);
	}
	public function getWebtasksforapproval()
	{
		$data = $this->db->select('*')
				->from('web_tasks_for_approval')
				->get();

				return $data->result();
	}
	public function updateStatusOnTaskApproval($id, $updateStatus)
	{
		$this->db->from('web_tasks_for_approval')
				->where('id', $id)
				->set($updateStatus)
				->update();
	}
	public function updateInfoForBuyingShares($insertInfo){
		$this->db->insert('buy_ad_packs',$insertInfo);
	}
	public function GetMaxPercentageSilver($username)
	{
		$data = $this->db->select('*')
				->from('buy_ad_packs')
				->where('username',$username)
				->where('status','active')
				->where('ad_pack','Silver')
				->get();

				return $data->result();
	}
	public function GetMaxPercentageBronze($username)
	{
		$data = $this->db->select('*')
				->from('buy_ad_packs')
				->where('username',$username)
				->where('status','active')
				->where('ad_pack','Bronze')
				->get();

				return $data->result();
	}
	public function GetMaxPercentageGold($username)
	{
		$data = $this->db->select('*')
				->from('buy_ad_packs')
				->where('username',$username)
				->where('status','active')
				->where('ad_pack','Gold')
				->get();

				return $data->result();
	}
	public function updatemaxpercentage($id, $updatemaxpercentage)
	{
		$this->db->from('buy_ad_packs')
			->where('id',$id)
			->set($updatemaxpercentage)
			->update();
	}
	public function getHistory($username)
	{
		$data = $this->db->from('buy_ad_packs')
		->where('username',$username)
		->get();

		return $data->result();
	}
	public function updateAmount($userid, $amount)
	{
		$this->db->from('user_payment_method')
			->where('user_id',$userid)
			->set($amount)
			->update();
	}
	public function GetAllTaskss()
	{
		$data = $this->db->select('*')
				->from('webtasks_admin')
				->get();

				return $data->result();

	}
	public function getpostbyid($id)
	{
		$data = $this->db->select('*')
			->from('webtasks_admin')
			->where('id',$id)
			->get();

			return $data->result();
	}
	public function edittask($id, $updateinfo)
	{
		$this->db->from('webtasks_admin')
		->where('id',$id)
		->set($updateinfo)
		->update();
	}
	public function getRoyaltyShares()
	{
		$data = $this->db->select('*')
			->from('user_payment_method')
			->where('royalty_positions !=','0')
			->get();

			return $data->result();
	}
	public function updatePaymentDividend($userid, $updateamoutn)
	{
		$this->db->from('user_payment_method')
			->where('user_id',$userid)
			->set($updateamoutn)
			->update();
	}
	public function getUsername($user_id)
	{
		
		$data = $this->db->select('username')
				->from('users')
				->where('id',$user_id)
				->get();

				return $data->result();
	}
	public function insertDividends($insertArray)
	{
		$this->db->insert('dividends',$insertArray);
	}
	public function getlistoftaskss($username)
	{
		$data = $this->db->select('*')
				->from('web_tasks_for_approval')
				->where('user_name', $username)
				->order_by('id','desc')
				->limit(1)
				->get();

				return $data->result();

	}
	public function getEmail($username)
	{
		$data = $this->db->select('email')
				->from('users')
				->where('username', $username)
				->get();

				return $data->result();
	}


}

?>