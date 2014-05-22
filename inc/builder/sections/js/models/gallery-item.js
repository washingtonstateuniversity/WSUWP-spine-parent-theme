/* global Backbone, jQuery, _ */
var oneApp = oneApp || {};

(function (window, Backbone, $, _, oneApp) {
	'use strict';

	oneApp.GalleryItemModel = Backbone.Model.extend({
		defaults: {
			id: '',
			parentID: ''
		}
	});

	// Set up this model as a "no URL model" where data is not synced with the server
	oneApp.GalleryItemModel.prototype.sync = function () { return null; };
	oneApp.GalleryItemModel.prototype.fetch = function () { return null; };
	oneApp.GalleryItemModel.prototype.save = function () { return null; };
})(window, Backbone, jQuery, _, oneApp);