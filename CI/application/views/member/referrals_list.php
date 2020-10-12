<div class="col-lg-12">
<h1 class="clearFix">Referrals List
    <? if (ENABLE_INVITES && $userData->account_level > 0) { ?>
        &nbsp;&nbsp;
        <a class="btn btn-alt popup" title="Invite New Member" href="<?= site_url('member/form/invite') ?>">
            Invite new member
        </a>
    <? } ?>
        <input type class="search" id="ref-search" size="30" maxlength="30" name="refSearch" placeholder="search referrals..."/>

</h1>
    <div class="clear"></div>

    <? if (!empty($invites)) { ?>
    <div class="tile">
        <h2 class="tile-title">Active Invitations </h2>

        <table class="rwd-table">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Sponsor</th>
                <th>Date sent</th>
                <th>Expires</th>
            </tr>
            <? foreach ($invites as $i) { ?>
            <tr>
                <td data-th="First Name"><?=$i->first_name?></td>
                <td data-th="Last Name" ><?= $i->last_name ?></td>
                <td data-th="Email"><?= $i->email ?></td>
                <td data-th="Sponsor"><?= (isset($i->sponsor)) ? $i->sponsor : $userData->username ?></td>
                <td data-th="Date sent"><?= date(DEFAULT_DATETIME_FORMAT, $i->date) ?></td>
                <td data-th="Expires"><?= displayCountDown($i->date + (INVITE_EXPIRATION * CACHE_ONE_HOUR) - now(), TRUE, TRUE) ?></td>
            </tr>
            <? } ?>
        </table>
    </div>
    <? } ?>

    <? if ($userData->account_level > 0) { ?>
        <div class="memberRefLink">
            <b>Your referral link:</b> <?= $refUrl ?>
        </div>
    <? } ?>

        <div class="tab-container">
            <ul class="nav tab nav-tabs">
                <? for ($i = 1; $i <= CYCLER_DEPTH && isset($refCount[$i]); $i++) { ?>

                    <li>
                        <a href="#Level<?= $i ?>" class="tab-ajax" data-url="<?= site_url('ajax/referrals/get_level/'.$i) ?>">Level <?= $i ?></a>
                    </li>
                <? } ?>

            </ul>

            <div class="tab-content" style="min-height: 400px;">
                <? for ($i = 1; $i <= CYCLER_DEPTH && isset($refCount[$i]); $i++) { ?>
                    <div class="tab-pane p-20" id="Level<?= $i ?>">
                        <span class="loading"></span>
                    </div>
                <? } ?>
            </div>

        </div>
</div>
