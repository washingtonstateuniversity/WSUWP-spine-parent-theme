(function($){
	$(window).load(function() {
		var $open_sans_control = $('#customize-control-spine_options-open_sans'),
			$default_fonts = $('#customize-control-spine_open_sans-400 input,#customize-control-spine_open_sans-400italic input,#customize-control-spine_open_sans-700 input');

		/**
		 * If Open Sans is disabled when the customizer loads, hide all of the individual
		 * font selections. If it is enabled, ensure that the defaults are checked.
		 */
		if ( false === $open_sans_control.find('label:first-of-type input').prop('checked') ) {
			$open_sans_control.parent('ul').find('.customize-control-checkbox').hide();
		} else {
			$default_fonts.addClass('checked').prop('checked', true).prop('disabled', true);
		}

		// Show individual font options when Open Sans is enabled, check defaults.
		$open_sans_control.find('label:first-of-type input').on('click', function() {
			$open_sans_control.parent('ul').find('.customize-control-checkbox').show();
			$default_fonts.addClass('checked').prop('checked', true).prop('disabled', true);
		});

		// Hide individual font options when Open Sans is disabled.
		$open_sans_control.find('label:last-of-type input').on('click', function() {
			$open_sans_control.parent('ul').find('.customize-control-checkbox').hide();
		});

	});
}(jQuery));