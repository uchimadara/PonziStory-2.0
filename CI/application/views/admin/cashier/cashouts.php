<? //$methodsMenu?>
<? $statusCodes = array('pending', 'ok', 'cancelled', 'rejected');
?>
<h1 class="yellow">Cashouts</h1>
<div class="col-lg-12">
    <div class="tab-container tile">
        <ul class="nav nav-tabs">
            <? foreach ($statusCodes as $s) { ?>
                <? if ($status == $s) { ?>
                    <li class="active"><a href="#tab-1"><?= ucwords($s) ?></a></li>

                <? } else { ?>
                    <li><a href="<?= "$codeUrl/$s" ?>"><?= ucwords($s) ?></a></li>

                <? } ?>

            <? } ?>
        </ul>

        <div id="tab-1">
            <?= isset($cashouts) ? $cashouts : "<div class='p-10'>No $status cashouts</div>" ?>
        </div>
    </div>
</div>

