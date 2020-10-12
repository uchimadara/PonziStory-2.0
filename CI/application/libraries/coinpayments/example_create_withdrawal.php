<?php
/*
	CoinPayments.net API Example
	Copyright 2014 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
	require('./coinpayments.inc.php');
	$cps = new CoinPaymentsAPI();
	$cps->Setup('Your_Private_Key', 'Your_Public_Key');

	$result = $cps->CreateWithdrawal(0.1, 'BTC', '1LC9Tn7ekRXhMTzh7ZJnZ55XUBM4ZGuLhJ');
	if ($result['error'] == 'ok') {
		print 'Withdrawal created with ID: '.$result['result']['id'];
	} else {
		print 'Error: '.$result['error']."\n";
	}
