<div id="siteStats">
    <? foreach ($userStats as $name => $stat) { ?>
    <div class="col-md-6">
        <div class="tile stat-chart">
            <div class="sparkline" data-url="<?= SITE_ADDRESS.$stat['url'] ?>"></div>
            <div class="data">
                <h2 data-value="<?= $stat['count'] ?>" data-format="<?= (isset($stat['format'])) ? $stat['format'] : '' ?>"><?= $stat['count'] ?></h2>
                <small><?= $name ?></small>
            </div>
        </div>
    </div>
<? } ?>
</div>
<div class="clear"></div>
<div class="alert alert-info alert-narrow">
    * Lists update automatically every minute.
</div>
<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Pending Testimonials</h2>

        <div id="pending_testimonialsList" class="getList updateTimer" data-url="<?= base_url() ?>admin/getList/pending_testimonials">
            <span class="loading"></span>
        </div>
    </div>
</div>
<!--
<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Locked Members</h2>

        <div id="locked_membersList" class="getList updateTimer" data-url="<?= base_url() ?>admin/getList/locked_members">
            <span class="loading"></span>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Rejected Payments</h2>

        <div id="rejected_paymentsList" class="getList updateTimer" data-url="<?= base_url() ?>admin/getList/rejected_payments">
            <span class="loading"></span>
        </div>
    </div>
</div>
-->
<!--
<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Open Members Tickets</h2>

        <div id="user_ticketsList" class="getList updateTimer" data-url="<?= base_url() ?>support/getList/user_tickets?status=open">
            <span class="loading"></span>
        </div>
    </div>
</div>
<div class="clear"></div>
<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Open Guest Tickets</h2>

        <div id="guest_ticketsList" class="getList updateTimer" data-url="<?= base_url() ?>support/getList/guest_tickets?status=open">
            <span class="loading"></span>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Pending Text Ads</h2>

        <div id="pending_text_adsList" class="getList updateTimer" data-url="<?= base_url() ?>admin/getList/pending_text_ads">
            <span class="loading"></span>
        </div>
    </div>
</div>


<script>
    var refresh_int = 60 * 1000;
    setInterval(function () {
        $('div.updateTimer').each(function () {
            var url = $(this).attr('data-url');
            var e = $(this);
            if (url) {
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        e.html(data);
                    }
                });
            }
        });

    }, refresh_int);
</script>
<? if ($ajax) { ?>
    <script>
        $('div.sparkline').each(function () {
            var url = $(this).attr('data-url');

            var e = $(this);
            if (url) {
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    success: function (data) {
                        var vals = consumeJSONData(data);
                        e.sparkline(vals, {
                            type: 'line',
                            width: '100%',
                            height: '65',
                            lineColor: 'rgba(255,255,255,0.4)',
                            fillColor: 'rgba(0,0,0,0.2)',
                            lineWidth: 1.25
                        });
                    }
                });
            }
        });


    </script>
<? } ?>
