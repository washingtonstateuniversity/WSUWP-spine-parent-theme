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
		define( 'TTFMAKE_VERSION', '1.0.10' );

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
	}

	/**
	 * Enqueue the scripts and styles used with the page builder.
	 */
	public function enqueue_scripts() {
		global $pagenow;

		if ( 'page' === get_current_screen()->id ) {
			wp_enqueue_script( 'ttfmake-admin-edit-page', get_template_directory_uri() . '/inc/builder-custom/js/edit-page.js', array( 'jquery' ), spine_get_script_version(), true );

			wp_enqueue_style( 'wsuwp-builder-styles', get_template_directory_uri() . '/builder-templates/css/sections.css', array(), spine_get_script_version() );
			wp_enqueue_script( 'wsuwp-builder-two-columns', get_template_directory_uri() . '/builder-templates/js/two-columns.js', array(), spine_get_script_version(), true );

			wp_localize_script(
				'ttfmake-admin-edit-page',
				'ttfmakeEditPageData',
				array(
					'featuredImage' => __( 'Featured images are not available for this page while using the current page template.', 'make' ),
					'pageNow'       => esc_js( $pagenow ),
				)
			);
		}
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
	 * Add the custom sections used in our implementation of the page builder.
	 */
	public function add_builder_sections() {
		ttfmake_add_section(
			'wsuwpsingle',
			'Single',
			get_template_directory_uri() . '/inc/builder/sections/css/images/blank.png',
			'A single column layout.',
			array( $this, 'save_blank' ),
			'admin/single',
			'front-end/single',
			200,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwphalves',
			'Halves',
			get_template_directory_uri() . '/inc/builder-custom/images/halves.png',
			'Two column layout with equal size columns.',
			array( $this, 'save_columns' ),
			'admin/two-columns',
			'front-end/halves',
			100,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpsidebarleft',
			'Sidebar Left',
			get_template_directory_uri() . '/inc/builder-custom/images/side-left.png',
			'Two column layout with the right side larger than the left.',
			array( $this, 'save_columns' ),
			'admin/two-columns',
			'front-end/sidebar-left',
			100,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpsidebarright',
			'Sidebar Right',
			get_template_directory_uri() . '/inc/builder-custom/images/side-right.png',
			'Two column layout with the left side larger than the right.',
			array( $this, 'save_columns' ),
			'admin/two-columns',
			'front-end/sidebar-right',
			100,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpheader',
			'Top Level Header',
			get_template_directory_uri() . '/inc/builder-custom/images/h1.png',
			'An H1 element to provide a page title or other top level header.',
			array( $this, 'save_header' ),
			'admin/h1-header',
			'front-end/h1-header',
			100,
			'builder-templates'
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
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
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
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
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
					$clean_data['columns'][ $id ]['title'] = apply_filters( 'title_save_pre', $item['title'] );

					// The first title serves as the section title
					if ( 1 === $i ) {
						$clean_data['label'] = apply_filters( 'title_save_pre', $item['title'] );
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

				$i++;
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
function spine_get_two_column_data( $ttfmake_section_data ) {
	$columns_number = 2;

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
 * Output the input field for section wrapper that is shared amongst admin templates.
 *
 * @param string $section_name         Current section being displayed.
 * @param array  $ttfmake_section_data Data associated with the section.
 */
function spine_output_builder_section_wrapper( $section_name, $ttfmake_section_data ) {
	?>
	<div class="wsuwp-builder-meta" style="width:100%; margin-top:10px;">
		<label for="<?php echo $section_name; ?>[section-wrapper]">Section Wrapper</label><input type="text" id="<?php echo $section_name; ?>[section-wrapper]" class="wsuwp-builder-section-wrapper widefat" name="<?php echo $section_name; ?>[section-wrapper]" value="<?php if ( isset( $ttfmake_section_data['data']['section-wrapper'] ) ) echo esc_attr( $ttfmake_section_data['data']['section-wrapper'] ); ?>" />
		<p class="description">Enter space delimited class names here to output a <code>div</code> element around this <code>section</code> with those class names applied.</p>
	</div>
	<?php
}

/**
 * Output the input field for section classes that is shared amongst admin templates.
 *
 * @param string $section_name         Current section being displayed.
 * @param array  $ttfmake_section_data Data associated with the section.
 */
function spine_output_builder_section_classes( $section_name, $ttfmake_section_data ) {
	$section_classes = ( isset( $ttfmake_section_data['data']['section-classes'] ) ) ? $ttfmake_section_data['data']['section-classes'] : 'gutter marginalize-ends';
	?>
	<div class="wsuwp-builder-meta" style="width:100%; margin-top:10px;">
		<label for="<?php echo $section_name; ?>[section-classes]">Section Classes</label><input type="text" id="<?php echo $section_name; ?>[section-classes]" class="wsuwp-builder-section-classes widefat" name="<?php echo $section_name; ?>[section-classes]" value="<?php echo esc_attr( $section_classes ); ?>" />
		<p class="description">Enter space delimited class names here to apply them to the <code>section</code> element represented by this builder area.</p>
	</div>
	<?php
}