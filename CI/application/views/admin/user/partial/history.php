<table class="commissions">
    <tr>
        <th class="W15">Date</th>
        <th class="W10">Bal.</th>
        <th class="W15">Amount</th>
        <th>Description</th>
        <th class="W15">Closing Balance</th>
    </tr>
<?
foreach ($data as $d):
    $date    = Date("d/m/Y", $d->date);
    $time    = Date("H:i:s", $d->date);
    $amount  = $d->amount;
    $_method = $d->method;
    $message = NULL;

    $balance = money($d->balance);

    // Now let's see what we display in terms of colors
    switch ($d->type)
    {
        case 'initial':
            $amount  = money($amount, '$', TRUE);
            $message = 'Balance brought forward';

            break;

        case 'deposit':
            $amount  = money($amount, '$', TRUE);
            $message = $d->method_name . ' deposit #' . $d->id . '<br/>Reference: ' . $d->info1;

            break;

        case 'cashout':
            switch ($d->status)
            {
                case 'pending':
                    $amount  = money($amount, '$', TRUE);
                    $message = $d->method_name . ' cashout #' . $d->id . ' requested';

                    break;

                case 'ok':
                    $amount  = '<span>' . money($amount) . '</span>';
                    $message = $d->method_name . ' cashout #' . $d->id . ' paid<br/>Reference: ' . $d->info1;

                    break;

                case 'rejected':
                case 'cancelled':
                    $amount  = money($amount, '$', TRUE);
                    $message = $d->method_name . ' cashout #' . $d->id . ' cancelled';

                    break;
            }

            break;

        case 'transfer':
            if ($method == NULL) // if we are in the summary page, no color needed
            {
                $amount  = '<span>' . money($amount) . '</span>';
                $_method = 'eb';
            }
            else
            {
                $amount = money($amount, '$', TRUE);
                if ($method == 'eb')
                    $_method = 'eb';
            }

            $message = 'Earnings Transfer #' . $d->id . ' to ' . $d->method_name;

            break;

        case 'dividend':
            $amount  = money($amount, '$', TRUE);
            $message = 'Shareholder Dividend #' . $d->info1 . ' for ' . number_format($d->info2) . ' ' . pluralise('share', $d->info2);

            break;

        case 'm_pro':
            $amount  = money($amount*-1, '$', TRUE);
            $message = 'Monitor Voting Game Pro Upgrade ';
            break;

        case 'm_vote':
            $amount  = money($amount*-1, '$', TRUE);
            $message = 'Voting Game '.(($d->info1 == 1) ? 'YES' : 'NO').' vote for '.anchor('monitor/listing/'.$d->info2.'.html', $d->info3);
            break;

        case 'm_point':
            $amount  = money($amount*-1, '$', TRUE);
            $message = 'Monitor Voting Game '.$d->info1.' points purchase ';
            break;

        case 'm_listing':
            $amount  = '<span > '.money($amount).'</span >';
            $message = 'Monitor Listing  '.anchor('monitor/listing/'.$d->info1.'.html', $d->info2).' activated ';

            break;

        case 'm_invest':
            $amount  = money($amount*-1, '$', TRUE);
            $message = 'Investment for monitor listing  '.anchor('monitor/listing/'.$d->info1.'.html', $d->info2);
            break;

        case 'm_listing_upgrade':
            $amount  = money($amount*-1, '$', TRUE);
            $message = 'Bought '.$d->info3.' premium days for listing  '.anchor('monitor/listing/'.$d->info1.'.html', $d->info2);
            break;

        case 'm_listing_add':
            $amount  = money($amount*-1, '$', TRUE);
            $message = 'Monitor Listing  &quot;'.$d->info2.'&quot; submitted; pending approval ';
            break;

        case 'm_listing_reject':
            $amount  = money($amount, '$', TRUE);
            $message = 'Monitor Listing  &quot;'.$d->info2.'&quot; rejected ';
            break;

        case 'm_prize':
            $amount  = money($amount, '$', TRUE);
            $message = 'Monitor Voting Game monthly cash prize ';
            break;

        case 'vg_prize':
            $amount = money($amount, '$', TRUE);
            switch ($d->info1) {
                case 'daily':
                    $message = 'Voting Game correct vote for '.anchor('monitor/listing/'.$d->info3.'.html', $d->info4);
                    break;

                case 'weekly':
                    $message = anchor('monitor.html', 'Voting Game').' weekly competition cash prize ';
                    break;

                case 'six_month':
                    $message = anchor('monitor.html', 'Voting Game').' six-month competition cash prize ';
                    break;
            }
            break;

        case 'm_cashout':
            $amount  = money($amount, '$', TRUE);
            $message = 'Monitor listing '.anchor('monitor/listing/'.$d->info1.'.html', $d->info2).' cashout referral bonus ';
            break;

        case 'm_listing_ref':
            $amount  = money($amount, '$', TRUE);
            $message = 'Monitor listing '.anchor('monitor/listing/'.$d->info1.'.html', $d->info3).' referral commission ';
            break;

        case 'm_pro_ref':
            $amount  = money($amount, '$', TRUE);
            $message = 'Monitor Voting Game Pro Upgrade referral fee for <b>'.$d->info1.'</b> ';
            break;

        case 'm_premium_ref':
            $amount  = money($amount, '$', TRUE);
            $message = 'Voting Game referral payment for Premium Upgrade by <b>'.$d->info1.'</b> for '.anchor('monitor/listing/'.$d->info2.'.html', $d->info3);
            break;

        case 'bid':
            if ($d->status == 'running')
            {
                $amount  = money($amount * -1, '$', TRUE);
                $message = 'Bid for ' . number_format($d->info1) . ' ' . pluralise('share', $d->info1) . ' @ ' . money($d->info2) . ' each';
            }
            else if ($d->status == 'completed')
            {
                $amount  = '<span>' . money($amount) . '</span>';
                $message = 'Bid for ' . number_format($d->info1) . ' ' . pluralise('share', $d->info1) . ' completed';
            }
            else
            {
                $amount  = money($amount, '$', TRUE);
                $message = 'Bid for ' . number_format($d->info1) . ' ' . pluralise('share', $d->info1) . ' ' . $d->status;
            }

            break;

        case 'shares_buy':
            if ($d->status == 'pending')
            {
                $amount  = money($amount * -1, '$', TRUE);
                $message = 'Bought ' . number_format($d->info1) . ' ' . pluralise('share', $d->info1) . ' @ ' . money($d->info2) . ' each from '. $d->info3 . ' (pending approval)';
            }
            else if ($d->status == 'denied')
            {
                $amount  = money($amount, '$', TRUE);
                $message = 'Refund for denied purchase of ' . number_format($d->info1) . ' ' . pluralise('share', $d->info1) . ' from '. $d->info3;
            }
            else
            {
                $amount  = '<span>' . money($amount) . '</span>';
                $message = 'Bought ' . number_format($d->info1) . ' ' . pluralise('share', $d->info1) . ' @ ' . money($d->info2) . ' each from '. $d->info3;
            }

            break;

        case 'shares_sell':
            $amount  = money($amount, '$', TRUE);
            $message = 'Sold ' . number_format($d->info1) . ' ' . pluralise('share', $d->info1) . ' @ ' . money($d->info2) . ' each to '. $d->info3;

            break;

        case 'investment':
            $amount  = money($amount * -1, '$', TRUE);
            $message = 'Traffic Value Investment: ' . money($d->amount) . ' into Round ' . $d->info1;

            break;

        case 'unit_cycle':
            $amount  = money($amount, '$', TRUE);
            $message = 'Traffic Value Investment 110% Payment for ' . number_format($d->info3) . ' ' . pluralise('unit', $d->info3) . ($d->info2 == 1 ? '. Automatically Re-invested!' : '');

            break;

        case 'ref_investment':
            $amount  = money($amount, '$', TRUE);
            $message = 'Main Investment Referral Payment: <strong>' . $d->info2 . '</strong> (Round #' . $d->info1 . ')';

            break;

        case 'daily_pay':
            $amount = money($amount, '$', TRUE);

            if ($d->info2 == 1) // Fast Tracked Payment?
                $message = 'Traffic Value Investment: Fast Track Payment';
            else $message = 'Traffic Value Investment: Daily Dividend for <strong>' . $d->info1 . '</strong> units';

            break;

        case 'powerplan':
            $amount = money($amount * -1, '$', TRUE);

            $message = 'Power Plan: <strong>' . money($d->info1) . '</strong> invested to return 194%';

            break;

        case 'powerplan_pay':
            $amount = money($amount, '$', TRUE);

            if ($d->info2 == 1) // Fast Tracked Payment?
                $message = 'Power Plan: Fast Track Payment';
            else $message = 'Power Plan: Daily Dividend for <strong>' . $d->info1 . '</strong> units';

            break;

        case 'ref_powerplan':
            $amount  = money($amount, '$', TRUE);
            $message = 'Power Plan Referral Payment: <strong>' . $d->info2 . '</strong> (Round #' . $d->info1 . ')';

            break;

        case 'power186':
            $amount = money($amount * -1, '$', TRUE);

            $message = 'Power Plan 186: <strong>' . money($d->info1) . '</strong> invested to return 186%';

            break;

        case 'power186_pay':
            $amount = money($amount, '$', TRUE);

            if ($d->info2 == 1) // Fast Tracked Payment?
                $message = 'Power Plan 186: Fast Track Payment';
            else $message = 'Power Plan 186: Daily Dividend for <strong>' . $d->info1 . '</strong> units';

            break;

        case 'ref_power186':
            $amount  = money($amount, '$', TRUE);
            $message = 'Power Plan 186 Referral Payment: <strong>' . $d->info2 . '</strong> (Round #' . $d->info1 . ')';

            break;

        case 'ft250':
            $amount = money($amount * -1, '$', TRUE);

            $message = 'Fast Track 250: <strong>' . money($d->info1) . '</strong> invested to return 250%';

            break;

        case 'ft250_pay':
            $amount = money($amount, '$', TRUE);

            $message = 'Fast Track 250: Fast Track Payment';

            break;

        case 'ref_ft250':
            $amount  = money($amount, '$', TRUE);
            $message = 'Fast Track 250 Referral Payment: <strong>' . $d->info2 . '</strong> (Round #' . $d->info1 . ')';

            break;

        case 'standard_game':
            if ($d->status == 'lose') {
                $message = $d->info1.' loss';
            } elseif ($d->status == 'win') {
                $message = $d->info1.' win';
            } else {
                $message = $d->info1.' push';
            }
            $message .= ' with a bet of '.money($d->info2);
            $amount = money($amount, '$', TRUE);
            break;

        case 'game_coin_flip':
        case 'game_slot':
            if ($d->status == 'lose')
            {
                $amount  = $amount * -1;
                $message = 'The ' . $d->info1 . ' loss';
            }
            else
            {
                $message = 'The ' . $d->info1 . ' win with bet of ' . money($amount);
                $amount  = $d->info2 - $amount;
            }

            $amount = money($amount, '$', TRUE);

            break;

        case 'game_spinner':
            if ($d->status == 'lose')
            {
                $amount  = ($amount - $d->info2) * -1;
                $message = 'The ' . $d->info1 . ' loss';
            }
            else
            {
                if ($amount == $d->info2)
                    $message = 'The ' . $d->info1 . ' your ' . money($amount) . ' money back';
                else $message = 'The ' . $d->info1 . ' win with bet of ' . money($amount);
                $amount  = $d->info2 - $amount;
            }

            $amount = money($amount, '$', TRUE);

            break;

        case 'game_lotto_purchase':
            $message = 'Daily Lotto purchase '.$d->info1.' ticket'.((intval($d->info1) > 1) ? 's' : '');
            $amount  = money(-$amount, '$', TRUE);
            break;

        case 'game_lotto_prize':
            $message = 'Daily Lotto win!';
            $amount  = money($amount, '$', TRUE);
            break;

        case 'fixed_ads':
            $amount  = money($amount * -1, '$', TRUE);
            $message = 'Purchased ' . $d->info2 . ' ' . pluralise('slot', $d->info2) . ' @' . money($d->info3) . ' for Campaign #' . str_pad($d->info1, 4, '0', STR_PAD_LEFT);
            break;

        case 'blackjack':
            if ($d->status == 'lose')
            {
                $message = '' . $d->info1 . ' loss with bet total of ' . money($amount);
                $amount = $amount * -1;
            }
            elseif($d->status == 'push')
            {
                $message = '' . $d->info1 . ' push (tie) with bet total of ' . money($amount);
                $amount = $d->info2 - $amount;
            }
            else
            {
                $message = '' . $d->info1 . ' win with bet total of ' . money($amount);
                $amount = $d->info2 - $amount;
            }

            $amount = money($amount, '$', TRUE);

            break;

        case 'jacks_or_better':
        case 'casino_holdem':
        case 'let_it_ride':
        case 'holdem_bonus':
            if ($d->status == 'lose')
            {
                $message = '' . $d->info1 . ' loss with bet total of ' . money($amount);
                $amount = $amount * -1;
            }
            elseif($d->status == 'push')
            {
                $message = '' . $d->info1 . ' broke even with bet total of ' . money($amount);
                $amount = $d->info2 - $amount;
            }
            else
            {
                $message = '' . $d->info1 . ' won with bet total of ' . money($amount);
                $amount = $d->info2 - $amount;
            }

            $amount = money($amount, '$', TRUE);
            break;

        case 'game_boxes':
            if ($d->status == 'loss')
            {
                $message = $d->info1 . ' loss on level ' . $d->info3;
                $amount  = $amount * -1;
            }
            else
            {
                $message = $d->info1 . ' bet ' . money($amount). ' and won on level ' . $d->info3;
                $amount  = $d->info2 - $amount;
            }

            $amount = money($amount, '$', TRUE);

            break;

        case 'ref_adverts':
            case 'ref_adverts_fixed':
                $amount  = money($amount, '$', TRUE);
            $message = 'Campaign Purchase Referral Payment: <strong>' . $d->info2 . '</strong> (Campaign #' . str_pad($d->info1, 4, '0', STR_PAD_LEFT) . ')';

            break;

        case 'auction_ads':
            if ($d->status == 'bid')
            {
                $amount  = money($amount * -1, '$', TRUE);
                $message = 'Bid for ' . anchor('campaign/bid/' . $d->info2 . '.html', $d->info2) . ' - Campaign #' . str_pad($d->info1, 4, '0', STR_PAD_LEFT);
}
            else
            {
                $amount  = money($amount, '$', TRUE);
                $message = 'Refund for your bid from ' . anchor('campaign/bid/' . $d->info2 . '.html', $d->info2) . ' - Campaign #' . str_pad($d->info1, 4, '0', STR_PAD_LEFT);
            }
            break;

        case 'ref_banner_auction':
            $amount  = money($amount, '$', TRUE);
            $message = 'Banner Auction Referral Payment: <strong>' . $d->info2 . '</strong> (Campaign #' . str_pad($d->info1, 4, '0', STR_PAD_LEFT) . ')';

            break;

        case 'topbanner_ads':
            if ($d->status == 'bid')
            {
                $amount  = money($amount * -1, '$', TRUE);
                $message = 'Bid for ' . anchor('campaign/view_bids/' . date('Y-m-d-H', strtotime($d->info2.':00')) . '.html', $d->info2) . ' - Campaign #' . str_pad($d->info1, 4, '0', STR_PAD_LEFT);
            }
            else
            {
                $amount  = money($amount, '$', TRUE);
                $message = 'Refund for your bid from ' . anchor('campaign/view_bid/' . date('Y-m-d-H', strtotime($d->info2.':00')) . '.html', $d->info2) . ' - Campaign #' . str_pad($d->info1, 4, '0', STR_PAD_LEFT);
            }
            break;

        case 'ref_topbanner':
            $amount  = money($amount, '$', TRUE);
            $message = 'Top Banner Auction Referral Payment: <strong>' . $d->info2 . '</strong> (Campaign #' . str_pad($d->info1, 4, '0', STR_PAD_LEFT) . ')';

            break;

        case 'adjustment':
            $amount  = money($amount, '$', TRUE);
            $message = ($method == 'all' ? strtoupper($d->method . ' ') : '') . 'Admin adjustment' . ($d->info1 ? ': ' . $d->info1 : '');

            break;

        case 'ecurrency_transfer':
            if ($method == 'all')
                $amount = money($amount * $d->info2 * -1, '$', TRUE);
            else if ($method == $d->info1)
            {
                $_method = strtoupper($d->info1);
                $amount  = money($amount * -1, '$', TRUE);
                $balance = money(0);
            }
            else $amount = money($amount - $amount * $d->info2, '$', TRUE);

            $message = strtoupper($d->info1) . ' eCurrency transfer to ' . strtoupper($d->method);

            break;

        case 'experimental_investment':
            $message = 'Experimental Fund investment - Capital ' . money($amount * 0.9);
            $amount  = money($amount * -1, '$', TRUE);

            break;

        case 'experimental_payment':
            $amount = money($amount, '$', TRUE);
            switch ($d->info1)
            {
                case 'payment':
                    $message = 'Experimental Fund Payment on Capital ' . money($d->info2);
                    break;

                case 'refund':
                    $message = 'Experimental Fund Refund Payment on Capital ' . money($d->info3);
                    break;

                case 'cashout':
                    $message = 'Experimental Fund Cashout of remaining Capital ' . money($d->info2);
                    break;
            }

            break;

        case 'experimental_payment_ref':
            $amount = money($amount, '$', TRUE);
            $message = 'Experimental Fund Referral Payment: <strong>' . $d->info2 . '</strong> (Capital ' . money($d->info1) . ')';

            break;
    }
?>
    <tr>
        <td valign="top"><?=$date?><br/><span class="smaller"><?=$time?></span></td>
        <td valign="top"><?=strtoupper($_method)?></td>
        <td valign="top"><?=$amount?></td>
        <td valign="top"><?=$message?></td>
        <td valign="top" align="right"><?=$balance?></td>
    </tr>
    <? endforeach; ?>
</table>
<? if ($hasPages): ?>
<span class="paging"><strong>Page:</strong> <?=$paging?></span>
<? endif; ?>