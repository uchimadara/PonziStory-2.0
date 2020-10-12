Hello <?= $user->username ?>, <br/>
<br/>
Your <?= SITE_NAME ?> <?= $user->level ?> monthly subscription expired on <?= date(DEFAULT_DATETIME_FORMAT, $user->expires) ?>.
<br/>
<br/>
Donations at this stage will pass up to members above you if you don't upgrade in time.
<br/>
<br/>
Thank you.