<?php
/**
 * @package Make
 */

/**
 * Determine if a section of a specified type.
 *
 * @since  1.0.0.
 *
 * @param  string    $type    The section type to check.
 * @param  array     $data    The section data.
 * @return bool               True if the section is the specified type; false if it is not.
 */
function ttfmake_builder_is_section_type( $type, $data ) {
	$is_section_type = ( isset( $data['section-type'] ) && $type === $data['section-type'] );

	/**
	 * Allow developers to alter if a set of data is a specified section type.
	 *
	 * @since 1.2.3.
	 *
	 * @param bool      $is_section_type    Whether or not the data represents a specific section.
	 * @param string    $type               The section type to check.
	 * @param array     $data               The section data.
	 */
	return apply_filters( 'make_builder_is_section_type', $is_section_type, $type, $data );
}

/**
 * Extract the list of gallery items from the data array.
 *
 * @since  1.0.0.
 *
 * @param  array    $ttfmake_section_data    The section data.
 * @return array                             The array of gallery items.
 */
function ttfmake_builder_get_gallery_array( $ttfmake_section_data ) {
	if ( ! ttfmake_builder_is_section_type( 'gallery', $ttfmake_section_data ) ) {
		return array();
	}

	$gallery_order = array();
	if ( isset( $ttfmake_section_data['gallery-item-order'] ) ) {
		$gallery_order = $ttfmake_section_data['gallery-item-order'];
	}

	$gallery_items = array();
	if ( isset( $ttfmake_section_data['gallery-items'] ) ) {
		$gallery_items = $ttfmake_section_data['gallery-items'];
	}

	$gallery_array = array();
	if ( ! empty( $gallery_order ) && ! empty( $gallery_items ) ) {
		foreach ( $gallery_order as $order => $key ) {
			$gallery_array[$order] = $gallery_items[$key];
		}
	}

	/**
	 * Filter the gallery item data.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $gallery_array           The array of gallery item data.
	 * @param array    $ttfmake_section_data    The section data.
	 */
	return apply_filters( 'make_builder_get_gallery_array', $gallery_array, $ttfmake_section_data );
}

/**
 * Generate the class to use for the gallery section.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @param  array     $sections                The list of sections.
 * @return string                             The class.
 */
function ttfmake_builder_get_gallery_class( $ttfmake_section_data, $sections ) {
	if ( ! ttfmake_builder_is_section_type( 'gallery', $ttfmake_section_data ) ) {
		return '';
	}

	$gallery_class = ' ';

	// Section classes
	$gallery_class .= ttfmake_get_builder_save()->section_classes( $ttfmake_section_data, $sections );

	// Columns
	$gallery_columns = ( isset( $ttfmake_section_data['columns'] ) ) ? absint( $ttfmake_section_data['columns'] ) : 1;
	$gallery_class  .= ' builder-gallery-columns-' . $gallery_columns;

	// Captions
	if ( isset( $ttfmake_section_data['captions'] ) && ! empty( $ttfmake_section_data['captions'] ) ) {
		$gallery_class .= ' builder-gallery-captions-' . esc_attr( $ttfmake_section_data['captions'] );
	}

	// Caption color
	if ( isset( $ttfmake_section_data['caption-color'] ) && ! empty( $ttfmake_section_data['caption-color'] ) ) {
		$gallery_class .= ' builder-gallery-captions-' . esc_attr( $ttfmake_section_data['caption-color'] );
	}

	// Aspect Ratio
	if ( isset( $ttfmake_section_data['aspect'] ) && ! empty( $ttfmake_section_data['aspect'] ) ) {
		$gallery_class .= ' builder-gallery-aspect-' . esc_attr( $ttfmake_section_data['aspect'] );
	}

	// Test for background padding
	$bg_color = ( isset( $ttfmake_section_data['background-color'] ) && ! empty( $ttfmake_section_data['background-color'] ) );
	$bg_image = ( isset( $ttfmake_section_data['background-image'] ) && 0 !== absint( $ttfmake_section_data['background-image'] ) );
	if ( true === $bg_color || true === $bg_image ) {
		$gallery_class .= ' has-background';
	}

	/**
	 * Filter the class applied to a gallery.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $gallery_class           The class applied to the gallery.
	 * @param array     $ttfmake_section_data    The section data.
	 * @param array     $sections                The list of sections.
	 */
	return apply_filters( 'make_gallery_class', $gallery_class, $ttfmake_section_data, $sections );
}

