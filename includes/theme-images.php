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

		add_filter( 'wsuwp_install_default_image_sizes', array( $this, 'install_default_image_sizes' ) );
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
			'thumbnail_size_w' 	=> 198,
			'thumbnail_size_h' 	=> 198,
			'small_size_w'   	=> 198,
			'small_size_h'    	=> 99164,
			'medium_size_w'    	=> 396,
			'medium_size_h'    	=> 99164,
			'large_size_w'     	=> 792,
			'large_size_h'     	=> 99164,
		);

		return $image_sizes;
	}

	/**
	 * Use Multi Post Thumbnails to output the HTML necessary for displaying a custom
	 * post thumbnail.
	 *
	 * @param string $type The type of post thumbnail to display.
	 * @param string $size The size of the post thumbnail.
	 */
	public function the_post_thumbnail( $type, $size ) {
		if ( class_exists( 'MultiPostThumbnails' ) ) {
			MultiPostThumbnails::the_post_thumbnail( get_post_type(), $type, get_the_ID(), $size );
		} else {
			echo '';
		}
	}

	/**
	 * Check to see if a custom post thumbnail has been added to a post.
	 *
	 * @param string $type Type of added thumbnail to check for.
	 *
	 * @return bool True if thumbnail exists. False if not.
	 */
	public function has_post_thumbnail( $type ) {
		if ( class_exists( 'MultiPostThumbnails' ) ) {
			return MultiPostThumbnails::has_post_thumbnail( get_post_type(), $type );
		}

		return false;
	}

	/**
	 * Retrieve the source of an image added through multiple post thumbnails.
	 *
	 * @param string $type Type of thumbnail being requested.
	 * @param string $size Size of thumbnail being requested.
	 *
	 * @return bool|string URL of the image if available. False if not.
	 */
	public function get_thumbnail_image_src( $type, $size = null ) {
		if ( class_exists( 'MultiPostThumbnails' ) ) {
			return MultiPostThumbnails::get_post_thumbnail_url( get_post_type(), $type, get_the_ID(), $size );
		}

		return false;
	}
}
$spine_theme_image = new Spine_Theme_Images();

/**
 * Use the_post_thumbnail to display the default featured image provided
 * by WordPress core functionality.
 *
 * @param string $size Thumbnail size.
 */
function spine_the_featured_image( $size = 'spine-medium_size' ) {
	the_post_thumbnail( $size );
}

/**
 * Wrapper to determine if the displayed post or page has a featured image assigned.
 *
 * @return bool True if featured image exists, false if not.
 */
function spine_has_featured_image() {
	return has_post_thumbnail();
}

/**
 * Retrieve the source URL for a featured image attached to a post.
 *
 * @param string $size Size of the thumbnail to retrieve.
 *
 * @return bool|string URL of the image if available. False if not.
 */
function spine_get_featured_image_src( $size = 'spine-xlarge_size' ) {
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $size );

	if ( isset( $image[0] ) ) {
		return $image[0];
	} else {
		return false;
	}
}

/**
 * Wraps functionality inside Spine_Theme_Images to display the background image
 * configured as one of the thumnbnails in Multiple Post Thumbnails by the theme.
 *
 * @param string $size Thumbnail size.
 */
function spine_the_background_image( $size = 'spine-xlarge_size' ) {
	global $spine_theme_image;
	$spine_theme_image->the_post_thumbnail( 'background-image', $size );
}

/**
 * Wrapper to determine if the displayed post or page has a background image assigned.
 *
 * @return bool True if background exists. False if not.
 */
function spine_has_background_image() {
	global $spine_theme_image;
	return $spine_theme_image->has_post_thumbnail( 'background-image' );
}

/**
 * Retrieve the source URL for a background image attached to a post.
 *
 * @param string $size Size of the thumbnail to retrieve.
 *
 * @return bool|string URL of the image if available. False if not.
 */
function spine_get_background_image_src( $size = 'spine-xlarge_size' ) {
	global $spine_theme_image;
	return $spine_theme_image->get_thumbnail_image_src( 'background-image', $size );
}

/**
 * Wraps functionality inside Spine_Theme_Images to display the thumbnail image
 * configured as one of the thumnbnails in Multiple Post Thumbnails by the theme.
 *
 * @param string $size Thumbnail size.
 */
function spine_the_thumbnail_image( $size = 'spine-thumbnail_size' ) {
	global $spine_theme_image;
	$spine_theme_image->the_post_thumbnail( 'thumbnail-image', $size );
}

/**
 * Wrapper to determine if the displayed post or page has a thumbnail image assigned.
 *
 * @return bool True if thumbnail exists. False if not.
 */
function spine_has_thumbnail_image() {
	global $spine_theme_image;
	return $spine_theme_image->has_post_thumbnail( 'thumbnail-image' );
}

/**
 * Retrieve the source URL for a thumbnail image attached to a post.
 *
 * @param string $size Size of the thumbnail to retrieve.
 *
 * @return bool|string URL of the image if available. False if not.
 */
function spine_get_thumbnail_image_src( $size = 'spine-thumbnail_size' ) {
	global $spine_theme_image;
	return $spine_theme_image->get_thumbnail_image_src( 'thumbnail-image', $size );
}