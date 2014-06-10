<?php
/**
 * @package Make
 */

if ( ! class_exists( 'TTFMAKE_Section_Definitions' ) ) :
/**
 * Collector for builder sections.
 *
 * @since 1.0.0.
 *
 * Class TTFMAKE_Section_Definitions
 */
class TTFMAKE_Section_Definitions {
	/**
	 * The one instance of TTFMAKE_Section_Definitions.
	 *
	 * @since 1.0.0.
	 *
	 * @var   TTFMAKE_Section_Definitions
	 */
	private static $instance;

	/**
	 * Instantiate or return the one TTFMAKE_Section_Definitions instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Section_Definitions
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register the sections.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Section_Definitions
	 */
	public function __construct() {
		// Register all of the sections via the section API
		$this->register_text_section();
		$this->register_banner_section();
		$this->register_gallery_section();
		$this->register_blank_section();

		// Add the section JS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Add additional templating
		add_action( 'admin_footer', array( $this, 'print_templates' ) );
	}

	/**
	 * Register the text section.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function register_text_section() {
		ttfmake_add_section(
			'text',
			_x( 'Text', 'section name', 'make' ),
			get_template_directory_uri() . '/inc/builder/sections/css/images/text.png',
			__( 'Create rearrangeable columns of content and images.', 'make' ),
			array( $this, 'save_text' ),
			'sections/builder-templates/text',
			'sections/front-end-templates/text',
			100,
			'inc/builder/'
		);
	}

	/**
	 * Save the data for the text section.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data    The data from the $_POST array for the section.
	 * @return array             The cleaned data.
	 */
	public function save_text( $data ) {
		$clean_data = array();

		if ( isset( $data['columns-number'] ) ) {
			if ( in_array( $data['columns-number'], range( 1, 4 ) ) ) {
				$clean_data['columns-number'] = $data['columns-number'];
			}
		}

		$clean_data['title'] = $clean_data['label'] = ( isset( $data['title'] ) ) ? apply_filters( 'title_save_pre', $data['title'] ) : '';

		if ( isset( $data['columns-order'] ) ) {
			$clean_data['columns-order'] = array_map( array( 'TTFMAKE_Builder_Save', 'clean_section_id' ), explode( ',', $data['columns-order'] ) );
		}

		if ( isset( $data['columns'] ) && is_array( $data['columns'] ) ) {
			foreach ( $data['columns'] as $id => $item ) {
				if ( isset( $item['title'] ) ) {
					$clean_data['columns'][ $id ]['title'] = apply_filters( 'title_save_pre', $item['title'] );
				}

				if ( isset( $item['image-link'] ) ) {
					$clean_data['columns'][ $id ]['image-link'] = esc_url_raw( $item['image-link'] );
				}

				if ( isset( $item['image-id'] ) ) {
					$clean_data['columns'][ $id ]['image-id'] = ttfmake_sanitize_image_id( $item['image-id'] );
				}

				if ( isset( $item['content'] ) ) {
					$clean_data['columns'][ $id ]['content'] = sanitize_post_field( 'post_content', $item['content'], ( get_post() ) ? get_the_ID() : 0, 'db' );
				}
			}
		}

		return $clean_data;
	}

	/**
	 * Register the blank section.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function register_blank_section() {
		ttfmake_add_section(
			'blank',
			_x( 'Blank', 'section name', 'make' ),
			get_template_directory_uri() . '/inc/builder/sections/css/images/blank.png',
			__( 'A blank canvas for standard content or HTML code.', 'make' ),
			array( $this, 'save_blank' ),
			'sections/builder-templates/blank',
			'sections/front-end-templates/blank',
			200,
			'inc/builder/'
		);
	}

	/**
	 * Save the data for the blank section.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data    The data from the $_POST array for the section.
	 * @return array             The cleaned data.
	 */
	public function save_blank( $data ) {
		$clean_data = array();

		if ( isset( $data['title'] ) ) {
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
		}

		if ( isset( $data['content'] ) ) {
			$clean_data['content'] = sanitize_post_field( 'post_content', $data['content'], ( get_post() ) ? get_the_ID() : 0, 'db' );
		}

		return $clean_data;
	}

