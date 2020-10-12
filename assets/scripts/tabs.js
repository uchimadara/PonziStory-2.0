/********** Tabs *************/
var curTabAjax = null;

function refreshTab(tabHref, ajaxAndChangeNow) {
    ajaxAndChangeNow = ajaxAndChangeNow || false;

    //refreshTab( $('.tab-ajax[href="#Active"]') );
    //$(dom).removeAttr('data-dont-refresh');

    $('.tab-ajax[href="'+tabHref+'"]').removeAttr('data-dont-refresh');
    $(tabHref).html('<div class="loading"></div>');
    if( ajaxAndChangeNow ) {
        $('.tab-ajax[href="'+tabHref+'"]').click();
    }
}

function displayTab($this){

    if( $this.hasClass('tab-manual-refresh') ) {
        if( $this.attr('data-dont-refresh') ) {
            $this.tab('show');
            return;
        }
    }

    $this.tab('show');
    tabId = $this.attr("href");
    url = $this.attr('data-url');
    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {
            $(tabId).html(data);
            if( $this.hasClass('tab-manual-refresh') ) {
                $this.attr('data-dont-refresh', 1);
            }
        }
    });
}


$(document).ready(function(){
    $('.nav-tabs').tabs();

    $(document).delegate('a.nav-tab', 'click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    if ($('.tab-ajax').length) {

        var hash = document.location.hash;
        if (hash != "") {
            $('.tab-ajax').each(function(){
                if ($(this).attr('href') == hash) {
                    displayTab($(this));
                    $(this).parent('li').addClass('active');
                } else {
                    $(this).parent('li').removeClass('active');
                }
            });
        } else {
            displayTab($('.tab-ajax:first'));
        }
    }
    $(document).delegate('a.tab-ajax', 'click', function (e) {
        e.preventDefault();
        if (curTabAjax) {
            if( $(this).attr('data-dont-refresh') ) {
                $(curTabAjax.attr('href')).html('<div class="loading"></div>');
            }
        }
        curTabAjax = $(this);
        displayTab( curTabAjax );
        location.hash = $(this).attr('href');

    });


});