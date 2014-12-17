/* global jQuery, _ */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function (window, $, _, oneApp) {
	'use strict';

	oneApp.OverlayView = Backbone.View.extend({
		events: function() {
			return _.extend({}, oneApp.SectionView.prototype.events, {
				'click .ttfmake-overlay-close-action' : 'closeOnClick'
			});
		},

		open: function() {
			$oneApp.trigger('overlayOpen', this.$el);
			this.$el.show();

			// Auto focus on the editor
			var focusOn = (oneApp.isVisualActive()) ? tinyMCE.get('make') : oneApp.cache.$makeTextArea;
			focusOn.focus();
		},

		close: function() {
			$oneApp.trigger('overlayClose', this.$el);
			this.$el.hide();

			// Pass the new content to the iframe and textarea
			oneApp.setTextArea(oneApp.getActiveTextAreaID());

			if ('' !== oneApp.getActiveiframeID()) {
				oneApp.filliframe(oneApp.getActiveiframeID());
			}

			this.toggleHasContent();
		},

		closeOnClick: function(e) {
			e.preventDefault();
			this.close();
		},

		toggleHasContent: function(textareaID) {
			textareaID = textareaID || oneApp.getActiveTextAreaID();

			var link = $('.edit-content-link[data-textarea="' + textareaID + '"]'),
				content = oneApp.getMakeContent();

			if ('' !== content) {
				link.addClass('item-has-content');
			} else {
				link.removeClass('item-has-content');
			}
		}
	});

	// Initialize available gallery items
	oneApp.initOverlayViews = function () {
		oneApp.tinymceOverlay = new oneApp.OverlayView({
			el: $('#ttfmake-tinymce-overlay')
		});
	};

	// Initialize the views when the app starts up
	oneApp.initOverlayViews();
})(window, jQuery, _, oneApp);