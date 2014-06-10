/*global jQuery, tinyMCEPreInit, tinyMCE, QTags, quicktags, wpActiveEditor:true, ttfmakeMCE */
var oneApp = oneApp || {}, $oneApp = $oneApp || jQuery(oneApp);

(function ($, oneApp) {
	'use strict';

	// Initiate a new TinyMCE editor instance
	oneApp.initEditor = function (editorID, tempEditorID) {
		var mceInit = {},
			qtInit = {},
			$wrapper;

		/**
		 * Get the default values for this section type from the pre init object. Store them in a new object with
		 * the id of the section as the key.
		 */
		mceInit[editorID] = $.extend({}, tinyMCEPreInit.mceInit[tempEditorID]);
		qtInit[editorID] = $.extend({}, tinyMCEPreInit.qtInit[tempEditorID]);

		/**
		 * Append the new object to the pre init object. Doing so will provide the TinyMCE and quicktags code with
		 * the proper configuration information that is needed to init the editor.
		 */
		tinyMCEPreInit.mceInit = $.extend(tinyMCEPreInit.mceInit, mceInit);
		tinyMCEPreInit.qtInit = $.extend(tinyMCEPreInit.qtInit, qtInit);

		// Change the ID within the settings to correspond to the section ID
		tinyMCEPreInit.mceInit[editorID].elements = editorID;
		tinyMCEPreInit.qtInit[editorID].id = editorID;
		tinyMCEPreInit.mceInit[editorID].selector = '#' + editorID;

		// Only display the tinyMCE instance if in that mode. Else, the buttons will display incorrectly.
		if ('tinymce' === ttfmakeMCE) {
			tinyMCE.init(tinyMCEPreInit.mceInit[editorID]);
		}

		/**
		 * This is a bit of a hack. In the quicktags.js script, the buttons are only added when this variable is
		 * set to false. It is unclear exactly why this is the case. By setting this variable, the editors are
		 * properly initialized. Not taking this set will cause the quicktags to be missing.
		 */
		QTags.instances[0] = false;

		// Init the quicktags
		quicktags(tinyMCEPreInit.qtInit[editorID]);

		/**
		 * When using the different editors, the wpActiveEditor variables needs to be set. If it is not set, the
		 * Add Media buttons, as well as some other buttons will add content to the wrong editors. This strategy
		 * assumes that if you are clicking on the editor, it is the active editor.
		 */
		$wrapper = $('#wp-' + editorID + '-wrap');

		$wrapper.on('click', '.add_media', {id: editorID}, function (evt) {
			wpActiveEditor = evt.data.id;
		});

		$wrapper.on('click', {id: editorID}, function (evt) {
			wpActiveEditor = evt.data.id;
		});
	};

	oneApp.initAllEditors = function(section_id, section) {
		var $section = $('#' + section_id),
			$tinyMCEWrappers = $('.wp-editor-wrap', $section),
			sectionID = section.get('id');

		$tinyMCEWrappers.each(function() {
			var $el = $(this),
				editorID = $el.attr('id').replace('wp-', '').replace('temp-wrap', section.get('sectionType')).replace('-wrap', ''),
				tempEditorID = editorID.replace(sectionID, '') + 'temp';

			oneApp.initEditor(editorID, tempEditorID);
		});
	};

	oneApp.removeTinyMCE = function (id) {
		tinyMCE.execCommand( 'mceRemoveEditor', false, id );
	};

	oneApp.addTinyMCE = function (id) {
		tinyMCE.execCommand( 'mceAddEditor', false, id );
	};

	oneApp.syncEditorHeight = function(evt, baseEl) {
		baseEl = baseEl || 'iframe';

		var $this = $(evt.target),
			$parent = $this.parents('.wp-editor-wrap'),
			$iframe = $('.mceIframeContainer iframe', $parent),
			iframeHeight = $iframe.height(),
			$textarea = $('textarea', $parent),
			textareaHeight = $textarea.height();

		if ('iframe' === baseEl) {
			$textarea.height(parseInt(iframeHeight, 10) + 1);
		} else {
			$iframe.height(parseInt(textareaHeight, 10) - 1);
		}
	};

	oneApp.adjustEditorHeightOnClick = function(evt) {
		evt.preventDefault();

		var $this = $(evt.target),
			baseEl = ($this.hasClass('switch-html')) ? 'iframe' : 'textarea';

		this.syncEditorHeight(evt, baseEl);
	};

})(jQuery, oneApp);