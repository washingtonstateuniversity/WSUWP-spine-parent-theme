<?php
/**
 * Class Spine_Theme_Navigation
 *
 * Manages the various customizations to navigation used by the Spine theme. This
 * includes adjustments based on other plugins in addition to WordPress core.
 */
class Spine_Theme_Navigation {
	public function __construct() {
		// Filters for navigation handled by WordPress core.
		add_filter( 'nav_menu_css_class', array( $this, 'abbridged_menu_classes' ), 10 );

		// Filters for navigation handled by BU Navigation.
		add_filter( 'bu_navigation_filter_pages', array( $this, 'bu_filter_page_urls' ), 11 );
		add_filter( 'bu_navigation_filter_anchor_atts', array( $this, 'bu_filter_anchor_attrs' ), 10, 1 );
	}

	/**
	 * Condense verbose menu classes provided by WordPress.
	 *
	 * Removes the default current-menu-item and current_page_parent classes
	 * if they are found on this page view and replaces them with 'current'.
	 *
	 * @param array $classes Current list of nav menu classes.
	 *
	 * @return array Modified list of nav menu classes.
	 */
	public function abbridged_menu_classes( $classes ) {
		if ( in_array( 'current-menu-item', $classes ) || in_array( 'current_page_parent', $classes ) ) {
			return array( 'current' );
		}

		return array();
	}

	/**
	 * Look for pages that are intended to be section labels rather than
	 * places where content exists. Filter the URL attached to these pages
	 * to be only '#' so that an overview page is not generated within the
	 * Spine navigation framework.
	 *
	 * @param array $pages A list of pages used with BU Navigation.
	 *
	 * @return array
	 */
	public function bu_filter_page_urls( $pages ) {
		global $wpdb;

		$filtered = array();

		if ( is_array( $pages ) && count( $pages ) > 0 ) {

			$ids = array_map( 'absint', array_keys( $pages ) );
			$query = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '%s' AND post_id IN (" .  implode( ',', $ids ) . ") and meta_value = '%s'", '_wp_page_template', 'templates/section-label.php' );
			$labels = $wpdb->get_results( $query, OBJECT_K );

			if ( is_array( $labels ) && count( $labels ) > 0 ) {
				foreach ( $pages as $page ) {
					if ( array_key_exists( $page->ID, $labels ) ) {
						$page->url = '#';
					}
					$filtered[ $page->ID ] = $page;
				}
			} else {
				$filtered = $pages;
			}
		}

		return $filtered;
	}

	/**
	 * Filter anchor attributes when generating the BU Navigation menu to remove the
	 * title attribute. This allows the Spine default "Overview" behavior to continue.
	 *
	 * @param array $attrs List of attributes to output as part of the anchor.
	 *
	 * @return array
	 */
	public function bu_filter_anchor_attrs( $attrs ) {
		$attrs['title'] = '';

		return $attrs;
	}
}
new Spine_Theme_Navigation();