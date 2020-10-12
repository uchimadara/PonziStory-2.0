Hello <?= $username ?>!<br/>
<br/>
Thank you for supporting <?=SITE_NAME?>.<br/>
<br/>
Member <?= $referral->username?> has just signed up as your level <?=$level?> referral!<br/>
<br/>
Name: <?= $referral->first_name.' '.$referral->last_name?><br/>
Email: <?= $referral->email?><br/>
<? if (!empty($referral->phone)) { ?>
    Phone: <?= $referral->phone ?><br/>

<? } ?>

<br/>
Thank you.<br/>