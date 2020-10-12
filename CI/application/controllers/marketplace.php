<?php 
/**
* 
*/

require_once APPPATH."vendor/autoload.php";

use Blocktrail\SDK\BlocktrailSDK;
use \Blocktrail\SDK\Connection\Exceptions\InvalidCredentials;

class marketplace extends MY_CONTROLLER
{
	public function index($buyorbid ,$hiddenValue, $hiddenrp, $hiddenid, $hiddenuserid)
	{
		$loadView = true;

		$profileInfo = $this->profile;

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

 		$price = $client->price();

 		$onebtcprice = $price['USD'];

 		$marketplace['onebtcpricee'] = $onebtcprice;

		$username = $profileInfo->username;

		$userid = $profileInfo->id;

		$email = $profileInfo->email;

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$marketplace['websitename'] = $websitenameee;

		$marketplace['username'] = $username;

		$this->load->model('marketplacemodel','market');

		$amount_balance = $this->market->index($userid);

		foreach ($amount_balance as $amount) {
			# code...
			$currentamount = $amount->amount_balance;

			$royalty_position = $amount->royalty_positions;

		}

		$currentamountinusd = $currentamount * $onebtcprice;

		$currentexactamountinusd = number_format($currentamountinusd, 4);

		$marketplace['current_balance'] = $currentexactamountinusd;

		$marketplace['roy_pos'] = $royalty_position;

		$this->load->library('form_validation');

		$this->form_validation->set_rules('SellYourRoyality','Sell Royality','required');

		if ($this->form_validation->run()) {
			# code...

			if($royalty_position === '0')
			{
				$marketplace['error'] = '1';
			}
			else
			{
				$this->sell_royaltypositions();

				$loadView = false;
			}

		}


		$getRPForSale = $this->market->Get_RP_For_Sale();

		$marketplace['getRpForSale'] = $getRPForSale;

		
		if (isset($hiddenValue)) {
			# code...
			if($buyorbid === 'buy')
			{
				if ($currentexactamountinusd >= $hiddenValue) {
					# code...
					
					$rptoupdate = $royalty_position + $hiddenrp;

					$amountoupdate = $hiddenValue / $onebtcprice;

					$amountosendtodatabase = number_format($amountoupdate, 6);

					$exactamountodb = $currentamount - $amountosendtodatabase;

					$datatodb = array(
						'amount_balance'=>$exactamountodb,
						'royalty_positions'=>$rptoupdate
						);

					$this->market->updateBalanceOnBuy($userid, $datatodb);


					$updatedStatus = array(
						'status'=>'completed'
						);

					$this->market->updateRPwhencompleted($hiddenid, $updatedStatus);

					redirect('/marketplace');

				}
				else
				{
					$marketplace['error'] = '2';
				}
			}

			if ($buyorbid === 'bid') {
				# code...

				if ($royalty_position >= $hiddenrp) {
					# code...

					$deduct_rp = $royalty_position - $hiddenrp;

					$amountoupdate = $hiddenValue / $onebtcprice;

					$amountosendtodatabase = number_format($amountoupdate, 6);

					$exactamountodb = $currentamount + $amountosendtodatabase;

					$datatodb = array(
						'amount_balance'=>$exactamountodb,
						'royalty_positions'=>$deduct_rp
						);

					$this->market->updateBalanceOnBuy($userid, $datatodb);

					$updatedStatus = array(
						'status'=>'completed'
						);

					$this->market->updateRPwhencompleted($hiddenid, $updatedStatus);

					$getbidderrp = $this->market->getBidderrp($userid);

					foreach ($getbidderrp as $bidder_rp) {
						# code...
						$bidder_rpp = $bidder_rp->royalty_positions;
					}

					$bidder_rp_update = $bidder_rpp + $hiddenrp;

					$bidderdatatoupdate = array(
						'royalty_positions'=>$bidder_rp_update
						);
					$this->market->updateBalanceOnBuy($hiddenuserid, $bidderdatatoupdate);

				}
				else
				{
					$marketplace['error'] = '2';
				}

			}
		}

		$getBidData = $this->market->getBidData();

		$marketplace['bid_data'] = $getBidData;

		$marketplace['pending_sales'] = $this->market->getPendingSales($userid);

		$marketplace['active_bids'] = $this->market->getActiveBids($userid);


		$this->load->model('webtaskmodel','webtask');

		$royalty_positions = $this->webtask->getRoyaltyShares();

		$count = 0;

		foreach ($royalty_positions as $rp) {
			# code...
			$roy_pos = $rp->royalty_positions;

			$count = $count + $roy_pos;
		}

		$marketplace['totalnumberofdividends'] = $count;

		$getallDividends = $this->market->getAllDividendsPaid();

		$countamount = 0;

		foreach ($getallDividends as $getdividendspaid) {
			# code...
			$getfullamountpaid = $getdividendspaid->dividends_paid;

			$countamount = $countamount + $getfullamountpaid;
		}

		$amounttiinusd =  $onebtcprice * $countamount;

		$numberformatinusd = number_format($amounttiinusd, 3);

		$marketplace['fullamountpaid'] = $numberformatinusd;

		$historyofdividends = $this->market->historydividends($username);


		$marketplace['history_dividends'] = $historyofdividends;

		if ($loadView == true) {
			# code...

			$this->load->view('marketplaceview',['marketplace'=>$marketplace]);

		}

	
	}

