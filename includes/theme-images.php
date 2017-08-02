<?php
/**
 * Configure and create image related functionality in the parent theme.
 *
 * Class Spine_Theme_Images
 */
class Spine_Theme_Images {
	/**
	 * @var Spine_Theme_Images
	 */
	private static $instance;

	/**
	 * Maintain and return the one instance and initiate hooks when
	 * called the first time.
	 *
	 * @return \Spine_Theme_Images
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Spine_Theme_Images();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks to include and then activate the plugin's shortcodes.
	 */
	public function setup_hooks() {
		add_action( 'after_setup_theme', array( $this, 'setup_image_sizes' ), 11 );

		if ( class_exists( 'MultiPostThumbnails' ) ) {
			add_action( 'after_setup_theme', array( $this, 'setup_additional_post_thumbnails' ), 11 );
		}

		add_filter( 'wsuwp_install_default_image_sizes', array( $this, 'install_default_image_sizes' ) );
		add_filter( 'admin_post_thumbnail_html', array( $this, 'meta_featured_image_position' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
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

		if ( true === apply_filters( 'spine_post_supports_background_image', true ) ) {
			$background_args['post_type'] = 'post';
			new MultiPostThumbnails( $background_args );
		}

		if ( true === apply_filters( 'spine_page_supports_background_image', true ) ) {
			$background_args['post_type'] = 'page';
			new MultiPostThumbnails( $background_args );
		}

		if ( true === apply_filters( 'spine_post_supports_thumbnail_image', true ) ) {
			$thumbnail_args['post_type'] = 'post';
			new MultiPostThumbnails( $thumbnail_args );
		}

		if ( true === apply_filters( 'spine_page_supports_thumbnail_image', true ) ) {
			$thumbnail_args['post_type'] = 'page';
			new MultiPostThumbnails( $thumbnail_args );
		}
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
			'thumbnail_size_w'  => 198,
			'thumbnail_size_h'  => 198,
			'small_size_w'      => 198,
			'small_size_h'      => 99164,
			'medium_size_w'     => 396,
			'medium_size_h'     => 99164,
			'large_size_w'      => 792,
			'large_size_h'      => 99164,
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

	/**
	 * Provide an input to manually adjust a featured image's background position.
	 *
	 * @param string $content HTML output for the featured image area in the post editor.
	 * @param int    $post_id ID of the post.
	 * @return string
	 */
	public function meta_featured_image_position( $content, $post_id ) {
		$position = sanitize_html_class( get_post_meta( $post_id, '_featured_image_position', true ) );

		$content .= wp_nonce_field( 'save-spine-featured-image', '_spine_featured_image_nonce', true, false );

		$content .= '<div class="featured-image-meta-extra">
						<label for="featured-image-position">Background Position</label>
						<select name="featured_image_position" id="featured-image-position">
							<option value="0">--- No Change ---</option>
							<option value="background-position-center" ' . selected( $position, 'background-position-center', false ) . '>
								Center Center</option>
							<option value="background-position-center-top" ' . selected( $position, 'background-position-center-top', false ) . '>
								Center Top</option>
							<option value="background-position-right-top" ' . selected( $position, 'background-position-right-top', false ) . '>
								Right Top</option>
							<option value="background-position-right-center" ' . selected( $position, 'background-position-right-center', false ) . '>
								Right Center</option>
							<option value="background-position-right-bottom" ' . selected( $position, 'background-position-right-bottom', false ) . '>
								Right Bottom</option>
							<option value="background-position-center-bottom" ' . selected( $position, 'background-position-center-bottom', false ) . '>
								Center Bottom</option>
							<option value="background-position-left-bottom" ' . selected( $position, 'background-position-left-bottom', false ) . '>
								Left Bottom</option>
							<option value="background-position-left-center" ' . selected( $position, 'background-position-left-center', false ) . '>
								Left Center</option>
							<option value="background-position-left-top" ' . selected( $position, 'background-position-left-top', false ) . '>
								Left Top</option>
						</select>
						<p class="description">When the featured image is displayed as a background, the above will adjust its position.</p>
					</div>';

		return $content;
	}

	public function save_post( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['_spine_featured_image_nonce'] ) || false === wp_verify_nonce( $_POST['_spine_featured_image_nonce'], 'save-spine-featured-image' ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( isset( $_POST['featured_image_position'] ) && ! empty( sanitize_html_class( $_POST['featured_image_position'] ) ) ) {
			update_post_meta( $post_id, '_featured_image_position', sanitize_html_class( $_POST['featured_image_position'] ) );
		} elseif ( 0 == $_POST['featured_image_position'] ) {
			delete_post_meta( $post_id, '_featured_image_position' );
		}
	}
}

add_action( 'after_setup_theme', 'Spine_Theme_Images', 10 );
/**
 * Start things up.
 *
 * @return \Spine_Theme_Images
 */
function Spine_Theme_Images() {
	return Spine_Theme_Images::get_instance();
}

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
 * We use our `spine_get_featured_image_src()` rather than `has_thumbnail()` as we
 * want to ensure the source of the image is valid and not empty as a result of a
 * misplaced media attachment.
 *
 *
 * @return bool True if featured image exists, false if not.
 */
function spine_has_featured_image() {
	return spine_get_featured_image_src();
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

	if ( isset( $image[0] ) && ! empty( $image[0] ) ) {
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
	return Spine_Theme_Images()->the_post_thumbnail( 'background-image', $size );
}

/**
 * Wrapper to determine if the displayed post or page has a background image assigned.
 *
 * @return bool True if background exists. False if not.
 */
function spine_has_background_image() {
	return Spine_Theme_Images()->has_post_thumbnail( 'background-image' );
}

/**
 * Retrieve the source URL for a background image attached to a post.
 *
 * @param string $size Size of the thumbnail to retrieve.
 *
 * @return bool|string URL of the image if available. False if not.
 */
function spine_get_background_image_src( $size = 'spine-xlarge_size' ) {
	return Spine_Theme_Images()->get_thumbnail_image_src( 'background-image', $size );
}

/**
 * Wraps functionality inside Spine_Theme_Images to display the thumbnail image
 * configured as one of the thumnbnails in Multiple Post Thumbnails by the theme.
 *
 * @param string $size Thumbnail size.
 */
function spine_the_thumbnail_image( $size = 'spine-thumbnail_size' ) {
	return Spine_Theme_Images()->the_post_thumbnail( 'thumbnail-image', $size );
}

/**
 * Wrapper to determine if the displayed post or page has a thumbnail image assigned.
 *
 * @return bool True if thumbnail exists. False if not.
 */
function spine_has_thumbnail_image() {
	return Spine_Theme_Images()->has_post_thumbnail( 'thumbnail-image' );
}

/**
 * Retrieve the source URL for a thumbnail image attached to a post.
 *
 * @param string $size Size of the thumbnail to retrieve.
 *
 * @return bool|string URL of the image if available. False if not.
 */
function spine_get_thumbnail_image_src( $size = 'spine-thumbnail_size' ) {
	return Spine_Theme_Images()->get_thumbnail_image_src( 'thumbnail-image', $size );
}
