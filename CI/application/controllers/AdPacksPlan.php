<?php

/**
* 
*/
class AdPacksPlan extends MY_CONTROLLER
{
	public function index()
	{
		$this->load->model('webtaskmodel','webtask');

		$profileInfo = $this->profile;

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

		$WebTask['websitename'] = $websitenameee;

		$WebTask['username'] = $username;

		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$WebTask['amount_balance'] = $userbalancedetailss;

		$WebTask['bronze_shares'] = $BronzeShares;

		$WebTask['silver_shares'] = $SilverShares;

		$WebTask['gold_shares'] = $GoldShares;

		$GetBronzeSharePrice = $this->webtask->GetBronzeSharePrice();

		foreach ($GetBronzeSharePrice as $bronzeshareprice) {
					# code...
				$bronzepershareprice = $bronzeshareprice->price_per_share;

				$bronze_share_per_user = $bronzeshareprice->share_per_user;

				$bronze_banner_ads = $bronzeshareprice->banner_ads;

				$bronze_text_ads = $bronzeshareprice->text_ads;

				$bronze_daily_percentage = $bronzeshareprice->daily_percentage;

				$bronze_total_profit = $bronzeshareprice->max_return;


			}

		$WebTask['BronzePerSharePrice'] = $bronzepershareprice;

		$WebTask['BronzeSharePerUser'] = $bronze_share_per_user;

		$WebTask['BronzeBannerAds'] = $bronze_banner_ads;

		$WebTask['BronzeTextAds'] = $bronze_text_ads;

		$WebTask['BronzeDailyPercentage'] = $bronze_daily_percentage;

		$WebTask['BronzeMaxReturn'] = $bronze_total_profit;

		$GetSilverSharePrice = $this->webtask->GetSilverSharePrice();

		foreach ($GetSilverSharePrice as $silvershareprice) {
			# code...
			$silverpricepershare = $silvershareprice->price_per_share;

			$silver_share_per_user = $silvershareprice->share_per_user;

			$silver_banner_ads = $silvershareprice->banner_ads;

			$silver_text_ads = $silvershareprice->text_ads;

			$silver_daily_percentage = $silvershareprice->daily_percentage;

			$silver_max_return = $silvershareprice->max_return;
		}

		$WebTask['SilverPerSharePrice'] = $silverpricepershare;

		$WebTask['SilverSharePerUser'] = $silver_share_per_user;

		$WebTask['SilverBannerAds'] = $silver_banner_ads;

		$WebTask['SilverTextAds'] = $silver_text_ads;

		$WebTask['SilverDailyPercentage'] = $silver_daily_percentage;

		$WebTask['SilverMaxReturn'] = $silver_max_return;

		$GetGoldSharePrice = $this->webtask->GetGoldSharePrice();

		foreach ($GetGoldSharePrice as $goldshareprice) {
			# code...
			$goldpricepershare = $goldshareprice->price_per_share;

			$gold_share_per_user = $goldshareprice->share_per_user;

			$gold_text_ads = $goldshareprice->banner_ads;

			$gold_banner_ads = $goldshareprice->banner_ads;

			$gold_daily_percentage = $goldshareprice->daily_percentage;

			$gold_max_return = $goldshareprice->max_return;
		}

		$WebTask['GoldPerSharePrice'] = $goldpricepershare;

		$WebTask['GoldSharePerUser'] = $gold_share_per_user;

		$WebTask['GoldBannerAds'] = $gold_banner_ads;

		$WebTask['GoldTextAds'] = $gold_text_ads;

		$WebTask['GoldDailyPercentage'] = $gold_daily_percentage;

		$WebTask['GoldMaxReturn'] = $gold_max_return; 


		$historyofadpacks = $this->webtask->getHistory($username);

		$WebTask['history'] = $historyofadpacks;


		$this->load->view('AdPacksClientView', ['WebTask'=>$WebTask] );
	}
	public function BuySilverPack()
	{

		$this->load->model('webtaskmodel','webtask');

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$silverpack['websitename'] = $websitenameee;

		$profileInfo = $this->profile;

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$silverpack['username'] = $username;

		$silverpack['amount_details'] = $userbalancedetailss;

		$this->form_validation->set_rules('BuySilverPack','Buy Silver','required');

		if ($this->form_validation->run()) {
			# code...
			$SubmitValue = $this->input->post('BuySilverPack');

			$SilverPackPerPrice = $this->input->post('PriceSilverPack');

			if ($SubmitValue === 'Buy Now') {
				# code...
				$silverpack['silver_pack'] = '1';

				$silverpack['silverperprice'] = $SilverPackPerPrice;	
			}
		}

		$this->load->view('BuyPacks',['silverpack'=>$silverpack]);


	}
	public function BuyGoldPack()
	{

		$this->load->model('webtaskmodel','webtask');

		$profileInfo = $this->profile;

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$goldpackk['websitename'] = $websitenameee;

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$goldpackk['username'] = $username;

		$goldpackk['amount_details'] = $userbalancedetailss;

		$this->form_validation->set_rules('PriceGoldPack','Price Gold Pack','required');

		if($this->form_validation->run())
		{


			$submitValue = $this->input->post('BuyGoldPack');

			$pricebronepack = $this->input->post('PriceGoldPack');

			if ($submitValue === 'Buy Now') {
				# code...

				$goldpackk['gold_pack'] = '1';

				$goldpackk['goldperprice'] = $pricebronepack;

			}

		}

		$this->load->view('BuyPacks',['goldpackk'=>$goldpackk]);



	}
	public function BuyGoldPacks()
	{

		$profileInfo = $this->profile;

		$this->load->model('webtaskmodel','webtask');

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

		$goldpacks['websitename'] = $websitenameee;

		$goldpacks['username'] = $username;

		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$goldpacks['user_amount'] = $userbalancedetailss;

		$getGoldMaxShareDetails = $this->webtask->GetGoldSharePrice();

		foreach ($getGoldMaxShareDetails as $getbronzedetails) {
			# code...
			$max_shares_user_can_buy = $getbronzedetails->share_per_user;

			$price_per_sharee = $getbronzedetails->price_per_share;

			$max_return = $getbronzedetails->max_return;
		}




		$this->form_validation->set_rules('perpackprice','Pack Per Shaer','required');

		if ($this->form_validation->run()) {
			# code...
			$amount_to_buy = $this->input->post('amounttobuyadpacks');

			$per_pack_price = $this->input->post('perpackprice');

			$total_amount = $amount_to_buy * $per_pack_price;

			
			if ($userbalancedetailss < $total_amount) {
				# code...
				$goldpacks['error_buying'] = '1';
			}
			else
			{
				$sharestoupdate = $GoldShares + $amount_to_buy;

				if($max_shares_user_can_buy < $sharestoupdate){

					$silverrpackk['error_buying'] = '3';

				}
				else
				{


					$amounttoupdate = $userbalancedetailss - $total_amount;

					$updateInfo = array(
						'amount_balance'=>$amounttoupdate,
						'gold_shares'=>$sharestoupdate
						);

					$this->webtask->updateInfo($userid, $updateInfo);

					$buyInfo = array(
						'username'=>$username,
						'ad_pack'=>'Gold',
						'status'=>'active',
						'max_percentage'=>'0',
						'price'=>$price_per_sharee,
						'total_max_return'=>$max_return
						);

					$this->webtask->updateInfoForBuyingShares($buyInfo);

					$goldpacks['error_buying'] = '2';

					redirect('/AdPacksPlan');
				}
			}

		}


		$this->load->view('BuyPacks',['goldpacks'=>$goldpacks]);
	}

