/* global Backbone, jQuery, _ */
var oneApp = oneApp || {};

(function (window, Backbone, $, _, oneApp) {
	'use strict';

	var Sections = Backbone.Collection.extend({
		model: oneApp.SectionModel,

		$stage: $('#ttfmake-stage'),

		toggleStageClass: function() {
			var sections = $('.ttfmake-section', this.$stage).length;

			if (sections > 0) {
				this.$stage.removeClass('ttfmake-stage-closed');
			} else {
				this.$stage.addClass('ttfmake-stage-closed');
			}
		}
	});

	oneApp.sections = new Sections();
})(window, Backbone, jQuery, _, oneApp);