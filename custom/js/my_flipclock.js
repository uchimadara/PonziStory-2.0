$(document).ready(function () {
    if (typeof homepage != "undefined") {
        if (mim.hasOwnProperty('launchtime') && $('.my-flipclock').length) {
            if (mim.launchtime > 0) {
                var clock = $('.my-flipclock').FlipClock(mim.launchtime, {
                    countdown: true
                });
            }
        }
    } else if (mim.hasOwnProperty('countdown') && $('.my-flipclock').length) {
        if (mim.countdown > 0) {
            var clock = $('.my-flipclock').FlipClock(mim.countdown, {
                countdown: true
            });
        }
    }
});