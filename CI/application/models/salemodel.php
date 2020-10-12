<?php


/**
* 
*/
class salemodel extends CI_Model
{
	public function index($user_id)
	{
		$pack_balance = $this->db->select('ad_pack_balance, account')
						->from('user_payment_method')
						->where('user_id',$user_id)
						->get();

						return $pack_balance->result();
	}
	public function insertsellerinfo($sellerinfo)
	{
		$this->db->insert('seller_adcredits',$sellerinfo);
	}
	public function getpendingrequest($user_id)
	{
		$get_pending_request = $this->db->select('*')
								->from('seller_adcredits')
								->where('user_id',$user_id)
								->get();

								return $get_pending_request->result();
	}
	public function insertBuyerInfo($buyerinfo)
	{
		$this->db->insert('buyer_adcredits',$buyerinfo);
	}
	public function selecttimefinished($userid)
	{
		$selecttimefinished = $this->db->select('id, user_id, btc_address, hash, seller_btc, ad_credits, datetopaytill')
							->from('buyer_adcredits')
							->where('user_id' ,$userid)
							->get();

							return $selecttimefinished->result();
	}
	public function updateSellerBtcAddress($infotopass, $btcseller)
	{
		$this->db->from('buyer_adcredits')
		->where($infotopass)
		->set($btcseller)
		->update();
	}
	public function deletetimefinishedads($id)
	{
		$deletetime = $this->db->select('*')
		->from('buyer_adcredits')
		->where('hash','')
		->where('id',$id)
		->delete();

		return $deletetime->result();
	}
	public function getSeller($ad_credits)
	{
		$getBuyer = $this->db->select('*')
					->from('seller_adcredits')
					->where('status','0')
					->where('ad_packs',$ad_credits)
					->limit(1)
					->get();
					return $getBuyer->result();
	}
	public function updateStatus($id, $dataupdate)
	{
		$updateStatus = $this->db->select('*')
						->from('seller_adcredits')
						->where('id',$id)
						->set($dataupdate)
						->update();
	}
	public function updateStatusforSeller($id, $updatedata)
	{
		$updateStatus = $this->db->select('*')
						->from('seller_adcredits')
						->where('id',$id)
						->set($updatedata)
						->update();
	}
	public function ShowBtcAndAmount($id)
	{
		$showsellerbtc = $this->db->select('seller_btc, amount')
						->from('buyer_adcredits')
						->where('id',$id)
						->get();

						return $showsellerbtc->result();
	}
	public function getBuyerBalance($userid)
	{
		$getbalance = $this->db->select('ad_pack_balance')
						->from('user_payment_method')
						->where('user_id' ,$userid )
						->get();

						return $getbalance->result();
	}
	public function updateadcreditspack($userid,$totalbalanceupdate)
	{
		$this->db->from('user_payment_method')
				->where('user_id',$userid)
				->set($totalbalanceupdate)
				->update();
	}
	
}

?>