<?php $startTab = 'form'; ?>
<h1 class="yellow"><?= $page_title ?></h1>
<a href="<?=SITE_ADDRESS?>adminpanel/users/delete/<?=$user->id?>" class="btn btn-alt confirm">Delete Account</a>
<div class="tab-container tile">
    <ul class="nav tabAjax nav-tabs">
        <?php foreach ($tabs as $id => $name) { ?>                
            <?php if ($id == $startTab) { ?>
                <li class="active"><a href="#tab<?=$startTab?>"><?= ucwords($name) ?></a></li>
            <?php } else { ?>
                <li><a href="#tab<?= $id ?>"><?= ucwords($name) ?></a></li>
            <?php } ?>
        <?php } ?>
    </ul>
    <div class="tab-content" data-url="<?= site_url('adminpanel/users/detail/' . $user->id) ?>">
        <?php foreach ($tabs as $id => $name) { ?>                
            <?php if ($id == $startTab) { ?>
                <div class="tab-pane active" id="tab<?=$startTab?>">
                    <div class="loading"></div>
                </div>
            <?php } else { ?>
                <div class="tab-pane" id="tab<?= $id ?>">
                    <div class="loading"></div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<script>
    var startTab = '<?=$startTab?>';
</script>
    