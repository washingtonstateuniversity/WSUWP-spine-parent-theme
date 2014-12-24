<?php
/**
 * @package Make
 */

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

//add_filter( 'wp_page_menu_args', 'ttfmake_page_menu_args' );

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

//add_filter( 'body_class', 'ttfmake_body_classes' );

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

//add_filter( 'wp_title', 'ttfmake_wp_title', 10, 2 );

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

//add_action( 'wp', 'ttfmake_setup_author' );

if ( ! function_exists( 'ttfmake_sanitize_text' ) ) :
/**
 * Allow only certain tags and attributes in a string.
 *
 * @since  1.0.0.
 *
 * @param  string    $string    The unsanitized string.
 * @return string               The sanitized string.
 */
function ttfmake_sanitize_text( $string ) {
	global $allowedtags;
	$expandedtags = $allowedtags;

	// span
	$expandedtags['span'] = array();

	// Enable id, class, and style attributes for each tag
	foreach ( $expandedtags as $tag => $attributes ) {
		$expandedtags[$tag]['id']    = true;
		$expandedtags[$tag]['class'] = true;
		$expandedtags[$tag]['style'] = true;
	}

	// br (doesn't need attributes)
	$expandedtags['br'] = array();

	/**
	 * Customize the tags and attributes that are allows during text sanitization.
	 *
	 * @since 1.4.3
	 *
	 * @param array     $expandedtags    The list of allowed tags and attributes.
	 * @param string    $string          The text string being sanitized.
	 */
	apply_filters( 'make_sanitize_text_allowed_tags', $expandedtags, $string );

	return wp_kses( $string, $expandedtags );
}
endif;

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

//add_filter( 'excerpt_more', 'ttfmake_excerpt_more' );

