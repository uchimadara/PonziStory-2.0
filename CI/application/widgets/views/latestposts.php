        <div class="latestPosts">

            <div>
                <span class="fpUser h">Who</span>
                <span class="fpTopic h">What</span>
                <span class="fpDate h">When</span>
                <span class="fpCategory h">Where</span>
            </div>

            <? foreach ($latestPosts as $idx => $post): ?>
                <div>
                    <span class="fpUser"><?= $post->username?></span>
                               <span class="fpTopic">
                        <a href="<?= site_url('forum/'.$post->cat_slug.'/'.$post->topic_slug.'/latest') ?>" title="view topic" <?= ($userId > 0 && (!$post->read_date || ($post->message_date > $post->read_date))) ? ' class="unread"' : '' ?>><?= $post->topic_name ?></a>
                    </span>
                    <span class="fpDate"><?= elapsedTime($post->message_date) ?> ago</span>
                    <span class="fpCategory">
                        <?= anchor('/forum/'.$post->cat_slug, $post->cat_name, 'title="view category"') ?>
                    </span>
                </div>
                <?
            endforeach;
            ?>
        </div>
        <div class="clear"></div>

