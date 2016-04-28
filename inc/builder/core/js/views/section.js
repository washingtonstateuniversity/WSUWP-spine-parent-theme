/* global Backbone, jQuery, _, wp:true */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function (window, Backbone, $, _, oneApp, $oneApp) {
	'use strict';

	oneApp.SectionView = Backbone.View.extend({
		template: '',
		className: 'ttfmake-section ttfmake-section-open',
		$headerTitle: '',
		$sectionLabel: '',
		$titleInput: '',
		$titlePipe: '',
		serverRendered: false,
		$document: $(window.document),
		$scrollHandle: $('html, body'),

		events: {
			'click .ttfmake-section-toggle': 'toggleSection',
			'click .ttfmake-section-remove': 'removeSection',
			'click .spine-builder-section-configure': 'toggleOverlay',
			'click .spine-builder-overlay-close': 'toggleOverlay',
			'click .spine-builder-column-configure': 'toggleColumnOverlay',
			'click .spine-builder-column-overlay-close': 'toggleColumnOverlay',
			'keyup .wsuwp-builder-section-label': 'constructHeader',
			'keyup .ttfmake-section-header-title-input': 'constructHeader',
			'click .ttfmake-media-uploader-add': 'initUploader',
			'click .ttfmake-media-uploader-remove': 'removeImage',
			'click .wp-switch-editor': 'adjustEditorHeightOnClick'
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
			this.constructHeader();
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
				$input = $('.ttfmake-section-state', this.$el),
				$curHeight = $('#first').height();

			if ($section.hasClass('ttfmake-section-open')) {
				$sectionBody.animate({
					height: 0,
					paddingBottom: 0,
					paddingTop: 0
				}, oneApp.options.closeSpeed, function() {
					$section.removeClass('ttfmake-section-open');
					$input.val('closed');
				});
			} else {
				$sectionBody.animate({
					height: $sectionBody.get(0).scrollHeight + 40,
					paddingBottom: '20px',
					paddingTop: '20px'
				}, oneApp.options.openSpeed, function() {
					$sectionBody.css('height', 'auto');
					$section.addClass('ttfmake-section-open');
					$input.val('open');
				});
			}
		},

		toggleOverlay: function (evt) {
			evt.preventDefault();

			var $this = $(evt.target),
				$section = $this.parents('.ttfmake-section'),
				$sectionAdvanced = $('.spine-builder-overlay', $section);

			if ($sectionAdvanced.hasClass('spine-builder-overlay-open')) {
				$sectionAdvanced.removeClass('spine-builder-overlay-open');
			} else {
				$sectionAdvanced.addClass('spine-builder-overlay-open');
			}
		},

		toggleColumnOverlay: function (evt) {
			evt.preventDefault();

			var $this = $(evt.target),
				$column = $this.parents('.wsuwp-spine-builder-column'),
				$columnAdvanced = $('.spine-builder-column-overlay', $column);

			if ($columnAdvanced.hasClass('spine-builder-overlay-open')) {
				$columnAdvanced.removeClass('spine-builder-overlay-open');
			} else {
				$columnAdvanced.addClass('spine-builder-overlay-open');
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

		constructHeader: function () {
			if ('' === this.$headerTitle) {
				this.$headerTitle = $('.ttfmake-section-header-title', this.$el);
			}

			if ('' === this.$sectionLabel) {
				this.$sectionLabel = $('.wsuwp-builder-section-label', this.$el);
			}

			if ('' === this.$titleInput) {
				this.$titleInput = $('.ttfmake-section-header-title-input', this.$el);
			}

			if ('' === this.$titlePipe) {
				this.$titlePipe = $('.ttfmake-section-header-pipe', this.$el);
			}

			var input = this.$sectionLabel.val() ? this.$sectionLabel.val() : this.$titleInput.val();

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

			// If the media frame already exists, reopen it.
			if ('function' === typeof frame.open) {
				frame.open();
				return;
			}

			// Create the media frame.
			frame = wp.media.frames.frame = wp.media({
				title: $this.data('title'),
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

				// The URL property is blank, so complete it
				props.url = attachment.url;

				image = wp.media.string.image( props );

				// Show the image
				$placeholder.html(image);

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

		removeImage: function (evt) {
			evt.preventDefault();

			var $this = $(evt.target),
				$parent = $this.parents('.ttfmake-uploader'),
				$placeholder = $('.ttfmake-media-uploader-placeholder', $parent),
				$input = $('.ttfmake-media-uploader-value', $parent),
				$set = $('.ttfmake-media-uploader-add', $parent);

			// Remove the image
			$placeholder.empty();

			// Remove the value from the input
			$input.removeAttr('value');

			// Hide the remove link
			$this.hide();

			// Show the set link
			$set.show();
		},

		adjustEditorHeightOnClick: function (evt) {
			oneApp.adjustEditorHeightOnClick(evt);
		}
	});
})(window, Backbone, jQuery, _, oneApp, $oneApp);
