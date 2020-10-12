<? foreach ($quickStats as $name => $stat) { ?>

<div class="col-md-3 col-xs-6">
    <div class="tile quick-stats">
        <div class="sparkline pull-left" data-url="<?= SITE_ADDRESS.$stat['url'] ?>"></div>
        <div class="data">
            <h2 data-value="<?= $stat['count'] ?>">0</h2>
            <small><?=$name?></small>
        </div>
    </div>
</div>

<? } ?>
<? if (isset($pieCharts)) { ?>
        <div class="clear"></div>
<div class="col-md-6 col-xs-6">
    <div class="tile text-center">
        <div class="tile-dark p-10">

    <? foreach ($pieCharts as $title => $percent) { ?>
            <div class="pie-chart-tiny" data-percent="<?= $percent ?>">
                <span class="percent"></span>
                <span class="pie-title"><?=$title?><!-- <i class="m-l-5 fa fa-retweet"></i> --></span>
            </div>
    <? } ?>
        </div>
    </div>
</div>
<? } ?>

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
                        lineWidth: 1.25,
                    });
                }
            });
        }
    });
    $('.pie-chart-tiny').easyPieChart({
        easing: 'easeOutBounce',
        barColor: 'rgba(255,255,255,0.75)',
        trackColor: 'rgba(0,0,0,0.3)',
        scaleColor: 'rgba(255,255,255,0.3)',
        lineCap: 'square',
        lineWidth: 4,
        size: 100,
        animate: 3000,
        onStep: function (from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });

</script>
<? } ?>