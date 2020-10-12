<?
$payment = (object) $payment;
?>
Hello <?= $payment->payee ?>!<br/>
<br/>
A donation has been sent to you as follows:<br/>
----
<br/>
<table>
    <tr>
        <td>Submitted:</td>
        <td><?= date(DEFAULT_DATETIME_FORMAT, $payment->created) ?></td>
    </tr>
    <tr>
        <td>Submitted By:</td>
        <td><?= $payment->payer_name ?> (<?=$payment->payer?>)</td>
    </tr>
    <tr>
        <td>Transaction ID:</td>
        <td><span style="font-size:10px"><?= $payment->transaction_id ?> </span></td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><?= $payment->title ?></td>
    </tr>

    <tr>
        <td>Amount:</td>
        <td><?= money($payment->amount) ?></td>
    </tr>
</table>
<br/>

<p>Login to your dashboard, call your partner to confirm</p>

---
<br/>
Thank you for your continued support.
<br/>
