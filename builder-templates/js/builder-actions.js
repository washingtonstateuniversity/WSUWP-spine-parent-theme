(function($){
	/**
	 * On visible advanced builder controls, setup a click event so that they can
	 * be hidden again.
	 */
	function setup_hide_toggle() {
		$('#ttfmake-builder' ).on('click', '.builder-toggle-advanced-visible', function(e) {
			e.preventDefault();
			$(this).parents('.ttfmake-section').children('.ttfmake-section-body').children('.builder-advanced-controls').hide();
			$(this ).html('Show advanced controls' ).removeClass('builder-toggle-advanced-visible' ).addClass('builder-toggle-advanced');

			setup_show_toggle();
		})
	}

	/**
	 * On hidden advanced builder controls (default), setup a click event so that
	 * these areas can be shown on individual sections.
	 */
	function setup_show_toggle() {
		$('#ttfmake-builder' ).on('click', '.builder-toggle-advanced', function(e){
			e.preventDefault();

			$(this).parents('.ttfmake-section').children('.ttfmake-section-body').children('.builder-advanced-controls').show();
			$(this ).html('Hide advanced controls' ).removeClass('builder-toggle-advanced' ).addClass('builder-toggle-advanced-visible');

			setup_hide_toggle();
		})
	}

	// Fire the default action on page load.
	setup_show_toggle();
}(jQuery));