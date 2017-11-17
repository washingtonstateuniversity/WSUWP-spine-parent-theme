<?php
/**
 * Class Spine_Main_Header
 */
class Spine_Main_Header {
	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_header_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_main_header' ), 10, 2 );
	}

	/**
	 * Add meta boxes used to override the spine's main header.
	 */
	public function add_header_meta_box() {
		add_meta_box( 'spine-main-header', 'Spine Main Header', array( $this, 'display_main_header_meta_box' ), 'page' );
	}

	/**
	 * Display the meta box for controlling `sup-header` and `sub-header` overrides on
	 * a per page basis.
	 */
	public function display_main_header_meta_box( $post ) {
		wp_nonce_field( 'save-spine-main-header', '_spine_header_nonce' );

		$sup_header = get_post_meta( $post->ID, 'sup-header', true );
		$sub_header = get_post_meta( $post->ID, 'sub-header', true );
		?>
		<p class="description">Text entered here for the top and bottom header areas will override the default values provided by the parent theme.</p>
		<label for="spine_sup_header">Top Header Text:</label><br />
		<input type="text" class="widefat" name="spine_sup_header" id="spine_sup_header" value="<?php echo esc_attr( $sup_header ); ?>" />
		<br /><br />
		<label for="spine_sub_header">Bottom Header Text:</label><br />
		<input type="text" class="widefat" name="spine_sub_header" id="spine_sub_header" value="<?php echo esc_attr( $sub_header ); ?>" />
		<?php
	}

	/**
	 * Save a possible override of the default sup and sub headers at an individual page level.
	 *
	 * @param int     $post_id The current post ID.
	 * @param WP_Post $post    Object representing the current post.
	 */
	public function save_main_header( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['_spine_header_nonce'] ) || false === wp_verify_nonce( $_POST['_spine_header_nonce'], 'save-spine-main-header' ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( isset( $_POST['spine_sup_header'] ) && '' != trim( $_POST['spine_sup_header'] ) ) {
			$sup_header = sanitize_post_field( 'post_title', $_POST['spine_sup_header'], $post->ID, 'db' );
			update_post_meta( $post_id, 'sup-header', $sup_header );
		} else {
			delete_post_meta( $post_id, 'sup-header' );
		}

		if ( isset( $_POST['spine_sub_header'] ) && '' != trim( $_POST['spine_sub_header'] ) ) {
			$sub_header = sanitize_post_field( 'post_title', $_POST['spine_sub_header'], $post->ID, 'db' );
			update_post_meta( $post_id, 'sub-header', $sub_header );
		} else {
			delete_post_meta( $post_id, 'sub-header' );
		}
	}
}
new Spine_Main_Header();

function spine_section_meta( $attribute = 'slug', $sectional = 'subsection' ) {

	if ( ! is_singular() && ! in_the_loop() ) {
		return '';
	}

	if ( empty( $sectional ) ) {
		$sectional = 'subsection';
	}

	if ( empty( $attribute ) || 'slug' == $attribute ) {
		$attribute = 'post_name';
	}

	if ( 'title' == $attribute ) {
		$attribute = 'post_title';
	}

	$subsections = get_post_ancestors( get_the_ID() );
	if ( ! empty( $subsections ) ) {
		$subsection = get_post( $subsections[0] );
		$sections = array_reverse( $subsections );
		$section = get_post( $sections[0] );

		if ( isset( $sectional ) && in_array( $sectional, array( 'section', 'top' ) ) ) {
			return $section->$attribute;
		} else {
			return $subsection->$attribute;
		}
	}

	return null;
}

/**
 * Determine what should be displayed in the spine's main header area for the
 * sub and sub sections.
 *
 * @return array List of elements for output in main header.
 */
