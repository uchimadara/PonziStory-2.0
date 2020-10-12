var modal = (function(){
    var
    method = {},
    overlay,
    modal,
    modalContent,
    contentUrl,
    modalClose;

    // Center the modal in the viewport
    method.center = function (relTop) {
        var top, left;

        if (relTop == undefined) {
            top = Math.abs($(window).height() - modal.outerHeight()) / 2;
        }  else {
            top = relTop;
        }

        left = Math.abs($(window).width() - modal.outerWidth()) / 2;

        modal.css({
            top: top+'px',
            left: left+'px'
        });
    };

    // Open the modal
    method.open = function (settings) {
        $('body').addClass('modal-open');

        modalContent.html(settings.content);

        modal.css({
            width: settings.width || 'auto',
            height: settings.height || 'auto'
        });

        method.center(settings.top);

        $(window).bind('resize.modal', method.center);

        modal.show();
        overlay.show();
    };

    method.openUrl = function (settings) {
        $('body').addClass('modal-open');

        modalContent.html('<div class="loading"></div>');

        modal.css({
            width: settings.width || 'auto',
            height: settings.height || 'auto'
        });

        method.center(settings.top);

        $(window).bind('resize.modal', method.center);

        modal.show();
        overlay.show();

        $.get(settings.contentUrl, function (data) {
            try {
                var jsonData = $.parseJSON(data);
                showError(obj, jsonData.error);
            }
            catch (e) {
                modalContent.html(data);
                method.center(settings.top);

            }
        });

    };

    // Close the modal
    method.close = function () {
        $('body').removeClass('modal-open');

        modal.hide();
        overlay.hide();
        modalContent.empty();
        $(window).unbind('resize.modal');
    };

    // Generate the HTML and add it to the document
    overlay = $('<div id="peOverlay"></div>');
    modal = $('<div id="peModal"></div>');
    modalContent = $('<div id="peModalContent"></div>');
    modalClose = $('<a id="peModalClose" href="#">close</a>');

    modal.hide();
    overlay.hide();
    modal.append(modalContent, modalClose);

    $(document).ready(function(){
        $('body').append(overlay, modal);
    });

    modalClose.click(function(e){
        e.preventDefault();
        method.close();
    });

    return method;
}());

var closeModal = function() {
  modal.close();
}

function showError(obj, message){
    if ($('#errorDiv').length > 0) {
        $('#errorDiv').html(message).fadeIn('fast');
    } else {

        $('<div id="errorDiv">')
            .css({
                'top': (obj.offset().top + obj.outerHeight()) + 'px',
                'left': obj.offset().left + 'px'
            })
            .html(message)
            .appendTo('body')
            .fadeIn('fast')
            .animate({opacity: 1.0}, 2000)
            .fadeOut('fast', function() {
                $(this).remove();
            });
    }
}

$('body').on('hidden.bs.modal', '.modal', function () {
    $('#modalTitle').html('');
    $('#modalBody').html('<div class="loading"></div>');
    $('#modalDialog').removeClass('modal-lg');
});


$(document).ready(function(){

    $(document).delegate('a.modalPopup', 'click', function (e) {
        e.preventDefault();

        var $this = $(this);
        var href = $this.attr('href');
        var $target = $($this.attr('data-target')); //strip for ie7

        $target
            .modal()
            .one('hide', function () {
                $this.is(':visible') && $this.focus()
            });

        $.ajax({
            url: href,
            type: 'get',
            dataType: 'json',
            success: function (data) {
                $('#modalTitle').html(data.title);
                $('#modalBody').html(data.body);
            },
            error: function (request, status, error) {
                $('#modalTitle').html('Error');
                $('#modalBody').html(error);
            }
        });
    });

    var popping = false;
    $(document).delegate('a.popup', 'click', function(e){
        if (popping) return; // prevent double click
        e.preventDefault();
        popping = true;
        var $this = $(this);
        var href = $this.attr('href');
        var title = $this.attr('title');
        var $target = $('#modal'); //strip for ie7

        $target
            .modal({backdrop: 'static'})
            .one('hide', function () {
                $this.is(':visible') && $this.focus()
            });

        $('#modalTitle').html(title);
        if ($this.hasClass('wider')) $('#modalDialog').addClass('modal-lg');

        $.get($this.attr('href'), function(data){
            popping = false;
            try {
                var jsonData = $.parseJSON(data);
                showError(link, jsonData.error);
            }
            catch(e) {
                $('#modalBody').html(data);
            }
        });
    });

    $(document).delegate('a.popupImg', 'click', function (e) {
        e.preventDefault();

        var link = $(this);
        modal.close();
        modal.open({content: '<img src="'+link.attr('href')+'" />'});
    });

    if (mim.userState == 'guest') {
        $("a.validate").each(function(){
            $(this).attr('href', mim.baseUrl+'login.html?redirect=' + $(this).attr('href')).removeClass('validate');
            if (!mim.isMobile)
                $(this).addClass('popup');
        });
    }
});
function popup(e) {
    var $this = $(e);
    var href = $this.attr('href');
    var title = $this.attr('title');
    var $target = $('#modal'); //strip for ie7

    $target
        .modal('toggle', this)
        .one('hide', function () {
            $this.is(':visible') && $this.focus()
        });

    $('#modalTitle').html(title);

    $.get(url, function (data) {
        try {
            var jsonData = $.parseJSON(data);
            showError(link, jsonData.error);
        }
        catch (e) {
            $('#modalBody').html(data);
        }
    });

}
