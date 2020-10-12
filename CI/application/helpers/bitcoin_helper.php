<?
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function wallet_check($address) {
    $ch = curl_init('https://blockchain.info/q/addressbalance/'.$address);

    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $output = curl_exec($ch);

    if ($err = curl_errno($ch))
        return 'connection error: '.$err;

    curl_close($ch);

    if (strpos($output, 'Illegal') !== FALSE) return FALSE;

    return $output != 'Checksum does not validate';
}

function blockr_wallet_check($address) {

    $ch = curl_init('http://btc.blockr.io/api/v1/address/info/'.$address);

    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $output = curl_exec($ch);

    if ($err = curl_errno($ch))
        return 'connection error: '.$err;

    curl_close($ch);

    $result = json_decode($output, TRUE);

    if (empty($result['status']) || $result['status'] != 'success')
        return FALSE;

    return ($result['data']['is_unknown'] == FALSE);
}

function transaction_check($txId, $address, $amount, $time) {
    $ch = curl_init('http://btc.blockr.io/api/v1/tx/info/'.$txId);

    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $output = curl_exec($ch);

    if ($err = curl_errno($ch))
        return 'connection error: '.$err;

    curl_close($ch);

    $result = json_decode($output, TRUE);

    if (empty($result['status']) || $result['status'] != 'success')
        return $result['message'];

    $txTime = strtotime($result['data']['time_utc']);
    $ckTime = strtotime(gmdate('Y-m-d h:i:s', $time));

    if ($txTime < $time) {
        return 'Transaction occurred before page load.';
    }

    $found = $amountFound = FALSE;
    $minAmount = $amount - PAYMENT_VARIANCE;
    $maxAmount = $amount + PAYMENT_VARIANCE;

    log_message('debug', '<<bjb>> bitcoin_helper: tx='.$txId.' wallet='.$address);

    foreach ($result['data']['vouts'] as $vout) {
        if (trim($address) == trim($vout['address'])) {

            $rAmount = floatval($vout['amount']);
            $rFeeAmount = floatval($vout['amount']) + floatval($result['data']['fee']);

            log_message('debug', '<<bjb>> bitcoin_helper: [1] amount='.$amount);
            log_message('debug', '<<bjb>> bitcoin_helper: [1] result amount='.$vout['amount']);
            log_message('debug', '<<bjb>> bitcoin_helper: [1] result fee amount='.$rFeeAmount);

            $found = TRUE;
            $amountFound = ($rAmount >= $minAmount && $rAmount <= $maxAmount);
            break;
        }
    }

    if ($found == FALSE || $amountFound == FALSE) {

        foreach ($result['data']['trade']['vouts'] as $vout) {
            if (trim($address) == trim($vout['address'])) {

                $rAmount    = floatval($vout['amount']);
                $rFeeAmount = floatval($vout['amount']) + floatval($result['data']['fee']);

                log_message('debug', '<<bjb>> bitcoin_helper: [2] amount='.$amount);
                log_message('debug', '<<bjb>> bitcoin_helper: [2] result amount='.$vout['amount']);
                log_message('debug', '<<bjb>> bitcoin_helper: [2] result fee amount='.$rFeeAmount);

                $found = TRUE;
                $amountFound = ($rAmount >= $minAmount && $rAmount <= $maxAmount);
                break;
            }
        }
    }

    if ($found == FALSE) {
        return 'Payment sent to incorrect wallet';
    }
    if ($amountFound == FALSE) {
        return 'Transaction amount is not correct.';
    }

    return TRUE;
}

function get_confirmations($txId) {
    $ch = curl_init('http://btc.blockr.io/api/v1/tx/info/'.$txId);

    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $output = curl_exec($ch);

    if (curl_errno($ch))
        return FALSE;

    curl_close($ch);

    $result = json_decode($output, TRUE);

    if (empty($result['status']) || $result['status'] != 'success')
        return FALSE;

    return $result['data']['confirmations'];
}
