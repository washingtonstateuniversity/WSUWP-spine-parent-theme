<?php
function ttfmake_get_google_font_uri() { return ''; }
if ( ! function_exists( 'ttfmake_page_menu_args' ) ) :
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since  1.0.0.
 *
 * @param  array    $args    Configuration arguments.
 * @return array             Modified page menu args.
 */
function ttfmake_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
endif;

add_filter( 'wp_page_menu_args', 'ttfmake_page_menu_args' );

if ( ! function_exists( 'ttfmake_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0.0.
 *
 * @param  array    $classes    Classes for the body element.
 * @return array                Modified class list.
 */
function ttfmake_body_classes( $classes ) {
	// Left Sidebar
	if ( true === ttfmake_has_sidebar( 'left' ) ) {
		$classes[] = 'has-left-sidebar';
	}

	// Right Sidebar
	if ( true === ttfmake_has_sidebar( 'right' ) ) {
		$classes[] = 'has-right-sidebar';
	}

	return $classes;
}
endif;

add_filter( 'body_class', 'ttfmake_body_classes' );

if ( ! function_exists( 'ttfmake_wp_title' ) ) :
/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since  1.0.0.
 *
 * @param  string    $title    Default title text for current view.
 * @param  string    $sep      Optional separator.
 *
 * @return string              The filtered title.
 */
function ttfmake_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'make' ), max( $paged, $page ) );
	}

	return $title;
}
endif;

add_filter( 'wp_title', 'ttfmake_wp_title', 10, 2 );

