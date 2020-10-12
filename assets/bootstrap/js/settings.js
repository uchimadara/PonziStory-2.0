$(document).ready(function(){
    /* --------------------------------------------------------
     Template Settings
     -----------------------------------------------------------*/
    var $body = $('body');
    $body.on('click', '.template-skins > a', function (e) {
        e.preventDefault();
        var skin = $(this).attr('data-skin');
        $('body').attr('id', skin);
        $('#changeSkin').modal('hide');
        $.get(mim.baseUrl + 'member/setting/skin/' + skin, function (data) {
        });
    });

    $body.on('click', 'a#settingShortcuts', function (e) {
        e.preventDefault();

        var setting = 0,
            sa = $('.shortcut-area');

        if (sa.css('display') == 'none') setting = 1;
        sa.slideToggle(400);

        $.get(mim.baseUrl + 'member/setting/view_shortcuts/' + setting, function (data) {
        });

    });

    $('.settingSwitch').on('switch-change', function (e, data) {
        var $el = $(data.el),
            val = data.value,
            setting = $el.attr('data-url');

        if (val) val = '1';
        else val = '0';

        $.get(setting + val, function (data) {
        });

    });
     
    $(document).delegate('a.saveSetting', 'click', function (e) {
        e.preventDefault();

        var link = $(this),
            url = link.attr('href'),
            ctrl = link.attr('data-input'),
            setting = $('#'+ctrl).val(),
            content = link.html();

        link.html('<img src="'+mim.assetPath+'images/redloading.gif" />');
        $.get(url + setting, function (data) {
            link.html(content);
        });

    });

});



