Hello <?= $username ?>, <br/>
<br/>
Welcome to <?= SITE_NAME ?>!<br/>
<br/>
Your account has been created. Your password is ********** (hidden - if you forgot it,
you'll need to perform the "forgot password" procedure after your account is activated.)<br/>
<br/>
To complete your registration please click the following link to activate your account:<br/>
<br/>
<?= anchor(SITE_ADDRESS.'activate/'.$userId.'/'.$activation, SITE_ADDRESS.'activate/'.$userId.'/'.$activation) ?>
<br/><br/>

Thank you.