function spine_get_main_header() {
	$page_for_posts = absint( get_option( 'page_for_posts', 0 ) );

	if ( 0 !== $page_for_posts ) {
		$posts_page_title = get_the_title( $page_for_posts );
	} else {
		$posts_page_title = '';
	}

	$site_name          = get_bloginfo( 'name', 'display' );
	$site_tagline       = get_bloginfo( 'description', 'display' );
	$page_title         = get_the_title();
	$post_title         = get_the_title();
	$global_sup_header  = spine_get_option( 'global_main_header_sup' );
	$global_sub_header  = spine_get_option( 'global_main_header_sub' );

	// Attempt to determine the section and subsection through page hierarchy.
	$section_title      = spine_section_meta( 'title', 'section' );
	$subsection_title   = spine_section_meta( 'title', 'subsection' );

	// By default, the `sup-header` area is the site's configured Title
	$sup_header_default = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $site_name . '</a>';

	// The `sub-header` area is properly set in the conditional logic that follows.
	$sub_header_default = '';

	// Alternate `sup-header` and `sub-header` areas are available for targeting as data attributes via CSS.
	$sup_header_alternate = '';
	$sub_header_alternate = '';

	// On date archive views, use one of the day, month, year as the sub header. Use the page title of
	// page_for_posts if available as the sup header, otherwise use the site name.
	if ( is_archive() ) {
		if ( is_category() ) {
			$sub_header_default = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$sub_header_default = single_tag_title( '', false );
		} elseif ( is_tax( 'wsuwp_university_category' ) ) {
			$sub_header_default = single_term_title( '', false );
		} elseif ( is_day() ) {
			$sub_header_default = get_the_date();
		} elseif ( is_month() ) {
			$sub_header_default = get_the_date( 'F Y' );
		} elseif ( is_year() ) {
			$sub_header_default = get_the_date( 'Y' );
		} elseif ( is_author() ) {
			$sub_header_default = get_the_author();
		} else {
			$sub_header_default = 'Archives';
		}

		if ( 0 === $page_for_posts ) {
			$section_title = $site_name;
		} else {
			$section_title = $posts_page_title;
		}
	}

	// For any posts or post types, if page_for_posts is not set or this view is
	// of a custom post type, use the post type's label as the sub header. Otherwise
	// use the title of the page_for_posts page.
	if ( is_single() ) {
		if ( 0 === $page_for_posts || ! is_singular( 'post' ) ) {
			$post = get_post();
			$post_type = get_post_type_object( get_post_type( $post ) );
			$sub_header_default = $post_type->labels->name;
		} else {
			$sub_header_default = $posts_page_title;
		}
	}

	// If this page is a child of another page, use the subsection title as a sub
	// header. Otherwise, use the current page's title.
	if ( is_page() ) {
		if ( spine_is_sub() ) {
			$sub_header_default = $subsection_title;
		} else {
			$sub_header_default = $site_tagline;
		}
	}

	// If this is the front page, explicitly overwrite to defaults that may have been
	// changed in the is_page() area. In both the front page and in the next block for
	// is_home(), the site name as sup header should not link to home.
	if ( is_front_page() ) {
		$sup_header_default = $site_name;
		$sub_header_default = $site_tagline;
	}

	// If a static front page is set, `is_home()` will be true if this is the page for posts.
	if ( is_home() && ! is_front_page() ) {
		$sup_header_default = $site_name;

		if ( 0 === $page_for_posts ) {
			$page_title = $site_name;
			$sub_header_default = $site_tagline;
		} else {
			$sub_header_default = $posts_page_title;
			$page_title = $posts_page_title;
		}
	}

	if ( is_search() ) {
		$sup_header_alternate = 'Search Terms';
		$sub_header_default = 'Search Results';
		$sub_header_alternate = esc_html( get_search_query() );
	}

	if ( is_404() ) {
		$sub_header_default = 'Page not found';
	}

	// If global headers are chosen, store the default as alternate and assign the global.
	if ( '' !== trim( $global_sup_header ) ) {
		$sup_header_alternate = $sup_header_default;
		$sup_header_default = $global_sup_header;
	}
	if ( '' !== trim( $global_sub_header ) ) {
		$sub_header_alternate = $sub_header_default;
		$sub_header_default = $global_sub_header;
	}

	// Both sup and sub headers can be overridden with the use of post meta if
	// this is a singular template, the front page, or the page used for posts.
	if ( is_singular() || is_front_page() || ( is_home() && 0 < $page_for_posts ) ) {
		if ( is_home() && 0 < $page_for_posts ) {
			$post_id = $page_for_posts;
		} else {
			$post_id = get_the_ID();
		}

		$sup_override = get_post_meta( $post_id, 'sup-header', true );
		$sub_override = get_post_meta( $post_id, 'sub-header', true );

		if ( ! empty( $sup_override ) ) {
			$sup_header_default = wp_kses_post( $sup_override );
		}
		if ( spine_get_option( 'articletitle_header' ) == 'true' ) {
			$sub_header_default = $page_title;
		}
		if ( ! empty( $sub_override ) ) {
			$sub_header_default = wp_kses_post( $sub_override );
		}
	}

	$sup_header_default = apply_filters( 'spine_sup_header_default', $sup_header_default );
	$sub_header_default = apply_filters( 'spine_sub_header_default', $sub_header_default );

	$main_header_elements = array(
		'site_name'            => $site_name,
		'site_tagline'         => $site_tagline,
		'page_title'           => $page_title,
		'post_title'           => $post_title,
		'section_title'        => $section_title,
		'subsection_title'     => $subsection_title,
		'posts_page_title'     => $posts_page_title,
		'sup_header_default'   => $sup_header_default,
		'sub_header_default'   => $sub_header_default,
		'sup_header_alternate' => $sup_header_alternate,
		'sub_header_alternate' => $sub_header_alternate,
	);

	return apply_filters( 'spine_main_header_elements', $main_header_elements );
}
