Hello <?= $user->username ?>, <br/>
<br/>
Your <?= SITE_NAME ?> <?= $user->level ?> monthly subscription is set to expire on <?= date(DEFAULT_DATETIME_FORMAT, $user->expires) ?>.
<br/>
<br/>
If this subscription expires, you will no longer receive donations at this stage.<br/>
<br/>
Upgrade today to keep receiving <?= $user->level ?> stage donations.
<br/>
<br/>
Thank you.