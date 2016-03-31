(function($,window){
	/**
	 * Look for any sections with background images stored as data attributes
	 * and convert the data attribute into inline CSS for that section.
	 */
	process_section_backgrounds = function() {
		var $bg_sections = $('.section-wrapper-has-background');

		$bg_sections.each( function() {
			var background_image = $(this).data('background'),
				mobile_background_image = $(this).data('background-mobile');
			if ( 792 > $(window).width() && mobile_background_image ) {
				$(this).css('background-image', 'url(' + mobile_background_image + ')' );
			} else if ( background_image ) {
				$(this).css('background-image', 'url(' + background_image + ')' );
			}
		});
	};

	$(document).ready( function() {
		process_section_backgrounds();
	});
}(jQuery,window));
