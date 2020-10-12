$(document).ready(function(){
    var timer = null;

    $(document).delegate('input.searchList', 'keyup', function() {
        var input   = $(this),
            url     = input.attr('url'),
            search  = input.val(),
            content = input.attr('data-div');

       // if (search.length < 3) return;

        clearTimeout(timer);

        timer = setTimeout(function(){
            $.ajax({
                url:  url,
                data: { keywords: search },
                type: 'get',
                success: function(data){
                    $('#'+content).html(data);
                }
            });
        }, 500);
    });

    $(document).delegate('form.frmSearch', 'submit', function (e) {
        e.preventDefault();

        var form = $(this),
            id = form.attr('id'),
            container = $('#'+id.substr(0, id.length - 7)), // trim '-search' for list div id
            loading = $('div.loading', form),
            bottom = $('div.formBottom', form);

        bottom.hide();

        if (loading.length == 0) {
            form.append($('<div class="loading"></div>'));
            loading = $('div.loading', form);
        }
        loading.show();

        var h = container.outerHeight(true);
        if (h < 70) h = 70;

        container.html('<div id="to" class="testoverlay" style="width:95%;height:' + h + 'px;"><img src="/images/loading_big.gif"></div>');

       $('#to').find('img').css({
            position: 'relative',
            top: (h / 2) - 16,
            left: (container.outerWidth(true) / 2) - 16
        });

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serializeArray(),
             success: function (data) {

                container.html(data);
                loading.hide();
                bottom.show();

            },
            error: function (request, status, error) {
                //console.log(request, status, error);
                alert('Server connection error. Please try again.');
            },
            complete: function () {
                $("input[type='submit']", form).removeClass('disabled');
            }
        });
    });
});