(function($){
	$(window).load(function() {
		var $open_sans_control = $('#customize-control-spine_options-open_sans'),
			$default_fonts = $('#customize-control-spine_open_sans-400, #customize-control-spine_open_sans-400italic, #customize-control-spine_open_sans-700, #customize-control-spine_open_sans-700italic');

		/**
		 * If Open Sans is disabled when the customizer loads, hide all of the individual
		 * font selections. If it is enabled, ensure that the defaults are checked.
		 */
		if ( false === $open_sans_control.find('label:first-of-type input').prop('checked') ) {
			$open_sans_control.parent('ul').find('.customize-control-checkbox').hide();
		} else {
			$default_fonts.find( 'input' ).addClass( 'checked' ).prop( 'checked', true ).prop( 'disabled', true );
		}

		// Show individual font options when Open Sans is enabled, check defaults.
		$open_sans_control.find('label:first-of-type input').on('click', function() {
			$open_sans_control.parent('ul').find('.customize-control-checkbox').show();
			$default_fonts.find( 'input' ).addClass( 'checked' ).prop( 'checked', true ).prop( 'disabled', true );
		});

		// Hide individual font options when Open Sans is disabled.
		$open_sans_control.find('label:last-of-type input').on('click', function() {
			$open_sans_control.parent('ul').find('.customize-control-checkbox').hide();
		});

	});

	/**
	 * Toggle the display of the options to show a front page title or posts page title. These
	 * options should only appear when a static page is selected as the option for Front Page.
	 */
	wp.customize( 'show_on_front', function( setting ) {
		setting.bind( function( value )  {
			if ( 'page' === value ) {
				$('#customize-control-spine_options-front_page_title').show();
				$('#customize-control-spine_options-page_for_posts_title').show();
			} else {
				$('#customize-control-spine_options-page_for_posts_title').hide();
				$('#customize-control-spine_options-front_page_title').hide();
			}
		});
	} );
}(jQuery));
