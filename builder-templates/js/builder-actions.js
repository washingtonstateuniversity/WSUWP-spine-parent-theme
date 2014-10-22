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
		});
	}

	var toggle_column = function(e) {
		e.preventDefault();

		var $this = $( e.target );
		var column_parent = $($this.parents('.wsuwp-spine-builder-column'));
		var column_visibility = column_parent.find('.wsuwp-column-visible' );

		column_parent.find('.wsuwp-column-content' ).toggle();

		if ( 'visible' === column_visibility.val() ) {
			column_visibility.val('invisible');
		} else {
			column_visibility.val('visible');
		}

		if ( $this.hasClass( 'wsuwp-toggle-closed' ) ) {
			$this.removeClass( 'wsuwp-toggle-closed' );
		} else {
			$this.addClass( 'wsuwp-toggle-closed' );
		}
	};

	/**
	 * Setup a toggle switch on individual columns within sections.
	 */
	var setup_column_toggle = function() {
		$('#wpbody').on('click', '.wsuwp-column-toggle', toggle_column );
	};

	// Fire the default actions on page load.
	setup_show_toggle();
	setup_column_toggle();
}(jQuery));