if ( ! function_exists( 'ttfmake_get_view' ) ) :
/**
 * Determine the current view.
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
		$post_parent      = get_post()->post_parent;
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

	/**
	 * Allow developers to dynamically change the view.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $view                The view name.
	 * @param string    $parent_post_type    The post type for the parent post of the current post.
	 */
	return apply_filters( 'make_get_view', $view, $parent_post_type );
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

	/**
	 * Allow developers to dynamically changed the result of the "has sidebar" check.
	 *
	 * @since 1.2.3.
	 *
	 * @param bool      $show_sidebar    Whether or not to show the sidebar.
	 * @param string    $location        The location of the sidebar being evaluated.
	 * @param string    $view            The view name.
	 */

	return apply_filters( 'make_has_sidebar', $show_sidebar, $location, $view );
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
			$description = __( 'This widget area is currently disabled. Enable it in the "Footer" panel of the Customizer.', 'make' );
		}
	}
	// Other sidebars
	else if ( false !== strpos( $sidebar_id, 'sidebar-' ) ) {
		$location = str_replace( 'sidebar-', '', $sidebar_id );

		$enabled_views = ttfmake_sidebar_list_enabled( $location );

		// Not enabled anywhere
		if ( empty( $enabled_views ) ) {
			$description = __( 'This widget area is currently disabled. Enable it in the "Content & Layout" panel of the Customizer.', 'make' );
		}
		// List enabled views
		else {
			$description = sprintf(
				__( 'This widget area is currently enabled for the following views: %s. Change this in the "Content & Layout" panel of the Customizer.', 'make' ),
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

	/**
	 * Filter the list of sidebars that are available for a specific location.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $enabled_views    The list of views enabled for the sidebar.
	 * @param string   $location         The location of the sidebar being evaulated.
	 */
	return apply_filters( 'make_sidebar_list_enabled', $enabled_views, $location );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_data' ) ) :
/**
 * Retrieve all of the data for the sections.
 *
 * @since  1.2.0.
 *
 * @param  string    $post_id    The post to retrieve the data from.
 * @return array                 The combined data.
 */
function ttfmake_get_section_data( $post_id ) {
	$ordered_data = array();
	$ids          = get_post_meta( $post_id, '_ttfmake-section-ids', true );
	$ids          = ( ! empty( $ids ) && is_array( $ids ) ) ? array_map( 'strval', $ids ) : $ids;
	$post_meta    = get_post_meta( $post_id );

	// Temp array of hashed keys
	$temp_data = array();

	// Any meta containing the old keys should be deleted
	if ( is_array( $post_meta ) ) {
		foreach ( $post_meta as $key => $value ) {
			// Only consider builder values
			if ( 0 === strpos( $key, '_ttfmake:' ) ) {
				// Get the individual pieces
				$temp_data[ str_replace( '_ttfmake:', '', $key ) ] = $value[0];
			}
		}
	}

	// Create multidimensional array from postmeta
	$data = ttfmake_create_array_from_meta_keys( $temp_data );

	// Reorder the data in the order specified by the section IDs
	if ( is_array( $ids ) ) {
		foreach ( $ids as $id ) {
			if ( isset( $data[ $id ] ) ) {
				$ordered_data[ $id ] = $data[ $id ];
			}
		}
	}

	/**
	 * Filter the section data for a post.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $ordered_data    The array of section data.
	 * @param int      $post_id         The post ID for the retrieved data.
	 */
	return apply_filters( 'make_get_section_data', $ordered_data, $post_id );
}
endif;

if ( ! function_exists( 'ttfmake_create_array_from_meta_keys' ) ) :
/**
 * Convert an array with array keys that map to a multidimensional array to the array.
 *
 * @since  1.2.0.
 *
 * @param  array    $arr    The array to convert.
 * @return array            The converted array.
 */
function ttfmake_create_array_from_meta_keys( $arr ) {
	// The new multidimensional array we will return
	$result = array();

	// Process each item of the input array
	foreach ( $arr as $key => $value ) {
		// Store a reference to the root of the array
		$current = & $result;

		// Split up the current item's key into its pieces
		$pieces = explode( ':', $key );

		/**
		 * For all but the last piece of the key, create a new sub-array (if necessary), and update the $current
		 * variable to a reference of that sub-array.
		 */
		for ( $i = 0; $i < count( $pieces ) - 1; $i++ ) {
			$step = $pieces[ $i ];
			if ( ! isset( $current[ $step ] ) ) {
				$current[ $step ] = array();
			}
			$current = & $current[ $step ];
		}

		// Add the current value into the final nested sub-array
		$current[ $pieces[ $i ] ] = $value;
	}

	// Return the result array
	return $result;
}
endif;

if ( ! function_exists( 'ttfmake_post_type_supports_builder' ) ) :
/**
 * Check if a post type supports the Make builder.
 *
 * @since  1.2.0.
 *
 * @param  string    $post_type    The post type to test.
 * @return bool                    True if the post type supports the builder; false if it does not.
 */
function ttfmake_post_type_supports_builder( $post_type ) {
	return post_type_supports( $post_type, 'make-builder' );
}
endif;

if ( ! function_exists( 'ttfmake_is_builder_page' ) ) :
/**
 * Determine if the post uses the builder or not.
 *
 * @since  1.2.0.
 *
 * @param  int     $post_id    The post to inspect.
 * @return bool                True if builder is used for post; false if it is not.
 */
function ttfmake_is_builder_page( $post_id = 0 ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	// Pages will use the template-builder.php template to denote that it is a builder page
	$has_builder_template = ( 'template-builder.php' === get_page_template_slug( $post_id ) );

	// Other post types will use meta data to support builder pages
	$has_builder_meta = ( 1 === (int) get_post_meta( $post_id, '_ttfmake-use-builder', true ) );

	$is_builder_page = $has_builder_template || $has_builder_meta;

	/**
	 * Allow a developer to dynamically change whether the post uses the builder or not.
	 *
	 * @since 1.2.3
	 *
	 * @param bool    $is_builder_page    Whether or not the post uses the builder.
	 * @param int     $post_id            The ID of post being evaluated.
	 */
	return apply_filters( 'make_is_builder_page', $is_builder_page, $post_id );
}
endif;

if ( ! function_exists( 'ttfmake_builder_css' ) ) :
/**
 * Trigger an action hook for each section on a Builder page for the purpose
 * of adding section-specific CSS rules to the document head.
 *
 * @since 1.4.5
 *
 * @return void
 */
function ttfmake_builder_css() {
	if ( ttfmake_is_builder_page() ) {
		$sections = ttfmake_get_section_data( get_the_ID() );

		if ( ! empty( $sections ) ) {
			foreach ( $sections as $id => $data ) {
				if ( isset( $data['section-type'] ) ) {
					/**
					 * Allow section-specific CSS rules to be added to the document head of a Builder page.
					 *
					 * @since 1.4.5
					 *
					 * @param array    $data    The Builder section's data.
					 * @param int      $id      The ID of the Builder section.
					 */
					do_action( 'make_builder_' . $data['section-type'] . '_css', $data, $id );
				}
			}
		}
	}
}
endif;

add_action( 'make_css', 'ttfmake_builder_css' );

if ( ! function_exists( 'ttfmake_builder_banner_css' ) ) :
/**
 * Add frontend CSS rules for Banner sections based on certain section options.
 *
 * @since 1.4.5
 *
 * @param array    $data    The banner's section data.
 * @param int      $id      The banner's section ID.
 *
 * @return void
 */
function ttfmake_builder_banner_css( $data, $id ) {
	$responsive = ( isset( $data['responsive'] ) ) ? $data['responsive'] : 'balanced';
	$slider_height = absint( $data['height'] );
	if ( 0 === $slider_height ) {
		$slider_height = 600;
	}
	$slider_ratio = ( $slider_height / 960 ) * 100;

	if ( 'aspect' === $responsive ) {
		ttfmake_get_css()->add( array(
			'selectors'    => array( '#builder-section-' . esc_attr( $id ) . ' .builder-banner-slide' ),
			'declarations' => array(
				'padding-bottom' => $slider_ratio . '%'
			),
		) );
	} else {
		ttfmake_get_css()->add( array(
			'selectors'    => array( '#builder-section-' . esc_attr( $id ) . ' .builder-banner-slide' ),
			'declarations' => array(
				'padding-bottom' => $slider_height . 'px'
			),
		) );
		ttfmake_get_css()->add( array(
			'selectors'    => array( '#builder-section-' . esc_attr( $id ) . ' .builder-banner-slide' ),
			'declarations' => array(
				'padding-bottom' => $slider_ratio . '%'
			),
			'media'        => 'screen and (min-width: 600px) and (max-width: 960px)'
		) );
	}
}
endif;

add_action( 'make_builder_banner_css', 'ttfmake_builder_banner_css', 10, 2 );