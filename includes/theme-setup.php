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
	}

	/**
	 * Add theme support for various features provided by WordPress.
	 */
	public function add_theme_support() {
		add_theme_support( 'html5', array( 'gallery', 'caption' ) );
	}
}
new Spine_Theme_Setup();