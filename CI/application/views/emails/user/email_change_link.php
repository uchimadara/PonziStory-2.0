Hello!<br/>
<br/>
A request has been made to change the <?=SITE_NAME?> email for <?=$username?>.<br/>
<br/>
Please click the following link to Change Your E-mail Address:<br/>
<?=anchor(SITE_ADDRESS.'change_email/'. $email_change_code.'/'. $email.'/'. $user_id, SITE_ADDRESS.'change_email/'.$email_change_code.'/'. $email.'/'. $user_id)?><br/>
<br/>
If you did not request this change, simply ignore this email and the request will expire.<br/>
<br/>
Thank you.