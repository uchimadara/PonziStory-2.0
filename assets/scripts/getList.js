function remove (e, url) {
    if (confirm("Delete item. Please confirm.")) {
        var container = $(e).parents('div.removable');

        obj = container;

        overlay.show(obj);

        $.get(url, function (data) {
            container.html(data);
            overlay.hide();
        });

    }
}

$(document).ready(function(){
    $(document).delegate('a.remove', 'click', function (e) {
        e.preventDefault();

        if (confirm("Delete item. Please confirm.")) {

            var link = $(this);
            var container = link.attr('data-div');

            $.get(link.attr('href'), function (data) {
                $('#' + container).html(data);
            });

        }
    });

    $('div.getList').each(function(){
        var url = $(this).attr('data-url');

        console.info('ready .getList url=',url);

        var e = $(this);
        if (url)
            $.get(url, function (data) {
               e.html(data);
            });
    });
});

function getList() {
    $('div.getList').each(function () {
        var e = $(this);
        var url = e.attr('data-url');

        console.info('func .getList url=',url);

        if (url)
            $.get(url, function (data) {
                e.html(data);
            });
    });

}