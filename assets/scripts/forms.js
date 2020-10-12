function maskInteger(field) {
    var valid = "0123456789-";
    var strVal = field.value;
    if (valid.indexOf(strVal.substring(strVal.length - 1, strVal.length)) == "-1")
        field.value = strVal.substring(0, strVal.length - 1);
}

function maskAmount(field) {
    var valid = "0123456789.-";
    var strVal = field.value;
    if (valid.indexOf(strVal.substring(strVal.length - 1, strVal.length)) == "-1")
        field.value = strVal.substring(0, strVal.length - 1);
}

cleanForm = function (container) {
    $('.frm_error, .frm_success, .element_error, .formError', container).remove();
    $('.error', container).removeClass('error');
};

redirect = function (url, hash) {
    var curUrl = window.location.href,
        curHash = window.location.hash;

    if (curHash)
        curUrl = curUrl.substr(0, curUrl.indexOf(curHash));

    if (hash !== undefined && curUrl == url) {
        window.location.hash = hash;
        window.location.reload(true);
        return;
    }

    window.location.href = url + (hash !== undefined ? hash : '');
};

var formSuccess = true;

function datePicker(e) {
    e.datepicker({
        changeMonth: true,
        changeYear: true,
        prevText: '',
        nextText: '',
        dateFormat: 'yy-mm-dd',
        minDate: "-6m",
        maxDate: "+6m",
        showOn: "both",
        buttonImage: mim.assetPath + "images/calendar_icon.png",
        buttonImageOnly: true,
        showButtonPanel: false
    });

}

function getUsername(e) {
    var listingId = $(e).val();
    $.get(mim.baseUrl+'member/get_username/'+listingId, function(data) {
        $('input[name="username"]').val(data);
    });
}

function dontShow(what) {
    $.get(mim.baseUrl + 'member/dontShow/' + what, function (data) {
        $('#'+what).hide("blind", {easing: 'swing'}, 400, function () {});
    });
}

$(document).delegate('input:checkbox.dontShow','click', function (event) {
    var what = $(this).attr('data-what');

    $.get(mim.baseUrl + 'member/dontShow/'+what, function (data) {
        $('#'+what).hide("blind", {easing: 'swing'}, 400, function () {
        });
    });
});

function validateImageUrl(e){
    var elem = $(e);

    if (elem.val() == '') return;

    var form = elem.closest('form');
    var container = form.parent('div.formContainer');

    var loading = $('span.loading', container),
        bottom = $('div.formBottom', container);

    if (bottom.length == 0) {
        var submitButton = $("input[type='submit']", container);
        submitButton.hide();
    } else {
        bottom.hide();
    }

    if (loading.length == 0) {
        form.append($('<span class="loading"></span>'));
        loading = $('span.loading', container);
    } else {
        loading.html('Validating image... <img src="' + mim.assetPath + 'images/loading.gif" />');
    }

    loading.show();

    $.ajax({
        url: mim.baseUrl + 'member/validate_image_url',
        type: 'post',
        data: {url: elem.val()},
        dataType: 'html',
        success: function (data) {
            $('#' + elem.attr('name') + '_image').html(data);
            loading.html('Image validated.');
            setTimeout(function(){
                loading.hide();
                if (bottom.length == 0) {
                    submitButton.show();
                } else {
                    bottom.show();
                }
            }, 1500);
        },
        error: function (request, status, error) {
            //console.log(request, status, error);
            alert('Server connection error. Please try again..');
            alert(mim.baseUrl);
            loading.hide();
            if (bottom.length == 0) {
                submitButton.show();
            } else {
                bottom.show();
            }

        },
        complete: function () {
        }
    });

}

