<div class="col-lg-10">
    <div class="tile">
            <div class="p-10">
                <ul id="reportsToTree" class="filetree">
                    <?= $refTree ?>
                </ul>
            </div>

    </div>
</div>

<? if ($ajax) { ?>
    <script type="text/javascript">
        $('#reportsToTree').treeview();

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
