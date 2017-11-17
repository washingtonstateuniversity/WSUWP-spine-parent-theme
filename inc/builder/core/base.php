<?php
/**
 * @package Make
 */

if ( ! function_exists( 'TTFMAKE_Builder_Base' ) ) :
/**
 * Defines the functionality for the HTML Builder.
 *
 * @since 1.0.0.
 */
class TTFMAKE_Builder_Base {
	/**
	 * The one instance of TTFMAKE_Builder_Base.
	 *
	 * @since 1.0.0.
	 *
	 * @var   TTFMAKE_Builder_Base
	 */
	private static $instance;

	/**
	 * Instantiate or return the one TTFMAKE_Builder_Base instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Builder_Base
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initiate actions.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Builder_Base
	 */
	public function __construct() {
		// Include the API
		require get_template_directory() . '/inc/builder/core/api.php';

		// Add the core sections
		require get_template_directory() . '/inc/builder/sections/section-definitions.php';

		// Include the save routines
		require get_template_directory() . '/inc/builder/core/save.php';

		// Include the front-end helpers
		require get_template_directory() . '/inc/builder/sections/section-front-end-helpers.php';

		// Set up actions
		add_action( 'admin_init', array( $this, 'register_post_type_support_for_builder' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 1 ); // Bias toward top of stack
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 11 );
		add_action( 'admin_print_styles-post.php', array( $this, 'admin_print_styles' ) );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'admin_print_styles' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		add_action( 'admin_footer', array( $this, 'print_templates' ) );
		add_action( 'tiny_mce_before_init', array( $this, 'tiny_mce_before_init' ), 15, 2 );
		add_action( 'after_wp_tiny_mce', array( $this, 'after_wp_tiny_mce' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'builder_toggle' ) );

		if ( false === ttfmake_is_plus() ) {
			add_action( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );
		}
	}

	/**
	 * Add support for post types to use the Make builder.
	 *
	 * @since  1.3.0.
	 *
	 * @return void
	 */
	public function register_post_type_support_for_builder() {
		add_post_type_support( 'page', 'make-builder' );
	}

	/**
	 * Add the meta box.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		foreach ( ttfmake_get_post_types_supporting_builder() as $name ) {
			add_meta_box(
				'ttfmake-builder',
				__( 'Page Builder', 'make' ),
				array( $this, 'display_builder' ),
				$name,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Display the checkbox to turn the builder on or off.
	 *
	 * @since  1.2.0.
	 *
	 * @return void
	 */
	public function builder_toggle() {
		// Do not show the toggle for pages as the builder is controlled by page templates
		if ( 'page' === get_post_type() ) {
			return;
		}

		// Only show the builder toggle for CPTs that support the builder
		if ( ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}

		// If the builder is forced to be toggled on, don't show the option to toggle off.
		if ( apply_filters( 'spine_builder_force_builder', false ) ) {
			return;
		}

		$using_builder = get_post_meta( get_the_ID(), '_ttfmake-use-builder', true );
	?>
		<div class="misc-pub-section">
			<input type="checkbox" value="1" name="use-builder" id="use-builder"<?php checked( $using_builder, 1 ); ?> />
			&nbsp;<label for="use-builder"><?php _e( 'Use Page Builder', 'make' ); ?></label>
		</div>
	<?php
	}

