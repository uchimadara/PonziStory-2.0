function colSort(e, url) {
    var col = $(e);
    var container = col.parents('div.rms-sortable'),
        table = container.find('table');

    if (table.length) obj = table;
    else if (container.length) obj = container;

    overlay.show(obj);

    $.get(url, function (data) {
        container.html(data);
        overlay.hide();
    });
}

$(document).ready(function(){
    $(document).delegate('a.remove', 'click', function (e) {
        e.preventDefault();

        if (confirm("Delete item. Please confirm.")) {

            var container = col.parents('div.rms-sortable'),
                table = container.find('table');

            if (table.length) obj = table;
            else if (container.length) obj = container;

            overlay.show(obj);

            $.get($(this).attr('href'), function (data) {
                $('#' + container).html(data);
            });

        }
    });

    $('div.getList').each(function(){
        var url = $(this).attr('data-url');

        var e = $(this);
        if (url)
            $.get(url, function (data) {
               e.html(data);
            });
    });
});