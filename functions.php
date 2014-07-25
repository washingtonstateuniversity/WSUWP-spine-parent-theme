<?php

// Global version tracker.
$wsuwp_spine_theme_version = '0.9.12';

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
		'spine-version'             => '1',
		'grid_style'                => 'hybrid',
		'spine_color'               => 'white',
		'large_format'              => '',
		'theme_style'               => 'bookmark',
		'secondary_colors'          => 'gray',
		'theme_spacing'             => 'default',
		'main_header_show'          => true,
		'articletitle_show'         => true,
		'articletitle_header'       => false,
		'broken_binding'            => false,
		'bleed'                     => true,
		'crop'			            => false,
		'spineless'		            => false,
		'open_sans'                 => false,
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
	}

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

add_action( 'init', 'spine_theme_menus' );
/**
 * Provide default navigation menus.
 */
function spine_theme_menus() {
	register_nav_menus(
		array(
		'site'    => 'Site',
		'offsite' => 'Offsite',
		)
	);
}

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

		$excerpt_end = '... <a href="' . get_permalink() . '">' . '&raquo; More ...' . '</a>';
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

add_filter( 'img_caption_shortcode', 'spine_theme_caption_markup', 10, 3 );
/**
 * Modify the markup for an image caption.
 *
 * @param string $output  Empty by default.
 * @param array  $attr    Attributes passed to the caption shortcode.
 * @param string $content The content being parsed.
 *
 * @return string Modified output. If returned empty, default processing will continue.
 */
function spine_theme_caption_markup( $output, $attr, $content ) {
	if ( is_feed() ) {
		return $output;
	}

	$defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => 0,
		'caption' => ''
	);

	$attr = shortcode_atts( $defaults, $attr );
	$attr['width'] = absint( $attr['width'] );

	if ( 1 > $attr['width'] || empty( $attr['caption'] ) ) {
		return $content;
	}

	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="' . esc_attr( $attr['align'] ) . '"';
	$attributes .= ' style="width:' . $attr['width'] . 'px;"';

	$output = '<figure' . $attributes .'><div class="liner cf">';
	$output .= do_shortcode( $content );
	$output .= '<figcaption>' . $attr['caption'] . '</figcaption>';
	$output .= '</div></figure>';

	return $output;
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
	
	if ( spine_has_background_image() ) { $classes[] = 'has-background-image'; }
	if ( spine_has_featured_image() ) { $classes[] = 'has-featured-image'; }
	if ( spine_has_thumbnail_image() ) { $classes[] = 'has-thumbnail-image'; }

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
 * and end with 'at WSU'
 *
 * @return string Built title.
 */
function spine_get_title() {
	$site_part = ' ' . get_option( 'blogname' );
	$global_part = ' at WSU';
	$view_title = wp_title( '|', false, 'right' );

	$title = $view_title . $site_part . $global_part;

	return apply_filters( 'spine_get_title', $title, $site_part, $global_part, $view_title );
}