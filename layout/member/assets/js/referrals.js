$(document).ready(function () {

    $('#ref-search').bind("keypress", function (e) {
        if (e.keyCode == 13) {

            var terms = $('#ref-search').val(),
                cObj = $('#refList'),
                h = cObj.outerHeight(true);

            if (terms.length < 3) {
                alert ('Search 3 characters min.');
            } else {
                if (h < 50) h = 50;

                $('#refListHeader').html('Searching...');
                cObj.html('<div id="to" style="width:95%;height:' + h + 'px;"><img src="' + mim.assetPath + 'images/loader.gif"></div>');

                $('#to').find('img').css({
                    position: 'relative',
                    top: (h / 2) - 16,
                    left: (cObj.outerWidth(true) / 2) - 16
                });

                $.ajax({
                    url: mim.baseUrl + 'ajax/referrals/search',
                    type: 'post',
                    data: {
                        terms: terms
                    },
                    dataType: 'json',
                    success: function (response) {
                        $('#refListHeader').html(response.total);
                        cObj.html(response.html);
                    }
                });

            }
            return false; // prevent the button click from happening
        }
    });
});