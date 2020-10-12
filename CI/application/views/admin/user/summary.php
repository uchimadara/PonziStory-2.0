<div class="styleshow">
    <a href="<?= site_url('adminpanel/users/detail/'.$user->id) ?>">Profile</a>
    <a href="<?= site_url('adminpanel/users/campaigns/'.$user->id) ?>">Campaigns</a>
    <a href="<?= site_url('adminpanel/users/history/'.$user->id.'/all') ?>">History</a>
    <a href="<?= site_url('adminpanel/users/tickets/'.$user->id) ?>">Tickets</a>

</div>
<h2>Transaction Summary for <?=anchor('/adminpanel/users/detail/' . $user->id, $user->username)?></h2>
<b>Total Deposits:</b> <?= money($data['totalDeposits']) ?> <br/>
<b>Total Cashouts:</b> <?=money($data['totalCashouts'])?> <br />
<b>Total Ref. Comm.:</b> <?= money($data['refComm']) ?> <br/>

<div class="rounded">
    <h3>Deposits</h3>

        <div class="pageable">
            <?= $data['deposits'] ?>
        </div>


</div>
<div class="rounded">
    <h3>Cashouts</h3>

        <div class="pageable">
            <?= $data['cashouts'] ?>
        </div>


</div>
<div class="rounded">
    <h3>EB Transfers</h3>

        <div class="pageable">
            <?= $data['transfers'] ?>
        </div>

</div>
<div class="rounded">
    <h3>LR Transfers</h3>
        <div class="pageable">
            <?= $data['lr_transfers'] ?>
        </div>

</div>
