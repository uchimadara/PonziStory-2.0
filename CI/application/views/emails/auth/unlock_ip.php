Hello <?= $username ?>,
<br/>
<br/>
An attempt was made to login to your account from a different IP address and you have your IP address locked.
You can not access your account from a different IP address if the IP address is locked.
<br/>
<br/>
If you want to unlock the IP address for your <?= SITE_NAME ?> account, you may click the link below.
<br/>
<br/>
If you weren't the one who tried to log in, simply ignore this email, or submit a support ticket to alert us.
<br/>
<br/>
<?= anchor(SITE_ADDRESS.'user/unlock_ip/'.$userId.'/'.$unlockCode, SITE_ADDRESS.'activate/'.$userId.'/'.$unlockCode) ?>
<br/><br/>
If you need further assistance, please submit a <a href="<?=SITE_ADDRESS?>support">support ticket.</a>
<br/>
<br/>
Thank you.
