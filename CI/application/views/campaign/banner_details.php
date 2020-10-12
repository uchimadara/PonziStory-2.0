<div id="adStat">
    <table class="fs14">
        <tr>
            <td><strong>Name:</strong> </td>
            <td><?=$ad->name?></td>
        </tr>
        <tr>
            <td><strong>URL:</strong><br/></td>
            <td><?=anchor($ad->target_url, NULL)?><br/></td>
        </tr>
        <tr>
            <td valign="top" colspan="2" style="padding:5px;">
                <img src="<?=$ad->image_url?>">
            </td>
        </tr>
    </table>
    <h2><?= number_format($stats['views']) ?> Views &amp; <?= number_format($stats['clicks']) ?> Clicks</h2>
        <div class="tile stat-chart">
            <div id="adChart" class="sparkline" data-url="<?= SITE_ADDRESS.$stats['url'] ?>"></div>
        </div>
</div>

<? if ($ajax) { ?>
    <script type="text/javascript">
        procAdStats();
    </script>
<? } ?>
