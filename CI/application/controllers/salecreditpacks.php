<?php

/**
* 
*/
class salecreditpacks extends MY_Controller
{
	public function index()
	{

		#PROFILE INFO 
		$profileInfo = $this->profile;	

		$this->load->helper('date');

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$sale['websitename'] = $websitenameee;

		$this->load->model('buyerandsellermodel','sellmodel');

		$adpackprice = $this->sellmodel->getadpackprice();

		foreach ($adpackprice as $adp) {
			# code...
			$oneadpackprice = $adp->amount;
		}

		$sale['onepackbalance'] = $oneadpackprice;

		$sale['username'] = $username;

		$pack_balance = $this->sellmodel->index($userid);

		foreach ($pack_balance as $pb) {
			# code...
			$packbalance = $pb->ad_pack_balance;

			$btc_add = $pb->account;

			$amount_balanceee = $pb->amount_balance;
			
		}

		$sale['pack_balance'] = $packbalance;

		$sale['amount_balance'] = $amount_balanceee;

		$completeData = $this->sellmodel->getcompletedata($userid);

		$sale['completedata'] = $completeData;

		$checkpendingrequest = $this->sellmodel->checkforpendingrequest($userid);

		foreach ($checkpendingrequest as $cpr) {
			# code...
			$checkingpending = $cpr->status;

			if(($checkingpending === 'in progress') || ($checkingpending === 'pending'))
			{
				$sale['pendingrequest'] = "no";
			}
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('sellvaluesubmit','Sell Value','required');

		if($this->form_validation->run())
		{
			$creditvaluetosell = $this->input->post('creditsellvalue');

			$insertSellerData = array(

					'user_id'=>$userid,
					'seller_btc'=>$btc_add,
					'credit_packs'=>$creditvaluetosell,
					'categeory'=>'sell',
					'status'=>'pending'
				);

			if($packbalance < $creditvaluetosell)
			{
				$sale['confirmationmessage'] = "You Dont Have Sufficient Balance To Sell Your Credits";
			}
			else
			{
				
				$sale['confirmationmessage'] = "Your Request Have Been Submitted";

				$this->sellmodel->insertDepositData($insertSellerData);

				$checkbalance = $this->sellmodel->index($userid);

				foreach ($checkbalance as $cb) {
					# code...
					$adpackbalance = $cb->ad_pack_balance;
				}

					$adpackbalancee = $adpackbalance - $creditvaluetosell;

					$adpacks = array(
						'ad_pack_balance'=>$adpackbalancee
						);

					$this->sellmodel->updatepacks($userid, $adpacks);

					redirect('/salecreditpacks');

			}

			

		}

		$this->load->view('salecredits',['sale'=>$sale]);
	}
	
}

?>