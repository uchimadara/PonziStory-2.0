$(document).ready(function(){

    $(document).delegate('form[name=testimonialForm]', 'reset', function(e) {
        clearScreenshot();
    });

    $(document).delegate('a#cancelScreenshot', 'click', function (e) {
        e.preventDefault();
        cancelScreenshot();
    });

    $(document).delegate('#screenshotValidate', 'click', function (e) {

        e.preventDefault();

        var form = $(this).parents('form'),
            container = form.parent('div.formContainer');

        var screenshotURL = $('#screenshotImageURL'),
            url = screenshotURL.val(),
            label = $('label[for="screenshotImageURL"]', form);

        cleanForm(container);

        if (url.length < 28) {
            screenshotURL.addClass('error');
            return false;
        }
        if (!validateURL(url)) {
            screenshotURL.addClass('error');
            return false;
        }
        var ext = url.substr(url.length - 3);

        if (ext != 'jpg' && ext != 'gif' && ext != 'png'&& ext != 'jpeg') {
            screenshotURL.addClass('error');
            return false;
        }

        var img_loading = $('#screenshotLoading'),
            vButton = $('#screenshotValidate'),
            screenshotUpload = $('#screenshotUpload');

        $('div.formBottom', container).hide();
        $('#samplescreenshotImg').hide();

        screenshotUpload.hide();
        vButton.hide();
        img_loading.removeClass('hidden').show();

        cancelScreenshot();

        $('#screenshot_image').hide();

        $.ajax({
            url: mim.baseUrl + 'ajax/screenshot/test_image',
            type: 'post',
            data: {
                imageURL: url
            },
            dataType: 'json',
            success: function (response) {
                img_loading.hide();
                $('div.formBottom', container).show();
                if (response.error) {
                    screenshotURL.addClass('error');
                    label.append('<span class="element_error">' + response.error + '</span>');
                    $('#screenshot_image').show();
                    vButton.show();
                    screenshotUpload.show();
                }
                else {
                    $('#screenshot_image').html('<img class="thumb" src="' + response.screenshot + '" /><input id="screenshot" type="hidden" name="screenshot" value="' + response.file + '" /><br/>').removeClass('hidden').show();
                }
            }
        });
    });
});

function initScreenshot(formId) {

    new AjaxUpload('screenshot_file', {
        action: mim.baseUrl + 'ajax/screenshot/image_upload',
        name: 'screenshot_file',
        responseType: 'json',
        onSubmit: function (file, extension) {

            cancelScreenshot();

            var loading = $('#screenshotLoading'),
                screenshotURL = $('#screenshotURL'),
                bottom = $('div.formBottom');

            $('#screenshot_image').html('');

            bottom.hide();
            if (loading.hasClass('hidden')) {
                loading.removeClass('hidden');
            }
            loading.show();
            screenshotURL.hide();
        },
        onComplete: function (file, response) {
            var loading = $('#screenshotLoading'),
                screenshotURL = $('#screenshotURL'),
                bottom = $('div.formBottom');

            bottom.show();
            loading.hide();

            $('span.upload_loading', '#screenshot_upload').hide();
            if (response.error) {
                $('#screenshot_image').html('<span class="element_error">' + response.error + '</span><br/>').removeClass('hidden').show();
                screenshotURL.show();
            }
            else {
                $('#submitScreenshot').show();
                $('#screenshot_image').html('<img class="thumb" src="' + response.screenshot + '" /><input id="screenshot" type="hidden" name="screenshot" value="' + response.file + '" /><br/>').removeClass('hidden').show();
            }
        }
    });
}

function clearScreenshot() {

    cancelScreenshot();
    $('#screenshot_image').html('');
}

function cancelScreenshot() {

    if ($('input[name="screenshot"]').length) {
        $.ajax({
            url: mim.baseUrl + 'ajax/screenshot/cancel_image',
            type: 'post',
            data: {
                screenshot: $('input[name="screenshot"]').val()
            },
            dataType: 'json',
            success: function (response) {
            }
        });

    }
}