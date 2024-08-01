(function($) {
	"use strict";
	
	// Preview param
	$('.mnky_preview input[type=radio]').click(function() {
		
		var param_name = $(this).attr('class'),
		selected = $(this).val();

		$('#mnky_selected_' + param_name).val(selected).change();
		
	});	
	
	// Category param
	$('.mnky_categories span').click(function() {
		$(this).toggleClass('selected-cat');
		
		var param_name = $(this).parent().data('param-name');
		var all_selected = $('.mnky_categories.'+ param_name +' span.selected-cat').map(function() {
			return this.id;
		}).get().join(', ');
		
		$('#mnky_selected_' + param_name).val(all_selected);
	});	
	
	// Tag param
	$('.mnky_tags span').click(function() {
		$(this).toggleClass('selected-tag');
		
		var param_name = $(this).parent().data('param-name');
		var all_selected = $('.mnky_tags.'+ param_name +' span.selected-tag').map(function() {
			return this.id;
		}).get().join(', ');
		
		$('#mnky_selected_' + param_name).val(all_selected);
	});

})(jQuery);