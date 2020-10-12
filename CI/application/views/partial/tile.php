
<div class="col-<?=$tile['size']?>-<?=$tile['cols']?>">
    <div class="tile">
        <h2 class="tile-title"><?=$tile['title']?></h2>

        <? if (isset($tile['buttons']) && !empty($tile['buttons'])) { ?>
        <div class="tile-config dropdown">
            <a data-toggle="dropdown" href="" class="tile-menu"></a>

            <ul class="dropdown-menu animated pull-right text-right">
                <? foreach ($tile['buttons'] as $b) { ?>
                    <li><a href="<?= $b['url'] ?>" <? if (isset($b['extra'])) echo $b['extra']; ?>><?=$b['title']?></a></li>
                <? } ?>
            </ul>
        </div>
        <? } ?>


        <div id="<?=$tile['id']?>">
            <?=$tile['body']?>
        </div>

    </div>
</div>