	public function accept_bid()
	{
		$this->form_validation->set_rules('bid_id','Hidden Value','required');

		if ($this->form_validation->run()) {
			# code...
			$bidder_id = $this->input->post('bid_id');

			$bidder_amount = $this->input->post('amount_bid');

			$bidder_rp_value = $this->input->post('rp_value_bid');

			$bidder_userid = $this->input->post('bidder_userid');

			$bidder_username = $this->input->post('bidder_username');

		}

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$bidder_rp['websitename'] = $websitenameee;


		$bidder_rp['id'] = $bidder_id;

		$profileInfo = $this->profile;

		$username = $profileInfo->username;

		$userid = $profileInfo->id;


		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

 		$price = $client->price();

 		$onebtcprice = $price['USD'];

 		$this->load->model('marketplacemodel','market');

 		$amount_balance = $this->market->index($userid);

		foreach ($amount_balance as $amount) {
			# code...
			$currentamount = $amount->amount_balance;

			$royalty_position = $amount->royalty_positions;

		}

		$amountinusdd = $currentamount * $onebtcprice;

		$amountinusd = number_format($amountinusdd, 4);

		$bidder_rp['currentprice'] = $amountinusd;


		$bidder_rp['usernameee'] = $username;

		$bidder_rp['user_id'] = $bidder_userid;

		$bidder_rp['user_name'] = $bidder_username;

		$this->load->model('marketplacemodel','market');

		$dataforbid = $this->market->getRpValueForBidder($bidder_id);

		foreach ($dataforbid as $bid_data) {
			# code...
			$roy_pos = $bid_data->royalty_positions;

			$amount_per_rp = $bid_data->amount_per_rp;

		}

		$bidder_rp['roy_pos'] = $roy_pos;

		$bidder_rp['amount_per_rp'] = $amount_per_rp;

		$bidder_rp['total_amount'] = $amount_per_rp * $roy_pos;

		$this->load->view('bid_rp_page',['bidder_rp'=>$bidder_rp]);
	}
	public function sale_rp_bid()
	{

		$profileInfo = $this->profile;

		$username = $profileInfo->username;

		$userid = $profileInfo->id;

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$bidder_data['websitename'] = $websitenameee;

 		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

 		$price = $client->price();

 		$onebtcprice = $price['USD'];

 		$this->load->model('marketplacemodel','market');

 		$amount_balance = $this->market->index($userid);

		foreach ($amount_balance as $amount) {
			# code...
			$currentamount = $amount->amount_balance;

			$royalty_position = $amount->royalty_positions;

		}

		$amountinusdd = $currentamount * $onebtcprice;

		$amountinusd = number_format($amountinusdd, 4);

		$bidder_data['username'] = $username;

		$bidder_data['currentusd'] = $amountinusd; 

		$this->form_validation->set_rules('amountofrptosellforbid','Amount to sell','required');

		if ($this->form_validation->run()) {
			# code...

			$rp_amountToSell = $this->input->post('amountofrptosellforbid');

			$rp_amountforbid = $this->input->post('bidder_rp');

			$per_rp_amount = $this->input->post('bidder_amount_perrp');

			$bidder_user_id = $this->input->post('bidder_userid');

			$id_rp = $this->input->post('bidder_id');

			if ($rp_amountforbid < $rp_amountToSell) {
				# code...
				$bidder_data['message'] = '1';
			}
			else
			{
				if ($royalty_position < $rp_amountforbid) {
					# code...
					$bidder_data['message'] = '2';
				}
				elseif ($rp_amountToSell === $rp_amountforbid) {
					# code...
					$completedStatus = array(
							'status'=>'completed'
							);

					$this->market->updateRPwhencompleted($id_rp, $completedStatus);

					$updateSellerRp = $royalty_position - $rp_amountToSell;

					$updatePayment = $rp_amountToSell * $per_rp_amount;

					$updatePaymentt = $updatePayment / $onebtcprice;

					$updatePaymenttt = $updatePaymentt + $currentamount;

					$rptoupdatee = $royalty_position - $rp_amountToSell;

					$updateinfo = array(
						'amount_balance'=>$updatePaymenttt,
						'royalty_positions'=>$rptoupdatee
						);

					$this->market->updateBalanceOnBuy($userid, $updateinfo);


					$amount_balance_bidder = $this->market->index($bidder_user_id);

						foreach ($amount_balance_bidder as $amount_bid) {
							# code...

							$royalty_position_bidder = $amount_bid->royalty_positions;

						}

					$updateToBidderrr = $royalty_position_bidder + $rp_amountToSell;

						$updatebidderinfoo = array(
						'royalty_positions'=>$updateToBidderrr
						);

					$this->market->updateBalanceOnBuy($bidder_user_id, $updatebidderinfoo);

					$bidder_data['message'] = '3';


				}
				else
				{
					$updateSellerRp = $royalty_position - $rp_amountToSell;

					$updatePayment = $rp_amountToSell * $per_rp_amount;

					$updatePaymentt = $updatePayment / $onebtcprice;

					$updatePaymenttt = $updatePaymentt + $currentamount;

					$rptoupdatee = $royalty_position - $rp_amountToSell;

					$updateinfo = array(
						'amount_balance'=>$updatePaymenttt,
						'royalty_positions'=>$rptoupdatee
						);

					$this->market->updateBalanceOnBuy($userid, $updateinfo);


					$rptoupdateinbid = $rp_amountforbid - $rp_amountToSell;

					$rpptoupdateee = array(
						'royalty_positions'=>$rptoupdateinbid
						);

					$this->market->updateRPwhencompleted($id_rp, $rpptoupdateee);

					$amount_balance_bidder = $this->market->index($bidder_user_id);

						foreach ($amount_balance_bidder as $amount_bid) {
							# code...

							$royalty_position_bidder = $amount_bid->royalty_positions;

						}

					$updateToBidderrr = $royalty_position_bidder + $rp_amountToSell;

					$updatebidderinfoo = array(
						'royalty_positions'=>$updateToBidderrr
						);

					$bidder_data['message'] = '3';

					$this->market->updateBalanceOnBuy($bidder_user_id, $updatebidderinfoo);

				}
			}

		}


		$this->load->view('bid_rp_page',['bidder_data'=>$bidder_data]);
		
	}

