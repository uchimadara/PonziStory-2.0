var twoG = 0;
var threeG = 0;
var twoGdaily = 0;
var threeGdaily = 0;

var MB = 1;
var GB = MB * 1024;
var KB = MB / 1024;

var fourGB = 4 * GB;
var twoGB = 2 * GB;
var oneGB = 1 * GB;
var fiveHundredMB = 500 * MB;
var hundredMB = 100 * MB;
var tenMB = 10 * MB;
var oneMB = 1 * MB;

function calculator(data, target) {
    var dataPercent = 0;
    var dataPipes = 0;
    var labelCount = 0;
    var labelSuffix = "";
    if (data > fourGB) {
        dataPipes = 50;
        labelCount = 4;
        labelSuffix = "GB+/Month";
    } else if (data >= twoGB) {
        dataPipes = Math.round((10 * 4) + (data - (2 * GB)) / 200);
        labelCount = data / GB;
        labelCount = labelCount.toFixed(2);
        labelSuffix = "GB/Month";
    } else if (data >= oneGB) {
        dataPipes = Math.round((10 * 3) + (data - GB) / 100);
        labelCount = data / GB;
        labelCount = labelCount.toFixed(2);
        labelSuffix = "GB/Month";
    } else if (data >= fiveHundredMB) {
        dataPipes = Math.round((10 * 2) + (data - 500) / 50);

        labelCount = Math.round(data);
        labelSuffix = "MB/Month";
    } else if (data >= hundredMB) {
        dataPipes = Math.round((10 * 1) + (data - 100) / 40);
        labelCount = Math.round(data);
        labelSuffix = "MB/Month";
    } else if (data >= 0) {

        dataPipes = Math.round((10 * 0) + (data - 0) / 10);

        labelCount = Math.round(data);
        labelSuffix = "MB/Month";
    }

    targetInput = "#" + target + "-input";
    targetMeter = "#" + target + "-Meter";
    ////console.log(data);
    jQuery(targetInput).val(data);
    dataPercent = dataPipes * 2;
    dataPercent = dataPercent + "%";
    var labelBottom = (dataPipes * 2) + 2;
    labelBottom = labelBottom + "%";
    jQuery(targetMeter).children('.ui-progressbar-value').css('height', dataPercent);
    jQuery(targetMeter).find('.label .label_count').text(labelCount);
    jQuery(targetMeter).find('.label .label_suffix').text(labelSuffix);
    jQuery(targetMeter).find('.label').css('bottom', labelBottom);
}

/* Daily Calculato */
function calculatorDaily(data, target) {
    var dataPercent = 0;
    var dataPipes = 0;
    var labelCount = 0;
    var labelSuffix = "";

    if (data > oneGB) {
        dataPipes = 50;
        labelCount = 1;
        labelSuffix = "GB+/Day";
    } else if (data >= fiveHundredMB) {
        dataPipes = Math.round((10 * 4) + (data - (500 * MB)) / (50 * MB));
        labelCount = data / MB;
        labelCount = Math.round(labelCount);
        labelSuffix = "MB/Day";
    } else if (data >= hundredMB) {
        dataPipes = Math.round((10 * 3) + (data - (100 * MB)) / (40 * MB));
        labelCount = data / MB;
        labelCount = Math.round(labelCount);
        labelSuffix = "MB/Day";
    } else if (data >= tenMB) {
        dataPipes = Math.round((10 * 2) + (data - (10 * MB)) / (9 * MB));

        labelCount = data.toFixed(1);
        labelSuffix = "MB/Day";
    } else if (data >= oneMB) {
        dataPipes = Math.round((10 * 1) + (data - (1 * MB)) / (0.9 * MB));
        labelCount = data.toFixed(2);
        labelSuffix = "MB/Day";
    } else if (data >= 0) {

        data = data * 1024;
        //console.log(data);
        dataPipes = Math.round((10 * 0) + (data - 0) / (MB / (KB * 10)));
        if (Math.round(data) >= 1000) {
            labelCount = 1;
            labelSuffix = "MB/Day";
        } else {
            labelCount = Math.round(data);
            labelSuffix = "KB/Day";
        }

    }

    targetInput = "#" + target + "-input";
    targetMeter = "#" + target + "-Meter";
    ////console.log(data);
    jQuery(targetInput).val(data);
    dataPercent = dataPipes * 2;
    dataPercent = dataPercent + "%";
    var labelBottom = (dataPipes * 2) + 2;
    labelBottom = labelBottom + "%";
    ////console.log(labelSuffix);
    jQuery(targetMeter).children('.ui-progressbar-value').css('height', dataPercent);
    jQuery(targetMeter).find('.label .label_count').text(labelCount);
    jQuery(targetMeter).find('.label .label_suffix').text(labelSuffix);
    jQuery(targetMeter).find('.label').css('bottom', labelBottom);
}

