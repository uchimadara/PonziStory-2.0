<h1>Please wait to upgrade</h1>

<div class="alert alert-warning">
    <div class="pull-left p-t-10">
        <i class="fa fa-exclamation-triangle fs20" aria-hidden="true"></i>

    </div>
    <div class="pull-left m-l-20" style="width:90%">
        <p>
            Your sponsor has maximum referrals and is waiting for payments to be approved. You will need to wait until at
            least one of those payments is approved so you can spill down to a new sponsor.

        </p>

        <p>
            The oldest payment was submitted <?=displayCountDown(now()-$waiting, true, true)?> ago
            and has <?=$confirmations.pluralise(' confirmation', $confirmations)?>.

        </p>
        <p>
            Thank you for your patience.
        </p>
    </div>
    <div class="clear"></div>
</div>
