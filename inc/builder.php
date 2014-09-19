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
		define( 'TTFMAKE_VERSION', '1.1.0' );

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

		add_filter( 'ttfmake_insert_post_data_sections', array( $this, 'set_section_meta' ), 10, 1 );

		add_filter( 'ttfmake_builder_section_footer_links', array(  $this, 'add_builder_section_links' ), 10, 1 );
	}

	/**
	 * Enqueue the scripts and styles used with the page builder.
	 */
	public function enqueue_scripts() {
		global $pagenow;

		if ( 'page' === get_current_screen()->id ) {
			wp_enqueue_script( 'ttfmake-admin-edit-page', get_template_directory_uri() . '/inc/builder-custom/js/edit-page.js', array( 'jquery' ), spine_get_script_version(), true );

			wp_enqueue_style( 'wsuwp-builder-styles', get_template_directory_uri() . '/builder-templates/css/sections.css', array(), spine_get_script_version() );
			wp_enqueue_script( 'wsuwp-builder-actions', get_template_directory_uri() . '/builder-templates/js/builder-actions.js', array('jquery'), spine_get_script_version(), true );
			wp_enqueue_script( 'wsuwp-builder-two-columns', get_template_directory_uri() . '/builder-templates/js/two-columns.js', array(), spine_get_script_version(), true );

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
	 * Add links to the defaults displayed at the bottom of each section.
	 *
	 * @param array $links Links to be displayed.
	 *
	 * @return array Modified list of links to display.
	 */
	public function add_builder_section_links( $links ) {
		$links[50] = array(
			'href' => '#',
			'class' => 'builder-toggle-advanced',
			'label' => 'Show advanced controls',
		);

		return $links;
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
			'wsuwpthirds',
			'Three Columns',
			get_template_directory_uri() . '/inc/builder-custom/images/thirds.png',
			'Three column layout, all equal sizes.',
			array( $this, 'save_columns' ),
			'admin/three-columns',
			'front-end/thirds',
			100,
			'builder-templates'
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

		ttfmake_add_section(
			'banner',
			_x( 'Banner', 'section name', 'make' ),
			get_template_directory_uri() . '/inc/builder/sections/css/images/banner.png',
			__( 'Display multiple types of content in a banner or a slider.', 'make' ),
			array( $this, 'save_banner' ),
			'admin/banner',
			'front-end/banner',
			300,
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
	 * Allow span tags to be added in title areas via the kses allowed HTML filter.
	 *
	 * @return array List of tags and attributes allowed.
	 */
	public function allow_span_titles() {
		$tags = array();
		$tags['span']['class'] = true;
		$tags['span']['id'] = true;

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
			add_filter( 'wp_kses_allowed_html', array( $this, 'allow_span_titles' ) );
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
			remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_span_titles' ) );
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
			add_filter( 'wp_kses_allowed_html', array( $this, 'allow_span_titles' ) );
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
			remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_span_titles' ) );
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
					add_filter( 'wp_kses_allowed_html', array( $this, 'allow_span_titles' ) );
					$clean_data['columns'][ $id ]['title'] = apply_filters( 'title_save_pre', $item['title'] );
					remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_span_titles' ) );

					// The first title serves as the section title
					if ( 1 === $i ) {
						add_filter( 'wp_kses_allowed_html', array( $this, 'allow_span_titles' ) );
						$clean_data['label'] = apply_filters( 'title_save_pre', $item['title'] );
						remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_span_titles' ) );
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
	$section_classes = ( isset( $ttfmake_section_data['data']['section-classes'] ) ) ? $ttfmake_section_data['data']['section-classes'] : 'gutter pad-top';
	?>
	<div class="wsuwp-builder-meta" style="width:100%; margin-top:10px;">
		<label for="<?php echo $section_name; ?>[section-classes]">Section Classes</label><input type="text" id="<?php echo $section_name; ?>[section-classes]" class="wsuwp-builder-section-classes widefat" name="<?php echo $section_name; ?>[section-classes]" value="<?php echo esc_attr( $section_classes ); ?>" />
		<p class="description">Enter space delimited class names here to apply them to the <code>section</code> element represented by this builder area.</p>
	</div>
	<?php
}

function spine_output_builder_section_layout( $section_name, $ttfmake_section_data ) {
	if ( 'wsuwpthirds' === $ttfmake_section_data['section']['id'] ) {
		$options = array( 'thirds', 'triptych' );
		if ( isset( $ttfmake_section_data['data']['section-layout'] ) && in_array( $ttfmake_section_data['data']['section-layout'], $options ) ) {
			$current = $ttfmake_section_data['data']['section-layout'];
		} else {
			$current = 'thirds';
		}
	} elseif ( 'wsuwpsidebarleft' === $ttfmake_section_data['section']['id'] ) {
		$options = array( 'side-left', 'margin-left' );
		if ( isset( $ttfmake_section_data['data']['section-layout'] ) && in_array( $ttfmake_section_data['data']['section-layout'], $options ) ) {
			$current = $ttfmake_section_data['data']['section-layout'];
		} else {
			$current = 'side-left';
		}
	} elseif ( 'wsuwpsidebarright' === $ttfmake_section_data['section']['id'] ) {
		$options = array( 'side-right', 'margin-right' );
		if ( isset( $ttfmake_section_data['data']['section-layout'] ) && in_array( $ttfmake_section_data['data']['section-layout'], $options ) ) {
			$current = $ttfmake_section_data['data']['section-layout'];
		} else {
			$current = 'side-right';
		}
	} else {
		return;
	}

	?><div class="wsuwp-builder-meta" style="width:100%; margin-top: 10px;">
		<label for="<?php echo $section_name; ?>[section-layout]">Section Layout</label>
		<select id="<?php echo $section_name; ?>[section-layout]"
				name="<?php echo $section_name; ?>[section-layout]"
				value="<?php if ( isset( $ttfmake_section_data['data']['section-layout'] ) ) echo esc_attr( $ttfmake_section_data['data']['section-layout'] ); ?>">
			<?php
			foreach( $options as $option ) {
				echo '<option value="' . $option . '" ' . selected( $option, $current, false ) . '">' . $option . '</option>';
			}
			?></select>
	</div><?php
}