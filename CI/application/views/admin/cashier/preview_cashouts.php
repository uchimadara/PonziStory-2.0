<?
//    if (count ($sendFrom) > 1)
//    {
//        $fromAccounts = array();
//        foreach ($sendFrom as $acct)
//            $fromAccounts[$acct->id] = $acct->name;
//    }
?>
<h1 class="yellow">Preview of cashouts</h1>
<p>
    Clicking submit will pay all but PayPal.
    Pay the PayPal cashouts manually and enter the transaction ID, and those cashouts will be marked paid.
    Any cashouts where the Transaction ID is entered will be assumed to be paid already.
</p>
<div class="tile">
    <?= form_open(site_url('adminpanel/cashier/process_cashouts/'.$code), array('method' => 'post')); ?>
    <table class="table">
        <tr>
            <th>Method</th>
            <th>Username</th>
            <th>Account</th>
            <th>Net Amount</th>
            <th>Cost</th>
            <? if ($code == 'bw'): ?>
                <th>Info</th>
                <th>Pickup Amount</th>
                <th>Currency</th>
            <? elseif ($code == 'wu'): ?>
                <th>MTCN</th>
                <th>Pickup Amount</th>
                <th>Currency</th>
            <? endif; ?>
            <!-- <th>Send From</th> -->
            <th>Transaction ID</th>
        </tr>
        <?
        foreach ($cashouts as $d):
            switch ($d->method) {
                case 'bw':
                    $details = new BankWire($d->account);
                    $account = $details->getTitle();
                    break;

                case 'wu':
                    $details = new WesternUnion($d->account);
                    $account = $details->getTitle();
                    break;

                default:
                    $account = $d->account;
            }
            ?>
            <tr>
                <td><img src="<?= asset('images/currencies/'.$d->method.'.gif') ?>"/></td>
                <td><a href="<?= site_url('adminpanel/users/detail/'.$d->user_id) ?>"><?= $d->username ?></a></td>
                <td><?= $account ?></td>
                <td>
                    <a href="<?= site_url('adminpanel/cashier/cashout_details/'.$d->id) ?>" class="popup"><?= money($d->amount) ?></a>
                </td>
                <? if ($code == 'bw'): ?>
                    <td>
                        <input type="text" class="form-control" name="cost[<?= $d->id ?>]" value="<?= isset($costs[$d->id]) ? $costs[$d->id] : '' ?>"/>
                    </td>
                    <td>
                        <textarea name="info[<?= $d->id ?>]"><?= isset($infos[$d->id]) ? $infos[$d->id] : '' ?></textarea>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="pickup_amount[<?= $d->id ?>]" value="<?= isset($pickupAmounts[$d->id]) ? $pickupAmounts[$d->id] : '' ?>"/>
                    </td>
                    <td><?= form_dropdown('pickup_currency['.$d->id.']', array('USD' => 'USD', 'EUR' => 'EUR'), isset($pickupCurrencies[$d->id]) ? $pickupCurrencies[$d->id] : '') ?></td>
                <? elseif ($code == 'wu'): ?>
                    <td>$0.00</td>
                    <td>
                        <input type="text" class="form-control" name="mtcn[<?= $d->id ?>]" value="<?= isset($mtcns[$d->id]) ? $mtcns[$d->id] : '' ?>"/>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="pickup_amount[<?= $d->id ?>]" value="<?= isset($amounts[$d->id]) ? $amounts[$d->id] : '' ?>"/>
                    </td>
                    <td><?= form_dropdown('pickup_currency['.$d->id.']', array('USD' => 'USD', 'EUR' => 'EUR'), isset($currencies[$d->id]) ? $currencies[$d->id] : '') ?></td>
                <?
                else: ?>
                    <td><input type="hidden" name="cost[<?= $d->id ?>]" value="<?= $d->cost ?>"/><?= money($d->cost) ?>
                    </td>
                <? endif; ?>
                <!--  <td>
<?
  //  if (count ($sendFrom) > 1)
  //  {
  //      echo form_dropdown('account[' . $d->id . ']', $fromAccounts, isset($accounts[$d->id]) ? $accounts[$d->id] : '');
   // }
   // else {
  //      echo '<input type="hidden" name="account['.$d->id.']" value="'.$sendFrom->id.'" />'.$sendFrom->name;
  //  }
?>
                </td> -->
                <td>
                    <input type="text" class="form-control" name="reference[<?= $d->id ?>]" value="<?= isset($references[$d->id]) ? $references[$d->id] : '' ?>"/>
                    <input type="hidden" name="cashout[]" value="<?= $d->id ?>"/>
                </td>
            </tr>
        <? endforeach; ?>
    </table>
    <div class="p-10">
        <? if (false): //($code == 'pp'): ?>
            API Key: <input type="text" class="form-control" name="passphrase" id="passphrase"/> <br/>
        <? endif; ?>
        <input type="submit" class="btn btn-alt" value="Submit" name="commit"/>
        <a href="<?=SITE_ADDRESS?>adminpanel/cashier/cashouts" class="btn btn-alt">Cancel</a>
    </div>
    </form>
</div>