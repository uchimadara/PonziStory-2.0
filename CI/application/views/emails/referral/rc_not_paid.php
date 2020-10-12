Hello <?= $username ?>!<br/>
<br/>
Your <?=ordinal($level, TRUE)?> level referral, <?=$referral?>, has just purchased <?=$description?> which would have earned you a commission.<br/>
<br/>
Simply upgrade your account to get the following comissions added to your <?= SITE_NAME ?> account balance
in the future.<br/>
<br/>
On this purchase... <br/>
<ul>
  <li>Novice level account earns <?=money($amountNovice)?></li>
  <li>Advanced level account earns <?=money($amountAdvanced)?></li>
  <li>Expert level account earns <?=money($amountExpert)?></li>
</ul>
<br/>
