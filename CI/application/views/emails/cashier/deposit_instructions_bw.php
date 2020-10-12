Hello!

To fund your account using Bank Wire, please send between <?=money($depositAccount->minimum, '')?> and <?=money($depositAccount->maximum, '')?> (USD or EUR) to the details shown below.
Add your username in to the memo line of the wire, and nothing else.

After sending, please login to your account, and then visit the link below to inform us that your deposit is on its way:

<?=site_url('member/deposit/bw')?>


We will then aim to collect your Bank Wire deposit within 5 business days. And once received, we'll add the funds received, less our <?=$fees->percent?>% + $<?=$fees->fixed?> deposit fee. And send you an email notification informing you that your funds are available for use.

----

Bank Name: <?=$depositDetails->bank_name?>

Bank Country: <?=$depositDetails->bank_country?>

Account Holder: <?=$depositDetails->fullname?>

Account Number: <?=$depositDetails->account_number?>

IBAN: <?=$depositDetails->iban?>

BIC/SWIFT: <?=$depositDetails->bic_swift?>


----

If you have any questions, reply to this email, or open a support ticket from your members area.

Thank you.