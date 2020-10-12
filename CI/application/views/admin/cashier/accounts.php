<?=$methodsMenu?>
List of accounts:
<div id="accounts">
    <table width="1000" border="0"  cellpadding="5" cellspacing="0">
        <tr>
            <td>Name</td>
            <td>Details</td>
            <td>Extra 1</td>
            <td>Extra 2</td>
            <td>Direction</td>
            <td>Minimum</td>
            <td>Maximum</td>
            <td>Enabled</td>
            <td>Action</td>
        </tr>
<?
    foreach($accounts as $account):
        switch ($account->payment_code)
        {
            case 'bw':
                $details = new BankWire($account->details);
                $accountDetails = $details->getTitle();
                break;

            case 'wu':
                $details = new WesternUnion($account->details);
                $accountDetails = $details->getTitle();
                break;

            default:
                $accountDetails = $account->details;
        }
?>
        <tr>
            <td><a href="<?=site_url('adminpanel/cashier/accounts/' . $code . '/' . $account->id)?>" class="popup"><?=$account->name?></a></td>
            <td><?=$accountDetails?></td>
            <td><?=$account->extra_field_1?></td>
            <td><?=$account->extra_field_2?></td>
            <td><?=$account->restrict_to?></td>
            <td><?=money($account->minimum)?></td>
            <td><?=money($account->maximum)?></td>
            <td><input type="checkbox" class="status" name="<?=$account->id?>" value="on"<?=$account->enabled ? ' checked' : ''?> /></td>
            <td><a href="<?=site_url('adminpanel/cashier/account/' . $code . '/' . $account->id)?>">Edit</a></td>
        </tr>
<? endforeach; ?>
        <tr>
            <td><a href="<?=site_url('adminpanel/cashier/account/' . $code)?>"  class="button_blue">Add New Account</a></td>
        </tr>
    </table>
</div>