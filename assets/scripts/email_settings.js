$(document).ready(function(){
    $('#emails_tab_content').delegate('.cbEmailSetting', 'click', function(){
        // let's grab all the checkboxes we have in that div and sum up the ones that are ticked
        var total = 0;
        $(".cbEmailSetting:checked").each(function (){
            total += parseInt($(this).val());
        });

        var form      = $("#emailsettingsFrm"),
            container = $('#notif_message');

        overlay.show($('dl'));

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: { settings: total },
            dataType: 'json',
            success: function(data){
                if (data.error) {
                    container.html('<span class="frm_error">' + data.error + '</span>');
                }
                else {
                    if (data.success !== undefined){
                        container.html(data.success).show();
                        setTimeout(function() {
                            container.fadeOut('fast').html('');
                        }, 2000);
                    }
                    console.log(data);
                }
            },
            complete: function(){
                overlay.hide();
            }
        });
    });
});