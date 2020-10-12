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
		$this->cronjob_paymentconfirmationfordeposit();

		$profileInfo = $this->profile;
		
		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

		$user_id = '';

		$deposit['amountofonead'] = '0.0001471';

		$idtoupdate = '';

		$timeended = '';
		
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

		$this->load->model('buyerandsellermodel','depo');

		$pack_balance = $this->depo->index($userid);

		foreach ($pack_balance as $pb) {
			# code...
			$packbalance = $pb->ad_pack_balance;

			$btc_add = $pb->account;
			
		}

		$deposit['pack_balance'] = $packbalance;

		$getcompletedata = $this->depo->getcompletedata($userid);

		$deposit['completedata'] = $getcompletedata;

		$checkforpendingrequest = $this->depo->checkforpendingrequest($userid);

		foreach ($checkpendingrequest as $cpr) {
			# code...
			$checkingpending = $cpr->status;

			if(($checkingpending === 'in progress') || ($checkingpending === 'pending'))
			{
				$deposit['pendingrequest'] = "no";
			}
		}
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('buyvaluesubmit','Buy Value','required');

		if ($this->form_validation->run()) {
			# code...
			$creditValue = $this->input->post('creditbuyvalue');

			$AmountofOneAd = '0.0001471';

			$deposit['amountofonead'] = $AmountofOneAd;

			$TotalAmount1 = $AmountofOneAd * $creditValue;

			$zeronumber = '0';

			$TotalAmount = $TotalAmount1.$zeronumber;

			$insertDepositData = array(
				'user_id'=>$userid,
				'credit_packs'=>$creditValue,
				'categeory'=>'deposit',
				'amount'=>$TotalAmount,
				'currentdateandtime'=>$CurrentTime,
				'dateandtimetopaytill'=>$PayTill,
				'status'=>'pending'
				);

			$this->depo->insertDepositData($insertDepositData);

			$sellerinfo = $this->getSellerInfo($userid);

		}

		$selleruserid = $sellerinfo['seller_userid'];

		$sellerbtcadd = $sellerinfo['seller_btcadd'];

		$sellercreditpacks = $sellerinfo['seller_creditpacks'];

		$sellerid = $sellerinfo['seller_id'];

		$idtoupdate = $sellerid;

		$deposit['btc_add'] = $sellerbtcadd;

		$sellerstatus = array(
			'status'=>'in progress'
			);

		$checking = $this->depo->updateSellerStatus($sellerid, $sellerstatus);

		$sellerbtcaddressupdate = array(
			'seller_btc'=>$sellerbtcadd
			);

		$buyerinfo = array(
			'user_id'=>$userid,
			'credit_packs'=>$sellercreditpacks,
			'status'=>'pending'
			);

		$this->depo->updateSellerBtcInBuyer($buyerinfo, $sellerbtcaddressupdate);

		$getCurrentStatus = $this->depo->getCurrentStatusOfBtc($userid, $sellercreditpacks);

		foreach ($getCurrentStatus as $gcs) {
			# code...
			$currentstatusid = $gcs->user_id;

			$currentstatussellerbtc = $gcs->seller_btc;

			$timetopaytillforbuyer = $gcs->dateandtimetopaytill;

			$creditpackstobuy = $gcs->credit_packs;

			$amounttopay = $gcs->amount;

			$idtocheck = $gcs->id;

		}

		$deposit['amounttopay'] = $amounttopay;

		$deposit['btc_add'] = $currentstatussellerbtc;

		if($this->input->post('submitPaymentInfo'))
		{
			$hash = $this->input->post('hashPayment');

			$exactpayment = $this->input->post('exactPayment');

			$messageReceived = $this->paymentconfirmation($exactpayment, $hash, $currentstatussellerbtc);

			if($exactpayment === $amounttopay)
			{
				$messageReceived = $this->paymentconfirmation($exactpayment, $hash, $currentstatussellerbtc);

				if($messageReceived === 'Confirmed')
				{

					$statusupdate = array(
						'status'=>'completed',
						'hash'=>$hash
					);
	
					$this->depo->updateSellerStatus($idtocheck, $statusupdate);

					$checkingseller = array(
						'credit_packs'=>$creditpackstobuy,
						'seller_btc'=>$currentstatussellerbtc,
						'categeory'=>'sell',
						'status'=>'in progress'
						);
					
					$this->depo->updateBuyerStatus($checkingseller, $statusupdate);

					$getBalance = $this->depo->index($userid);

					foreach ($getBalance as $gb) {
						# code...
						$adpackcredits = $gb->ad_pack_balance;	
					
					}

					$adpackcreditss = $adpackcredits + $creditpackstobuy;

					$balancetoupdate = array(
						'ad_pack_balance'=>$adpackcreditss
						);

					$this->depo->updateBuyerBalance($userid, $balancetoupdate);



				}
				if($messageReceived === 'Not Confirmed Yet')
				{
					$statusupdate2 = array(
						'status'=>'not confirmed yet',
						'hash'=>$hash
						);

					$this->depo->updateSellerStatus($idtocheck, $statusupdate2);

					$checkingseller = array(
						'credit_packs'=>$creditpackstobuy,
						'seller_btc'=>$currentstatussellerbtc,
						'categeory'=>'sell',
						'status'=>'in progress'
						);

					$this->depo->updateBuyerStatus($checkingseller, $statusupdate2);

				}

				$deposit['messageReceived'] = $messageReceived;
			}
			else{
				
				$deposit['messageReceived'] = "Amount Not Matched";

			}

		}

		


		

		$this->load->view('depositview',['deposit'=>$deposit]);
	}
	public function getSellerInfo($userid)
	{	
		
		$this->load->model('buyerandsellermodel','buyer');

		$userridd = $userid;

		$getBuyerInfo = $this->buyer->getBuyerCreditInfo($userid);

		foreach ($getBuyerInfo as $buyer) {
			# code...
			$buyercreditpacks = $buyer->credit_packs;

		}

		$getSellerInfo = $this->buyer->getSeller($userridd, $buyercreditpacks);

		foreach ($getSellerInfo as $seller) {
			# code...
			$sellerBTC = $seller->seller_btc;

			$sellerCreditPacks = $seller->credit_packs;

			$sellerUserid = $seller->user_id;

			$sellerid = $seller->id;
		}

		$sellerinformation = array(
			'seller_userid'=>$sellerUserid,
			'seller_creditpacks'=>$sellerCreditPacks,
			'seller_btcadd'=>$sellerBTC,
			'seller_id'=>$sellerid
			);

		return $sellerinformation;

	}
	public function paymentconfirmation($extamount, $hash, $btcaddres)
	{
		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		$message = '';

 		try
 		{
 			$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

			$transaction_information = $client->transaction($hash);

			$hashReceived = $transaction_information['hash'];

			$exactamount = $transaction_information['estimated_value'];
			$exactamount2 = $transaction_information['outputs']['0']['value'];
			$exactamount3 = $transaction_information['outputs']['1']['value'];

			$estimated_amount1 = BlocktrailSDK::toBTC($exactamount);
			$estimated_amount2 = BlocktrailSDK::toBTC($exactamount2);
			$estimated_amount3 = BlocktrailSDK::toBTC($exactamount3);

			$btcreceiver = $transaction_information['outputs']['1']['address'];
			$btcreceiver2 = $transaction_information['outputs']['0']['address'];

			$confirmation = $transaction_information['confirmations'];

			if($hashReceived === $hash)
			{
				if((($estimated_amount1 === $extamount) || ($estimated_amount2 === $extamount) || ($estimated_amount3 === $extamount)))
				{
					if(($btcreceiver === $btcaddres) || ($btcreceiver2 === $btcaddres))
					{
						if($confirmation > 0)
						{
							$message = "Confirmed";
						}
						else
						{
							$message = "Not Confirmed Yet";
						}
					}
					else
					{
						$message = "Btc receiver not matched";
					}
				}
				else
				{
					$message = "Amount not matched";
				}
			}
			else
			{
				$message = "Hash not matched";
			}	

 		}
 		catch(Exception $e)
		{
			$message = "Invalid Error";
		}

		return $message;
	}
	public function cronjob_paymentconfirmationfordeposit()
	{
		$this->load->model('buyerandsellermodel','cronjob');

		$showalldata = $this->cronjob->selectingalldata();

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

		foreach ($showalldata as $alldata) {
				# code...
			$idfordeposit = $alldata->id;

			$useridfordeposit = $alldata->user_id;

			$creditpacksfordeposit = $alldata->credit_packs;

			$hashfordeposit = $alldata->hash;

		}

		try
 		{
 			$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

			$transaction_information = $client->transaction($hashfordeposit);

			$confirmation = $transaction_information['confirmations'];

			if($confirmation > 0)
			{
				$statusupdate = array(
						'status'=>'completed'
					);

				$this->cronjob->updateSellerStatus($idfordeposit, $statusupdate);

				$checkallinfo = $this->cronjob->index($useridfordeposit);

				foreach ($checkallinfo as $callinfo) {
					# code...
					$currentadpackscredit = $callinfo->ad_pack_balance;

				}

				$totaladcreditpackstoupdate = $currentadpackscredit + $creditpacksfordeposit;

				$balancetoupdate = array(
						'ad_pack_balance'=>$totaladcreditpackstoupdate
						);

				$this->cronjob->updateBuyerBalance($useridfordeposit, $balancetoupdate);



			}

		}
 		catch(Exception $e)
		{
			$message = "Invalid Error";
		}

	}

	public function cronjob_paymentconfirmationforseller()
	{
		$this->load->model('buyerandsellermodel','cronjob');

		$showalldata = $this->cronjob->selectingalldataforseller();

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";


 		foreach ($showalldata as $alldata) {
				# code...
			$idfordeposit = $alldata->id;

			$useridfordeposit = $alldata->user_id;

			$creditpacksfordeposit = $alldata->credit_packs;

			$hashfordeposit = $alldata->hash;

		}

		try
 		{
 			$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

			$transaction_information = $client->transaction($hashfordeposit);

			$confirmation = $transaction_information['confirmations'];

			if($confirmation > 0)
			{
				$statusupdate = array(
						'status'=>'completed'
					);

				$this->cronjob->updateSellerStatus($idfordeposit, $statusupdate);

				$checkallinfo = $this->cronjob->index($useridfordeposit);

				foreach ($checkallinfo as $callinfo) {
					# code...
					$currentadpackscredit = $callinfo->ad_pack_balance;

				}

				$totaladcreditpackstoupdate = $currentadpackscredit - $creditpacksfordeposit;

				$balancetoupdate = array(
						'ad_pack_balance'=>$totaladcreditpackstoupdate
						);

				$this->cronjob->updateBuyerBalance($useridfordeposit, $balancetoupdate);


			}

		}
 		catch(Exception $e)
		{
			$message = "Invalid Error";
		}
	}

	public function deleterequestaftertimecompletion()
	{
		$datestring = '%Y-%m-%d %h:%i %a';

		$time = time();

		$CurrentTime = mdate($datestring, $time);
		
		$this->load->model('buyerandsellermodel','dell');

		$getdata = $this->dell->getalldatatodelete();

		foreach ($getdata as $gd) {
			# code...

			$timeended = $gd->dateandtimetopaytill;

			$btcfordelete = $gd->seller_btc;

			$idfordelete = $gd->id;

			if($CurrentTime > $timeended)
			{
				$this->dell->deletetimeended($idfordelete);

				$this->dell->sellerstatuschangeupontimecompletion($btcfordelete);
			}

		}

		

	}

}

?>