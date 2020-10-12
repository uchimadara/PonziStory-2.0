function checkBannerURL() {

    var url = $('#bannerImageURL').val(),
    form = $('#listingForm');

    if (url.length < 18) return false;
    if (!validateURL(url)) return false;

    var ext = url.substr(url.length - 3);

    if (ext != 'jpg' && ext != 'gif' && ext != 'png') return false;

    var img_loading = $('#bannerImgUploadLoading');
    var container = $('div#listingForm');
    $('div.formBottom', container).hide();
    $('#sampleBannerImg').hide();

    $('#banner_upload').hide();
    $('#banner_image').hide();
    img_loading.removeClass('hidden').show();
    $.ajax({
        url: mim.baseUrl + 'member/test_banner',
        type: 'post',
        data: {
            imageURL: url
        },
        dataType: 'json',
        success: function (response) {
            img_loading.hide();
            $('div.formBottom', container).show();
            if (response.error) {
                var el = 'bannerImageURL';
                var label = $('label[for=' + el + ']', form),
                    formElement = $('#' + el, form);

                formElement.addClass('error');
                label.append('<span class="element_error">' + response.error + '</span>');
                $('#sampleBannerImg').show();
            }
            else {
                $('#sampleBannerImg').html('<img src="' + response.banner + '" /><input id="banner_img" type="hidden" name="image" value="' + response.file + '" /><br/>').show();
            }
        }
    });
}
function checkBannerMultisizeURL(size) {
    var url = $('#bannerImageURL').val(),
        form = $('#banner_adForm'),
        bsize = size;

    if (url.length < 18) return false;
    if (!validateURL(url)) return false;

    var ext = url.substr(url.length - 3);

    if (ext != 'jpg' && ext != 'gif' && ext != 'png') return false;

    var img_loading = $('#bannerImgUploadLoading');
    var container = $('div#banner_adForm');
    $('#formBottom', container).hide();
    $('#sampleBannerImg').hide();

    $('#banner_upload').hide();
    $('#banner_image').hide();
    img_loading.removeClass('hidden').show();
    $.ajax({
        url: mim.baseUrl + 'member/test_banner/'+bsize,
        type: 'post',
        data: {
            imageURL: url
        },
        dataType: 'json',
        success: function (response) {
            img_loading.hide();
            $('div.formBottom', container).show();
            if (response.error) {
                var el = 'bannerImageURL';
                var label = $('label[for=' + el + ']', form),
                    formElement = $('#' + el, form);

                formElement.addClass('error');
                label.append('<span class="element_error">' + response.error + '</span>');
                $('#sampleBannerImg').show();
            }
            else {
                $('#sampleBannerImg').html('<img src="' + response.banner + '" /><input id="banner_img" type="hidden" name="image" value="' + response.file + '" /><br/>').show();
            }
        }
    });
}

function validateURL(value) {
    return /^(https?):\/\/?(?:www.)?(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
    //return /^(http(?:s)?\:\/\/[a-zA-Z0-9]+(?:(?:\.|\-)[a-zA-Z0-9]+)+(?:\:\d+)?(?:\/[\w\-]+)*(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/i.test(value)
}