/* 2G usage monthly */
function calculate2G(tempTwoGUsage) {
    var twoGDuo = parseFloat(jQuery('#2G-input').attr('data-2g-duo'));
    var tempTwoG = tempTwoGUsage * twoGDuo;
    twoG = twoG + tempTwoG;
    jQuery("#twoG-input").val(twoG);
    jQuery("#twoGmonthly-input").val(twoG.toFixed(2));

    calculator(twoG, "2G");
}

/* 3G usage monthly */
function calculate3G(tempThreeGUsage) {
    var threeGDuo = parseFloat(jQuery('#3G-input').attr('data-3g-duo'));
    ////console.log(threeGDuo);
    var tempThreeG = tempThreeGUsage * threeGDuo;
    threeG = threeG + tempThreeG;
    jQuery("#threeGmonthly-input").val(threeG.toFixed(2));
    jQuery("#threeG-input").val(threeG);
    calculator(threeG, "3G");
}

/* 2G usage daily */
function calculate2Gdaily(tempTwoGUsage) {
    twoGdaily = twoGdaily + tempTwoGUsage;
    jQuery("#twoGdaily-input").val(twoGdaily.toFixed(2));
    ////console.log(twoGdaily);
    calculatorDaily(twoGdaily, "2G");
}

/* 3G usage daily */
function calculate3Gdaily(tempThreeGUsage) {
    threeGdaily = threeGdaily + tempThreeGUsage;
    jQuery("#threeGdaily-input").val(threeGdaily.toFixed(2));
    ////console.log(threeGdaily);
    calculatorDaily(threeGdaily, "3G");
}

/* ts calculator */
//jQuery(".ts_cal").each(function () {
//    var color = jQuery(this).attr('data-color');
//    var slices = jQuery(this).attr('data-slices');
//    var max = parseInt(slices) - 1;
//    var label = jQuery(this).attr('data-labels');
//    var orientation = jQuery(this).attr('data-orientation');
//    var step = jQuery(this).attr('data-step');
//
//    if (jQuery.isNumeric(step)) {
//        step = parseInt(step);
//    } else {
//        step = 1;
//    }
//    if (jQuery.type(label) === "string") {
//        var labelsArr = label.split(',');
//    } else {
//        var labelsArr = "";
//    }
//
//    var $slider = "";
//    var $slider = jQuery(this).slider({
//        max: max,
//        range: "min",
//        step: step,
//        orientation: orientation,
//        stop: function (event, ui) {
//            var targetInput = jQuery(this).attr("data-input");
//            
//            var minIndex = 0;
//            var maxIndex = ui.value;
//           
//            if(maxIndex > 0 ){
//                minIndex = ui.value-1;
//            }
//            
//            var minVal = labelsArr[minIndex];
//            var maxVal = labelsArr[maxIndex];
//            var minId = "#min-" + targetInput;
//            var maxId = "#max-" + targetInput;
//            jQuery(minId).val(minVal);
//            jQuery(maxId).val(maxVal);
//        }
//    });
//
//    $slider.slider(
//            "pips",
//            {
//                rest: "label",
//                labels: labelsArr,
//                step: 1
//            }
//    ).slider(
//            "float",
//            {
//                labels: labelsArr
//            }
//    );
//
//});
//
//jQuery(".ts_cal .ui-slider-range").each(function () {
//    var color;
//    color = jQuery(this).closest(".ts_cal").attr('data-color');
//
//    jQuery(this).css("background-color", color);
//});
//
//jQuery(".ts_cal").each(function () {
//    for (var i = 0; i < 50; i++) {
//       // jQuery(this).append("<span class='p_pipe'></span>");
//    }
//});
//
///* end TS calculator */

