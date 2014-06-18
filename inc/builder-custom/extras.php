<?php
function ttfmake_get_google_font_uri() { return ''; }

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

if( ! function_exists( 'ttfmake_is_plus' ) ) :
	/**
	 * Parts of the page builder templates look for the plus version of Make.
	 * 
	 * @return bool
	 */
	function ttfmake_is_plus() {
		return false;
	}
endif;