	/**
	 * Display the meta box.
	 *
	 * @since  1.0.0.
	 *
	 * @param  WP_Post    $post_local    The current post object.
	 * @return void
	 */
	public function display_builder( $post_local ) {
		wp_nonce_field( 'save', 'ttfmake-builder-nonce' );

		// Get the current sections
		global $ttfmake_sections;
		$ttfmake_sections = get_post_meta( $post_local->ID, '_ttfmake-sections', true );
		$ttfmake_sections = ( is_array( $ttfmake_sections ) ) ? $ttfmake_sections : array();

		// Load the boilerplate templates
		get_template_part( 'inc/builder/core/templates/menu' );
		get_template_part( 'inc/builder/core/templates/stage', 'header' );

		$section_data        = ttfmake_get_section_data( $post_local->ID );
		$registered_sections = ttfmake_get_sections();

		// Print the current sections
		foreach ( $section_data as $section ) {
			if ( isset( $registered_sections[ $section['section-type'] ]['display_template'] ) ) {
				// Print the saved section
				$this->load_section( $registered_sections[ $section['section-type'] ], $section );
			}
		}

		get_template_part( 'inc/builder/core/templates/stage', 'footer' );

		// Add the sort input
		$section_order = get_post_meta( $post_local->ID, '_ttfmake-section-ids', true );
		$section_order = ( ! empty( $section_order ) ) ? implode( ',', $section_order ) : '';
		echo '<input type="hidden" value="' . esc_attr( $section_order ) . '" name="ttfmake-section-order" id="ttfmake-section-order" />';
	}

	/**
	 * Enqueue the JS and CSS for the admin.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $hook_suffix    The suffix for the screen.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		// Only load resources if they are needed on the current page
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) || ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}

		// Enqueue the CSS
		wp_enqueue_style(
			'ttfmake-builder',
			get_template_directory_uri() . '/inc/builder/core/css/builder.css',
			array(),
			TTFMAKE_VERSION
		);

		wp_enqueue_style( 'wp-color-picker' );

		// Dependencies regardless of min/full scripts
		$dependencies = array(
			'wplink',
			'utils',
			'wp-color-picker',
			'jquery-effects-core',
			'jquery-ui-sortable',
			'backbone',
		);

		wp_register_script(
			'ttfmake-builder/js/tinymce.js',
			get_template_directory_uri() . '/inc/builder/core/js/tinymce.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-builder/js/models/section.js',
			get_template_directory_uri() . '/inc/builder/core/js/models/section.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-builder/js/collections/sections.js',
			get_template_directory_uri() . '/inc/builder/core/js/collections/sections.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-builder/js/views/menu.js',
			get_template_directory_uri() . '/inc/builder/core/js/views/menu.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-builder/js/views/section.js',
			get_template_directory_uri() . '/inc/builder/core/js/views/section.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		/**
		 * Filter the dependencies for the many builder JS.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $dependencies    The list of dependencies.
		 */
		$dependencies = apply_filters(
			'ttfmake_builder_js_dependencies',
			array_merge(
				$dependencies,
				array(
					'ttfmake-builder/js/tinymce.js',
					'ttfmake-builder/js/models/section.js',
					'ttfmake-builder/js/collections/sections.js',
					'ttfmake-builder/js/views/menu.js',
					'ttfmake-builder/js/views/section.js',
				)
			)
		);

		wp_enqueue_script(
			'ttfmake-builder',
			get_template_directory_uri() . '/inc/builder/core/js/app.js',
			$dependencies,
			TTFMAKE_VERSION,
			true
		);

		// Add data needed for the JS
		$data = array(
			'pageID' => get_the_ID(),
			'confirmString' => esc_html__( 'Delete the section?', 'make' ),
		);

