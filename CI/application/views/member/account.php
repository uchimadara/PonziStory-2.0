<div class="col-md-6">
    <div class="tile">
        <h2 class="tile-title">Registration Details</h2>

        <table class="table">
            <tr>
                <td>Username</td>
                <td><span class="boldUpper"><?= $userData->username ?></span></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?= $userData->email ?>
                    <a class="modalPopup" data-target="#modal" href="<?= site_url('member/modal/change_email') ?>">  &nbsp;
                        <i class="fa fa-pencil-square-o"></i></a>
                </td>
            </tr>
            <tr>
                <td>Skype ID</td>
                <td>
                    <input type="text" name="skype_id" id="skype_id" class="form-control" style="float: left;width: 75%;" value="<?= isset($userSettings->skype_id) ? $userSettings->skype_id : '' ?>"/>

                    <a class="btn btn-alt btn-xs saveSetting m-t-5 m-l-5" data-input="skype_id" href="<?= SITE_ADDRESS ?>member/setting/skype_id/">Save</a>
                </td>
            </tr>
            <tr>
                <td>Joined</td>
                <td><?= date(DEFAULT_DATE_FORMAT, $userData->created_on) ?></td>
            </tr>
            <tr>
                <td>IP Address</td>
                <td><?= $userData->ip_address ?></td>
            </tr>

            <? if(REGISTER_FIELD_COUNTRY){ ?>
            <tr>
                <td>Country</td>
                <td><?= $userData->country->country_name ?>
                    <a class="modalPopup" data-target="#modal" href="<?= site_url('member/modal/change_country') ?>">
                        &nbsp;   <i class="fa fa-pencil-square-o"></i>
                    </a>
                </td>
            </tr>
            <? } ?>

            <? if(REGISTER_FIELD_NAMES){ ?>
            <tr>
                <td>Name</td>
                <td><?= $userData->first_name.' '.$userData->last_name ?>
                    <a class="modalPopup" data-target="#modal" href="<?= site_url('member/modal/change_names') ?>">  &nbsp;
                        <i class="fa fa-pencil-square-o"></i></a>
                </td>
            </tr>
            <? } ?>

            <? if(REGISTER_FIELD_ADDRESS){ ?>
            <tr>
                <td>Address</td>
                <td><?= $userData->address.'<br>'.$userData->postal_code.' '.$userData->city.'<br>'.$userData->state ?>
                    <a class="modalPopup" data-target="#modal" href="<?= site_url('member/modal/change_address') ?>">  &nbsp;
                        <i class="fa fa-pencil-square-o"></i></a>
                </td>
            </tr>
            <? } ?>

            <? if(REGISTER_FIELD_PHONE){ ?>
            <tr>
                <td>Phone</td>
                <td><?= $userData->phone ?>
                    <a class="modalPopup" data-target="#modal" href="<?= site_url('member/modal/change_phone') ?>">  &nbsp;
                        <i class="fa fa-pencil-square-o"></i></a>
                </td>
            </tr>
            <? } ?>

            <? if ($referrer) { ?>
                <tr>
                <td>Invited By</td>
                <td><span class="boldCap"><?= $referrer->username ?></span>
                    <a href="mailto:<?= $referrer->email ?>"><?= $referrer->email ?></a></td>
            </tr>
            <? } ?>
        </table>
        <h2 class="tile-title">Security</h2>
        <table class="table">
            <tr>
                <td>Account Password</td>
                <td><span>***************</span>
                    <a class="modalPopup" data-target="#modal" href="<?= site_url('member/modal/change_password') ?>">
                        &nbsp; <i class="fa fa-pencil-square-o"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Secret Question</td>
                <td><span><?= (empty($userData->secret_question)) ? '<a class="modalPopup red" data-target="#modal" href="' . site_url('member/modal/change_secret') . '">Set this up now!</a>' : $userData->secret_question ?></span>
                </td>
            </tr>
            <tr>
                <td>Secret Answer</td>
                <td><span>***************</span>
                    <? if (!empty($userData->secret_question)) { ?>

                    <a class="modalPopup" data-target="#modal" href="<?= site_url('member/modal/change_secret') ?>"> &nbsp;
                        <i class="fa fa-pencil-square-o"></i></a>
                    <? } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="settingLabel">
                        <a class="tooltips" data-toggle="tooltip" data-placement="left" title="" data-original-title="When set to ON you may only log in from the current IP address. Any attempts to log in from a different IP address will fail."><i class="fa fa-question-circle"></i></a>
                        Lock my IP address
                    </div>
                    <div class="make-switch switch-small right settingSwitch">
                        <input data-url="<?= SITE_ADDRESS ?>member/setting/lock_my_ip/" type="checkbox" <?= (isset($userSettings->lock_my_ip) && $userSettings->lock_my_ip == '1') ? 'checked="checked"' : '' ?> />
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">Last 5 different login IP's</td>
            </tr>
            <?php foreach ($account['logIPs'] as $prof) : ?>
                <tr>
                    <td><?= date('d-M-Y H:i', $prof->date) ?></td>
                    <td><?= long2ip($prof->user_ip) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

