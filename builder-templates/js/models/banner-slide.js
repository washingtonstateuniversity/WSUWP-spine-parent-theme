/* global Backbone, jQuery, _ */
var oneApp = oneApp || {};

(function (window, Backbone, $, _, oneApp) {
	'use strict';

	oneApp.BannerSlideModel = Backbone.Model.extend({
		defaults: {
			id: '',
			parentID: '',
			sectionType: 'bannerslide'
		}
	});

	// Set up this model as a "no URL model" where data is not synced with the server
	oneApp.BannerSlideModel.prototype.sync = function () { return null; };
	oneApp.BannerSlideModel.prototype.fetch = function () { return null; };
	oneApp.BannerSlideModel.prototype.save = function () { return null; };
})(window, Backbone, jQuery, _, oneApp);