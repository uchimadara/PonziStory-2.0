<? if ($summary && !empty($summary)) { ?>
    <table class="table" style="font-size: 18px">
        <tr>
            <th>Level</th>
            <th>Donation</th>
            <th class="right">Max. Referrals</th>
            <th class="right">Referrals</th>
            <th class="right">Received</th>
            <th class="right">Potential</th>
        </tr>
    <?
    $refs = $potential = $total = $maxMoney = $maxRefs = 0;
    foreach ($summary as $level => $r) {
        $total += $r->earning;
        $refs += $r->referrals;
        $potential += $r->price * $r->referrals;
        $maxMoney += $r->price*pow(CYCLER_WIDTH, $level);
        $maxRefs += pow(CYCLER_WIDTH, $level);
    ?>
        <tr>
            <td data-th="Level">
                <?= $level ?>
            </td>
            <td data-th="Price">
                <?= money($r->price) ?>
            </td>
            <td data-th="Max. Referrals" class="right">
                <?= number_format(pow(CYCLER_WIDTH,$level)) ?>
                (<?=money($r->price * pow(CYCLER_WIDTH, $level))?>)
            </td>
            <td data-th="Referrals" class="right">
                <a href="/back_office/referrals_list#Level<?=$level?>"><?= number_format($r->referrals) ?></a>
            </td>
            <td data-th="Received" class="right">
                <?= money($r->earning) ?>
            </td>
            <td data-th="Potential" class="right">
                <?= money($r->price*$r->referrals) ?>
            </td>
        </tr>

    <? } ?>
        <tr>
            <td data-th="Sum"class="listTotal">Total</td>
            <td data-th="" class="listTotal">&nbsp;</td>
            <td data-th="" class="listTotal right"><?=$maxRefs?> (<?=money($maxMoney)?>)</td>
            <td data-th="Referrals" class="listTotal right"><?=number_format($refs)?></td>
            <td data-th="Received" class="listTotal right"><?=money($total)?></td>
            <td data-th="Potential" class="listTotal right"><?= money($potential) ?></td>
        </tr>
</table>

<? } else { ?>
        No referrals. See our <a href="<?=site_url('back_office/promotion')?>">marketing materials</a>.
<? } ?>
