Hello <?= $payer ?>!<br/>
<br/>
A donation you submitted has been approved!<br/>
<br/>
----<br/>
<br/>
<table>
    <tr>
        <td>Submitted:</td>
        <td><?= date(DEFAULT_DATETIME_FORMAT, $created) ?></td>
    </tr>
    <tr>
        <td>Approved:</td>
        <td><?= date(DEFAULT_DATETIME_FORMAT, $approved) ?></td>
    </tr>
    <tr>
        <td>Donation payment method:</td>
        <td><?= $method_name ?></td>
    </tr>
    <tr>
        <td>Transaction ID:</td>
        <td><?= $transaction_id ?> </td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><?= $title ?></td>
    </tr>
    <tr>
        <td>Amount:</td>
        <td><?= money($amount) ?></td>
    </tr>
</table>
<br/>
---
<br/>
<br/>
Congratulations! You are now eligible to upgrade to the next level.
<br/>
<br/>
Thank you for your continued support.
<br/>