if ( ! function_exists( 'ttfmake_setup_author' ) ) :
/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function ttfmake_setup_author() {
	global $wp_query;

	if ( ! isset( $GLOBALS['authordata'] ) && $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
endif;

add_action( 'wp', 'ttfmake_setup_author' );

if ( ! function_exists( 'sanitize_hex_color' ) ) :
/**
 * Sanitizes a hex color.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return null;
}
endif;

if ( ! function_exists( 'sanitize_hex_color_no_hash' ) ) :
/**
 * Sanitizes a hex color without a hash. Use sanitize_hex_color() when possible.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function sanitize_hex_color_no_hash( $color ) {
	$color = ltrim( $color, '#' );

	if ( '' === $color ) {
		return '';
	}

	return sanitize_hex_color( '#' . $color ) ? $color : null;
}
endif;

if ( ! function_exists( 'maybe_hash_hex_color' ) ) :
/**
 * Ensures that any hex color is properly hashed.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function maybe_hash_hex_color( $color ) {
	if ( $unhashed = sanitize_hex_color_no_hash( $color ) ) {
		return '#' . $unhashed;
	}

	return $color;
}
endif;

if ( ! function_exists( 'ttfmake_excerpt_more' ) ) :
/**
 * Modify the excerpt suffix
 *
 * @since 1.0.0.
 *
 * @param string $more
 *
 * @return string
 */
function ttfmake_excerpt_more( $more ) {
	return ' &hellip;';
}
endif;

add_filter( 'excerpt_more', 'ttfmake_excerpt_more' );

if ( ! function_exists( 'ttfmake_get_view' ) ) :
/**
 * Determine the current view
 *
 * For use with view-related theme options.
 *
 * @since  1.0.0.
 *
 * @return string    The string representing the current view.
 */
function ttfmake_get_view() {
	// Post types
	$post_types = get_post_types(
		array(
			'public' => true,
			'_builtin' => false
		)
	);
	$post_types[] = 'post';

	// Post parent
	$parent_post_type = '';
	if ( is_attachment() ) {
		$post_parent = get_post()->post_parent;
		$parent_post_type = get_post_type( $post_parent );
	}

	$view = 'post';

	// Blog
	if ( is_home() ) {
		$view = 'blog';
	}
	// Archives
	else if ( is_archive() ) {
		$view = 'archive';
	}
	// Search results
	else if ( is_search() ) {
		$view = 'search';
	}
	// Posts and public custom post types
	else if ( is_singular( $post_types ) || ( is_attachment() && in_array( $parent_post_type, $post_types ) ) ) {
		$view = 'post';
	}
	// Pages
	else if ( is_page() || ( is_attachment() && 'page' === $parent_post_type ) ) {
		$view = 'page';
	}

	// Filter the view and return
	return apply_filters( 'ttfmake_get_view', $view, $parent_post_type );
}
endif;

if ( ! function_exists( 'ttfmake_has_sidebar' ) ) :
/**
 * Determine if the current view should show a sidebar in the given location.
 *
 * @since  1.0.0.
 *
 * @param  string    $location    The location to test for.
 * @return bool                   Whether or not the location has a sidebar.
 */
function ttfmake_has_sidebar( $location ) {
	global $wp_registered_sidebars;

	// Validate the sidebar location
	if ( ! in_array( 'sidebar-' . $location, array_keys( $wp_registered_sidebars ) ) ) {
		return false;
	}

	// Get the view
	$view = ttfmake_get_view();

	// Get the relevant option
	$show_sidebar = (bool) get_theme_mod( 'layout-' . $view . '-sidebar-' . $location, ttfmake_get_default( 'layout-' . $view . '-sidebar-' . $location ) );

	// Builder template doesn't support sidebars
	if ( 'page' === $view && 'template-builder.php' === get_page_template_slug() ) {
		$show_sidebar = false;
	}

	// Filter and return
	return apply_filters( 'ttfmake_has_sidebar', $show_sidebar, $location, $view );
}
endif;

if ( ! function_exists( 'ttfmake_sidebar_description' ) ) :
/**
 * Output a sidebar description that reflects its current status.
 *
 * @since  1.0.0.
 *
 * @param  string    $sidebar_id    The sidebar to look up the description for.
 * @return string                   The description.
 */
function ttfmake_sidebar_description( $sidebar_id ) {
	$description = '';

	// Footer sidebars
	if ( false !== strpos( $sidebar_id, 'footer-' ) ) {
		$column = (int) str_replace( 'footer-', '', $sidebar_id );
		$column_count = (int) get_theme_mod( 'footer-widget-areas', ttfmake_get_default( 'footer-widget-areas' ) );

		if ( $column > $column_count ) {
			$description = __( 'This widget area is currently disabled. Enable it in the "Footer" section of the Theme Customizer.', 'make' );
		}
	}
	// Other sidebars
	else if ( false !== strpos( $sidebar_id, 'sidebar-' ) ) {
		$location = str_replace( 'sidebar-', '', $sidebar_id );

		$enabled_views = ttfmake_sidebar_list_enabled( $location );

		// Not enabled anywhere
		if ( empty( $enabled_views ) ) {
			$description = __( 'This widget area is currently disabled. Enable it in the "Layout" section of the Theme Customizer.', 'make' );
		}
		// List enabled views
		else {
			$description = sprintf(
				__( 'This widget area is currently enabled for the following views: %s. Change this in the "Layout" section of the Theme Customizer.', 'make' ),
				esc_html( implode( _x( ', ', 'list item separator', 'make' ), $enabled_views ) )
			);
		}
	}

	return esc_html( $description );
}
endif;

if ( ! function_exists( 'ttfmake_sidebar_list_enabled' ) ) :
/**
 * Compile a list of views where a particular sidebar is enabled.
 *
 * @since  1.0.0.
 *
 * @param  string    $location    The sidebar to look up.
 * @return array                  The sidebar's current locations.
 */
function ttfmake_sidebar_list_enabled( $location ) {
	$enabled_views = array();

	$views = array(
		'blog'    => __( 'Blog (Post Page)', 'make' ),
		'archive' => __( 'Archives', 'make' ),
		'search'  => __( 'Search Results', 'make' ),
		'post'    => __( 'Posts', 'make' ),
		'page'    => __( 'Pages', 'make' ),
	);

	foreach ( $views as $view => $label ) {
		$option = (bool) get_theme_mod( 'layout-' . $view . '-sidebar-' . $location, ttfmake_get_default( 'layout-' . $view . '-sidebar-' . $location ) );
		if ( true === $option ) {
			$enabled_views[] = $label;
		}
	}

	return $enabled_views;
}
endif;
