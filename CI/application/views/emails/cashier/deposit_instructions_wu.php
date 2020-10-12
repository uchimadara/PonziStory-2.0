Hello!

To fund your account using Western Union, please send between <?=money($depositAccount->minimum, '')?> and <?=money($depositAccount->maximum, '')?> (USD or EUR) to the details shown below.

After sending, please login to your account, and then visit the link below to submit your MTCN number, and inform us
that your deposit is on its way:

<?=site_url('member/deposit/wu')?>


We will then aim to collect your Western Union deposit the following business day. And once received, we'll add the funds received, less our <?=$fees->percent?>% + $<?=$fees->fixed?> deposit fee. And send you an email notification informing you that your funds are available for use.

----

First Name: <?=$depositDetails->first_name?>

Surname: <?=$depositDetails->last_name?>

City: <?=$depositDetails->city?>

Country: <?=$depositDetails->country?>


----

If you have any questions, reply to this email, or open a support ticket from your members area.

Thank you.