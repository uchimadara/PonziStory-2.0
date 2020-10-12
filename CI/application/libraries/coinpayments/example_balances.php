<?php
/*
	CoinPayments.net API Example
	Copyright 2014 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
	require('./coinpayments.inc.php');
	$cps = new CoinPaymentsAPI();
	$cps->Setup('Your_Private_Key', 'Your_Public_Key');

	$result = $cps->GetBalances();
	if ($result['error'] == 'ok') {
		print 'Coins returned: '.count($result['result'])."\n";
		$le = php_sapi_name() == 'cli' ? "\n" : '<br />';
		foreach ($result['result'] as $coin => $bal) {
			print $coin.': '.sprintf('%.08f', $bal['balancef']).$le;
		}
	} else {
		print 'Error: '.$result['error']."\n";
	}
