<table width="1000" border="0" cellpadding="5" cellspacing="0">
    <tr class="tablebanner">
        <td><b>Method</b></td>
        <td><b>Username</b></td>
        <td><b>Gross Amount</b></td>
<? if ($status != 'pending'): ?>
        <td><b>Net Amount</b></td>
<? endif; ?>
        <td><b>From Account</b></td>
        <td><b>To Account</b></td>
<? if ($status == 'pending'): ?>
        <td><b>Waiting</b></td>
        <td><b>Action</b></td>
<? else: ?>
        <td><b>Actioned</b></td>
<? endif; ?>
    </tr>

<?
foreach ($data as $d):
    switch ($d->method)
    {
        case 'bw':
            $account = new BankWire($d->account);
            $account = $account->getTitle();
            $details = new BankWireDetails($d->details);
            break;

        case 'wu':
            $account = new WesternUnion($d->account);
            $account = $account->getTitle();
            $details = new WesternUnionDetails($d->details);
            break;

        default:
            $account = $d->account;
    }
?>
    <tr class="tablehbidder">
        <td><img src="<?=asset('images/currencies/' . $d->method . '.gif')?>" /></td>
        <td><a href="<?=site_url('adminpanel/users/detail/' . $d->user_id)?>"><?=$d->username?></a></td>
<? if ($status != 'pending'): ?>
        <td><?=money($d->gross_amount)?></td>
        <td><?=money($d->amount)?></td>
<? else: ?>
        <td><?=isset($details) ? money($details->amount, $details->currency) : money($d->gross_amount)?></td>
<? endif; ?>
        <td><a href="<?=site_url('adminpanel/cashier/user_account_details/' . $d->user_id . '/' . $d->method)?>" class="popup"><?=$account?></a></td>
        <td><?=$d->deposit_account_name?></td>
<? if ($status == 'pending'): ?>
        <td><?=timespan($d->created, now())?></td>
        <td>
            <a href="<?=site_url('adminpanel/cashier/reject/' . $d->id)?>" class="confirm_row">Reject</a>
            /
            <a href="<?=site_url('adminpanel/cashier/deposit/' . $d->id)?>">Edit</a>
        </td>
<? elseif ($status == 'rejected'): ?>
        <td><?=date('d/m/Y H:i', $d->updated)?> <a href="<?=site_url('adminpanel/cashier/reset/' . $d->id)?>" class="confirm_row">Reset</a></td>
<? else: ?>
        <td><?=date('d/m/Y H:i', $d->updated)?></td>
<? endif; ?>
    </tr>
<? endforeach; ?>

</table>

<? if ($hasPages): ?>
    <span class="paging"><strong>Page:</strong> <?=$paging?></span>
<? endif; ?>