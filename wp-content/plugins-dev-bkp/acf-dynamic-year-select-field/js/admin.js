(function($){
	
	function setup_dynamic_year_field(el) {
		$(document).on('change', '.oldest_year-method, .newest_year-method', function(evt) {
			var parent = $(this).closest('ul');
		
			if($(this).val()=='exact') {
				$('.oldest_year-exact_year, .newest_year-exact_year', parent).closest('li').show();
				$('.oldest_year-relative_year, .oldest_year-relative_year_direction, .newest_year-relative_year, .newest_year-relative_year_direction', parent).closest('li').hide();
			} else {
				$('.oldest_year-exact_year, .newest_year-exact_year', parent).closest('li').hide();
				$('.oldest_year-relative_year, .oldest_year-relative_year_direction, .newest_year-relative_year, .newest_year-relative_year_direction', parent).closest('li').show();
			}
		});
		
		setInterval(function() {
			$('.oldest_year-method, .newest_year-method').change();
		}, 100);
	}
	
	//determine which ACF version to use
	if( typeof acf.add_action !== 'undefined' ) {
		acf.add_action('open_field change_field_type', function(el){
			setup_dynamic_year_field(el);
		});
	} else {
		$(document).on('acf/field_form-open', function(i, el){
			setup_dynamic_year_field(el);
		});
	}
	
})(jQuery);