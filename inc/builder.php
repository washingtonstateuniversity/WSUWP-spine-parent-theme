<?php

class Spine_Builder_Custom {
	public function __construct() {

		define( 'TTFMAKE_VERSION', '1.0.5' );
		include_once( 'builder-custom/extras.php' );

		if ( is_admin() ) {
			require get_template_directory() . '/inc/builder/core/base.php';
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ),11 );
		add_action( 'admin_init', array( $this, 'add_builder_sections' ), 11 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'ttfmake-admin-edit-page',
			get_template_directory_uri() . '/inc/builder-custom/js/edit-page.js',
			array( 'jquery' ),
			TTFMAKE_VERSION,
			true
		);

		wp_enqueue_script( 'wsuwp-modify-make-list', get_template_directory_uri() . '/inc/builder-custom/js/edit-sections.js', array('jquery'), spine_get_script_version(), true );

		wp_enqueue_style( 'wsuwp-builder-styles', get_template_directory_uri() . '/builder-templates/css/sections.css', array(), spine_get_script_version() );
		wp_enqueue_script( 'wsuwp-builder-halves', get_template_directory_uri() . '/builder-templates/js/halves.js', array(), spine_get_script_version(), true );

		wp_localize_script(
			'ttfmake-admin-edit-page',
			'TTFMakeEditPage',
			array(
				'featuredImage' => __( 'Featured images are not available for this page while using the current page template.', 'ttfmake' )
			)
		);
	}

	public function add_builder_sections() {
		ttfmake_add_section(
			'wsuwpsingle',
			'Single',
			get_template_directory_uri() . '/inc/builder/sections/css/images/blank.png',
			__( 'A blank canvas for standard content or HTML code.', 'ttf-one' ),
			array( $this, 'save_blank' ),
			'admin/single',
			'front-end/single',
			200,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpcolumns',
			'Columns',
			get_template_directory_uri() . '/inc/builder-custom/images/columns.png',
			'Two or more columns of varying sizes.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			100,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwphalves',
			'Halves',
			get_template_directory_uri() . '/inc/builder-custom/images/halves.png',
			'Two column layout with equal size columns.',
			array( $this, 'save_columns' ),
			'admin/halves',
			'front-end/halves',
			100,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpsidebarleft',
			'Sidebar Left',
			get_template_directory_uri() . '/inc/builder-custom/images/sideleft.png',
			'Two column layout with the right side larger than the left.',
			array( $this, 'save_columns' ),
			'admin/sidebar-left',
			'front-end/sidebar-left',
			100,
			'builder-templates/'
		);

		ttfmake_add_section(
			'wsuwpsidebarright',
			'Sidebar Right',
			get_template_directory_uri() . '/inc/builder-custom/images/sidebar.png',
			'Two column layout with the left side larger than the right.',
			array( $this, 'save_columns' ),
			'admin/sidebar-right',
			'front-end/sidebar-right',
			100,
			'builder-templates/'
		);
	}

	public function save_blank( $data ) {
		$clean_data = array();

		if ( isset( $data['title'] ) ) {
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
		}

		if ( isset( $data['content'] ) ) {
			$clean_data['content'] = sanitize_post_field( 'post_content', $data['content'], get_the_ID(), 'db' );
		}

		return $clean_data;
	}

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

		return $clean_data;
	}
}
new Spine_Builder_Custom();

function spine_get_halves_data( $ttfmake_section_data ) {
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
			$columns_array[$order] = $columns_data[$key];
			$count++;
			if ( $count >= $columns_number ) {
				break;
			}
		}
	}

	return $columns_array;
}