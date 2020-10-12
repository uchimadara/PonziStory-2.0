$(document).ready(function () {
    if ($(window).width() <= '767') {
        $('.move-to-bottom').clone().appendTo('#temp-bottom');
        $('#temp-bottom .move-to-bottom').removeClass('hidden-xs');
    }
    $('nav ul').tinyNav({
        header: 'Navigation'
    });
    $('#left li a').each(function (index, value) {
        $('#tinynav1').append($('<option>', {
            value: $(value).attr('href'),
            text: $(value).text()
        }));
    });
    $('.menu').tinyNav({
        header: 'Navigation'
    });

    getAlert = function () {
        var timestamp = (new Date).getTime(); // timestamp creates unique url for cache busting
        $.get(mim.baseUrl + 'ajax/alert/get/' + timestamp, function (data) {
            var jsonData = $.parseJSON(data),
                c = parseInt(jsonData.alert_count);

            if (c != 0) {
                if (c > mim.alertCount) {
                    sound(teAlert);
                }
                if (c != mim.alertCount) {
                   $('#notify-msg').html(jsonData.alert_message);
                    mim.alertCount = c;
                }

                $('#alertBell').addClass('blink');
                $('#notify').addClass('lit');

            } else {
                $('#alertBell').removeClass('blink');
                $('#notify').removeClass('lit');
                $('#notify-msg').html('');
            }

            if (mim.alertInterval > 0) {
                setTimeout(getAlert, mim.alertInterval);
            }

        });
    }

    // start alerts
    if (mim.alertInterval > 0) {
        setTimeout(getAlert, 1000);
    }

    if ($('#show-testimonial').length) {
        $(document).delegate('a#show-testimonial', 'click', function(e) {
            e.preventDefault();
            $('#add-testimonial').modal('show');

        });
        initScreenshot('testimonialForm');
    }
});

var teAlert = null;

$(document).ready(function () {
    if (mim.teAlert != 'none') {
        if (isOgg()) {
            teAlert = new Audio(mim.assetPath + "sounds/" + mim.teAlert + ".ogg");
        }
        else {
            teAlert = new Audio(mim.assetPath + "sounds/" + mim.teAlert + ".mp3");
        }
    }

    $('.alert-select').on('change', function () {
        var e = $(this), a = e.val();
        $.get(mim.baseUrl + 'member/setting/' + e.attr('name') + '/' + a, function (data) {
        });

        if (a == 'none') return;
        var s = null;
        if (isOgg()) {
            s = new Audio(mim.assetPath + "sounds/" + a + ".ogg");
        }
        else {
            s = new Audio(mim.assetPath + "sounds/" + a + ".mp3");
        }
        sound(s);
    });

    if ($('.countdown-display').length) {
        $('.countdown-display').each(function() {
            var t = $(this).attr('data-secs');
            $(this).countdown({until: +t})
        })
    }

});
function isOgg() {
    var a = document.createElement('audio');
    return !!(a.canPlayType && a.canPlayType('audio/ogg; codecs="vorbis"').replace(/no/, ''));
}

function sound(s) {
    if (s) {
        s.play();
    }
}
