<?
$payment = (object) $payment;
?>
Hello <?= $payment->payer ?>!<br/>
<br/>
You have been Merged on tradermoni<br/>
----
<br/>
<table>
    <tr>
        <td>Submitted:</td>
        <td><?= date(DEFAULT_DATETIME_FORMAT, $payment->created) ?></td>
    </tr>
    <tr>
        <td>Partner:</td>
        <td><?= $payment->payer_name ?> (<?=$payment->payer?>)</td>
    </tr>
   
    <tr>
        <td>Description:</td>
        <td>Merger</td>
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
