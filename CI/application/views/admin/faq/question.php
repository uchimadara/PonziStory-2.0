<h2>FAQ Question</h2>
<div class="tile p-10">
    <form method="post">
        <? if ($message): ?>
            <div class="session_message error"><?= $message ?></div>
        <? endif; ?>
            <div class="form-group"><label for="category_id">Category</label>
                <?= form_dropdown('category_id', $categories, set_value('category_id', $questionData ? $questionData->category_id : ''), 'class="form-control"') ?>
            </div>
            <div class="form-group"><label for="">Question</label>
                <?= form_input('question', set_value('question', $questionData ? $questionData->question : ''), 'class="form-control"') ?>
            </div>
            <div class="form-group"><label for="">Answer</label>
                <?= form_textarea('answer', set_value('answer', $questionData ? $questionData->answer : ''), 'class="form-control htmlEdit"') ?>
            </div>
            <div class="formBottom">
                <?= form_submit('submit', $questionData ? 'Edit' : 'Add', 'class="btn"') ?>
                <a href="<?= SITE_ADDRESS ?>adminpanel/faq" class="btn">Cancel</a>
            </div>
    </form>
</div>

