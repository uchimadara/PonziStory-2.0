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

		$this->load->model('buyerandsellermodel','sellmodel');

		$sale['username'] = $username;

		$pack_balance = $this->sellmodel->index($userid);

		foreach ($pack_balance as $pb) {
			# code...
			$packbalance = $pb->ad_pack_balance;

			$btc_add = $pb->account;
			
		}

		$sale['pack_balance'] = $packbalance;

		$completeData = $this->sellmodel->getcompletedata($userid);

		$sale['completedata'] = $completeData;

		$checkpendingrequest = $this->sellmodel->checkforpendingrequest($userid);

		if(!empty($checkpendingrequest))
		{
			$sale['pendingrequest'] = "no";
		}
		else{
			$sale['pendingrequest'] = "yes";
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
			}
		}

		$this->load->view('salecredits',['sale'=>$sale]);
	}
}

?>