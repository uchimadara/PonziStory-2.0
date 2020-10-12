<table class="commissions">
    <tr>
        <th class="W20">Last Active</th>
        <th class="W20">Username</th>
    </tr>
<? foreach ($data as $d): ?>
    <tr>
        <td><b><?=date("d-m-Y / H:i:s", $d->last_activity)?></b></td>
        <td><b><?=$d->username?></b></td>
    </tr>
<? endforeach; ?>
</table>

<? if ($hasPages): ?>
<span class="paging"><strong>Page:</strong> <?=$paging?></span>
<? endif; ?>