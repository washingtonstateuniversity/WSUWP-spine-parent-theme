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
		if ( class_exists( 'MultiPostThumbnails' ) ) {
			add_action( 'after_setup_theme', array( $this, 'setup_additional_post_thumbnails' ) );
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
}
new Spine_Theme_Images();