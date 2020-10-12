<?php

require_once APPPATH."vendor/autoload.php";

use Blocktrail\SDK\BlocktrailSDK;
use \Blocktrail\SDK\Connection\Exceptions\InvalidCredentials;

/**
* 
*/
class deposit extends MY_CONTROLLER
{
	public function index()
	{

		$profileInfo = $this->profile;

		$creditValue = '';
		
		$datestring = '%Y-%m-%d %h:%i %a';

			$time = time();

			$CurrentTime = mdate($datestring, $time);

			$DateToPay = time() + (24 * 60 * 60);

			$datetodelete = $DateToPay;

			$PayTill = mdate($datestring, $DateToPay);



		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$deposit['username'] = $username;

		$this->load->model('salemodel','buy');


		$pack_balance = $this->buy->index($userid);

		foreach ($pack_balance as $pb) {
			# code...
			$packbalance = $pb->ad_pack_balance;

			$btc_add = $pb->account;
			
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('buyvaluesubmit','Sell Value','required');

		if ($this->form_validation->run()) {
			# code...
			$creditValue = $this->input->post('creditbuyvalue');

			$AmountofOneAd = 0.01619;

			$TotalAmount = $AmountofOneAd * $creditValue;



			$insertData = array(

				'user_id'=>$userid,
				'btc_address'=>$btc_add,
				'amount'=>$TotalAmount,
				'ad_credits'=>$creditValue,
				'dateandtime'=>$CurrentTime,
				'datetopaytill'=>$PayTill
				);	

			$this->buy->insertBuyerInfo($insertData);

			$deposit['TotalAmount'] = $TotalAmount;

		}


		$Buyerinformation = $this->selectBuyer();

		$idforbuyerinfo = $Buyerinformation['id'];

		$useridforbuyerinfo = $Buyerinformation['user_id'];

		$btcaddforbuyerinfo = $Buyerinformation['btc_address'];

		$amounttopay = $Buyerinformation['amount'];

		$ad_credits_ofbuyer = $Buyerinformation['ad_credits'];

		$btcselleraddress1 = array(
			'seller_btc'=>$btcaddforbuyerinfo
			);

		$infotopass = array(
			'user_id'=>$userid,
			'btc_address'=>$btc_add,
			'amount'=>$amounttopay
			);

		$this->buy->updateSellerBtcAddress($infotopass, $btcselleraddress1);

		$updateStatusSeller1 = array(
			'status'=>'1'
			);

		$this->buy->updateStatus($idforbuyerinfo,$updateStatusSeller1);

		$selecttimefinished = $this->buy->selecttimefinished($userid);

		foreach ($selecttimefinished as $timefinished) {
			# code...
			$datefinished = $timefinished->datetopaytill;

			$idtodelete = $timefinished->id;

			if ($datefinished < $CurrentTime) {
				# code...
				$this->buy->deletetimefinishedads($idtodelete);
			}
		}

		$showsellerbtc = $this->buy->ShowBtcAndAmount($idtodelete);

		foreach ($showsellerbtc as $sbtc) {
			# code...
			$deposit['btc_add'] = $sbtc->seller_btc;

			$deposit['amount'] = $sbtc->amount;

		}

		if($this->input->post('submitPaymentInfo'))
		{
			$hash = $this->input->post('hashPayment');

			$exactpay = $this->input->post('exactPayment');

			$message = $this->paymentConfirmation($btc_add, $btcaddforbuyerinfo, $hash, $exactpay);

			$amountfromblocktrail = $message['exactamount'];

			$messagefromblocktrail = $message['message'];

			$checkbalance = $this->buy->getBuyerBalance($userid);

			foreach ($checkbalance as $bal) {
				# code...
				$buyerbalance = $bal->ad_pack_balance;

			}

			$totalbalance = $ad_credits_ofbuyer + $buyerbalance;

			$sellerbalance = $this->buy->getSellerBalance($useridforbuyerinfo);

			foreach ($sellerbalance as $sel) {
				# code...
				$sellerbalancee = $sel->ad_pack_balance;
			}

			$sellertotalbalance = $sellerbalancee - $ad_credits_ofbuyer;

			$sellerbalanceupdate = array(
					'ad_pack_balance'=>$sellerbalanceupdate
				);

			$totalbalanceupdate = array(
				'ad_pack_balance'=>$totalbalance
				);

			if ($messagefromblocktrail === 'Payment Confirmed') {
				# code...
				if ($amountfromblocktrail === $exactpay) {
					# code...
					$this->buy->updateadcreditspack($userid, $totalbalanceupdate);

					$this->buy->updateadcreditspack($useridforbuyerinfo, $totalbalanceupdate);
				}
			}

		}



		$deposit['pack_balance'] = $pb->ad_pack_balance;
		
		$this->load->view('depositview',['deposit'=>$deposit]);


	}

	public function selectBuyer()
	{

		$profileInfo = $this->profile;

		$this->load->model('salemodel','buy');

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$Userdata = $this->buy->selecttimefinished($userid);

		foreach ($Userdata as $data) {
			# code...
			$btc_address = $data->btc_address;

			$ad_credits = $data->ad_credits;

		}

		$Buyer = $this->buy->getSeller($ad_credits);

		foreach ($Buyer as $buyers) {
			# code...

			$idforbuyer = $buyers->id;

			$useridforbuyer = $buyers->user_id;

			$btcforbuyer = $buyers->btc_address;

			$creditValue = $buyers->ad_packs;


		}

		$Amount = $creditValue * 0.01619;

		$buyerInformation = array(

				'id'=>$idforbuyer,
				'user_id'=>$useridforbuyer,
				'btc_address'=>$btcforbuyer,
				'amount'=>$Amount,
				'ad_credits'=>$ad_credits
			);


		return $buyerInformation;


	}
	public function paymentConfirmation($senderBTC, $receiverBTC, $hash, $extAmount)
	{
		$message = '';

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		try
 		{

		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

		$transaction_information = $client->transaction($hash);

		$hashReceived = $transaction_information['hash'];

		$senderAddress = $transaction_information['estimated_change_address'];

		$receiverAddress = $transaction_information['outputs']['1']['address'];

		$exactamount = $transaction_information['estimated_value'];

		$exact_amount = BlocktrailSDK::toBTC($exactamount);

		$confirmation = $transaction_information['confirmations'];


		if ($senderAddress === $senderBTC && $receiverAddress === $receiverBTC && $hashReceived === $hash && $exact_amount === $extAmount) {
			# code...
			if($confirmations > 0)
			{
				$message = 'Payment Confirmed';
			}
		}
		else{
			$message = 'Payment Not Confirmed';
		}
	}
	catch(Exception $e)
	{
		$message = 'Invalid Error';
	}

		$paymentinfotoindex = array(
			'exactamount'=>$exact_amount,
			'message'=>$message
			);

		return $paymentinfotoindex;

		

	}


}

?>