var inProcess = [];
function ConvChar(str) {
    c = {'<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;', "'": '&#039;',
        '#': '&#035;' };
    return str.replace(/[<&>'"#]/g, function (s) {
        return c[s];
    });
}
$(document).ready(function () {

    if ($('.tip')[0]) $('.tip').tooltipsy();

    if ($('.htmlEdit').length) {
        tinymce.init({
            selector: ".htmlEdit",
            theme: "modern",
            plugins: [
                "lists link image charmap preview hr anchor",
                "wordcount fullscreen",
                "media table ",
                "emoticons paste textcolor colorpicker textpattern"
            ],
            toolbar1: "undo redo | bold italic fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | link image | preview media | forecolor backcolor emoticons",
            menubar: false

        });

    }
    //Date Only
    if ($('.date')[0]) {
        //$.fn.datepicker.defaults.format = "yyyy-mm-dd";

        $('.date').datepicker({
            //autoclose: true,
            format : "yyyy-mm-dd"
        });
    }

    //Time only
    if ($('.time-only')[0]) {
        $('.time-only').datetimepicker({
            pickDate: false
        });
    }

    //12 Hour Time
    if ($('.time-only-12')[0]) {
        $('.time-only-12').datetimepicker({
            pickDate: false,
            pick12HourFormat: true
        });
    }
    if ($(".form_datetime").length) {
        $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
    }
    $('.datetime-pick input:text').on('click', function () {
        $(this).closest('.datetime-pick').find('.add-on i').click();
    });
    if ($('.dp').length) {
        $('.dp').datepicker({
            changeMonth: true,
            changeYear: true,
            prevText: '',
            nextText: '',
            dateFormat: 'yy-mm-dd',
            minDate: "-6m",
            maxDate: "+6m",
            showOn: "both",
            buttonImage: mim.assetPath + "images/calendar_icon.png",
            buttonImageOnly: true,
            showButtonPanel: false
        });

    }

    $(document).delegate('input, textarea, checkbox, select', 'focus', function () {
        var label = $(this).prev('label');

        if (label.length)
            label.find('span.element_error').remove();
        $(this).removeClass("error");
    });

    $(document).delegate('.editField', 'click', function (e) {
        e.preventDefault();

        var container = $(this).closest('div.hiddenEditContainer'),
            disp_field = container.find('span.displayField'),
            edit_field = container.find('span.hiddenEdit');

        $(edit_field).show();
        $(disp_field).hide();
        $(this).hide();
    });

    $(document).delegate('.editSaveField', 'click', function (e) {
        e.preventDefault();

        var container = $(this).closest('div.hiddenEditContainer'),
            disp_field = container.find('span.displayField'),
            edit_field = container.find('span.hiddenEdit');

        $(edit_field).show();
        $(disp_field).hide();
        $(this).addClass('saveField').removeClass('editField').html('save');
    });

    $(document).delegate('form.frm_ajax', 'submit', function (e) {
        e.preventDefault();

        var form = $(this),
            id = form.attr('id'),
            container = form.parent('div.formContainer');

        if (inProcess[id] === undefined || inProcess[id] === false) {
            inProcess[id] = true;

            if ($('.htmlEdit').length) { //
                $('.htmlEdit').each(function(){
                    if (inProcess[id] === false) return false;
                    try {
                        var html = tinyMCE.get($(this).attr('id')).getContent(); //$(this).code();
                        var contents = ConvChar(html);
                        if (contents != '<p><br></p>') {
                            $(this).val(contents);
                        }

                    } catch (e) {

                        alert('Form is not completely loaded yet. Please wait a couple more seconds.');
                        inProcess[id] = false;
                    }

                });
            }

            if (inProcess[id] === false) return false;

//            if ($('#forum_message').length) { //
//                var html = $('#forum_message').code();
//                $('#forum_message').val(ConvChar(html));
//            }

            var formData = form.serializeArray(),
                loading = $('div.loading', container),
                bottom = $('div.formBottom', container);

            if (bottom.length == 0) {
                var submitButton = $("input[type='submit']", container);
                submitButton.hide();
            } else {
                bottom.hide();
            }

            if (loading.length == 0) {
                form.append($('<div class="loading"></div>'));
                loading = $('div.loading', container);
            }

            cleanForm(container);

            loading.show();

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                dataType: 'json',
                success: function (data) {
                    console.info(data);
                    if (data.error) {
                        formSuccess = false;
                        if (container.closest('#modal').length)
                            form.before('<p class="formError">' + data.error + '</p>');
                        else container.prepend('<div class="frm_error alert narrow alert-danger alert-dismissable"><i class="fa fa-exclamation-triangle"></i> &nbsp;' + data.error + '</div>');
                    }
                    else if (data.errorElements) {
                        formSuccess = false;
                        for (var el in data.errorElements) {

                            var label = $('label[for=' + el + ']', form),
                                formElement = $('#' + el, form);

                            formElement.addClass('error');

                            if (data.errorElements[el] != "" && label.length) {
                                label.append('<span class="element_error">' + data.errorElements[el] + '</span>');
                            }
                        }
                    }
                    else {
                        formSuccess = true;
                        if (data.again !== undefined) {
                            formSuccess = false; // will cause loading to hide and bottom to show
                            form.get(0).reset();
                        }

                        if (data.html !== undefined) {
                            container.html(data.html);
                        }
                        else if (data.success !== undefined) {
                            container.prepend('<div class="alert narrow alert-success alert-dismissable">' + data.success + '</div>');
                        }
                        if (data.replace !== undefined) {
                            for (el in data.replace) {
                                if ($('#' + el).length) $('#' + el).html(data.replace[el]);
                            }
                        }
                        if (data.replace_val !== undefined) {
                            for (el in data.replace_val) {
                                if ($('#' + el).length) $('#' + el).val(data.replace_val[el]);
                            }
                        }

                        if (data.append !== undefined) {
                            for (el in data.append) {
                                if ($('#' + el).length) {
                                    var d = $('#' + el);
                                    d.append(data.append[el]);
                                    d.animate({scrollTop: d[0].scrollHeight}, "fast");
                                }
                            }
                        }
                        if (data.redirect !== undefined) {
                            if (data.redirect === 'reload') {
                                window.location.reload(true);
                            }
                            else if (data.redirect.timeout !== undefined) {
                                setTimeout(function () {
                                    redirect(data.redirect.url, data.redirect.hash);
                                }, data.redirect.timeout);
                            }
                            else  {
                                redirect(data.redirect.url, data.redirect.hash);
                            }
                        }

                        if (form.attr('callback') !== undefined) {
                            var func = form.attr('callback');
                            if (typeof window[func] === 'function') {
                                window[func]();
                            }
                        }
                    }

                    if (data.redirect !== undefined) {
                        if (data.redirect === 'reload') {
                            window.location.reload(true);
                        } else {
                            redirect(data.redirect.url)
                        }
                    }
                },
                error: function (request, status, error) {
                    //console.log(request, status, error);
                    alert('Server connection error. Please try again...');
                    loading.hide();
                    if (bottom.length == 0) {
                        submitButton.show();
                    } else {
                        bottom.show();
                    }
                },
                complete: function () {
                    inProcess[id] = false;
                    loading.hide();

                    if (formSuccess) {
                        //loading.html('Success!');
                    } else {
                        if (bottom.length == 0) {
                            submitButton.show();
                        } else {
                            bottom.show();
                            //bottom.css('top', "0");
                        }
                    }
                }
            });
        }
    });

    $(document).delegate('a.confirm', 'click', function (e) {
        return confirm('Are you sure?');
    });

    $('div.getForm').each(function () {
        var url = $(this).attr('data-url');

        var e = $(this);
        if (url)
            $.get(url, function (data) {
                e.html(data);
            });
    });

});