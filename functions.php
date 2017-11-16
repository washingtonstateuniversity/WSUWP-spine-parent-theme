<?php

// Global version tracker.
$wsuwp_spine_theme_version = '0.27.16';

require_once 'includes/theme-setup.php'; // Setup basic portions of the theme.
require_once 'includes/theme-navigation.php'; // Include functionality for navigation.
require_once 'includes/theme-main-header.php'; // Include main header functionality.
require_once 'includes/theme-customizer.php'; // Include customizer functionality.
require_once 'includes/theme-images.php'; // Manipulating images

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

add_action( 'init', 'spine_load_builder_module', 10 );
/**
 * Allow our version of Make's builder tool to be disabled at the
 * platform or WordPress installation level.
 *
 * Note: admin_init is too late for this to be brought in.
 */
function spine_load_builder_module() {
	if ( true === apply_filters( 'spine_enable_builder_module', true ) ) {
		include_once 'inc/builder.php';
	}
}

add_filter( 'theme_page_templates', 'spine_show_builder_page_template', 10, 1 );
/**
 * If builder functionality is not available, do not show the builder template
 * on the list of available page templates.
 *
 * @param array $page_templates List of available page templates.
 *
 * @return array Modified list of page templates.
 */
function spine_show_builder_page_template( $page_templates ) {
	if ( false === apply_filters( 'spine_enable_builder_module', true ) ) {
		unset( $page_templates['template-builder.php'] );
	}
	return $page_templates;
}

/**
 * Retrieve the home URL for the campus signature selected by the theme.
 *
 * @return string
 */
function spine_get_campus_home_url() {
	return spine_get_campus_data( 'url' );
}

/**
 * Retrieve the home URL and link text for the campus signature selected by the theme.
 *
 * @return string
 */
function spine_get_campus_data( $part ) {
	$campus_data = array(
		'extension'              => array( 'extension.wsu.edu', 'Extension' ),
		'foundation'             => array( 'foundation.wsu.edu', 'Foundation' ),
		'globalcampus'           => array( 'globalcampus.wsu.edu', 'Global Campus' ),
		'healthsciences-spokane' => array( 'spokane.wsu.edu', 'Health Sciences Spokane' ),
		'spokane'                => array( 'spokane.wsu.edu', 'Spokane' ),
		'tricities'              => array( 'tricities.wsu.edu', 'Tri-Cities' ),
		'vancouver'              => array( 'vancouver.wsu.edu', 'Vancouver' ),
	);
	$campus_location = spine_get_option( 'campus_location' );

	if ( isset( $campus_data[ $campus_location ] ) ) {
		if ( 'url' === $part ) {
			return esc_url( $campus_data[ $campus_location ][0] );
		} elseif ( 'link-text' === $part ) {
			return esc_html( 'Washington State University ' . $campus_data[ $campus_location ][1] );
		}
	}

	if ( 'url' === $part ) {
		return apply_filters( 'spine_get_campus_home_url', 'https://wsu.edu/' );
	} elseif ( 'link-text' === $part ) {
		return apply_filters( 'spine_get_campus_data', 'Washington State University' );
	}

	return '';
}

/**
 * A set of defaults for the options set in the customizer for the Spine theme.
 *
 * @return array List of default options.
 */
