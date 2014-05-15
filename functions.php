<?php

// Global version tracker.
$wsuwp_spine_theme_version = '0.1.2';

include_once( 'includes/main-header.php' ); // Include main header functionality.
include_once( 'includes/customizer/customizer.php' ); // Include customizer functionality.

/**
 * Creates a script version based on this theme, the WSUWP Platform, and
 * the platform's current version of WordPress if available.
 *
 * In individual installations, only this theme's version will be used. In
 * the platform installation, this will help break cache on any major change.
 */
function spine_get_script_version() {
	global $wsuwp_spine_theme_version, $wsuwp_global_version, $wsuwp_wp_changeset;

	$script_version = $wsuwp_spine_theme_version;

	if ( null !== $wsuwp_global_version ) {
		$script_version .= '-' . $wsuwp_global_version;
	}

	if ( null !== $wsuwp_wp_changeset ) {
		$script_version .= '-' . $wsuwp_wp_changeset;
	}

	return $script_version;
}

/**
 * Retrieve the requested spine option from the database.
 *
 * @param string $option_name The option name or key to retrieve.
 *
 * @return mixed The value of the option if found. False if not found.
 */
function spine_get_option( $option_name ) {
	$spine_options = get_option( 'spine_options' );

	// Defaults for the spine options will be compared to what is stored in spine_options.
	$defaults = array(
		'grid_style'                => 'hybrid',
		'spine_color'               => 'white',
		'large_format'              => '',
		'theme_style'               => 'bookmark',
		'broken_binding'            => false,
		'bleed'                     => false,
		'contact_name'              => 'Washington State University',
		'contact_department'        => '',
		'contact_url'               => 'http://wsu.edu',
		'contact_streetAddress'     => 'PO Box 641227',
		'contact_addressLocality'   => 'Pullman, WA',
		'contact_postalCode'        => '99164',
		'contact_telephone'         => '(509) 335-3564',
		'contact_email'             => 'info@wsu.edu',
		'contact_ContactPoint'      => '',
		'contact_ContactPointTitle' => 'Contact Page...',
	);

	// A child theme can override all spine option defaults with the spine_option_defaults filter.
	$defaults = apply_filters( 'spine_option_defaults', $defaults );

	$spine_options = wp_parse_args( $spine_options, $defaults );

	// Special handling for the broken_binding option, which should only be one of two options.
	if ( 'broken_binding' === $option_name && true == $spine_options[ $option_name ] ) {
		$spine_options[ $option_name ] = ' broken';
	} elseif ( 'broken_binding' === $option_name ) {
		$spine_options[ $option_name ] = '';
	}

	if ( 'bleed' === $option_name && true == $spine_options[ $option_name ] ) {
		$spine_options[ $option_name ] = ' bleed';
	} elseif ( 'bleed' === $option_name ) {
		$spine_options[ $option_name ] = '';
	}

	// A child theme can override a specific spine option with the spine_option filter.
	$spine_options[ $option_name ] = apply_filters( 'spine_option', $spine_options[ $option_name ], $option_name );

	if ( isset( $spine_options[ $option_name ] ) ) {
		return $spine_options[ $option_name ];
	} else {
		return false;
	}
}

/**
 * Provide an array of social options when requested. These are originally
 * added through the theme customizer.
 *
 * @return array Class attributes and URLs for each configured social network.
 */
function spine_social_options() {
	$spine_options = get_option( 'spine_options' );

	$social = array();

	if ( isset( $spine_options['social_spot_one_type'] ) && $spine_options['social_spot_one_type'] != "none" ) {
		$key = $spine_options['social_spot_one_type'];
		$social[ $key ] = $spine_options['social_spot_one'];
	}

	if ( isset( $spine_options['social_spot_two_type'] ) && $spine_options['social_spot_two_type'] != "none" ) {
		$key = $spine_options['social_spot_two_type'];
		$social[ $key ] = $spine_options['social_spot_two'];
	}

	if ( isset( $spine_options['social_spot_three_type'] ) && $spine_options['social_spot_three_type'] != "none" ) {
		$key = $spine_options['social_spot_three_type'];
		$social[ $key ] = $spine_options['social_spot_three'];
	}

	if ( isset( $spine_options['social_spot_four_type'] ) && $spine_options['social_spot_four_type'] != "none" ) {
		$key = $spine_options['social_spot_four_type'];
		$social[ $key ] = $spine_options['social_spot_four'];
	}

	return $social;
}