/**
 * Generate the CSS for the gallery.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @return string                             The CSS string.
 */
function ttfmake_builder_get_gallery_style( $ttfmake_section_data ) {
	if ( ! ttfmake_builder_is_section_type( 'gallery', $ttfmake_section_data ) ) {
		return '';
	}

	$gallery_style = '';

	// Background color
	if ( isset( $ttfmake_section_data['background-color'] ) && ! empty( $ttfmake_section_data['background-color'] ) ) {
		$gallery_style .= 'background-color:' . maybe_hash_hex_color( $ttfmake_section_data['background-color'] ) . ';';
	}

	// Background image
	if ( isset( $ttfmake_section_data['background-image'] ) && 0 !== absint( $ttfmake_section_data['background-image'] ) ) {
		$image_src = ttfmake_get_image_src( $ttfmake_section_data['background-image'], 'full' );
		if ( isset( $image_src[0] ) ) {
			$gallery_style .= 'background-image: url(\'' . addcslashes( esc_url_raw( $image_src[0] ), '"' ) . '\');';
		}
	}

	// Background style
	if ( isset( $ttfmake_section_data['background-style'] ) && ! empty( $ttfmake_section_data['background-style'] ) ) {
		if ( 'cover' === $ttfmake_section_data['background-style'] ) {
			$gallery_style .= 'background-size: cover;';
		}
	}

	/**
	 * Filter the style added to a gallery section.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $gallery_style           The style applied to the gallery.
	 * @param array     $ttfmake_section_data    The section data.
	 */
	return apply_filters( 'make_builder_get_gallery_style', $gallery_style, $ttfmake_section_data );
}

/**
 * Generate the class for an individual gallery item.
 *
 * @since  1.0.0.
 *
 * @param  array     $item                    The item's data.
 * @param  array     $ttfmake_section_data    The section data.
 * @param  int       $i                       The current gallery item iterator
 * @return string                             The class.
 */
function ttfmake_builder_get_gallery_item_class( $item, $ttfmake_section_data, $i ) {
	if ( ! ttfmake_builder_is_section_type( 'gallery', $ttfmake_section_data ) ) {
		return '';
	}

	$gallery_class = '';

	// Link
	$has_link = ( isset( $item['link'] ) && ! empty( $item['link'] ) ) ? true : false;
	if ( true === $has_link ) {
		$gallery_class .= ' has-link';
	}

	// Columns
	$gallery_columns = ( isset( $ttfmake_section_data['columns'] ) ) ? absint( $ttfmake_section_data['columns'] ) : 1;
	if ( $gallery_columns > 2 && 0 === $i % $gallery_columns ) {
		$gallery_class .= ' last-' . $gallery_columns;
	}

	if ( 0 === $i % 2 ) {
		$gallery_class .= ' last-2';
	}

	/**
	 * Filter the class used for a gallery item.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $gallery_class           The computed gallery class.
	 * @param array     $item                    The item's data.
	 * @param array     $ttfmake_section_data    The section data.
	 * @param int       $i                       The current gallery item number.
	 */
	return apply_filters( 'make_builder_get_gallery_item_class', $gallery_class, $item, $ttfmake_section_data, $i );
}

/**
 * Get the image for the gallery item.
 *
 * @since  1.0.0.
 *
 * @param  array     $item      The item's data.
 * @param  string    $aspect    The aspect ratio for the section.
 * @return string               The HTML or CSS for the item's image.
 */