	/**
	 * Register the banner section.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function register_banner_section() {
		ttfmake_add_section(
			'banner',
			_x( 'Banner', 'section name', 'make' ),
			get_template_directory_uri() . '/inc/builder/sections/css/images/banner.png',
			__( 'Display multiple types of content in a banner or a slider.', 'make' ),
			array( $this, 'save_banner' ),
			'sections/builder-templates/banner',
			'sections/front-end-templates/banner',
			300,
			'inc/builder/'
		);
	}

	/**
	 * Save the data for the banner section.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data    The data from the $_POST array for the section.
	 * @return array             The cleaned data.
	 */
	public function save_banner( $data ) {
		$clean_data = array();

		$clean_data['title']       = $clean_data['label'] = ( isset( $data['title'] ) ) ? apply_filters( 'title_save_pre', $data['title'] ) : '';
		$clean_data['hide-arrows'] = ( isset( $data['hide-arrows'] ) && 1 === (int) $data['hide-arrows'] ) ? 1 : 0;
		$clean_data['hide-dots']   = ( isset( $data['hide-dots'] ) && 1 === (int) $data['hide-dots'] ) ? 1 : 0;
		$clean_data['autoplay']    = ( isset( $data['autoplay'] ) && 1 === (int) $data['autoplay'] ) ? 1 : 0;

		if ( isset( $data['transition'] ) && in_array( $data['transition'], array( 'fade', 'scrollHorz', 'none' ) ) ) {
			$clean_data['transition'] = $data['transition'];
		}

		if ( isset( $data['delay'] ) ) {
			$clean_data['delay'] = absint( $data['delay'] );
		}

		if ( isset( $data['height'] ) ) {
			$clean_data['height'] = absint( $data['height'] );
		}

		if ( isset( $data['banner-slide-order'] ) ) {
			$clean_data['banner-slide-order'] = array_map( array( 'TTFMAKE_Builder_Save', 'clean_section_id' ), explode( ',', $data['banner-slide-order'] ) );
		}

		if ( isset( $data['banner-slides'] ) && is_array( $data['banner-slides'] ) ) {
			foreach ( $data['banner-slides'] as $id => $slide ) {

				if ( isset( $slide['content'] ) ) {
					$clean_data['banner-slides'][ $id ]['content'] = sanitize_post_field( 'post_content', $slide['content'], ( get_post() ) ? get_the_ID() : 0, 'db' );
				}

				if ( isset( $slide['background-color'] ) ) {
					$clean_data['banner-slides'][ $id ]['background-color'] = maybe_hash_hex_color( $slide['background-color'] );
				}

				$clean_data['banner-slides'][ $id ]['darken'] = ( isset( $slide['darken'] ) && 1 === (int) $slide['darken'] ) ? 1 : 0;

				if ( isset( $slide['image-id'] ) ) {
					$clean_data['banner-slides'][ $id ]['image-id'] = ttfmake_sanitize_image_id( $slide['image-id'] );
				}

				$clean_data['banner-slides'][ $id ]['alignment'] = ( isset( $slide['alignment'] ) && in_array( $slide['alignment'], array( 'none', 'left', 'right' ) ) ) ? $slide['alignment'] : 'none';

				if ( isset( $slide['state'] ) ) {
					$clean_data['banner-slides'][ $id ]['state'] = ( in_array( $slide['state'], array( 'open', 'closed' ) ) ) ? $slide['state'] : 'open';
				}
			}
		}

		return $clean_data;
	}

