function savePreviousValue(that) {
    $(that).data('previous', $(that).val());
}

var selectAjaxResult = function(status, $form, callAfter, that){
    if( typeof status === "undefined" ) {
        status = 1;
    }

    $form.parent().parent().animate({
        backgroundColor: (status ? "green" : "red")
    }).fadeIn(2000, function(){
        try {
            callAfter(that, $form, (status ? 'success' : 'error'));
        } catch(e){};

        if(!status) {
            $(that).val( $(that).data('previous') );
        }
    }).animate({
        backgroundColor: "transparent"
    }).fadeIn(2000);
}

function selectAjax(formName, callAfter, that) {
    var $form = $('form[name=' + formName + ']');
    var method = "POST";
    $.ajax({
        url: $form.attr("action"),
        data: $form.serialize(),
        type: method,
        dataType: 'json',
        error: function(result) {
            var status = false;
            if(result.status == 200) status = true;
            //console.info('status',false,result);
            selectAjaxResult(status, $form, callAfter, that);
        },
        success: function (data) {
            selectAjaxResult(data.success, $form, callAfter, that);
        }

    });
}
