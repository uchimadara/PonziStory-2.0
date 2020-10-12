
<div class="col-lg-12">
    <h2>Member Profile</h2>
</div>

<?php if($lock){ ?>
<h2 style="color:orangered">YOU CAN'T VIEW PROFILE COS YOUR ACCOUNT IS LOCKED. CONTACT SUPPORT</h2>
<?php } else { ?>

<? foreach ($widgets as $w) echo $w; ?>
 <?php } ?>