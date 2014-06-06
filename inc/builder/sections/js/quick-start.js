/* global jQuery */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function($, $oneApp) {
	'use strict';

	var layoutTemplates = {
		init: function() {
			var $messageBox = $('.ttfmp-import-message');

			$oneApp.on('afterSectionViewAdded', function() {
				$messageBox.addClass('ttfmp-import-message-hide');
			});

			$oneApp.on('afterSectionViewRemoved', function() {
				if ($('.ttfmake-section').length < 1) {
					$messageBox.removeClass('ttfmp-import-message-hide');
				}
			});
		}
	};

	layoutTemplates.init();
})(jQuery, $oneApp);