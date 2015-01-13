<?php
/**
 * Class Spine_Builder_Custom
 */
class Spine_Builder_Custom {

	/**
	 * Add hooks, start up custom builder components.
	 */
	public function __construct() {

		// This is pulled from the Make theme. We should keep it updated as upstream changes are pulled in.
		define( 'TTFMAKE_VERSION', '1.4.8' );

		// Include extra functions from Make that are not part of the builder, but are required.
		include_once( 'builder-custom/extras.php' );

		// Include the actual core builder files from the Make theme.
		if ( is_admin() ) {
			require get_template_directory() . '/inc/builder/core/base.php';
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ),11 );
		add_action( 'admin_init', array( $this, 'remove_extra_make' ), 11 );
		add_action( 'admin_init', array( $this, 'remove_builder_sections' ), 11 );
		add_action( 'admin_init', array( $this, 'add_builder_sections' ), 12 );
		add_action( 'admin_footer', array( $this, 'print_templates' ) );
		add_filter( 'make_insert_post_data_sections', array( $this, 'set_section_meta' ), 10, 1 );
	}

	/**
	 * Enqueue the scripts and styles used with the page builder.
	 */
	public function enqueue_scripts() {
		global $pagenow;

		if ( 'page' === get_current_screen()->id ) {
			wp_enqueue_script( 'ttfmake-admin-edit-page', get_template_directory_uri() . '/inc/builder-custom/js/edit-page.js', array( 'jquery' ), spine_get_script_version(), true );

			wp_enqueue_style( 'make-builder-styles', get_template_directory_uri() . '/inc/builder/sections/css/sections.css', array(), spine_get_script_version() );
			wp_enqueue_style( 'wsuwp-builder-styles', get_template_directory_uri() . '/builder-templates/css/sections.css', array(), spine_get_script_version() );

			wp_enqueue_script( 'wsuwp-builder-banner-slide-model', get_template_directory_uri() . '/builder-templates/js/models/banner-slide.js', array(), spine_get_script_version(), true );
			wp_enqueue_script( 'wsuwp-builder-banner-slide-view', get_template_directory_uri() . '/builder-templates/js/views/banner-slide.js', array(), spine_get_script_version(), true );
			wp_enqueue_script( 'wsuwp-builder-banner-view', get_template_directory_uri() . '/builder-templates/js/views/banner.js', array(), spine_get_script_version(), true );
			wp_enqueue_script( 'wsuwp-builder-columns', get_template_directory_uri() . '/builder-templates/js/columns.js', array(), spine_get_script_version(), true );

			wp_localize_script(
				'ttfmake-admin-edit-page',
				'ttfmakeEditPageData',
				array(
					'pageNow'       => esc_js( $pagenow ),
				)
			);
		}
	}

	/**
	 * Check to see if specific sections are being saved and enqueue necessary front end scripts
	 * and styles if applicable.
	 *
	 * @param array $sections List of sections being saved as content in page builder.
	 *
	 * @return array Same list of sections.
	 */
	public function set_section_meta( $sections ) {
		$section_types = wp_list_pluck( $sections, 'section-type' );

		if ( in_array( 'banner', $section_types ) ) {
			update_post_meta( get_the_ID(), '_has_builder_banner', 1 );
		} else {
			delete_post_meta( get_the_ID(), '_has_builder_banner' );
		}

		return $sections;
	}

	/**
	 * Remove some of the add-on functionality for Make that we are not able to
	 * support in the Spine parent theme.
	 */
	public function remove_extra_make() {
		remove_action( 'edit_form_after_title', 'ttfmake_plus_quick_start' );
		remove_action( 'post_submitbox_misc_actions', array( ttfmake_get_builder_base(), 'post_submitbox_misc_actions' ) );
		remove_action( 'tiny_mce_before_init', array( ttfmake_get_builder_base(), 'tiny_mce_before_init' ), 15 );
	}

	/**
	 * Remove sections that were previously defined in the upstream Make project.
	 */
	public function remove_builder_sections() {
		ttfmake_remove_section( 'text' );
		ttfmake_remove_section( 'gallery' );
		ttfmake_remove_section( 'banner' );
		ttfmake_remove_section( 'blank' );
	}

	/**
	 * Provide a set of default options to add to each of our section types.
	 *
	 * @return array
	 */
	private function get_default_section_args() {
		$args = array(
			200 => array(
				'type'    => 'text',
				'name'    => 'section-classes',
				'class'   => 'ttfmake-section-classes',
				'label'   => 'Section Classes',
				'default' => 'gutter pad-top',
			),
			300 => array(
				'type'    => 'text',
				'name'    => 'section-wrapper',
				'class'   => 'ttfmake-section-wrapper',
				'label'   => 'Section Wrapper Classes',
			)
		);
		return $args;
	}

	/**
	 * Add the custom sections used in our implementation of the page builder.
	 */
	public function add_builder_sections() {
		$single_args = $this->get_default_section_args();
		ttfmake_add_section(
			'wsuwpsingle',
			'Single',
			get_template_directory_uri() . '/inc/builder/sections/css/images/blank.png',
			'A single column layout.',
			array( $this, 'save_blank' ),
			'admin/single',
			'front-end/single',
			200,
			'builder-templates/',
			$single_args
		);

		$halves_args = $this->get_default_section_args();
		ttfmake_add_section(
			'wsuwphalves',
			'Halves',
			get_template_directory_uri() . '/inc/builder-custom/images/halves.png',
			'Two column layout with equal size columns.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			500,
			'builder-templates/',
			$halves_args
		);

		$sidebar_left_args = array(
			400 => array(
				'type'    => 'select',
				'name'    => 'section-layout',
				'class'   => 'ttfmake-text-columns',
				'label'   => 'Columns Layout',
				'default' => 'side-left',
				'options' => array(
					'side-left' => 'Side Left',
					'margin-left'  => 'Margin Left',
				),
			),
		);
		$sidebar_left_args = wp_parse_args( $sidebar_left_args, $this->get_default_section_args() );
		ttfmake_add_section(
			'wsuwpsidebarleft',
			'Sidebar Left',
			get_template_directory_uri() . '/inc/builder-custom/images/side-left.png',
			'Two column layout with the right side larger than the left.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			400,
			'builder-templates/',
			$sidebar_left_args
		);

		$sidebar_right_args = array(
			400 => array(
				'type'    => 'select',
				'name'    => 'section-layout',
				'class'   => 'ttfmake-text-columns',
				'label'   => 'Columns Layout',
				'default' => 'side-right',
				'options' => array(
					'side-right' => 'Side Right',
					'margin-right'  => 'Margin Right',
				),
			),
		);
		$sidebar_right_args = wp_parse_args( $sidebar_right_args, $this->get_default_section_args() );
		ttfmake_add_section(
			'wsuwpsidebarright',
			'Sidebar Right',
			get_template_directory_uri() . '/inc/builder-custom/images/side-right.png',
			'Two column layout with the left side larger than the right.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			300,
			'builder-templates/',
			$sidebar_right_args
		);

		$thirds_args = array(
			400 => array(
				'type'    => 'select',
				'name'    => 'section-layout',
				'class'   => 'ttfmake-text-columns',
				'label'   => 'Columns Layout',
				'default' => 'thirds',
				'options' => array(
					'thirds' => 'Equal Thirds',
					'triptych'  => 'Triptych',
				),
			),
		);
		$thirds_args = wp_parse_args( $thirds_args, $this->get_default_section_args() );
		ttfmake_add_section(
			'wsuwpthirds',
			'Three Columns',
			get_template_directory_uri() . '/inc/builder-custom/images/thirds.png',
			'Three column layout, choose between thirds and triptych.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			600,
			'builder-templates',
			$thirds_args
		);

		$quarters_args = $this->get_default_section_args();
		ttfmake_add_section(
			'wsuwpquarters',
			'Four Columns',
			get_template_directory_uri() . '/inc/builder-custom/images/quarters.png',
			'Four column layout, all equal sizes.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			700,
			'builder-templates',
			$quarters_args
		);

		$header_args = $this->get_default_section_args();
		ttfmake_add_section(
			'wsuwpheader',
			'Top Level Header',
			get_template_directory_uri() . '/inc/builder-custom/images/h1.png',
			'An H1 element to provide a page title or other top level header.',
			array( $this, 'save_header' ),
			'admin/h1-header',
			'front-end/h1-header',
			100,
			'builder-templates',
			$header_args
		);

		$banner_args = array(
			100 => array(
				'type'  => 'section_title',
				'name'  => 'title',
				'label' => __( 'Enter section title', 'make' ),
				'class' => 'ttfmake-configuration-title ttfmake-section-header-title-input',
			),
			200 => array(
				'type'    => 'checkbox',
				'label'   => __( 'Hide navigation arrows', 'make' ),
				'name'    => 'hide-arrows',
				'default' => 0
			),
			300 => array(
				'type'    => 'checkbox',
				'label'   => __( 'Hide navigation dots', 'make' ),
				'name'    => 'hide-dots',
				'default' => 0
			),
			400 => array(
				'type'    => 'checkbox',
				'label'   => __( 'Autoplay slideshow', 'make' ),
				'name'    => 'autoplay',
				'default' => 1
			),
			500 => array(
				'type'    => 'text',
				'label'   => __( 'Time between slides (ms)', 'make' ),
				'name'    => 'delay',
				'default' => 6000
			),
			600 => array(
				'type'    => 'select',
				'label'   => __( 'Transition effect', 'make' ),
				'name'    => 'transition',
				'default' => 'scrollHorz',
				'options' => array(
					'scrollHorz' => __( 'Slide horizontal', 'make' ),
					'fade'       => __( 'Fade', 'make' ),
					'none'       => __( 'None', 'make' ),
				)
			),
			700 => array(
				'type'    => 'text',
				'label'   => __( 'Section height (px)', 'make' ),
				'name'    => 'height',
				'default' => 600
			),
			800 => array(
				'type'        => 'select',
				'label'       => __( 'Responsive behavior', 'make' ),
				'name'        => 'responsive',
				'default'     => 'balanced',
				'description' => __( 'Choose how the banner will respond to varying screen widths.', 'make' ),
				'options'     => array(
					'balanced' => __( 'Default', 'make' ),
					'aspect'   => __( 'Aspect', 'make' ),
				)
			),
			900 => array(
				'type'    => 'text',
				'name'    => 'section-classes',
				'class'   => 'ttfmake-section-classes',
				'label'   => 'Section Classes',
				'default' => 'gutter pad-top',
			),
			910 => array(
				'type'    => 'text',
				'name'    => 'section-wrapper',
				'class'   => 'ttfmake-section-wrapper',
				'label'   => 'Section Wrapper Classes',
			),
		);
		ttfmake_add_section(
			'banner',
			_x( 'Banner', 'section name', 'make' ),
			get_template_directory_uri() . '/inc/builder/sections/css/images/banner.png',
			__( 'Display multiple types of content in a banner or a slider.', 'make' ),
			array( $this, 'save_banner' ),
			'admin/banner',
			'front-end/banner',
			800,
			'builder-templates',
			$banner_args
		);

	}

	/**
	 * Clean a passed input value of arbitrary classes.
	 *
	 * @param string $classes A string of arbitrary classes from a text input.
	 *
	 * @return string Clean, space delimited classes for output.
	 */
	public function clean_classes( $classes ) {
		$classes = explode( ' ', trim( $classes ) );
		$classes = array_map( 'sanitize_key', $classes );
		$classes = implode( ' ', $classes );

		return $classes;
	}

	/**
	 * Allow phrasing tags to be added in title areas via the kses allowed HTML filter.
	 *
	 * @return array List of tags and attributes allowed.
	 */
	public function allow_phrasing_in_titles() {
		$phrasing_tags = array( 'b', 'big', 'i', 'small', 'tt', 'abbr', 'acronym', 'cite', 'code', 'dfn', 'em', 'kbd', 'strong',
								'samp', 'var', 'a', 'bdo', 'br', 'q', 'span', 'sub', 'sup', 'label', 'wbr', 'del', 'ins' );

		$tags = array();

		foreach( $phrasing_tags as $tag ) {
			$tags[ $tag ]['class'] = true;
			$tags[ $tag ]['id'] = true;
		}

		return $tags;
	}

	/**
	 * Clean the data being passed from the title input field to ensure it is ready
	 * for input into the database as part of the template.
	 *
	 * @param array $data Array of data inputs being passed.
	 *
	 * @return array Clean data.
	 */
	public function save_header( $data ) {
		$clean_data = array();

		// The title_save_pre filter applies wp_filter_kses() to the title.
		if ( isset( $data['title'] ) ) {
			add_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
			remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
		}

		if ( isset( $data['section-classes'] ) ) {
			$clean_data['section-classes'] = $this->clean_classes( $data['section-classes'] );
		}

		if ( isset( $data['section-wrapper'] ) ) {
			$clean_data['section-wrapper'] = $this->clean_classes( $data['section-wrapper'] );
		}

		return $clean_data;
	}

	/**
	 * Clean the data being passed from the save of a "Single" section in the admin.
	 *
	 * @param array $data Array of data inputs being passed.
	 *
	 * @return array Clean data.
	 */
	public function save_blank( $data ) {
		$clean_data = array();

		if ( isset( $data['title'] ) ) {
			add_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
			remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
		}

		if ( isset( $data['content'] ) ) {
			$clean_data['content'] = sanitize_post_field( 'post_content', $data['content'], get_the_ID(), 'db' );
		}

		if ( isset( $data['section-classes'] ) ) {
			$clean_data['section-classes'] = $this->clean_classes( $data['section-classes'] );
		}

		if ( isset( $data['section-wrapper'] ) ) {
			$clean_data['section-wrapper'] = $this->clean_classes( $data['section-wrapper'] );
		}

		if ( isset( $data['column-classes'] ) ) {
			$clean_data['column-classes'] = $this->clean_classes( $data['column-classes'] );
		}
		return $clean_data;
	}

	/**
	 * Clean the data being passed from the save of a columns layout.
	 *
	 * @param array $data Array of data inputs being passed.
	 *
	 * @return array Clean data.
	 */
	public function save_columns( $data ) {
		$clean_data = array();

		if ( isset( $data['title'] ) ) {
			add_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
			remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
		}

		if ( isset( $data['columns-number'] ) ) {
			if ( in_array( $data['columns-number'], range( 1, 4 ) ) ) {
				$clean_data['columns-number'] = $data['columns-number'];
			}
		}

		if ( isset( $data['columns-order'] ) ) {
			$clean_data['columns-order'] = array_map( array( 'TTFMake_Builder_Save', 'clean_section_id' ), explode( ',', $data['columns-order'] ) );
		}

		if ( isset( $data['columns'] ) && is_array( $data['columns'] ) ) {
			$i = 1;
			foreach ( $data['columns'] as $id => $item ) {
				if ( isset( $item['title'] ) ) {
					add_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
					$clean_data['columns'][ $id ]['title'] = apply_filters( 'title_save_pre', $item['title'] );
					remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );

					// The first title serves as the section title
					if ( 1 === $i ) {
						add_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
						$clean_data['label'] = apply_filters( 'title_save_pre', $item['title'] );
						remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
					}
				}

				if ( isset( $item['image-link'] ) ) {
					$clean_data['columns'][ $id ]['image-link'] = esc_url_raw( $item['image-link'] );
				}

				if ( isset( $item['image-id'] ) ) {
					$clean_data['columns'][ $id ]['image-id'] = absint( $item['image-id'] );
				}

				if ( isset( $item['content'] ) ) {
					$clean_data['columns'][ $id ]['content'] = sanitize_post_field( 'post_content', $item['content'], get_the_ID(), 'db' );
				}

				if ( isset( $item['toggle'] ) ) {
					if ( in_array( $item['toggle'], array( 'visible', 'invisible' ) ) ) {
						$clean_data['columns'][ $id ]['toggle'] = $item['toggle'];
					}
				}

				if ( isset( $item['column-classes'] ) ) {
					$clean_data['columns'][ $id ]['column-classes'] = $this->clean_classes( $item['column-classes'] );
				}

				$i++;
			}
		}

		if ( isset( $data['section-classes'] ) ) {
			$clean_data['section-classes'] = $this->clean_classes( $data['section-classes'] );
		}

		if ( isset( $data['section-wrapper'] ) ) {
			$clean_data['section-wrapper'] = $this->clean_classes( $data['section-wrapper'] );
		}

		if ( isset( $data['section-layout'] ) ) {
			$clean_data['section-layout'] = $this->clean_classes( $data['section-layout'] );
		}

		return $clean_data;
	}

	/**
	 * Clean the data being passed when saving the Banner layout.
	 *
	 * @param array $data Array of data inputs being passed.
	 *
	 * @return array Clean data.
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

		if ( isset( $data['responsive'] ) && in_array( $data['responsive'], array( 'aspect', 'balanced' ) ) ) {
			$clean_data['responsive'] = $data['responsive'];
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

				if ( isset( $slide['slide-url'] ) ) {
					$clean_data['banner-slides'][ $id ]['slide-url'] = esc_url_raw( $slide['slide-url'] );
				}
			}
		}

		if ( isset( $data['section-classes'] ) ) {
			$clean_data['section-classes'] = $this->clean_classes( $data['section-classes'] );
		}

		if ( isset( $data['section-wrapper'] ) ) {
			$clean_data['section-wrapper'] = $this->clean_classes( $data['section-wrapper'] );
		}

		return $clean_data;
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
		if ( ! ttfmake_post_type_supports_builder( $typenow ) || ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) )) {
			return;
		}

		// Define the templates to print
		$templates = array(
			array(
				'id' => 'banner-slide',
				'builder_template' => 'admin/banner-slide',
				'path' => 'builder-templates/',
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
}
new Spine_Builder_Custom();

/**
 * Retrieve data for display in a two column format - halves, sidebar, etc - in
 * a front end template.
 *
 * @param array $ttfmake_section_data Data to be prepped for column output.
 *
 * @return array Prepped data.
 */
