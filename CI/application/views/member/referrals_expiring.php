<div class="col-lg-12">
<h1>Referrals Expiring</h1>

    <div class="getList" data-url="/member/getList/referrals_expiring?user_id=<?=$userData->id?>" >
        <div class="loading"></div>
    </div>

    <? if (!empty($referrals)) { ?>
        <table class="rwd-table">
            <tr>
                <th>Level</th>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Expires</th>
            </tr>
            <? foreach ($referrals as $r) { ?>
            <tr>
                <td data-th="Level"><?=$r->level?><br/> <?=money($r->price)?></td>
                <td data-th="Username"><?= $r->username ?></td>
                <td data-th="Name" ><?= $r->first_name.' '.$r->last_name ?></td>
                <td data-th="Email"><?= ($r->show_email)? $r->email : '--' ?></td>
                <td data-th="Phone"><?= ($r->show_phone) ? $r->phone : '--' ?></td>
                <td data-th="Expires"><?=date('d-M-Y h:i', $r->expires)?><br/>
                    <? if ($r->expires < now()) { ?>
                        <span class="red">Arrears</span>
                    <? } else { ?>
                        <?= displayCountDown($r->expires - now(), TRUE, TRUE) ?>
                    <? } ?>
                </td>
            </tr>
            <? } ?>
        </table>
    <? } else { ?>
            <div class="alert alert-warning">No referrals Expiration</div>
    <? } ?>

</div>
