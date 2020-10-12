<div class="container">
    <table class="fs14">
        <tr>
            <td><strong>Name:</strong> </td>
            <td><?=$ad->name?></td>
        </tr>
        <tr>
            <td><strong>URL:</strong><br/></td>
            <td><?=anchor($ad->target_url, null)?><br/></td>
        </tr>
        <tr>
            <td valign="top" colspan="2" style="border:1px solid green; padding:5px;">
                <?=$ad->line1?><br/>
                <?= $ad->line2 ?><br/>
                <?= $ad->line3 ?><br/>
            </td>
        </tr>
    </table>
    <h2>Clicks &amp; Views</h2>
    <div>This displays a breakdown of exactly who viewed and clicked on this ad.</div>
    <table class="table table-striped">
        <tr>
            <th class="W20">User Value Level</th>
            <th class="W20">Invest Min.</th>
            <th class="W20">Impressions</th>
            <th class="W20">Clicks</th>
        </tr>
        <tr>
            <td>Guests</td>
            <td>--</td>
            <td><?=isset($impressions[0]) && $impressions[0] > -1 ? number_format($impressions[0]) : '0'?></td>
            <td><?=isset($clicks[0]) && $clicks[0] > -1 ? number_format($clicks[0]) : '0'?></td>
        </tr>
        <? foreach ($impressionValues as $level => $value) { ?>

            <tr>
                <td><?=$level?></td>
                <td><?= money($value) ?></td>
                <td><?= $impressions[$level] > -1 ? number_format($impressions[$level]) : '--' ?></td>
                <td><?= $clicks[$level] > -1 ? number_format($clicks[$level]) : '--' ?></td>
            </tr>

        <? } ?>
    </table>
</div>

