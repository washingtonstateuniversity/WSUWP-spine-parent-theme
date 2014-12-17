/* global Backbone, jQuery, _, wp:true */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function (window, Backbone, $, _, oneApp, $oneApp) {
	'use strict';

	oneApp.SectionView = Backbone.View.extend({
		template: '',
		className: 'ttfmake-section ttfmake-section-open',
		$headerTitle: '',
		$titleInput: '',
		$titlePipe: '',
		serverRendered: false,
		$document: $(window.document),
		$scrollHandle: $('html, body'),

		events: {
			'click .ttfmake-section-toggle': 'toggleSection',
			'click .ttfmake-section-remove': 'removeSection',
			'keyup .ttfmake-section-header-title-input': 'constructHeader',
			'click .ttfmake-media-uploader-add': 'initUploader',
			'click .edit-content-link': 'openTinyMCEOverlay',
			'click .ttfmake-overlay-open': 'openConfigurationOverlay',
			'click .ttfmake-overlay-close': 'closeConfigurationOverlay'
		},

		initialize: function (options) {
			this.model = options.model;
			this.idAttr = 'ttfmake-section-' + this.model.get('id');
			this.serverRendered = ( options.serverRendered ) ? options.serverRendered : false;

			// Allow custom init functions
			$oneApp.trigger('viewInit', this);

			_.templateSettings = {
				evaluate   : /<#([\s\S]+?)#>/g,
				interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
				escape     : /\{\{([^\}]+?)\}\}(?!\})/g
			};
			this.template = _.template($('#tmpl-ttfmake-' + this.model.get('sectionType')).html());
		},

		render: function () {
			this.$el.html(this.template(this.model.toJSON()))
				.addClass('ttfmake-section-' + this.model.get('sectionType'))
				.attr('id', this.idAttr)
				.attr('data-id', this.model.get('id'))
				.attr('data-section-type', this.model.get('sectionType'));
			return this;
		},

		toggleSection: function (evt) {
			evt.preventDefault();

			var $this = $(evt.target),
				$section = $this.parents('.ttfmake-section'),
				$sectionBody = $('.ttfmake-section-body', $section),
				$input = $('.ttfmake-section-state', this.$el);

			if ($section.hasClass('ttfmake-section-open')) {
				$sectionBody.slideUp(oneApp.options.closeSpeed, function() {
					$section.removeClass('ttfmake-section-open');
					$input.val('closed');
				});
			} else {
				$sectionBody.slideDown(oneApp.options.openSpeed, function() {
					$section.addClass('ttfmake-section-open');
					$input.val('open');
				});
			}
		},

		removeSection: function (evt) {
			evt.preventDefault();

			// Confirm the action
			if (false === window.confirm(ttfmakeBuilderData.confirmString)) {
				return;
			}

			oneApp.removeOrderValue(this.model.get('id'), oneApp.cache.$sectionOrder);

			// Fade and slide out the section, then cleanup view and reset stage on complete
			this.$el.animate({
				opacity: 'toggle',
				height: 'toggle'
			}, oneApp.options.closeSpeed, function() {
				this.remove();
				oneApp.sections.toggleStageClass();
				$oneApp.trigger('afterSectionViewRemoved', this);
			}.bind(this));
		},

		constructHeader: function (evt) {
			if ('' === this.$headerTitle) {
				this.$headerTitle = $('.ttfmake-section-header-title', this.$el);
			}

			if ('' === this.$titleInput) {
				this.$titleInput = $('.ttfmake-section-header-title-input', this.$el);
			}

			if ('' === this.$titlePipe) {
				this.$titlePipe = $('.ttfmake-section-header-pipe', this.$el);
			}

			var input = this.$titleInput.val();

			// Set the input
			this.$headerTitle.html(_.escape(input));

			// Hide or show the pipe depending on what content is available
			if ('' === input) {
				this.$titlePipe.addClass('ttfmake-section-header-pipe-hidden');
			} else {
				this.$titlePipe.removeClass('ttfmake-section-header-pipe-hidden');
			}
		},

		initUploader: function (evt) {
			evt.preventDefault();

			var $this = $(evt.target),
				$parent = $this.parents('.ttfmake-uploader'),
				$placeholder = $('.ttfmake-media-uploader-placeholder', $parent),
				$input = $('.ttfmake-media-uploader-value', $parent),
				$remove = $('.ttfmake-media-uploader-remove', $parent),
				$add = $('.ttfmake-media-uploader-set-link', $parent),
				frame = frame || {},
				props, image;

			oneApp.$currentPlaceholder = $placeholder;

			// If the media frame already exists, reopen it.
			if ('function' === typeof frame.open) {
				frame.open();
				return;
			}

			// Create the media frame.
			frame = wp.media.frames.frame = wp.media({
				title: $this.data('title'),
				className: 'media-frame ttfmake-builder-uploader',
				button: {
					text: $this.data('buttonText')
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			frame.on('select', function () {
				// We set multiple to false so only get one image from the uploader
				var attachment = frame.state().get('selection').first().toJSON();

				// Remove the attachment caption
				attachment.caption = '';

				// Build the image
				props = wp.media.string.props(
					{},
					attachment
				);

				// Show the image
				$placeholder.css('background-image', 'url(' + attachment.url + ')');
				$parent.addClass('ttfmake-has-image-set');

				// Record the chosen value
				$input.val(attachment.id);

				// Hide the link to set the image
				$add.hide();

				// Show the remove link
				$remove.show();
			});

			// Finally, open the modal
			frame.open();
		},

		openTinyMCEOverlay: function (evt) {
			evt.preventDefault();
			oneApp.tinymceOverlay.open();

			var $target = $(evt.target),
				iframeID = ($target.attr('data-iframe')) ? $target.attr('data-iframe') : '',
				textAreaID = $target.attr('data-textarea');

			oneApp.setMakeContentFromTextArea(iframeID, textAreaID);
		},

		openConfigurationOverlay: function (evt) {
			evt.preventDefault();

			var self = this,
				$this = $(evt.target),
				$overlay = $($this.attr('data-overlay')),
				$wrapper = $('.ttfmake-overlay-wrapper', $overlay);

			$overlay.show(1, function(){
				$('.wp-color-result', $overlay).click().off('click');
				$( 'body' ).off( 'click.wpcolorpicker' );
				self.setSize($overlay, $wrapper);
				$overlay.find('input,select').filter(':first').focus();
			});

			$oneApp.trigger('ttfOverlayOpened', [this.model.get('sectionType'), $overlay]);
		},

		setSize: function($overlay, $wrapper) {
			var $body = $('.ttfmake-overlay-body', $wrapper),
				bodyHeight = $body.height(),
				wrapperHeight;

			wrapperHeight =
				parseInt(bodyHeight, 10) + // Body height
					20 + // Bottom padding
					30 + // Button height
					37; // Header height

			$wrapper
				.height(wrapperHeight)
				.css({
					'margin-top': -1 * parseInt(wrapperHeight/2, 10) + 'px'
				})
		},

		closeConfigurationOverlay: function (evt) {
			evt.preventDefault();

			var $this = $(evt.target),
				$overlay = $this.parents('.ttfmake-overlay');

			$overlay.hide();
		}
	});
})(window, Backbone, jQuery, _, oneApp, $oneApp);