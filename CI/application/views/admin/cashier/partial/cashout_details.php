<h1 class="roundAqua top">Cashout Details</h1>
<div class="mainRound clearFix">
    <div class="row">
        <label><b>Gross Amount:</b></label> <span><?=money($cashout->gross_amount)?></span>
    </div>

    <div class="row">
        <label><b>Net Amount:</b></label> <span><?=money($cashout->amount)?></span>
    </div>

    <div class="row">
        <label><b>Status:</b></label> <span><?=$cashout->status?></span>
    </div>

    <div class="row">
        <label><b>Created:</b></label> <span><?=Date('d/m/Y H:i:s', $cashout->created)?></span>
    </div>

<? if ($cashout->created != $cashout->updated): ?>
    <div class="row">
        <label><b>Updated:</b></label> <span><?=Date('d/m/Y H:i:s', $cashout->updated)?></span>
    </div>
<? endif; ?>

<?
    switch ($cashout->method)
    {
        /* Bank Wire */
        case 'bw':
            $receiver = new BankWire($userAccount->account);
            $details  = new BankWireDetails($cashout->details);
?>
        <h3>Bank Details</h3>
        <div class="row">
            <label><b>Name:</b></label> <span><?=$receiver->bank_name?></span>
        </div>

        <div class="row">
            <label><b>Address:</b></label> <span><?=$receiver->bank_address?></span>
        </div>

        <div class="row">
            <label><b>City:</b></label> <span><?=$receiver->bank_city?></span>
        </div>

        <div class="row">
            <label><b>Country:</b></label> <span><?=$receiver->bank_country?></span>
        </div>

        <h3>Account Holder Details</h3>
        <div class="row">
            <label><b>Fullname:</b></label> <span><?=$receiver->fullname?></span>
        </div>

        <div class="row">
            <label><b>Address:</b></label> <span><?=$receiver->address?></span>
        </div>

        <div class="row">
            <label><b>City:</b></label> <span><?=$receiver->city?></span>
        </div>

        <div class="row">
            <label><b>Country:</b></label> <span><?=$receiver->country?></span>
        </div>

        <h3>Account Details</h3>
        <div class="row">
            <label><b>Account Number:</b></label> <span><?=$receiver->account_number?></span>
        </div>

        <div class="row">
            <label><b>BIC/SWIFT:</b></label> <span><?=$receiver->bic_swift?></span>
        </div>

        <? if ($receiver->iban): ?>
        <div class="row">
            <label><b>IBAN:</b></label> <span><?=$receiver->iban?></span>
        </div>
        <? endif; ?>

        <? if ($receiver->info): ?>
        <div class="row">
            <label><b>Info:</b></label> <span><?=$receiver->info?></span>
        </div>
        <? endif; ?>
<?
        break;

        /* Western Union */
        case 'wu':
            $receiver = new WesternUnion($userAccount->account);
            $details  = new WesternUnionDetails($cashout->details);
?>
        <h3>Receiver Details</h3>
        <div class="row">
            <label><b>Fullname:</b></label> <span><?=$receiver->getTitle()?></span>
        </div>

        <div class="row">
            <label><b>City:</b></label> <span><?=$receiver->city?></span>
        </div>

        <div class="row">
            <label><b>Country:</b></label> <span><?=$receiver->country?></span>
        </div>
<?
        break;
    }
?>
</div>