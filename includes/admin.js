(function($){
	$(window).load(function() {
		var $open_sans_control = $('#customize-control-spine_options-open_sans'),
			$default_fonts = $('#customize-control-spine_open_sans-400 input,#customize-control-spine_open_sans-400italic input,#customize-control-spine_open_sans-700 input');

		$open_sans_control.find('label:first-of-type input').on('click', function() {
			$default_fonts.addClass('checked').prop('checked', true).prop('disabled', true);
		});

		$open_sans_control.find('label:last-of-type input').on('click', function() {
			$default_fonts.prop('disabled', false);
		});

	});
}(jQuery));