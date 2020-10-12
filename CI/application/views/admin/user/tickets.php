<div class="styleshow">
    <a href="<?= site_url('adminpanel/users/detail/'.$user->id) ?>">Profile</a>
    <a href="<?= site_url('adminpanel/users/campaigns/'.$user->id) ?>">Campaigns</a>
    <a href="<?= site_url('adminpanel/users/history/'.$user->id.'/all') ?>">History</a>
    <a href="<?= site_url('adminpanel/users/summary/'.$user->id.'/all') ?>">Summary</a>
</div>
<h2>Support Tickets for <?=anchor('/adminpanel/users/detail/' . $user->id, $user->username)?></h2>
<div class="pageable">
    <?=$tickets?>
</div>