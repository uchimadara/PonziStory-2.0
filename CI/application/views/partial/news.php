<div class="overflow" style="height: 254px">
    <? if (empty($messages)) echo '<p class="m-10">Nothing new.</p>'; else { ?>
        <? foreach ($messages as $m) { ?>
            <div class="media">
                <div class="pull-left">
                    <? if (empty($m->avatar)) { ?>
                        <i class="fa fa-coffee" style="font-size: 15pt;color: #C6C6C6;margin-bottom: 5px;"></i>
                    <? } else { ?>
                        <img class="media-object pull-left" src="<?= avatar($m->avatar) ?>" width="30px" height="30px">
                    <? } ?>
                </div>
                <div class="media-body">
                    <small class="text-muted">
                        <?= $m->message_count?> new <?= pluralise('post', $m->message_count)?>
                        in
                        <a class="news-title fs13" href="<?= sprintf($url, $m->cat_slug, $m->topic_slug); ?>">
                            <?= $m->cat_name ?> / <?= $m->topic_name ?>
                        </a>
                        <?= elapsedTime($m->message_date, NULL, TRUE) ?> </small>
                    <br>
                </div>
            </div>
        <? } ?>
    <? } ?>
</div>