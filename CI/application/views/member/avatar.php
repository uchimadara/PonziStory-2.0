<div class="col-lg-6">
    <div class="tile">
        <h2 class="tile-title">Avatar</h2>

        <div class="p-10">
            <div id="memberAvatar">

                <div id="avatarDisplay">
                        <span id="avatarContent">
                            <img src="<?= avatar($userData->avatar) ?>"/>
                    </span><a href="<?=site_url('member/edit_avatar')?>" class="replace btn btn-alt" data-div="page-content">Change Avatar</a>
                </div>
            </div>


        </div>
    </div>
    <div class="tile">
        <h2 class="tile-title">Profile Options</h2>
        <div class="p-10">
            <p>Control what you share with others.</p>
            <div class="settingLabel">
                <a class="tooltips" data-toggle="tooltip" data-placement="left" title="" data-original-title="Show my email">
                    <i class="fa fa-question-circle"></i></a>
                Email Address
            </div>
            <div class="make-switch switch-small right settingSwitch">
                <input data-url="<?= SITE_ADDRESS ?>member/setting/show_email/" type="checkbox" <?= (isset($userSettings->show_email) && $userSettings->show_email == '0') ? '' : 'checked="checked"' ?> />
            </div>
            <div class="clear"></div>
            <div class="settingLabel">
                <a class="tooltips" data-toggle="tooltip" data-placement="left" title="" data-original-title="Show my avatar"><i class="fa fa-question-circle"></i></a>
                Avatar
            </div>
            <div class="make-switch switch-small right settingSwitch">
                <input data-url="<?= SITE_ADDRESS ?>member/setting/show_avatar/" type="checkbox" <?= (isset($userSettings->show_avatar) && $userSettings->show_avatar == '0') ? '' : 'checked="checked"' ?> />
            </div>
            <div class="clear"></div>

            <div class="settingLabel">
                <a class="tooltips" data-toggle="tooltip" data-placement="left" title="" data-original-title="Show my social newtwork links"><i class="fa fa-question-circle"></i></a>
                Social Links
            </div>
            <div class="make-switch switch-small right settingSwitch">
                <input data-url="<?= SITE_ADDRESS ?>member/setting/show_social/" type="checkbox" <?= (isset($userSettings->show_social) && $userSettings->show_social == '0') ? '' : 'checked="checked"' ?> />
            </div>
            <div class="clear"></div>
            <div class="settingLabel">
                <a class="tooltips" data-toggle="tooltip" data-placement="left" title="" data-original-title="Show my skype ID"><i class="fa fa-question-circle"></i></a>
                Skype ID
            </div>
            <div class="make-switch switch-small right settingSwitch">
                <input data-url="<?= SITE_ADDRESS ?>member/setting/show_skype/" type="checkbox" <?= (isset($userSettings->show_skype) && $userSettings->show_skype == '0') ? '' : 'checked="checked"' ?> />
            </div>
            <div class="clear"></div>
            <div class="settingLabel">
                <a class="tooltips" data-toggle="tooltip" data-placement="left" title="" data-original-title="Show my phone number"><i class="fa fa-question-circle"></i></a>
                Phone Number
            </div>
            <div class="make-switch switch-small right settingSwitch">
                <input data-url="<?= SITE_ADDRESS ?>member/setting/show_phone/" type="checkbox" <?= (isset($userSettings->show_phone) && $userSettings->show_phone == '0') ? '' : 'checked="checked"' ?> />
            </div>
            <div class="clear"></div>
        </div>

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

        $('a.tooltips').tooltip();

    </script>
<? } ?>
