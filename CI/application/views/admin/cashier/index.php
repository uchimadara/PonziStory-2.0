<h1 class="yellow">Cashier Transactions</h1>
<div class="col-md-6">
    <!-- Main Chart -->
    <div class="tile">
        <h2 class="tile-title">All Time Statistics</h2>

        <table class="table">
        <? foreach ($alltimeStats as $label => $val) { ?>
            <tr>
                <td><?=$label?></td>
                <td><?= isset($val['count']) ? number_format($val['count']) : '--' ?></td>
                <td><?= money($val['amount']) ?></td>
            </tr>
        <? } ?>
            <tr>
                <td colspan="2">Transaction Costs</td>
                <td><?=money($overhead->costs)?></td>
            </tr>
            <tr>
                <td colspan="2">Transaction Fees</td>
                <td><?= money($overhead->fees) ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="col-md-6">
    <!-- Main Chart -->
    <div class="getList" data-url="<?=SITE_ADDRESS?>adminpanel/cashier/summary/<?=$begin?>" id="cashierSummary">
        <span class="loading"></span>
    </div>
</div>
<div class="col-lg-12">
    <h2 class="m-10">Transaction Logs</h2>

    <div class="tab-container tile">
        <ul class="nav nav-tabs">
            <? $active = "class='active'";
               foreach ($tabs as $tabId => $t) { ?>
                <li <?=$active?>>
                    <a href="#tab<?=$tabId?>" class="tab-ajax" data-url="<?= $t['url']?>"><?=$t['title']?></a>
                </li>

            <? $active = ''; } ?>

        </ul>

        <div class="tab-content tile tile-dark" style="min-height: 400px;">
            <? $active = "active";
            foreach ($tabs as $tabId => $t) {
            ?>
            <div class="tab-pane <?=$active?>" id="tab<?=$tabId?>">
               <span class="loading"></span>
            </div>
                <? $active = '';
            } ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
