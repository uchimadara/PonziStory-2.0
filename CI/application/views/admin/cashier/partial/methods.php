<div class="styleshow">
<?
    foreach ($methods as $method):
        $methodName = $method->name;
        $pageUrl = $url . '/' . $method->code;
?>
    <? if ($code == $method->code): ?>
    <strong><?=$methodName?></strong>
    <? else: ?>
    <a class="btn btn-alt" href="<?=$pageUrl?>"><?=$methodName?></a>
    <? endif; ?>
<? endforeach; ?>
</div>
<? if (false): //($code): ?>
<div class="styleshow">
    <a href="<?=site_url('adminpanel/cashier/cashouts/' . $code)?>">Cashout</a>
    <a href="<?=site_url('adminpanel/cashier/deposits/' . $code)?>">Deposits</a>
    <a href="<?=site_url('adminpanel/cashier/accounts/' . $code)?>">Accounts</a>
    <a href="<?=site_url('adminpanel/cashier/settings/' . $code)?>">Settings</a>
    <a href="<?=site_url('adminpanel/cashier/users/' . $code)?>">Balances</a>
</div>
<? endif; ?>