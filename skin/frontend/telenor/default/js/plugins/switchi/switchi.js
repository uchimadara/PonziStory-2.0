jQuery(document).ready(function(){

	function disableSliders(block){
		jQuery(block).append('<div class="darkClass"></div>');
		jQuery('.disabled .ui-slider').slider("disable");
		jQuery('.disabled .ui-slider-range').css("background-color", "#666666");
	}
	
	function enableSliders(block){
		jQuery(block).children('.darkClass').remove();
		jQuery(block).find('.ui-slider').each(function(){
			jQuery(this).slider("enable");
			var targetColor = jQuery(this).attr('data-color');
			jQuery(this).children(".ui-slider-range").css("background-color",targetColor);
		});
	}
	
	function switchiNo(target){
		var parent = target.closest(".switchi");
		var toggleBlock = jQuery(parent).attr('data-toggle-block');
		var targetName = jQuery(parent).attr('data-name');
		
		if(toggleBlock === "true"){
			var block = target.closest(".cal-block");
			jQuery(block).addClass("disabled");
			disableSliders(block);
		}
		
		jQuery(parent).children("#" + targetName + "_no").addClass('active');
		jQuery("#" + targetName + "_no2").attr('checked', 'checked');
		jQuery(parent).children("#" + targetName + "_yes").removeClass('active');
		jQuery(parent).children(".switchi_button").removeClass('active');
	}
	
	function switchiYes(target){
		var parent = target.closest(".switchi");
		var toggleBlock = jQuery(parent).attr('data-toggle-block');
		var targetName = jQuery(parent).attr('data-name');
		
		if(toggleBlock === "true"){
			var block = target.closest(".cal-block");
			jQuery(block).removeClass("disabled");
			enableSliders(block);
		}
		jQuery(parent).children("#" + targetName + "_yes").addClass('active');
		jQuery("#" + targetName + "_yes2").attr('checked', 'checked');
		jQuery(parent).children("#" + targetName + "_no").removeClass('active');
		jQuery(parent).children(".switchi_button").addClass('active');
	}
	
	jQuery('.switchi').each(function(){
		var targetName = jQuery(this).attr('data-name');
		var yesLabel = jQuery(this).attr('data-yes');
		var noLabel = jQuery(this).attr('data-no');
		var $targetName = jQuery(this).attr('data-value'); 
		
		
		jQuery(this).append("<div id='"+targetName+"_no' class='switchi_radio_label active'>"+noLabel+"</div>");
		jQuery(this).append("<div class='switchi_button'></div>");
		jQuery(this).append("<div id='"+targetName+"_yes' class='switchi_radio_label'>"+yesLabel+"</div>");
		jQuery(this).append("<input name='"+targetName+"' id='"+targetName+"_no2' value='"+noLabel+"' type='radio'/>");
		jQuery(this).append("<input name='"+targetName+"' id='"+targetName+"_yes2' value='"+yesLabel+"' type='radio'/>");
		
		var switchiButton = jQuery(this).children('.switchi_button');
		if($targetName !== "true"){
			switchiNo(jQuery(switchiButton));
			$targetName = false;
		}else{
			switchiYes(jQuery(switchiButton));
			$targetName = true;
		}
		
		jQuery(this).children('input').hide();
		jQuery(this).children('.switchi_button').click(function(){
			if($targetName){
				switchiNo(jQuery(this));
				$targetName = false;
			}else{
				switchiYes(jQuery(this));
				$targetName = true;
			}
		});
		jQuery(this).children("#"+targetName+"_yes").click(function(){
			if(!($targetName)){
				switchiYes(jQuery(this));
				$targetName = true;
			}
		});
		jQuery(this).children("#"+targetName+"_no").click(function(){
			if($targetName){
				switchiNo(jQuery(this));
				$targetName = false;
			}
		});
	});
	
});