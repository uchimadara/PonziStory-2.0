
$(document).ready(function() {
    if ($('input:checkbox.dontShow').length) {

        $('input:checkbox.dontShow').on('ifChecked', function (event) {
            $.get(mim.baseUrl + 'member/dontShow/upgrade_instructions', function (data) {
                $('#upgrade_instructions').hide("blind", {easing: 'swing'}, 400, function () {
                });
            });
        });

    }

    $('#currency').change(function() {
       var $this = $(this),
           curr = $this.val();

        if (curr == 'USD') {
            $('#currency_amount_input').hide().val('');

        } else {
            $('label[for=currency_amount]').html('Amount in ' + $('#currency option:selected').text() + ' ('+curr+')');
            $('#currency_amount_input').show();

            $.get('http://api.fixer.io/latest?base=USD', function(data){
                var newPrice = parseFloat($('#price').html()) * parseFloat(read_prop(data.rates,curr));

                if (isNaN(newPrice)) {
                    $('#currency_amount').attr('placeholder', 'Enter amount of '+ $('#currency option:selected').text()+' that you sent.');
                } else {
                    $('#currency_amount').val(newPrice.toFixed(2));

                }
            })
        }
    });

    //ajax_upload();
});
function read_prop(obj, prop) {
    return obj[prop];
}
function ajax_upload() {
    var id = 'proofForm',
        form = $('#' + id),
        container = form.parent('div.formContainer'),
        target = $('#displayImage'),
        orig = null;


    new AjaxUpload('banner', {
        action: mim.baseUrl + 'member/upload_proof',
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
                target.html('<img src="' + response.banner + '" /><input id="proof_img" type="hidden" name="proof_img" value="' + response.file + '" /><br/>').removeClass('hidden').show();
            }
        }
    });

}