	public function buy_rp()
	{

		$this->load->model('WebsiteNameModel','namemodel');

		$this->form_validation->set_rules('hiddenvalueforbuy','Buy Hidden','required');

		if ($this->form_validation->run()) {
			# code...

			$SellerValue = $this->input->post('hiddenvalueforbuy');

			$Sellerrp = $this->input->post('hiddenrpvalue');

			$Sellerid = $this->input->post('hiddenidforbuy');

			$Sellerusername = $this->input->post('hiddenusernameseller');

			$Seller_per_rp = $this->input->post('hiddenperrpsell');

		}

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$buy_rpp['websitename'] = $websitenameee;



		$buy_rpp['Sellerusername'] = $Sellerusername;
		$buy_rpp['Amount_Per_Rp'] = $Seller_per_rp;
		$buy_rpp['Total_Amount'] = $SellerValue;
		$buy_rpp['Total_Rp'] = $Sellerrp;
		$buy_rpp['idforbuy'] = $Sellerid;



		$this->load->view('buy_rp_page',['buy_rpp'=>$buy_rpp]);

	}
	public function buy_royalty_position()
	{
		$this->load->model('WebsiteNameModel','namemodel');

		$profileInfo = $this->profile;

		$username = $profileInfo->username;

		$this->load->model('marketplacemodel','market');

		$userid = $profileInfo->id;

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$buy_rrpp['websitename'] = $websitenameee;

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

 		$price = $client->price();

 		$onebtcprice = $price['USD'];


		$amount_balance = $this->market->index($userid);

		foreach ($amount_balance as $amount) {
			# code...
			$currentamount = $amount->amount_balance;

			$royalty_position = $amount->royalty_positions;

		}

		$amountinusdd = $currentamount * $onebtcprice;

		$amountinusd = number_format($amountinusdd, 4);

		$buy_rrpp['amountinusd'] = $amountinusd;

		$this->form_validation->set_rules('amountofrptobuy','Amount to buy','required');

		if ($this->form_validation->run()) {
			# code...
			$amountobuy = $this->input->post('amountofrptobuy');

			$totalamountofrp = $this->input->post('totalamountofrp');

			$amount_perr_rp = $this->input->post('amountperrp');

			$idforrp = $this->input->post('idforbuying');

			if ($amountobuy <= $totalamountofrp) {
				# code...

				$amounttopay = $amount_perr_rp * $amountobuy;


				if ($amounttopay < $amountinusd) {
					# code...
					
					if ($amountobuy === $totalamountofrp) {
						# code...
						$completedStatus = array(
							'status'=>'completed'
							);

						$this->market->updateRPwhencompleted($idforrp, $completedStatus);


						$amountinbtctoupdate = $amounttopay / $onebtcprice;

						$amountoupdateinbtc = $currentamount - $amountinbtctoupdate;

						$royaltypositionstoupdate = $royalty_position + $amountobuy;

						$PaymentUser = array(
							'amount_balance'=>$amountoupdateinbtc,
							'royalty_positions'=>$royaltypositionstoupdate
							);

						$this->market->seller_info_toupdate($userid, $PaymentUser);

						$buy_rrpp['completed'] = '1';


					}
					if ($amountobuy < $totalamountofrp) {
						# code...
						$amounttoupdate = $totalamountofrp - $amountobuy;

						$updateamount = array(
							'royalty_positions'=>$amounttoupdate
							);

						$this->market->updateRPwhencompleted($idforrp, $updateamount);

						$amountinbtctoupdate = $amounttopay / $onebtcprice;

						$amountoupdateinbtc = $currentamount - $amountinbtctoupdate;

						$royaltypositionstoupdate = $royalty_position + $amountobuy;

						$PaymentUser = array(
							'amount_balance'=>$amountoupdateinbtc,
							'royalty_positions'=>$royaltypositionstoupdate
							);

						$this->market->seller_info_toupdate($userid, $PaymentUser);

						$buy_rrpp['completed'] = '1';


					}


				}
				else
				{
					$buy_rrpp['error_msg'] = '2';
				}

			}
			else{
				$buy_rrpp['error_msg'] = '1';
				
			}
		}


		$this->load->view('buy_rp_page',['buy_rrpp'=>$buy_rrpp]);		
	}