jQuery(".ts_cal").each(function () {
    var color = jQuery(this).attr('data-color');
    var slices = jQuery(this).attr('data-slices');
    var max = parseInt(slices) - 1;
    var label = jQuery(this).attr('data-labels');
    var orientation = jQuery(this).attr('data-orientation');
    var step = jQuery(this).attr('data-step');

    if (jQuery.isNumeric(step)) {
        step = parseInt(step);
    } else {
        step = 1;
    }
    if (jQuery.type(label) === "string") {
        var labelsArr = label.split('-');
    } else {
        var labelsArr = "";
    }

    var $slider = "";
    var $slider = jQuery(this).slider({
        max: max,
        range: true,
        step: step,
        orientation: orientation,
        stop: function (event, ui) {
            var targetInput = jQuery(this).attr("data-input");
            var values = ui.values;
            
            var minIndex = 0;
            var maxIndex = 0;
            
            if(values[0]>= 0){
                minIndex = values[0];
            }
            if(values[1]>= 0){
                maxIndex = values[1];
            }
           
//            if(maxIndex > 0 ){
//                minIndex = ui.value-1;
//            }
            
            var minVal = labelsArr[minIndex];
            var maxVal = labelsArr[maxIndex];
            var minId = "#min-" + targetInput;
            var maxId = "#max-" + targetInput;
            jQuery(minId).val(minVal);
            jQuery(maxId).val(maxVal);
        }
    });

    $slider.slider(
            "pips",
            {
                rest: "label",
                labels: labelsArr,
                step: 1
            }
    ).slider(
            "float",
            {
                labels: labelsArr
            }
    );

});

jQuery(".ts_cal .ui-slider-range").each(function () {
    var color;
    color = jQuery(this).closest(".ts_cal").attr('data-color');

    jQuery(this).css("background-color", color);
});

jQuery(".ts_cal").each(function () {
    for (var i = 0; i < 50; i++) {
        jQuery(this).append("<span class='p_pipe'></span>");
    }
});

/* awesome calcualtor */
/* Internet Usage Calculator Daily */
jQuery(".awesome2").each(function () {
    var color = jQuery(this).attr('data-color');
    var slices = jQuery(this).attr('data-slices');
    var max = parseInt(slices) - 1;
    var label = jQuery(this).attr('data-labels');
    var orientation = jQuery(this).attr('data-orientation');
    var step = jQuery(this).attr('data-step');

    if (jQuery.isNumeric(step)) {
        step = parseInt(step);
    } else {
        step = 1;
    }
    if (jQuery.type(label) === "string") {
        var labelsArr = label.split('-');
    } else {
        var labelsArr = "";
    }

    var $slider = "";
    var $slider = jQuery(this).slider({
        max: max,
        range: "min",
        step: step,
        orientation: orientation,
        stop: function (event, ui) {
            var targetInput = jQuery(this).attr("data-input");
            var oldVal = parseFloat(jQuery(targetInput).val());
            var threeGUnit = parseFloat(jQuery(this).attr("data-3g-unit"));
            var twoGUnit = parseFloat(jQuery(this).attr("data-2g-unit"));

            if (!jQuery.isNumeric(oldVal)) {
                oldVal = 0;
            }
            var newVal = labelsArr[ui.value];
            var diffVal = newVal - oldVal;
            var twoGUsage = twoGUnit * diffVal;
            var threeGUsage = threeGUnit * diffVal;
            jQuery(targetInput).val(newVal);
            //console.log(twoGUsage);
            //console.log(threeGUsage);
            calculate2Gdaily(twoGUsage);
            calculate3Gdaily(threeGUsage);
        }
    });
    $slider.slider(
            "pips",
            {
                rest: "label",
                labels: labelsArr,
                step: 1
            }
    ).slider(
            "float",
            {
                labels: labelsArr
            }
    );
});

