
<?if ($payee_user_id == $userData->id) {  //var_dump($username)?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="tile">

            <h2 class="tile-title">Awaiting Confirmation</h2>
            <table class="rwd-table">
                <tr>
                    <th>Name</th>
                    <th>Began</th>
                    <th>Last Paid</th>
                    <th>Due Next</th>
                    <th>Time Remaining</th>
                    <th></th>
                </tr>
                <? foreach ($expires as $level => $m) { ?>
                    <tr>
                        <td data-th="Level"><?=$memberships[$level]->title?></td>
                        <td data-th="Began"><?= date(DEFAULT_DATE_FORMAT, $m->start) ?></td>
                        <td data-th="Last Paid"><?= date(DEFAULT_DATE_FORMAT, $m->lastPaid)?></td>
                        <td data-th="Due Next"><?= date(DEFAULT_DATETIME_FORMAT, $m->expires) ?></td>
                        <td data-th="Time Remaining">
                            <? if ($m->expires < now()) { ?>
                                <span class="m-l-10 red">Arrears</span>
                            <? } else { ?>
                                <?= displayCountDown($m->expires - now(), TRUE, TRUE) ?>
                            <? } ?>
                        </td>
                        <td>
                            <? if ($m->expires - now() < (CACHE_ONE_DAY*5)) { ?>
                                <a href="/back_office/pay_subscription/<?= $level ?>" class="btn btn-alt">Pay Now</a>
                            <? } ?>
                        </td>
                    </tr>
                <? } ?>
            </table>

            <b>Note: </b> You can only receive donations from the levels you are qualified to receive.
            You will not receive any donations from levels in arrears.
        </div>
    </div>
<? } ?>