add_action( 'wp_enqueue_scripts', 'spine_wp_enqueue_scripts' );
/**
 * Enqueue scripts and styles required for front end pageviews.
 */
function spine_wp_enqueue_scripts() {
	// Much relies on the main stylesheet provided by the WSU Spine.
	wp_enqueue_style( 'wsu-spine', '//repo.wsu.edu/spine/1/spine.min.css', array(), spine_get_script_version() );

	/**
	 * By default, a child theme has 3 styles enqueued—the main stylesheet, an extra stylesheet per the theme_style
	 * option, and the child stylesheet. The parent theme has 2 styles enqueued—the main stylesheet and the extra
	 * stylesheet defined by the theme_style option.
	 *
	 * If a child theme would like to provide all styles and **not** rely on the parent theme, it should dequeue
	 * the parent style with something like the following:
	 *
	 *     wp_dequeue_style( 'spine-theme' );
	 *     wp_dequeue_style( 'spine-theme-extra' );
	 *
	 * In all cases, the main spine CSS is enqueued separately from this logic. See above.
	 */
	if ( is_child_theme() ) {
		wp_enqueue_style( 'spine-theme',       get_template_directory_uri()   . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
		wp_enqueue_style( 'spine-theme-extra', get_template_directory_uri()   . '/styles/' . spine_get_option( 'theme_style' ) . '.css', array(), spine_get_script_version() );
		wp_enqueue_style( 'spine-theme-child', get_stylesheet_directory_uri() . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
	} else {
		wp_enqueue_style( 'spine-theme',       get_template_directory_uri()   . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
		wp_enqueue_style( 'spine-theme-extra', get_template_directory_uri()   . '/styles/' . spine_get_option( 'theme_style' ) . '.css', array(), spine_get_script_version() );
	}
	
	if ( true == spine_get_option( 'open_sans' ) ) {
		wp_enqueue_style( 'wsu-spine-opensans', '//repo.wsu.edu/spine/1/styles/opensans.css', array(), spine_get_script_version() );
	} else { ; }

	// WordPress core provides much of jQuery UI, but not in a nice enough package to enqueue all at once.
	// For this reason, we'll pull the entire package from the Google CDN.
	wp_enqueue_script( 'wsu-jquery-ui-full', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', array( 'jquery' ) );

	// Much relies on the main Javascript provided by the WSU Spine.
	wp_enqueue_script( 'wsu-spine', '//repo.wsu.edu/spine/1/spine.min.js', array( 'wsu-jquery-ui-full' ), spine_get_script_version(), false );
}

add_action( 'admin_enqueue_scripts', 'spine_admin_enqueue_scripts' );
/**
 * Enqueue styles required for admin pageviews.
 */
function spine_admin_enqueue_scripts() {
	wp_enqueue_style( 'admin-interface-styles', get_template_directory_uri() . '/includes/admin.css' );
	add_editor_style( 'admin-editor-styles', get_template_directory_uri() . '/includes/editor.css' );
}

// Two Navigation Menus
add_action( 'init', 'spine_theme_menus' );
function spine_theme_menus() {
	register_nav_menus(
		array(
		'site'    => 'Site',
		'offsite' => 'Offsite',
		)
	);
}

// A Single Sidebar
add_action( 'widgets_init', 'spine_theme_widgets_init' );
/**
 * Register sidebars used by the theme.
 */
function spine_theme_widgets_init() {
	$widget_options = array(
		'name'          => __( 'Sidebar', 'sidebar' ),
		'id'            => 'sidebar',
		'before_widget' => '<aside id="%1$s2" class="%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<header>',
		'after_title'   => '</header>'
	);
	register_sidebar( $widget_options );
}

add_action( 'after_setup_theme', 'spine_theme_setup_theme' );
/**
 * Setup some defaults provided by the theme.
 */
function spine_theme_setup_theme() {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 198, 198, true );

	add_image_size( 'teaser-image', 198, 198, true );
	add_image_size( 'header-image', 792, 99163 );
	add_image_size( 'billboard-image', 1584, 99163 );
}

add_filter( 'nav_menu_css_class', 'spine_abbridged_menu_classes', 10 );
/**
 * Condense verbose menu classes provided by WordPress.
 *
 * Removes the default current-menu-item and current_page_parent classes
 * if they are found on this page view and replaces them with 'current'.
 *
 * @param array $classes Current list of nav menu classes.
 *
 * @return array Modified list of nav menu classes.
 */
function spine_abbridged_menu_classes( $classes ) {
	if ( in_array( 'current-menu-item', $classes ) || in_array( 'current_page_parent', $classes ) ) {
		return array( 'current' );
	}

	return array();
}

add_action( 'admin_init', 'spine_theme_image_options' );
function spine_theme_image_options() {
	// Default Image Sizes
	update_option( 'thumbnail_size_w', 198   );
	update_option( 'thumbnail_size_h', 198   );
	update_option( 'medium_size_w',    396   );
	update_option( 'medium_size_h',    99163 );
	update_option( 'large_size_w',     792   );
	update_option( 'large_size_h',     99163 );
	// update_option('full_size_w', 1980);
	// update_option('full_size_h', 99163);
}

/* Default Image Markup */

add_filter( 'img_caption_shortcode', 'spine_theme_caption_markup', 10, 3 );

function spine_theme_caption_markup( $output, $attr, $content ) {
	if ( is_feed() ) {
		return $output;
	}

	$defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => ''
	);

	$attr = shortcode_atts( $defaults, $attr );
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) ) {
		return $content;
	}

	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="' . esc_attr( $attr['align'] ) . '"';
	$output = '<figure' . $attributes .'><div class="liner cf">';
	$output .= do_shortcode( $content );
	$output .= '<figcaption>' . $attr['caption'] . '</figcaption>';
	$output .= '</div></figure>';

	return $output;
}

