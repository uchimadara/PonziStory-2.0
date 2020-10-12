<?
    $paymentDetails = null;

    switch ($code)
    {
        case 'bw':
            $bankWire = new BankWire($userAccount->account);
            $userAccountDetails = $bankWire->getTitle();

            $paymentDetails = $details ? new BankWireDetails($details->details) : null;

            break;

        case 'wu':
            $westernUnion = new WesternUnion($userAccount->account);
            $userAccountDetails = $westernUnion->getTitle();;

            $paymentDetails = $details ? new WesternUnionDetails($details->details) : null;

            break;

        default:
            $userAccountDetails = $userAccount->account;
    }

    // Setting some of the defaults for the edit or add version
    $url = $details ? 'adminpanel/cashier/deposit/' . $details->id : 'adminpanel/cashier/deposit/0/' . $userId . '/' . $code;
?>

<div class="formContainer">
    <?=form_open($url, array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'depositFrm')); ?>
        <table>
            <tr>
                <td><strong>From</strong></td>
                <td><a href="<?=site_url('adminpanel/users/detail/' . $userId)?>"><?=$username?></a></td>
            </tr>
            <tr>
                <td valign="top"><strong>Using</strong></td>
                <td><a href="<?=site_url('adminpanel/cashier/user_account_details/' . $userId . '/' . $code)?>" class="popup"><?=$userAccountDetails?></a></td>
            </tr>
            <tr>
                <td><strong>To</strong></td>
                <td>
<?
    if (count($accounts) > 1)
    {
        echo form_dropdown('account_id', $accounts, $details ? $details->account_id : null);
    }
    else
    {
        reset($accounts);
        $account_id = key($accounts);

        echo $accounts[$account_id] . form_hidden('account_id', $account_id);
    }
?>
                </td>
            </tr>
<?
    switch ($code)
    {
        case 'bw':
?>
            <tr>
                <td valign="top"><label for="memo"><strong>Memo</strong></label></td>
                <td><?=form_textarea('memo', $paymentDetails ? $paymentDetails->memo : '', 'id="memo"')?></td>
            </tr>
            <tr>
                <td valign="top"><label for="info"><strong>Info</strong></label></td>
                <td><?=form_textarea('info', $paymentDetails ? $paymentDetails->info : '', 'id="info"')?></td>
            </tr>
<? if ($details): ?>
            <tr>
                <td>Pick up Amount (<?=$paymentDetails->currency?>)</td>
                <td><?=money($paymentDetails->amount, $paymentDetails->currency)?></td>
            </tr>
<? endif; ?>
<?
            break;

        case 'wu':
?>
            <tr>
                <td valign="top"><label for="city"><strong>City</strong></label></td>
                <td><?=form_input('city', $paymentDetails ? $paymentDetails->city : '', 'id="city"')?></td>
            </tr>
            <tr>
                <td valign="top"><label for="region"><strong>Region</strong></label></td>
                <td><?=form_input('region', $paymentDetails ? $paymentDetails->region : '', 'id="region"')?></td>
            </tr>
            <tr>
                <td valign="top"><label for="zip"><strong>Postcode</strong></label></td>
                <td><?=form_input('zip', $paymentDetails ? $paymentDetails->zip : '', 'id="zip"')?></td>
            </tr>
            <tr>
                <td valign="top"><label for="country"><strong>Country</strong></label></td>
                <td><?=form_dropdown('country', $countries, $paymentDetails ? $paymentDetails->country : '', 'id="country"')?></td>
            </tr>
            <tr>
                <td valign="top"><label for="mtcn"><strong>MTCN</strong></label></td>
                <td><?=form_input('mtcn', $paymentDetails ? $paymentDetails->mtcn : '', 'id="mtcn"')?></td>
            </tr>
            <tr>
                <td valign="top"><label for="transfer_date"><strong>Transfer Date</strong></label></td>
                <td><?=form_input('transfer_date', $paymentDetails ? $paymentDetails->transfer_date : '', 'id="transfer_date"')?></td>
            </tr>
<? if ($details): ?>
            <tr>
                <td>Pick up Amount (<?=$paymentDetails->currency?>)</td>
                <td><?=money($paymentDetails->amount, $paymentDetails->currency)?></td>
            </tr>
<? endif; ?>
<?
            break;
    }
?>
            <tr>
                <td><label for="gross_amount"><strong>Gross Amount (USD)</strong></label></td>
                <td><?=form_input('gross_amount', $paymentDetails ? ($paymentDetails->currency == 'USD' ? $details->gross_amount : '') : ($details ? $details->gross_amount : ''), 'id="gross_amount"')?></td>
            </tr>
<? if ($details): ?>
            <tr>
                <td><strong>Net Amount</strong></td>
                <td><?=$details->amount?></td>
            </tr>
            <tr>
                <td><strong>Fee</strong></td>
                <td><?=$details->fee?></td>
            </tr>
            <tr>
                <td><label for="cost"><strong>Cost</strong></label></td>
                <td><?=form_input('cost', $details ? $details->cost : '', 'id="cost"')?></td>
            </tr>
            <tr>
                <td><strong>Identifier</strong></td>
                <td><?=$details->identifier?></td>
            </tr>
            <tr>
                <td><strong>Created</strong></td>
                <td><?=date("d/m/Y H:i", $details->created)?></td>
            </tr>
<? endif; ?>
            <tr>
                <td><label for="reference"><strong>Reference</strong></label></td>
                <td><?=form_input('reference', '', 'id="reference"')?></td>
            </tr>
            <tr>
                <td></td>
                <td><?=form_submit('ok', 'Payment received')?></td>
            </tr>
        </table>
    </form>
    <span class="loading" style="display: none; "><img src="<?=asset('images/loading.gif')?>"></span>
</div>