<?php
/**
 * Configure and create image related functionality in the parent theme.
 *
 * Class Spine_Theme_Images
 */
class Spine_Theme_Images {

	/**
	 * Add hooks.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup_image_sizes' ), 10 );

		if ( class_exists( 'MultiPostThumbnails' ) ) {
			add_action( 'after_setup_theme', array( $this, 'setup_additional_post_thumbnails' ), 11 );
		}

		add_filter( 'wsuwp_install_default_image_sizes', 'install_default_image_sizes' );
	}

	/**
	 * Use the Multiple Post Thumbnails plugin to generate additional post
	 * thumbnails for posts and pages.
	 */
	public function setup_additional_post_thumbnails() {
		$background_args = array(
			'label' => 'Background Image',
			'id' => 'background-image',
		);

		$thumbnail_args = array(
			'label' => 'Thumbnail Image',
			'id' => 'thumbnail-image',
		);

		$background_args['post_type'] = 'post';
		new MultiPostThumbnails( $background_args );

		$background_args['post_type'] = 'page';
		new MultiPostThumbnails( $background_args );

		$thumbnail_args['post_type'] = 'post';
		new MultiPostThumbnails( $thumbnail_args );

		$thumbnail_args['post_type'] = 'page';
		new MultiPostThumbnails( $thumbnail_args );
	}

	/**
	 * Setup the default image sizes used by the theme.
	 */
	public function setup_image_sizes() {
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 198, 198, true );

		add_image_size( 'spine-thumbnail_size', 198, 198, true );
		add_image_size( 'spine-small_size', 396, 99164 );
		add_image_size( 'spine-medium_size', 792, 99164 );
		add_image_size( 'spine-large_size', 990, 99164 );
		add_image_size( 'spine-xlarge_size', 1188, 99164 );
	}

	/**
	 * Use the filter provided by the WSUWP Platform to modify the default image
	 * sizes whenever a new site is installed. Rather than using the passed parameters,
	 * we're currently overwriting the defaults with our own.
	 *
	 * @param array $image_sizes List of default image sizes.
	 *
	 * @return array Modified list of default image sizes.
	 */
	public function install_default_image_sizes( $image_sizes ) {
		$image_sizes = array(
			'thumbnail_size_w' => 198,
			'thumbnail_size_h' => 198,
			'medium_size_w'    => 396,
			'medium_size_h'    => 99164,
			'large_size_w'     => 792,
			'large_size_h'     => 99164,
		);

		return $image_sizes;
	}
}
new Spine_Theme_Images();