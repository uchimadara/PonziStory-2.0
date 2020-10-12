<div class="col-lg-12">
<h1>Referrals
    <? if (ENABLE_INVITES && $userData->account_level > 0) { ?>
        &nbsp;&nbsp;
        <a class="btn btn-alt popup" title="Invite New Member" href="<?= site_url('member/form/invite') ?>">
            Invite new member (Email)
        </a>

    <? if (ENABLE_INVITES && $userData->account_level == 111) { ?>
        <!-- Button trigger modal -->
        <button class="btn btn-alt btn-md" data-toggle="modal" data-target="#myModalHorizontal">
            INVITE NEW MEMBER (SMS)
        </button>

    <? } ?>
    <? } ?>
        <input type class="search" id="ref-search" size="30" maxlength="30" name="refSearch" placeholder="search referrals..."/>
</h1>
    <div class="caption">
        <a href="/back_office/referrals_list" class="btn btn-alt">List View</a> &nbsp;&nbsp;
        <a href="/back_office/referrals_expiring" class="btn btn-alt">Expiring</a>

    </div>
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

    <div class="tile">
        <h2 class="tile-title" id="refListHeader">Total active on all levels: <?= $totalReferrals ?> &nbsp;&nbsp; Total Received:  <?= money($totalEarned) ?></h2>

            <div class="p-10 getList" id="refList" data-url="<?=SITE_ADDRESS?>ajax/referrals/get_list/<?=$userData->id?>/1">
                <div class="loading"></div>
            </div>
    </div>
</div>

<style>
    .modal-body .form-horizontal .col-sm-2,
    .modal-body .form-horizontal .col-sm-10 {
        width: 100%
    }

    .modal-body .form-horizontal .control-label {
        text-align: left;
    }
    .modal-body .form-horizontal .col-sm-offset-2 {
        margin-left: 15px;
    }
</style>



<!-- Modal -->
<div class="modal fade" id="myModalHorizontal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Invite new member (SMS)
                </h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <span style="color: red">
                    1. You can only send SMS to one person at a time
                </span>
                <form class="form-horizontal" role="form" action="<?= site_url('member/refSms') ?>" method="post">

                    <div class="form-group">
                        <label  class="col-sm-2 control-label"
                                for="phone">Phone</label>
                        <div class="col-sm-6">
                            <input type="tel" class="form-control" name="phone" required="required" maxlength="11" onkeypress="return isNumberKey(event)" id="phone" placeholder="0801234567"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10">
                        <textarea class="form-control" maxlength="160" id="message" name="message"  rows="3">
                       Good day.Hv u heard of tradermoni? Discover how u can fund ur business. Register,Connect with people while u raise funds.click <?= $refUrl ?>
                    </textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-alt">Submit</button>
                        </div>
                    </div>
                </form>






            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                    Close
                </button>

            </div>
        </div>
    </div>
</div>
