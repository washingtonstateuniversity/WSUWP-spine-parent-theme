/* global jQuery, _ */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function (window, $, _, oneApp, $oneApp) {
	'use strict';

	oneApp.HalvesView = oneApp.SectionView.extend({
		events: function() {
			return _.extend({}, oneApp.SectionView.prototype.events, {
				'change .wsuwp-spine-halves-column' : 'handleColumns'
			});
		},

		handleColumns : function (evt) {
			evt.preventDefault();

		}
	});

	// Makes gallery items sortable
	oneApp.initializeHalvesColumnSortables = function(view) {
		var $selector;
		view = view || '';

		if (view.$el) {
			$selector = $('.wsuwp-spine-halves-stage', view.$el);
		} else {
			$selector = $('.wsuwp-spine-halves-stage');
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
					$stage = $item.parents('.wsuwp-spine-halves-stage');

				$('.sortable-placeholder', $stage).height($item.height());
				oneApp.disableEditors($item);
			},
			stop: function (event, ui) {
				var $item = $(ui.item.get(0)),
					$stage = $item.parents('.ttfmake-section-body'),
					$orderInput = $('.wsuwp-spine-halves-columns-order', $stage),
					i;

				oneApp.setOrder($(this).sortable('toArray', {attribute: 'data-id'}), $orderInput);
				oneApp.enableEditors($item);

				// Label the columns according to the position they are in
				i = 1;
				$('.wsuwp-spine-halves-column', $stage).each(function(){
					$(this)
						.removeClass('wsuwp-spine-halves-column-position-1 wsuwp-spine-halves-column-position-2')
						.addClass('wsuwp-spine-halves-column-position-' + i);
					i++;
				});
			}
		});
	};

	// Initialize the sortables
	$oneApp.on('afterSectionViewAdded', function(evt, view) {
		console.log(view.model.get('sectionType'));
		if ('wsuwphalves' === view.model.get('sectionType')) {
			oneApp.initializeHalvesColumnSortables(view);
		}
	});

	// Initialize sortables for current columns
	oneApp.initializeHalvesColumnSortables();
})(window, jQuery, _, oneApp, $oneApp);