		wp_localize_script(
			'ttfmake-builder',
			'ttfmakeBuilderData',
			$data
		);
	}

	/**
	 * Print additional, dynamic CSS for the builder interface.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function admin_print_styles() {
		global $pagenow;

		// Do not complete the function if the product template is in use (i.e., the builder needs to be shown)
		if ( ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}
	?>
		<style type="text/css">
			<?php if ( 'post-new.php' === $pagenow || ( 'post.php' === $pagenow && ttfmake_is_builder_page() ) ) : ?>
			#postdivrich {
				display: none;
			}
			<?php else : ?>
			#ttfmake-builder {
				display: none;
			}
			.ttfmake-duplicator {
				display: none;
			}
			<?php endif; ?>

			<?php foreach ( ttfmake_get_sections() as $key => $section ) : ?>
			#ttfmake-menu-list-item-link-<?php echo esc_attr( $section['id'] ); ?> .ttfmake-menu-list-item-link-icon-wrapper {
				background-image: url(<?php echo addcslashes( esc_url_raw( $section['icon'] ), '"' ); ?>);
			}
			<?php endforeach; ?>
		</style>
	<?php
	}

	/**
	 * Add a class to indicate the current template being used.
	 *
	 * @since  1.0.4.
	 *
	 * @param  array    $classes    The current classes.
	 * @return array                The modified classes.
	 */
	public function admin_body_class( $classes ) {
		global $pagenow;

		// Do not complete the function if the product template is in use (i.e., the builder needs to be shown)
		if ( ttfmake_post_type_supports_builder( get_post_type() ) ) {
			if ( 'post-new.php' === $pagenow || ( 'post.php' === $pagenow && ttfmake_is_builder_page() ) ) {
				$classes .= ' ttfmake-builder-active';
			} else {
				$classes .= ' ttfmake-default-active';
			}
		}

		return $classes;
	}

	/**
	 * Reusable component for adding an image uploader.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $section_name    Name of the current section.
	 * @param  int       $image_id        ID of the current image.
	 * @param  array     $messages        Message to show.
	 * @return void
	 */
	public function add_uploader( $section_name, $image_id = 0, $messages = array() ) {
		$image        = ttfmake_get_image( $image_id, 'large' );
		$add_state    = ( '' === $image ) ? 'ttfmake-show' : 'ttfmake-hide';
		$remove_state = ( '' === $image ) ? 'ttfmake-hide' : 'ttfmake-show';

		// Set default messages. Note that the theme textdomain is not used in some cases
		// because the strings are core i18ns
		$messages['add']    = ( empty( $messages['add'] ) ) ? __( 'Set featured image', 'make' ) : $messages['add'];
		$messages['remove'] = ( empty( $messages['remove'] ) ) ? __( 'Remove featured image', 'make' ) : $messages['remove'];
		$messages['title']  = ( empty( $messages['title'] ) ) ? __( 'Featured Image', 'make' ) : $messages['title'];
		$messages['button'] = ( empty( $messages['button'] ) ) ? __( 'Use as Featured Image', 'make' ) : $messages['button'];
		?>
		<div class="ttfmake-uploader">
			<div class="ttfmake-media-uploader-placeholder ttfmake-media-uploader-add">
				<?php if ( '' !== $image ) : ?>
					<?php echo $image; ?>
				<?php endif; ?>
			</div>
			<div class="ttfmake-media-link-wrap">
				<a href="#" class="ttfmake-media-uploader-add ttfmake-media-uploader-set-link <?php echo $add_state; ?>" data-title="<?php echo esc_attr( $messages['title'] ); ?>" data-button-text="<?php echo esc_attr( $messages['button'] ); ?>">
					<?php echo $messages['add']; ?>
				</a>
				<a href="#" class="ttfmake-media-uploader-remove <?php echo $remove_state; ?>">
					<?php echo $messages['remove']; ?>
				</a>
			</div>
			<input type="hidden" name="<?php echo esc_attr( $section_name ); ?>[image-id]" value="<?php echo ttfmake_sanitize_image_id( $image_id ); ?>" class="ttfmake-media-uploader-value" />
		</div>
	<?php
	}

	/**
	 * Load a section template with an available data payload for use in the template.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $section     The section data.
	 * @param  array     $data        The data payload to inject into the section.
	 * @return void
	 */
	public function load_section( $section, $data = array() ) {
		if ( ! isset( $section['id'] ) ) {
			return;
		}

		// Globalize the data to provide access within the template
		global $ttfmake_section_data;
		$ttfmake_section_data = array(
			'data'    => $data,
			'section' => $section,
		);

		// Include the template
		ttfmake_load_section_template(
			$section['builder_template'],
			$section['path']
		);

		// Destroy the variable as a good citizen does
		unset( $GLOBALS['ttfmake_section_data'] );
	}

	/**
	 * Print out the JS section templates
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function print_templates() {
		global $hook_suffix, $typenow, $ttfmake_is_js_template;
		$ttfmake_is_js_template = true;

		// Only show when adding/editing pages
		if ( ! ttfmake_post_type_supports_builder( $typenow ) || ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
			return;
		}

		// Print the templates
		foreach ( ttfmake_get_sections() as $key => $section ) : ?>
			<script type="text/html" id="tmpl-ttfmake-<?php echo esc_attr( $section['id'] ); ?>">
			<?php
			ob_start();
			$this->load_section( $section, array() );
			$html = ob_get_clean();

			$html = str_replace(
				array(
					'temp',
				),
				array(
					'{{{ id }}}',
				),
				$html
			);

			echo $html;
			?>
		</script>
		<?php endforeach;

		unset( $GLOBALS['ttfmake_is_js_template'] );
	}

	/**
	 * Wrapper function to produce a WP Editor with special defaults.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $content     The content to display in the editor.
	 * @param  string    $name        Name of the editor.
	 * @param  array     $settings    Setting to send to the editor.
	 * @return void
	 */
	public function wp_editor( $content, $name, $settings = array() ) {
		$settings = wp_parse_args( $settings, array(
			'tinymce'   => array(
				'toolbar1' => 'bold,italic,link,unlink',
				'toolbar2' => '',
				'toolbar3' => '',
				'toolbar4' => '',
			),
			'quicktags' => array(
				'buttons' => 'strong,em,link',
			),
			'editor_height' => 150,
		) );

		// Remove the default media buttons action and replace it with the custom one
		remove_action( 'media_buttons', 'media_buttons' );
		add_action( 'media_buttons', array( $this, 'media_buttons' ) );

		// Render the editor
		wp_editor( $content, $name, $settings );

		// Reinstate the original media buttons function
		remove_action( 'media_buttons', array( $this, 'media_buttons' ) );
		add_action( 'media_buttons', 'media_buttons' );
	}

	/**
	 * Add the media buttons to the text editor.
	 *
	 * This is a copy and modification of the core "media_buttons" function. In order to make the media editor work
	 * better for smaller width screens, we need to wrap the button text in a span tag. By doing so, we can hide the
	 * text in some situations.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $editor_id    The value of the current editor ID.
	 * @return void
	 */
	public function media_buttons( $editor_id = 'content' ) {
		$post = get_post();
		if ( ! $post && ! empty( $GLOBALS['post_ID'] ) ) {
			$post = $GLOBALS['post_ID'];
		}

		wp_enqueue_media( array(
			'post' => $post,
		) );

		$img = '<span class="wp-media-buttons-icon"></span>';

		// Note that the theme textdomain is not used for Add Media in order to use the core l10n
		echo '<a href="#" id="insert-media-button" class="button insert-media add_media" data-editor="' . esc_attr( $editor_id ) . '" title="' . esc_attr__( 'Add Media', 'make' ) . '">' . $img . ' <span class="ttfmake-media-button-text">' . __( 'Add Media', 'make' ) . '</span></a>';
	}

	/**
	 * Append the editor styles to the section editors.
	 *
	 * Unfortunately, the `wp_editor()` function does not support a "content_css" argument. As a result, the stylesheet
	 * for the "content_css" parameter needs to be added via a filter.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $mce_init     The array of tinyMCE settings.
	 * @param  string    $editor_id    The ID for the current editor.
	 * @return array                   The modified settings.
	 */
	public function tiny_mce_before_init( $mce_init, $editor_id ) {
		// Only add stylesheet to a section editor
		if ( false === strpos( $editor_id, 'make' ) ) {
			return $mce_init;
		}

		// Editor styles
		$editor_styles = array();
		$google_request = ttfmake_get_google_font_uri();

		if ( '' !== $google_request ) {
			$editor_styles[] = $google_request;
		}

		$editor_styles[] = get_template_directory_uri() . '/css/font-awesome.css';
		$editor_styles[] = get_template_directory_uri() . '/css/editor-style.css';

		// Append in the customizer styles if available
		if ( function_exists( 'ttfmake_get_css' ) && ttfmake_get_css()->build() ) {
			$editor_styles[] = add_query_arg( 'action', 'ttfmake-css', admin_url( 'admin-ajax.php' ) );
		}

		// Create string of CSS files
		$content_css = implode( ',', $editor_styles );

		// If there is already a stylesheet being added, append and do not override
		if ( isset( $mce_init['content_css'] ) ) {
			$mce_init['content_css'] .= ',' . $content_css;
		} else {
			$mce_init['content_css'] = $content_css;
		}

		return $mce_init;
	}

	/**
	 * Denote the default editor for the user.
	 *
	 * Note that it would usually be ideal to expose this via a JS variable using wp_localize_script; however, it is
	 * being printed here in order to guarantee that nothing changes this value before it would otherwise be printed.
	 * The "after_wp_tiny_mce" action appears to be the most reliable place to print this variable.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $settings   TinyMCE settings.
	 * @return void
	 */
	public function after_wp_tiny_mce( $settings ) {
		?>
		<script type="text/javascript">
			var ttfmakeMCE = '<?php echo esc_js( wp_default_editor() ); ?>';
		</script>
	<?php
	}

	/**
	 * Retrieve all of the data for the sections.
	 *
	 * Note that in 1.2.0, this function was changed to call the global function. This global function was added to
	 * provide easier reuse of the function. In order to maintain backwards compatibility, this function is left in
	 * place.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $post_id    The post to retrieve the data from.
	 * @return array                 The combined data.
	 */
	public function get_section_data( $post_id ) {
		return ttfmake_get_section_data( $post_id );
	}

	/**
	 * Convert an array with array keys that map to a multidimensional array to the array.
	 *
	 * Note that in 1.2.0, this function was changed to call the global function. This global function was added to
	 * provide easier reuse of the function. In order to maintain backwards compatibility, this function is left in
	 * place.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $arr    The array to convert.
	 * @return array            The converted array.
	 */
	public function create_array_from_meta_keys( $arr ) {
		return ttfmake_create_array_from_meta_keys( $arr );
	}

	/**
	 * Display information about duplicating posts.
	 *
	 * @since  1.1.0.
	 *
	 * @return void
	 */
	public function post_submitbox_misc_actions() {
	?>
		<div class="misc-pub-section ttfmake-duplicator">
			<p style="font-style:italic;margin:0 0 7px 3px;">
				<?php
				printf(
					__( 'Duplicate this page with %s.', 'make' ),
					sprintf(
						'<a href="%1$s" target="_blank">%2$s</a>',
						esc_url( ttfmake_get_plus_link( 'duplicator' ) ),
						'Make Plus'
					)
				);
				?>
			</p>
			<div class="clear"></div>
		</div>
	<?php
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_builder_base' ) ) :
/**
 * Instantiate or return the one TTFMAKE_Builder_Base instance.
 *
 * @since  1.0.0.
 *
 * @return TTFMAKE_Builder_Base
 */
function ttfmake_get_builder_base() {
	return TTFMAKE_Builder_Base::instance();
}
endif;

// Add the base immediately
if ( is_admin() ) {
	ttfmake_get_builder_base();
}

if ( ! function_exists( 'ttfmake_get_post_types_supporting_builder' ) ) :
/**
 * Get all post types that support the Make builder.
 *
 * @since  1.2.0.
 *
 * @return array    Array of all post types that support the builder.
 */
function ttfmake_get_post_types_supporting_builder() {
	$post_types_supporting_builder = array();

	// Inspect each post type for builder support
	foreach ( get_post_types() as $name => $data ) {
		if ( post_type_supports( $name, 'make-builder' ) ) {
			$post_types_supporting_builder[] = $name;
		}
	}

	return $post_types_supporting_builder;
}
endif;

if ( ! function_exists( 'ttfmake_will_be_builder_page' ) ) :
/**
 * Determines if a page in the process of being saved will use the builder template.
 *
 * @since  1.2.0.
 *
 * @return bool    True if the builder template will be used; false if it will not.
 */
function ttfmake_will_be_builder_page() {
	$template    = isset( $_POST['page_template'] ) ? $_POST['page_template'] : '';
	$use_builder = isset( $_POST['use-builder'] ) ? (int) isset( $_POST['use-builder'] ) : 0;

	/**
	 * Allow developers to dynamically change the builder page status.
	 *
	 * @since 1.2.3.
	 *
	 * @param bool      $will_be_builder_page    Whether or not this page will be a builder page.
	 * @param string    $template                The template name.
	 * @param int       $use_builder             Value of the "use-builder" input. 1 === use builder. 0 === do not use builder.
	 */
	return apply_filters( 'make_will_be_builder_page', ( 'template-builder.php' === $template || 1 === $use_builder ), $template, $use_builder );
}
endif;

if ( ! function_exists( 'ttfmake_load_section_header' ) ) :
/**
 * Load a consistent header for sections.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function ttfmake_load_section_header() {
	global $ttfmake_section_data;
	get_template_part( 'inc/builder/core/templates/section', 'header' );

	/**
	 * Allow for script execution in the header of a builder section.
	 *
	 * This action is a variable action that allows a developer to hook into specific section types (e.g., 'text'). Do
	 * not confuse "id" in this context as the individual section id (e.g., 14092814910).
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $ttfmake_section_data    The array of data for the section.
	 */
	do_action( 'make_section_' . $ttfmake_section_data['section']['id'] . '_before', $ttfmake_section_data );

	// Backcompat
	do_action( 'ttfmake_section_' . $ttfmake_section_data['section']['id'] . '_before', $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_load_section_footer' ) ) :
/**
 * Load a consistent footer for sections.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function ttfmake_load_section_footer() {
	global $ttfmake_section_data;
	get_template_part( 'inc/builder/core/templates/section', 'footer' );

	/**
	 * Allow for script execution in the footer of a builder section.
	 *
	 * This action is a variable action that allows a developer to hook into specific section types (e.g., 'text'). Do
	 * not confuse "id" in this context as the individual section id (e.g., 14092814910).
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $ttfmake_section_data    The array of data for the section.
	 */
	do_action( 'make_section_' . $ttfmake_section_data['section']['id'] . '_after', $ttfmake_section_data );

	// Backcompat
	do_action( 'ttfmake_section_' . $ttfmake_section_data['section']['id'] . '_after', $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_load_section_template' ) ) :
/**
 * Load a section front- or back-end section template. Searches for child theme versions
 * first, then parent themes, then plugins.
 *
 * @since  1.0.4.
 *
 * @param  string    $slug    The relative path and filename (w/out suffix) required to substitute the template in a child theme.
 * @param  string    $path    An optional path extension to point to the template in the parent theme or a plugin.
 * @return string             The template filename if one is located.
 */
function ttfmake_load_section_template( $slug, $path ) {
	$templates = array(
		$slug . '.php',
		trailingslashit( $path ) . $slug . '.php',
	);

	/**
	 * Filter the templates to try and load.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $templates    The list of template to try and load.
	 * @param string   $slug         The template slug.
	 * @param string   $path         The path to the template.
	 */
	$templates = apply_filters( 'make_load_section_template', $templates, $slug, $path );
	$located = locate_template( $templates, true, false );

	if ( '' === $located ) {
		if ( isset( $templates[1] ) && file_exists( $templates[1] ) ) {
			require $templates[1];
			$located = $templates[1];
		}
	}

	return $located;
}
endif;

if ( ! function_exists( 'ttfmake_get_wp_editor_id' ) ) :
/**
 * Generate the ID for a WP editor based on an existing or future section number.
 *
 * @since  1.0.0.
 *
 * @param  array     $data              The data for the section.
 * @param  array     $is_js_template    Whether a JS template is being printed or not.
 * @return string                       The editor ID.
 */
function ttfmake_get_wp_editor_id( $data, $is_js_template ) {
	$id_base = 'ttfmakeeditor' . $data['section']['id'];

	if ( $is_js_template ) {
		$id = $id_base . 'temp';
	} else {
		$id = $id_base . $data['data']['id'];
	}

	/**
	 * Alter the wp_editor ID.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $id                The ID for the editor.
	 * @param array     $data              The section data.
	 * @param bool      $is_js_template    Whether or not this is in the context of a JS template.
	 */
	return apply_filters( 'make_get_wp_editor_id', $id, $data, $is_js_template );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_name' ) ) :
/**
 * Generate the name of a section.
 *
 * @since  1.0.0.
 *
 * @param  array     $data              The data for the section.
 * @param  array     $is_js_template    Whether a JS template is being printed or not.
 * @return string                       The name of the section.
 */
function ttfmake_get_section_name( $data, $is_js_template ) {
	$name = 'ttfmake-section';

	if ( $is_js_template ) {
		$name .= '[{{{ id }}}]';
	} else {
		$name .= '[' . $data['data']['id'] . ']';
	}

	/**
	 * Alter section name.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $name              The name of the section.
	 * @param array     $data              The section data.
	 * @param bool      $is_js_template    Whether or not this is in the context of a JS template.
	 */
	return apply_filters( 'make_get_section_name', $name, $data, $is_js_template );
}
endif;

if ( ! function_exists( 'ttfmake_sanitize_text' ) ) :
/**
 * Allow only the allowedtags array in a string.
 *
 * @since  1.0.0.
 *
 * @param  string    $string    The unsanitized string.
 * @return string               The sanitized string.
 */
function ttfmake_sanitize_text( $string ) {
	global $allowedtags;
	return wp_kses( $string, $allowedtags );
}
endif;

if ( ! function_exists( 'ttfmake_get_image' ) ) :
/**
 * Get an image to display in page builder backend or front end template.
 *
 * This function allows image IDs defined with a negative number to surface placeholder images. This allows templates to
 * approximate real content without needing to add images to the user's media library.
 *
 * @since  1.0.4.
 *
 * @param  int       $image_id    The attachment ID. Dimension value IDs represent placeholders (100x150).
 * @param  string    $size        The image size.
 * @return string                 HTML for the image. Empty string if image cannot be produced.
 */
function ttfmake_get_image( $image_id, $size ) {
	$return = '';

	if ( false === strpos( $image_id, 'x' ) ) {
		$return = wp_get_attachment_image( $image_id, $size );
	} else {
		$image = ttfmake_get_placeholder_image( $image_id );

		if ( ! empty( $image ) && isset( $image['src'] ) && isset( $image['alt'] ) && isset( $image['class'] ) && isset( $image['height'] ) && isset( $image['width'] ) ) {
			$return = '<img src="' . $image['src'] . '" alt="' . $image['alt'] . '" class="' . $image['class'] . '" height="' . $image['height'] . '" width="' . $image['width'] . '" />';
		}
	}

	/**
	 * Filter the image HTML.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $return      The image HTML.
	 * @param int       $image_id    The ID for the image.
	 * @param bool      $size        The requested image size.
	 */
	return apply_filters( 'make_get_image', $return, $image_id, $size );
}
endif;

if ( ! function_exists( 'ttfmake_get_image_src' ) ) :
/**
 * Get an image's src.
 *
 * @since  1.0.4.
 *
 * @param  int       $image_id    The attachment ID. Dimension value IDs represent placeholders (100x150).
 * @param  string    $size        The image size.
 * @return string                 URL for the image.
 */
function ttfmake_get_image_src( $image_id, $size ) {
	$src = '';

	if ( false === strpos( $image_id, 'x' ) ) {
		$image = wp_get_attachment_image_src( $image_id, $size );

		if ( false !== $image && isset( $image[0] ) ) {
			$src = $image;
		}
	} else {
		$image = ttfmake_get_placeholder_image( $image_id );

		if ( isset( $image['src'] ) ) {
			$wp_src = array(
				0 => $image['src'],
				1 => $image['width'],
				2 => $image['height'],
			);
			$src = array_merge( $image, $wp_src );
		}
	}

	/**
	 * Filter the image source attributes.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $src         The image source attributes.
	 * @param int       $image_id    The ID for the image.
	 * @param bool      $size        The requested image size.
	 */
	return apply_filters( 'make_get_image_src', $src, $image_id, $size );
}
endif;

global $ttfmake_placeholder_images;

if ( ! function_exists( 'ttfmake_get_placeholder_image' ) ) :
/**
 * Gets the specified placeholder image.
 *
 * @since  1.0.4.
 *
 * @param  int      $image_id    Image ID. Should be a dimension value (100x150).
 * @return array                 The image data, including 'src', 'alt', 'class', 'height', and 'width'.
 */
function ttfmake_get_placeholder_image( $image_id ) {
	global $ttfmake_placeholder_images;
	$return = array();

	if ( isset( $ttfmake_placeholder_images[ $image_id ] ) ) {
		$return = $ttfmake_placeholder_images[ $image_id ];
	}

	/**
	 * Filter the image source attributes.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $return                        The image source attributes.
	 * @param int       $image_id                      The ID for the image.
	 * @param bool      $ttfmake_placeholder_images    The list of placeholder images.
	 */
	return apply_filters( 'make_get_placeholder_image', $return, $image_id, $ttfmake_placeholder_images );
}
endif;

if ( ! function_exists( 'ttfmake_register_placeholder_image' ) ) :
/**
 * Add a new placeholder image.
 *
 * @since  1.0.4.
 *
 * @param  int      $id      The ID for the image. Should be a dimension value (100x150).
 * @param  array    $data    The image data, including 'src', 'alt', 'class', 'height', and 'width'.
 * @return void
 */
function ttfmake_register_placeholder_image( $id, $data ) {
	global $ttfmake_placeholder_images;
	$ttfmake_placeholder_images[ $id ] = $data;
}
endif;

/**
 * Add information about Quick Start.
 *
 * @since  1.0.6.
 *
 * @return void
 */
function ttfmake_plus_quick_start() {
	if ( false !== ttfmake_is_plus() || 'page' !== get_post_type() ) {
		return;
	}

	$section_ids        = get_post_meta( get_the_ID(), '_ttfmake-section-ids', true );
	$additional_classes = ( ! empty( $section_ids ) ) ? ' ttfmp-import-message-hide' : '';
	?>
	<div id="message" class="error below-h2 ttfmp-import-message<?php echo esc_attr( $additional_classes ); ?>">
		<p>
			<strong><?php _e( 'Want some ideas?', 'make' ); ?></strong><br />
			<?php
			printf(
				__( '%s and get a quick start with pre-made designer builder templates.', 'make' ),
				sprintf(
					'<a href="%1$s" target="_blank">%2$s</a>',
					esc_url( ttfmake_get_plus_link( 'quick-start' ) ),
					sprintf(
						__( 'Upgrade to %s', 'make' ),
						'Make Plus'
					)
				)
			);
			?>
		</p>
	</div>
<?php
}

add_action( 'edit_form_after_title', 'ttfmake_plus_quick_start' );
