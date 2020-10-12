Hello <?=$first_name.' '.$last_name?>!<br/>
You have been invited by <?=$inviter?> to join them in <?=SITE_NAME?>!
<br/><br/>
<? if (isset($upline)) { ?>
You will be joining their team under <?=$upline?>.<br/><br/>
<? } ?>
<?= SITE_NAME ?> is a CrowdFunding Networking platform for Business owners, Entrepreneurs, start ups, etc
<br/><br/>
<a href="<?= SITE_ADDRESS.'user/invite/'.$activation_code ?>">Click here to accept this invitation.</a><br/>
<?= anchor(SITE_ADDRESS.'user/invite/'.$activation_code, SITE_ADDRESS.'user/invite/'.$activation_code) ?>

<br/><br/>
This invitation will expire <?= date(DEFAULT_DATETIME_FORMAT, $account_expires) ?>.
<br/><br/>

You can read How it works:
<a href="<?= BASE_URL ?>page/howitworks"><?=BASE_URL?>page/howitworks</a>
<p>
You can read more here:
<a href="<?= BASE_URL ?>page/faqs"><?=BASE_URL?>page/faqs</a>
</p>
<br/><br/>

Thank you.<br/>
