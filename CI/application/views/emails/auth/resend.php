Hello <?= $user->username ?>, <br/>
<br/>
A request was made to resend the activation link for your <?=SITE_NAME?> account.<br/>
<br/>
<b>If you did not make this request, please ignore this email.</b><br/>
<br/>
Please click the following link to activate your account:<br/>
<br/>
<?=anchor(SITE_ADDRESS.'activate/'. $user->id .'/'. $user->activation_code, SITE_ADDRESS.'activate/'.$user->id.'/'.$user->activation_code) ?>
<br/><br/>

Thank you.