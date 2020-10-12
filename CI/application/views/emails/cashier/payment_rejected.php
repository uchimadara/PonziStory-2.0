<?
$payment = (object)$payment;
$payer = (object)$payer;
$payee = (object)$payee;
?>
Hello <?= $payer->username ?>!<br/>
<br/>
A donation you submitted to <b><?=$payee->username?></b> has been rejected.<br/>
<br/>
----<br/>
<br/>
<table>
    <tr>
        <td>Submitted:</td>
        <td><?= date(DEFAULT_DATETIME_FORMAT, $payment->created) ?></td>
    </tr>
    <tr>
        <td>Rejected:</td>
        <td><?= date(DEFAULT_DATETIME_FORMAT, $payment->rejected) ?></td>
    </tr>
    <tr>
        <td>Donation payment method:</td>
        <td><?= $payment->method_name ?></td>
    </tr>
    <tr>
        <td>Transaction ID:</td>
        <td><?= $payment->transaction_id ?> </td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><?= $payment->title ?></td>
    </tr>
    <tr>
        <td>Amount:</td>
        <td><?= money($payment->amount) ?></td>
    </tr>
    <? if ($payment->currency != 'USD') { ?>
        <tr>
            <td>Currency Sent:</td>
            <td><?= roundDown($payment->currency_amount, 2) ?> <?= $payment->currency ?></td>
        </tr>

    <? } ?>
</table>
<br/>
---
<br/>
<br/>
Please submit a support ticket if you think this is an error.
<br/>
<br/>
Thank you.
<br/>