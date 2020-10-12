<h2>FAQ</h2>
<div class="tile p-10">
    <div class="admin_faq_formContainer">
        <? foreach ($categories as $idx => $c): ?>
            <div class="<?= $idx%2 ? 'odd' : 'even' ?>">
                <div class="category">
                    <h2><?= $c->icon ? '<img src="'.$c->icon.'" width="20" height="20" />' : '' ?>
                        <?= anchor(SITE_ADDRESS.'adminpanel/faq/category/'.$c->id, $c->name) ?>
                        <a href="<?= SITE_ADDRESS ?>adminpanel/faq/delete_category/<?= $c->id ?>" class="confirm">
                            <img src="https://tdm.nghelpers.com/mim/public_html/assets/images/icons/delete.png">
                        </a>
                    </h2>
                    <p><?= $c->description ?></p>
                </div>
                <? if (count($questions)): ?>
                    <ul>
                        <? foreach ($questions as $q): ?>
                            <? if ($q->category_id != $c->id) continue; ?>
                            <li><?= anchor(SITE_ADDRESS.'adminpanel/faq/question/'.$q->id, $q->question) ?>
                                <a href="<?=SITE_ADDRESS?>adminpanel/faq/delete_question/<?=$q->id?>" class="confirm">
                                    <img src="https://tdm.nghelpers.com/mim/public_html/assets/images/icons/delete.png">
                                </a>
                            </li>
                        <? endforeach; ?>
                    </ul>
                <? endif; ?>
            </div>
        <? endforeach; ?>
        <?= anchor('/adminpanel/faq/category', 'Add Category', 'class="btn"') ?> <?= anchor('/adminpanel/faq/question', 'Add Question', 'class="btn"') ?>
    </div>
</div>