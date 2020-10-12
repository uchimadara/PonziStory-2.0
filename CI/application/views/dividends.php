<?php

require_once APPPATH."vendor/autoload.php";

use Blocktrail\SDK\BlocktrailSDK;
use \Blocktrail\SDK\Connection\Exceptions\InvalidCredentials;

/**
* 
*/
class dividends extends MY_CONTROLLER
{
	public function index()
	{

		
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

		$dividends['websitename'] = $websitenameee;

		$dividends['username'] = $username;

		$this->load->model('webtaskmodel','webtask');

		$SECRET_API = "46504774f15174b6b32417ac77772f6223956d54";

 		$API_KEY = "502f9f6f2b8d53ca0166b5197264ff90e9465b68";

		$client = new BlocktrailSDK($API_KEY, $SECRET_API , "BTC", false);


		$perbtcprice = $client->price();

		$perbtcpricee = $perbtcprice['USD'];


		$royalty_positions = $this->webtask->getRoyaltyShares();

		$count = 0;

		foreach ($royalty_positions as $rp) {
			# code...
			$roy_pos = $rp->royalty_positions;

			$count = $count + $roy_pos;
		}

		$this->form_validation->set_rules('dividend_amount','Dividend Amount','required');

		if ($this->form_validation->run()) {
			# code...
			$dividendamount = $this->input->post('dividend_amount');

			$amountodivide = $dividendamount / $count;

			$amountodividee = $amountodivide / $perbtcpricee;

			$royalty_positions2 = $this->webtask->getRoyaltyShares();

			foreach ($royalty_positions2 as $rpp) {

				$idtoupdate = $rpp->user_id;

				$getroypos = $rpp->royalty_positions;

				$amounttoupdateforroypso = $getroypos * $amountodividee;

				$amounttoupdateefordiv = array(
					'amount_balance'=>$amounttoupdateforroypso
					);

				$this->webtask->updatePaymentDividend($idtoupdate, $amounttoupdateefordiv);

			}



		}






		$total_number_of_shares = $count;

		$dividends['total_number_of_shares'] = $total_number_of_shares;

		$this->load->view('dividendsview',['dividends'=>$dividends]);
	}
}

?>