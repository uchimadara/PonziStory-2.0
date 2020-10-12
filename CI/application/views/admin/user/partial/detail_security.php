<h2>Change Password for <?= $user->username ?></h2>
<div class="formContainer">
    <? if ($error): ?>
    <p class="error"><?= $error ?></p>
    <? endif; ?>
    <?= form_open(site_url('adminpanel/users/change_password/' . $user->id), array('method' => 'post', 'class' => 'frm_ajax')); ?>
    <div class="form-group">
        <label for="password">New Password</label></td>
        <input class="form-control" type="text" id="password" name="password" value=""/>
    </div>
    <div class="form-group">
        <input type="checkbox" name="send_email" value="1"/> Send Email to User
    </div>
    <div class="formBottom">
        <input class="btn btn-alt" type="submit" name="submit" value="Save" />
    </div>
</form>
</div>
<h2>Unlock IP</h2>

<div style="width: 300px">

    <div class="settingLabel">
        <a class="tooltips" data-toggle="tooltip" data-placement="left" title="" data-original-title="When set to ON you may only log in from the current IP address. Any attempts to log in from a different IP address will fail."><i class="fa fa-question-circle"></i></a>
        Lock my IP address
    </div>
    <div class="make-switch switch-small right settingSwitch">
        <input data-url="<?= SITE_ADDRESS ?>adminpanel/users/setting/<?= $user->id ?>/lock_my_ip/" type="checkbox" <?= (isset($currentUserSettings->lock_my_ip) && $currentUserSettings->lock_my_ip == '1') ? 'checked="checked"' : '' ?> />
    </div>
</div>
<div class="clear"></div>
<div class="formContainer">

    <p>Last 5 different login IP's</p>
    <table class="table">
        <?php foreach ($logsIPs as $prof) : ?>
            <tr>
                <td><?= date('d-M-Y H:i', $prof->date) ?></td>
                <td><?= long2ip($prof->user_ip) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="clear"></div>
<script>
    $(".settingSwitch").bootstrapSwitch();
    $('.settingSwitch').on('switch-change', function (e, data) {
        var $el = $(data.el),
                val = data.value,
                setting = $el.attr('data-url');

        if (val)
            val = '1';
        else
            val = '0';

        $.get(setting + val, function (data) {
        });

    });
</script>