	public function sell_royaltypositions()
	{
		$this->load->model('WebsiteNameModel','namemodel');

		$profileInfo = $this->profile;

		$username = $profileInfo->username;

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$sell_rp['websitename'] = $websitenameee;

 		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

 		$price = $client->price();

 		$onebtcprice = $price['USD'];

		$userid = $profileInfo->id;

		$email = $profileInfo->email;

		$sell_rp['username'] = $username;

		$this->load->model('marketplacemodel','market');

		$amount_balance = $this->market->index($userid);

		foreach ($amount_balance as $amount) {
			# code...
			$currentamount = $amount->amount_balance;

			$royalty_position = $amount->royalty_positions;

		}

		$currentamountinusd = $currentamount * $onebtcprice;

		$currentexactamountinusd = number_format($currentamountinusd, 4);

		$sell_rp['current_balance'] = $currentexactamountinusd;

		$sell_rp['roy_pos'] = $royalty_position;

		$amountosell = $this->input->post('royaltypositiontosell');

		$saleperrp = $this->input->post('salepriceperrp');

		$this->form_validation->set_rules('royaltypositiontosell','Royalty Position Sell','required');

		$this->form_validation->set_rules('salepriceperrp','Sale Price Per Rp','required');

		if ($this->form_validation->run()) {
			# code...
			$royaltypostosell = $this->input->post('royaltypositiontosell');

			$saleprice = $this->input->post('salepriceperrp');

			if ($royaltypostosell <= $royalty_position) {
				# code...
				
				$sell_credits_info_to_insert = array(
					'user_id'=>$userid,
					'categeory'=>'sell',
					'royalty_positions'=>$royaltypostosell,
					'amount_per_rp'=>$saleprice,
					'status'=>'pending'
					);

				$this->market->sell_royalty_positions($sell_credits_info_to_insert);

				$rp_to_update = $royalty_position - $royaltypostosell;

				$udpate_userinfo_rp = array(
					'user_id'=>$userid,
					'royalty_positions'=>$rp_to_update
					);

				$this->market->seller_info_toupdate($userid, $udpate_userinfo_rp);


				$sell_rp['sellerror'] = 'request_submitted';

			}
			else
			{
				$sell_rp['sellerror'] = 'sufficient_balance';
			}
		}

		$this->load->view('sell_royaltypositionsview',['sell_rp'=>$sell_rp]);
	}