jQuery(".awesome2 .ui-slider-range").each(function () {
    var color;
    color = jQuery(this).closest(".awesome2").attr('data-color');

    jQuery(this).css("background-color", color);
});

jQuery(".progressbar").progressbar({
    value: 100
});

var i = 0;

jQuery(".progressbar").each(function () {
    var meterLabels = jQuery(this).attr('data-labels');
    var meterLabelsArr = meterLabels.split('-');
    var labelCounter = 5;
    for (i = 0; i <= 50; i++) {
        if (i % 10 != 0) {
            if (i % 5 != 0) {
                jQuery(this).children('.progress_pipes').append("<span class='p_pipe'></span>");
            } else {
                jQuery(this).children('.progress_pipes').append("<span class='p_pipe milestone'></span>");
            }
        } else {
            jQuery(this).children('.progress_pipes').append("<span class='meter_label'>" + meterLabelsArr[labelCounter] + "</span><span class='p_pipe milestone'> </span>");
            labelCounter = labelCounter - 1;
        }

    }
});

jQuery(".awesome2").each(function () {
    for (var i = 0; i < 50; i++) {
        jQuery(this).append("<span class='p_pipe'></span>");
    }
});
/* end awesome daily calculator */

/* awesome monthly calculator */
jQuery(".awesome1").each(function () {
    var color = jQuery(this).attr('data-color');
    var slices = jQuery(this).attr('data-slices');
    var max = parseInt(slices) - 1;
    var label = jQuery(this).attr('data-labels');
    var orientation = jQuery(this).attr('data-orientation');
    var step = jQuery(this).attr('data-step');

    if (jQuery.isNumeric(step)) {
        step = parseInt(step);
    } else {
        step = 1;
    }
    if (jQuery.type(label) === "string") {
        var labelsArr = label.split('-');
    } else {
        var labelsArr = "";
    }

    var $slider = "";
    var $slider = jQuery(this).slider({
        max: max,
        range: "min",
        step: step,
        orientation: orientation,
        stop: function (event, ui) {
            var targetInput = jQuery(this).attr("data-input");
            var oldVal = parseFloat(jQuery(targetInput).val());
            var threeGUnit = parseFloat(jQuery(this).attr("data-3g-unit"));
            var twoGUnit = parseFloat(jQuery(this).attr("data-2g-unit"));

            if (!jQuery.isNumeric(oldVal)) {
                oldVal = 0;
            }
            var newVal = labelsArr[ui.value];
            var diffVal = newVal - oldVal;
            var twoGUsage = twoGUnit * diffVal;
            var threeGUsage = threeGUnit * diffVal;
            jQuery(targetInput).val(newVal);
            //console.log(twoGUsage);
            //console.log(threeGUsage);
            calculate2G(twoGUsage);
            calculate3G(threeGUsage);
        }
    });
    $slider.slider(
            "pips",
            {
                rest: "label",
                labels: labelsArr,
                step: 1
            }
    ).slider(
            "float",
            {
                labels: labelsArr
            }
    );
});

jQuery(".awesome1 .ui-slider-range").each(function () {
    var color;
    color = jQuery(this).closest(".awesome1").attr('data-color');

    jQuery(this).css("background-color", color);
});



jQuery(".awesome1").each(function () {
    for (var i = 0; i < 50; i++) {
        jQuery(this).append("<span class='p_pipe'></span>");
    }
});

/* end awesome monthly calculator */

