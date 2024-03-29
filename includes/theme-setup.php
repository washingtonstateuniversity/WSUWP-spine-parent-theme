<?php
/**
 * Class Spine_Theme_Setup
 */
class Spine_Theme_Setup {
	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'add_editor_style' ) );
		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
		add_action( 'template_redirect', array( $this, 'check_author_page' ) );
		add_filter( 'author_link', array( $this, 'filter_author_link' ) );
		add_filter( 'upload_mimes', array( $this, 'add_mime_types' ) );

		if ( version_compare( get_bloginfo( 'version' ), '5.7', '>=' ) ) {

			add_filter( 'wp_robots', array( $this, 'filter_robots' ), 9999 );

		 } else {
			
			add_action( 'wp_head', array( $this, 'legacy_robots' ), 1 );

		 }
	}

	public function filter_robots( $robots ) {

		if ( is_search() ) {

			if ( array_key_exists( 'follow', $robots ) ) {

				$robots['follow'] = false;

			}

			$robots['nofollow'] = true;

		}

		return $robots;

	}


	public function legacy_robots( $robots ) {

		echo "<meta name='robots' content='noindex, max-image-preview:large, nofollow' />";

	}


	/**
	 * Add additional upload mime types
	 */
	public function add_mime_types( $mimes ) {

		// Allow administrators to upload svg type
		if ( current_user_can( 'administrator' ) ) {

			$mimes['svg'] = 'image/svg+xml';

		} // End if

		return $mimes;

	} // End add_mime_types

	/**
	 * Add the stylesheet used inside the editor.
	 */
	public function add_editor_style() {
		$editor_stylesheets = array( 'css/editor.css' );

		if ( '1' == spine_get_option( 'open_sans' ) ) {
			$editor_stylesheets[] = '//fonts.googleapis.com/css?family=Open+Sans%3A300%2C400%2C600%2C300italic%2C400italic%2C600italic%2C700%2C700italic&subset=latin%2Clatin-ext';
			$editor_stylesheets[] = 'css/editor-opensans.css';
		}

		add_editor_style( $editor_stylesheets );
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

	/**
	 * Provide a blank string as the author page URL when author pages have
	 * been disabled.
	 *
	 * @param string $url The URL to the author's archive page.
	 *
	 * @return string The modified URL to the author's archive page.
	 */
	public function filter_author_link( $url ) {
		if ( '1' === spine_get_option( 'show_author_page' ) ) {
			return $url;
		}

		return '';
	}
}
$spine_theme_setup = new Spine_Theme_Setup();
