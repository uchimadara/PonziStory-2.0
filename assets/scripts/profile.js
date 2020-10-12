$(document).ready(function() {
    $(document).delegate('#validateImage', 'click', function (e) {

        e.preventDefault();

        var id = 'avatarForm',
            form = $('#' + id),
            container = form.parent('div.formContainer'),
            button = $(this);

        var $this = $('input[name="imageURL"]'),
            max_w = $this.attr('data-maxw'),
            max_h = $this.attr('data-maxh'),
            url = $this.val(),
            label = $('label[for=imageURL]', form);

        if (!validateURL(url)) {
            $this.addClass('error');
            label.append('<span class="element_error">' + '*invalid url' + '</span>');
            return false;
        }
        var ext = url.substr(url.length - 3);

        if (ext != 'jpg' && ext != 'gif' && ext != 'png') {
            $this.addClass('error');
            label.append('<span class="element_error">' + '*incorrect file type' + '</span>');
            return false;
        }

        button.html('<img src="' + mim.assetPath + 'images/redloading.gif" />');
        cleanForm(container);

        var img = new Image;
        img.onload = function () {

            var w = this.width, h = this.height;

            if ((w > max_w) || (h > max_h)) {
                $this.addClass('error');
                label.append('<span class="element_error">' + '*incorrect size' + '</span>');
            } else {
                $('#displayImage').html('<img src="' + url + '" />').removeClass('hidden').show();
            }
            button.html('Validate');
        };

        img.onerror = function () {
            $this.addClass('error');
            var label = $('label[for=imageURL]', form);
            label.append('<span class="element_error">' + '* does not point to an image' + '</span>');
            button.html('Validate');
        };

        var target = $('#displayImage'),
            orig = target.html();

        target.html('<span class="loading"></span>');

        $.ajax({
            url: mim.baseUrl + 'member/wget_avatar',
            type: 'post',
            data: {
                avatar: url
            },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    label.append('<span class="element_error">' + response.error + '</span>');
                    target.html(orig);

                }
                else {
                    target.html('<img src="' + mim.baseUrl + '/avatars/' + response.imgFile + '" />');
                }

                button.html('Validate');
            }
        });

    });
    $(document).delegate('.avatar-image-select', 'click', function (e) {

        var imgFile = $(this).attr('data-select'),
            id = imgFile.replace('.', '-');

        $('#' + id).attr('checked', "checked");
        useDefaultAvatar(imgFile);

    });

    $(document).delegate('.avatar-select', 'change', function (e) {
        useDefaultAvatar($(this).val());
    });

});
function useDefaultAvatar(imgFile) {
    $('#displayImage').html('<span class="loading"></span>');

    $.ajax({
        url: mim.baseUrl + 'member/set_avatar',
        type: 'post',
        data: {
            avatar: 'default/' + imgFile
        },
        dataType: 'json',
        success: function (response) {
            $('#displayImage').html('<img src="' + mim.baseUrl + '/avatars/default/' + imgFile + '" />');
        }
    });

}
function ajax_upload() {
    var id = 'proofForm',
        form = $('#' + id),
        container = form.parent('div.formContainer'),
        target = $('#displayImage'),
        orig = null;


    new AjaxUpload('banner', {
        action: mim.baseUrl + 'member/upload_avatar',
        name: 'banner',
        responseType: 'json',
        onSubmit: function (file, extension) {
            $('.frm_error, .frm_success, .element_error, .formError', container).remove();
            $('.error', container).removeClass('error');

            orig = target.html();
            target.html('<span class="loading"></span>');

        },
        onComplete: function (file, response) {
            if (response.error) {
                target.html('<span class="element_error">' + response.error + '</span>');
            }
            else {
                target.html('<img src="' + response.banner + '" />');
            }
        }
    });

}
