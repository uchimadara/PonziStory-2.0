Hello <?=$user['username']?>!<br/>
<br/>
<br/>
Your withdrawal request from <?=SITE_NAME?> has been paid!<br/>
<br/>
The details of the transaction are shown below:<br/>
<br/>
----<br/>
<table>
    <tr>
        <td>
            Cashout Id:
        </td>
        <td> <?= $trans->identifier ?></td>
    </tr>
    <tr>
        <td>Method: </td>
        <td><?= $this->picklist->select_value('payment_code_list', $trans->method) ?></td>
    </tr>
    <tr>
        <td>Amount:</td>
        <td> <?= money($trans->amount) ?></td>
    </tr>
    <tr>
        <td>Transaction Id: </td>
        <td><?= $trans->reference ?></td>
    </tr>
</table>
----

If you have any questions, reply to this email, or open a support ticket in your members area.

Thank you.