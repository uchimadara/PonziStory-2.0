<div class="styleshow">
    <a href="<?= site_url('adminpanel/users/detail/'.$user->id) ?>">Profile</a>
    <a href="<?= site_url('adminpanel/users/campaigns/'.$user->id) ?>">Campaigns</a>
    <a href="<?= site_url('adminpanel/users/history/'.$user->id.'/all') ?>">History</a>
    <a href="<?= site_url('adminpanel/users/summary/'.$user->id.'/all') ?>">Summary</a>
    <a href="<?= site_url('adminpanel/users/tickets/'.$user->id) ?>">Tickets</a>
</div>

<h2>Referrals for <?=$user->username?></h2>
<? if ($referrals): ?>
<h3>Referrals:</h3>

    <table>
    <tr>
        <th>Level 1 (<?=count($referrals[1])?>)</th>
        <th>
        <? if (isset($referrals[2])): ?>
            Level 2 (<?=count($referrals[2])?>)
        <? endif; ?>
        </th>
    </tr>
    <tr>
        <td valign="top">
            <ul>
            <? foreach ($referrals[1] as $ref): ?>
                <li><a href="<?=site_url('adminpanel/users/detail/' . $ref->id)?>" class="tablehbidder"><?=$ref->username?></a></li>
            <? endforeach; ?>
            </ul>
        </td>
        <td valign="top">
        <? if (isset($referrals[2])): ?>
            <ul>
            <? foreach ($referrals[2] as $ref): ?>
                <li><a href="<?=site_url('adminpanel/users/detail/' . $ref->id)?>" class="tablehbidder"><?=$ref->username?></a></li>
            <? endforeach; ?>
            </ul>
        <? endif; ?>
        </td>
    </tr>
</table>
<? endif; ?>