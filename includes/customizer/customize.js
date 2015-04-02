(function($){
	$(document).ready(function(){
		var $accordion_section = $('.accordion-section label input');

		$accordion_section.focusin( function() { $(this).closest('label').addClass('focused'); } );
		$accordion_section.focusout( function() { $(this).closest('label').removeClass('focused'); } );

		$('#customize-control-social_spot_one').find('input').attr('placeholder','http://www.facebook.com/wsupullman');
		$('#customize-control-social_spot_two').find('input').attr('placeholder','http://twitter.com/wsupullman');
		$('#customize-control-social_spot_three').find('input').attr('placeholder','http://youtube.com/washingtonstateuniv');
		$('#customize-control-social_spot_four').find('input').attr('placeholder','http://social.wsu.edu');
	});
}(jQuery));