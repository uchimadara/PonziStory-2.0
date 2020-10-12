$(document).ready(function () {
    procAdStats();
});

function procAdStats() {

    $('#adStat').find('.sparkline').each(function () {
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

    $('#adStat').find('.stat-chart').each(function () {
        var target = $(this).find('h2');

        var toAnimate = target.attr('data-value');
        // Animate the element's value from x to y:
        $({someValue: 0}).animate({someValue: toAnimate}, {
            duration: 1000,
            easing: 'swing', // can be anything
            step: function () { // called on every step
                // Update the element's text with rounded-up value:
                target.text(commaSeparateNumber(Math.round(this.someValue)));
            }
        });

        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
            return val;
        }
    });

}
function consumeJSONData(sparkData) {
    var i = 0;
    var vals = new Array();
    for (var key in sparkData) {
        vals[i++] = parseInt(sparkData[key]);
    }
    return vals;
}
