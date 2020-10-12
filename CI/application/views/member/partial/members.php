<? foreach ($members as $m) { ?>
    <div class="col-lg-6">
        <div class="tile p-10">

            <div class="pull-left m-r-20">
                <? if ($m->avatar) { ?>
                    <a href="http://<?=$m->username.SITE_DOMAIN?>portfolio.html" class="m-t-15">
                        <img src="<?= avatar($m->avatar) ?>"/>
                    </a>
                <? }  else {  ?>
                                    <i class="fa fa-coffee" style="font-size: 46pt;color: #C6C6C6;margin-bottom: 5px;"></i>
                <? } ?>

            </div>
            <div class="pull-left">
                <div class="fs22 underline">
                    <a href="http://<?= $m->username.SITE_DOMAIN ?>portfolio.html" ><?= $m->username ?></a>
                </div>
                <div class="teProfileBox">
                    <div class="pull-left">
                        <table class="table table-condensed table-responsive">
                            <tr>
                                <td>Member Since:</td>
                                <td><?= date(DEFAULT_DATE_FORMAT, $m->member_since) ?></td>
                            </tr><tr>
                                <td>Account Level:</td>
                                <td><?= $m->account_level ?></td>
                            </tr>
                        </table>

                    </div>

                    <div class="clear"></div>
                </div>
            </div>
            <div class="pull-right">
                <? if ($following && in_array($m->user_id, $following)) { ?>
                    <i class="fa fa-flag green"></i>

                <? } ?>

            </div>
            <div class="clear"></div>
            <table class="table table-responsive listingStats">
                <tr>
                    <td>Active Listings:</td>
                    <td><?= number_format($m->listing_count) ?> </td>
                    <td>Earnings:</td>
                    <td><?= money($m->earning) ?></td>
                </tr>
                <? if ($m->show_financial)  { ?>
                <tr>
                    <td>Total Invested:</td>
                    <td><?= money($m->investment_total) ?></td>
                    <td>Total Cashout:</td>
                    <td><?= money($m->cashout_total) ?></td>
                </tr>
                    <tr>
                        <td>ROI:</td>
                        <td colspan="3">
                            <?= ($m->investment_total) ? number_format($m->cashout_total/$m->investment_total*100).'%' : 'N/A' ?>
                        </td>
                    </tr>
                <? } ?>
            </table>
        </div>
    </div>

<? } ?>

<div class="clear"></div>
<? if ($memberCount > ($page * $perPage)) { ?>
    <div id="memberPage-<?php echo "$filter$page" ?>">
        <div class="center p-20">
            <a class="btn btn-lg replace" href="<?= SITE_ADDRESS ?>member/more_members/<?= $page + 1 ?>/<?= $perPage ?>/<?= $filter ?>" data-div="memberPage-<?php echo "$filter$page" ?>">Load More...</a>
        </div>
    </div>

<? } ?>
