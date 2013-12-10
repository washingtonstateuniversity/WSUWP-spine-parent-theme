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
  'id' => 'sidebar',
  'description' => __( 'Widgets in this area will be shown on the right-hand side.' ),
  'before_title' => '<h1>',
  'after_title' => '</h1>'
));

// Condense wordy menu classes
add_filter( 'nav_menu_css_class', 'abbridged_menu_classes', 10, 3 );
function abbridged_menu_classes( $classes, $item, $args ) {
	if ( in_array( 'current-menu-item', $classes ) )
		return array( 'current' );
	return array();	
}

// ADMIN MODS

// Add CSS files
function spine_theme_admin_styles() {
    wp_enqueue_style('admin-interface-styles', get_template_directory_uri() . '/admin/admin.css');
    add_editor_style('admin-editor-styles', get_template_directory_uri() . '/admin/editor.css');
}
add_action('admin_enqueue_scripts', 'spine_theme_admin_styles');


// DEFAULTS

// Default Image Sizes
add_theme_support('post-thumbnails');
update_option('thumbnail_size_w', 198);
update_option('thumbnail_size_h', 198);
update_option('medium_size_w', 396);
update_option('medium_size_h', 9999);
update_option('large_size_w', 792);
update_option('large_size_h', 9999);
// update_option('full_size_w', 1980);
// update_option('full_size_h', 9999);

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


// CUSTOMIZATION
// http://codex.wordpress.org/Plugin_API/Action_Reference/customize_register
// http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/

function spine_theme_customize_styles() {
    wp_enqueue_style('customize-interface-styles', get_template_directory_uri() . '/admin/customize.css');
}
add_action( 'customize_controls_enqueue_scripts', 'spine_theme_customize_styles' );

function spine_theme_customize_scripts() {
    wp_enqueue_script('customize-interface-scripts', get_template_directory_uri().'/admin/customize.js', array( 'jquery','customize-preview' ),'',true );
}
add_action( 'customize_controls_enqueue_scripts', 'spine_theme_customize_scripts' );

