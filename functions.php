<?php

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



// DEFAULTS

// Condense verbose menu classes
add_filter( 'nav_menu_css_class', 'spine_abbridged_menu_classes', 10, 3 );
function spine_abbridged_menu_classes( $classes, $item, $args ) {
	if ( in_array( ('current-menu-item'), $classes ) || in_array( ('current_page_parent'), $classes ) ) {
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

function spine_is_sub() {
    global $post;
    if ( is_page() && $post->post_parent ) {
        return $post->post_parent;
    } else {
		return false;
	}
}

function spine_section_meta($attribute='slug',$sectional='subsection') {
	global $post;
	if ( !isset($sectional) ) { $sectional = 'subsection'; }
	if ( !isset($attribute) || $attribute == 'slug' ) { $attribute = 'post_name'; }
	if ( $attribute == 'title' || $attribute == 'title' ) { $attribute = 'post_title'; }
	if ( is_page() && $post->post_parent ) {
		$subsections = get_post_ancestors($post->id);
		$subsection = get_page($subsections[0]);
		$sections = @array_reverse( get_post_ancestors($post->id) );
		$section = get_page($sections[0]);
		
		if ( isset($sectional) && ($sectional == 'section' || $sectional == 'top') ) {
			return $section->$attribute;
		} else {
			return $subsection->$attribute;
		}
	} else { return null; }

	}

// Add Randomized Body Classes
add_filter( 'body_class','spine_speckled_body_classes' );
function spine_speckled_body_classes( $classes ) {
	$classes[] = 'five'.mt_rand(1,5);
	$classes[] = 'ten'.mt_rand(1,10);
	$classes[] = 'twenty'.mt_rand(1,20);
	return $classes;
	}

// Add Categorized Body Classes
add_filter('body_class', 'spine_categorized_body_classes');
function spine_categorized_body_classes( $classes ) {
global $post;
if ( $post && $post->ID && has_category() && is_singular() ) {
	foreach((get_the_category($post->ID)) as $category) {
		$classes[] = 'categorized-'.trim($category->slug);
	}
	/*foreach((wp_get_object_terms($post->ID,'tags')) as $term) {
		$classes[] = 'term-'.trim($term->slug); */
	}
	return array_unique($classes);
	}

/**
 * Add custom body classes based on the requested URL.
 *
 * @param array $classes Current list of body classes.
 *
 * @return array Modified list of body classes.
 */
function spine_sectioned_body_classes( $classes ) {
	$post = get_post();

	if ( $post && $post->ID ) {
		$url = $_SERVER['REQUEST_URI'];
		$url = parse_url( $url );
		$path = $url['path'];
		$skips = trim( $path, '/' );
		$hops = explode( '/', $skips );
		$depth = count( $hops ) - 1;

		$classes[] = 'depth-' . $depth;

		$sub = '';
		$last = end( $hops );
		$lastkey = key( $hops );

		foreach( $hops as $hop => $hopped ) {
			$classes[] = $sub . 'section-' . trim( $hopped );

			$sub = 'sub-' . $sub;
			if ( $lastkey == $hop ) {
				$classes[] = 'page-' . trim( $hopped );
			}
		}
	}

	return array_unique( $classes );
}
add_filter( 'body_class', 'spine_sectioned_body_classes' );

// ...

// Default Read More
function spine_theme_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '" >More</a>';
	}
add_filter( 'excerpt_more', 'spine_theme_excerpt_more' );


// MAIN HEADER
include_once( 'admin/main-header.php' );

// CUSTOMIZATION
include_once( 'admin/customizer.php' );

// TEMPLATES

// ADMIN MODS

// Add CSS files
function spine_theme_admin_styles() {
    wp_enqueue_style( 'admin-interface-styles', get_template_directory_uri() . '/admin/admin.css' );
    add_editor_style( 'admin-editor-styles', get_template_directory_uri() . '/admin/editor.css' );
}
add_action( 'admin_enqueue_scripts', 'spine_theme_admin_styles' );


// Ad Hoc Sections
// include_once('admin/sections.php');