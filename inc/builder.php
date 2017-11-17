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
		define( 'TTFMAKE_VERSION', '1.3.2' );

		// Include extra functions from Make that are not part of the builder, but are required.
		include_once 'builder-custom/extras.php';

		// Include the actual core builder files from the Make theme.
		if ( is_admin() ) {
			require get_template_directory() . '/inc/builder/core/base.php';
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 11 );
		add_action( 'admin_init', array( $this, 'remove_extra_make' ), 11 );
		add_action( 'admin_init', array( $this, 'remove_builder_sections' ), 11 );
		add_action( 'admin_init', array( $this, 'add_builder_sections' ), 12 );

		add_filter( 'make_insert_post_data_sections', array( $this, 'set_section_meta' ), 10, 1 );
	}

	/**
	 * Enqueue the scripts and styles used with the page builder.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		global $pagenow;

		// Only load resources if they are needed on the current page
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) || ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}

		wp_enqueue_script( 'ttfmake-admin-edit-page', get_template_directory_uri() . '/inc/builder-custom/js/edit-page.js', array( 'jquery' ), spine_get_script_version(), true );
		wp_enqueue_script( 'wsuwp-builder-actions', get_template_directory_uri() . '/builder-templates/js/builder-actions.js', array( 'jquery' ), spine_get_script_version(), true );
		wp_enqueue_script( 'wsuwp-builder-two-columns', get_template_directory_uri() . '/builder-templates/js/two-columns.js', array(), spine_get_script_version(), true );

		wp_localize_script( 'ttfmake-admin-edit-page', 'ttfmakeEditPageData', array(
			'pageNow' => esc_js( $pagenow ),
		) );
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
	 * Add the custom sections used in our implementation of the page builder.
	 */
	public function add_builder_sections() {
		ttfmake_add_section(
			'wsuwpsingle',
			'Single',
			get_template_directory_uri() . '/inc/builder/sections/css/images/blank.png',
			'A single column layout.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			200,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwphalves',
			'Halves',
			get_template_directory_uri() . '/inc/builder-custom/images/halves.png',
			'Two column layout with equal size columns.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			500,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpsidebarleft',
			'Sidebar Left',
			get_template_directory_uri() . '/inc/builder-custom/images/side-left.png',
			'Two column layout with the right side larger than the left.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			300,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpsidebarright',
			'Sidebar Right',
			get_template_directory_uri() . '/inc/builder-custom/images/side-right.png',
			'Two column layout with the left side larger than the right.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			400,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpthirds',
			'Thirds',
			get_template_directory_uri() . '/inc/builder-custom/images/thirds.png',
			'Three column layout, choose between thirds and triptych.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			600,
			'builder-templates'
		);

		ttfmake_add_section(
			'wsuwpquarters',
			'Quarters',
			get_template_directory_uri() . '/inc/builder-custom/images/quarters.png',
			'Four column layout, all equal sizes.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			700,
			'builder-templates'
		);

		ttfmake_add_section(
			'wsuwpheader',
			'Header',
			get_template_directory_uri() . '/inc/builder-custom/images/h1.png',
			'A header element to provide a page title or other top level header.',
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
			800,
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
	 * Clean a header element against an allowed list.
	 *
	 * @param string $header_element
	 *
	 * @return string
	 */
	public function clean_header_element( $header_element ) {
		if ( in_array( $header_element, array( 'h1', 'h2', 'h3', 'h4', 'h5' ), true ) ) {
			return $header_element;
		}

		return 'h2';
	}

	/**
	 * Allow phrasing tags to be added in title areas via the kses allowed HTML filter.
	 *
	 * @return array List of tags and attributes allowed.
	 */
	public function allow_phrasing_in_titles() {
		$phrasing_tags = array(
			'b',
			'big',
			'i',
			'small',
			'tt',
			'abbr',
			'acronym',
			'cite',
			'code',
			'dfn',
			'em',
			'kbd',
			'strong',
			'samp',
			'var',
			'a',
			'bdo',
			'br',
			'q',
			'span',
			'sub',
			'sup',
			'label',
			'wbr',
			'del',
			'ins',
		);

		$tags = array();

		foreach ( $phrasing_tags as $tag ) {
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

		if ( isset( $data['column-classes'] ) ) {
			$clean_data['column-classes'] = $this->clean_classes( $data['column-classes'] );
		}

		if ( isset( $data['header-level'] ) ) {
			$clean_data['header-level'] = $this->clean_header_element( $data['header-level'] );
		}

		if ( isset( $data['column-background-image'] ) ) {
			$clean_data['column-background-image'] = esc_url_raw( $data['column-background-image'] );
		}

		if ( isset( $data['label'] ) ) {
			$clean_data['label'] = sanitize_text_field( $data['label'] );
		}

		if ( isset( $data['background-img'] ) ) {
			$clean_data['background-img'] = esc_url_raw( $data['background-img'] );
		}

		if ( isset( $data['background-mobile-img'] ) ) {
			$clean_data['background-mobile-img'] = esc_url_raw( $data['background-mobile-img'] );
		}

		$clean_data = apply_filters( 'spine_builder_save_header', $clean_data, $data );

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

				if ( isset( $item['header-level'] ) ) {
					$clean_data['columns'][ $id ]['header-level'] = $this->clean_header_element( $item['header-level'] );
				}

				if ( isset( $item['column-background-image'] ) ) {
					$clean_data['columns'][ $id ]['column-background-image'] = esc_url_raw( $item['column-background-image'] );
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

		if ( isset( $data['section-title'] ) ) {
			$clean_data['section-title'] = sanitize_text_field( $data['section-title'] );
		}

		if ( isset( $data['header-level'] ) ) {
			$clean_data['header-level'] = $this->clean_header_element( $data['header-level'] );
		}

		if ( isset( $data['label'] ) ) {
			$clean_data['label'] = sanitize_text_field( $data['label'] );
		}

		if ( isset( $data['background-img'] ) ) {
			$clean_data['background-img'] = esc_url_raw( $data['background-img'] );
		}

		if ( isset( $data['background-mobile-img'] ) ) {
			$clean_data['background-mobile-img'] = esc_url_raw( $data['background-mobile-img'] );
		}

		$clean_data = apply_filters( 'spine_builder_save_columns', $clean_data, $data );

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

				if ( isset( $slide['spine_slide_url'] ) ) {
					$clean_data['banner-slides'][ $id ]['slide-url'] = esc_url_raw( $slide['spine_slide_url'] );
				}

				if ( isset( $slide['spine_slide_title'] ) ) {
					$clean_data['banner-slides'][ $id ]['slide-title'] = sanitize_text_field( $slide['spine_slide_title'] );
				}
			}
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

		if ( isset( $data['label'] ) ) {
			$clean_data['label'] = sanitize_text_field( $data['label'] );
		}

		if ( isset( $data['background-img'] ) ) {
			$clean_data['background-img'] = esc_url_raw( $data['background-img'] );
		}

		if ( isset( $data['background-mobile-img'] ) ) {
			$clean_data['background-mobile-img'] = esc_url_raw( $data['background-mobile-img'] );
		}

		$clean_data = apply_filters( 'spine_builder_save_banner', $clean_data, $data );

		return $clean_data;
	}
}

new Spine_Builder_Custom();

/**
 * The upstream Make project has a premium plugin, Make Plus, that
 * is checked for throughout the code base. We should always return
 * false.
 *
 * @return bool Always false. Whether or not the companion plugin is installed.
 */
function ttfmake_is_plus() {
	return false;
}

/**
 * Retrieve data for display in a column format for use in any front end
 * template.
 *
 * @param array $section_data   Data to be prepped for column output.
 * @param int   $columns_number Number of columns to retrieve.
 *
 * @return array Prepped data.
 */
function spine_get_column_data( $section_data, $columns_number = 2 ) {
	$columns_order = array();
	if ( isset( $section_data['columns-order'] ) ) {
		$columns_order = $section_data['columns-order'];
	}

	$columns_data = array();
	if ( isset( $section_data['columns'] ) ) {
		$columns_data = $section_data['columns'];
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
 * @param string $section_name Current section being displayed.
 * @param array $ttfmake_section_data Data associated with the section.
 */
function spine_output_builder_section_wrapper( $section_name, $ttfmake_section_data ) {
	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[section-wrapper]">Section Wrapper:</label><input type="text"
		id="<?php echo $section_name; ?>[section-wrapper]" class="wsuwp-builder-section-wrapper widefat"
		name="<?php echo $section_name; ?>[section-wrapper]" value="<?php
		if ( isset( $ttfmake_section_data['data']['section-wrapper'] ) ) {
			echo esc_attr( $ttfmake_section_data['data']['section-wrapper'] );
		} ?>"/>
		<p class="description">Enter space delimited class names here to output a <code>div</code> element around this
			<code>section</code> with those class names applied.</p>
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
	$section_classes = ( isset( $ttfmake_section_data['data']['section-classes'] ) ) ? $ttfmake_section_data['data']['section-classes'] : apply_filters( 'spine_default_section_classes', 'gutter pad-top', $ttfmake_section_data );
	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[section-classes]">Section Classes:</label><input type="text" id="<?php echo $section_name; ?>[section-classes]" class="wsuwp-builder-section-classes widefat" name="<?php echo $section_name; ?>[section-classes]" value="<?php echo esc_attr( $section_classes ); ?>" />
		<p class="description">Enter space delimited class names here to apply them to the <code>section</code> element represented by this builder area.</p>
	</div>
	<?php
}

/**
 * Output the input field for section label shared amongst admin templates. This label helps
 * to identify sections when minimized without requiring the entry of a title for the front-end.
 *
 * @param $section_name
 * @param $ttfmake_section_data
 */
function spine_output_builder_section_label( $section_name, $ttfmake_section_data ) {
	$section_label = ( isset( $ttfmake_section_data['data']['label'] ) ) ? $ttfmake_section_data['data']['label'] : '';
	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[label]">Section Label:</label>
		<input type="text" id="<?php echo $section_name; ?>[label]" class="wsuwp-builder-section-label widefat" name="<?php echo $section_name; ?>[label]" value="<?php echo esc_attr( $section_label ); ?>" />
		<p class="description">Enter a label to use to identify sections without titles.</p>
	</div>
	<?php
}

/**
 * Output the input field for column classes and header levels used in column configuration.
 *
 * @param string $column_name
 * @param array $section_data
 * @param int $column
 */
function spine_output_builder_column_classes( $column_name, $section_data, $column = false ) {
	$header_level_default = ( 'wsuwpheader' === $section_data['section']['id'] ) ? 'h1' : 'h2';
	if ( $column ) {
		$column_classes = ( isset( $section_data['data']['columns'][ $column ]['column-classes'] ) ) ? $section_data['data']['columns'][ $column ]['column-classes'] : '';
		$header_level   = ( isset( $section_data['data']['columns'][ $column ]['header-level'] ) ) ? $section_data['data']['columns'][ $column ]['header-level'] : $header_level_default;
		$column_background = ( isset( $section_data['data']['columns'][ $column ]['column-background-image'] ) ) ? $section_data['data']['columns'][ $column ]['column-background-image'] : '';
	} else {
		$column_classes = ( isset( $section_data['data']['column-classes'] ) ) ? $section_data['data']['column-classes'] : '';
		$header_level   = ( isset( $section_data['data']['header-level'] ) ) ? $section_data['data']['header-level'] : $header_level_default;
		$column_background = ( isset( $section_data['data']['column-background-image'] ) ) ? $section_data['data']['column-background-image'] : '';
	}

	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $column_name; ?>[column-classes]">Column Classes</label>
		<input type="text"
			   id="<?php echo $column_name; ?>[column-classes]"
			   name="<?php echo $column_name; ?>[column-classes]"
			   class="spine-builder-column-classes widefat"
			   value="<?php echo esc_attr( $column_classes ); ?>"/>
		<p class="description">Enter space delimited class names here to apply them to the <code>div.column</code>
			element represented by this builder area.</p>
	</div>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $column_name; ?>[header-level]">Header Level</label>
		<select id="<?php echo $column_name; ?>[header-level]"
				name="<?php echo $column_name; ?>[header-level]"
				class="">
			<?php if ( 'wsuwpheader' === $section_data['section']['id'] ) : ?>
			<option value="h1" <?php selected( esc_attr( $header_level ), 'h1' ); ?>>H1</option>
			<?php endif; ?>
			<option value="h2" <?php selected( esc_attr( $header_level ), 'h2' ); ?>>H2</option>
			<option value="h3" <?php selected( esc_attr( $header_level ), 'h3' ); ?>>H3</option>
			<option value="h4" <?php selected( esc_attr( $header_level ), 'h4' ); ?>>H4</option>
			<option value="h5" <?php selected( esc_attr( $header_level ), 'h5' ); ?>>H5</option>
		</select>
		<p class="description">This header will wrap the column title. <?php echo strtoupper( $header_level_default ); ?> by default.</p>
	</div>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $column_name; ?>[column-background-image]">Background Image</label>
		<input type="text"
			   id="<?php echo $column_name; ?>[column-background-image]"
			   name="<?php echo $column_name; ?>[column-background-image]"
			   class="spine-builder-column-classes widefat"
			   value="<?php echo esc_attr( $column_background ); ?>" />
		<p class="description">Enter the URL of an image to apply it as this column's background.</p>
	</div>
	<?php
}

/**
 * Output a selection tool for the type of layout a section should have. This allows classes
 * to be assigned for various multi column layouts.
 *
 * @param string $section_name         Current section being displayed.
 * @param array  $ttfmake_section_data Data associated with the section.
 */
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

	?>
	<div class="wsuwp-builder-meta">
	<label for="<?php echo $section_name; ?>[section-layout]">Section Layout:</label>
	<select id="<?php echo $section_name; ?>[section-layout]" name="<?php echo $section_name; ?>[section-layout]"
			value="<?php if ( isset( $ttfmake_section_data['data']['section-layout'] ) ) { echo esc_attr( $ttfmake_section_data['data']['section-layout'] ); } ?>">
	<?php
	foreach ( $options as $option ) {
		echo '<option value="' . $option . '" ' . selected( $option, $current, false ) . '">' . $option . '</option>';
	}
	?>
	</select>
	<p class="description">See the WSU Spine <a
			href="https://github.com/washingtonstateuniversity/WSU-spine/wiki/II.2.-Page:-Size,-Layouts,-and-Grids"
			target="_blank">grid layout documentation</a> for more information on section layouts.</p>
	</div><?php
}

/**
 * Output an input field to capture background images.
 *
 * @param $section_name
 * @param $ttfmake_section_data
 */
function spine_output_builder_section_background( $section_name, $ttfmake_section_data ) {
	$section_background        = ( isset( $ttfmake_section_data['data']['background-img'] ) ) ? $ttfmake_section_data['data']['background-img'] : '';
	$section_mobile_background = ( isset( $ttfmake_section_data['data']['background-img'] ) ) ? $ttfmake_section_data['data']['background-mobile-img'] : '';

	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[background-img]">Background Image</label>
		<input type="text"
			   class="wsuwp-builder-section-image widefat"
			   id="<?php echo $section_name; ?>[background-img]"
			   name="<?php echo $section_name; ?>[background-img]"
			   value="<?php echo $section_background; ?>"/>
		<br/>
		<label for="<?php echo $section_name; ?>[background-mobile-img]">Mobile Background Image</label>
		<input type="text"
			   class="wsuwp-builder-section-image widefat"
			   id="<?php echo $section_name; ?>[background-mobile-img]"
			   name="<?php echo $section_name; ?>[background-mobile-img]"
			   value="<?php echo $section_mobile_background; ?>"/>
		<p class="description">Mobile background images are used for display widths narrower than 792px.</p>
		<p class="description">Background images on sections are an in progress feature. :)</p>
	</div>
	<?php
}

/**
 * Output the input field for section header and header level shared amongst the columns templates.
 * This outputs a header above the columns on the front-end.
 *
 * @param $section_name
 * @param $ttfmake_section_data
 */
function spine_output_builder_section_header( $section_name, $ttfmake_section_data ) {
	$section_title = ( isset( $ttfmake_section_data['data']['section-title'] ) ) ? $ttfmake_section_data['data']['section-title'] : '';
	$section_title = ( isset( $ttfmake_section_data['data']['title'] ) ) ? $ttfmake_section_data['data']['title'] : $section_title;
	$header_level = ( isset( $ttfmake_section_data['data']['header-level'] ) ) ? $ttfmake_section_data['data']['header-level'] : 'h2';
	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[section-title]">Section Title:</label>
		<input type="text" id="<?php echo $section_name; ?>[section-title]" class="wsuwp-builder-section-label widefat" name="<?php echo $section_name; ?>[section-title]" value="<?php echo esc_attr( $section_title ); ?>" />
		<p class="description">Enter a title to display above the section columns.</p>
	</div>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[header-level]">Section Title Header Level:</label>
		<select id="<?php echo $section_name; ?>[header-level]"
				name="<?php echo $section_name; ?>[header-level]">
			<option value="h1" <?php selected( esc_attr( $header_level ), 'h1' ); ?>>H1</option>
			<option value="h2" <?php selected( esc_attr( $header_level ), 'h2' ); ?>>H2</option>
			<option value="h3" <?php selected( esc_attr( $header_level ), 'h3' ); ?>>H3</option>
			<option value="h4" <?php selected( esc_attr( $header_level ), 'h4' ); ?>>H4</option>
		</select>
		<p class="description">This header will wrap the section title. H2 by default.</p>
	</div>
	<?php
}

/**
 * Load a common header template when adding sections to a page builder instance.
 */
function spine_load_section_header() {
	get_template_part( 'builder-templates/admin/section', 'header' );
}

/**
 * Load a common footer template when adding sections to a page builder instance.
 */
function spine_load_section_footer() {
	get_template_part( 'builder-templates/admin/section', 'footer' );
}
