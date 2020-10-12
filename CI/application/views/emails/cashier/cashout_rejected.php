Hello <?=$user['username']?>,<br/>
<br/>
Your withdrawal request has been rejected, and the funds have been returned to your account balance.<br/>
<br/>
The details of the cancelled transaction are shown below:<br/>
<br/>
----<br/>
<br/>
Cashout Id: <?=$trans['identifier']?><br/>
<br/>
Method: <?=$trans['name']?><br/>
<br/>
Amount: <?=money($trans['gross_amount'])?><br/>
<br/>
----<br/>
<br/>
For more information please open a support ticket in your <?=anchor(SITE_ADDRESS.'back_office', 'back office')?>.<br/>
<br/>
Thank you.