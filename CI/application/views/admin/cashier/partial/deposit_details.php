<?
    switch ($deposit->method)
    {
        /* Bank Wire */
        case 'bw':
            $sender  = new BankWire($userAccount->account);
            $details = new BankWireDetails($deposit->details);

        /* Western Union */
        case 'wu':
            $sender  = new WesternUnion($userAccount->account);
            $details = new WesternUnionDetails($deposit->details);
    }
?>
<h1 class="roundAqua top">Deposit Details</h1>
<div class="mainRound clearFix">
    <div class="row">
        <label><b>Sent to:</b></label> <span><?=$deposit->account_name?></span>
    </div>

    <div class="row">
        <label><b>Status:</b></label> <span><?=$deposit->status?></span>
    </div>

    <div class="row">
        <label><b>Created:</b></label> <span><?=Date('d/m/Y H:i:s', $deposit->created)?></span>
    </div>
<? if ($deposit->created != $deposit->updated): ?>
    <div class="row">
        <label><b>Updated:</b></label> <span><?=Date('d/m/Y H:i:s', $deposit->updated)?></span>
    </div>
<? endif; ?>
<?
    switch ($deposit->method)
    {
        /* Bank Wire */
        case 'bw':
?>
        <div class="row">
            <label><b>Bank Name:</b></label> <span><?=$sender->bank_name?></span>
        </div>

        <div class="row">
            <label><b>Bank Address:</b></label> <span><?=$sender->bank_address?></span>
        </div>

        <div class="row">
            <label><b>Bank City:</b></label> <span><?=$sender->bank_city?></span>
        </div>

        <div class="row">
            <label><b>Bank Country:</b></label> <span><?=$sender->bank_country?></span>
        </div>

        <div class="row">
            <label><b>Fullname:</b></label> <span><?=$sender->fullname?></span>
        </div>

        <div class="row">
            <label><b>Address:</b></label> <span><?=$sender->address?></span>
        </div>

        <div class="row">
            <label><b>City:</b></label> <span><?=$sender->city?></span>
        </div>

        <div class="row">
            <label><b>Country:</b></label> <span><?=$sender->country?></span>
        </div>

        <div class="row">
            <label><b>Account Number:</b></label> <span><?=$sender->account_number?></span>
        </div>

        <div class="row">
            <label><b>BIC/SWIFT:</b></label> <span><?=$sender->bic_swift?></span>
        </div>

        <? if ($sender->iban): ?>
        <div class="row">
            <label><b>IBAN:</b></label> <span><?=$sender->iban?></span>
        </div>
        <? endif; ?>

        <div class="row">
            <label><b>Memo Line:</b></label> <span><?=$details->memo?></span>
        </div>

        <? if ($details->info): ?>
        <div class="row">
            <label><b>Other Information:</b></label> <span><?=$details->info?></span>
        </div>
        <? endif; ?>
<?
        break;

        /* Western Union */
        case 'wu':
?>
        <h3>Details</h3>
        <div class="row">
            <label><b>Fullname:</b></label> <span><?=$sender->getTitle()?></span>
        </div>

        <div class="row">
            <label><b>City:</b></label> <span><?=$details->city?></span>
        </div>

        <div class="row">
            <label><b>Country:</b></label> <span><?=$details->country?></span>
        </div>

        <div class="row">
            <label><b>MTCN:</b></label> <span><?=$details->mtcn?></span>
        </div>

        <div class="row">
            <label><b>Currency:</b></label> <span><?=$details->currency?></span>
        </div>
<?
        break;
    }
?>
    <div class="row">
        <label><b>Amount:</b></label> <span><?=money($details->amount, $details->currency)?></span>
    </div>
</div>