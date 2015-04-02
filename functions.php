<?php

// Global version tracker.
$wsuwp_spine_theme_version = '0.20.1';

include_once( 'includes/theme-setup.php' ); // Setup basic portions of the theme.
include_once( 'includes/theme-navigation.php' ); // Include functionality for navigation.
include_once( 'includes/main-header.php' ); // Include main header functionality.
include_once( 'includes/customizer/customizer.php' ); // Include customizer functionality.
include_once( 'includes/theme-images.php' ); // Manipulating images

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
 * If enabled at the platform or installation level, include the
 * necessary files for the Make builder tool.
 *
 * Note: admin_init is too late for this to be brought in.
 */
function spine_load_builder_module() {
	if ( true === apply_filters( 'spine_enable_builder_module', false ) ) {
		include_once( 'inc/builder.php' );
	}
}

add_filter( 'theme_page_templates', 'spine_show_builder_page_template', 10, 1);
/**
 * If builder functionality is not available, do not show the builder template
 * on the list of available page templates.
 *
 * @param array $page_templates List of available page templates.
 *
 * @return array Modified list of page templates.
 */
function spine_show_builder_page_template( $page_templates ) {
	if ( false === apply_filters( 'spine_enable_builder_module', false ) ) {
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
	$campus_urls = array(
		'extension'              => 'extension.wsu.edu',
		'globalcampus'           => 'globalcampus.wsu.edu',
		'healthsciences-spokane' => 'spokane.wsu.edu',
		'spokane'                => 'spokane.wsu.edu',
		'tricities'              => 'tricities.wsu.edu',
		'vancouver'              => 'vancouver.wsu.edu',
	);
	$campus_location = spine_get_option( 'campus_location' );

	if ( isset( $campus_urls[ $campus_location ] ) ) {
		return esc_url( $campus_urls[ $campus_location ] );
	}

	return 'https://wsu.edu/';
}

/**
 * A set of defaults for the options set in the customizer for the Spine theme.
 *
 * @return array List of default options.
 */
function spine_get_option_defaults() {
	return array(
		'spine_version'             => '1',
		'grid_style'                => 'hybrid',
		'campus_location'           => '',
		'spine_color'               => 'white',
		'large_format'              => '',
		'theme_style'               => 'bookmark',
		'secondary_colors'          => 'gray',
		'theme_spacing'             => 'default',
		'global_main_header_sup'	=> '',
		'global_main_header_sub'	=> '',
		'main_header_show'          => true,
		'articletitle_show'         => true,
		'articletitle_header'       => false,
		'broken_binding'            => false,
		'bleed'                     => true,
		'search_state'              => 'closed',
		'crop'                      => false,
		'spineless'                 => false,
		'open_sans'                 => 0,
		'contact_name'              => 'Washington State University',
		'contact_department'        => '',
		'contact_url'               => '',
		'contact_streetAddress'     => 'PO Box 641227',
		'contact_addressLocality'   => 'Pullman, WA',
		'contact_postalCode'        => '99164',
		'contact_telephone'         => '(509) 335-3564',
		'contact_email'             => 'info@wsu.edu',
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
 * the Customizer.
 *
 * @return array List of font weights and styles.
 */
function spine_get_open_sans_options() {
	$spine_open_sans = get_option( 'spine_open_sans', array() );
	$fonts = array();

	foreach( $spine_open_sans as $k => $v ) {
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
	$fonts = array();

	foreach( $spine_open_sans_cond as $k => $v ) {
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
		wp_enqueue_style( 'spine-theme',       get_template_directory_uri()   . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
		if ( 'skeletal' !== spine_get_option( 'theme_style' ) ) {
			wp_enqueue_style( 'spine-theme-extra', get_template_directory_uri()   . '/styles/' . spine_get_option( 'theme_style' ) . '.css', array(), spine_get_script_version() );
		}
		wp_enqueue_style( 'spine-theme-child', get_stylesheet_directory_uri() . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
	} else {
		wp_enqueue_style( 'spine-theme',       get_template_directory_uri()   . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
		if ( 'skeletal' !== spine_get_option( 'theme_style' ) ) {
			wp_enqueue_style( 'spine-theme-extra', get_template_directory_uri()   . '/styles/' . spine_get_option( 'theme_style' ) . '.css', array(), spine_get_script_version() );
		}
	}

	// All theme styles have been output at this time. Plugins and other themes should print styles here, before blocking
	// Javascript resources are output.
	do_action( 'spine_enqueue_styles' );

	$google_font_css_url = '//fonts.googleapis.com/css?family=';
	$build_open_sans_css = '';
	$build_open_sans_cond_css = '';
	$count = 0;
	$spine_open_sans = spine_get_open_sans_options();

	/**
	 * Build the URL used to pull additional Open Sans font weights and styles from
	 * Google. If this page view has an admin bar, we can assume that several weights
	 * and styles are already loaded and remove those from the requested set.
	 */
	if ( ! empty( $spine_open_sans ) ) {
		$wp_default_open_sans = array( '300italic', '400italic', '600italic', '300', '400', '600' );

		foreach( $spine_open_sans as $font_option ) {
			if ( is_admin_bar_showing() && in_array( $font_option, $wp_default_open_sans ) ) {
				continue;
			}
			if ( 0 === $count ) {
				$build_open_sans_css = 'Open+Sans%3A' . $font_option;
			} else {
				$build_open_sans_css .= '%2C' . $font_option;
			}

			$count++;
		}

		if ( 0 !== $count ) {
			$google_font_css_url .= $build_open_sans_css;
		}
	}

	$spine_open_sans_condensed = spine_get_open_sans_condensed_options();

	$condensed_count = 0;
	if ( ! empty( $spine_open_sans_condensed ) ) {
		if ( 0 !== $count ) {
			$build_open_sans_cond_css = '|Open+Sans+Condensed%3A';
		} else {
			$build_open_sans_cond_css = 'Open+Sans+Condensed%3A';
		}

		foreach( $spine_open_sans_condensed as $font_option ) {
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

	if( 0 !== $count ) {
		wp_enqueue_style( 'spine-open-sans', $google_font_css_url, array(), false );
	}

	// WordPress core provides much of jQuery UI, but not in a nice enough package to enqueue all at once.
	// For this reason, we'll pull the entire package from the Google CDN.
	wp_enqueue_script( 'wsu-jquery-ui-full', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', array( 'jquery' ) );

	// Much relies on the main Javascript provided by the WSU Spine.
	wp_enqueue_script( 'wsu-spine', 'https://repo.wsu.edu/spine/' . $spine_version . '/spine.min.js', array( 'wsu-jquery-ui-full' ), spine_get_script_version(), false );

	// Override default options in the WSU Spine.
	$spineoptions = array(
		'social' => array(
			'share_text' => esc_js( spine_get_title() ),
			'twitter_text' => esc_js( spine_get_title() ),
			'twitter_handle' => 'wsupullman',
		),
	);
	// If a Twitter account has been added in the Customizer, use that for the via handle.
	$spine_social_options = spine_social_options();
	if ( isset( $spine_social_options['twitter'] ) ) {
		$twitter_array =  array_filter( explode( '/', $spine_social_options['twitter'] ) );
		$twitter_handle = array_pop( $twitter_array );
		$spineoptions['social']['twitter_handle'] = esc_js( $twitter_handle );
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
}

add_action( 'admin_enqueue_scripts', 'spine_admin_enqueue_scripts' );
/**
 * Enqueue styles required for admin pageviews.
 */
function spine_admin_enqueue_scripts() {
	wp_enqueue_style( 'admin-interface-styles', get_template_directory_uri() . '/includes/admin.css' );
	wp_enqueue_script( 'admin-interface-scripts', get_template_directory_uri() . '/includes/admin.js' );
	add_editor_style( 'includes/editor.css' );
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
		'after_title'   => '</header>'
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

		$text = apply_filters( 'the_content', $text );
		$text = str_replace( ']]>', ']]&gt;', $text );

		$allowed_tags = '<p>,<a>,<em>,<strong>,<img>';
		$text = strip_tags( $text, $allowed_tags );

		$excerpt_word_count = 105;
		$excerpt_length = apply_filters( 'excerpt_length', $excerpt_word_count );

		$excerpt_end = '... <a href="' . get_permalink() . '" class="more-link"><span class="more-default">' . '&raquo; More ...' . '</span></a>';
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . $excerpt_end );

		$words = preg_split( "/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
		if ( count( $words ) > $excerpt_length ) {
			array_pop( $words );
			$text = implode( ' ', $words );
			$text = $text . $excerpt_more;
		} else {
			$text = implode( ' ', $words );
		}
	}
	return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt );
}

/**
 * Determine if the current page has a parent.
 *
 * @return bool|int The ID of the parent if found, otherwise false.
 */
function spine_is_sub() {
    $post = get_post();

    if ( is_page() && $post->post_parent ) {
        return $post->post_parent;
    } else {
		return false;
	}
}

add_filter( 'body_class', 'spine_site_body_class');
/**
 * Add body classes for the site domain and path to help with targeting on multiple
 * sites using this theme.
 *
 * @param array $classes
 *
 * @return array
 */
function spine_site_body_class( $classes ) {
	if ( ! function_exists( 'wsuwp_get_current_site' ) ) {
		return $classes;
	}

	$site = wsuwp_get_current_site();
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
		$classes[] = esc_attr( spine_get_option( 'campus_location' ) ).'-signature';
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

add_filter( 'body_class','spine_theme_images_classes' );
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

add_filter('body_class', 'spine_categorized_body_classes');
/**
 * Add 'categorized' in classes to body on singular views.
 *
 * @param array $classes List of classes to be added to the body element.
 *
 * @return array Modified list of classes.
 */
function spine_categorized_body_classes( $classes ) {
	if ( has_category() && is_singular() ) {
		foreach( get_the_category( get_the_ID() ) as $category ) {
			$classes[] = 'categorized-' . $category->slug;
		}
	}
	if ( has_tag() && is_singular() ) {
		foreach( get_the_tags( get_the_ID() ) as $tag ) {
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
	if ( !is_singular() ) {
		
		if ( $post->post_excerpt ) {
			$classes[] = "summary-excerpted";
		} elseif ( strstr( $post->post_content, '<!--more-->' ) ) {
			$classes[] = "summary-divided";
		} elseif ( 'excerpt' === spine_get_option( 'archive_content_display' ) ) {
			$classes[] = "summary-truncated";
		} else {
			$classes[] = "summary-unabridged";
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

http://brand.wsu.edu/media/web/web-standards/

As a general rule of thumb, if your styles target aspects of the spine (#spine), that's against standard, whereas if you're styling elsewhere on the page, it's all good.";

}

add_filter( 'wsuwp_first_page_template', 'spine_install_default_template' );
/**
 * Filter the template used for the home page when creating a new site on the WSUWP Platform.
 *
 * @return string Default template to use for the first page.
 */
function spine_install_default_template() {
	return 'templates/single.php';
}

add_filter( 'wsuwp_first_page_title', 'spine_install_default_title' );
/**
 * Filter the title used for the home page when creating a new site on the WSUWP Platform.
 *
 * @return string Default title to add to the home page.
 */
function spine_install_default_title() {
	return 'Welcome to the WSU Web';
}

add_filter( 'wsuwp_first_page_content', 'spine_install_default_content' );
/**
 * Filter the content used for the home page when creating a new site on the WSUWP Platform.
 *
 * @return string Default content to add to the home page.
 */
function spine_install_default_content() {
	$page_content = '<p>As a visual element, the WSU Spine is a 198px wide column that binds together the many websites of wsu.edu. As a framework, the WSU Spine is a minimal template that provides global tools and a responsive and flexible grid for every WSU website. With a uniform and global spine on the left and a blank, unwritten page to the right, the Spine balances the unity and diversity of our university.</p>
	<img src="' . esc_url( get_template_directory_uri() . '/includes/customizer/customizer.png' ) . '" class="alignright">
	<h2>Getting Started</h2>
	<ol>
		<li>After <a href="' . esc_url( wp_login_url() ) . '">logging in</a>, head to the <a href="' . esc_url( admin_url( 'customize.php?theme=spine' ) ) . '">Customizer</a>.</li>
		<li>Enter your Site Title and Tagline.</li>
		<li>Expand "Contact Details" and enter the information of the unit responsible for this site.</li>
		<li>Optionally, you can replace or remove one or more of the university\'s social channels.</li>
		<li>Optionally, you can alter the Spine\'s default behavior in "Spine Options".</li>
		<li>Head to <a href="' . esc_url( admin_url( 'edit.php?post_type=page' ) ) . '">Pages</a> and <a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">Appearance -> Menus</a> to begin building out your site.</li>
		<li>And finally, delete or modify your <a href="' . esc_url( admin_url( 'post.php?post=1&action=edit' ) ) . '">Hello World post</a> to remove this primer.</li>
	</ol>';

	return $page_content;
}

/**
 * Build an appropriate title for a page view. This title should include the site name
 * and end with '| Washington State University'
 *
 * @return string Built title.
 */
function spine_get_title() {
	$site_part = ' ' . get_option( 'blogname' );
	$global_part = ' | Washington State University';
	$view_title = wp_title( '|', false, 'right' );

	$title = $view_title . $site_part . $global_part;

	return apply_filters( 'spine_get_title', $title, $site_part, $global_part, $view_title );
}