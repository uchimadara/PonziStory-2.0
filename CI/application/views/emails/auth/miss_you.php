Hello <?= $user->username ?>, <br/>
<br/>

We noticed you have not checked what's happening on <?= SITE_NAME ?> in a while.
Please take a minute to <?=anchor(user_url($user->username), 'log in')?> to see all the great things we have going on. We miss you!

<?= ($user->balance > 0) ? '<p> You have '.money($user->balance).' in your account balance.</p>' : '' ?>
<?=($user->te_credits > 0) ?  '<p> You have '.number_format($user->te_credits).' credits you can use to promote your portfolio to other members.</p>' : '' ?>
<?= ($user->ad_credits > 0) ? '<p> You have '.number_format($user->ad_credits).' credits you can use to run text ads.</p>' : '' ?>
<?= ($user->banner_credits > 0) ? '<p> You have '.number_format($user->banner_credits).' credits you can use to place banners.</p>' : '' ?>