function spine_get_option_defaults() {
	return array(
		'spine_version'             => '1',
		'grid_style'                => 'fluid',
		'campus_location'           => '',
		'spine_color'               => 'white',
		'large_format'              => '',
		'theme_style'               => 'bookmark',
		'secondary_colors'          => 'default',  // Crimson
		'theme_spacing'             => 'default',
		'global_main_header_sup'    => '',
		'global_main_header_sub'    => '',
		'main_header_show'          => true,
		'articletitle_show'         => true,
		'articletitle_header'       => false,
		'broken_binding'            => false,
		'bleed'                     => false,
		'search_state'              => 'closed',
		'crop'                      => false,
		'spineless'                 => false,
		'open_sans'                 => '1',
		'contact_name'              => 'Washington State University',
		'contact_department'        => '',
		'contact_url'               => '',
		'contact_streetAddress'     => '',
		'contact_addressLocality'   => '',
		'contact_postalCode'        => '',
		'contact_telephone'         => '',
		'contact_email'             => '',
		'contact_ContactPoint'      => '',
		'contact_ContactPointTitle' => 'Contact Page...',
		'archive_content_display'   => 'full',
		'social_spot_one_type'      => 'facebook',
		'social_spot_one'           => 'https://www.facebook.com/WSUPullman',
		'social_spot_two_type'      => 'twitter',
		'social_spot_two'           => 'https://twitter.com/wsupullman',
		'social_spot_three_type'    => 'youtube',
		'social_spot_three'         => 'https://www.youtube.com/washingtonstateuniv',
		'social_spot_four_type'     => 'directory',
		'social_spot_four'          => 'http://social.wsu.edu',
		'post_social_placement'     => 'none',
		'show_author_page'          => '1',
		'show_breadcrumbs'          => 'top', // Only valid with Breadcrumb NavXT plugin installed.
		'front_page_title'          => false,
		'page_for_posts_title'      => false,
	);
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
	$defaults = spine_get_option_defaults();

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
 * Retrieve a list of Open Sans weights and styles enabled for the site via
 * the Customizer. Apply defaults expected by both the Spine, when Open Sans
 * is enabled, and by WordPress when the admin bar is showing.
 *
 * @return array List of font weights and styles.
 */
function spine_get_open_sans_options() {
	$spine_open_sans = get_option( 'spine_open_sans', array() );
	$enabled = absint( spine_get_option( 'open_sans' ) );

	// When Open Sans is enabled, the Spine expects these to exist.
	if ( 1 === $enabled ) {
		$spine_open_sans['400'] = true;
		$spine_open_sans['400italic'] = true;
		$spine_open_sans['700'] = true;
		$spine_open_sans['700italic'] = true;
	}

	$fonts = array();

	foreach ( $spine_open_sans as $k => $v ) {
		if ( true === $v ) {
			$fonts[] = $k;
		}
	}

	// A child theme can override all spine open sans defaults with the spine_open_sans_options filter.
	$fonts = apply_filters( 'spine_open_sans_options', $fonts );

	return $fonts;
}

/**
 * Retrieve a list of Open Sans Condensed weights and styles enabled for the site
 * via the Customizer.
 *
 * @return array List of font weights and styles.
 */
function spine_get_open_sans_condensed_options() {
	$spine_open_sans_cond = get_option( 'spine_open_sans_cond', array() );
	$enabled = absint( spine_get_option( 'open_sans' ) );

	if ( 0 === $enabled ) {
		return array();
	}

	$fonts = array();

	foreach ( $spine_open_sans_cond as $k => $v ) {
		if ( true === $v ) {
			$fonts[] = $k;
		}
	}

	$fonts = apply_filters( 'spine_open_sans_cond_options', $fonts );

	return $fonts;
}

/**
 * Provide an array of social options when requested. These are originally
 * added through the theme customizer.
 *
 * @return array Class attributes and URLs for each configured social network.
 */
function spine_social_options() {
	$spine_options = get_option( 'spine_options' );

	// Defaults for the spine options will be compared to what is stored in spine_options.
	$defaults = spine_get_option_defaults();

	// A child theme can override all spine option defaults with the spine_option_defaults filter.
	$defaults = apply_filters( 'spine_option_defaults', $defaults );

	$spine_options = wp_parse_args( $spine_options, $defaults );

	$social = array();

	if ( isset( $spine_options['social_spot_one_type'] ) && 'none' !== $spine_options['social_spot_one_type'] ) {
		$social[ $spine_options['social_spot_one_type'] ] = $spine_options['social_spot_one'];
	}

	if ( isset( $spine_options['social_spot_two_type'] ) && 'none' !== $spine_options['social_spot_two_type'] ) {
		$social[ $spine_options['social_spot_two_type'] ] = $spine_options['social_spot_two'];
	}

	if ( isset( $spine_options['social_spot_three_type'] ) && 'none' !== $spine_options['social_spot_three_type'] ) {
		$social[ $spine_options['social_spot_three_type'] ] = $spine_options['social_spot_three'];
	}

	if ( isset( $spine_options['social_spot_four_type'] ) && 'none' !== $spine_options['social_spot_four_type'] ) {
		$social[ $spine_options['social_spot_four_type'] ] = $spine_options['social_spot_four'];
	}

	return $social;
}

add_action( 'wp_enqueue_scripts', 'spine_wp_enqueue_scripts', 20 );
/**
 * Enqueue scripts and styles required for front end pageviews.
 */
function spine_wp_enqueue_scripts() {

	$spine_version = spine_get_option( 'spine_version' );
	// This may be an unnecessary check, but we don't want to screw this up.
	if ( 'develop' !== $spine_version && 0 === absint( $spine_version ) ) {
		$spine_version = 1;
	}

	// Much relies on the main stylesheet provided by the WSU Spine.
	wp_enqueue_style( 'wsu-spine', 'https://repo.wsu.edu/spine/' . $spine_version . '/spine.min.css', array(), spine_get_script_version() );

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
	 *
	 * If "Skeletal" styling is chosen in the Customizer, no `spine-theme-extra` stylesheet will be enqueued.
	 */
	if ( is_child_theme() ) {
		wp_enqueue_style( 'spine-theme', get_template_directory_uri() . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
		if ( 'skeletal' !== spine_get_option( 'theme_style' ) ) {
			wp_enqueue_style( 'spine-theme-extra', get_template_directory_uri() . '/styles/' . spine_get_option( 'theme_style' ) . '.css', array(), spine_get_script_version() );
		}

		if ( apply_filters( 'spine_child_min_css', false ) ) {
			$child_stylesheet = 'style.min.css';
		} else {
			$child_stylesheet = 'style.css';
		}
		wp_enqueue_style( 'spine-theme-child', get_stylesheet_directory_uri() . '/' . $child_stylesheet, array( 'wsu-spine' ), spine_get_child_version() );
	} else {
		wp_enqueue_style( 'spine-theme', get_template_directory_uri() . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
		if ( 'skeletal' !== spine_get_option( 'theme_style' ) ) {
			wp_enqueue_style( 'spine-theme-extra', get_template_directory_uri() . '/styles/' . spine_get_option( 'theme_style' ) . '.css', array(), spine_get_script_version() );
		}
	}

	wp_enqueue_style( 'spine-theme-print', get_template_directory_uri() . '/css/print.css', array(), spine_get_script_version(), 'print' );

	// All theme styles have been output at this time. Plugins and other themes should print styles here, before blocking
	// Javascript resources are output.
	do_action( 'spine_enqueue_styles' );

	$google_font_css_url = '//fonts.googleapis.com/css?family=';
	$count = 0;
	$spine_open_sans = spine_get_open_sans_options();

	// Build the URL used to pull additional Open Sans font weights and styles from Google.
	if ( ! empty( $spine_open_sans ) ) {
		$build_open_sans_css = '';
		foreach ( $spine_open_sans as $font_option ) {
			if ( 0 === $count ) {
				$build_open_sans_css = 'Open+Sans%3A' . $font_option;
			} else {
				$build_open_sans_css .= '%2C' . $font_option;
			}

			$count++;
		}

		if ( 0 !== $count ) {
			$google_font_css_url .= $build_open_sans_css;
		} else {
			$google_font_css_url = '';
		}
	} else {
		$google_font_css_url = '';
	}

	$spine_open_sans_condensed = spine_get_open_sans_condensed_options();

	$condensed_count = 0;
	if ( ! empty( $spine_open_sans_condensed ) ) {
		if ( 0 !== $count ) {
			$build_open_sans_cond_css = '|Open+Sans+Condensed%3A';
		} else {
			$build_open_sans_cond_css = 'Open+Sans+Condensed%3A';
		}

		foreach ( $spine_open_sans_condensed as $font_option ) {
			if ( 0 === $condensed_count ) {
				$build_open_sans_cond_css .= $font_option;
			} else {
				$build_open_sans_cond_css .= '%2C' . $font_option;
			}

			$count++;
			$condensed_count++;
		}

		$google_font_css_url .= $build_open_sans_cond_css;
	}

	// Only enqueue a custom Google Fonts URL if extra options have been selected for Open Sans.
	if ( '' !== $google_font_css_url ) {
		$google_font_css_url .= '&subset=latin,latin-ext';

		// Deregister the default Open Sans URL provided by WordPress core and instead provide our own.
		wp_deregister_style( 'open-sans' );
		wp_enqueue_style( 'open-sans', $google_font_css_url, array(), false );
	}

	// WordPress core provides much of jQuery UI, but not in a nice enough package to enqueue all at once.
	// For this reason, we'll pull the entire package from the Google CDN.
	wp_enqueue_script( 'wsu-jquery-ui-full', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', array( 'jquery' ) );

	// Much relies on the main Javascript provided by the WSU Spine.
	wp_enqueue_script( 'wsu-spine', 'https://repo.wsu.edu/spine/' . $spine_version . '/spine.min.js', array( 'wsu-jquery-ui-full' ), spine_get_script_version(), false );

	// Override default options in the WSU Spine.
	$twitter_text = ( is_front_page() ) ? get_option( 'blogname' ) : trim( wp_title( '', false ) );
	$spineoptions = array(
		'social' => array(
			'share_text' => esc_js( spine_get_title() ),
			'twitter_text' => esc_js( $twitter_text ),
			'twitter_handle' => 'wsupullman',
		),
	);
	// If a Twitter account has been added in the Customizer, use that for the via handle.
	$spine_social_options = spine_social_options();
	if ( isset( $spine_social_options['twitter'] ) ) {
		$twitter_array = array_filter( explode( '/', $spine_social_options['twitter'] ) );
		$twitter_handle = array_pop( $twitter_array );
		$spineoptions['social']['twitter_handle'] = esc_js( $twitter_handle );
	}

	if ( is_admin_bar_showing() ) {
		$spineoptions['framework'] = array(
			'viewport_offset' => 32,
		);
	}

	wp_localize_script( 'wsu-spine', 'spineoptions', $spineoptions );

	// Enqueue jQuery Cycle2 and Genericons when a page builder template is used.
	if ( is_page_template( 'template-builder.php' ) ) {
		$has_builder_banner = get_post_meta( get_the_ID(), '_has_builder_banner', true );

		if ( $has_builder_banner ) {
			// Enqueue the compilation of jQuery Cycle2 scripts required for the slider
			wp_enqueue_script( 'wsu-cycle', get_template_directory_uri() . '/js/cycle2/jquery.cycle2.min.js', array( 'jquery' ), spine_get_script_version(), true );
			wp_enqueue_style( 'genericons', get_template_directory_uri() . '/styles/genericons/genericons.css', array(), spine_get_script_version() );
		}
	}

	// Enqueue scripting for the entire parent theme.
	wp_enqueue_script( 'wsu-spine-theme-js', get_template_directory_uri() . '/js/spine-theme.js', array( 'jquery' ), spine_get_script_version(), true );
}

add_action( 'customize_controls_enqueue_scripts', 'spine_customizer_enqueue_scripts' );
/**
 * Enqueue the styles and scripts used inside the Customizer.
 */
function spine_customizer_enqueue_scripts() {
	wp_enqueue_style( 'spine-customizer-styles', get_template_directory_uri() . '/css/customizer.css' );
	wp_enqueue_script( 'spine-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'jquery' ), spine_get_script_version(), true );
}

add_action( 'widgets_init', 'spine_theme_widgets_init' );
/**
 * Register sidebars used by the theme.
 */
function spine_theme_widgets_init() {
	$widget_options = array(
		'name'          => __( 'Sidebar', 'sidebar' ),
		'id'            => 'sidebar',
		'before_widget' => '<aside id="%1$s2" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<header>',
		'after_title'   => '</header>',
	);
	register_sidebar( $widget_options );
}

add_filter( 'get_the_excerpt', 'spine_trim_excerpt', 5 );
/**
 * Provide a custom trimmed excerpt.
 *
 * @param string $text The raw excerpt.
 *
 * @return string The modified excerpt.
 */
function spine_trim_excerpt( $text ) {
	$raw_excerpt = $text;
	if ( '' == $text ) {
		//Retrieve the post content.
		$text = get_the_content( '' );

		//Delete all shortcode tags from the content.
		$text = strip_shortcodes( $text );

		$allowed_tags = '<p>,<a>,<em>,<strong>,<img>,<h2>,<h3>,<h4>,<h5>,<blockquote>';
		$text = strip_tags( $text, $allowed_tags );

		$text = apply_filters( 'the_content', $text );

		if ( ! has_filter( 'the_content', 'wpautop' ) ) {
			$text = wpautop( $text );
		}

		$text = str_replace( ']]>', ']]&gt;', $text );

		$excerpt_word_count = 105;
		$excerpt_length = apply_filters( 'excerpt_length', $excerpt_word_count );

		$excerpt_end = '... <a href="' . get_permalink() . '" class="more-link"><span class="more-default">&raquo; More ...</span></a>';
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . $excerpt_end );

		$words = preg_split( "/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
		if ( count( $words ) > $excerpt_length ) {
			array_pop( $words );
			$text = implode( ' ', $words );
			$text = $text . $excerpt_more;
		} else {
			$text = implode( ' ', $words );
		}

		$text = force_balance_tags( $text );
	}
	return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt );
}

/**
 * Determine if the current page has a parent.
 *
 * @return bool|int The ID of the parent if found, otherwise false.
 */
function spine_is_sub() {
	$_post = get_post();

	if ( is_page() && $_post->post_parent ) {
		return $_post->post_parent;
	} else {
		return false;
	}
}

add_filter( 'body_class', 'spine_site_body_class' );
/**
 * Add body classes for the site domain and path to help with targeting on multiple
 * sites using this theme.
 *
 * @param array $classes
 *
 * @return array
 */
function spine_site_body_class( $classes ) {
	if ( ! is_multisite() ) {
		return $classes;
	}

	$site = get_site();
	$site_domain = 'domain-' . sanitize_title_with_dashes( $site->domain );
	$site_path = 'path-' . sanitize_title_with_dashes( $site->path );

	if ( 'path-' === $site_path ) {
		$site_path = 'path-none';
	}

	if ( ! isset( $classes[ $site_domain ] ) ) {
		$classes[] = $site_domain;
	}

	if ( ! isset( $classes[ $site_path ] ) ) {
		$classes[] = $site_path;
	}

	return $classes;
}

add_filter( 'body_class', 'spine_open_sans_body_class' );
/**
 * If Open Sans has been applied to the Spine, add the
 * appropriate body class.
 *
 * @param array $classes Current list of body classes.
 *
 * @return array Modified list of body classes.
 */
function spine_open_sans_body_class( $classes ) {
	if ( '1' == spine_get_option( 'open_sans' ) ) {
		$classes[] = 'opensansy';
	}

	return $classes;
}

add_filter( 'body_class', 'spine_campus_body_class' );
/**
 * If a campus or locations has been applied to the Spine, add the
 * appropriate body class.
 *
 * @param array $classes Current list of body classes.
 *
 * @return array Modified list of body classes.
 */
function spine_campus_body_class( $classes ) {
	if ( spine_get_option( 'campus_location' ) != '' ) {
		$classes[] = esc_attr( spine_get_option( 'campus_location' ) ) . '-signature';
	}

	return $classes;
}

add_filter( 'body_class', 'spine_singularity_body_class' );
/**
 * Indicate not only single post, but also singularity.
 *
 * @param array $classes Current list of body classes.
 *
 * @return array Modified list of body classes.
 */
function spine_singularity_body_class( $classes ) {
	if ( is_singular() && ! isset( $classes['single'] ) ) {
		$classes[] = 'single';
	} else {
		$classes[] = 'not-single';
	}

	return $classes;
}

add_filter( 'body_class', 'spine_speckled_body_classes' );
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

add_filter( 'body_class', 'spine_theme_images_classes' );
/**
 * Add classes indicated which theme images are available.
 *
 * @param array $classes Current list of body classes.
 *
 * @return array Modified list of body classes.
 */
function spine_theme_images_classes( $classes ) {

	if ( spine_has_background_image() && is_singular() ) { $classes[] = 'has-background-image'; }
	if ( spine_has_featured_image() && is_singular() ) { $classes[] = 'has-featured-image'; }
	if ( spine_has_thumbnail_image() && is_singular() ) { $classes[] = 'has-thumbnail-image'; }

	return $classes;
}

add_filter( 'body_class', 'spine_categorized_body_classes' );
/**
 * Add 'categorized' in classes to body on singular views.
 *
 * @param array $classes List of classes to be added to the body element.
 *
 * @return array Modified list of classes.
 */
function spine_categorized_body_classes( $classes ) {
	if ( has_category() && is_singular() ) {
		foreach ( get_the_category( get_the_ID() ) as $category ) {
			$classes[] = 'categorized-' . $category->slug;
		}
	}
	if ( has_tag() && is_singular() ) {
		foreach ( get_the_tags( get_the_ID() ) as $tag ) {
			$classes[] = 'tagged-' . $tag->slug;
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
		$depth = count( $path );
		if ( is_front_page() ) { $depth = '0'; }
		$classes[] = 'depth-' . $depth;
		$strip = array( '?', '=' );

		if ( 1 === count( $path ) ) {
			$classes[] = 'section-' . str_replace( $strip, '', $path[0] );
			$classes[] = 'page-' . str_replace( $strip, '', $path[0] );
		} else {
			$classes[] = 'section-' . array_shift( $path );
			$prefix = 'sub-';
			foreach ( $path as $part ) {
				$classes[] = $prefix . 'section-' . $part;
				$prefix = 'sub-' . $prefix;
			}
			$classes[] = 'page-' . array_pop( $path );
		}
	}

	return array_unique( $classes );
}

add_filter( 'post_class', 'spine_excerpt_style_classes' );
/**
 * Add excerpt style in classes to article on list views.
 *
 * @param array $classes List of classes to be added to the article element.
 *
 * @return array Modified list of classes.
 */
function spine_excerpt_style_classes( $classes ) {
	global $post;

	if ( ! is_admin() && ! is_singular() ) {
		if ( $post->post_excerpt ) {
			$classes[] = 'summary-excerpted';
		} elseif ( strstr( $post->post_content, '<!--more-->' ) ) {
			$classes[] = 'summary-divided';
		} elseif ( 'excerpt' === spine_get_option( 'archive_content_display' ) ) {
			$classes[] = 'summary-truncated';
		} else {
			$classes[] = 'summary-unabridged';
		}
		if ( spine_has_background_image() ) { $classes[] = 'has-background-image'; }
		if ( spine_has_featured_image() ) { $classes[] = 'has-featured-image'; }
		if ( spine_has_thumbnail_image() ) { $classes[] = 'has-thumbnail-image'; }
	}

	return $classes;
}


add_filter( 'safecss_default_css', 'spine_editcss_intro' );
/**
 * Filter the introductory text display in the editCSS plugin.
 */
function spine_editcss_intro() {
	return "Welcome to custom CSS for the WSU Spine Theme!

You may delete these comments and get started with your custom stylesheet. Before doing so, please review the WSU web standards:

https://brand.wsu.edu/media/web/web-standards/

As a general rule of thumb, if your styles target aspects of the spine (#spine), that's against standard, whereas if you're styling elsewhere on the page, it's all good.";

}

add_filter( 'wsuwp_install_site_description', 'spine_install_site_description' );
/**
 * Filter the default site description used when creating a new site on the WSUWP Platform.
 *
 * @return string Site description to use for the new site.
 */
function spine_install_site_description() {
	return 'A new WSU WordPress website';
}

add_filter( 'wsuwp_first_page_template', 'spine_install_default_template' );
/**
 * Filter the template used for the home page when creating a new site on the WSUWP Platform.
 *
 * @return string Default template to use for the first page.
 */
function spine_install_default_template() {
	return 'template-builder.php';
}

add_filter( 'wsuwp_first_page_title', 'spine_install_default_title' );
/**
 * Filter the title used for the home page when creating a new site on the WSUWP Platform.
 *
 * @return string Default title to add to the home page.
 */
function spine_install_default_title() {
	return 'Home';
}

add_filter( 'wsuwp_first_page_content', 'spine_install_default_content' );
/**
 * Filter the content used for the home page when creating a new site on the WSUWP Platform.
 *
 * @return string Default content to add to the home page.
 */
function spine_install_default_content() {
	ob_start();

	?>
	<section class="row single h1-header gutter pad-top">
		<div class="column one ">
			<h1>Welcome</h1>
		</div>
	</section>
	<section class="row single gutter padded-top">
		<div class="column one">
			<p>This home page was automatically created with your new site. As soon as this page is edited, this introduction will be replaced with the content you save.</p>
			<p>You can login to your dashboard at <a href="<?php echo esc_url( admin_url() ); ?>"><?php echo esc_url( admin_url() ); ?></a>.</p>
		</div>
	</section>
	<section class="row single gutter pad-top">
		<div class="column one">
			<h2>Getting started</h2>
			<ul>
				<li>Verify your site's title and tagline description in <a href="<?php echo esc_url( admin_url( 'options-general.php' ) ); ?>">General Settings</a>.</li>
				<li>Use the <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">Customizer</a> to modify WSU Spine options and customize several parts of your site.</li>
				<li>Add <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>">Pages</a> and modify <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">Menus</a> to begin building out your site.</li>
				<li>Add <a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">Posts</a> to share updates on your work with the world.</li>
				<li>Join the <a href="https://wsu-web.slack.com/signup">WSU Web Slack</a> team to discuss your site with the WSU web community.</li>
				<li>Attend <a href="https://web.wsu.edu/open-lab/">Open Labs</a> on Friday mornings to do the same in person.</li>
				<li>Subscribe to posts on <a href="https://web.wsu.edu/">web.wsu.edu</a> to receive updates on the web at WSU.</li>
			</ul>
		</div>
		<div class="column two"></div>
	</section>
	<?php
	$page_content = ob_get_contents();
	ob_end_clean();

	return $page_content;
}

/**
 * Build an appropriate title for a page view. This title should include the site name
 * and end with '| Washington State University'
 *
 * @return string Built title.
 */
function spine_get_title() {
	$site_part = get_option( 'blogname' );
	$global_part = ' | Washington State University';
	$view_title = wp_title( '|', false, 'right' );

	$title = $view_title . $site_part . $global_part;

	return apply_filters( 'spine_get_title', $title, $site_part, $global_part, $view_title );
}

add_filter( 'tribe_events_title_tag', 'spine_tribe_events_title_tag' );
/**
 * Adds a separator to titles filtered by The Events Calendar.
 *
 * @since 0.27.9
 *
 * @param string $title
 *
 * @return string
 */
function spine_tribe_events_title_tag( $title ) {
	return $title . ' | ';
}

/**
 * Run an individual content syndicate item through wpautop. This is attached through
 * the page builder template, which normally removes the use of wpautop completely so
 * that it can process its sections.
 *
 * @param $subset
 * @param $post
 * @param $atts
 *
 * @return mixed
 */
function spine_filter_local_content_syndicate_item( $subset, $post, $atts ) {
	if ( ! isset( $atts['scheme'] ) || 'local' !== $atts['scheme'] ) {
		return $subset;
	}

	$subset->content = wpautop( $subset->content );

	return $subset;
}

add_action( 'add_meta_boxes_page', 'add_body_class_meta_box', 10 );
/**
 * Add a metabox to Pages for assigning an arbitrary body class.
 */
function add_body_class_meta_box() {
	add_meta_box(
		'wsuwp-body-class-meta',
		'Body Classes',
		'display_body_class_meta_box',
		'page',
		'side',
		'default'
	);
}

/**
 * Display the metabox used for assigning a body class.
 *
 * @param WP_Post $post Object for the post currently being edited.
 */
function display_body_class_meta_box( $post ) {
	$value = get_post_meta( $post->ID, '_wsuwp_body_class', true );

	wp_nonce_field( 'save-wsuwp-body-class', '_wsuwp_body_class_nonce' );

	?>
	<input type="text" class="widefat" name="wsuwp_body_class" value="<?php echo esc_attr( $value ); ?>" />
	<p class="howto">Separate classes with spaces</p>
	<?php
}

add_action( 'save_post', 'save_body_classes', 10, 2 );
/**
 * Save the body class(es) assigned to a page.
 *
 * @param int     $post_id ID of the post being saved.
 * @param WP_Post $post    Post object of the post being saved.
 */
function save_body_classes( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'page' !== $post->post_type ) {
		return;
	}

	if ( 'auto-draft' === $post->post_status ) {
		return;
	}

	if ( ! isset( $_POST['_wsuwp_body_class_nonce'] ) || false === wp_verify_nonce( $_POST['_wsuwp_body_class_nonce'], 'save-wsuwp-body-class' ) ) {
		return;
	}

	if ( isset( $_POST['wsuwp_body_class'] ) && ! empty( trim( $_POST['wsuwp_body_class'] ) ) ) {
		update_post_meta( $post_id, '_wsuwp_body_class', sanitize_text_field( $_POST['wsuwp_body_class'] ) );
	} else {
		delete_post_meta( $post_id, '_wsuwp_body_class' );
	}
}

add_filter( 'body_class', 'page_body_class' );
/**
 * Add body classes added via the Body Classes metabox.
 *
 * @param array $classes Current list of body classes.
 *
 * @return array Modified list of body classes.
 */
function page_body_class( $classes ) {
	if ( is_singular() ) {
		$_post = get_post();

		$body_classes = get_post_meta( $_post->ID, '_wsuwp_body_class', true );

		if ( $body_classes ) {
			$classes[] = esc_attr( $body_classes );
		}
	}

	return $classes;
}

/**
 * Return a version number that can be used to help break cache on child themes.
 *
 * @since 0.26.15
 *
 * @return string
 */
function spine_get_child_version() {
	return apply_filters( 'spine_child_theme_version', spine_get_script_version() );
}

/**
 * Determines whether breadcrumbs should be displayed in a given location.
 *
 * @since 0.27.0
 *
 * @param string $position
 * @return bool True if breadcrumbs should display. False if not.
 */
function spine_display_breadcrumbs( $position ) {
	if ( ! function_exists( 'bcn_display' ) ) {
		return false;
	}

	$setting = spine_get_option( 'show_breadcrumbs' );

	if ( 'top' === $position && in_array( $setting, array( 'top', 'both' ), true ) ) {
		return true;
	}

	if ( 'bottom' === $position && in_array( $setting, array( 'bottom', 'both' ), true ) ) {
		return true;
	}

	return false;
}
