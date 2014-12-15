/* global Backbone, jQuery, _, ttfmakeBuilderData, setUserSetting, deleteUserSetting */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function (window, Backbone, $, _, oneApp, $oneApp) {
	'use strict';

	oneApp.MenuView = Backbone.View.extend({
		el: '#ttfmake-menu',

		$stage: $('#ttfmake-stage'),

		$document: $(window.document),

		$scrollHandle: $('html, body'),

		$pane: $('.ttfmake-menu-pane'),

		initialize: function () {
			this.listenTo(oneApp.sections, 'add', this.addOne);
		},

		events: {
			'click .ttfmake-menu-list-item-link': 'addSection'
		},

		addSection: function (evt) {
			evt.preventDefault();

			var $evt = $(evt),
				$target = $($evt.get(0).currentTarget),
				sectionType = $target.attr('data-section').replace(/\W/g, ''); // Get and sanitize section

			// Add a new model to the collection with the specified section type
			oneApp.sections.create({
				sectionType: sectionType,
				id: new Date().getTime()
			});
		},

		addOne: function (section) {
			// Ensure that a view exists for the section, otherwise show the generic view
			var modelViewName = section.get('viewName') + 'View',
				viewName = (true === oneApp.hasOwnProperty(modelViewName)) ? modelViewName : 'SectionView',
				view, html;

			// Create view
			view = new oneApp[viewName]({
				model: section
			});

			// Append view
			html = view.render().el;
			this.$stage.append(html);

			$oneApp.trigger('afterSectionViewAdded', view);

			// Scroll to added view and focus first input
			oneApp.scrollToAddedView(view);

			oneApp.sections.toggleStageClass();
			oneApp.addOrderValue(section.get('id'), oneApp.cache.$sectionOrder);
		},

		menuToggle: function(evt) {
			evt.preventDefault();
			var id = ttfmakeBuilderData.pageID,
				key = 'ttfmakemt' + parseInt(id, 10);

			// Open it down
			if (this.$pane.is(':hidden')) {
				this.$pane.slideDown({
					duration: oneApp.options.openSpeed,
					easing: 'easeInOutQuad',
					complete: function() {
						deleteUserSetting( key );
						this.$el.addClass('ttfmake-menu-opened').removeClass('ttfmake-menu-closed');
					}.bind(this)
				});

			// Close it up
			} else {
				this.$pane.slideUp({
					duration: oneApp.options.closeSpeed,
					easing: 'easeInOutQuad',
					complete: function() {
						setUserSetting( key, 'c' );
						this.$el.addClass('ttfmake-menu-closed').removeClass('ttfmake-menu-opened');
					}.bind(this)
				});
			}
		}
	});
})(window, Backbone, jQuery, _, oneApp, $oneApp);