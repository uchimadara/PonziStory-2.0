<?php

/**
* 
*/
class buyerandsellermodel extends CI_Model
{
	public function index($user_id)
	{
		$pack_balance = $this->db->select('ad_pack_balance, account, amount_balance')
						->from('user_payment_method')
						->where('user_id',$user_id)
						->get();

						return $pack_balance->result();
	}
	public function getalldatatodelete()
	{
		$getdata = $this->db->select('*')
					->from('buyingandsellingadcredits')
					->where('hash','')
					->where('categeory','deposit')
					->get();

					return $getdata->result();
	}
	public function getcompletedata($userid)
	{
		$completedata = $this->db->select('*')
						->from('buyingandsellingadcredits')
						->where('user_id',$userid)
						->get();

						return $completedata->result();
	}
	public function checkforpendingrequest($userid)
	{
		$pendingrequest = $this->db->select('*')
							->from('buyingandsellingadcredits')
							->where('user_id',$userid)
							->get();

							return $pendingrequest->result();
	}
	public function insertDepositData($insertDepositData)
	{
		$this->db->insert('buyingandsellingadcredits',$insertDepositData);
	}
	public function gettingBuyerInfo($adcredits)
	{
		$gettingBuyer = $this->db->select('*')
						->from('buyingandsellingadcredits')
						->where('categeory','sell')
						->where('status','pending')
						->where('credit_packs',$adcredits)
						->where('hash','')
						->limit(1)
						->get();

						return $gettingBuyer->result();

	}
	public function getBuyerCreditInfo($userid){

		$gettingSellerCreditInfo = $this->db->select('*')
									->from('buyingandsellingadcredits')
									->where('categeory','deposit')
									->where('status','pending')
									->where('user_id',$userid)
									->get();

									return $gettingSellerCreditInfo->result();

	}
	public function getSeller($userid, $adcredits)
	{
		$Seller = $this->db->select('*')
				->from('buyingandsellingadcredits')
				->where('user_id !=',$userid)
				->where('status','pending')
				->where('categeory','sell')
				->where('credit_packs',$adcredits)
				->limit(1)
				->get();

				return $Seller->result();
	}
	public function updateSellerBtcInBuyer($buyerinfo, $sellerbtc)
	{
		$this->db->from('buyingandsellingadcredits')
				->where($buyerinfo)
				->set($sellerbtc)
				->update();
	}
	public function getCurrentStatusOfBtc($userid, $sellercreditpacks)
	{
		$getCurrentStatus = $this->db->select('*')
							->from('buyingandsellingadcredits')
							->where('user_id', $userid)
							->where('categeory','deposit')
							->where('status','pending')
							->get();

							return $getCurrentStatus->result();
	}
	public function deleterequestattime($timetopaytill)
	{
		$this->db->select('*')
		->from('buyingandsellingadcredits')
		->where('dateandtimetopaytill',$timetopaytill)
		->where('status','pending')
		->where('hash','')
		->delete();
	}
	public function updatebacksellerstatus($id, $updatebackstatus)
	{
		$this->db->from('buyingandsellingadcredits')
		->where('id',$id)
		->set($updatebackstatus)
		->update();
	}
	public function updatestatusofbuyer($id, $confirmstatus)
	{
		$this->db->from('buyingandsellingadcredits')
		->where('id',$id)
		->set($confirmstatus)
		->update();
	}
	public function updatepacks($userid, $packsupdate)
	{
		$this->db->from('user_payment_method')
		->where('user_id',$userid)
		->set($packsupdate)
		->update();
	}
	public function updateSellerStatus($id, $statusupdate)
	{
		$this->db->from('buyingandsellingadcredits')
		->where('id',$id)
		->set($statusupdate)
		->update();
	}
	public function updateBuyerStatus($matchinfo, $statusupdate)
	{
		$this->db->from('buyingandsellingadcredits')
		->where($matchinfo)
		->set($statusupdate)
		->update();
	}
	public function updateBuyerBalance($userid,$balanceupdate)
	{
		$this->db->from('user_payment_method')
		->where('user_id',$userid)
		->set($balanceupdate)
		->update();
	}
	public function selectingalldata()
	{
		$data = $this->db->select('*')
		->from('buyingandsellingadcredits')
		->where('status','not confirmed yet')
		->where('categeory','deposit')
		->get();

		return $data->result();

	}
	public function selectingalldataforseller()
	{
		$data = $this->db->select('*')
				->from('buyingandsellingadcredits')
				->where('status','not confirmed yet')
				->where('categeory','sell')
				->get();

				return $data->result();

	}
	public function deletetimeended($idfordelete)
	{
		$this->db->from('buyingandsellingadcredits')
		->where('id',$idfordelete)
		->delete();
	}
	public function sellerstatuschangeupontimecompletion($btc)
	{
		$this->db->from('buyingandsellingadcredits')
		->where('seller_btc',$btc)
		->where('status','in progress')
		->set('status','pending')
		->update();
	}
	public function getadpackprice()
	{
		$data = $this->db->select('amount')
				->from('ad_price')
				->where('id','1')
				->where('name','adPrice')
				->get();

				return $data->result();

	}
	public function gethashinfo()
	{
		$data = $this->db->select('hash')
				->from('buyingandsellingadcredits')
				->get();

				return $data->result();
	}

}

?>