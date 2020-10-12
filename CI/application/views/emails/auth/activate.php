Hello <?= $username ?>, <br/>
<br/>
Welcome to <?= SITE_NAME ?>!<br/>
<br/>
Your account has been created. Your password is <?= $password ?><br/>
<br/>
To complete your registration please click the following link to activate your account:<br/>
<br/>
<?= anchor(SITE_ADDRESS.'activate/'.$userId.'/'.$activation, SITE_ADDRESS.'activate/'.$userId.'/'.$activation) ?>
<br/><br/>

Thank you.
