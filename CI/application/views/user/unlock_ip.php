<? if (isset($error) && $error == true) { ?>
    <h2 class="hbg-d">Oops!</h2>
    <p>
        Something went wrong unlocking your account.<br/>
        <?= anchor('support', 'Please submit a support ticket to unlock your account or Contact Livechat below..') ?>
    </p>

<? } else { ?>

    <h2 class="hbg-d">Success!</h2>
    <p>
        Your account is now unlocked and you can log in with your username and password.<br/>
        <br/>
    </p>

<? } ?>

