<table class="commissions commissions20W">
    <caption class="topReferrer">Top Referrers</caption>
    <tr>
        <th>Username</th>
        <th><?=($sorting == 'count_l1') ? 'Level 1 Referrals' : anchor('/member/ref_top_referrer/count_l1', 'Level 1 Referrals', 'class="click_sort"')?></th>
        <th><?=($sorting == 'count_l2') ? 'Level 2 Referrals' : anchor('/member/ref_top_referrer/count_l2', 'Level 2 Referrals', 'class="click_sort"')?></th>
        <th><?=($sorting == 'earnings') ? 'Total Commissions' : anchor('/member/ref_top_referrer/earnings', 'Total Commissions', 'class="click_sort"')?></th>
    </tr>
<? foreach ($data as $d): ?>
    <tr>
        <td><?=$d->username?></td>
        <td><?=$d->count_l1 ? '<b>' . intval($d->count_l1) . '</b>' : intval($d->count_l1)?></td>
        <td><?=$d->count_l2 ? '<b>' . intval($d->count_l2) . '</b>' : intval($d->count_l2)?></td>
        <td><?=money($d->earnings)?></td>
    </tr>
<? endforeach; ?>
</table>
<? if ($hasPages): ?>
<span class="paging"><strong>Page:</strong> <?=$paging?></span>
<? endif; ?>