/* hide show calculator */
function toggleElement(status, element, delay) {
    if (status) {
        jQuery(element).hide(delay);
        return false;
    } else {
        jQuery(element).show(delay);
        jQuery(element).css("overflow", "visible");
    }
    return true;
}

jQuery(document).ready(function () {
    jQuery(".toggle-display").each(function () {
        var targetId = jQuery(this).attr("data-id");
        var display = jQuery(this).attr("data-status");
        var delay = 0;
        var status = true;
        var btn_offset = jQuery(this).offset();
        //////console.log(btn_offset);
        if (display !== "show") {
            status = toggleElement(status, targetId, delay);
        }
        delay = 1000;
        jQuery(this).click(function () {
            status = toggleElement(status, targetId, delay);
            var old_offset = btn_offset;

            btn_offset = jQuery(this).offset();
            //////console.log("BTN OFFSET");
            //////console.log( btn_offset);
            //////console.log("OLD OFFSET");
            //////console.log( old_offset);
            if (!status) {

                //////console.log(btn_offset);
                jQuery('html, body').stop().animate({
                    'scrollTop': old_offset.top - 100
                }, 500, 'swing', function () {

                });
                //////console.log(status);
            }
        });

        jQuery('.switchi').click(function () {
            var toggleBlock = jQuery(this).attr('data-toggle-block');
            //console.log(toggleBlock);
            if (toggleBlock === "true" && status) {
                //console.log(this);
                var block2 = jQuery(this).closest('.cal-block');
                //console.log(jQuery(block2));
                var toggleButton2 = jQuery(block2).find('.toggle-display');
                var targetId2 = jQuery(toggleButton2).attr("data-id");
                status = toggleElement(true, targetId2, delay);
            }
        });
    });
    
    jQuery('.ts_strips').each(function(){
        var tsStrips = jQuery(this);
        var active=false;
        var color= jQuery(this).attr('data-color');
        var labels = jQuery(this).attr('data-labels');
        var values = jQuery(this).attr('data-values');
        var labelsArr = labels.split(',');
        var valuesArr = values.split(',');
        var input = jQuery(this).attr('data-input');
        var minInput = "#min-"+input;
        var maxInput = "#max-"+input;
        
        
        var stripTable = "<div class='striptable'><div class='striprow'>";
        for(var i=0; i< labelsArr.length; i++){
            var row = "<div class='stripcell'><div class='strip_label'>"+labelsArr[i]+"</div></div>";
            stripTable = stripTable + row;
        }
        stripTable = stripTable + "</div><div class='striprow'>";
        for(var j=0; j< i ; j++){
            var row = "<div class='stripcell'><div class='strip_block' data-val='"+valuesArr[j]+"'></div></div>";
            stripTable = stripTable + row;
        }
        stripTable = stripTable + "</div></div>";
        jQuery(this).append(stripTable);
        jQuery(tsStrips).find('.strip_block').css('background-color',color);
        
        jQuery(this).find('.strip_block').click(function(){
            
            var val = jQuery(this).attr('data-val');
            var vals = val.split('-');
            var minVal, maxVal;
            if(vals[0] === 'min'){
                minVal = 0;
            }else{
                minVal = parseInt(vals[0]);
            }
            
            if(vals[1] === 'max'){
                maxVal = -1;
            }else{
                maxVal = parseInt(vals[1]);
            }
            console.log(minInput + minVal);
            var parentCell = jQuery(this).closest('.stripcell');
            if(jQuery(parentCell).hasClass('active')){
                jQuery(parentCell).removeClass('active');
                jQuery(tsStrips).siblings(minInput).val("");
                jQuery(tsStrips).siblings(maxInput).val("");
                
            }else{
                jQuery(parentCell).addClass('active').siblings().removeClass('active');
                jQuery(tsStrips).siblings(minInput).val(minVal);
                jQuery(tsStrips).siblings(maxInput).val(maxVal);
            }
        });
    });
});