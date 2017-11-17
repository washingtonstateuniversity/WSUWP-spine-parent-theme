<?php
/**
 * Class Spine_Theme_Navigation
 *
 * Manages the various customizations to navigation used by the Spine theme. This
 * includes adjustments based on other plugins in addition to WordPress core.
 */
class Spine_Theme_Navigation {
	/**
	 * Parents of dogeared items that should be also be marked
	 * as dogeared items.
	 *
	 * @since 0.26.8
	 *
	 * @var array
	 */
	public $parent_dogeared = array();

	public function __construct() {
		add_action( 'init', array( $this, 'theme_menus' ) );

		// Filters for navigation handled by WordPress core.
		add_filter( 'nav_menu_css_class', array( $this, 'abbridged_menu_classes' ), 10, 3 );

		// Filters for navigation handled by BU Navigation.
		add_filter( 'bu_navigation_filter_pages', array( $this, 'bu_filter_page_urls' ), 11 );
		add_filter( 'bu_navigation_filter_anchor_attrs', array( $this, 'bu_filter_anchor_attrs' ), 10, 1 );
		add_filter( 'bu_navigation_filter_item_attrs', array( $this, 'bu_navigation_filter_item_attrs' ), 10, 2 );
	}

	/**
	 * Setup the default navigation menus used in the Spine.
	 */
	public function theme_menus() {
		register_nav_menus(
			array(
				'site'    => 'Site',
				'offsite' => 'Offsite',
			)
		);
	}

	/**
	 * Condense verbose menu classes provided by WordPress when processing the Spine
	 * navigation. Removes the default current-menu-item and current_page_parent classes
	 * if they are found on this page view and replaces them with 'active'.
	 *
	 * Adds the 'active' class to a current page's immediate parent if the page itself
	 * is not in the Spine navigation menu.
	 *
	 * If this is not a menu in the Spine navigation, the 'active' classes is appended to
	 * the array, but other classes are left alone.
	 *
	 * @param array    $classes Current list of nav menu classes.
	 * @param WP_Post  $item    Post object representing the menu item.
	 * @param stdClass $args    Arguments used to create the menu.
	 *
	 * @return array Modified list of nav menu classes.
	 */
	public function abbridged_menu_classes( $classes, $item, $args ) {
		$post = get_post();
		$current_or_parent_menu_item = array_intersect( array( 'current-menu-item', 'current_page_parent' ), $classes );
		$parent_of_page_not_in_menu = is_page() && ( $item->object_id == $post->post_parent ) && ! in_array( 'current-page-parent', $classes, true );
		$event_post_or_archive = is_post_type_archive( 'tribe_events' ) || is_singular( 'tribe_events' );
		$event_archive_menu_item = in_array( 'current-menu-item current_page_item', $classes, true );

		if ( in_array( $args->menu, array( 'site', 'offsite' ) ) ) {
			if ( ( $current_or_parent_menu_item || $parent_of_page_not_in_menu ) && ! $event_post_or_archive ) {
				$classes = array( 'active' );
			} elseif ( $event_post_or_archive && $event_archive_menu_item ) {
				$classes = array( 'active' );
			} else {
				$classes = array();
			}
		} elseif ( $current_or_parent_menu_item ) {
			$classes[] = 'active';
		}

		return $classes;
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
			$query = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND post_id IN (" . implode( ',', $ids ) . ') and meta_value = %s', '_wp_page_template', 'templates/section-label.php' );
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
		$attrs['class'] = '';

		return $attrs;
	}

	/**
	 * Filter the list item classes to manually add active on the current page in nav.
	 *
	 * @param array   $item_classes List of classes assigned to the list item.
	 * @param WP_Post $page         Post object for the current page.
	 *
	 * @return array
	 */
	public function bu_navigation_filter_item_attrs( $item_classes, $page ) {
		$remove_classes = array( 'page_item', 'current_page_item', 'current_page_parent' );
		$event_post_or_archive = is_post_type_archive( 'tribe_events' ) || is_singular( 'tribe_events' );
		$events_slug = 'events';
		if ( $event_post_or_archive ) {
			$events_calendar_options = get_option( 'tribe_events_calendar_options' );
			if ( is_array( $events_calendar_options ) && array_key_exists( 'eventsSlug', $events_calendar_options ) ) {
				$events_slug = $events_calendar_options['eventsSlug'];
			}
		}

		$posts_page = '';

		if ( get_option( 'show_on_front' ) === 'posts' ) {
			$posts_page = home_url( '/' );
		} elseif ( get_option( 'page_for_posts' ) ) {
			$posts_page = get_permalink( get_option( 'page_for_posts' ) );
		}

		if ( in_array( 'current_page_item', $item_classes, true ) ) {
			$item_classes[] = 'active';
		}

		if ( $event_post_or_archive && isset( $page->url ) && home_url( $events_slug ) === rtrim( $page->url, '/' ) ) {
			$item_classes[] = 'active';
		}

		if ( is_singular( 'post' ) && isset( $page->url ) && $posts_page === $page->url ) {
			$item_classes[] = 'active';
		}

		return array_diff( $item_classes, $remove_classes );
	}
}
new Spine_Theme_Navigation();