function ttfmake_builder_get_gallery_item_image( $item, $aspect ) {
	global $ttfmake_section_data;
	$image = '';

	if ( ttfmake_builder_is_section_type( 'gallery', $ttfmake_section_data ) && 0 !== ttfmake_sanitize_image_id( $item[ 'image-id' ] ) ) {
		$image_style = '';

		$image_src = ttfmake_get_image_src( $item[ 'image-id' ], 'large' );
		if ( isset( $image_src[0]  ) ) {
			$image_style .= 'background-image: url(\'' . addcslashes( esc_url_raw( $image_src[0] ), '"' ) . '\');';
		}

		if ( 'none' === $aspect && isset( $image_src[1] ) && isset( $image_src[2] ) ) {
			$image_ratio = ( $image_src[2] / $image_src[1] ) * 100;
			$image_style .= 'padding-bottom: ' . $image_ratio . '%;';
		}

		if ( '' !== $image_style ) {
			$image .= '<figure class="builder-gallery-image" style="' . esc_attr( $image_style ) . '"></figure>';
		}
	}

	/**
	 * Alter the generated gallery image.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $image     The image HTML.
	 * @param array     $item      The item's data.
	 * @param string    $aspect    The aspect ratio for the section.
	 */
	return apply_filters( 'make_builder_get_gallery_item_image', $image, $item, $aspect );
}

/**
 * Get the columns data for a text section.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @return array                              Array of data for columns in a text section.
 */
function ttfmake_builder_get_text_array( $ttfmake_section_data ) {
	if ( ! ttfmake_builder_is_section_type( 'text', $ttfmake_section_data ) ) {
		return array();
	}

	$columns_number = ( isset( $ttfmake_section_data['columns-number'] ) ) ? absint( $ttfmake_section_data['columns-number'] ) : 1;

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

	/**
	 * Filter the array of builder data for the text section.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $columns_array           The ordered data for the text section.
	 * @param array    $ttfmake_section_data    The raw section data.
	 */
	return apply_filters( 'make_builder_get_text_array', $columns_array, $ttfmake_section_data );
}

/**
 * Get the class for the text section.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @param  array     $sections                The list of sections.
 * @return string                             The class.
 */
function ttfmake_builder_get_text_class( $ttfmake_section_data, $sections ) {
	if ( ! ttfmake_builder_is_section_type( 'text', $ttfmake_section_data ) ) {
		return '';
	}

	$text_class = ' ';

	// Section classes
	$text_class .= ttfmake_get_builder_save()->section_classes( $ttfmake_section_data, $sections );

	// Columns
	$columns_number = ( isset( $ttfmake_section_data['columns-number'] ) ) ? absint( $ttfmake_section_data['columns-number'] ) : 1;
	$text_class .= ' builder-text-columns-' . $columns_number;

	/**
	 * Filter the text section class.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $text_class              The computed class string.
	 * @param array     $ttfmake_section_data    The section data.
	 * @param array     $sections                The list of sections.
	 */
	return apply_filters( 'make_builder_get_text_class', $text_class, $ttfmake_section_data, $sections );
}

/**
 * Get the data for the array section.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @return array                              The data.
 */
function ttfmake_builder_get_banner_array( $ttfmake_section_data ) {
	$banner_order  = array();
	$banner_slides = array();
	$banner_array  = array();

	if ( ttfmake_builder_is_section_type( 'banner', $ttfmake_section_data ) ) {
		if ( isset( $ttfmake_section_data['banner-slide-order'] ) ) {
			$banner_order = $ttfmake_section_data['banner-slide-order'];
		}

		if ( isset( $ttfmake_section_data['banner-slides'] ) ) {
			$banner_slides = $ttfmake_section_data['banner-slides'];
		}

		if ( ! empty( $banner_order ) && ! empty( $banner_slides ) ) {
			foreach ( $banner_order as $order => $key ) {
				$banner_array[$order] = $banner_slides[$key];
			}
		}
	}

	/**
	 * Filter the data array for a banner section.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $banner_array            The ordered banner data.
	 * @param array    $ttfmake_section_data    All of the data for the section.
	 */
	return apply_filters( 'make_builder_get_banner_array', $banner_array, $ttfmake_section_data );
}

/**
 * Get the class for a banner section.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @param  array     $sections                The list of sections.
 * @return string                             The class.
 */
function ttfmake_builder_get_banner_class( $ttfmake_section_data, $sections ) {
	$banner_class = '';

	if ( ttfmake_builder_is_section_type( 'banner', $ttfmake_section_data ) ) {
		$banner_class .= ' ' . ttfmake_get_builder_save()->section_classes( $ttfmake_section_data, $sections );
	}

	/**
	 * Filter the class for the banner section.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $banner_class            The banner class.
	 * @param array     $ttfmake_section_data    The section data.
	 */
	return apply_filters( 'make_builder_banner_class', $banner_class, $ttfmake_section_data );
}

