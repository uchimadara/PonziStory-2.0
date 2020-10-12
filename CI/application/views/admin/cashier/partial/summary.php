<div class="tile" id="cashierSummary">
    <h2 class="tile-title">
        <div class="pull-left" style="width:20%">
            <? if ($begin > strtotime(date('2014-08-01 00:00:00'))) { ?>
                <a href="<?= SITE_ADDRESS ?>adminpanel/cashier/summary/<?= $begin - CACHE_ONE_DAY ?>" class="btn replace" data-div="cashierSummary">
                    << Prev
                </a>
            <? } ?>

        </div>
        <div class="pull-left center" style="width:50%; padding-top:8px;">
        <?=date(DEFAULT_DATE_FORMAT, $begin)?> Statistics
        </div>
        <div class="pull-right">
        <? if ($begin < strtotime(date('Y-m-d 00:00:00'))) { ?>
            <a href="<?= SITE_ADDRESS ?>adminpanel/cashier/summary/<?= $begin + CACHE_ONE_DAY ?>" class="btn replace" data-div="cashierSummary">
                Next >>
            </a>
        <? } ?>
        </div>
    </h2>

    <table class="table">
        <? foreach ($todayStats as $label => $val) { ?>
            <tr>
                <td><?= $label ?></td>
                <td><?= isset($val['count']) ? number_format($val['count']) : '--' ?></td>
                <td><?= money($val['amount']) ?></td>
            </tr>
        <? } ?>
    </table>
</div>
