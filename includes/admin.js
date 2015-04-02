(function($){
	$(window).load(function() {

		jQuery("#customize-control-spine_options-open_sans label:first-of-type input").click( function() {

			jQuery('#customize-control-spine_open_sans-400 input,#customize-control-spine_open_sans-400italic input,#customize-control-spine_open_sans-700 input').addClass('checked').prop('checked', true).prop('disabled', true);

		});

		jQuery("#customize-control-spine_options-open_sans label:last-of-type input").click( function() {

			jQuery('#customize-control-spine_open_sans-400 input,#customize-control-spine_open_sans-400italic input,#customize-control-spine_open_sans-700 input').prop('disabled', false);

		});

	});
}(jQuery));