/* add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );

function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
} */

/* function image_tag_class($class, $id, $align, $size) {
	return $align;
}
add_filter('get_image_tag_class', 'image_tag_class', 0, 4);

*/

// SECTIONING
// @todo remove this hack... :)
function spine_is_subpage() {
	return spine_is_sub();
}

function spine_is_sub() {
    $post = get_post();

    if ( is_page() && $post->post_parent ) {
        return $post->post_parent;
    } else {
		return false;
	}
}

add_filter( 'body_class','spine_speckled_body_classes' );
/**
 * Add randomized body classes.
 *
 * @param array $classes Current list of body classes.
 *
 * @return array Modified list of body classes.
 */
function spine_speckled_body_classes( $classes ) {
	$classes[] = 'five' . mt_rand( 1, 5 );
	$classes[] = 'ten' . mt_rand( 1, 10 );
	$classes[] = 'twenty' . mt_rand( 1, 20 );

	return $classes;
}

add_filter('body_class', 'spine_categorized_body_classes');
/* Add categorized in classes to body on singular views */
function spine_categorized_body_classes( $classes ) {
	if ( has_category() && is_singular() ) {
		foreach( get_the_category( get_the_ID() ) as $category ) {
			$classes[] = 'categorized-' . $category->slug;
		}
	}

	return array_unique( $classes );
}

add_filter( 'body_class', 'spine_sectioned_body_classes' );
/**
 * Add custom body classes based on the requested URL for individual
 * page and post views.
 *
 * @param array $classes Current list of body classes.
 *
 * @return array Modified list of body classes.
 */
function spine_sectioned_body_classes( $classes ) {

	// Paths may be polluted with additional site information, so we
	// compare the post/page permalink with the home URL.
	$path = str_replace( get_home_url(), '', get_permalink() );
	$path = trim( $path, '/' );
	$path = explode( '/', $path );

	if ( is_singular() && ! empty( $path ) ) {
		$depth = count( $path ) - 1;
		$classes[] = 'depth-' . $depth;
		$strip = array('?','=');

		if ( 1 === count( $path ) ) {
			$classes[] = 'section-' . str_replace( $strip, "", $path[0]);
			$classes[] = 'page-' . str_replace( $strip, "", $path[0]);
		} else {
			$classes[] = 'section-' . array_shift( $path );
			$prefix = 'sub-';
			foreach( $path as $part ) {
				$classes[] = $prefix.'section-' . $part;
				$prefix = 'sub-'.$prefix;
			}
			$classes[] = 'page-' . array_pop( $path );
		}
	}

	return array_unique( $classes );
}

// Default Read More
function spine_theme_excerpt_more() {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '" >More</a>';
}
add_filter( 'excerpt_more', 'spine_theme_excerpt_more' );

