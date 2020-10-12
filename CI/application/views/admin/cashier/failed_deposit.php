<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Failed Deposit ID: <?=$deposit->id?></h2>
        <table class="table">
            <? foreach ($deposit->fields as $k => $v) { ?>
            <tr><td><?=$k?></td><td><?=$v?></td></tr>
            <?} ?>
        </table>
    </div>
</div>