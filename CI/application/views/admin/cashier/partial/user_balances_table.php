<p>Users with balance in <?=$code?>: <?=$count?></p>
<table>
    <tr>
        <th>Username</th>
        <th>Balance</th>
    </tr>
<? foreach ($balances as $bal): ?>
    <tr>
        <td><?=anchor('/adminpanel/users/detail/' . $bal->id, $bal->username)?></td>
        <td><?=anchor('/adminpanel/users/adjust/' . $bal->id . '/' . $code, $bal->balance)?></td>
    </tr>
<? endforeach; ?>
</table>

<? if ($balance_hasPages): ?>
<div class="paging" align="center">
    <?=$balance_paging?>
</div>
<? endif; ?>