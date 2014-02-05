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
		echo $topmost_parent->post_title;
		}
	else {
		echo $post->post_title;
		}
	}
function section_slug(){
	global $post;
	if ( is_page() && $post->post_parent ) {
		$parents = array_reverse(get_post_ancestors($post->id));
		$topmost_parent = get_page($parents[0]);
		echo $topmost_parent->post_name;
		}
	else {
		echo $post->post_name;
		}
	}



// on backend area
// add_action( 'admin_head', 'fb_move_admin_bar' );
// on frontend area
add_action( 'wp_head', 'fb_move_admin_bar' );

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
include_once('includes/customizer.php');

// TEMPLATES


// ADMIN MODS

// Add CSS files
function spine_theme_admin_styles() {
    wp_enqueue_style('admin-interface-styles', get_template_directory_uri() . '/admin/admin.css');
    add_editor_style('admin-editor-styles', get_template_directory_uri() . '/admin/editor.css');
}
add_action('admin_enqueue_scripts', 'spine_theme_admin_styles');

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
    </style>';
}

/**
 * Repeatable Custom Fields in a Metabox
 * Author: Helen Hou-Sandi
 *
 * From a bespoke system, so currently not modular - will fix soon
 * Note that this particular metadata is saved as one multidimensional array (serialized)
 */
 
function get_column_options() {
	$column_options = array (
		'One' => '1',
		'Two' => '2',
		'Three' => '3',
		'Four' => '4',
		'Five' => '5',
		'Six' => '6',
		'Seven' => '7',
		'Eight' => '8',
	);
	return $column_options;
	}
function get_section_options() {
	$section_options = array (
		'One' => '1',
		'Two' => '2',
		'Three' => '3',
		'Four' => '4',
		'Five' => '5',
		'Six' => '6',
		'Seven' => '7',
		'Eight' => '8',
	);
	return $section_options;
	}
 
add_action('admin_init', 'wsu_add_meta_boxes', 1);
function wsu_add_meta_boxes() {
	add_meta_box( 'page-sections', 'Sections', 'wsu_repeatable_meta_box_display', 'page', 'normal', 'default');
}
 
function wsu_repeatable_meta_box_display() {
	global $post;
 
	$sections = get_post_meta($post->ID, 'sections', true);
	
	$section_options = get_section_options();
	$column_options = get_column_options();
 
	wp_nonce_field( 'wsu_repeatable_meta_box_nonce', 'wsu_repeatable_meta_box_nonce' );
	?>
	<script type="text/javascript">
	jQuery(document).ready(function( $ ){
		$( '#add-row' ).on('click', function() {
			var row = $( '.empty-row.screen-reader-text' ).clone(true);
			row.removeClass( 'empty-row screen-reader-text' );
			row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
			return false;
		});
  	
		$( '.remove-row' ).on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});
		
		$("select#column-count").change( function() { var section_cols = $("#column-count").value(); $(this).parents("tr").siblings('tr.columns-editors'); } )
	});
	</script>
  
	<table id="repeatable-fieldset-one" width="100%">
	<tbody>
	<?php
	
	if ( $sections ) :
	
	foreach ( $sections as $section ) {
	?>
	<tr>
		<td>
			<label for="section-number">Section Number</label>
			<select name="name[]">
			<?php foreach ( $section_options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"<?php selected( $section['name'], $value ); ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
	
		<td>
			<label for="column-count">Column Count</label>
			<select id="column-count" name="select[]">
			<?php foreach ( $column_options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"<?php selected( $section['select'], $value ); ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
	
		<td></td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<tr class="columns-editors">
		<td colspan="4">

		<?php
			if ($section['url'] != '' ) {
				$content = $section['url']; } else {
				$content = ''; };
				$editor_id = 'column_editor';
			
			wp_editor( $content, $editor_id, $settings = array('textarea_name' => 'url[]') );
			
			?>
		</td>
	</tr>
	<?php
	}
	else :
	// show a blank one
	?>
	<tr>
		<td>
			<label for="section-number">Section Number</label>
			<select name="name[]">
			<?php foreach ( $section_options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
			
		</td>
	
		<td>
			<label for="column-count">Column Count</label>
			<select name="select[]">
			<?php foreach ( $column_options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
	
		<td></td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<tr>
		<td colspan="4">
		<?php

			$content = '';
			$editor_id = 'column_editor';
			wp_editor( $content, $editor_id );
			
			?>
		</td>
	</tr>
	<?php endif; ?>
	
	<!-- empty hidden one for jQuery -->
	<tr class="empty-row screen-reader-text">
		<td><input type="text" class="widefat" name="name[]" /></td>
	
		<td>
			<select name="select[]">
			<?php foreach ( $column_options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
		
		<td><input type="text" class="widefat" name="url[]" value="http://" /></td>
		  
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<tr class="empty-row screen-reader-text">
		<td colspan="4">
		<textarea class="wp-editor-area" cols="40" name="url[]" value=""></textarea>
		</td>
	</tr>
	</tbody>
	</table>
	
	<p><a id="add-row" class="button" href="#">Add another</a></p>
	<?php
}
 
add_action('save_post', 'wsu_repeatable_meta_box_save');
function wsu_repeatable_meta_box_save($post_id) {
	if ( ! isset( $_POST['wsu_repeatable_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['wsu_repeatable_meta_box_nonce'], 'wsu_repeatable_meta_box_nonce' ) )
		return;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	if (!current_user_can('edit_post', $post_id))
		return;
	
	$old = get_post_meta($post_id, 'sections', true);
	$new = array();
	
	$column_options = get_column_options();
	$section_options = get_section_options();
	
	$names = $_POST['name'];
	$selects = $_POST['select'];
	$urls = $_POST['url'];
	
	$count = count( $names );
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $names[$i] != '' ) :
			$new[$i]['name'] = stripslashes( strip_tags( $names[$i] ) );
			
			if ( in_array( $selects[$i], $column_options ) )
				$new[$i]['select'] = $selects[$i];
			else
				$new[$i]['select'] = '';
		
			if ( $urls[$i] == 'http://' )
				$new[$i]['url'] = '';
			else
				$new[$i]['url'] = stripslashes( $urls[$i] ); // and however you want to sanitize
		endif;
	}
 
	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'sections', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'sections', $old );
}


?>