/**
 * Get the attributes for a banner slider.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @return string                             The attributes.
 */
function ttfmake_builder_get_banner_slider_atts( $ttfmake_section_data ) {
	$data_attributes = '';

	if ( ttfmake_builder_is_section_type( 'banner', $ttfmake_section_data ) ) {
		$atts = shortcode_atts( array(
			'autoplay'   => true,
			'transition' => 'scrollHorz',
			'delay'      => 6000
		), $ttfmake_section_data );

		// Data attributes
		$data_attributes  = ' data-cycle-log="false"';
		$data_attributes .= ' data-cycle-slides="div.builder-banner-slide"';
		$data_attributes .= ' data-cycle-swipe="true"';

		// Autoplay
		$autoplay = (bool) $atts['autoplay'];
		if ( false === $autoplay ) {
			$data_attributes .= ' data-cycle-paused="true"';
		}

		// Delay
		$delay = absint( $atts['delay'] );
		if ( 0 === $delay ) {
			$delay = 6000;
		}

		if ( 4000 !== $delay ) {
			$data_attributes .= ' data-cycle-timeout="' . esc_attr( $delay ) . '"';
		}

		// Effect
		$effect = trim( $atts['transition'] );
		if ( ! in_array( $effect, array( 'fade', 'fadeout', 'scrollHorz', 'none' ) ) ) {
			$effect = 'scrollHorz';
		}

		if ( 'fade' !== $effect ) {
			$data_attributes .= ' data-cycle-fx="' . esc_attr( $effect ) . '"';
		}
	}

	/**
	 * Allow for altering the banner slider attributes.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $data_attributes         The data attributes in string form.
	 * @param array     $ttfmake_section_data    The section data.
	 */
	return apply_filters( 'make_builder_get_banner_slider_atts', $data_attributes, $ttfmake_section_data );
}

/**
 * Get the class attribute for a slide.
 *
 * @since  1.0.0.
 *
 * @param  array     $slide    The data for an individual slide.
 * @return string              The slide's class.
 */
function ttfmake_builder_banner_slide_class( $slide ) {
	$slide_class = '';

	// Content position
	if ( isset( $slide['alignment'] ) && '' !== $slide['alignment'] ) {
		$slide_class .= ' ' . sanitize_html_class( 'content-position-' . $slide['alignment'] );
	}

	/**
	 * Allow developers to alter the class for the banner slide.
	 *
	 * @since 1.2.3.
	 *
	 * @param string $slide_class The banner classes.
	 */
	return apply_filters( 'make_builder_banner_slide_class', $slide_class, $slide );
}

/**
 * Get the CSS for a slide.
 *
 * @since  1.0.0.
 *
 * @param  array     $slide                   The slide data.
 * @param  array     $ttfmake_section_data    The section data.
 * @return string                             The CSS.
 */
function ttfmake_builder_banner_slide_style( $slide, $ttfmake_section_data ) {
	$slide_style = '';

	// Background color
	if ( isset( $slide['background-color'] ) && '' !== $slide['background-color'] ) {
		$slide_style .= 'background-color:' . maybe_hash_hex_color( $slide['background-color'] ) . ';';
	}

	// Background image
	if ( isset( $slide['image-id'] ) && 0 !== ttfmake_sanitize_image_id( $slide['image-id'] ) ) {
		$image_src = ttfmake_get_image_src( $slide['image-id'], 'full' );
		if ( isset( $image_src[0] ) ) {
			$slide_style .= 'background-image: url(\'' . addcslashes( esc_url_raw( $image_src[0] ), '"' ) . '\');';
		}
	}

	/**
	 * Allow developers to change the CSS for a Banner section.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $slide_style             The CSS for the banner.
	 * @param array     $slide                   The slide data.
	 * @param array     $ttfmake_section_data    The section data.
	 */
	return apply_filters( 'make_builder_banner_slide_style', esc_attr( $slide_style ), $slide, $ttfmake_section_data );
}