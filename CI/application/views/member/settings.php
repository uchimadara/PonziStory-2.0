<div class="col-md-8">
    <div class="tile">
        <h2 class="tile-title">Settings</h2>

        <? foreach ($settings as $s) { ?>
            <div class="settingLabel">
                <?= $s['description'] ?>
            </div>
            <div class="make-switch switch-small right settingSwitch">
                <input data-url="<?= SITE_ADDRESS ?>member/setting/<?= $s['name'] ?>/" type="checkbox" <?= ($s['value'] == 1) ? 'checked="checked"' : '' ?> />
            </div>
            <div class="clear"></div>
        <? } ?>
    </div>
</div>
<? if ($ajax) { ?>
    <script>
        $('.settingSwitch').bootstrapSwitch().on('switch-change', function (e, data) {
            var $el = $(data.el),
                    val = data.value,
                    setting = $el.attr('data-url');

            if (val) val = '1';
            else val = '0';

            $.get(setting + val, function (data) {
            });

        });
        $('.tip').tooltipsy();

    </script>
<? } ?>
