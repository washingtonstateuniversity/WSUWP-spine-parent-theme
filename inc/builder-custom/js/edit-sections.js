(function($){
	// text, blank, banner, gallery
	$('.ttfmake-menu-list-item').each(
		function(){
			var sections = [ 'text', 'blank', 'banner', 'gallery' ];
			var link = $(this).find('a');
			var section = $(link).attr('data-section');
			if ( $.inArray( section, sections ) >= 0 ) {
				jQuery(this).remove();
			}
		}
	);
}(jQuery));