	public function BuyBronzePack()
	{
		$this->load->model('webtaskmodel','webtask');

		$profileInfo = $this->profile;

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

		$bronzeepackk['websitename'] = $websitenameee;

		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$bronzeepackk['username'] = $username;

		$bronzeepackk['amount_details'] = $userbalancedetailss;



		$this->form_validation->set_rules('PriceBronzePack','Price Bronze Pack','required');

		if($this->form_validation->run())
		{


			$submitValue = $this->input->post('BuyBronzePack');

			$pricebronepack = $this->input->post('PriceBronzePack');

			if ($submitValue === 'Buy Now') {
				# code...

				$bronzeepackk['bronze_pack'] = '1';

				$bronzeepackk['bronzeperprice'] = $pricebronepack;

			}

		}

		$this->load->view('BuyPacks',['bronzeepackk'=>$bronzeepackk]);
	}
	public function BuySilverPacks()
	{

		$profileInfo = $this->profile;

		$this->load->model('webtaskmodel','webtask');

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

		$silverrpackk['websitename'] = $websitenameee;



		$silverrpackk['username'] = $username;

		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}


		$getSilverMaxShareDetails = $this->webtask->GetSilverSharePrice();

		foreach ($getSilverMaxShareDetails as $getbronzedetails) {
			# code...
			$max_shares_user_can_buy = $getbronzedetails->share_per_user;

			$price_per_sharee = $getbronzedetails->price_per_share;

			$max_returnn = $getbronzedetails->max_return;
		}


