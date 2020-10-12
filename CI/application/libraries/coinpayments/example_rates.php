<?php
/*
	CoinPayments.net API Example
	Copyright 2014 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
	require('./coinpayments.inc.php');
	$cps = new CoinPaymentsAPI();
	$cps->Setup('Your_Private_Key', 'Your_Public_Key');

	$result = $cps->GetRates();
	if ($result['error'] == 'ok') {
		print 'Number of currencies: '.count($result['result'])."\n";
		foreach ($result['result'] as $coin => $rate) {
			if (php_sapi_name() == 'cli') {
				print print_r($rate);
			} else {
				print nl2br(print_r($rate, TRUE));
			}
		}
	} else {
		print 'Error: '.$result['error']."\n";
	}
