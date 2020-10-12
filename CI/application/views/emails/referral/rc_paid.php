Hello!<br/>
<br/>
Good news <?=$username?>!<br/>
<br/>
Your <?=ordinal($level, true)?> level referral, <?=$referral?> has just purchased <?=$description?> earning you a <?=money($amount)?> commission.<br/>
<br/>
That amount has been added to your <?=SITE_NAME?> account balance.<br/>
<br/>
Transaction ID: <?= $transaction_id ?><br/>
<br/>
Thank you.<br/>