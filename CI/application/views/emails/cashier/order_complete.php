Hello <?= $username ?>!<br/>
<br/>
Your order has been completed as follows:<br/>
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
    <? if ($order->ad_credits > 0) { ?>

        <tr>
            <td>Text Ad Credits:</td>
            <td><?= number_format($order->ad_credits*$order->qty) ?></td>
        </tr>
    <? } ?>
    <? if ($order->te_credits > 0) { ?>

        <tr>
            <td>Traffic Exchange Credits:</td>
            <td><?= number_format($order->te_credits*$order->qty) ?></td>
        </tr>
    <? } ?>
    <? if ($order->banner_credits > 0) { ?>

        <tr>
            <td>Banner Ad Credits:</td>
            <td><?= number_format($order->banner_credits*$order->qty) ?></td>
        </tr>
    <? } ?>
    <? if ($tokens > 0) { ?>

        <tr>
            <td>Tokens Earned:</td>
            <td><?= number_format($tokens) ?></td>
        </tr>
    <? } ?>
</table>
<br/>
---
<br/>
<br/>
If you have any questions, please open a support ticket in your <?=anchor(site_url('support'), 'Back Office')?>.
<br/>
<br/>
Thank you for your continued support.