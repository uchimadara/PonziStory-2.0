<div class="mainRound">
    <div class="heading">
        <div class="headerControl"></div>
        <div class="floatLeft"><?= $widget['title'] ?></div>
        <? if (!empty($widget['buttons'])) { ?>
        <span class="headerButton">
            <? foreach ($widget['buttons'] as $button) {
                echo anchor(site_url($button['uri']), $button['title'], $button['extra']);
            } ?>
        </span>
        <? } ?>
        <div class="clear"></div>
    </div>
    <div class="body pageable sortable" id="<?=$widget['id']?>">
        <?=$widget['content']?>
    </div>
</div>
