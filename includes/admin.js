/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {

	// Update the site title in real time...
	/* wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '#site-title a' ).html( newval );
		} );
	} );
	
	//Update the site description in real time...
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.site-description' ).html( newval );
		} );
	} );

	//Update site title color in real time...
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( newval ) {
			$('#site-title a').css('color', newval );
		} );
	} );

	//Update site background color...
	wp.customize( 'background_color', function( value ) {
		value.bind( function( newval ) {
			$('body').css('background-color', newval );
		} );
	} );
	
	//Update Spine color...
	wp.customize( 'spine_options[spine_color]', function( value ) {
		value.bind( function( newval ) {
			$('#jacket').addClass(newval);
		} );
	} );
	
	//Update site title color in real time...
	wp.customize( 'mytheme_options[link_textcolor]', function( value ) {
		value.bind( function( newval ) {
			$('a').css('color', newval );
		} );
	} ); */
	
	
	
} )( jQuery );

/* function expandArea() {
	
	jQuery(this).dblclick(expandedArea).parents('[class*="position"]').addClass('expanded').siblings().removeClass('expanded').addClass('condensed').dblclick(condensedArea);
	
	}

function expandedArea() {
	
	jQuery(this).dblclick(expandArea).parents('[class*="position"]').removeClass('expanded').siblings().on('dblclick', expandArea).removeClass('condensed').removeClass('condensed');
	
	}

function condensedArea() {
	
	jQuery(this).removeClass('condensed').siblings().removeClass('expanded').removeClass('condensed').on('dblclick', expandArea);
	
	}

jQuery(document).on('ready', function() {

	jQuery('.sortable-background').on('dblclick', expandArea);
		//jQuery(this).parents('[class*="position"]').siblings().toggleClass('expanded').on('dblclick', function() {
		//	jQuery(this).toggleClass('expanded').siblings().toggleClass('condensed');
		//});
	
	}); */