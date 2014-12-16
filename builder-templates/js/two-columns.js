/* global jQuery, _ */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function (window, $, _, oneApp, $oneApp) {
	'use strict';

	oneApp.TextView = oneApp.SectionView.extend({
		events: function() {
			return _.extend({}, oneApp.SectionView.prototype.events, {
				'change .ttfmake-text-columns' : 'handleColumns'
			});
		},

		handleColumns : function (evt) {
			evt.preventDefault();

			var columns = $(evt.target).val(),
				$stage = $('.ttfmake-text-columns-stage', this.$el);

			$stage.removeClass('ttfmake-text-columns-1 ttfmake-text-columns-2 ttfmake-text-columns-3 ttfmake-text-columns-4');
			$stage.addClass('ttfmake-text-columns-' + parseInt(columns, 10));
		}
	});

	// Makes gallery items sortable
	oneApp.initializeTextColumnSortables = function(view) {
		var $selector;
		view = view || '';

		if (view.$el) {
			$selector = $('.ttfmake-text-columns-stage', view.$el);
		} else {
			$selector = $('.ttfmake-text-columns-stage');
		}

		$selector.sortable({
			handle: '.ttfmake-sortable-handle',
			placeholder: 'sortable-placeholder',
			forcePlaceholderSizeType: true,
			distance: 2,
			tolerance: 'pointer',
			zIndex: 99999,
			start: function (event, ui) {
				// Set the height of the placeholder to that of the sorted item
				var $item = $(ui.item.get(0)),
					$stage = $item.parents('.ttfmake-text-columns-stage'),
					addClass = '';

				// If text item, potentially add class to stage
				if ($item.hasClass('ttfmake-text-column')) {
					if ($item.hasClass('ttfmake-column-width-two-thirds')) {
						addClass = 'current-item-two-thirds';
					} else if ($item.hasClass('ttfmake-column-width-one-third')) {
						addClass = 'current-item-one-third';
					} else if ($item.hasClass('ttfmake-column-width-one-fourth')) {
						addClass = 'current-item-one-fourth';
					} else if ($item.hasClass('ttfmake-column-width-three-fourths')) {
						addClass = 'current-item-three-fourths';
					} else if ($item.hasClass('ttfmake-column-width-one-half')) {
						addClass = 'current-item-one-half';
					}

					$stage.addClass(addClass);
				}

				$('.sortable-placeholder', $stage)
					.height(parseInt($item.height(), 10) - 2) // -2 to account for placeholder border
					.css({
						'flex': $item.css('flex'),
						'-webkit-flex': $item.css('-webkit-flex')
					});
			},
			stop: function (event, ui) {
				var $item = $(ui.item.get(0)),
					$section = $item.parents('.ttfmake-section'),
					$stage = $('.ttfmake-section-body', $section),
					$columnsStage = $item.parents('.ttfmake-text-columns-stage'),
					$orderInput = $('.ttfmake-text-columns-order', $stage),
					id = $section.attr('data-id'),
					column = $item.attr('data-id'),
					i;

				oneApp.setOrder($(this).sortable('toArray', {attribute: 'data-id'}), $orderInput);

				// Label the columns according to the position they are in
				i = 1;
				$('.ttfmake-text-column', $stage).each(function(){
					$(this)
						.removeClass('ttfmake-text-column-position-1 ttfmake-text-column-position-2 ttfmake-text-column-position-3 ttfmake-text-column-position-4')
						.addClass('ttfmake-text-column-position-' + i);
					i++;
				});

				// Remove the temporary classes from stage
				$columnsStage.removeClass('current-item-two-thirds current-item-one-third current-item-one-fourth current-item-three-fourths current-item-one-half');

				setTimeout(function() {
					oneApp.initFrame(id + '-' + column);
				}, 100);
			}
		});
	};

	// Initialize the sortables
	$oneApp.on('afterSectionViewAdded', function(evt, view) {
		if ('text' === view.model.get('sectionType')) {
			oneApp.initializeTextColumnSortables(view);

			// Initialize the iframes
			var $frames = $('iframe', view.$el),
				link = oneApp.getFrameHeadLinks(),
				id, $this;

			$.each($frames, function() {
				$this = $(this);
				id = $this.attr('id').replace('ttfmake-iframe-', '');
				oneApp.initFrame(id, link);
			});
		}
	});

	// Initialize sortables for current columns
	oneApp.initializeTextColumnSortables();
})(window, jQuery, _, oneApp, $oneApp);