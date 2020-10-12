Hello <?= $username ?>!<br/>
Thank you for being an owner of <?=SITE_NAME?>. Each month we pay dividends to shareholders
from the profits of the company. Find details below.

<br/>
<br/>
<b>Dividend Payment Statement for period <?= date(DEFAULT_DATE_FORMAT, $begin) ?> through <?= date(DEFAULT_DATE_FORMAT, $end) ?></b>
<br/>
<table>
    <tr>
        <td colspan="2">
            <b>DIVIDEND PAYMENT RUN #</b><?= number_format($run) ?>
        </td>
    </tr>
    <tr>
        <td>
            Member Purchases
        </td>
        <td>
            <?= money($summary['membership']['amount'] + $summary['advertising']['amount']) ?>
        </td>
    </tr>
    <tr>
        <td>
            Ref. Comm. Paid
        </td>
        <td>
            <?= money(-$summary['ref_comm']['amount']) ?>
        </td>
    </tr>
    <tr>
        <td>
            Members Earned
        </td>
        <td>
            <?= money(-$summary['earning']['amount']) ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Expenses</b>
        </td>
    </tr>
    <? foreach ($expenses as $cat => $total) { ?>
        <tr>
            <td><?= $cat ?></td>
            <td> <?= money($total) ?></td>
        </tr>
    <? } ?>
    <tr>
        <td><b>TOTAL EXPENSES</b></td>
        <td> <?= money(-$totalExpenses) ?></td>
    </tr>
    <tr>
        <td>
            Tokens Earned
        </td>
        <td>
            <?= money(-$summary['tokens']*.10) ?>
        </td>
    </tr>
    <tr>
        <td>
            Reset Fund
        </td>
        <td>
            <?= money($summary['advertising']['amount']*.10) ?>
        </td>
    </tr>
    <tr>
        <td>
            Prizes Paid
        </td>
        <td>
            <?= money(-$summary['admin_adjustment']['amount']) ?>
        </td>
    </tr>

    <tr>
        <td>NET PROFIT</td>
        <td> <?= money($net) ?></td>
    </tr>
    <tr>
        <td>Dividend Per Share</td>
        <td><?= money($perShare) ?></td>
    </tr>
    <tr>
        <td>Your Shares (<?= $shares/intval(TOTAL_SHARES)*100 ?>%)</td>
        <td><?= number_format($shares) ?></td>
    </tr>
    <tr>
        <td><b>Your Dividend</b></td>
        <td><b><?=money($dividend)?></b></td>
    </tr>
    <tr>
        <td>Transaction ID:</td>
        <td><?= $transactionId ?></td>
    </tr>
</table>

----<br/>
<br/>
If you have any questions, please open a support ticket in your <?=anchor(site_url('support'), 'Back Office')?>.
<br/>
<br/>
Thank you for your continued support.


