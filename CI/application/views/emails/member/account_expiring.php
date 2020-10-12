Hello <?= $user->username ?>, <br/>
<br/>
Your <?= SITE_NAME ?> <?= $user->account_level ?> membership is set to expire on <?= date(DEFAULT_DATETIME_FORMAT, $user->account_expires) ?>.
<br/>
<br/>
If your membership expires, your account will be deleted.<br/>
<br/>
Upgrade today to keep your account active.
<br/>
<br/>

Thank you.