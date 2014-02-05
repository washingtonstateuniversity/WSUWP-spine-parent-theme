<?php

// Two Navigation Menus
add_action( 'init', 'spine_menus' );
function spine_menus() {
	register_nav_menus(
		array(
		'site' => 'Site',
		'offsite' => 'Offsite'
		)
	);
}

// A Single Sidebar
register_sidebar(array(
  'name' => 'Sidebar',
  'description' => __( 'Widgets in this area will be shown on the right-hand side.' ),
  'before_title' => '<header>',
  'after_title' => '</header>',
  'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  'after_widget' => '</aside>'
));


// DEFAULTS

// Condense verbose menu classes
add_filter( 'nav_menu_css_class', 'abbridged_menu_classes', 10, 3 );
function abbridged_menu_classes( $classes, $item, $args ) {
	if ( in_array( 'current-menu-item', $classes ) )
		return array( 'current' );
	return array();	
}

// Default Image Sizes
update_option('thumbnail_size_w', 198);
update_option('thumbnail_size_h', 198);
update_option('medium_size_w', 396);
update_option('medium_size_h', 99163);
update_option('large_size_w', 792);
update_option('large_size_h', 99163);
// update_option('full_size_w', 1980);
// update_option('full_size_h', 99163);

add_theme_support('post-thumbnails');
set_post_thumbnail_size( 198, 198, true );

add_image_size( 'teaser-image', 198, 198, true );
add_image_size( 'header-image', 792, 99163 );
add_image_size( 'billboard-image', 1584, 99163 );

/* Default Image Markup */

add_filter( 'img_caption_shortcode', 'caption_markup', 10, 3 );

function caption_markup( $output, $attr, $content ) {

	/* We're not worried abut captions in feeds, so just return the output here. */
	if ( is_feed() )
		return $output;

	/* Set up the default arguments. */
	$defaults = array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	);

	/* Merge the defaults with user input. */
	$attr = shortcode_atts( $defaults, $attr );

	/* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
		return $content;

	/* Set up the attributes for the <figcaption>. */
	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="' . esc_attr( $attr['align'] ) . '"';
	// $attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px"';

	/* Open the caption <div>. */
	$output = '<figure' . $attributes .'><div class="liner cf">';

	/* Allow shortcodes for the content the caption was created for. */
	$output .= do_shortcode( $content );

	/* Append the caption text. */
	$output .= '<figcaption>' . $attr['caption'] . '</figcaption>';

	/* Close the caption </div>. */
	$output .= '</div></figure>';

	/* Return the formatted, clean caption. */
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

// Sectioning
function is_subpage() {
    global $post;
    if ( is_page() && $post->post_parent ) {
        return $post->post_parent;
    } else { return false; }
}

function section_title(){
	global $post;
	if ( is_page() && $post->post_parent ) {
		$parents = array_reverse(get_post_ancestors($post->id));
		$topmost_parent = get_page($parents[0]);
		return $topmost_parent->post_title;
		}
	else {
		return $post->post_title;
		}
	}
function section_slug(){
	global $post;
	if ( is_page() && $post->post_parent ) {
		$parents = array_reverse(get_post_ancestors($post->id));
		$topmost_parent = get_page($parents[0]);
		return $topmost_parent->post_name;
		}
	else {
		return $post->post_name;
		}
	}



// Default Widget Markup
if (function_exists('register_sidebar')) {
        $widget_options = array(
        'name' => __( 'Sidebar', 'sidebar' ),
        'id' => 'sidebar',
        'before_widget' =>  '<aside id="%1$s2" class="%2$s">',
        'after_widget'  =>  '</aside>',
        'before_title'  =>  '<header>',
        'after_title'   =>  '</header>'
    );
    register_sidebar($widget_options);
}

// Default Read More
function spine_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
}
add_filter( 'excerpt_more', 'spine_excerpt_more' );

// Extend Body Class 

add_filter('body_class','extend_body_classes');
function extend_body_classes($classes) {
	$stippled = 'stippled-'.mt_rand(0,19); // Add Randomizer
	$classes[] = $stippled;
	return $classes;
}


// CUSTOMIZATION
include_once('admin/customizer.php');

// TEMPLATES


// ADMIN MODS

// Add CSS files
function spine_theme_admin_styles() {
    wp_enqueue_style('admin-interface-styles', get_template_directory_uri() . '/admin/admin.css');
    add_editor_style('admin-editor-styles', get_template_directory_uri() . '/admin/editor.css');
}
add_action('admin_enqueue_scripts', 'spine_theme_admin_styles');

// Move Admin Bar to Bottom
function fb_move_admin_bar() {
    echo '<style type="text/css">
   body.admin-bar {
        margin-top: -32px !important;
        padding-bottom: 32px !important;
    }
    #wpadminbar {
        top: auto !important;
        bottom: 0;
    }
    #wpadminbar .quicklinks>ul>li {
        position:relative;
    }
    #wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper {
        bottom:32px;
        box-shadow: none;
    }
    @media (max-width: 779px) {
    
    	body.admin-bar {
	        margin-top: -46px !important;
	        padding-bottom: 46px !important;
		}
		#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper {
	        bottom:46px;
		}
    
    }
    </style>';
}
// on backend area
// add_action( 'admin_head', 'fb_move_admin_bar' );
// on frontend area
add_action( 'wp_head', 'fb_move_admin_bar' );

// Ad Hoc Sections
include_once('admin/repeater.php');
 


?>