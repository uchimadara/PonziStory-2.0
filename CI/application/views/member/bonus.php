<div class="col-lg-12">
    <h1>Referral Bonus</h1>

    <? if ($userData->account_level > 0) { ?>



        <div class="modal fade" id="modalLogForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">WITHDRAW BONUS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <br>

                    <?php


                    if ($gast > 2500){ ?>
                        <form name="frmm" id="frmm" method="post" action="/member/insertGHBonus">


                            <div class="modal-body mx-3">
                                <p style="text-align: center">Click submit to withdraw the sum of <b><?php echo money($gast);  ?></b></p>


                            </div>

                            <div class="modal-footer d-flex justify-content-center">
                                <input class="btn btn-alt m-r-10" type="submit" name="submit" value="SUBMIT">
                            </div>
                        </form>
                    <?php } else { ?>

                  <p style="color: red;font-size: 16px;font-weight: bold;text-align: center">Your Total Available Bonus is <?php echo money($gast) ?></p>
                        <p style="color: red;font-size: 16px;font-weight: bold;text-align: center">You must have at least N2500 to Withdraw Bonus</p>


                    <?php } ?>
                    <br>
                    <br>
                </div>
            </div>
        </div>




        <div class="p-10">
            <h2>Your Referral Link</h2>
            <?php if(!$lock){ ?>
            <button type="button" style="float: right;" data-toggle="modal" data-target="#modalLogForm" class="btn btn-success btn-lg">WithDraw Bonus</button>
            <?php } ?>
            <div class="memberRefLink" style="width: 400px">
                <?= $refUrl ?>

            </div>

            <div class="memberRefLink" style="width: auto">

<p style="color: orangered">NOTE: Only Fixed amount of N2500, N5000 and N10,000 will be merged </p>
<p style="color: orangered">NOTE2: Bonus of N2500+ will be changed to N2500 and Bonus of N5000+ will be N5000 and bonus of N10,000+ will be N10000 </p>
            </div>

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">First-Tier Bonus (<?php echo count($fbl) ?>)</a></li>
                <li><a data-toggle="tab" href="#menu1">Second-Tier Bonus (<?php echo count($sbl) ?>)</a></li>
                <li><a data-toggle="tab" href="#menu2">Available Bonus</a></li>
                <li><a data-toggle="tab" href="#menu3">Withdraw History</a></li>
            </ul>

            <div class="tab-content">
                <div id="home" class="tab-pane fade in active">
                    <div class="col-lg-12">
                        <div class="tile">

                            <div id="memberSummary">
                                <table class="table" style="font-size: 18px">
                                    <tbody><tr>
                                        <th>Bonus Level</th>
                                        <th>Username</th>
                                        <th class="right">Plan Invest</th>
                                        <th class="right">Avail. Date</th>
                                        <th class="right">Potential</th>
                                    </tr>

                                    <?php foreach ($fbl as $firstb){ ?>
                                        <tr>
                                            <td data-th="Level">
                                                1
                                            </td>
                                            <td data-th="Price">
                                                <?php echo $firstb->username; ?>
                                            </td>
                                            <td data-th="Max. Referrals" class="right">
                                                <?php echo money($firstb->amount); ?>
                                            </td>
                                            <td data-th="Referrals" class="right">
                                                <?php echo $firstb->date_of_gh; ?>
                                            </td>
                                                <?php if($firstb->status == 4) { ?>
                                            <td data-th="Potential" class="right" style="color: darkgreen">
                                                <?php echo money($firstb->amount * 0.05); ?>
                                            </td>
                                            <?php }else { ?>
                                            <td data-th="Potential" class="right">
                                                <?php echo money($firstb->amount * 0.05); ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>


                                    <tr>
                                        <td data-th="Sum" class="listTotal">Total</td>
                                        <td data-th="" class="listTotal">&nbsp;</td>
                                        <td data-th="" class="listTotal right"><?php echo money($flt); ?></td>
                                        <td data-th="Referrals" class="listTotal right">&nbsp;</td>
                                        <td data-th="Potential" class="listTotal right"><?php echo money($flt * 0.05); ?></td>
                                    </tr>


                                    </tbody></table>

                            </div>

                        </div>
                    </div>
                </div>
                <div id="menu1" class="tab-pane fade">
                    <div class="col-lg-12">
                        <div class="tile">

                            <div id="memberSummary">
                                <table class="table" style="font-size: 18px">
                                    <tbody><tr>
                                        <th>Bonus Level</th>
                                        <th>Username</th>
                                        <th class="right">Plan Invest</th>
                                        <th class="right">Avail. Date</th>
                                        <th class="right">Potential</th>
                                    </tr>


                                    <?php foreach ($sbl as $secondb){ ?>
                                        <tr>
                                            <td data-th="Level">
                                                2
                                            </td>
                                            <td data-th="Price">
                                                <?php echo $secondb->username; ?>
                                            </td>
                                            <td data-th="Max. Referrals" class="right">
                                                <?php echo money($secondb->amount); ?>
                                            </td>
                                            <td data-th="Referrals" class="right">
                                                <?php echo $secondb->date_of_gh; ?>
                                            </td>

                                            <td data-th="Potential" class="right">
                                                <?php echo money($secondb->amount * 0.025); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                    <tr>
                                        <td data-th="Sum" class="listTotal">Total</td>
                                        <td data-th="" class="listTotal">&nbsp;</td>
                                        <td data-th="" class="listTotal right"><?php echo money($slt); ?></td>
                                        <td data-th="Referrals" class="listTotal right">&nbsp;</td>
                                        <td data-th="Potential" class="listTotal right"><?php echo money($flt * 0.025); ?></td>
                                    </tr>
                                    </tr>


                                    </tbody></table>

                            </div>

                        </div>
                    </div>
                </div>
                <div id="menu2" class="tab-pane fade">
                    <div class="col-lg-12">
                        <div class="tile">

                            <div id="memberSummary">
                                <table class="table" style="font-size: 18px">
                                    <tbody><tr>
                                        <th>Bonus Level</th>
                                        <th>Username</th>
                                        <th class="right">Plan Invest</th>
                                        <th class="right">Avail. Date</th>
                                        <th class="right" style="color: darkgreen">Available</th>
                                    </tr>


                                    <?php foreach ($fab as $fabonus){ ?>
                                        <tr>
                                            <td data-th="Level">
                                                1
                                            </td>
                                            <td data-th="Price">
                                                <?php echo $fabonus->username; ?>
                                            </td>
                                            <td data-th="Max. Referrals" class="right">
                                                <?php echo money($fabonus->amount); ?>
                                            </td>
                                            <td data-th="Referrals" class="right">
                                                <?php echo $fabonus->date_of_gh; ?>
                                            </td>

                                            <td data-th="Potential" class="right" style="color: darkgreen">
                                                <?php echo money($fabonus->amount * 0.05); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <?php foreach ($sab as $sabonus){ ?>
                                        <tr>
                                            <td data-th="Level">
                                                2
                                            </td>
                                            <td data-th="Price">
                                                <?php echo $sabonus->username; ?>
                                            </td>
                                            <td data-th="Max. Referrals" class="right">
                                                <?php echo money($sabonus->amount); ?>
                                            </td>
                                            <td data-th="Referrals" class="right">
                                                <?php echo $sabonus->date_of_gh; ?>
                                            </td>

                                            <td data-th="Potential" class="right" style="color: darkgreen">
                                                <?php echo money($sabonus->amount * 0.025); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td data-th="Sum" class="listTotal">Total</td>
                                        <td data-th="" class="listTotal">&nbsp;</td>
                                        <td data-th="" class="listTotal right"><?php echo  money($gabsf + $gabss) ?></td>
                                        <td data-th="Referrals" class="listTotal right">&nbsp;</td>
                                        <td data-th="Potential" class="listTotal right"><?php echo  money(($gabsf *0.05) + ($gabss * 0.025)) ?></td>
                                    </tr>


                                    </tbody></table>

                            </div>

                        </div>
                    </div>
                </div>
                <div id="menu3" class="tab-pane fade">
                    <div class="col-lg-12">
                        <div class="tile">

                            <div id="memberSummary">
                                <table class="table" style="font-size: 18px">
                                    <tbody><tr>
                                        <th>#</th>
                                        <th> Date Added</th>
                                        <th> Date Due</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>


                                    <?php foreach ($gbgh as $fabonus){ ?>
                                        <tr>
                                            <td data-th="Level">
                                                #
                                            </td>
                                            <td data-th="Price">
                                                <?php echo $fabonus->date_added; ?>
                                            </td>

                                            <td data-th="Price">
                                                <?php echo $fabonus->date_of_gh; ?>
                                            </td>
                                            <td>
                                                <?php echo money($fabonus->amount); ?>
                                            </td>
                                            <? if($fabonus->status == 1){ ?>
                                            <td>
                                                <b>Pending </b>
                                            </td>
                                            <? } ?>

                                            <? if($fabonus->status == 2){ ?>
                                                <td>
                                                    <b>Merged </b>
                                                </td>
                                            <? } ?>
                                            <? if($fabonus->status == 3){ ?>
                                                <td>
                                                    <b>Awaiting Approval </b>
                                                </td>
                                            <? } ?>
                                            <? if($fabonus->status == 4){ ?>
                                                <td>
                                                    <b>Cashed Out </b>
                                                </td>
                                            <? } ?>

                                        </tr>
                                    <?php } ?>





                                    </tbody></table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="referralBanner">
                <? foreach ($banners as $img) { ?>
                    <br/>Image Location: <?= htmlentities(SITE_ADDRESS."banners/$img") ?>
                    <br/><img alt="<?= SITE_NAME ?>" src="<?= SITE_ADDRESS.'banners/'.$img ?>"/>
                    Code:<br/>
                    <div style="border:1px solid #333333; padding:5px;font-size:.9em;">
                        &lt;a href=&quot;<?= $refUrl ?>&quot;&gt;&lt;img src=&quot;<?= SITE_ADDRESS.'banners/'.$img ?>&quot; /&gt;&lt;/a&gt;
                    </div>
                    <hr class="whiter"/>

                <? } ?>
            </div>

        </div>

    <? } else { ?>

        <div class="alert alert-warning">This page can be viewed only by upgraded members.
            <a href="<?= SITE_ADDRESS ?>back_office/upgrade">Upgrade now.</a>
        </div>

    <? } ?>

</div>
