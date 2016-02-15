<?php
/**
 * Class Spine_Theme_Setup
 */
class Spine_Theme_Setup {
	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
		add_action( 'template_redirect', array( $this, 'check_author_page' ) );
	}

	/**
	 * Add theme support for various features provided by WordPress.
	 */
	public function add_theme_support() {
		add_theme_support( 'html5', array( 'gallery', 'caption' ) );
	}

	/**
	 * If author pages have been disabled, ensure a 404 response is returned and the
	 * proper template is displayed.
	 */
	public function check_author_page() {
		global $wp_query;

		// HEAD processing logic from WordPress core's template-loader.php
		if ( 'HEAD' === $_SERVER['REQUEST_METHOD'] && apply_filters( 'exit_on_http_head', true ) ) {
			exit();
		}

		if ( '1' === spine_get_option( 'show_author_page' ) ) {
			return;
		}

		if ( is_author() ) {
			$wp_query->is_author = false;
			$wp_query->is_404 = true;
			status_header( 404 );
		}
	}
}
new Spine_Theme_Setup();