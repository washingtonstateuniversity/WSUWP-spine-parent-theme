/* global jQuery, _ */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function (window, $, _, oneApp, $oneApp) {
	'use strict';

	oneApp.BannerView = oneApp.SectionView.extend({

		events: function() {
			return _.extend({}, oneApp.SectionView.prototype.events, {
				'click .ttfmake-add-slide' : 'addSlide'
			});
		},

		addSlide: function (evt, params) {
			evt.preventDefault();

			var view, html;

			// Create view
			view = new oneApp.BannerSlideView({
				model: new oneApp.BannerSlideModel({
					id: new Date().getTime(),
					parentID: this.getParentID()
				})
			});

			// Append view
			html = view.render().el;
			$('.ttfmake-banner-slides-stage', this.$el).append(html);

			// Only scroll and focus if not triggered by the pseudo event
			if ( ! params ) {
				// Scroll to added view and focus first input
				oneApp.scrollToAddedView(view);
			}

			// Initiate the color picker
			oneApp.initializeBannerSlidesColorPicker(view);

			// Add the section value to the sortable order
			oneApp.addOrderValue(view.model.get('id'), $('.ttfmake-banner-slide-order', $(view.$el).parents('.ttfmake-banner-slides')));
		},

		getParentID: function() {
			var idAttr = this.$el.attr('id'),
				id = idAttr.replace('ttfmake-section-', '');

			return parseInt(id, 10);
		}
	});

	// Makes banner slides sortable
	oneApp.initializeBannerSlidesSortables = function(view) {
		var $selector;
		view = view || '';

		if (view.$el) {
			$selector = $('.ttfmake-banner-slides-stage', view.$el);
		} else {
			$selector = $('.ttfmake-banner-slides-stage');
		}

		$selector.sortable({
			handle: '.ttfmake-sortable-handle',
			placeholder: 'sortable-placeholder',
			forcePlaceholderSizeType: true,
			distance: 2,
			tolerance: 'pointer',
			start: function (event, ui) {
				// Set the height of the placeholder to that of the sorted item
				var $item = $(ui.item.get(0)),
					$stage = $item.parents('.ttfmake-banner-slides-stage');

				$('.sortable-placeholder', $stage).height($item.height());
			},
			stop: function (event, ui) {
				var $item = $(ui.item.get(0)),
					$stage = $item.parents('.ttfmake-banner-slides'),
					$orderInput = $('.ttfmake-banner-slide-order', $stage);

				oneApp.setOrder($(this).sortable('toArray', {attribute: 'data-id'}), $orderInput);
			}
		});
	};

	// Initialize the color picker
	oneApp.initializeBannerSlidesColorPicker = function (view) {
		var $selector;
		view = view || '';

		if (view.$el) {
			$selector = $('.ttfmake-configuration-color-picker', view.$el);
		} else {
			$selector = $('.ttfmake-configuration-color-picker');
		}

		$selector.wpColorPicker();
	};

	// Initialize the sortables
	$oneApp.on('afterSectionViewAdded', function(evt, view) {
		if ('banner' === view.model.get('sectionType')) {
			// Add an initial slide item
			$('.ttfmake-add-slide', view.$el).trigger('click', {type: 'pseudo'});

			// Initialize the sortables
			oneApp.initializeBannerSlidesSortables(view);
		}
	});

	// Initialize available slides
	oneApp.initBannerSlideViews = function ($el) {
		$el = $el || '';
		var $slides = ('' === $el) ? $('.ttfmake-banner-slide') : $('.ttfmake-banner-slide', $el);

		$slides.each(function () {
			var $item = $(this),
				idAttr = $item.attr('id'),
				id = $item.attr('data-id'),
				$section = $item.parents('.ttfmake-section'),
				parentID = $section.attr('data-id'),
				model, view;

			// Build the model
			model = new oneApp.BannerSlideModel({
				id: id,
				parentID: parentID
			});

			// Build the view
			view = new oneApp.BannerSlideView({
				model: model,
				el: $('#' + idAttr),
				serverRendered: true
			});

			oneApp.initializeBannerSlidesColorPicker(view);
		});

		oneApp.initializeBannerSlidesSortables();
	};

	// Initialize the views when the app starts up
	oneApp.initBannerSlideViews();
})(window, jQuery, _, oneApp, $oneApp);