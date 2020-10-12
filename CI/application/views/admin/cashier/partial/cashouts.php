<? if (!$cashouts) { echo '<div class="alert alert-info">No cashouts</div>'; }
else { ?>

    <?=form_open(site_url('adminpanel/cashier/process_cashouts/' . $code), array('method' => 'post')); ?>
    <table class="table">
        <tr>
            <th>Method</th>
            <th>Username</th>
            <th>Status</th>
            <th>Account</th>
            <th>Gross Amount</th>
            <th>Net Amount</th>
            <th>Fee</th>
<? if ($status == 'pending'): ?>
            <th>Waiting</th>
            <th>Select</th>
            <th>Action</th>

<? elseif ($status == 'ok'): ?>
            <th>Cost</th>
            <th>Reference</th>
            <th>Actioned</th>
<? else: ?>
            <th>Actioned</th>
<? endif; ?>
        </tr>
<?
    foreach ($cashouts as $d):
        switch ($d->method)
        {
            case 'bw':
                $details = new BankWire($d->account);
                $account = $details->getTitle();
                break;

            case 'wu':
                $details = new WesternUnion($d->account);
                $account = $details->getTitle();
                break;

            default:
                $account = $d->user_account;
        }
?>
        <tr>
            <td><span class="ppIcon <?=$d->method?>"></span></td>
            <td><a href="<?=site_url('adminpanel/users/detail/' . $d->user_id)?>"><?=$d->username?></a></td>
            <td><?= $d->status ?></td>
            <td><?=$account?></td>
            <td><a href="<?=site_url('adminpanel/cashier/cashout_details/' . $d->id)?>" class="popup" title="Account Audit"><?=money($d->gross_amount)?></a></td>
            <td><?=money($d->amount)?></td>
            <td><?=money($d->fee)?></td>
<? if ($status == 'pending'): ?>
            <td><?=timespan($d->created, now())?></td>
            <td><input type="checkbox" name="cashout[]" value="<?=$d->id?>"/></td>
            <td><a href="<?=site_url('adminpanel/cashier/reject_cashout/' . $d->id)?>" class="confirm_row">Reject</a></td>
<? elseif ($status == 'ok'): ?>
            <td><?=money($d->cost)?></td>
            <td><?=$d->reference?></td>
            <td><?=date('d/m/Y H:i', $d->updated)?></td>
<? else: ?>
            <td><?=date('d/m/Y H:i', $d->updated)?></td>
<? endif; ?>
        </tr>
<? endforeach; ?>
    </table>
<? if ($status == 'pending'): ?>
    <div class="formBottom p-10 right">
            <input type="submit" class="btn btn-alt" value="Send Selected"/>
    </div>
<? endif; ?>

    </form>

<? if ($hasPages): ?>
    <span class="paging"><strong>Page:</strong> <?=$paging?></span>
<? endif; ?>
<? } ?>