function spine_get_column_data( $ttfmake_section_data, $columns_number = 2 ) {
	$columns_order = array();
	if ( isset( $ttfmake_section_data['columns-order'] ) ) {
		$columns_order = $ttfmake_section_data['columns-order'];
	}

	$columns_data = array();
	if ( isset( $ttfmake_section_data['columns'] ) ) {
		$columns_data = $ttfmake_section_data['columns'];
	}

	$columns_array = array();
	if ( ! empty( $columns_order ) && ! empty( $columns_data ) ) {
		$count = 0;
		foreach ( $columns_order as $order => $key ) {
			$columns_array[ $order ] = $columns_data[ $key ];
			$count++;
			if ( $count >= $columns_number ) {
				break;
			}
		}
	}

	return $columns_array;
}

/**
 * Override for method expected by upstream project Make.
 */
function ttfmake_is_plus() {
	return false;
}

/**
 * Add a title field in the builder for any of our various columns when called.
 *
 * @param string $column_name
 * @param string $title
 *
 * @return string
 */
function spine_builder_add_column_title( $column_name, $title ) {
	ob_start();
	?>
	<div class="ttfmake-column-title">
		<input type="text" name="<?php echo esc_attr( $column_name ); ?>[title]" value="<?php echo esc_attr( $title ); ?>" />
	</div>
	<?php
	$output = ob_get_clean();
	return $output;
}