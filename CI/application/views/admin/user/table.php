<? if (count ($users)): ?>
<table>
    <tr>
        <td><strong>ID</strong></td>
        <td><strong>Username</strong></td>
        <td><strong>Email</strong></td>
        <td><strong>Country</strong></td>
        <td><strong>History</strong></td>
    </tr>
    <? foreach ($users as $user): ?>
    <tr>
        <td><?=$user->id?></td>
        <td><?=anchor('adminpanel/users/detail/' . $user->id, $user->username)?></td>
        <td><?=$user->email?></td>
        <td><?=$user->country?></td>
        <td><?=anchor('adminpanel/users/history/' . $user->id . '/all', 'view')?></td>
    </tr>
    <? endforeach; ?>
</table>

    <? if ($hasPages): ?>
<div class="paging">
    <?=$paging?>
</div>
    <? endif; ?>
<? else: ?>
    Sorry no users found with this filter
<? endif; ?>