	/**
	 * Register the gallery section.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function register_gallery_section() {
		ttfmake_add_section(
			'gallery',
			_x( 'Gallery', 'section name', 'make' ),
			get_template_directory_uri() . '/inc/builder/sections/css/images/gallery.png',
			__( 'Display your images in various grid combinations.', 'make' ),
			array( $this, 'save_gallery' ),
			'sections/builder-templates/gallery',
			'sections/front-end-templates/gallery',
			400,
			'inc/builder/'
		);
	}

	/**
	 * Save the data for the gallery section.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data    The data from the $_POST array for the section.
	 * @return array             The cleaned data.
	 */
	public function save_gallery( $data ) {
		$clean_data = array();

		if ( isset( $data['columns'] ) ) {
			if ( in_array( $data['columns'], range( 1, 4 ) ) ) {
				$clean_data['columns'] = $data['columns'];
			}
		}

		if ( isset( $data['caption-color'] ) ) {
			if ( in_array( $data['caption-color'], array( 'light', 'dark' ) ) ) {
				$clean_data['caption-color'] = $data['caption-color'];
			}
		}

		if ( isset( $data['captions'] ) ) {
			if ( in_array( $data['captions'], array( 'none', 'overlay', 'reveal' ) ) ) {
				$clean_data['captions'] = $data['captions'];
			}
		}

		if ( isset( $data['aspect'] ) ) {
			if ( in_array( $data['aspect'], array( 'none', 'landscape', 'portrait', 'square' ) ) ) {
				$clean_data['aspect'] = $data['aspect'];
			}
		}

		if ( isset( $data['background-image']['image-id'] ) ) {
			$clean_data['background-image'] = ttfmake_sanitize_image_id( $data['background-image']['image-id'] );
		}

		if ( isset( $data['title'] ) ) {
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
		}

		if ( isset( $data['darken'] ) ) {
			$clean_data['darken'] = 1;
		} else {
			$clean_data['darken'] = 0;
		}

		if ( isset( $data['background-color'] ) ) {
			$clean_data['background-color'] = maybe_hash_hex_color( $data['background-color'] );
		}

		if ( isset( $data['background-style'] ) ) {
			if ( in_array( $data['background-style'], array( 'tile', 'cover' ) ) ) {
				$clean_data['background-style'] = $data['background-style'];
			}
		}

		if ( isset( $data['gallery-item-order'] ) ) {
			$clean_data['gallery-item-order'] = array_map( array( 'TTFMAKE_Builder_Save', 'clean_section_id' ), explode( ',', $data['gallery-item-order'] ) );
		}

		if ( isset( $data['gallery-items'] ) && is_array( $data['gallery-items'] ) ) {
			foreach ( $data['gallery-items'] as $id => $item ) {
				if ( isset( $item['title'] ) ) {
					$clean_data['gallery-items'][ $id ]['title'] = apply_filters( 'title_save_pre', $item['title'] );
				}

				if ( isset( $item['link'] ) ) {
					$clean_data['gallery-items'][ $id ]['link'] = esc_url_raw( $item['link'] );
				}

				if ( isset( $item['description'] ) ) {
					$clean_data['gallery-items'][ $id ]['description'] = sanitize_post_field( 'post_content', $item['description'], ( get_post() ) ? get_the_ID() : 0, 'db' );
				}

				if ( isset( $item['image-id'] ) ) {
					$clean_data['gallery-items'][ $id ]['image-id'] = ttfmake_sanitize_image_id( $item['image-id'] );
				}
			}
		}

		return $clean_data;
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
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) || 'page' !== get_post_type() ) {
			return;
		}

		wp_register_script(
			'ttfmake-sections/js/models/gallery-item.js',
			get_template_directory_uri() . '/inc/builder/sections/js/models/gallery-item.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-sections/js/views/gallery-item.js',
			get_template_directory_uri() . '/inc/builder/sections/js/views/gallery-item.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-sections/js/views/gallery.js',
			get_template_directory_uri() . '/inc/builder/sections/js/views/gallery.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-sections/js/views/text.js',
			get_template_directory_uri() . '/inc/builder/sections/js/views/text.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-sections/js/models/banner-slide.js',
			get_template_directory_uri() . '/inc/builder/sections/js/models/banner-slide.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-sections/js/views/banner-slide.js',
			get_template_directory_uri() . '/inc/builder/sections/js/views/banner-slide.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-sections/js/views/banner.js',
			get_template_directory_uri() . '/inc/builder/sections/js/views/banner.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		if ( false === ttfmake_is_plus() ) {
			wp_enqueue_script(
				'ttfmake-sections/js/quick-start.js',
				get_template_directory_uri() . '/inc/builder/sections/js/quick-start.js',
				array(
					'ttfmake-builder',
				),
				TTFMAKE_VERSION,
				true
			);
		}

		// Add additional dependencies to the Builder JS
		add_filter( 'ttfmake_builder_js_dependencies', array( $this, 'add_js_dependencies' ) );

		// Add the section CSS
		wp_enqueue_style(
			'ttfmake-sections/css/sections.css',
			get_template_directory_uri() . '/inc/builder/sections/css/sections.css',
			array(),
			TTFMAKE_VERSION,
			'all'
		);
	}

	/**
	 * Append more JS to the list of JS deps.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $deps    The current deps.
	 * @return array             The modified deps.
	 */
	public function add_js_dependencies( $deps ) {
		if ( ! is_array( $deps ) ) {
			$deps = array();
		}

		return array_merge( $deps, array(
			'ttfmake-sections/js/models/gallery-item.js',
			'ttfmake-sections/js/models/banner-slide.js',
			'ttfmake-sections/js/views/gallery-item.js',
			'ttfmake-sections/js/views/gallery.js',
			'ttfmake-sections/js/views/text.js',
			'ttfmake-sections/js/views/banner-slide.js',
			'ttfmake-sections/js/views/banner.js',
		) );
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
		if ( 'page' !== $typenow || ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) )) {
			return;
		}

		// Define the templates to print
		$templates = array(
			array(
				'id' => 'gallery-item',
				'builder_template' => 'sections/builder-templates/gallery-item',
				'path' => 'inc/builder/',
			),
			array(
				'id' => 'banner-slide',
				'builder_template' => 'sections/builder-templates/banner-slide',
				'path' => 'inc/builder/',
			),
		);

		// Print the templates
		foreach ( $templates as $template ) : ?>
		<script type="text/html" id="tmpl-ttfmake-<?php echo $template['id']; ?>">
			<?php
			ob_start();
			ttfmake_get_builder_base()->load_section( $template, array() );
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
	 * An array of defaults for all the Builder section settings
	 *
	 * @since 1.0.4.
	 *
	 * @return array
	 */
	public function get_section_defaults() {
		$defaults = array(
			// Placeholder
		);

		return apply_filters( 'ttfmake_section_defaults', $defaults );
	}

	/**
	 * Define the choices for section setting dropdowns.
	 *
	 * @since 1.0.4.
	 *
	 * @param  string    $key             The key for the section setting.
	 * @param  string    $section_type    The section type.
 	 * @return array                      The array of choices for the section setting.
	 */
	public function get_choices( $key, $section_type ) {
		$choices = array( 0 );

		$choice_id = "$section_type-$key";

		switch ( $choice_id ) {
			// Placeholder
		}

		return apply_filters( 'ttfmake_section_choices', $choices, $key, $section_type );
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_section_default' ) ) :
/**
 * Return the default value for a particular section setting.
 *
 * @since 1.0.4.
 *
 * @param  string    $key             The key for the section setting.
 * @param  string    $section_type    The section type.
 * @return mixed                      Default value if found; false if not found.
 */
function ttfmake_get_section_default( $key, $section_type ) {
	$defaults = ttfmake_get_section_definitions()->get_section_defaults();
	$id = "$section_type-$key";
	return ( isset( $defaults[ $id ] ) ) ? $defaults[ $id ] : false;
}
endif;

if ( ! function_exists( 'ttfmake_get_section_choices' ) ) :
/**
 * Wrapper function for TTFMAKE_Section_Definitions->get_choices
 *
 * @since 1.0.4.
 *
 * @param  string    $key             The key for the section setting.
 * @param  string    $section_type    The section type.
 * @return array                      The array of choices for the section setting.
 */
function ttfmake_get_section_choices( $key, $section_type ) {
	return ttfmake_get_section_definitions()->get_choices( $key, $section_type );
}
endif;

if ( ! function_exists( 'ttfmake_sanitize_section_choice' ) ) :
/**
 * Sanitize a value from a list of allowed values.
 *
 * @since 1.0.4.
 *
 * @param  string|int $value The current value of the section setting.
 * @param  string        $key             The key for the section setting.
 * @param  string        $section_type    The section type.
 * @return mixed                          The sanitized value.
 */
function ttfmake_sanitize_section_choice( $value, $key, $section_type ) {
	$choices         = ttfmake_get_section_choices( $key, $section_type );
	$allowed_choices = array_keys( $choices );

	if ( ! in_array( $value, $allowed_choices ) ) {
		$value = ttfmake_get_section_default( $key, $section_type );
	}

	return $value;
}
endif;

/**
 * Instantiate or return the one TTFMAKE_Section_Definitions instance.
 *
 * @since  1.0.0.
 *
 * @return TTFMAKE_Section_Definitions
 */
function ttfmake_get_section_definitions() {
	return TTFMAKE_Section_Definitions::instance();
}

// Kick off the section definitions immediately
if ( is_admin() ) {
	ttfmake_get_section_definitions();
}