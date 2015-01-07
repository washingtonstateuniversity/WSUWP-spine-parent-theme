<?php

// http://codex.wordpress.org/Plugin_API/Action_Reference/customize_register
// http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/

add_action( 'customize_controls_enqueue_scripts', 'spine_theme_customize_scripts' );
/**
 * Enqueue custom scripts and styles for use with the customizer.
 */
function spine_theme_customize_scripts() {
	wp_enqueue_style( 'customize-interface-styles', get_template_directory_uri() . '/includes/customizer/customize.css' );
	wp_enqueue_script( 'customize-interface-scripts', get_template_directory_uri() . '/includes/customizer/customize.js', array( 'jquery' ), '', true );
}

add_action('customize_register', 'spine_customize_register');
/**
 * Add custom settings and controls to the WP Customizer.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function spine_customize_register( $wp_customize ){

	$wp_customize->remove_section( 'title_tagline');
	$wp_customize->remove_section( 'nav');

	// Page Headers
	$wp_customize->add_section( 'section_main_header', array(
		'title'    => __( 'Main Header' ),
		'priority' => 20,
	));

	$wp_customize->add_setting('spine_options[global_main_header_sup]', array(
		'default' => '',
		'capability' => 'edit_theme_options',
		'type' => 'option'
	));

	$wp_customize->add_control('global_main_header_sup', array(
		'label' => 'Global Header Top (Optional)',
		'section' => 'section_main_header',
		'settings' => 'spine_options[global_main_header_sup]',
		'type' => 'text',
		'priority' => 25
	));

	$wp_customize->add_setting('spine_options[global_main_header_sub]', array(
		'default' => '',
		'capability' => 'edit_theme_options',
		'type' => 'option'
	));

	$wp_customize->add_control('global_main_header_sub', array(
		'label' => 'Global Header Bottom (Optional)',
		'section' => 'section_main_header',
		'settings' => 'spine_options[global_main_header_sub]',
		'type' => 'text',
		'priority' => 30
	));

	$wp_customize->add_setting('spine_options[main_header_show]', array(
		'default'        => true,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_options[main_header_show]', array(
		'label'      => __('Show main header', 'spine'),
		'section'    => 'section_main_header',
		'settings'   => 'spine_options[main_header_show]',
		'type'       => 'checkbox',
		'priority' => 35
	));

	$wp_customize->add_setting('spine_options[articletitle_show]', array(
		'default'        => true,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_options[articletitle_show]', array(
		'label'      => __('Show article title', 'spine'),
		'section'    => 'section_main_header',
		'settings'   => 'spine_options[articletitle_show]',
		'type'       => 'checkbox',
		'priority' => 40
	));

	$wp_customize->add_setting('spine_options[articletitle_header]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_options[articletitle_header]', array(
		'label'      => __('Use article title in main header', 'spine'),
		'section'    => 'section_main_header',
		'settings'   => 'spine_options[articletitle_header]',
		'type'       => 'checkbox',
		'priority' => 45
	));

	// Spine Options
	$wp_customize->add_section('section_spine_options', array(
		'title'    => __('Spine Options', 'spine'),
		'priority' => 124,
	));

	// Grid
	$wp_customize->add_setting('spine_options[grid_style]', array(
		'default'        => 'hybrid',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));
	$wp_customize->add_control('spine_grid_style', array(
		'label'      => __('Grid Behavior', 'spine'),
		'section'    => 'section_spine_options',
		'settings'   => 'spine_options[grid_style]',
		'type'       => 'radio',
		'choices'    => array(
			'fixed'  => 'Fixed',
			'hybrid' => 'Hybrid',
			'fluid'  => 'Fluid'
		),
	));

	// Signature
	$wp_customize->add_setting('spine_options[campus_location]', array(
		'default'        => '',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('campus_location', array(
		'label'      => __('Signature', 'spine'),
		'section'    => 'section_spine_options',
		'settings'   => 'spine_options[campus_location]',
		'type'       => 'select',
		'choices'    => array(
			''                       => 'Select Campus/Location',
			'extension'              => 'Extension',
			'globalcampus'           => 'Global Campus',
			'healthsciences-spokane' => 'Health Sciences Spokane',
			'spokane'                => 'Spokane',
			'tricities'              => 'Tri-Cities',
			'vancouver'              => 'Vancouver',
		),
	));

	// Spine Color
	$wp_customize->add_setting('spine_options[spine_color]', array(
		'default'        => 'white',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control( 'spine_color_select', array(
		'settings' => 'spine_options[spine_color]',
		'label'    => 'Spinal Column Color',
		'section'  => 'section_spine_options',
		'type'     => 'select',
		'choices'  => array(
			'white' => 'Default (white)',
			'lightest' => 'Lightest',
			'lighter' => 'Lighter',
			'light' => 'Light',
			'gray' => 'Gray',
			'dark' => 'Dark',
			'darker' => 'Darker',
			'darkest' => 'Darkest (black)',
			'crimson' => 'Crimson',
			'velum' => 'Transparent'
		),
	));

	// Bleed Spine Leftward
	$wp_customize->add_setting('spine_options[bleed]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_bleed', array(
		'label'      => __('Bleed Spine Left', 'spine'),
		'section'    => 'section_spine_options',
		'settings'   => 'spine_options[bleed]',
		'type'       => 'checkbox'
	));

	$wp_customize->add_section('static_front_page', array(
		'title'    => __('Front Page', 'static_front_page'),
	));

	// SOCIAL CHANNELS
	$wp_customize->add_section('section_spine_social', array(
		'title'    => __('Social Channels', 'spine'),
		'priority' => 300,
		'description'    => __( 'You can retain, replace, or remove social channels. Select "None" to remove/hide a location.' ),
	));

	// Location One
	$wp_customize->add_setting('spine_options[social_spot_one]', array( 'default' => 'https://www.facebook.com/WSUPullman', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('social_spot_one', array( 'section' => 'section_spine_social', 'settings' => 'spine_options[social_spot_one]', 'priority' => 302 ));

	$wp_customize->add_setting('spine_options[social_spot_one_type]', array( 'default' => 'facebook', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('social_spot_one_type', array(
		'label' => __('Location One', 'spine'),
		'section' => 'section_spine_social',
		'settings' => 'spine_options[social_spot_one_type]',
		'type' => 'select',
		'choices' => array('none' => 'None', 'directory' => 'Directory', 'facebook' => 'Facebook', 'flickr' => 'Flickr', 'googleplus' => 'Google Plus', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'pinterest' => 'Pinterest', 'tumblr' => 'Tumblr', 'twitter' => 'Twitter', 'vimeo' => 'Vimeo', 'youtube' => 'YouTube'),
		'priority' => 301,
	));

	// Location Two
	$wp_customize->add_setting('spine_options[social_spot_two]', array( 'default' => 'https://twitter.com/wsupullman', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('social_spot_two', array( 'section' => 'section_spine_social', 'settings' => 'spine_options[social_spot_two]', 'priority' => 304 ));

	$wp_customize->add_setting('spine_options[social_spot_two_type]', array( 'default' => 'twitter', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('social_spot_two_type', array(
		'label' => __('Location Two', 'spine'),
		'section' => 'section_spine_social',
		'settings' => 'spine_options[social_spot_two_type]',
		'type' => 'select',
		'choices' => array('none' => 'None', 'directory' => 'Directory', 'facebook' => 'Facebook', 'flickr' => 'Flickr', 'googleplus' => 'Google Plus', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'pinterest' => 'Pinterest', 'tumblr' => 'Tumblr', 'twitter' => 'Twitter', 'vimeo' => 'Vimeo', 'youtube' => 'YouTube'),
		'priority' => 303,
	));

	// Location Three
	$wp_customize->add_setting('spine_options[social_spot_three]', array( 'default' => 'https://www.youtube.com/washingtonstateuniv', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('social_spot_three', array( 'section' => 'section_spine_social', 'settings' => 'spine_options[social_spot_three]', 'priority' => 306 ));

	$wp_customize->add_setting('spine_options[social_spot_three_type]', array( 'default' => 'youtube', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('social_spot_three_type', array(
		'label' => __('Location Three', 'spine'),
		'section' => 'section_spine_social',
		'settings' => 'spine_options[social_spot_three_type]',
		'type' => 'select',
		'choices' => array('none' => 'None', 'directory' => 'Directory', 'facebook' => 'Facebook', 'flickr' => 'Flickr', 'googleplus' => 'Google Plus', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'pinterest' => 'Pinterest', 'tumblr' => 'Tumblr', 'twitter' => 'Twitter', 'vimeo' => 'Vimeo', 'youtube' => 'YouTube'),
		'priority' => 305,
	));

	// Location Four
	$wp_customize->add_setting('spine_options[social_spot_four]', array( 'default' => 'http://social.wsu.edu', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('social_spot_four', array( 'section' => 'section_spine_social', 'settings' => 'spine_options[social_spot_four]', 'priority' => 308 ));

	$wp_customize->add_setting('spine_options[social_spot_four_type]', array( 'default' => 'directory', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('social_spot_four_type', array(
		'label' => __('Location Four', 'spine'),
		'section' => 'section_spine_social',
		'settings' => 'spine_options[social_spot_four_type]',
		'type' => 'select',
		'choices' => array('none' => 'None', 'directory' => 'Directory', 'facebook' => 'Facebook', 'flickr' => 'Flickr', 'googleplus' => 'Google Plus', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'pinterest' => 'Pinterest', 'tumblr' => 'Tumblr', 'twitter' => 'Twitter', 'vimeo' => 'Vimeo', 'youtube' => 'YouTube'),
		'priority' => 307,
	));

	// Contact
	$wp_customize->add_section('section_spine_contact', array(
		'title'    => __('Contact Information', 'spine'),
		'priority' => 315,
		'description'    => __( 'This is the official contact for your website.' ),
	));

	$wp_customize->add_setting('spine_options[contact_department]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_department', array( 'label' => 'Your Unit (Dep., College, etc.)', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_department]', 'priority' => 405 ));

	$wp_customize->add_setting('spine_options[contact_url]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_url', array( 'label' => 'Your Unit URL (Optional)', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_url]', 'priority' => 406 ));

	$wp_customize->add_setting('spine_options[contact_streetAddress]', array( 'default' => 'PO Box 99164', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_streetAddress', array( 'label' => 'Your Address', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_streetAddress]', 'type' => 'text', 'priority' => 410 ));

	$wp_customize->add_setting('spine_options[contact_addressLocality]', array( 'default' => 'Pullman, WA', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_addressLocality', array( 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_addressLocality]', 'type' => 'text', 'priority' => 411 ));

	$wp_customize->add_setting('spine_options[contact_postalCode]', array( 'default' => '99164', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_postalCode', array( 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_postalCode]', 'type' => 'text', 'priority' => 411 ));

	$wp_customize->add_setting('spine_options[contact_telephone]', array( 'default' => '509-335-3564', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_telephone', array( 'label' => 'Best Phone Number', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_telephone]', 'type' => 'text', 'priority' => 415 ));

	$wp_customize->add_setting('spine_options[contact_email]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_email', array( 'label' => 'Best Email Address', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_email]', 'type' => 'text', 'priority' => 420 ));

	$wp_customize->add_setting('spine_options[contact_ContactPoint]', array( 'default' => 'http://contact.wsu.edu', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_ContactPoint', array( 'label' => 'Contact Page/Directory (Optional)', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_ContactPoint]', 'type' => 'text', 'priority' => 425 ));

	$wp_customize->add_setting('spine_options[contact_ContactPointTitle]', array( 'default' => 'Contact Page...', 'capability' => 'edit_theme_options', 'type' => 'option' ));
	$wp_customize->add_control('contact_ContactPointTitle', array( 'label' => 'Contact Link Title', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_ContactPointTitle]', 'type' => 'text', 'priority' => 426 ));

	// Advanced
	$wp_customize->add_section('section_spine_advanced_options', array(
		'title'    => __('Advanced Options', 'spine_advanced'),
		'priority' => 2000,
	));

	// Spine Version
	$wp_customize->add_setting('spine_options[spine_version]', array(
		'default'        => '1',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_version', array(
		'label'      => __('Spine Version', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[spine_version]',
		'type'       => 'select',
		'choices'    => array(
			'1'  => '1',
			'develop' => 'develop'
		),
	));

	// Large Format
	$wp_customize->add_setting('spine_options[large_format]', array(
		'default'        => '',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_large_format', array(
		'label'      => __('Large Format', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[large_format]',
		'type'       => 'select',
		'choices'    => array(
			''  => 'Default Width of 990px',
			' folio max-1188' => 'Max Width 1188px',
			' folio max-1386' => 'Max Width 1386px',
			' folio max-1584' => 'Max Width 1584px',
			' folio max-1782' => 'Max Width 1782px',
			' folio max-1980' => 'Max Width 1980px'
		),
	));

	// Cropped Spine
	$wp_customize->add_setting('spine_options[crop]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_crop', array(
		'label'      => __('Cropped Spine (homepage)', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[crop]',
		'type'       => 'checkbox'
	));

	// Spineless
	$wp_customize->add_setting('spine_options[spineless]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_spineless', array(
		'label'      => __('Spineless (homepage)', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[spineless]',
		'type'       => 'checkbox'
	));

	// Bleed Main Rightward
	$wp_customize->add_setting('spine_options[broken_binding]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_broken_binding', array(
		'label'      => __('Bleed Main Right', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[broken_binding]',
		'type'       => 'checkbox'
	));

	// Show full content or excerpts on archive pages.
	$wp_customize->add_setting('spine_options[archive_content_display]', array(
		'default'        => 'full',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('archive_content_display', array(
		'label'      => __('Archive Content Display', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[archive_content_display]',
		'type'       => 'select',
		'choices'    => array(
			'full'  => 'Full Content',
			'excerpt' => 'Automatic Excerpt',
		),
	));

	// Offer Dynamic Shortcuts
	/*$wp_customize->add_setting('spine_options[index_shortcuts]', array(
		'default'        => 'google',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_index_shortcuts', array(
		'label'      => __('Coming: Offer Index Shortcuts', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[index_shortcuts]',
		'type'       => 'checkbox'
	));


	$wp_customize->add_setting('spine_options[local_site_shortcuts]', array(
		'default'        => 'google',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_local_site_shortcuts', array(
		'label'      => __('Offer Site Shortcuts', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[local_site_shortcuts]',
		'type'       => 'checkbox'
	));

	// Local Search
	$wp_customize->add_setting('spine_options[search_local]', array(
		'default'        => 'google',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_search_local', array(
		'label'      => __('Coming: Local Search Engine', 'spine'),
		'section'    => 'section_spine_advanced_options',
		'settings'   => 'spine_options[search_local]',
		'type'       => 'radio',
		'choices'    => array(
			'google' => 'Google',
			'wordpress' => 'WordPress'
		),
	));*/

	// Style Options
	$wp_customize->add_section('section_spine_style', array(
		'title'    => __('Style Options', 'spine'),
		'priority' => 400,
		'description' => 'These options may or may not be supported by your theme.',
	));

	$wp_customize->add_setting('spine_options[theme_style]', array(
		'default'        => 'bookmark',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_theme_style', array(
		'settings'   => 'spine_options[theme_style]',
		'label'      => __('Additional Styling', 'spine'),
		'section'    => 'section_spine_style',
		'type'       => 'select',
		'choices'    => array(
			'skeletal' => 'Skeletal (none)',
			'bookmark' => 'Bookmark'
		),
	));

	$wp_customize->add_setting('spine_options[secondary_colors]', array(
		'default'        => 'gray',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_secondary_colors', array(
		'settings'   => 'spine_options[secondary_colors]',
		'label'      => __('Secondary Color', 'spine'),
		'section'    => 'section_spine_style',
		'type'       => 'select',
		'choices'    => array(
			'default' => 'Default',
			'gray' => 'Gray',
			'green' => 'Green',
			'orange' => 'Orange',
			'blue' => 'Blue',
			'yellow' => 'Yellow'
		),
	));

	$wp_customize->add_setting('spine_options[theme_spacing]', array(
		'default'        => 'default',
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_theme_spacing', array(
		'settings'   => 'spine_options[theme_spacing]',
		'label'      => __('Sitewide Spacing', 'spine'),
		'section'    => 'section_spine_style',
		'type'       => 'select',
		'choices'    => array(
			'default' => 'Default (2em)',
			'loose' => 'Loose (4em)',
			'tight' => 'Tight (1em)'
		),
	));

	/**
	 * Start the font options section.
	 */
	$wp_customize->add_section('section_spine_fonts', array(
		'title'    => __('Font Options', 'spine'),
		'priority' => 1000,
		//'description' => 'Select fonts to load. (<b>Beware.</b> Each font decreases the speed a page loads.)',
		));

	/**
	 * A general option is available which tells the Spine Framework
	 * to apply its Open Sans configuration.
	 */
	$wp_customize->add_setting('spine_options[open_sans]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));
	$wp_customize->add_control('spine_options[open_sans]', array(
		'label'      => __('Apply Open Sans to page', 'spine'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_options[open_sans]',
		'type'       => 'radio',
		'choices'    => array(
			true  => 'On',
			false => 'Off',
		),
		'priority'   => 1,
	));

	/**
	 * Additional options are available for loading in various Open Sans
	 * font weights and styles to be used with our without the Spine
	 * Framework's default Open Sans configuration.
	 */
	$wp_customize->add_setting('spine_open_sans[300]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[300]', array(
		'label'      => __('300 Light', '300'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[300]',
		'type'       => 'checkbox',
		'priority'   => 2,
	));

	$wp_customize->add_setting('spine_open_sans[300italic]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[300italic]', array(
		'label'      => __('300 Light Italic', '300italic'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[300italic]',
		'type'       => 'checkbox',
		'priority'   => 3,
	));

	$wp_customize->add_setting('spine_open_sans[400]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[400]', array(
		'label'      => __('400 Normal', '400'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[400]',
		'type'       => 'checkbox',
		'priority'   => 4,
	));

	$wp_customize->add_setting('spine_open_sans[400italic]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[400italic]', array(
		'label'      => __('400 Normal Italic', '400italic'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[400italic]',
		'type'       => 'checkbox',
		'priority'   => 5,
	));

	$wp_customize->add_setting('spine_open_sans[600]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[600]', array(
		'label'      => __('600 Semi-Bold', '600'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[600]',
		'type'       => 'checkbox',
		'priority'   => 6,
	));

	$wp_customize->add_setting('spine_open_sans[600italic]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[600italic]', array(
		'label'      => __('600 Semi-Bold Italic', '600italic'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[600italic]',
		'type'       => 'checkbox',
		'priority'   => 7,
	));

	$wp_customize->add_setting('spine_open_sans[700]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[700]', array(
		'label'      => __('700 Bold', '700'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[700]',
		'type'       => 'checkbox',
		'priority'   => 8,
	));

	$wp_customize->add_setting('spine_open_sans[700italic]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[700italic]', array(
		'label'      => __('700 Bold Italic', '700italic'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[700italic]',
		'type'       => 'checkbox',
		'priority'   => 9,
	));

	$wp_customize->add_setting('spine_open_sans[800]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[800]', array(
		'label'      => __('800 Extra-Bold', '800'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[800]',
		'type'       => 'checkbox',
		'priority'   => 10,
	));

	$wp_customize->add_setting('spine_open_sans[800italic]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans[800italic]', array(
		'label'      => __('800 Extra-Bold Italic', '800italic'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans[800italic]',
		'type'       => 'checkbox',
		'priority'   => 11,
	));

	$wp_customize->add_setting('spine_open_sans_cond[300]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans_cond[300]', array(
		'label'      => __('300 Condensed Light', '300'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans_cond[300]',
		'type'       => 'checkbox',
		'priority'   => 12,
	));

	$wp_customize->add_setting('spine_open_sans_cond[300italic]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans_cond[300italic]', array(
		'label'      => __('300 Condensed Light Italic', '300italic'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans_cond[300italic]',
		'type'       => 'checkbox',
		'priority'   => 12,
	));

	$wp_customize->add_setting('spine_open_sans_cond[700]', array(
		'default'        => false,
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
	));

	$wp_customize->add_control('spine_open_sans_cond[700]', array(
		'label'      => __('700 Condensed Bold', '700'),
		'section'    => 'section_spine_fonts',
		'settings'   => 'spine_open_sans_cond[700]',
		'type'       => 'checkbox',
		'priority'   => 12,
	));


}