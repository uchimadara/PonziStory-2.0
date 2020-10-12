<? if ($success) { ?>
<p>
    <strong>You have successfully opted out from this type of email from <?=SITE_NAME?>.</strong>
    <br /><br />
    You can change your email settings in your account back office.
</p>

<? } else { ?>
    <p>
        <strong>There was an error unsubscribing.</strong>
        <br/><br/>
        You can change your email settings in your account back office.
    </p>

<? } ?>