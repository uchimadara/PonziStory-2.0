<h2>FAQ Category</h2>
<div class="tile p-10">
    <form method="post">
        <? if ($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <? endif; ?>
            <div class="form-group"><label for="name">Name</label>
                <?= form_input('name', set_value('name', $categoryData ? $categoryData->name : ''), 'class="form-control"') ?>
            </div>
            <div class="form-group"><label for="description">Description</label>
                <?= form_input('description', set_value('description', $categoryData ? $categoryData->description : ''), 'class="form-control"') ?>
            </div>
            <div class="form-group">
                <label for="icon">Icon (max 120 x 40px)</label>
                <?= form_input('icon', set_value('icon', $categoryData ? $categoryData->icon : ''), 'class="form-control"') ?>
            </div>
            <div class="formBottom">
                <?= form_submit('submit', $categoryData ? 'Edit' : 'Add', 'class="btn"') ?>
                <a href="<?=SITE_ADDRESS?>adminpanel/faq" class="btn">Cancel</a>
            </div>
    </form>
</div>