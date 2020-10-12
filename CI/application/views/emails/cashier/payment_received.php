Hello <?= $username ?>!<br/>
<br/>
Your <?= SITE_NAME ?> payment has been received as follows:<br/>
<br/>
----<br/>
<br/>
<table>
    <tr>
        <td>Date:</td>
        <td><?= date(DEFAULT_DATETIME_FORMAT, $transaction->updated) ?></td>
    </tr>
    <tr>
        <td>Payment Method:</td>
        <td><?= $order->payment_method ?></td>
    </tr>
    <tr>
        <td><?= $order->payment_method ?> Account:</td>
        <td><?= $transaction->user_account ?></td>
    </tr>
    <tr>
        <td><?= $order->payment_method ?> Transaction ID:</td>
        <td><?= $transaction->reference ?></td>
    </tr>
    <tr>
        <td>Order Id:</td>
        <td><?= $transaction->id ?></td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><?= $order->description ?></td>
    </tr>
    <tr>
        <td>Amount:</td>
        <td><?= money($order->amount) ?></td>
    </tr>
    <tr>
        <td>Quantity:</td>
        <td><?= $order->qty ?></td>
    </tr>
    <tr>
        <td>Subtotal:</td>
        <td><?= money($order->amount * $order->qty) ?></td>
    </tr>
    <tr>
        <td>Fee:</td>
        <td><?= money($order->fee) ?></td>
    </tr>
    <tr>
        <td>Balance Applied:</td>
        <td><?= money($order->apply_balance) ?></td>
    </tr>
    <tr>
        <td>Order Total:</td>
        <td> <?= money($order->total) ?></td>
    </tr>
</table>
<br/>

---
<br/>
<br/>
You will receive another email when your order is completed.
If you have any questions, please open a support ticket in your <?= anchor(site_url('support'), 'Back Office') ?>.
<br/>
<br/>
Thank you for your continued support.
<br/>
