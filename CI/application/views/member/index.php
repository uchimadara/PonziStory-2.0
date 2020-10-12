<h1 class="yellow">Member Dashboard</h1>
<div id="addListingCredits">
    <?= $activeListings ?>
</div>
<?= $topPerformers ?>
<div class="clear"></div>

<div class="tab-container tile">
    <ul class="nav tab nav-tabs">
        <li class="active"><a href="#myStats">My Portfolio Stats</a></li>
        <li><a href="#textAdStats">Text Ads Stats</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="myStats">
            <div class="p-10">
                <div class="tile p-10">
                    <h4 class="m-b-0">
                        <?= $userData->account_level ?> Member
                    </h4>
                    <?= $maxListings ?> Listings Allowed &nbsp;
                    <span id="activeListingCount"><?= $activeListingCount ?></span> Active Listings
                    <span class="m-l-20"><a href="<?=SITE_ADDRESS?>back_office/portfolio.html" class="btn btn-alt">Manage Listings</a></span>
                    <span id="upgradeOrAdd" class="m-l-20">
                                <? if ($activeListingCount < $maxListings) { ?>
                                    <a class="btn btn-alt" href="<?= SITE_ADDRESS ?>back_office/add_listing.html">Add Listing</a>
                                <? } else { ?>
                                    <?= anchor('upgrade', 'Upgrade your account', 'class="btn btn-alt"') ?>
                                <? } ?>
                            </span>


                </div>
                <div class="clear"></div>
                <h3>All Time</h3>

                <div>
                    <? foreach ($portfolioStats as $name => $stat) { ?>
                        <div class="tile stat-chart">
                            <div class="sparkline" data-url="<?= SITE_ADDRESS.$stat['url'] ?>"></div>
                            <div class="data">
                                <h2 data-value="<?= $stat['count'] ?>" data-format="<?= (isset($stat['format'])) ? $stat['format'] : '' ?>">0</h2>
                                <small><?= $name ?></small>
                            </div>
                        </div>
                    <? } ?>
                    <p>
                        Active Listing &amp; Traffic Exchange Statistics <span class="arialBold">Coming Soon!</span>
                    </p>
                </div>
                <div class="clear"></div>

            </div>
        </div>
        <div class="tab-pane" id="textAdStats">
            <div class="p-10">
                <div>
                    <? foreach ($adStats as $name => $stat) { ?>

                        <div class="tile stat-chart">
                            <div class="sparkline" data-url="<?= SITE_ADDRESS.$stat['url'] ?>"></div>
                            <div class="data">
                                <h2 data-value="<?= $stat['count'] ?>" data-format="<?= (isset($stat['format'])) ? $stat['format'] : '' ?>">0</h2>
                                <small><?= $name ?></small>
                            </div>
                        </div>
                    <? } ?>
                </div>
                <div class="clear"></div>
                <div class="m-t-10">
                    <h3>Current Status</h3>
                    <table class="table">
                        <tr>
                            <td>Unallocated Credits</td>
                            <td><?= number_format($userData->ad_credits) ?>
                                <? if ($userData->ad_credits > 0) { ?>
                                    <a href="<?= SITE_ADDRESS ?>back_office/text_ads.html" class="btn btn-alt btn-xs">Manage Ads</a>
                                <? } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Remaining Impressions</td>
                            <td><?= number_format($remainingViews) ?></td>
                        </tr>
                        <tr>
                            <td>Total Credits</td>
                            <td><?= number_format($totalCredits) ?></td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

<? if ($ajax) { ?>
    <script>
        $('.tab').tabs();
        $('.tab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');

            drawCharts($($(this).attr('href')));
        });

        drawCharts($('#siteStats'));
    </script>
<? } ?>
