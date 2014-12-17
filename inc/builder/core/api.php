<?php
/**
 * @package Make
 */

if ( ! class_exists( 'TTFMAKE_Sections' ) ) :
/**
 * Collector for builder sections.
 *
 * @since 1.0.0.
 *
 * Class TTFMAKE_Sections
 */
class TTFMAKE_Sections {
	/**
	 * The sections for the builder.
	 *
	 * @since 1.0.0.
	 *
	 * @var   array    The sections for the builder.
	 */
	private $_sections = array();

	/**
	 * The one instance of TTFMAKE_Sections.
	 *
	 * @since 1.0.0.
	 *
	 * @var   TTFMAKE_Sections
	 */
	private static $instance;

	/**
	 * Instantiate or return the one TTFMAKE_Sections instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Sections
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Create a new section.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Sections
	 */
	public function __constructor() {}

	/**
	 * Return the sections.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    The array of sections.
	 */
	public function get_sections() {
		return $this->_sections;
	}

	/**
	 * Add a section.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $id                  Unique ID for the section. Alphanumeric characters only.
	 * @param  string    $label               Name to display for the section.
	 * @param  string    $description         Section description.
	 * @param  string    $icon                URL to the icon for the display.
	 * @param  string    $save_callback       Function to save the content.
	 * @param  string    $builder_template    Path to the template used in the builder.
	 * @param  string    $display_template    Path to the template used for the frontend.
	 * @param  int       $order               The order in which to display the item.
	 * @param  string    $path                The path to the template files.
	 * @param  array     $config              Array of configuration options for the section.
	 * @return void
	 */
	public function add_section( $id, $label, $icon, $description, $save_callback, $builder_template, $display_template, $order, $path, $config = array() ) {
		/**
		 * Allow the added sections to be filtered.
		 *
		 * This filters allows for dynamically altering sections as they get added. This can help enforce policies for
		 * sections by sanitizing the registered values.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $section    The section being added.
		 */
		$this->_sections[ $id ] = apply_filters( 'make_add_section', array(
			'id'               => $id,
			'label'            => $label,
			'icon'             => $icon,
			'description'      => $description,
			'save_callback'    => $save_callback,
			'builder_template' => $builder_template,
			'display_template' => $display_template,
			'order'            => $order,
			'path'             => $path,
			'config'           => $config,
		) );
	}

	/**
	 * Remove a section.
	 *
	 * @since  1.0.7.
	 *
	 * @param  string    $id    Unique ID for an existing section. Alphanumeric characters only.
	 * @return void
	 */
	public function remove_section( $id ) {
		if ( isset( $this->_sections[ $id ] ) ) {
			unset( $this->_sections[ $id ] );
		}
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_sections_class' ) ) :
/**
 * Instantiate or return the one TTFMAKE_Sections instance.
 *
 * @since  1.0.0.
 *
 * @return TTFMAKE_Sections
 */
function ttfmake_get_sections_class() {
	return TTFMAKE_Sections::instance();
}
endif;

if ( ! function_exists( 'ttfmake_get_sections' ) ) :
/**
 * Get the registered sections.
 *
 * @since  1.0.0.
 *
 * @return array    The list of registered sections.
 */
function ttfmake_get_sections() {
	return ttfmake_get_sections_class()->get_sections();
}
endif;

if ( ! function_exists( 'ttfmake_get_sections_by_order' ) ) :
/**
 * Get the registered sections by the order parameter.
 *
 * @since  1.0.0.
 *
 * @return array    The list of registered sections in the parameter order.
 */
function ttfmake_get_sections_by_order() {
	$sections = ttfmake_get_sections_class()->get_sections();
	usort( $sections, 'ttfmake_sorter' );
	return $sections;
}
endif;

if ( ! function_exists( 'ttfmake_sorter' ) ) :
/**
 * Callback for `usort()` that sorts sections by order.
 *
 * @since  1.0.0.
 *
 * @param  mixed    $a    The first element.
 * @param  mixed    $b    The second element.
 * @return mixed          The result.
 */
function ttfmake_sorter( $a, $b ) {
	return $a['order'] - $b['order'];
}
endif;

if ( ! function_exists( 'ttfmake_add_section' ) ) :
/**
 * Add a section.
 *
 * @since  1.0.0.
 *
 * @param  string    $id                  Unique ID for the section. Alphanumeric characters only.
 * @param  string    $label               Name to display for the section.
 * @param  string    $description         Section description.
 * @param  string    $icon                URL to the icon for the display.
 * @param  string    $save_callback       Function to save the content.
 * @param  string    $builder_template    Path to the template used in the builder.
 * @param  string    $display_template    Path to the template used for the frontend.
 * @param  int       $order               The order in which to display the item.
 * @param  string    $path                The path to the template files.
 * @param  array     $config              Array of configuration options for the section.
 * @return void
 */
function ttfmake_add_section( $id, $label, $icon, $description, $save_callback, $builder_template, $display_template, $order, $path, $config = array() ) {
	ttfmake_get_sections_class()->add_section( $id, $label, $icon, $description, $save_callback, $builder_template, $display_template, $order, $path, $config );
}
endif;

if ( ! function_exists( 'ttfmake_remove_section' ) ) :
/**
 * Remove a defined section.
 *
 * @since  1.0.7.
 *
 * @param  string    $id    Unique ID for an existing section. Alphanumeric characters only.
 * @return void
 */
function ttfmake_remove_section( $id ) {
	ttfmake_get_sections_class()->remove_section( $id );
}
endif;