<?php
/**
 * @package Make
 */

if ( ! function_exists( 'TTFMAKE_Builder_Save' ) ) :
/**
 * Defines the functionality for the HTML Builder.
 *
 * @since 1.0.0.
 */
class TTFMAKE_Builder_Save {
	/**
	 * The one instance of TTFMAKE_Builder_Save.
	 *
	 * @since 1.0.0.
	 *
	 * @var   TTFMAKE_Builder_Save
	 */
	private static $instance;

	/**
	 * Holds the clean section data.
	 *
	 * @since 1.0.0.
	 *
	 * @var   array
	 */
	private $_sanitized_sections = array();

	/**
	 * Instantiate or return the one TTFMAKE_Builder_Save instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Builder_Save
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
	 * @return TTFMAKE_Builder_Save
	 */
	public function __construct() {
		// Only add filters when the builder is being saved
		if ( isset( $_POST[ 'ttfmake-builder-nonce' ] ) && wp_verify_nonce( $_POST[ 'ttfmake-builder-nonce' ], 'save' ) && isset( $_POST['ttfmake-section-order'] ) ) {
			// Save the post's meta data
			add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );

			// Combine the input into the post's content
			add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 30, 2 );
		}
	}

	/**
	 * Save section data.
	 *
	 * @since  1.0.0.
	 *
	 * @param  int        $post_id    The ID of the current post.
	 * @param  WP_Post    $post       The post object for the current post.
	 * @return void
	 */
	public function save_post( $post_id, $post ) {
		// Don't do anything during autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Only check permissions for pages since it can only run on pages
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

		// Don't save data if we're not on the Builder template
		$template = isset( $_POST[ 'page_template' ] ) ? $_POST[ 'page_template' ] : '';
		if ( 'template-builder.php' !== $template ) {
			return;
		}

		// Process and save data
		if ( isset( $_POST[ 'ttfmake-builder-nonce' ] ) && wp_verify_nonce( $_POST[ 'ttfmake-builder-nonce' ], 'save' ) && isset( $_POST['ttfmake-section-order'] ) ) {
			$this->save_data( $this->get_sanitized_sections(), $post_id );
		}
	}

	/**
	 * Validate and sanitize the builder section data.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $sections     The section data submitted to the server.
	 * @param  string    $order        The comma separated list of the section order.
	 * @return array                   Array of cleaned section data.
	 */
	public function prepare_data( $sections, $order ) {
		$ordered_sections    = array();
		$clean_sections      = array();
		$registered_sections = ttfmake_get_sections();

		// Get the order in which to process the sections
		$order = explode( ',', $order );

		// Sort the sections into the proper order
		foreach ( $order as $value ) {
			if ( isset( $sections[ $value ] ) ) {
				$ordered_sections[ $value ] = $sections[ $value ];
			}
		}

		// Call the save callback for each section
		foreach ( $ordered_sections as $id => $values ) {
			if ( isset( $registered_sections[ $values['section-type'] ]['save_callback'] ) && true === $this->is_save_callback_callable( $registered_sections[ $values['section-type'] ] ) ) {
				$clean_sections[ $id ]                 = apply_filters( 'ttfmake_prepare_data_section', call_user_func_array( $registered_sections[ $values['section-type'] ]['save_callback'], array( $values ) ), $values, $values['section-type'] );
				$clean_sections[ $id ]['state']        = ( isset( $values['state'] ) ) ? sanitize_key( $values['state'] ) : 'open';
				$clean_sections[ $id ]['section-type'] = $values['section-type'];
				$clean_sections[ $id ]['id']           = $id;
			}
		}

		return $clean_sections;
	}

	/**
	 * Save an array of data as individual rows in postmeta.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $sections    Array of section data.
	 * @param  string    $post_id     The post ID.
	 * @return void
	 */
	public function save_data( $sections, $post_id ) {
		/**
		 * Save each value in the array as a separate row in the `postmeta` table. This avoids the nasty issue with
		 * array serialization, whereby changing the site domain can lead to the value being unreadable. Instead, each
		 * value is independent.
		 */
		$values_to_save = $this->flatten_array( $sections, '_ttfmake:', ':' );

		foreach ( $values_to_save as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		// Save the ids for the sections. This will be used to lookup all of the separate values.
		$section_ids = array_keys( $sections );
		update_post_meta( $post_id, '_ttfmake-section-ids', $section_ids );

		// Remove the old section values if necessary
		$this->prune_abandoned_rows( $post_id, $values_to_save );
	}

	/**
	 * Remove deprecated section values.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $post_id           The post to prune the values.
	 * @param  array     $current_values    The current values that *should* be in the post's postmeta.
	 * @return void
	 */
	public function prune_abandoned_rows( $post_id, $current_values ) {
		// Get all of the metadata associated with the post
		$post_meta = get_post_meta( $post_id );

		// Any meta containing the old keys should be deleted
		if ( is_array( $post_meta ) && ! empty( $post_meta ) ) {
			foreach ( $post_meta as $key => $value ) {
				// Only consider builder values
				if ( 0 === strpos( $key, '_ttfmake:' ) ) {
					if ( ! isset( $current_values[ $key ] ) ) {
						delete_post_meta( $post_id, $key );
					}
				}
			}
		}
	}

	/**
	 * Flatten a multidimensional array.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $array        Array to transform.
	 * @param  string    $prefix       The beginning key value.
	 * @param  string    $separator    The value to place between key values.
	 * @return array                   Flattened array.
	 */
	public function flatten_array( $array, $prefix = '', $separator = ':' ) {
		$result = array();

		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = $result + $this->flatten_array( $value, $prefix . $key . $separator, $separator );
			}
			else {
				$result[ $prefix . $key ] = $value;
			}
		}

		return $result;
	}

	/**
	 * Determine if the specified save_callback is callable.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $section    The registered section data.
	 * @return bool                 True if callback; false if not callable.
	 */
	public function is_save_callback_callable( $section ) {
		$result = false;

		if ( ! empty( $section['save_callback'] ) ) {
			$callback = $section['save_callback'];

			if ( is_array( $callback ) && isset( $callback[0] ) && isset( $callback[1] ) ) {
				$result = method_exists( $callback[0], $callback[1] );
			} elseif ( is_string( $callback ) ) {
				$result = function_exists( $callback );
			}
		}

		return $result;
	}

	/**
	 * On post save, use a theme template to generate content from metadata.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data       The processed post data.
	 * @param  array    $postarr    The raw post data.
	 * @return array                Modified post data.
	 */
	public function wp_insert_post_data( $data, $postarr ) {
		// Make sure the correct page template is set
		$template = isset( $_POST[ 'page_template' ] ) ? $_POST[ 'page_template' ] : '';
		if ( 'template-builder.php' !== $template || ! isset( $_POST[ 'ttfmake-builder-nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ttfmake-builder-nonce' ], 'save' ) ) {
			return $data;
		}

		// Don't do anything during autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $data;
		}

		// Only check permissions for pages since it can only run on pages
		if ( ! current_user_can( 'edit_page', get_the_ID() ) ) {
			return $data;
		}

		// The data has been deleted and can be removed
		$sanitized_sections = apply_filters( 'ttfmake_insert_post_data_sections', $this->get_sanitized_sections() );
		if ( empty( $sanitized_sections ) ) {
			$data['post_content'] = '';
			return $data;
		}

		// Generate the post content
		$post_content = $this->generate_post_content( $sanitized_sections );

		// Sanitize and set the content
		kses_remove_filters();
		$data['post_content'] = sanitize_post_field( 'post_content', $post_content, get_the_ID(), 'db' );
		kses_init_filters();

		return $data;
	}

	/**
	 * Based on section data, generate a post's post_content.
	 *
	 * @since  1.0.4.
	 *
	 * @param  array     $data    Data for sections used to comprise a page's post_content.
	 * @return string             The post content.
	 */
	public function generate_post_content( $data ) {
		// Run wpautop when saving the data
		add_filter( 'ttfmake_the_builder_content', 'wpautop' );

		// Handle oEmbeds correctly
		add_filter( 'ttfmake_the_builder_content', array( $this, 'embed_handling' ), 8 );
		add_filter( 'embed_handler_html', array( $this, 'embed_handler_html' ) , 10, 3 );
		add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ) , 10, 4 );

		// Remove editor image constraints while rendering section data.
		add_filter( 'editor_max_image_size', array( &$this, 'remove_image_constraints' ) );

		// Start the output buffer to collect the contents of the templates
		ob_start();

		// For each sections, render it using the template
		foreach ( $data as $section ) {
			global $ttfmake_section_data, $ttfmake_sections;
			$ttfmake_section_data = $section;
			$ttfmake_sections     = $data;

			// Get the registered sections
			$registered_sections = ttfmake_get_sections();

			// Get the template for the section
			ttfmake_load_section_template(
				$registered_sections[ $section['section-type'] ]['display_template'],
				$registered_sections[ $section['section-type'] ]['path']
			);

			// Cleanup the global
			unset( $GLOBALS['ttfmake_section_data'] );
		}

		// Get the rendered templates from the output buffer
		$post_content = ob_get_clean();

		// Allow constraints again after builder data processing is complete.
		remove_filter( 'editor_max_image_size', array( &$this, 'remove_image_constraints' ) );

		return $post_content;
	}

	/**
	 * Run content through the $wp_embed->autoembed method to identify and process oEmbeds.
	 *
	 * This function causes oEmbeds to be identified and HTML to created for those oEmbeds. Additional functions in this
	 * file will not allow the embed code to be saved, but rather wrap the oEmbed url in embed shortcode tags (i.e.,
	 * [embed]url[/embed]).
	 *
	 * In other words, if the following content is passed to this function:
	 *
	 *     https://www.youtube.com/watch?v=jScLjUlLTLI
	 *
	 *     <p>Here is some more content</p>
	 *
	 * it is transformed into:
	 *
	 *     [embed]https://www.youtube.com/watch?v=jScLjUlLTLI[/embed]
	 *
	 *     <p>Here is some more content</p>
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $content    The content to inspect.
	 * @return string                The modified content.
	 */
	function embed_handling( $content ) {
		global $wp_embed;
		$content = $wp_embed->autoembed( $content );
		return $content;
	}

	/**
	 * Modify the embed HTML to be just the URL wrapped in embed tags.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $cache      The previously cached embed value.
	 * @param  string    $url        The embed URL.
	 * @param  array     $attr       The shortcode attrs.
	 * @param  int       $post_ID    The current Post ID.
	 * @return string                The modified embed code.
	 */
	function embed_oembed_html( $cache, $url, $attr, $post_ID ) {
		return $this->generate_embed_shortcode( $url, $attr );
	}

	/**
	 * Modify the embed HTML to be just the URL wrapped in embed tags.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $return     The embed code.
	 * @param  string    $url        The embed URL.
	 * @param  array     $attr       The shortcode attrs.
	 * @return string                The modified embed code.
	 */
	function embed_handler_html( $return, $url, $attr ) {
		return $this->generate_embed_shortcode( $url, $attr );
	}

	/**
	 * Wrap a URL in embed shortcode tags.
	 *
	 * This function also will apply shortcode attrs if they are available. It only supports the "height" and "width"
	 * attributes that core supports.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $url        The embed URL.
	 * @param  array     $attr       The shortcode attrs.
	 * @return string                The modified embed code.
	 */
	function generate_embed_shortcode( $url, $attr ) {
		$attr_string = '';

		if ( isset( $attr['height'] ) ) {
			$attr_string = ' height="' . absint( $attr['height'] ) . '"';
		}

		if ( isset( $attr['width'] ) ) {
			$attr_string = ' width="' . absint( $attr['width'] ) . '"';
		}

		return '[embed' . $attr_string . ']' . $url . '[/embed]';
	}

	/**
	 * Allows image size to be saved regardless of the content width variable.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $dimensions    The default dimensions.
	 * @return array                   The modified dimensions.
	 */
	public function remove_image_constraints( $dimensions ) {
		return array( 9999, 9999 );
	}

	/**
	 * Get the next section's data.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $current_section    The current section's data.
	 * @param  array    $sections           The list of sections.
	 * @return array                        The next section's data.
	 */
	public function get_next_section_data( $current_section, $sections ) {
		$next_is_the_one = false;
		$next_data       = array();

		foreach ( $sections as $id => $data ) {
			if ( true === $next_is_the_one ) {
				$next_data = $data;
				break;
			}

			if ( $current_section['id'] === $id ) {
				$next_is_the_one = true;
			}
		}

		return $next_data;
	}

	/**
	 * Get the previous section's data.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $current_section    The current section's data.
	 * @param  array    $sections           The list of sections.
	 * @return array                        The previous section's data.
	 */
	public function get_prev_section_data( $current_section, $sections ) {
		foreach ( $sections as $id => $data ) {
			if ( $current_section['id'] === $id ) {
				break;
			} else {
				$prev_key = $id;
			}
		}

		return ( isset( $prev_key ) && isset( $sections[ $prev_key ] ) ) ? $sections[ $prev_key ] : array();
	}

	/**
	 * Prepare the classes need for a section.
	 *
	 * Includes the name of the current section type, the next section type and the previous section type. It will also
	 * denote if a section is the first or last section.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $current_section    The current section's data.
	 * @param  array     $sections           The list of sections.
	 * @return string                        The class string.
	 */
	public function section_classes( $current_section, $sections ) {
		$prefix = 'builder-section-';

		// Get the current section type
		$current = ( isset( $current_section['section-type'] ) ) ? $prefix . $current_section['section-type'] : '';

		// Get the next section's type
		$next_data = $this->get_next_section_data( $current_section, $sections );
		$next      = ( ! empty( $next_data ) && isset( $next_data['section-type'] ) ) ? $prefix . 'next-' . $next_data['section-type'] : $prefix . 'last';

		// Get the previous section's type
		$prev_data = $this->get_prev_section_data( $current_section, $sections );
		$prev      = ( ! empty( $prev_data ) && isset( $prev_data['section-type'] ) ) ? $prefix . 'prev-' . $prev_data['section-type'] : $prefix . 'first';

		// Return the values as a single string
		return apply_filters( 'ttfmake_section_classes', $prev . ' ' . $current . ' ' . $next, $current_section );
	}

	/**
	 * Duplicate of "the_content" with custom filter name for generating content in builder templates.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $content    The original content.
	 * @return void
	 */
	public function the_builder_content( $content ) {
		$content = apply_filters( 'ttfmake_the_builder_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		echo $content;
	}

	/**
	 * Get the sanitized section data.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    The sanitized section data.
	 */
	public function get_sanitized_sections() {
		if ( empty( $this->_sanitized_sections ) ) {
			if ( isset( $_POST['ttfmake-section-order'] ) ) {
				$data = ( isset( $_POST['ttfmake-section'] ) ) ? $_POST['ttfmake-section'] : array();
				$this->_sanitized_sections = $this->prepare_data( $data, $_POST['ttfmake-section-order'] );
			}
		}

		return $this->_sanitized_sections;
	}

	/**
	 * Sanitizes a string to only return numbers.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $id    The section ID.
	 * @return string           The sanitized ID.
	 */
	public static function clean_section_id( $id ) {
		return preg_replace( '/[^0-9]/', '', $id );
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_builder_save' ) ) :
/**
 * Instantiate or return the one TTFMAKE_Builder_Save instance.
 *
 * @since  1.0.0.
 *
 * @return TTFMAKE_Builder_Save
 */
function ttfmake_get_builder_save() {
	return TTFMAKE_Builder_Save::instance();
}
endif;

add_action( 'admin_init', 'ttfmake_get_builder_save' );

if ( ! function_exists( 'ttfmake_sanitize_image_id' ) ) :
/**
 * Cleans an ID for an image.
 *
 * Handles integer or dimension IDs. This function is necessary for handling the cleaning of placeholder image IDs.
 *
 * @since  1.0.0.
 *
 * @param  int|string    $id    Image ID.
 * @return int|string           Cleaned image ID.
 */
function ttfmake_sanitize_image_id( $id ) {
	if ( false !== strpos( $id, 'x' ) ) {
		$pieces       = explode( 'x', $id );
		$clean_pieces = array_map( 'absint', $pieces );
		$id           = implode( 'x', $clean_pieces );
	} else {
		$id = absint( $id );
	}

	return $id;
}
endif;