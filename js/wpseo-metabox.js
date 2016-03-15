(function($){

	$( '#yoast_wpseo_title' ).val( '' );

	$(window).load(function() {

		$( '#yoast_wpseo_title' ).remove();
		$( '#snippet-editor-title' ).prop({
  			disabled: true,
			placeholder: 'Please edit the post title.',
			value: ''
		});

		// Hide the wpseo slug field but otherwise leave it alone
		// because it's bound to the default WP slug field.
		$( '#snippet-editor-slug' ).before( '<input type="text" class="snippet-editor__input" value="" placeholder="Please edit the permalink." disabled="disabled">' ).hide();

	});

}(jQuery));
