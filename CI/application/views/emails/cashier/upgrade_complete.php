Hello <?= $username ?>!<br/>
<br/>
Your <?=SITE_NAME?> membership upgrade has been completed as follows:<br/>
<br/>
----<br/>
<br/>
<table>
    <tr>
        <td>Order ID:</td>
        <td><?= $transaction_id ?></td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><?= $order->description ?></td>
    </tr>
    <tr>
        <td>Account Expires:</td>
        <td><?= ($user['account_expires']) ? date('d-M-Y', $user['account_expires']) : 'Never'?></td>
    </tr>
    <tr>
        <td>Ad Credits:</td>
        <td><?= $order->ad_credits ?></td>
    </tr>
    <tr>
        <td>Traffic Exchange Credits:</td>
        <td><?= $order->te_credits ?></td>
    </tr>
</table>
 <br/>

---
<br/>
You have a total of <?=$user['ad_credits']?> ad credits and <?=$user['te_credits']?> traffic exchange credits available.
<br/><br/>
If you have any questions, please open a support ticket
<br/>

<?=anchor(site_url('support'), 'Back Office')?>.
<br/>
<br/>

Thank you for your continued support.