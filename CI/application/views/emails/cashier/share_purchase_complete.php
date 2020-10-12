Hello <?= $username ?>!<br/>
<br/>
Your share purchase has been completed as follows:<br/>
<br/>
----<br/>
<br/>
<table>
    <tr>
        <td>Order ID:</td>
        <td><?= $transaction_id ?></td>
    </tr>
    <tr>
        <td>Total Shares:</td>
        <td><?= number_format($shares)?></td>
    </tr>
</table>
<br/>
---
<br/><br/>
If you have any questions, please open a support ticket
<br/>
<?=anchor(site_url('support'), 'Back Office')?>.
<br/>
<br/>
Thank you for your continued support.