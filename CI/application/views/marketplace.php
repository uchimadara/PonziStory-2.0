<?php 
/**
* 
*/

require_once APPPATH."vendor/autoload.php";

use Blocktrail\SDK\BlocktrailSDK;

class marketplace extends MY_CONTROLLER
{
	public function index($buyorbid ,$hiddenValue, $hiddenrp, $hiddenid, $hiddenuserid)
	{	
		$profileInfo = $this->profile;

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

 		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);

 		$price = $client->price();

 		$onebtcprice = $price['USD'];

		$username = $profileInfo->username;

		$userid = $profileInfo->id;

		$email = $profileInfo->email;

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

		$this->load->view('marketplaceview',['marketplace'=>$marketplace]);
	}

	public function accept_bid()
	{
		$this->form_validation->set_rules('bid_id','Hidden Value','required');

		if ($this->form_validation->run()) {
			# code...
			$buyer_id = $this->input->post('bid_id');

			$buyer_amount = $this->input->post('amount_bid');

			$buyer_rp = $this->input->post('rp_value_bid');

			$bidder_userid = $this->input->post('bidder_userid');

		}

		$bid = 'bid';

		$this->index($bid, $buyer_amount, $buyer_rp, $buyer_id, $bidder_userid);
	}

	public function buy_rp()
	{


		$this->form_validation->set_rules('hiddenvalueforbuy','Buy Hidden','required');

		if ($this->form_validation->run()) {
			# code...

			$SellerValue = $this->input->post('hiddenvalueforbuy');

			$Sellerrp = $this->input->post('hiddenrpvalue');

			$Sellerid = $this->input->post('hiddenidforbuy');

			$Sellerusername = $this->input->post('hiddenusernameseller');

			$Seller_per_rp = $this->input->post('hiddenperrpsell');

		}

		$buy_rpp['Sellerusername'] = $Sellerusername;
		$buy_rpp['Amount_Per_Rp'] = $Seller_per_rp;
		$buy_rpp['Total_Amount'] = $SellerValue;
		$buy_rpp['Total_Rp'] = $Sellerrp;
		$buy_rpp['idforbuy'] = $Sellerid;



		$this->load->view('buy_rp_page',['buy_rpp'=>$buy_rpp]);

	}
	public function buy_royalty_position()
	{

		$profileInfo = $this->profile;

		$username = $profileInfo->username;

		$this->load->model('marketplacemodel','market');

		$userid = $profileInfo->id;

		$this->form_validation->set_rules('amountofrptobuy','Amount to buy','required');

		if ($this->form_validation->run()) {
			# code...
			$amountobuy = $this->input->post('amountofrptobuy');

			$totalamountofrp = $this->input->post('totalamountofrp');

			$amount_perr_rp = $this->input->post('amountperrp');

			$idtoaccess = $this->post->post('idforrp');

			if ($amountobuy < $totalamountofrp) {
				# code...

				$amounttopay = $amount_perr_rp * $amountobuy;

				$amount_balance = $this->market->index($userid);

				foreach ($amount_balance as $amount) {
			# code...
					$currentamount = $amount->amount_balance;

					$royalty_position = $amount->royalty_positions;

				}

				if($currentamount >= $totalamountofrp)
				{
					if ($amountobuy === $totalamountofrp) {
						
						echo $idtoaccess;

					}
					else
					{

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

		$profileInfo = $this->profile;

		$username = $profileInfo->username;

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

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

		$profileInfo = $this->profile;

		$username = $profileInfo->username;

		$userid = $profileInfo->id;

		$email = $profileInfo->email;

		$bids['username'] = $username;

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


}

?>