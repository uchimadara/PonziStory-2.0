    <? if (isset($error) && $error == true) { ?>
    <h2 class="hbg-d">Oops!</h2>
    <p>
        Something went wrong activating your account.<br/>
        <?=anchor('user/resend', 'Click here to resend the activation email.')?>
    </p>

<? } else { ?>

<h2 class="hbg-d" style="color: #0E790E">Success!</h2>
<p>
    Your account is now activated and you can log in with your username and password.<br/>
    <br/>
</p>
<div class="fs22" style="color: #0E790E">Enjoy <?= SITE_NAME ?>!</div>

<? } ?>

