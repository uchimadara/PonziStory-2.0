<h1 class="roundAqua top">Account Details</h1>
<div class="mainRound clearFix">
<?
    switch($code)
    {
        case 'lr':
?>
        <div class="row">
            <label><b>Account Number:</b></label> <br /><span><?=$account?></span>
        </div>
<?
            break;

        case 'ap':
        case 'st':
        case 'pm':
        case 'hd':
?>
        <div class="row">
            <label><b>Account:</b></label> <br /><span><?=$account?></span>
        </div>
<?
            break;

        case 'bw':
?>
        <div class="row">
            <label><b>Bank Name:</b></label> <br /><span><?=$account->bank_name?></span>
        </div>

        <div class="row">
            <label><b>Bank Address:</b></label> <br /><span><?=$account->bank_address?></span>
        </div>

        <div class="row">
            <label><b>Bank City:</b></label><br /> <span><?=$account->bank_city?></span>
        </div>

        <div class="row">
            <label><b>Bank Country:</b></label><br /> <span><?=$account->bank_country?></span>
        </div>

        <div class="row">
            <label><b>Fullname:</b></label> <br /><span><?=$account->fullname?></span>
        </div>

        <div class="row">
            <label><b>Address:</b></label><br /> <span><?=$account->address?></span>
        </div>

        <div class="row">
            <label><b>Country:</b></label> <br /><span><?=$account->country?></span>
        </div>

        <div class="row">
            <label><b>Account Number:</b></label> <br /><span><?=$account->account_number?></span>
        </div>

        <div class="row">
            <label><b>BIC: / SWIFT:</b></label><br /> <span><?=$account->bic_swift?></span>
        </div>

        <div class="row">
            <label><b>Iban:</b></label><br /> <span><?=$account->iban?></span>
        </div>

        <div class="row">
            <label><b>Info:</b></label><br /> <span><?=$account->info?></span>
        </div>
<?
            break;

        case 'wu':
?>
        <div class="row">
            <label><b>First Name:</b></label> <span><?=$account->first_name?></span>
        </div>

        <div class="row">
            <label><b>Last Name:</b></label> <span><?=$account->last_name?></span>
        </div>

        <div class="row">
            <label><b>City:</b></label> <span><?=$account->city?></span>
        </div>

        <div class="row">
            <label><b>Region/State:</b></label> <span><?=$account->region?></span>
        </div>

        <div class="row">
            <label><b>Postal Code/Zip:</b></label> <span><?=$account->zip?></span>
        </div>

        <div class="row">
            <label><b>Country:</b></label> <span><?=$account->country?></span>
        </div>
<?
            break;
    }
?>
</div>