	public function placebid()
	{
		$this->load->model('WebsiteNameModel','namemodel');

		$profileInfo = $this->profile;

		$username = $profileInfo->username;

		$userid = $profileInfo->id;

		$email = $profileInfo->email;

		$bids['username'] = $username;

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$bids['websitename'] = $websitenameee;

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

 		$price = $client->price();

 		$onebtcprice = $price['USD'];

 		$this->load->model('marketplacemodel','bids');

		$amount_balance = $this->bids->index($userid);

		foreach ($amount_balance as $amount) {
			# code...
			$currentamount = $amount->amount_balance;

			$royalty_position = $amount->royalty_positions;

		}

		$currentamountinusd = $currentamount * $onebtcprice;

		$currentexactamountinusd = number_format($currentamountinusd, 4);

		$bids['current_balance'] = $currentexactamountinusd;

		$this->form_validation->set_rules('royaltypositiontobid','Royalty Positions','required');

		if ($this->form_validation->run()) {

			$amounttobid = $this->input->post('salepriceperrp');

			$rptobid = $this->input->post('royaltypositiontobid');

			$totalamount = $amounttobid * $rptobid;

			if ($totalamount < $currentexactamountinusd) {
				# code...

				$insertData = array(
					'user_id'=>$userid,
					'categeory'=>'bid',
					'royalty_positions'=>$rptobid,
					'amount_per_rp'=>$amounttobid,
					'status'=>'pending'
					);

				$this->bids->insertDataToBid($insertData);

				$amountoupdate = $totalamount / $onebtcprice;

				$amountosendtodatabase = number_format($amountoupdate, 6);

				$extamounttodb = $currentamount - $amountosendtodatabase;

				$updateData = array(
					'amount_balance'=>$extamounttodb
					);

				$this->bids->updateBalanceOnBuy($userid, $updateData);

				redirect('/marketplace/placebid');


			}
			else
			{
				$bids['error'] = '1';
			}

		}

		$this->load->view('placebidsview',['bids'=>$bids]);
	}
	public function cancel_sales()
	{
		$this->form_validation->set_rules('idtodeletependingsales','id','required');
		
		$this->load->model('marketplacemodel','mpmodel');

		if ($this->form_validation->run()) {

			$idtodeleteforpendingsales = $this->input->post('idtodeletependingsales');

			$user_idtoadd = $this->input->post('user_idtoadd');

			$totalnumberofrospos = $this->input->post('totalnumberofrospos');

			$royalty_positionss = $this->mpmodel->index($user_idtoadd);

			foreach ($royalty_positionss as $rossposs) {
				# code...
				$royalty_positionsofusers = $rossposs->royalty_positions;

			}

			$totalnumberofrpos = $totalnumberofrospos + $royalty_positionsofusers;

			$rp = array(
				'royalty_positions'=>$totalnumberofrpos
				);

			$this->mpmodel->seller_info_toupdate($user_idtoadd, $rp);

			$this->mpmodel->deletependingsales($idtodeleteforpendingsales);

		}

		return redirect('/marketplace');
	}
	public function cancel_bids()
	{
		$this->form_validation->set_rules('idtocancelbids','id','required');
		
		$this->load->model('marketplacemodel','mpmodel');

		if ($this->form_validation->run()) {

			$idtocancelbids = $this->input->post('idtocancelbids');

			$royaltypositiontoaddback = $this->mpmodel->getbackroyalpos($idtocancelbids);

			$this->mpmodel->deletependingsales($idtocancelbids);

		}

		return redirect('/marketplace');	
	}


}

?>