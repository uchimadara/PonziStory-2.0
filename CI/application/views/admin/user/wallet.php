<div class="col-lg-12 fs14">
    <h3>User Wallet</h3>

    <div class="fs18"><?= $wallet->method_name ?> &nbsp;&nbsp; <?= $wallet->note ?> &nbsp;&nbsp; <?= $wallet->account ?></div>
    <? if (!empty($changes)) { ?>

        <h3>Wallet Changes</h3>
        <table class="rwd-table">
            <tr>
                <th>Date</th>
                <th>Old Wallet</th>
                <th>New Wallet</th>
                <th>Status</th>
            </tr>
            <? foreach ($changes as $c) { ?>
                <tr>
                    <td data-th="Date"><?= date(DEFAULT_DATETIME_FORMAT, $c->date) ?> </td>
                    <td data-th="Old Wallet"><?= $c->old_wallet ?></td>
                    <td data-th="New Wallet"><?= $c->new_wallet ?></td>
                    <td data-th="Status"><?= $c->status ?></td>
                </tr>
            <? } ?>
        </table>
    <? } ?>
</div>