function spine_customize_register($wp_customize){
 
    $wp_customize->add_section('section_spine_options', array(
        'title'    => __('Spine Options', 'spine'),
        'priority' => 124,
    ));
 
    // Grid
    $wp_customize->add_setting('spine_options[grid_style]', array(
        'default'        => 'hybrid',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 
    $wp_customize->add_control('spine_grid_style', array(
        'label'      => __('Grid Behavior', 'spine'),
        'section'    => 'section_spine_options',
        'settings'   => 'spine_options[grid_style]',
        'type'       => 'radio',
        'choices'    => array(
            'fixed' => 'Fixed',
            'hybrid' => 'Hybrid',
            'fluid' => 'Fluid'
        ),
    ));
    
 
    // Spine Color
    $wp_customize->add_setting('spine_options[spine_color]', array(
        'default'        => 'white',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
 
    ));
    $wp_customize->add_control( 'spine_color_select', array(
        'settings' => 'spine_options[spine_color]',
        'label'   => 'Spine Color',
        'section' => 'section_spine_options',
        'type'    => 'select',
        'choices'    => array(
            'white' => 'Default (white)',
            'lightest' => 'Lightest',
            'lighter' => 'Lighter',
            'light' => 'Light',
            'gray' => 'Gray',
            'dark' => 'Dark',
            'darker' => 'Darker',
            'darkest' => 'Darkest (black)',
            'crimson' => 'Crimson',
            'velum' => 'Transparent'
        ),
    ));
    
    
    // Bleed Spine Leftward
    $wp_customize->add_setting('spine_options[bleed]', array(
        'default'        => false,
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 
    $wp_customize->add_control('spine_bleed', array(
        'label'      => __('Bleed Left', 'spine'),
        'section'    => 'section_spine_options',
        'settings'   => 'spine_options[bleed]',
        'type'       => 'checkbox'
    ));
    
    $wp_customize->add_section('static_front_page', array(
        'title'    => __('Front Page', 'static_front_page'),
    ));
    
    // SOCIAL CHANNELS
    $wp_customize->add_section('section_spine_social', array(
        'title'    => __('Social Channels', 'spine_advanced'),
        'priority' => 300,
        'description'    => __( 'You can retain, replace, or remove social channels. Select "None" to remove/hide a location.' ),
    ));
    
	
    
    // Location One
    $wp_customize->add_setting('spine_options[social_spot_one]', array( 'default' => 'http://www.facebook.com', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('social_spot_one', array( 'section' => 'section_spine_social', 'settings' => 'spine_options[social_spot_one]', 'priority' => 302 ));
    
    $wp_customize->add_setting('spine_options[social_spot_one_type]', array( 'default' => 'facebook', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('social_spot_one_type', array(
    	'label' => __('Location One', 'spine'),
    	'section' => 'section_spine_social',
    	'settings' => 'spine_options[social_spot_one_type]',
    	'type' => 'select',
    	'choices' => array('none' => 'None', 'facebook' => 'Facebook', 'twitter' => 'Twitter', 'youtube' => 'YouTube', 'directory' => 'Directory', 'linkedin' => 'LinkedIn', 'tumblr' => 'Tumblr', 'pinterest' => 'Pinterest'),
    	'priority' => 301
    	));
    
    // Location Two
    $wp_customize->add_setting('spine_options[social_spot_two]', array( 'default' => 'http://twitter.com/wsupullman', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('social_spot_two', array( 'section' => 'section_spine_social', 'settings' => 'spine_options[social_spot_two]', 'priority' => 304 ));
    
    $wp_customize->add_setting('spine_options[social_spot_two_type]', array( 'default' => 'twitter', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('social_spot_two_type', array(
    	'label' => __('Location Two', 'spine'), 
    	'section' => 'section_spine_social',
    	'settings' => 'spine_options[social_spot_two_type]',
    	'type' => 'select',
    	'choices' => array('none' => 'None', 'facebook' => 'Facebook', 'twitter' => 'Twitter', 'youtube' => 'YouTube', 'directory' => 'Directory', 'linkedin' => 'LinkedIn', 'tumblr' => 'Tumblr', 'pinterest' => 'Pinterest'),
    	'priority' => 303
    	));
    
    // Location Three
    $wp_customize->add_setting('spine_options[social_spot_three]', array( 'default' => 'http://www.youtube.com/washingtonstateuniv', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('social_spot_three', array( 'section' => 'section_spine_social', 'settings' => 'spine_options[social_spot_three]', 'priority' => 306 ));
    
    $wp_customize->add_setting('spine_options[social_spot_three_type]', array( 'default' => 'youtube', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('social_spot_three_type', array(
    	'label' => __('Location Three', 'spine'),
    	'section' => 'section_spine_social',
    	'settings' => 'spine_options[social_spot_three_type]',
    	'type' => 'select',
    	'choices' => array('none' => 'None', 'facebook' => 'Facebook', 'twitter' => 'Twitter', 'youtube' => 'YouTube', 'directory' => 'Directory', 'linkedin' => 'LinkedIn', 'tumblr' => 'Tumblr', 'pinterest' => 'Pinterest'),
    	'priority' => 305
    	));
    
    // Location Four
    $wp_customize->add_setting('spine_options[social_spot_four]', array( 'default' => 'http://social.wsu.edu', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('social_spot_four', array( 'section' => 'section_spine_social', 'settings' => 'spine_options[social_spot_four]', 'priority' => 308 ));
    
    $wp_customize->add_setting('spine_options[social_spot_four_type]', array( 'default' => 'directory', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('social_spot_four_type', array(
    	'label' => __('Location Four', 'spine'),
    	'section' => 'section_spine_social',
    	'settings' => 'spine_options[social_spot_four_type]',
    	'type' => 'select',
    	'choices' => array('none' => 'None', 'facebook' => 'Facebook', 'twitter' => 'Twitter', 'youtube' => 'YouTube', 'directory' => 'Directory', 'linkedin' => 'LinkedIn', 'tumblr' => 'Tumblr', 'pinterest' => 'Pinterest'),
    	'priority' => 307
    	));
    	
    	
    // Contact
    
    $wp_customize->add_section('section_spine_contact', array(
        'title'    => __('Contact Details', 'spine'),
        'priority' => 400,
        'description'    => __( 'This is the official contact for your website.' ),
    ));
    
    $wp_customize->add_setting('spine_options[contact_unit]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('contact_unit', array( 'label' => 'Your Unit (Dep., College, Initiative, etc.)', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_unit]', 'priority' => 402 ));
    
    $wp_customize->add_setting('spine_options[contact_address]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('contact_address', array( 'label' => 'Your Address', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_address]', 'type' => 'text', 'priority' => 402 ));
    $wp_customize->add_setting('spine_options[contact_address_two]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('contact_address_two', array( 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_address_two]', 'type' => 'text', 'priority' => 402 ));
    
    $wp_customize->add_setting('spine_options[contact_telephone]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('contact_telephone', array( 'label' => 'Best Phone Number', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_telephone]', 'type' => 'text', 'priority' => 402 ));
    
    $wp_customize->add_setting('spine_options[contact_email]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('contact_email', array( 'label' => 'Best Email Address', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_email]', 'type' => 'text', 'priority' => 402 ));
    
    $wp_customize->add_setting('spine_options[contact_page]', array( 'default' => '', 'capability' => 'edit_theme_options', 'type' => 'option' ));
    $wp_customize->add_control('contact_page', array( 'label' => 'Contact Page/Directory (Optional)', 'section' => 'section_spine_contact', 'settings' => 'spine_options[contact_page]', 'type' => 'text', 'priority' => 402 ));

    
    // Advanced
    $wp_customize->add_section('section_spine_advanced_options', array(
        'title'    => __('Advanced', 'spine_advanced'),
        'priority' => 1000,
    ));
    
    // Large Format
    $wp_customize->add_setting('spine_options[large_format]', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 
    $wp_customize->add_control('spine_large_format', array(
        'label'      => __('Large Format', 'spine'),
        'section'    => 'section_spine_advanced_options',
        'settings'   => 'spine_options[large_format]',
        'type'       => 'select',
        'choices'    => array(
            ''  => 'Default Width of 990px',
            ' folio max-1188' => 'Max Width 1188px',
            ' folio max-1386' => 'Max Width 1386px',
            ' folio max-1584' => 'Max Width 1584px',
            ' folio max-1782' => 'Max Width 1782px',
            ' folio max-1980' => 'Max Width 1980px',
            
        ),
    ));
    
    // Offer Dynamic Shortcuts
    $wp_customize->add_setting('spine_options[index_shortcuts]', array(
        'default'        => 'google',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 
    $wp_customize->add_control('spine_index_shortcuts', array(
        'label'      => __('Offer AZ Index Shortcuts', 'spine'),
        'section'    => 'section_spine_advanced_options',
        'settings'   => 'spine_options[index_shortcuts]',
        'type'       => 'checkbox'
    ));

    
    $wp_customize->add_setting('spine_options[local_site_shortcuts]', array(
        'default'        => 'google',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 
    $wp_customize->add_control('spine_local_site_shortcuts', array(
        'label'      => __('Offer Local Site Shortcuts', 'spine'),
        'section'    => 'section_spine_advanced_options',
        'settings'   => 'spine_options[local_site_shortcuts]',
        'type'       => 'checkbox'
    ));
    
    // Local Search
    $wp_customize->add_setting('spine_options[search_local]', array(
        'default'        => 'google',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 
    $wp_customize->add_control('spine_search_local', array(
        'label'      => __('Local Search Engine', 'spine'),
        'section'    => 'section_spine_advanced_options',
        'settings'   => 'spine_options[search_local]',
        'type'       => 'radio',
        'choices'    => array(
            'google' => 'Google',
            'wordpress' => 'Wordpress'
        ),
    ));
 
}
 
add_action('customize_register', 'spine_customize_register');

// TEMPLATES



?>

<?
/**
 * Repeatable Custom Fields in a Metabox
 * Author: Helen Hou-Sandi
 *
 * From a bespoke system, so currently not modular - will fix soon
 * Note that this particular metadata is saved as one multidimensional array (serialized)
 */
 
function hhs_get_sample_options() {
	$options = array (
		'Option 1' => 'option1',
		'Option 2' => 'option2',
		'Option 3' => 'option3',
		'Option 4' => 'option4',
	);
	
	return $options;
}
 
add_action('admin_init', 'hhs_add_meta_boxes', 1);
function hhs_add_meta_boxes() {
	add_meta_box( 'repeatable-fields', 'Repeatable Fields', 'hhs_repeatable_meta_box_display', 'post', 'normal', 'default');
}
 
function hhs_repeatable_meta_box_display() {
	global $post;
 
	$repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);
	$options = hhs_get_sample_options();
 
	wp_nonce_field( 'hhs_repeatable_meta_box_nonce', 'hhs_repeatable_meta_box_nonce' );
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
	});
	</script>
  
	<table id="repeatable-fieldset-one" width="100%">
	<thead>
		<tr>
			<th width="40%">Name</th>
			<th width="12%">Select</th>
			<th width="40%">URL</th>
			<th width="8%"></th>
		</tr>
	</thead>
	<tbody>
	<?php
	
	if ( $repeatable_fields ) :
	
	foreach ( $repeatable_fields as $field ) {
	?>
	<tr>
		<td><input type="text" class="widefat" name="name[]" value="<?php if($field['name'] != '') echo esc_attr( $field['name'] ); ?>" /></td>
	
		<td>
			<select name="select[]">
			<?php foreach ( $options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"<?php selected( $field['select'], $value ); ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
	
		<td><input type="text" class="widefat" name="url[]" value="<?php if ($field['url'] != '') echo esc_attr( $field['url'] ); else echo 'http://'; ?>" /></td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php
	}
	else :
	// show a blank one
	?>
	<tr>
		<td><input type="text" class="widefat" name="name[]" /></td>
	
		<td>
			<select name="select[]">
			<?php foreach ( $options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
	
		<td><input type="text" class="widefat" name="url[]" value="http://" /></td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php endif; ?>
	
	<!-- empty hidden one for jQuery -->
	<tr class="empty-row screen-reader-text">
		<td><input type="text" class="widefat" name="name[]" /></td>
	
		<td>
			<select name="select[]">
			<?php foreach ( $options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
		
		<td><input type="text" class="widefat" name="url[]" value="http://" /></td>
		  
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	</tbody>
	</table>
	
	<p><a id="add-row" class="button" href="#">Add another</a></p>
	<?php
}
 
add_action('save_post', 'hhs_repeatable_meta_box_save');
function hhs_repeatable_meta_box_save($post_id) {
	if ( ! isset( $_POST['hhs_repeatable_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['hhs_repeatable_meta_box_nonce'], 'hhs_repeatable_meta_box_nonce' ) )
		return;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	if (!current_user_can('edit_post', $post_id))
		return;
	
	$old = get_post_meta($post_id, 'repeatable_fields', true);
	$new = array();
	$options = hhs_get_sample_options();
	
	$names = $_POST['name'];
	$selects = $_POST['select'];
	$urls = $_POST['url'];
	
	$count = count( $names );
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $names[$i] != '' ) :
			$new[$i]['name'] = stripslashes( strip_tags( $names[$i] ) );
			
			if ( in_array( $selects[$i], $options ) )
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
		update_post_meta( $post_id, 'repeatable_fields', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'repeatable_fields', $old );
}
?>