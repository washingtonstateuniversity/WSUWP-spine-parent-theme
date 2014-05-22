/* global Backbone, jQuery, _ */
var oneApp = oneApp || {};

(function (window, Backbone, $, _, oneApp) {
	'use strict';

	oneApp.BannerSlideView = Backbone.View.extend({
		template: '',
		className: 'ttfmake-banner-slide ttfmake-banner-slide-open',

		events: {
			'click .ttfmake-banner-slide-remove': 'removeItem',
			'click .ttfmake-banner-slide-toggle': 'toggleSection'
		},

		initialize: function (options) {
			this.model = options.model;
			this.idAttr = 'ttfmake-banner-slide-' + this.model.get('id');
			this.serverRendered = ( options.serverRendered ) ? options.serverRendered : false;
			this.template = _.template($('#tmpl-ttfmake-banner-slide').html());
		},

		render: function () {
			this.$el.html(this.template(this.model.toJSON()))
				.attr('id', this.idAttr)
				.attr('data-id', this.model.get('id'));
			return this;
		},

		removeItem: function (evt) {
			evt.preventDefault();

			var $stage = this.$el.parents('.ttfmake-banner-slides'),
				$orderInput = $('.ttfmake-banner-slide-order', $stage);

			oneApp.removeOrderValue(this.model.get('id'), $orderInput);

			// Fade and slide out the section, then cleanup view
			this.$el.animate({
				opacity: 'toggle',
				height: 'toggle'
			}, oneApp.options.closeSpeed, function() {
				this.remove();
			}.bind(this));
		},

		toggleSection: function (evt) {
			evt.preventDefault();

			var $this = $(evt.target),
				$section = $this.parents('.ttfmake-banner-slide'),
				$sectionBody = $('.ttfmake-banner-slide-body', $section),
				$input = $('.ttfmake-banner-slide-state', this.$el);

			if ($section.hasClass('ttfmake-banner-slide-open')) {
				$sectionBody.slideUp(oneApp.options.closeSpeed, function() {
					$section.removeClass('ttfmake-banner-slide-open');
					$input.val('closed');
				});
			} else {
				$sectionBody.slideDown(oneApp.options.openSpeed, function() {
					$section.addClass('ttfmake-banner-slide-open');
					$input.val('open');
				});
			}
		}
	});
})(window, Backbone, jQuery, _, oneApp);