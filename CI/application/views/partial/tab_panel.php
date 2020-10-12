
<div class="col-lg-12">
    <h2 class="m-10"><?=$heading?></h2>

    <div class="tab-container tile">
        <ul class="nav nav-tabs">
            <? $active = "class='active'";
               foreach ($tabs as $tabId => $t) { ?>
                <li <?=$active?>>
                    <a href="#tab<?=$tabId?>" class="tab-ajax" data-url="<?= $t['url']?>"><?=$t['title']?></a>
                </li>

            <? $active = ''; } ?>

        </ul>

        <div class="tab-content tile tile-dark" style="min-height: 400px;">
            <? $active = "active";
            foreach ($tabs as $tabId => $t) {
            ?>
            <div class="tab-pane <?=$active?> getList" id="tab<?=$tabId?>">
               <span class="loading"></span>
            </div>
                <? $active = '';
            } ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
