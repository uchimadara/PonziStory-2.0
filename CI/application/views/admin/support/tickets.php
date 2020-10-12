<h1>Support Tickets</h1>
<div class="col-md-12">
  <?php $me = 50; ?>


    <div class="modal fade" id="supbonus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">WITHDRAW SUPPORT BONUS</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <br>
                <br>

                <?php


                if ($Supcount * $me  > 5000){ ?>
                    <form name="frmm" id="frmm" method="post" action="/member/insertSupBonus">


                        <div class="modal-body mx-3">
                            <p style="text-align: center">Click submit to withdraw the sum of <b><?php echo money($Supcount * $me);  ?></b></p>

                            <br>
<!--                            <p style="color: orangered">Note:If you want to upgrade to Next plan, Tick below</p>-->
                        </div>
                        <input type="hidden" name="amount" value="<?php echo $Supcount * $me;  ?>">

                        <div class="modal-footer d-flex justify-content-center">
                            <input class="btn btn-alt m-r-10" type="submit" name="submit" value="SUBMIT">
                        </div>
                    </form>
                <?php } else { ?>

                    <p style="color: red;font-size: 16px;font-weight: bold;text-align: center">Your Bonus is  <?php echo $Supcount * $me ?></p>
                    <p style="color: red;font-size: 16px;font-weight: bold;text-align: center">You must have at least N5000 to withdraw your Support Bonus</p>


                <?php } ?>
                <br>
                <br>
            </div>
        </div>
    </div>






        <a href="#">  <button type="button" data-toggle="modal" data-target="#supbonus" style="line-height: 40px; font-size: 20px;font-weight: bold;padding: 0px;margin: 0px"  class="btn btn-danger btn-sm"><small style="display: block;width: 150px;word-wrap: break-word;white-space: normal;">Support Bonus  (<?php echo money($Supcount*$me) ?>)</small></button> </a>



    <div class="tile">
        <h2 class="tile-title">Open Member Tickets [<?= $memberTicketCount ?> total]</h2>

        <div id="user_tickets" class="pageable rms-sortable getList" data-url="<?=SITE_ADDRESS?>support/getList/user_tickets?status=open">
            <span class="loading"></span>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Open Guest Tickets [<?= $guestTicketCount ?> total]</h2>

        <div id="user_tickets" class="pageable rms-sortable getList" data-url="<?= SITE_ADDRESS ?>support/getList/guest_tickets?status=open">
            <span class="loading"></span>
        </div>
    </div>
</div>