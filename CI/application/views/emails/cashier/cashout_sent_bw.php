<?
    $recipientDetails = new WesternUnion($userAccount->account);
    $details          = new WesternUnionDetails($cashout->details);
?>
Hello!

Your Bank Wire cashout has been accepted, and your funds have been sent.

Allow up to 5 working days for the funds to clear in your bank account. Please do not contact support during this time.

----

Cashout Id: <?=$cashout->identifier?>

Gross Amount: <?=money($cashout->gross_amount)?>

Cashout Fee Deducted: <?=money($cashout->fee)?>

Amount Sent: <?=money($details->amount, $details->currency)?>

Currency: <?=$details->currency?>

Funds transferred to: <?=$recipientDetails->fullname?>

Account Number: <?=$recipientDetails->account_number?>


----

If you have any questions, reply to this email, or open a support ticket from your members area.

Thank you.