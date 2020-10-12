Number.prototype.toMoney = function (n) {
    var s = parseFloat(this.toFixed(5)).toString();
    if (s.indexOf('.') == -1)
        s += '.';
    while (s.length <= s.indexOf('.') + 2)
        s += '0';
    return '$' + s;
}
$(document).ready(function () {

    /*----------------------------------
     Dynamic Upates
     -----------------------------------*/

    getMessageCount = function () {
        var timestamp = (new Date).getTime();
        $.get(mim.baseUrl + 'update/message_count/' + timestamp, function (data) {
            var jsonData = $.parseJSON(data),
                    c = parseInt(jsonData.msg_count);

            if (c != mim.messageCount) {
                if (c != 0) {
                    $('#userMessageCount').html('<i class="n-count animated">' + jsonData.msg_count + '</i>');
                    var msgs = $('#userMessages');
                    msgs.html('<div class="loading"></div>');
                    $.get(mim.baseUrl + 'update/user_messages', function (data) {
                        msgs.html(data);
                    });
                } else {
                    $('#userMessages').html('<p class="p-10">No news</p>');
                    $('#userMessageCount').html('');
                }
                mim.messageCount = c;
            }

            c = parseInt(jsonData.news_count);
            if (c != mim.newsCount) {
                if (c != 0) {
                    $('#newsCount').html('<i class="n-count animated">' + jsonData.news_count + '</i>');
                    var news = $('#news');
                    news.html('<div class="loading"></div>');
                    $.get(mim.baseUrl + 'update/news', function (data) {
                        news.html(data);
                    });
                } else {
                    $('#newsCount').html('');
                    $('#news').html('<p class="p-10">No news</p>');
                }
                mim.newsCount = c;
            }

            c = parseInt(jsonData.surf_count);
            if (c != 0) {
                if (c > mim.surfCount) {
                    sound(teAlert);
                }
                if (c != mim.surfCount) {
                    $('#surfCount').html('<i class="n-count animated">' + jsonData.surf_count + '</i>');
                    mim.surfCount = c;
                }
                $('#surfListings').html(jsonData.surf_listings);
            } else {
                $('#surfCount').html('');
                $('#surfListings').html('<p class="p-10">No listings</p>');
            }

            c = parseInt(jsonData.prv_count);
            if (c != mim.prvCount) {
                if (c != 0) {
                    $('#userMessagePrivateCount').html('<i class="n-count animated">' + jsonData.prv_count + '</i>');
//                    var prvmsgs = $('#userMessagesPrivate');
//                    prvmsgs.html('<div class="loading"></div>');
//                    $.get(mim.baseUrl + 'update/prvmessages', function (data) {
//                        prvmsgs.html(data);
//                    });
                } else {
                    $('#userMessagesPrivate').html('<p class="p-10">No messages</p>');
                    $('#userMessagePrivateCount').html('');
                }
                mim.prvCount = c;
            }

            setTimeout(getMessageCount, mim.messageCountInterval);
        });
    };

    // NEWS and MESSAGE alerts
    if (mim.messageCountInterval > 0) {
        setTimeout(getMessageCount, mim.messageCountInterval);
    }

    if ($('#newsModal').length) {
        setTimeout(function () {
            $('#new_news').modal('show');
            setTimeout(function () {
                $('#newsModalFooter').fadeIn();
            }, 5000);
        }, 2000);
    }
    if ($('#loginAds').length) {
        var loginadTime = 10;
        setTimeout(function () {
            $('#todayAd').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');

            var loginadInterval = setInterval(function () {
                //$('#loginadTimer').html(loginadTime);
                loginadTime = loginadTime - 1;

                if (loginadTime == 0) {
                    //$('#loginadTime').hide();
                    clearInterval(loginadInterval);
                    $('#loginAdsFooter').fadeIn();
                }
            }, 1000);
        }, 200);
    }
    if ($('#listingWizard').length) {
        setTimeout(function () {
            $('#listingWizard').modal({backdrop: 'static'});
        }, 2000);
    }

    if ($('#reportsToTree').length)
        $('#reportsToTree').treeview();
    $('.tip').tooltipsy();

    $('.mark_news_read').click(function () {
        $('.mark_news_read_msg').html('<span class="loading"></span>');
        var url = mim.baseUrl + 'forum/mark_read_category';
        $.get(url, function (data) {
            $('.mark_news_read_msg').html(data);
            $('.mark_news_read_msg').show('fast');
            $('.mark_news_read_msg').delay(7000).hide(2000);
            $('#news').html('<div class="p10">Nothing new.</div>');
        });
        return false;
    });

    /*------------
     Member dashboard
     */
    function drawCharts(div, force) {
        div.find('.sparkline').each(function () {
            var url   = $(this).attr('data-url'),
                items = $(this).attr('data-items'),
                ar    = items ? items.split(",") : null;

            if (ar && force === undefined) {
                for (var i = 0; i < ar.length; i++)
                    ar[i] = +ar[i];

                $(this).sparkline(ar, {
                    type: 'line',
                    width: '100%',
                    height: '65',
                    lineColor: 'rgba(255,255,255,0.4)',
                    fillColor: 'rgba(0,0,0,0.2)',
                    lineWidth: 1.25
                });
            }
            else {
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
            }
        });
        div.find('.stat-pie').each(function () {
            $(this).easyPieChart({
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
        });
        /* --------------------------------------------------------
         Animate numbers
         -----------------------------------------------------------*/
        div.find('.stat-chart').each(function () {
            var target = $(this).find('h2');
            var format = target.attr('data-format');

            var toAnimate = $(this).find('h2').attr('data-value');
            // Animate the element's value from x to y:
            $({someValue: 0}).animate({someValue: toAnimate}, {
                duration: 1000,
                easing: 'swing', // can be anything
                step: function () { // called on every step
                    // Update the element's text with rounded-up value:

                    target.text(format + commaSeparateNumber(Math.round(this.someValue)));
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

    if ($('#myStats').length)
        drawCharts($('#myStats'));

    /* --------------------------------------------------------
     Components
     -----------------------------------------------------------*/
    (function () {
        /* Textarea */
        if ($('.auto-size')[0]) {
            $('.auto-size').autosize();
        }

        //Select
        if ($('.select')[0]) {
            $('.select').selectpicker();
        }

        //Sortable
        if ($('.sortable')[0]) {
            $('.sortable').sortable();
        }

        //Tag Select
        if ($('.tag-select')[0]) {
            $('.tag-select').chosen();
        }

        /* Tab */
        if ($('.nav-tab')[0]) {
            $('.tab').tabs();
            $('.tab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
                if (typeof drawCharts == 'function') {
                    drawCharts($($(this).attr('href')), true);
                }
            });
        }
        if ($('.tab')[0]) {
            $('.tab').tabs();
            $('.tab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
                if (typeof drawCharts == 'function') {
                    drawCharts($($(this).attr('href')), true);
                }
            });
        }
        if (typeof drawCharts == 'function') {
            drawCharts($('#siteStats'));
        }
        if (typeof drawCharts == 'function') {
            drawCharts($('#membersStats'));
        }

        /* Tab AJAX */
        if ($('.tabAjax').length) {
            $('.tabAjax').tabs();
            var url = $('.tabAjax').next().attr('data-url');
            if (typeof startTab == 'string') {
                $.ajax({
                    type: "GET",
                    url: url + "/" + startTab,
                    success: function (data) {
                        $('#tab'+startTab).html(data);
                    }
                });
            }
            $('.tabAjax a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
                tabId = $(this).attr("href");
                urlId = tabId.substr(4);
                $.ajax({
                    type: "GET",
                    url: url + "/" + urlId,
                    success: function (data) {
                        $(tabId).html(data);
                    }
                });
            });
        }

        /* Collapse */
        if ($('.collapse')[0]) {
            $('.collapse').collapse();
        }

        /* Accordion */
        $('.panel-collapse').on('shown.bs.collapse', function () {
            $(this).prev().find('.panel-title a').removeClass('active');
        });

        $('.panel-collapse').on('hidden.bs.collapse', function () {
            $(this).prev().find('.panel-title a').addClass('active');
        });

        //Popover
        if ($('.pover')[0]) {
            $('.pover').popover();
        }
    })();

    /* --------------------------------------------------------
     Sidebar + Menu
     -----------------------------------------------------------*/
    (function () {
        /* Menu Toggle */
        $('body').on('click touchstart', '#menu-toggle', function (e) {
            e.preventDefault();
            $('html').toggleClass('menu-active');
            $('#sidebar').toggleClass('toggled');
            //$('#content').toggleClass('m-0');
        });

        /* Active Menu */
        /* Active Menu */
        $('#sidebar .menu-item').hover(function () {
            $(this).closest('.dropdown').addClass('hovered');
        }, function () {
            $(this).closest('.dropdown').removeClass('hovered');
        });

        $('li.dropdown').hover(function () {
            $(this).find('.pull-right').html('<i class="fa fa-angle-down" aria-hidden="true"></i>');
        }, function () {

            $(this).find('.pull-right').html('<i class="fa fa-angle-right" aria-hidden="true"></i>')
        });

        /* Prevent */
        $('.side-menu .dropdown > a').click(function (e) {
            e.preventDefault();
        });


    })();

    /* --------------------------------------------------------
     Chart Info
     -----------------------------------------------------------*/
    (function () {
        $('body').on('click touchstart', '.tile .tile-info-toggle', function (e) {
            e.preventDefault();
            $(this).closest('.tile').find('.chart-info').toggle();
        });
    })();

    /* --------------------------------------------------------
     Todo List
     -----------------------------------------------------------*/
    (function () {
        setTimeout(function () {
            //Add line-through for alreadt checked items
            $('.todo-list .media .checked').each(function () {
                $(this).closest('.media').find('.checkbox label').css('text-decoration', 'line-through')
            });

            //Add line-through when checking
            $('.todo-list .media input').on('ifChecked', function () {
                $(this).closest('.media').find('.checkbox label').css('text-decoration', 'line-through');
            });

            $('.todo-list .media input').on('ifUnchecked', function () {
                $(this).closest('.media').find('.checkbox label').removeAttr('style');
            });
        })
    })();

    /* --------------------------------------------------------
     Custom Scrollbar
     -----------------------------------------------------------*/
    (function () {
        if ($('.overflow')[0]) {
            var overflowRegular, overflowInvisible = false;
            overflowRegular = $('.overflow').niceScroll();
        }
    })();

    /* --------------------------------------------------------
     Messages + Notifications
     -----------------------------------------------------------*/
    (function () {
        $('body').on('click touchstart', '.drawer-toggle', function (e) {
            e.preventDefault();
            var drawer = $(this).attr('data-drawer');

            $('.drawer:not("#' + drawer + '")').removeClass('toggled');

            if ($('#' + drawer).hasClass('toggled')) {
                $('#' + drawer).removeClass('toggled');
            }
            else {
                $('#' + drawer).addClass('toggled');
            }
        });

        //Close when click outside
        $(document).on('mouseup touchstart', function (e) {
            var container = $('.drawer, .tm-icon');
            if (container.has(e.target).length === 0) {
                $('.drawer').removeClass('toggled');
                $('.drawer-toggle').removeClass('open');
            }
        });

        //Close
        $('body').on('click touchstart', '.drawer-close', function () {
            $(this).closest('.drawer').removeClass('toggled');
            $('.drawer-toggle').removeClass('open');
        });
    })();


    /* --------------------------------------------------------
     Calendar
     -----------------------------------------------------------*/
    (function () {

        //Sidebar
        if ($('#sidebar-calendar')[0]) {
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            $('#sidebar-calendar').fullCalendar({
                editable: false,
                events: [],
                header: {
                    left: 'title'
                }
            });
        }

        //Content widget
        if ($('#calendar-widget')[0]) {
            $('#calendar-widget').fullCalendar({
                header: {
                    left: 'title',
                    right: 'prev, next',
                    //right: 'month,basicWeek,basicDay'
                },
                editable: true,
                events: [
                    {
                        title: 'All Day Event',
                        start: new Date(y, m, 1)
                    },
                    {
                        title: 'Long Event',
                        start: new Date(y, m, d - 5),
                        end: new Date(y, m, d - 2)
                    },
                    {
                        title: 'Repeat Event',
                        start: new Date(y, m, 3),
                        allDay: false
                    },
                    {
                        title: 'Repeat Event',
                        start: new Date(y, m, 4),
                        allDay: false
                    }
                ]
            });
        }

    })();

    /* --------------------------------------------------------
     RSS Feed widget
     -----------------------------------------------------------*/
    (function () {
        if ($('#news-feed')[0]) {
            $('#news-feed').FeedEk({
                FeedUrl: 'http://rss.cnn.com/rss/edition.rss',
                MaxCount: 5,
                ShowDesc: false,
                ShowPubDate: true,
                DescCharacterLimit: 0
            });
        }
    })();

    /* --------------------------------------------------------
     Chat
     -----------------------------------------------------------*/
    $(function () {
        $('body').on('click touchstart', '.chat-list-toggle', function () {
            $(this).closest('.chat').find('.chat-list').toggleClass('toggled');
        });

        $('body').on('click touchstart', '.chat .chat-header .btn', function (e) {
            e.preventDefault();
            $('.chat .chat-list').removeClass('toggled');
            $(this).closest('.chat').toggleClass('toggled');
        });

        $(document).on('mouseup touchstart', function (e) {
            var container = $('.chat, .chat .chat-list');
            if (container.has(e.target).length === 0) {
                container.removeClass('toggled');
            }
        });
    });

    /* --------------------------------------------------------
     Form Validation
     -----------------------------------------------------------*/
    (function () {
        if ($("[class*='form-validation']")[0]) {
            $("[class*='form-validation']").validationEngine();

            //Clear Prompt
            $('body').on('click', '.validation-clear', function (e) {
                e.preventDefault();
                $(this).closest('form').validationEngine('hide');
            });
        }
    })();

    /* --------------------------------------------------------
     `Color Picker
     -----------------------------------------------------------*/
    (function () {
        //Default - hex
        if ($('.color-picker')[0]) {
            $('.color-picker').colorpicker();
        }

        //RGB
        if ($('.color-picker-rgb')[0]) {
            $('.color-picker-rgb').colorpicker({
                format: 'rgb'
            });
        }

        //RGBA
        if ($('.color-picker-rgba')[0]) {
            $('.color-picker-rgba').colorpicker({
                format: 'rgba'
            });
        }

        //Output Color
        if ($('[class*="color-picker"]')[0]) {
            $('[class*="color-picker"]').colorpicker().on('changeColor', function (e) {
                var colorThis = $(this).val();
                $(this).closest('.color-pick').find('.color-preview').css('background', e.color.toHex());
            });
        }
    })();

    /* --------------------------------------------------------
     Date Time Picker
     -----------------------------------------------------------*/
//    (function () {
//        //Date Only
//        if ($('.date-only')[0]) {
//            $('.date-only').datetimepicker({
//                pickTime: false
//            });
//        }
//
//        //Time only
//        if ($('.time-only')[0]) {
//            $('.time-only').datetimepicker({
//                pickDate: false
//            });
//        }
//
//        //12 Hour Time
//        if ($('.time-only-12')[0]) {
//            $('.time-only-12').datetimepicker({
//                pickDate: false,
//                pick12HourFormat: true
//            });
//        }
//
//        $('.datetime-pick input:text').on('click', function () {
//            $(this).closest('.datetime-pick').find('.add-on i').click();
//        });
//    })();

    /* --------------------------------------------------------
     Input Slider
     -----------------------------------------------------------*/
    (function () {
        if ($('.input-slider')[0]) {
            $('.input-slider').slider().on('slide', function (ev) {
                $(this).closest('.slider-container').find('.slider-value').val(ev.value);
            });
        }
    })();

    /* --------------------------------------------------------
     WYSIWYE Editor + Markedown
     -----------------------------------------------------------*/
    (function () {
        //Markedown
        if ($('.markdown-editor')[0]) {
            $('.markdown-editor').markdown({
                autofocus: false,
                savable: false
            });
        }

        //WYSIWYE Editor
        if ($('.wysiwye-editor')[0]) {
            $('.wysiwye-editor').summernote({
                height: 200
            });
        }

    })();

    /* --------------------------------------------------------
     Media Player
     -----------------------------------------------------------*/
    (function () {
        if ($('audio, video')[0]) {
            $('audio,video').mediaelementplayer({
                success: function (player, node) {
                    $('#' + node.id + '-mode').html('mode: ' + player.pluginType);
                }
            });
        }
    })();

    /* ---------------------------
     Image Popup [Pirobox]
     --------------------------- */
    (function () {
        if ($('.pirobox_gall')[0]) {
            //Fix IE
            jQuery.browser = {};
            (function () {
                jQuery.browser.msie = false;
                jQuery.browser.version = 0;
                if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                    jQuery.browser.msie = true;
                    jQuery.browser.version = RegExp.$1;
                }
            })();

            //Lightbox
            $().piroBox_ext({
                piro_speed: 700,
                bg_alpha: 0.5,
                piro_scroll: true // pirobox always positioned at the center of the page
            });
        }
    })();

    /* ---------------------------
     Vertical tab
     --------------------------- */
    (function () {
        $('.tab-vertical').each(function () {
            var tabHeight = $(this).outerHeight();
            var tabContentHeight = $(this).closest('.tab-container').find('.tab-content').outerHeight();

            if ((tabContentHeight) > (tabHeight)) {
                $(this).height(tabContentHeight);
            }
        })

        $('body').on('click touchstart', '.tab-vertical li', function () {
            var tabVertical = $(this).closest('.tab-vertical');
            tabVertical.height('auto');

            var tabHeight = tabVertical.outerHeight();
            var tabContentHeight = $(this).closest('.tab-container').find('.tab-content').outerHeight();

            if ((tabContentHeight) > (tabHeight)) {
                tabVertical.height(tabContentHeight);
            }
        });


    })();

    /* --------------------------------------------------------
     Login + Sign up
     -----------------------------------------------------------*/
    (function () {
        $('body').on('click touchstart', '.box-switcher', function (e) {
            e.preventDefault();
            var box = $(this).attr('data-switch');
            $(this).closest('.box').toggleClass('active');
            $('#' + box).closest('.box').addClass('active');
        });
    })();


    /* --------------------------------------------------------
     MAC Hack
     -----------------------------------------------------------*/
    (function () {
        //Mac only
        if (navigator.userAgent.indexOf('Mac') > 0) {
            $('body').addClass('mac-os');
        }
    })();

    /* --------------------------------------------------------
     Photo Gallery
     -----------------------------------------------------------*/
    (function () {
        if ($('.photo-gallery')[0]) {
            $('.photo-gallery').SuperBox();
        }
    })();


});

$(window).load(function () {
    /* --------------------------------------------------------
     Tooltips
     -----------------------------------------------------------*/
    (function () {
        if ($('.tooltips')[0]) {
            $('.tooltips').tooltip();
        }
    })();

    /* --------------------------------------------------------
     Animate numbers
     -----------------------------------------------------------*/
    $('.quick-stats').each(function () {
        var target = $(this).find('h2');
        var toAnimate = $(this).find('h2').attr('data-value');
        // Animate the element's value from x to y:
        $({someValue: 0}).animate({someValue: toAnimate}, {
            duration: 1000,
            easing: 'swing', // can be anything
            step: function () { // called on every step
                // Update the element's text with rounded-up value:
                target.text(commaSeparateNumber(Math.round(this.someValue)));
            },
            complete: function() {
                target.text(commaSeparateNumber(toAnimate));
            }
        });

        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
            return val;
        }
    });


});

/* --------------------------------------------------------
 Date Time Widget
 -----------------------------------------------------------*/
(function () {
    //console.log(currentDateTime);
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]

    // Create a newDate() object
    var newDate = new Date();

    // Extract the current date from Date object
    newDate.setDate(newDate.getDate());

    // Output the day, date, month and year
    $('#date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

    setInterval(function () {

        // Create a newDate() object and extract the seconds of the current time on the visitor's
        var seconds = new Date().getSeconds();

        // Add a leading zero to seconds value
        $("#sec").html((seconds < 10 ? "0" : "") + seconds);
    }, 1000);

    setInterval(function () {

        // Create a newDate() object and extract the minutes of the current time on the visitor's
        var minutes = new Date().getMinutes();

        // Add a leading zero to the minutes value
        $("#min").html((minutes < 10 ? "0" : "") + minutes);
    }, 1000);
    var rememberMinutes = new Date().getMinutes();

    setInterval(function () {
        var minutes = new Date().getMinutes();
        var rememberSeconds = new Date().getSeconds();
        if (minutes == 0 && minutes < rememberMinutes) {

            rememberSeconds = new Date().getSeconds();
            // increase Hours
            if (rememberSeconds == 0)
            {
                // block minutes limiter
                rememberMinutes = new Date().setMinutes(1);
                var newHour = currentDateTime++;
                // Add a leading zero to the hours value
                $("#hours").html((currentDateTime < 10 ? "0" : "") + newHour);
            }
        }
    }, 1000);
})();
// WYSIWYG Editor
function init_wysibb(e) {
    e.wysibb({
        buttons: "bold,italic,underline,|,img,|,bullist,numlist,|,fontcolor,fontsize,fontfamily,|,justifyleft,justifycenter,justifyright,|,quote,removeFormat,smilebox",
        smileList: [
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/smile.png" class="sm">', bbcode: ":)"},
            {title: CURLANG.sm1, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/happy.png" class="sm">', bbcode: ":happy:"},
            {title: CURLANG.sm1, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/laughing.png" class="sm">', bbcode: ":lol:"},
            {title: CURLANG.sm8, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/sad.png" class="sm">', bbcode: ":("},
            {title: CURLANG.sm1, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/grin.png" class="sm">', bbcode: ":D"},
            {title: CURLANG.sm3, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/wink.png" class="sm">', bbcode: ";)"},
            {title: CURLANG.sm4, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/cool.png" class="sm">', bbcode: "8-)"},
            {title: CURLANG.sm5, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/angry.png" class="sm">', bbcode: ":grr:"},
            {title: CURLANG.sm6, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/shocked.png" class="sm">', bbcode: ":O"},
            {title: CURLANG.sm7, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/angel.png" class="sm">', bbcode: ":angel:"},
            {title: CURLANG.sm6, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/sick.png" class="sm">', bbcode: ":sick:"},
            {title: CURLANG.sm6, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/ermm.png" class="sm">', bbcode: ":ermm:"},
            {title: CURLANG.sm6, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/silly.png" class="sm">', bbcode: ":silly:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/cwy.png" class="sm">', bbcode: ":'("},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/alien.png" class="sm">', bbcode: ":alien:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/blink.png" class="sm">', bbcode: ":blink:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/blush.png" class="sm">', bbcode: ":blush:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/cheerful.png" class="sm">', bbcode: ":cheerful:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/heart.png" class="sm">', bbcode: "<3"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/tongue.png" class="sm">', bbcode: ":P"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/dizzy.png" class="sm">', bbcode: ":dizzy:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/getlost.png" class="sm">', bbcode: ":getlost:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/dizzy.png" class="sm">', bbcode: ":dizzy:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/kissing.png" class="sm">', bbcode: ":kissing:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/ninja.png" class="sm">', bbcode: ":ninja:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/pinch.png" class="sm">', bbcode: ":pinch:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/pouty.png" class="sm">', bbcode: ":pouty:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/dizzy.png" class="sm">', bbcode: ":dizzy:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/sideways.png" class="sm">', bbcode: ":sideways:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/sleeping.png" class="sm">', bbcode: ":sleeping:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/unsure.png" class="sm">', bbcode: ":unsure:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/w00t.png" class="sm">', bbcode: ":woot:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/wassat.png" class="sm">', bbcode: ":wassat:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/whistling.png" class="sm">', bbcode: ":whistling:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/wub.png" class="sm">', bbcode: ":love:"},
            {title: CURLANG.sm9, img: '<img src="' + mim.assetPath + 'images/forum/emoticons/devil.png" class="sm">', bbcode: ":devil:"}
        ]
    });

}

