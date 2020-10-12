Hello!<br/>
<br/>
A request has been made to change the <?=SITE_NAME?> password for <?=$username?>.<br/>
<br/>
Please click the following link to Reset Your Password:<br/>
<?=anchor(SITE_ADDRESS.'reset_password/'. $forgotten_password_code, SITE_ADDRESS.'reset_password/'.$forgotten_password_code)?><br/>
<br/>
If you did not request this change, simply ignore this email and the request will expire.<br/>
<br/>
Thank you.