$(document).delegate('a.replace', 'click', function (e) {

    e.preventDefault();

    var link = $(this);

    var container = link.attr('data-div');
    var callback = link.attr('data-callback');
    var msg = link.attr('data-confirm');

    if (msg) {
        if (!confirm(msg)) return false;
    }
    var cObj = $('#' + container);

    var h=24;
    var toggle = true;
    if (cObj.css('display') != 'none') {
        h = cObj.outerHeight(true);
        toggle = false;
    }
    if (h < 24) h = 24;

    cObj.html('<div id="to" style="width:95%;height:' + h + 'px;"><img src="' + mim.assetPath + 'images/loader.gif"></div>');

    if (toggle) cObj.slideToggle("fast");

    $('#to').find('img').css({
        position: 'relative',
        top: (h / 2) - 16,
        left: (cObj.outerWidth(true) / 2) - 16
    });

    $.get(link.attr('href'), function (data) {
        try {
            var jsonData = $.parseJSON(data);
            showError(link, jsonData.error);
        }
        catch (e) {
            cObj.html(data);
            if (typeof window[callback] === 'function') {
                window[callback]();
            }

        }
    });
});
var currentDisplay = null;
$(document).delegate('a.replaceClass', 'click', function (e) {

    e.preventDefault();

    var link = $(this);

    var container = link.attr('data-div');

    var cObj = $('#' + container);

    if (currentDisplay == container) {
        cObj.hide('fast');
        currentDisplay = null;
        return;
    }
    var dataId = link.attr('data-id');

    if (cObj.css('display') != 'none') {
        cObj.hide('fast');
        currentDisplay = null;
        return;
    }
    currentDisplay = container;


    var h = 70;
    var toggle = true;

    cObj.html('<div id="to" class="testoverlay" style="width:95%;height:' + h + 'px;"><img src="/images/loading_big.gif"></div>');

    if (toggle) cObj.slideToggle("fast");

    $('#to').find('img').css({
        position: 'relative',
        top: (h / 2) - 16,
        left: (cObj.outerWidth(true) / 2) - 16
    });

    $.get(link.attr('href'), function (data) {
        try {
            var jsonData = $.parseJSON(data);
            showError(link, jsonData.error);
        }
        catch (e) {
            cObj.html(data);
        }
    });
});
var stateObj = null;
$(document).delegate('a.replaceState', 'click', function (e) {

    e.preventDefault();

    var link = $(this);

    var container = link.attr('data-div');

    stateObj = $('#' + container);

    var h = 70;
    var toggle = true;
    if (stateObj.css('display') != 'none') {
        h = stateObj.outerHeight(true);
        toggle = false;
    }
    if (h < 70) h = 70;

    stateObj.html('<div id="to" class="testoverlay" style="width:95%;height:' + h + 'px;"><img src="/images/loading_big.gif"></div>');

    if (toggle) stateObj.slideToggle("fast");

    $('#to').find('img').css({
        position: 'relative',
        top: (h / 2) - 16,
        left: (stateObj.outerWidth(true) / 2) - 16
    });

    $.get(link.attr('href'), function (data) {
        var jsonData = $.parseJSON(data);
        if (jsonData.error) {
            showError(link, jsonData.error);
        } else {

            stateObj.html(jsonData.html);
            document.title = jsonData.pageTitle;
            window.history.pushState({}, jsonData.pageTitle, jsonData.url);
            //window.history.pushState(null, null, jsonData.url);

        }
    });
});
var formOpen = null;
$(document).delegate('.revealCancel', 'click', function (e) {
    e.preventDefault();

    if (formOpen) {
        formOpen.hide("blind", {easing: 'swing'}, 400,function () {
        }).html('');
        formOpen = null;
    }
});

$(document).delegate('a.reveal', 'click', function (e) {
    e.preventDefault();

    if (formOpen) {
        formOpen.hide("blind", {easing: 'swing'}, 400,function () {
        }).html('');
        formOpen = null;
    }

    var link = $(this);

    var container = $('#' + link.attr('data-div'));
    container.html('<div class="loading"></div>').show("blind", {easing: 'swing'}, 400, function () {
    });

    $.get(link.attr('href'), function (data) {
        try {
            var jsonData = $.parseJSON(data);
            showError(link, jsonData.error);
        }
        catch (e) {
            container.html(data);
            formOpen = container;
        }
    });

});
function loadContent(href) {

    var h = stateObj.outerHeight(true), toggle = false;
    if (h < 70) h = 70;

    stateObj.html('<div id="to" class="testoverlay" style="width:95%;height:' + h + 'px;"><img src="/images/loading_big.gif"></div>');

    $('#to').find('img').css({
        position: 'relative',
        top: (h / 2) - 16,
        left: (stateObj.outerWidth(true) / 2) - 16
    });

    $.get(href, function (data) {
        var jsonData = $.parseJSON(data);
        if (jsonData.error) {
            showError(link, jsonData.error);
        } else {
            stateObj.html(jsonData.html);
            document.title = jsonData.pageTitle;
        }
    });

}
$(window).bind("popstate", function (event) {
    if (stateObj) {
        if (event.originalEvent.state != undefined) {
            stateObj.fadeOut(200, function() {
                stateObj.html(event.originalEvent.state.html).fadeIn(200);
            });
        } else {
            loadContent(location.href)
        }
    }
});