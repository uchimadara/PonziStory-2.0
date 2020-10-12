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
                    var chart = AmCharts.makeChart(e.attr('id'), {
                        "type": "serial",
                        "addClassNames": true,
                        "theme": "light",
                        "pathToImages": mim.assetPath+"amcharts/images/",
                        "autoMargins": false,
                        "marginLeft": 30,
                        "marginRight": 8,
                        "marginTop": 10,
                        "marginBottom": 26,
                        "balloon": {
                            "adjustBorderColor": false,
                            "horizontalPadding": 10,
                            "verticalPadding": 8,
                            "color": "#ffffff"
                        },

                        "dataProvider": data,
                        "valueAxes": [
                            {
                                "axisAlpha": 0,
                                "position": "left"
                            }
                        ],
                        "startDuration": 1,
                        "graphs": [
                            {
                                "alphaField": "alpha",
                                "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                                "fillAlphas": 1,
                                "title": "Clicks",
                                "type": "column",
                                "valueField": "Clicks",
                                "dashLengthField": "dashLengthColumn"
                            },
                            {
                                "id": "graph2",
                                "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                                "bullet": "round",
                                "lineThickness": 3,
                                "bulletSize": 7,
                                "bulletBorderAlpha": 1,
                                "bulletColor": "#FFFFFF",
                                "useLineColorForBulletBorder": true,
                                "bulletBorderThickness": 3,
                                "fillAlphas": 0,
                                "lineAlpha": 1,
                                "title": "Views",
                                "valueField": "Views"
                            }
                        ],
                        "categoryField": "Date",
                        "categoryAxis": {
                            "gridPosition": "start",
                            "axisAlpha": 0,
                            "tickLength": 0
                        },
                        "responsive": {
                            "enabled": true
                        },
                        "export": {
                            "enabled": false,
                        }
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
