function countTotal() {
    var total = 0;

    $('.fast_track_checkbox').each(function () {
        var cb = $(this),
                input = cb.closest('tr').find('input:text');

        total += cb.attr('checked') ? parseFloat(input.val()) : 0;
    });

    $('#total').html('$' + total);
}

function checkCashout(e, url) {
    var ref = prompt("Enter transaction ID");
    if (ref != null && ref != '') {
        var container = $('#' + e);

        container.html('<span class="loading">Submitting... <img src="' + mim.assetPath + 'images/loading.gif" /></span>');

        $.get(url + '/' + ref, function (data) {
            container.html(data);
        });

    }

}
function approveInvestment(e, url) {
    var refComm = prompt("How much ref. comm. did you receive on this investment?");
    if (refComm) {
//        var container = $('#' + e);
//
//        container.html('<span class="loading">Submitting... <img src="' + mim.assetPath + 'images/loading.gif" /></span>');

        $.get(url, function (data) {
            container.html(data);
            overlay.hide();
        });

    }
}

$(document).ready(function () {
    $('.datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
        prevText: '',
        nextText: '',
        dateFormat: 'yy-mm-dd',
        minDate: new Date(),
        maxDate: "+6m",
        showOn: "both",
        buttonImage: mim.assetPath + "images/calendar_icon.png",
        buttonImageOnly: true,
        showButtonPanel: false
    });

    $(document).on('click', 'a.confirm_row', function (e) {
        e.preventDefault();

        var link = $(this);


        if (confirm('are you sure?')) {
            link.html('<img src="' + mim.assetPath + 'images/redloading.gif" />');
            $.ajax({
                url: link.attr('href'),
                type: 'post',
                success: function () {
                    link.closest('tr').fadeOut('fast');
                }
            });
        }
    });

    if ($('#setNotify').length) {
        $('#setNotify').click(function (e) {
            e.preventDefault();
            $('#notify').val('1');
            document.resultForm.submit();
        });
    }

    $(document).on('click', 'a.addArticle', function (e) {
        e.preventDefault();
        var numArticles = parseInt($('#numArticles').val()) + 1;
        $('#numArticles').val(numArticles);

        var content = $('#articleContainer1').html();
        var newDiv = content.replace('article1', 'article' + numArticles)
                .replace('headline1', 'headline' + numArticles)
                .replace('image_url1', 'image_url' + numArticles)
                .replace('Headline 1', 'Headline ' + numArticles)
                .replace('Image URL 1', 'Image URL ' + numArticles)
                .replace('Article 1', 'Article ' + numArticles);

        $('#articles').append('<div class="rounded">' + newDiv + '</div>');

    });

    $('#accounts').on('click', ':checkbox', function () {
        $.ajax({
            url: mim.baseUrl + 'adminpanel/cashier/method_status',
            type: 'post',
            data: {
                account_id: $(this).attr('name'),
                enabled: $(this).prop('checked') ? 1 : 0
            },
            success: function (data) {
            }
        });
    });

    $(document).on('click', '#moreDetails', function (e) {
        var table = $('#moreDetailsTable');

        if (table.attr('class') == 'off') {
            $('#moreDetailsTable').hide(1000);
            table.removeClass('off');
            table.addClass('on');
        }
        else {
            $('#moreDetailsTable').show(1000);
            table.removeClass('on');
            table.addClass('off');
        }

        $('#moreDetailsTable').css('display', 'block');
    });

    $(document).on('click', '#buttonAddDeposit', function (e) {
        $.ajax({
            url: 'cashier/add_deposit',
            type: 'post',
            dataType: 'json',
            data: {
                amount: $('#amountDeposit').val(),
                userId: $('#user_id').val(),
                account_id: $('#account_id').val(),
                payment_code: $('#code_pm').val()
            },
            success: function (data) {
                if (data.errorElements != null) {
                    $('#amount').val(data.errorElements['amount']);
                    $('#amount').addClass('error');
                }
            }
        });
    });

    $(document).on('click', 'a.select_all', function (e) {
        e.preventDefault();

        $('.fast_track_checkbox').attr('checked', true);

        countTotal();
    });

    $(document).on('change', '.fast_track_checkbox', function () {
        countTotal();
    });

    $(document).delegate('form:not(.frm_ajax)', 'submit', function (e) {
        var form = $(this),
                submits = form.find('input[type="submit"]');

        submits.addClass('disabled');
    });

    $(document).delegate('input.disabled', 'click', function (e) {
        e.preventDefault();
        return false;
    });

    /* --------------------------------------------------------
     Admin menu drag&drop
     -----------------------------------------------------------*/
    // Return a helper with preserved width of cells
    var fixHelper = function (e, ui) {
        ui.children().each(function () {
            $(this).width($(this).width());
        });
        return ui;
    };

    $('ul#menu_sorting').sortable({
        helper: fixHelper,
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            var place = $(this).attr('place');
            // POST to server using $.post or $.ajax
            $.ajax({
                data: data,
                type: 'POST',
                url: '/adminpanel/admin_menu/sorting/'+ place
            });
        }
    }).disableSelection();
    
     $('table#submenu_sorting tbody').sortable({
        helper: fixHelper,
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            var place = $(this).parent().parent().parent().parent().parent().parent().parent().attr('place');
            var parent_id = $(this).attr('parent_id');
            // POST to server using $.post or $.ajax
            $.ajax({
                data: data,
                type: 'POST',
                url: '/adminpanel/admin_menu/sorting/'+ place +'/'+parent_id
            });
        }
    }).disableSelection();

});

