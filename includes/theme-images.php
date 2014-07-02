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
}
new Spine_Theme_Images();