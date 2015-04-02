(function($){
	$(window).load(function() {
		var $open_sans_control = $('#customize-control-spine_options-open_sans');

		$open_sans_control.find('label:first-of-type input').on('click', function() {

			jQuery('#customize-control-spine_open_sans-400 input,#customize-control-spine_open_sans-400italic input,#customize-control-spine_open_sans-700 input').addClass('checked').prop('checked', true).prop('disabled', true);

		});

		$open_sans_control.find('label:last-of-type input').on('click', function() {

			jQuery('#customize-control-spine_open_sans-400 input,#customize-control-spine_open_sans-400italic input,#customize-control-spine_open_sans-700 input').prop('disabled', false);

		});

	});
}(jQuery));