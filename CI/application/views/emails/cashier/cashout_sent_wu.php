<?
    $senderDetails    = new WesternUnion($fromAccount->details);
    $recipientDetails = new WesternUnion($userAccount->account);
    $details          = new WesternUnionDetails($cashout->details);
?>
Hello!

Your Western Union cashout has been accepted, and your funds have been sent.

----

Cashout Id: <?=$cashout->identifier?>

Gross Amount: <?=money($cashout->gross_amount)?>

Cashout Fee Deducted: <?=money($cashout->fee)?>


----

Senders First Name: <?=$senderDetails->first_name?>

Senders Surname: <?=$senderDetails->last_name?>

Senders City: <?=$senderDetails->city?>

Senders Country: <?=$senderDetails->country?>


Amount Sent (to pickup): <?=money($details->amount, $details->currency)?>

Currency: <?=$details->currency?>

MTCN: <?=$details->mtcn?>


Recipient First Name: <?=$recipientDetails->first_name?>

Recipient Surname: <?=$recipientDetails->last_name?>

Recipient City: <?=$details->city?>

Recipient Country: <?=$details->country?>


----

If you have any questions, reply to this email, or open a support ticket from your members area.

Thank you.