		$silverrpackk['user_amount'] = $userbalancedetailss;

		$this->form_validation->set_rules('perpackprice','Pack Per Shaer','required');

		if ($this->form_validation->run()) {
			# code...
			$amount_to_buy = $this->input->post('amounttobuyadpacks');

			$per_pack_price = $this->input->post('perpackprice');

			$total_amount = $amount_to_buy * $per_pack_price;

			
			if ($userbalancedetailss < $total_amount) {
				# code...
				$silverrpackk['error_buying'] = '1';
			}
			else
			{

				$sharestoupdate = $SilverShares + $amount_to_buy;

				if($max_shares_user_can_buy < $sharestoupdate){

					$silverrpackk['error_buying'] = '3';

				}
				else
				{
					

					$amounttoupdate = $userbalancedetailss - $total_amount;

					$updateInfo = array(
						'amount_balance'=>$amounttoupdate,
						'silver_shares'=>$sharestoupdate
						);

					$this->webtask->updateInfo($userid, $updateInfo);

					$buyInfo = array(
						'username'=>$username,
						'ad_pack'=>'Silver',
						'status'=>'active',
						'max_percentage'=>'0',
						'price'=>$price_per_sharee,
						'total_max_return'=>$max_returnn
						);

					$this->webtask->updateInfoForBuyingShares($buyInfo);


					$silverrpackk['error_buying'] = '2';

					redirect('/AdPacksPlan');
				}
			}

		}



		$this->load->view('BuyPacks',['silverrpackk'=>$silverrpackk]);
	}
	public function BuyBronzePacks()
	{
		$this->load->model('webtaskmodel','webtask');

		$profileInfo = $this->profile;

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

		$bronzepacks['websitename'] = $websitenameee;

		$bronzepacks['username'] = $username;

		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$getBronzeMaxShareDetails = $this->webtask->GetBronzeSharePrice();

		foreach ($getBronzeMaxShareDetails as $getbronzedetails) {
			# code...
			$max_shares_user_can_buy = $getbronzedetails->share_per_user;

			$price_per_sharee = $getbronzedetails->price_per_share;

			$max_returnn = $getbronzedetails->max_return;
		}



		$bronzepacks['balance_amount'] = $userbalancedetailss;

		$this->form_validation->set_rules('perpackprice','Per Pack Price','required');

		if ($this->form_validation->run()) {
			# code...
			$perpackprice = $this->input->post('perpackprice');

			$amount_to_buy = $this->input->post('amounttobuyadpacks');

			$total_amount = $perpackprice * $amount_to_buy;

			if ($userbalancedetailss < $total_amount) {
				# code...
				$bronzepacks['error_buying'] = '1';
			}
			else
			{
				$sharestoupdate = $BronzeShares + $amount_to_buy;

				if($max_shares_user_can_buy < $sharestoupdate){

					$bronzepacks['error_buying'] = '3';

				}
				else
				{
					

					$amounttoupdate = $userbalancedetailss - $total_amount;

					$updateInfo = array(
						'amount_balance'=>$amounttoupdate,
						'bronze_shares'=>$sharestoupdate
						);

					$this->webtask->updateInfo($userid, $updateInfo);

						$buyInfo = array(
						'username'=>$username,
						'ad_pack'=>'Bronze',
						'status'=>'active',
						'max_percentage'=>'0',
						'price'=>$price_per_sharee,
						'total_max_return'=>$max_returnn
						);

					$this->webtask->updateInfoForBuyingShares($buyInfo);

					$bronzepacks['error_buying'] = '2';

					redirect('/AdPacksPlan');
				}
			}
		}

		$this->load->view('BuyPacks',['bronzepacks'=>$bronzepacks]);
	}

}

?>