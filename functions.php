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

function spine_theme_customize_styles() {
    wp_enqueue_style('admin-interface-styles', get_template_directory_uri() . '/admin/customize.css');
}
add_action( 'customize_controls_enqueue_scripts', 'spine_theme_customize_styles' );


// CUSTOMIZATION
// http://codex.wordpress.org/Plugin_API/Action_Reference/customize_register

function spine_customize_register($wp_customize){
 
    $wp_customize->add_section('section_spine_options', array(
        'title'    => __('Spine Options', 'spine'),
        'priority' => 124,
    ));
 
    // Grid
    $wp_customize->add_setting('spine_theme_options[grid_style]', array(
        'default'        => 'hybrid',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 
    $wp_customize->add_control('spine_grid_style', array(
        'label'      => __('Grid Behavior', 'spine'),
        'section'    => 'section_spine_options',
        'settings'   => 'spine_theme_options[grid_style]',
        'type'       => 'radio',
        'choices'    => array(
            'fixed' => 'Fixed',
            'hybrid' => 'Hybrid',
            'fluid' => 'Fluid'
        ),
    ));
 
    // Spine Color
    $wp_customize->add_setting('spine_theme_options[spine_color]', array(
        'default'        => 'white',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
 
    ));
    $wp_customize->add_control( 'spine_color_select', array(
        'settings' => 'spine_theme_options[spine_color]',
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
            'crimson' => 'Crimson'
        ),
    ));
    
    $wp_customize->add_section('static_front_page', array(
        'title'    => __('Front Page', 'static_front_page'),
    ));
    
    // Advanced
    $wp_customize->add_section('section_spine_advanced_options', array(
        'title'    => __('Advanced', 'spine_advanced'),
        'priority' => 125,
    ));
    
    // Large Format
    $wp_customize->add_setting('spine_theme_options[large_format]', array(
        'default'        => 'hybrid',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 
    $wp_customize->add_control('spine_large_format', array(
        'label'      => __('Large Format', 'spine'),
        'section'    => 'section_spine_advanced_options',
        'settings'   => 'spine_theme_options[large_format]',
        'type'       => 'radio',
        'choices'    => array(
            'folio' => 'Max Width 1800px'
        ),
    ));
 
}
 
add_action('customize_register', 'spine_customize_register');

?>