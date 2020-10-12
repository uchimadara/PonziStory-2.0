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

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$deposit['websitename'] = $websitenameee;

		$this->load->model('buyerandsellermodel','getprice');

		$adpackprice = $this->getprice->getadpackprice();

		foreach ($adpackprice as $adp) {
			# code...
			$oneadpackprice = $adp->amount;
		}

		$deposit['onepackbalance'] = $oneadpackprice;

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

			$amount_balanceee = $pb->amount_balance;
			
		}

		$deposit['amount_balanceee'] = $amount_balanceee; 

		$deposit['pack_balance'] = $packbalance;

		$getcompletedata = $this->depo->getcompletedata($userid);

		$deposit['completedata'] = $getcompletedata;

		$checkforpendingrequest = $this->depo->checkforpendingrequest($userid);

		foreach ($checkforpendingrequest as $pendingrequest) {
			# code...
			$statuspending = $pendingrequest->status;

			if ($statuspending === 'pending' || $statuspending === 'in progress') {
				# code...
				$deposit['requestpending'] = 'pendingrequest';
			}
		}
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('buyvaluesubmit','Buy Value','required');

		if ($this->form_validation->run()) {
			# code...

			$creditValue = $this->input->post('creditbuyvalue');

			$deposit['amountofonead'] = $oneadpackprice;

			$TotalAmount = $oneadpackprice * $creditValue;

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
			

		}

		$sellerinfo = $this->getSellerInfo($userid);

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

			$amounttopayy = $gcs->amount;

			$idtocheck = $gcs->id;

		}

		$deposit['amounttopay'] = $amounttopayy;

		$amounttopay = number_format($amounttopayy, 6);

		$deposit['btc_add'] = $currentstatussellerbtc;

		if($this->input->post('submitPaymentInfo'))
		{
			$hash = $this->input->post('hashPayment');

			$exactpaymentt = $this->input->post('exactPayment');

			$exactpayment = number_format($exactpaymentt, 6);

			$messageReceived = $this->paymentconfirmation($exactpayment, $hash, $currentstatussellerbtc);

			$checkinghashfromdatabase = $this->depo->gethashinfo();

			foreach ($checkinghashfromdatabase as $checkhash2) {
				# code...
				$checkinghash = $checkhash2->hash;
			}

		$checkinghash2 = substr($checkinghash, 0, 50);

		$hash2 = substr($hash, 0, 50);

		if($checkinghash2 !== $hash2)
			{
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

						$amountbalance = $gb->amount_balance;
					
					}

					$adpackcreditss = $adpackcredits + $creditpackstobuy;

					$totalamountforthecredit = $creditpackstobuy * $oneadpackprice;

					$amountoupdate = $amountbalance + $totalamountforthecredit;


					$balancetoupdate = array(
						'ad_pack_balance'=>$adpackcreditss,
						'amount_balance'=>$amounttoupdate
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
		else{
			$deposit['messageReceived'] = "Hash Already Exist";
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

			$estimated_amount_1 = BlocktrailSDK::toBTC($exactamount);
			$estimated_amount_2 = BlocktrailSDK::toBTC($exactamount2);
			$estimated_amount_3 = BlocktrailSDK::toBTC($exactamount3);

			$estimated_amount1 = number_format($estimated_amount_1, 6);
			$estimated_amount2 = number_format($estimated_amount_2, 6);
			$estimated_amount3 = number_format($estimated_amount_3, 6);

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

		$adpackprice = $this->cronjob->getadpackprice();

		foreach ($adpackprice as $adp) {
			# code...
			$oneadpackprice = $adp->amount;

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

					$amountbalance = $callinfo->amount_balance;
				}

				$totaladcreditpackstoupdate = $currentadpackscredit + $creditpacksfordeposit;

				$totalamountforthecredit = $creditpacksfordeposit * $oneadpackprice;

				$amountoupdate = $amountbalance + $totalamountforthecredit;



				$balancetoupdate = array(
						'ad_pack_balance'=>$totaladcreditpackstoupdate,
						'amount_balance'=>$amountoupdate
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

					$amountbalance = $callinfo->amount_balance;

				}

				$totaladcreditpackstoupdate = $currentadpackscredit - $creditpacksfordeposit;

				$totalamountforthecredit = $creditpacksfordeposit * $oneadpackprice;

				$amountoupdate = $amountbalance - $totalamountforthecredit;

				$balancetoupdate = array(
						'ad_pack_balance'=>$totaladcreditpackstoupdate,
						'amount_balance'=>$amountoupdate
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