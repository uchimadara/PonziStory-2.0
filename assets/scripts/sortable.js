function colSort(e, url) {
    var col = $(e);
    var container = col.parents('div.getList'),
        table = container.find('table');

    var h = 70;
    var w = 70;
    var toggle = true;
    if (container.css('display') != 'none') {
        h = container.outerHeight(true);
        w = container.outerWidth(true);
        toggle = false;
    }

    container.html('<div id="to" class="testoverlay" style="width:95%;height:' + h + 'px;"><img src="/images/loading_big.gif"></div>');

    $('#to').find('img').css({
        position: 'relative',
        top: (h / 2) - 16,
        left: (w / 2) - 16
    });

    if (toggle) container.slideToggle("fast");

    $.get(url, function (data) {
        container.html(data);
    });
}
function colSortPageSelect(e, url, perPage) {
    colSort(e, url.replace('%d', $(e).val()).replace('%d', perPage));
}

function colSortPerPageSelect(e, url) {
    return colSort(e, url.replace('%d', $(e).val()));
}
function promptRemove(e, url, msg) {
    if (msg = prompt(msg)) {

        $(e).html('<img src="' + mim.assetPath + 'images/redloading.gif" />');

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                message: msg
            },
            success: function (data) {
                if (data.error) {
                    alert(data.error);
                } else {

                    var tr = $(e).parent().parent();
                    if (data.tr !== undefined) {
                        $(tr).prev().remove();
                        $(tr).next().remove();
                    }
                    $(tr).remove();
                    if (data.replace !== undefined) {
                        for (el in data.replace) {
                            $('#' + el).html(data.replace[el]);
                        }
                    }

                }
            }
        });

    }

}

function removeRow(e, url, msg) {
    if (confirm(msg)) {

        $(e).html('<img src="'+mim.assetPath+'images/redloading.gif" />');

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function (data) {
                if (data.error) {
                    alert(data.error);
                } else {

                    var tr = $(e).parent().parent();
                    if (data.tr !== undefined) {
                        $(tr).prev().remove();
                        $(tr).next().remove();
                    }
                    $(tr).remove();
                    if (data.replace !== undefined) {
                        for (el in data.replace) {
                            $('#' + el).html(data.replace[el]);
                        }
                    }

                }
            }
        });

    }
}
