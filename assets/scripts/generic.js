$(document).ready(function() {

    $('.dynamicGet').each(function(e) {
        var $this = $(this);
        $.get($this.attr('data-url'), function (data) {
            $this.html(data);
        });

    })


});
